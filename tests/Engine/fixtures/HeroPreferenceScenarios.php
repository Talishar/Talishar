<?php

// Runs in its own PHP process, because tests/Engine stubs out functions that the real card dictionary defines.
// Checks every hero in LegalHeroesHelper.php against the real card data and prints the problems found as JSON.

error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', '1');
chdir(dirname(__DIR__, 3) . '/APIs');

include_once '../CardDictionary.php';
include_once '../Libraries/LegalHeroesHelper.php';
include_once '../Libraries/HeroPreference.php';

global $ALL_HEROES_OF_RATHE;

// CreateGame.php escapes the description before saving it.
function LobbyDescription($prefix, $names) {
  return htmlspecialchars($prefix . implode(', ', $names), ENT_QUOTES);
}

$results = [
  'unknownHeroIds' => [],
  'blockedFromOwnLobby' => [],
  'allowedIntoAvoidingLobby' => [],
  'blockedByRealName' => [],
  'namesThatCollide' => [],
];

$namesByKey = [];
foreach ($ALL_HEROES_OF_RATHE as $hero) {
  $id = $hero['heroId'];
  $name = $hero['name'];
  $realName = CardName($id);
  if ($realName == "" || CardType($id) != "C") {
    $results['unknownHeroIds'][] = $id;
    continue;
  }
  if (CheckHeroPreference($id, LobbyDescription('Looking to play against ', [$name])) !== null) $results['blockedFromOwnLobby'][] = $id;
  if (CheckHeroPreference($id, LobbyDescription('No interest in playing against ', [$name])) === null) $results['allowedIntoAvoidingLobby'][] = $id;
  if (CheckHeroPreference($id, LobbyDescription('Looking to play against ', [$realName])) !== null) $results['blockedByRealName'][] = $id;
  $namesByKey[HeroNameKey($realName)][$realName] = true;
}
foreach ($namesByKey as $names) {
  if (count($names) > 1) $results['namesThatCollide'][] = array_keys($names);
}

$allowed = fn($hero, $description) => CheckHeroPreference($hero, htmlspecialchars($description, ENT_QUOTES)) === null;
$results['cases'] = [
  'otherHeroBlockedFromJarlLobby' => !$allowed('dorinthea_ironsong', 'Looking to play against Jarl Vetreidi'),
  'otherHeroAllowedWhenJarlAvoided' => $allowed('dorinthea_ironsong', 'No interest in playing against Jarl Vetreidi'),
  'jarlAllowedInMultiHeroLobby' => $allowed('jarl_vetreidi', 'Looking to play against Dorinthea Ironsong, Jarl Vetreidi'),
  'commaHeroAllowedInMultiHeroLobby' => $allowed('min_half_genius', 'Looking to play against Jarl Vetreidi, Min, Half Genius'),
  'classLobbyAllowsMatchingClass' => $allowed('dorinthea_ironsong', 'Looking to play against Warrior'),
  'classLobbyBlocksOtherClass' => !$allowed('jarl_vetreidi', 'Looking to play against Warrior'),
  'placeholderHasNoRestriction' => $allowed('jarl_vetreidi', 'Looking to play against a specific hero'),
  'unrelatedDescriptionHasNoRestriction' => $allowed('jarl_vetreidi', 'Game #123'),
];

$results['keys'] = [
  'Jarl Vetreiði' => HeroNameKey('Jarl Vetreiði'),
  'JARL VETREIÐI' => HeroNameKey('JARL VETREIÐI'),
  'Jarl Vetreiđi' => HeroNameKey('Jarl Vetreiđi'),
  'Maxx &#039;The Hype&#039; Nitro' => HeroNameKey('Maxx &#039;The Hype&#039; Nitro'),
  'Min, Half-Genius' => HeroNameKey('Min, Half-Genius'),
  'Þórdís Ærø' => HeroNameKey('Þórdís Ærø'),
  'α-01, First Cogwarden' => HeroNameKey('α-01, First Cogwarden'),
];

echo json_encode($results, JSON_UNESCAPED_UNICODE);
