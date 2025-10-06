<?php
function DelimStringContains($str, $find, $partial=false)
{
  if($str == null) return false;
  $arr = explode(",", $str);
  for($i=0; $i<count($arr); ++$i)
  {
    if($partial && str_contains($arr[$i], $find)) return true;
    else if($arr[$i] == $find) return true;
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
  if(count($layers) > 0) for($i=0; $i<count($layers); ++$i) $seedString .= $layers[$i];
  if(count($combatChain) > 0) for($i=0; $i<count($combatChain); ++$i) $seedString .= $combatChain[$i];

  $char = &GetPlayerCharacter(1);
  for($i=0; $i<count($char); ++$i) {
    if ($i % CharacterPieces() == 9) continue;
    $seedString .= $char[$i];
  }
  $char = &GetPlayerCharacter(2);
  for($i=0; $i<count($char); ++$i) {
    if ($i % CharacterPieces() == 9) continue;
    $seedString .= $char[$i];
  }

  $banish = &GetBanish(1);
  for($i=0; $i<count($banish); ++$i) $seedString .= $banish[$i];
  $banish = &GetBanish(2);
  for($i=0; $i<count($banish); ++$i) $seedString .= $banish[$i];

  $discard = &GetDiscard(1);
  for($i=0; $i<count($discard); ++$i) $seedString .= $discard[$i];
  $discard = &GetDiscard(2);
  for($i=0; $i<count($discard); ++$i) $seedString .= $discard[$i];

  $deck = &GetDeck(1);
  for($i=0; $i<count($deck); ++$i) $seedString .= $deck[$i];
  $deck = &GetDeck(2);
  for($i=0; $i<count($deck); ++$i) $seedString .= $deck[$i];

  $seedString = hash("sha256", $seedString);
  if ($reroll) ++$seedString;
  mt_srand(crc32($seedString));
  $randomSeeded = true;
}
?>
