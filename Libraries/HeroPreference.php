<?php

// Reduces a hero name to letters and digits, so names compare equal despite differences in
// case, punctuation or accents. Lobby descriptions are built from the names in
// LegalHeroesHelper.php, which don't always match CardName() exactly
// (e.g. "Jarl Vetreidi" / "Jarl Vetreiði", "Min, Half Genius" / "Min, Half-Genius").
function HeroNameKey($name) {
  static $letters = [
    'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'ā' => 'a', 'æ' => 'ae',
    'ç' => 'c', 'ð' => 'd', 'đ' => 'd',
    'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ē' => 'e',
    'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ī' => 'i', 'ł' => 'l', 'ñ' => 'n',
    'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o', 'ō' => 'o', 'œ' => 'oe',
    'ß' => 'ss', 'þ' => 'th',
    'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ū' => 'u', 'ý' => 'y', 'ÿ' => 'y',
  ];
  $name = mb_strtolower(html_entity_decode($name, ENT_QUOTES, 'UTF-8'), 'UTF-8');
  return preg_replace('/[^\p{L}\p{N}]+/u', '', strtr($name, $letters)) ?? '';
}

function CheckHeroPreference($character, $gameDescription) {
  $knownClasses = ['assassin', 'brute', 'guardian', 'illusionist', 'mechanologist',
                   'necromancer', 'ninja', 'pirate', 'ranger', 'runeblade', 'warrior', 'wizard'];

  $preferMode = null;
  $namesPart = null;

  if (str_starts_with($gameDescription, 'Looking to play against ')) {
    $after = trim(substr($gameDescription, strlen('Looking to play against ')));
    if ($after !== 'a specific hero' && $after !== 'a specific class') {
      $preferMode = 'include';
      $namesPart = $after;
    }
  } elseif (str_starts_with($gameDescription, 'No interest in playing against ')) {
    $after = trim(substr($gameDescription, strlen('No interest in playing against ')));
    if ($after !== 'specific hero') {
      $preferMode = 'exclude';
      $namesPart = $after;
    }
  }

  if (!$preferMode || !$namesPart) return null;

  $rawTokens = explode(', ', $namesPart);
  $tokens = array_map(function($t) { return strtolower(trim(str_replace(',', '', $t))); }, $rawTokens);
  $candidates = $tokens;
  $tokensCount = count($tokens) - 1;
  for ($i = 0; $i < $tokensCount; $i++) {
    $candidates[] = $tokens[$i] . ' ' . $tokens[$i + 1];
  }

  $isClassBased = !empty(array_intersect($tokens, $knownClasses));

  if ($isClassBased) {
    $heroClasses = explode(',', strtolower(CardClass($character)));
    $matched = !empty(array_intersect($heroClasses, $candidates));
  } else {
    $matched = in_array(HeroNameKey(CardName($character)), array_map('HeroNameKey', $candidates), true);
  }

  if ($preferMode === 'include' && !$matched) {
    return "⚠️ This lobby is looking to play against specific heroes. Your hero does not match the lobby's preferences.";
  }
  if ($preferMode === 'exclude' && $matched) {
    return "⚠️ This lobby has specified they are not interested in playing against your hero.";
  }

  return null;
}
