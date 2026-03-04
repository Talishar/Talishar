# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Talishar is the PHP backend game engine for a browser-based Flesh and Blood card game platform. The frontend (Talishar-FE, TypeScript/React) lives in a sibling directory. The game state is file-based (not DB-driven), with Redis caching and MySQL for user accounts.

## Development Commands

```bash
# Start/stop (Docker)
bash start.sh          # Setup + docker compose up -d
bash stop.sh           # docker compose down

# Lint
make lint              # PHP 8.1 linting via Docker

# Tests (PHPUnit 10)
composer install --dev
./vendor/bin/phpunit                           # All tests
./vendor/bin/phpunit --testsuite "Security Tests"  # Specific suite
./vendor/bin/phpunit --testsuite "Validation Tests"
./vendor/bin/phpunit --testsuite "Business Logic Tests"
```

**Ports:** Web: 8080, PhpMyAdmin: 5001, Redis: 6382, Xdebug: 9003

## Architecture

### Game State Flow
```
Frontend → ProcessInput.php / ProcessInputAPI.php (validates input)
  → ParseGamestate.php (loads state from /Games/{gameName}/GameFile.txt)
  → GameLogic.php / CardLogic.php / Ability files (executes rules)
  → WriteGamestate.php (persists state to file)
  → GetNextTurn.php → BuildGameState.php (serializes JSON response to frontend)
```

### Core Game Engine Files (root level)
- **GetNextTurn.php** — Main API endpoint returning game state JSON
- **ProcessInput.php / ProcessInputAPI.php** — Input processing and routing
- **GameLogic.php** — Core game mechanics and turn flow
- **CoreLogic.php** — Essential engine functions
- **Constants.php** — Zone definitions (HAND, BANISH, GRAVEYARD, PLAY, DECK), player IDs
- **CardDictionary.php** — Card data lookup (~211KB)
- **CardLogic.php** — Card ability implementations (~223KB)
- **CombatChain.php** — Combat resolution (~89KB)

### Ability Files (root level, organized by card type)
CharacterAbilities.php, AuraAbilities.php, AllyAbilities.php, ItemAbilities.php, WeaponLogic.php, LandmarkAbilities.php, PermanentAbilities.php, CurrentEffectAbilities.php

