<?php

// Matchmaking queue helpers. Waiting players are the open public lobbies from
// GameListScan.php; these helpers decide which of them a player can be matched
// into and, when none fit, why.
// Needs CardDictionary.php, LegalHeroesHelper.php and HeroPreference.php.

// Lobby heroes are hidden in these formats, so a joiner's own hero filter is ignored there.
function MatchmakingIsCompetitiveFormat($format)
{
  return in_array($format, ['compcc', 'compblitz', 'compllcc', 'compsage', 'shadowcompcc', 'shadowcompllcc', 'shadowcompsage'], true);
}

// Mirrors the shadow ban format swap in APIs/CreateGame.php.
function MatchmakingShadowFormat($format)
{
  if (in_array($format, ['cc', 'futurecc', 'llcc', 'openformatllcc', 'openformatsage'], true)) return 'shadowcc';
  if ($format == 'compcc') return 'shadowcompcc';
  if ($format == 'compllcc') return 'shadowcompllcc';
  if ($format == 'blitz' || $format == 'commoner') return 'shadowblitz';
  if (in_array($format, ['futuresage', 'sage', 'compsage'], true)) return 'shadowcompsage';
  if ($format == 'gage') return 'shadowgage';
  return $format;
}

// Shadow banned players must never see a shadow format name.
function MatchmakingUnshadowFormat($format)
{
  $shadowFormats = [
    'shadowcc' => 'cc', 'shadowcompcc' => 'compcc', 'shadowcompllcc' => 'compllcc',
    'shadowblitz' => 'blitz', 'shadowcompsage' => 'compsage', 'shadowgage' => 'gage',
  ];
  return $shadowFormats[$format] ?? $format;
}

// The GetLegalHeroes() format to check a hero against, or null when the lobby
// format does not restrict heroes that way (limited, precon).
function MatchmakingLegalityFormat($format)
{
  $format = MatchmakingUnshadowFormat($format);
  $checked = [
    'cc', 'compcc', 'futurecc', 'llcc', 'compllcc', 'futurell',
    'blitz', 'compblitz', 'openformatblitz', 'commoner', 'clash',
    'sage', 'compsage', 'futuresage', 'gage', 'open', 'brawl',
  ];
  return in_array($format, $checked, true) ? $format : null;
}

function MatchmakingHeroLegal($heroSetId, $legalityFormat)
{
  static $legalByFormat = [];
  if (!isset($legalByFormat[$legalityFormat])) {
    $legalByFormat[$legalityFormat] = [];
    foreach (GetLegalHeroes($legalityFormat) as $hero) $legalByFormat[$legalityFormat][SetID($hero['heroId'])] = true;
  }
  return isset($legalByFormat[$legalityFormat][$heroSetId]);
}

// Accepts a set ID (as stored for favorite decks and lobby heroes) or a card ID.
function MatchmakingHeroCardId($hero)
{
  $hero = trim((string)$hero);
  if ($hero === '') return '';
  $cardID = preg_match('/^[A-Z]{3}\d{3}$/', $hero) ? SetIDtoCardID($hero) : $hero;
  if (!is_string($cardID) || $cardID === '') return '';
  return DelimStringContains(CardType($cardID), "C") ? $cardID : '';
}

function MatchmakingHeroShortName($cardID)
{
  $name = html_entity_decode(CardName($cardID), ENT_QUOTES, 'UTF-8');
  $comma = strpos($name, ',');
  return $comma === false ? $name : substr($name, 0, $comma);
}

// The learning descriptions the lobby list asks for confirmation before joining.
function MatchmakingIsLearningLobby($description)
{
  $description = html_entity_decode((string)$description, ENT_QUOTES, 'UTF-8');
  return in_array($description, [
    'New player learning the game',
    'Practicing a new hero',
    'MENU.CREATE_GAME.GAME_DESCRIPTIONS.NEW_PLAYER',
    'MENU.CREATE_GAME.GAME_DESCRIPTIONS.NEW_HERO',
  ], true);
}

// One reason per lobby for the summary counts, most important first.
const MATCHMAKING_REASON_ORDER = ['own', 'kicked', 'heroIllegal', 'theyRejectHero', 'youRejectHero', 'learning'];

// Every reason the joiner cannot be matched into one open lobby. An empty list
// means the lobby fits. $joiner: userName, heroCardId, preference (ParseHeroPreference result or null).
function MatchmakingLobbyBlockers($joiner, $lobby)
{
  $codes = [];
  if ($joiner['userName'] !== '' && $lobby['p1uid'] === $joiner['userName']) $codes[] = 'own';
  if ($joiner['userName'] !== '' && WasKickedFromLobby($lobby['gameName'], $joiner['userName'])) $codes[] = 'kicked';

  $heroCardId = $joiner['heroCardId'];
  if ($heroCardId !== '') {
    $legalityFormat = MatchmakingLegalityFormat($lobby['format']);
    if ($legalityFormat !== null && !MatchmakingHeroLegal(SetID($heroCardId), $legalityFormat)) $codes[] = 'heroIllegal';
    // JoinGame.php only enforces the lobby's hero preference in public lobbies.
    if ($lobby['visibility'] == "1" && !HeroPreferenceAllows(ParseHeroPreference($lobby['description']), CardName($heroCardId), CardClass($heroCardId))) {
      $codes[] = 'theyRejectHero';
    }
  }

  if ($joiner['preference'] !== null && !MatchmakingIsCompetitiveFormat($lobby['format']) && $lobby['hero'] !== '') {
    $lobbyHeroCardId = MatchmakingHeroCardId($lobby['hero']);
    if ($lobbyHeroCardId !== '' && !HeroPreferenceAllows($joiner['preference'], CardName($lobbyHeroCardId), CardClass($lobbyHeroCardId))) {
      $codes[] = 'youRejectHero';
    }
  }

  if (MatchmakingIsLearningLobby($lobby['description'])) $codes[] = 'learning';
  return $codes;
}

function MatchmakingPrimaryReason($codes)
{
  foreach (MATCHMAKING_REASON_ORDER as $code) {
    if (in_array($code, $codes, true)) return $code;
  }
  return null;
}
