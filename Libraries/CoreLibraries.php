<?php
function DelimStringContains($str, $find, $partial=false)
{
  if($str == null) return false;
  $arr = explode(",", $str);
  foreach($arr as $item)
  {
    if($partial && str_contains($item, $find)) return true;
    else if($item == $find) return true;
  }
  return false;
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

  $seedString = hash("sha256", $seedString);
  if ($reroll) ++$seedString;
  mt_srand(crc32($seedString));
  $randomSeeded = true;
}