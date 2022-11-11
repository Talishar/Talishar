<?php

function CharacterTakeDamageAbilities($player, $damage, $type)
{
  $char = &GetPlayerCharacter($player);
  $otherPlayer = $player == 1 ? 1 : 2;
  //CR 2.1 6.4.10f If an effect states that a prevention effect can not prevent the damage of an event, the prevention effect still applies to the event but its prevention amount is not reduced. Any additional modifications to the event by the prevention effect still occur.
  $preventable = CanDamageBePrevented($otherPlayer, $damage, $type);
  for ($i = count($char) - CharacterPieces(); $i >= 0; $i -= CharacterPieces()) {
    if($char[$i+1] == 0) continue;
    $remove = 0;
    switch ($char[$i]) {
      case "DYN214":
        if ($damage > 0) {
          if ($preventable) $damage -= 1;
          $remove = 1;
        }
        break;
      case "DYN213":          // HAS TO BE LAST - Until we can order triggers
        if ($damage > 0) {
          if ($preventable) $damage -= 1;
          $remove = 1;
        }
        break;
      default:
        break;
    }
    if ($remove == 1) {
      if (HasWard($char[$i]) && (SearchCharacterActive($player, "DYN213") || $char[$i] == "DYN213") && CardType($char[$i]) != "T") {
        $index = FindCharacterIndex($player, "DYN213");
        $char[$index + 1] = 1;
        GainResources($player, 1);
      }
      DestroyCharacter($player, $i);
    }
  }
  if ($damage <= 0) $damage = 0;
  return $damage;
}

?>
