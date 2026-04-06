<?php

function Sharpen($MZIndex, $player, $num=1) {
  $zone = explode("-", $MZIndex)[0];
  $ind = explode("-", $MZIndex)[1] ?? -1;
  if ($ind != -1) {
    switch ($zone) {
      case "MYCHAR":
        $CharacterCard = new CharacterCard($ind, $player);
        if ($CharacterCard->CardID() == "zenith_blade" && SearchCharacterActive($player, "reverent_rerebrace", true)) {
          if (Rerebrace($MZIndex, $player, $num));
            return;
        }
        $CharacterCard->AddCounters($num);
        AddCurrentTurnEffect("hala_bladesaint_of_the_vow", $player, "-", $CharacterCard->UniqueID());
        break;
      default:
        break;
    }
  }
}

function Rerebrace($MZIndex, $player, $num) {
  $message = "";
	$context = "";
	Await($player, "YesNo", message:$message, context:$context, subsequent:0);
}