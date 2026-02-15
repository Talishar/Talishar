# New Developer Guide

This is intended to be a non-comprehensive, living document that goes over how to code new cards.
This guide is *not* intended to document how to implement cards with novel effects or cards that require updates to the game engine. These kinds of effects tend to be a lot harder to implement and frequently require some creativity and deep rules knowledge that can't exactly be documented.

## Old vs New Style
In the past, most cards were added to the game engine in the form of long switch statements where the effects of every card were coded into the engine itself. This may have been easier to code when the cardpool was smaller, but as things have grown its led to logic for cards being scattered all over the repo.
To address this, starting with Super Slam we've started putting all card logic into Classes/CardObjects/{SET}Cards.php. In theory almost all aspects of cards should be able to be put in a single Card object so you can see everything that a card does in one place. The one thing that often doesn't fit in this paradigm are rules-setting cards, as these effects alter how the game-engine functions, and so need to be added to the game engine itself.
This transition to Card objects is still a work in progress, and sometimes there are effects that haven't been converted to a form that an object can immediately use, in which case you can either use the old approach and add the effect to a switch statement in the engine, or (preferably) add a new method to the base class (Classes/Card.php) and call that function right above the switch statement in the engine. This function call in engine typically looks like this:
```
$card = GetClass($card, $player);
if ($card != "-") $card->Method();
```
The "GetClass" function checks if the card has an object associated with it, so that way we don't try to create an object that doesn't exist.
### Card object methods
Here are some common methods frequently used and how they correspond to FaB rules:
`__construct`
All card objects need a construct function. Most of the time this just sets the `$cardID` (which should be the same as the object's name) and `$controller` variables, but sometimes can declare a `$baseCard` or `$archetype` to allow the card to access methods of related cards.
`$baseCard` is frequently used to store methods used by all 3 members of a color cycle, while `$archetype` is for methods used by a wide range of cards with something in common (see `windup` from HVYCards.php).
`PlayAbility`  
This method handle any resolution abilities of a card, which is anything that happens when an attack becomes attacking in the attack step, or when a non-attack resolves.  
`IsPlayRestricted`  
This method returns false when a card does not meet a condition for being played. This is only for a card's own restrictions, not for restrictions imposed by other effects. Frequently it is used to check if there is a legal target and if additional costs can be paid.  
`PayAdditionalCosts`
This method handles everything that happens as a card is put onto the stack, primarily including targeting and paying additional costs.  
`CombatEffectActive`  
Many cards create what are called in the rules "Layer Continuous Effects" that alter the power of attacks on the combat chain or apply other effects. This function returns true if an effect should apply to an attack, and false otherwise. This is *technically* incorrect for effects that specify "the next X you play gets Y" as those effects only check whether they should apply when the card is played and are not re-evaluated, but in most cases this isn't relevant. If it becomes relevant, you can add the effect to `IsLayerContinuousBuff`.  
`EffectPowerModifier`  
Returns how much power an attack should gain from a layer continuous effect if the combat effect is active.
`IsCombatEffectPersistent`
If you don't override this method, it will return false, which will cause the layer continuous effect to disappear after a chain link where CombatEffectActive was true.  
## Decision Queue
A large chunk of Talishar's logic is handled by a system called the DecisionQueue which works by setting up a sequence of operations that will execute as soon as they are able to pending user input. They are used any time a player needs to make a decision, such as targeting.  
The decision queue has 4 primary arguments:
1. the command to be executed
2. the player who will be making any decisions
3. a parameter to be passed as an argument to the command. This can either be pre-set before the decision queue begins executing, or dynamic with a few special characters.
4. subsequent, a 0/1 bit where if set will cause the decision queue to be skipped if a previous decision queue failed/was declined.

What ties decision queues together is the `$lastResult` variable, which will always store whatever the previous decision returned.  
For example, if I run `AddDecisionQueue("FINDINDICES", $this->controller, "ARSENAL");` this will return indices that point out all cards in a players arsenal. If I follow this up with `AddDecisionQueue("MAYCHOOSEARSENAL", $this->controller, "<-", 1);`, the player is given the optional ability to choose a card from the list returned . the "<-" parameter is a special parameter that is replaced by the value of `$lastResult`. If we follow this with `AddDecisionQueue("REMOVEARSENAL", $this->controller, "-", 1);`, the choice from the last decisionqueue is stored as the $lastResult, and gets passed to the REMOVEARSENAL command which then removes the chosen arsenalled card. Because this command has `$subsequent` set to 1, if the player declined the previous MAYCHOOSEARSENAL, this decision queue (and all subsequent queues) will be skipped.

Notably DQs are at their core _ASYNCHRONOUS_ which means that if you have a block of DQs followed by a block of regular code, the DQs will be executed *after* all regular code. If you want to execute a block of regular code after a DQ, then you need to put that code inside a DQ command, commonly by extending the SPECIFICCARD DQ command. See the function SpecificCardLogic for many such examples.

DQs effectively only take in 2 arguments, the `$lastResult` and `$parameter`. DQ commands that use multiple commands will store them within the `$parameter` string, separated by some delimiter, usually "," and "-", but we haven't been the most consistent about which delimiter to use.

### Common DQ Commands
#### MULTIZONEINDICES
A DQ command that wraps the SearchMultizone function. This is used to search for objects that match certain parameters. It returns a comma separated list (string) of MultiZone Indices which can then be used as `$lastResult` in a subsequent DQ. See the Search Syntax section for documentation on how to search.
#### (MAY)CHOOSEMULTIZONE
A DQ command that takes in a comma separated list of MultiZone indices and presents them to the player to choose one. It returns the MultiZone Index of the chosen object. MAYCHOOSEMULTIZONE will allow the player to pass on the decision.
#### SETDQCONTEXT
When a DQ shows up offering a decision, there will be a text box to describe what action the player is taking. By calling SETDQCONTEXT before such a DQ, it will set the text box to whatever parameter is passed to SETDQCONTEXT.
#### MZREMOVE
Takes in a MultiZone Index, clears the object from its location, and returns the `$cardID` of the object. This will NOT put the object into the graveyard, you need to follow it up with a DQ such as ADDDISCARD to put the object in its new zone.
#### SETLAYERTARGET
Takes in a MultiZone Index and assigns it as the target to the topmost layer on the stack that has the same `$cardID` as the parameter. In most cases it will automatically convert the target to a unique identifier. This command has a lot of spaghetti in it right now, but you probably won't need to tangle with its inner workings much.
#### ELSE
Used to encode conditional logic with only DQs. If you add an ELSE DQ (with the `$subsequent` argument set to false) after a DQ block, then if at any point in the DQ block a PASS was returned, such as by players declining a MAYCHOOSEMULTIZONE, a DQ block after ELSE will be executed. If PASS is never returned by the above block, the below block will be skipped.
#### SPECIFICCARD
Used to execute regular code after a DQ, typically for cards with specific logic that they are the only card that uses. The parameter is a string that identifies which card's logic to use.
#### PASSPARAMETER
Takes in the `$parameter` argument and returns it unchanged as the `$lastResult` for the next DQ.

## Await
Await is a wrapper around decision queues that should hopefully clean up a lot of the pain points behind writing decision queues. Notably decision queues are heavily reliant on the $lastResult variable which can sometimes be obtuse and require the developer's focus on tracking how $lastResult is changed by an executing DQ. Instead Await heavily leans on $dqVars, a global associative array that tracks any number of named variables that can be used by Awaits. Some benefits are that this won't have an enormous switch statement of DQ commands, just a series of functions, and specific card Await functions can be stored in the card object with the card's other code.

`Await($player, $function,  $returnName="LASTRESULT", $lastResultName="LASTRESULT", $subsequent=1, $final=false, ...$args)`

Await takes as arguments:
1. the `$player` who may need to make decisions or will be affected by the Await statement
2. the `$function` to execute. This is a string that refers to a function (located for now in AwaitEffects.php). The functions here all have their name end in "Await" to indicate that they are only to be called as part of an Await execution. You don't include "Await" as part of the function name in the call to Await. Each await function is looking for specific named variables. If the function name is a cardID, it will attempt to call the `SpecificLogic` of the cards object if it exists. This replaces "SPECIFICCARD" in the old DQs
3. `$returnName` is what to name the variable that the Await function returns, is used to make sure that the variable is named in such a way that the next Await recognizes it.
4. `$lastResultName` is used to maintain backwards compatibility. If you precede an Await with a regular decision queue, you can use this variable to rename the `$lastResult` returned by the DQ so that the await recognizes it.
5. `$subsequent` is the variable to set if the await can be passed through. Works the same as subsequent in DQs, just here it defaults to true rather than false.
6. `$final` is a boolean that you set to true for the last Await in a sequence that clears the `$dqVars` to make sure future Awaits don't end up reading stale variables
7. `...$args` is any number of keyword arguments. These take the place of `$parameter` from DQs and serve to pass values to Await functions that aren't dependant on previous Awaits

### Example
```
$xVal = $resourcesPaid/2;
    $numRevealed = 3 + $xVal;
    WriteLog(CardLink($this->cardID, $this->cardID) . " reveals " . $numRevealed . " cards.");
    Await($this->controller, "DeckTopCards", "cardIDs", number:$numRevealed, subsequent:false);
    Await($this->controller, "RevealCards");
    Await($this->controller, $this->cardID, mode:"choose_cards");
    Await($this->controller, "MultiChooseDeck", "indices");
    Await($this->controller, "MultiRemoveDeck", "cardIDs");
    Await($this->controller, "MultiAddHand");
    Await($this->controller, $this->cardID, mode:"deal_arcane", target:$target);
    Await($this->controller, "ShuffleDeck", final:true);
```

## MultiZone Indices
A common way to reference cards is with a a MultiZone Index (MZIndex). These indices are formated as a location followed by either an index in that location or a unique id. MZIndices are always *relative* to a player. So MYCHAR-0 for player 1 will refer to player1's hero, and THEIRCHAR-0 will refer to their opponents. Any zone that can be owned by a player has both a "MY" and a "THEIR" version. If the MZIndex will be used immediately after being identified, directly using the index is fine. If there is a priority window between the MZIndex being generated and being used, it is important to convert it to use a unique ID instead of a numerical index. You can convert between the two formats with `CleanTarget` and `CleanTargetToIndex`.

## Generated Code
A large part of the process of coding new cards is handled automatically by the script zzCardCodeGenerator.php. This script pulls down a json dataset from fabcube and automatically populates a card's stats, types, subtypes, color, cost, name, and many keywords such as blade break, go again, and arcane barrier. Most of the time you won't need to code any of these.

Note: sometimes a card is simple enough that every part of it gets automatically generated. In these cases, we still recommend creating an object that only has the `__construct` method defined. When a player loads a deck, it will check for each card in their deck if the card has been implemented by checking if it has a name generated by zzCardCodeGenerator.php and if a class exists for it. This check is only done for cards from unreleased sets.

## Searching
Most Searching is done with the `SearchMultizone` function (or equivalently MULTIZONEINDICES for DQs), and this function's argument has its own syntax to learn.

example: `"MYDISCARD:cost<2;type=AA&THEIRDISCARD:cost<2;type=AA"`. This search will find all attack action cards in either players' graveyard with cost less than 2.

All searches start with a zone to search, encoded the same as the zone in a MultiZone index. Conditions on searches in this zone can be added after a ":", and you can encode multiple conditions that must all be satisfied in that zone by separating them with a ";". An "&" can connect an additional search, and the results of all searches will be appended together.

### Zones
- CHAR
  Hero, Equipment, and Weapons
- ALLY
- ARS (also ARSENAL, inconsistent)
- AURAS
- BANISH
- ITEMS
- LAYER
  Anything on the stack, shared
- HAND
- DISCARD
  graveyard
- PERM
  permanents
- PITCH
- DECK
- SOUL
- LANDMARK
  shared
- CC (also COMBATCHAINLINK, inconsistent)
  Combat Chain, only the active link
- PASTCHAINLINKS
- COMBATCHAINATTACKS
  attacks on the current and previous chain links, overlaps with CC and PASTCHAINLINKS
- PRELAYERS
  triggers before they are put onto the stack, you shouldn't need to interact with this zone.
- CURRENTTURNEFFECTS
  effects that "float" on the left side of the screen, represent layer continuous effects and perform various other book-keeping functions. Shared