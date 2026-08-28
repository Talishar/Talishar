<?php
function DelimStringContains($str, $find, $partial=false)
{
  if($str == null) return false;
  // Fast path: most calls pass a single value string with no delimiter
  // Skip the explode()/array. This function is called on so many card-ability check in the engine
  if (strpos($str, ",") === false) {
    return $partial ? str_contains($str, $find) : $str == $find;
  }
  $arr = explode(",", $str);
  foreach($arr as $item)
  {
    if($partial && str_contains($item, $find)) return true;
    else if($item == $find) return true;
  }
  return false;
}

function StringContainsWholeWords($str, $find)
{
  if ($str === null || $find === null || $find === "") return false;

  static $patternCache = [];
  if (!array_key_exists($find, $patternCache)) {
    $wordCharacter = '[\p{L}\p{M}\p{N}]';
    if (!preg_match_all('/' . $wordCharacter . '+/u', $find, $matches)) $patternCache[$find] = null;
    else {
      $words = array_map(fn($word) => preg_quote($word, '/'), $matches[0]);
      $patternCache[$find] = '/(?<!' . $wordCharacter . ')' . implode('[^\p{L}\p{M}\p{N}]+', $words) . '(?!' . $wordCharacter . ')/iu';
    }
  }

  $pattern = $patternCache[$find];
  if ($pattern === null) return false;
  return preg_match($pattern, $str) === 1;
}

function GetRandom($low=-1, $high=-1, $reroll=false)
{
  global $randomSeeded;
  if(!$randomSeeded) SeedRandom($reroll);
  if($low == -1) return mt_rand();
  return mt_rand($low, $high);
}

function SeedRandom($reroll=false)
{
  global $randomSeeded, $currentTurn, $turn, $currentPlayer, $layers, $combatChain;
  $seedString = $currentTurn . implode("", $turn) . $currentPlayer;
  if(!empty($layers)) $seedString .= implode("", $layers);
  if(!empty($combatChain)) $seedString .= implode("", $combatChain);

  $characterPieces = CharacterPieces();
  $char = &GetPlayerCharacter(1);
  foreach($char as $i => $value) {
    if ($i % $characterPieces != 9) $seedString .= $value;
  }
  $char = &GetPlayerCharacter(2);
  foreach($char as $i => $value) {
    if ($i % $characterPieces != 9) $seedString .= $value;
  }

  $seedString .= implode("", GetBanish(1));
  $seedString .= implode("", GetBanish(2));
  $seedString .= implode("", GetDiscard(1));
  $seedString .= implode("", GetDiscard(2));
  $seedString .= implode("", GetDeck(1));
  $seedString .= implode("", GetDeck(2));
  if($reroll) $seedString .= "|reroll";

  $seedString = hash("sha256", $seedString);
  mt_srand(crc32($seedString));
  $randomSeeded = true;
}

function AcquireGameActionLock(string $gameName, ?string $gamesRoot = null)
{
  if (!IsGameNameValid($gameName)) return false;

  $gamesRoot ??= dirname(__DIR__) . DIRECTORY_SEPARATOR . "Games";
  $gameDirectory = $gamesRoot . DIRECTORY_SEPARATOR . $gameName;
  if (!is_dir($gameDirectory)) return false;

  $lockHandler = fopen($gameDirectory . DIRECTORY_SEPARATOR . "action.lock", "c");
  if ($lockHandler === false) return false;
  if (!flock($lockHandler, LOCK_EX)) {
    fclose($lockHandler);
    return false;
  }

  return $lockHandler;
}

function ReleaseGameActionLock($lockHandler): void
{
  if (!is_resource($lockHandler)) return;
  flock($lockHandler, LOCK_UN);
  fclose($lockHandler);
}
