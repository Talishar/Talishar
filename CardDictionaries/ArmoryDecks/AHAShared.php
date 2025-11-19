<?php

function Sharpen($MZIndex, $player) {
  $zone = explode("-", $MZIndex)[0];
  $ind = explode("-", $MZIndex)[1] ?? -1;
  if ($ind != -1) {
    switch ($zone) {
      case "MYCHAR":
        $char = &GetPlayerCharacter($player);
        ++$char[$ind + 3];
        AddCurrentTurnEffect("hala_bladesaint_of_the_vow", $player, "-", $char[$ind + 11]);
        break;
      default:
        break;
    }
  }
}
