<?php

function CharacterTakeDamageAbility($player, $index, $damage, $preventable)
{
  $char = &GetPlayerCharacter($player);
  $otherPlayer = $player == 1 ? 1 : 2;
  //CR 2.1 6.4.10f If an effect states that a prevention effect can not prevent the damage of an event, the prevention effect still applies to the event but its prevention amount is not reduced. Any additional modifications to the event by the prevention effect still occur.
  $type = "-";//Add this if it ever matters
  $preventable = CanDamageBePrevented($otherPlayer, $damage, $type);
  switch ($char[$index]) {
    case "DYN213":
      if ($damage > 0) {
        if ($preventable) $damage -= 1;
        $remove = 1;
      }
      break;
    case "DYN214":
      if ($damage > 0) {
        if ($preventable) $damage -= 1;
        $remove = 1;
      }
      break;
    default:
      break;
  }
  if ($remove == 1) {
    if (HasWard($char[$index]) && (SearchCharacterActive($player, "DYN213") || $char[$index] == "DYN213") && CardType($char[$index]) != "T") {
      $kimonoIndex = FindCharacterIndex($player, "DYN213");
      $char[$kimonoIndex + 1] = 1;
      GainResources($player, 1);
    }
    DestroyCharacter($player, $index);
  }
  if ($damage <= 0) $damage = 0;
  return $damage;
}


?>