### Key Directories
- **CardDictionaries/** — Card definitions by set (29 sets). Card stats, types, keywords.
- **Classes/** — OOP architecture. `Card.php` is the base class.
- **Classes/CardObjects/** — Set-specific card implementations (e.g., `HVYCards.php`). This is where new cards go.
- **APIs/** — ~40 REST API endpoints for the frontend
- **Libraries/** — Utility functions (validation, HTTP, caching, CSRF, networking)
- **DecisionQueue/** — Async decision queue system (AwaitEffects.php, DecisionQueueEffects.php)
- **AccountFiles/** — User auth (OAuth via Metafy/Patreon), signup, sessions
- **Database/** — Schema and migrations
- **GeneratedCode/CompiledCode/** — Auto-generated card code

## Card Implementation Pattern

**New cards go in `Classes/CardObjects/{SET}Cards.php`** — not scattered in switch statements.

Use `zzCardCodeGenerator.php` to auto-generate card stats from FabCube JSON. Then create a Card object with these key methods:

- **`__construct`** — Sets `$cardID`, `$controller`. Optionally `$baseCard` (for color cycles) or `$archetype` (for shared patterns like `windup`).
- **`PlayAbility`** — Resolution abilities (attack becomes attacking, non-attack resolves)
- **`IsPlayRestricted`** — Returns false when the card's own play conditions aren't met
- **`PayAdditionalCosts`** — Targeting and costs when card goes on stack
- **`CombatEffectActive`** — Returns true if a layer continuous effect should apply
- **`EffectPowerModifier`** — Power bonus from layer continuous effects

**Methods to avoid unless specifically needed:**
- **`SpecialType()`** — Only needed when coding cards before FabCube database is updated. If the card exists in FabCube/generated dictionaries, do NOT add this method.
- **`ArcaneDamage()` / `ActionsThatDoArcaneDamage()`** — Only for cards whose **resolution abilities** deal arcane damage (so Blaze can detect it). Do NOT use for cards that deal arcane via `ProcessAbility` (ability mode).

To hook new methods into the engine, add above the relevant switch statement:
```php
$card = GetClass($card, $player);
if ($card != "-") $card->Method();
```

## Decision Queue & Await

The **Decision Queue** handles async player decisions (targeting, choices). DQs are asynchronous — code after DQ blocks runs *before* the DQs execute.

**Await** is the preferred wrapper around DQs. Uses named variables (`$dqVars`) instead of tracking `$lastResult`:
```php
Await($player, "DeckTopCards", "cardIDs", number:3, subsequent:false);
Await($player, "RevealCards");
Await($player, $this->cardID, mode:"choose_cards");
Await($player, "ShuffleDeck", final:true);  // final:true clears $dqVars
```

Key DQ commands: `MULTIZONEINDICES`, `CHOOSEMULTIZONE`, `SETDQCONTEXT`, `MZREMOVE`, `SETLAYERTARGET`, `ELSE`, `SPECIFICCARD`, `PASSPARAMETER`

## MultiZone Indices

Cards are referenced as `{ZONE}-{index}`, relative to a player: `MYCHAR-0` = player's hero, `THEIRCHAR-0` = opponent's hero. Convert between index and unique ID with `CleanTarget` / `CleanTargetToIndex`.

**Search syntax** (for `SearchMultizone` / `MULTIZONEINDICES`):
```
"MYDISCARD:cost<2;type=AA&THEIRDISCARD:cost<2;type=AA"
```
Zone, then `:` for conditions, `;` separates AND conditions, `&` combines searches across zones.

**Zones:** CHAR, ALLY, ARS/ARSENAL, AURAS, BANISH, ITEMS, LAYER, HAND, DISCARD, PERM, PITCH, DECK, SOUL, LANDMARK, CC/COMBATCHAINLINK, PASTCHAINLINKS, COMBATCHAINATTACKS, CURRENTTURNEFFECTS

## ClassState Tracking Pattern

To track per-turn game events (e.g., "if you've destroyed X this turn"), use **ClassState** variables:

1. **`Constants.php`** — Define constant (next sequential number after `$CS_NumWeaponsActivated = 115`), add to `global` declaration in `ResetMainClassState()`, and initialize to 0 in the same function.
2. **`MenuFiles/StartHelper.php`** — Append ` 0` to the class state string on line 35 (one value per constant).
3. **Trigger location** — Call `IncrementClassState($player, $CS_YourConstant)` where the event occurs (e.g., `AuraAbilities.php:DestroyAura()` for aura destruction).
4. **Check** — `GetClassState($player, $CS_YourConstant) > 0` in card logic.

Existing examples: `$CS_NumSeismicSurgeDestroyed`, `$CS_NumRedPlayed`, `$CS_NumWeaponsActivated`.

## Modal Choose-1 Pattern (BUTTONINPUT + Await)

For "choose 1" effects on attack cards, use BUTTONINPUT with Await:
```php
// In PlayAbility — queue the choice
AddDecisionQueue("SETDQCONTEXT", $player, "Choose a mode for " . CardLink($cardID, $cardID));
AddDecisionQueue("BUTTONINPUT", $player, "Option_A,Option_B,Option_C");
AddDecisionQueue("SHOWMODES", $player, $cardID, 1);
Await($player, $cardID, final:true);

// In SpecificLogic — resolve the choice
global $dqVars;
switch ($dqVars["LASTRESULT"]) {
  case "Option_A": /* effect */ break;
  case "Option_B": /* effect */ break;
}
```

Button labels use underscores as spaces. `SHOWMODES` logs the player's choice. Use `final:true` since no further DQ vars are needed.

## CurrentTurnEffect with Suffixed IDs

When a card creates multiple possible effects (e.g., modal choices for +power or go again), use **suffixed card IDs** to distinguish them:
```php
// In SpecificLogic — create effect with suffix
AddCurrentTurnEffect($this->cardID . "-BUFF", $this->controller);
AddCurrentTurnEffect($this->cardID . "-GOAGAIN", $this->controller);
```

The engine strips the suffix and routes to the card class, passing the suffix as `$parameter`/`$param`. Implement methods that check the suffix:
```php
function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
  return $parameter == "BUFF" || $parameter == "GOAGAIN";
}
function EffectPowerModifier($param, $attached = false) {
  if ($param == "BUFF") return 2;
  return 0;
}
function CurrentEffectGrantsGoAgain($param) {
  return $param == "GOAGAIN";
}
```

**Key points:**
- `CombatEffectActive` ties the effect to the current attack (cleans up when chain link closes)
- Do NOT use `GiveAttackGoAgain()` directly — use `CurrentEffectGrantsGoAgain` via the effect system instead
- Do NOT return `true` unconditionally from `CombatEffectActive` — always check `$parameter`

## Conventions

- **Card IDs:** lowercase with underscores (e.g., `fyendals_spring_tunic`, `command_and_conquer_red`)
- **Player-relative zones:** `MY`/`THEIR` prefix
- **State variables:** `$currentPlayer` (who has priority, 1 or 2), `$turn[0]` (phase: M=Main, A=Action, D=Defense)
- **Session handling:** Session lock is released immediately after capturing data to prevent deadlock
- **Container path mapping:** Project root maps to `/var/www/html/game` in Docker
