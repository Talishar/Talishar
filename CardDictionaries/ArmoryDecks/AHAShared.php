<?php

function Sharpen($MZIndex, $player, $num=1) {
  global $CurrentTurnEffects;
  $zone = explode("-", $MZIndex)[0];
  $ind = explode("-", $MZIndex)[1] ?? -1;
  for ($i = $CurrentTurnEffects->NumEffects() - 1; $i >= 0 ; --$i) {
    $Effect = $CurrentTurnEffects->Effect($i, true);
    if ($Effect->PlayerID() != $player) continue;
    switch ($Effect->EffectID()) {
      case "swordmasters_path_red":
      case "swordmasters_path_blue":
        ++$num;
        $Effect->Remove();
        break;
      default:
        break;
    }
  }
  if ($ind != -1) {
    switch ($zone) {
      case "MYCHAR":
        $CharacterCard = new CharacterCard($ind, $player);
        if ($CharacterCard->CardID() == "zenith_blade" && SearchCharacterActive($player, "reverent_rerebrace", true)) {
          if (Rerebrace($MZIndex, $player, $num))
            return;
					SearchCurrentTurnEffects("reverent_rerebrace", $player, true);
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
	if (SearchCurrentTurnEffects("reverent_rerebrace", $player)) // the replacement effect has already been applied and declined
		return false;
	$Character = new PlayerCharacter($player);
	$CharacterCard = $Character->FindCardID("reverent_rerebrace");
  $message = "if_you_want_to_sharpen_an_additional_time";
	$context = "Choose if you want to destroy " . CardLink("reverent_rerebrace") . " to sharpen an additional time";
	Await($player, "YesNo", message:$message, context:$context, subsequent:0);
	Await($player, "MZDestroy", MZInd: "MYCHAR-" . $CharacterCard->Index());
	Await($player, "Sharpen", MZindex: $MZIndex, num: $num+1, final:true);
	Await($player, "Else", subsequent:0);
	Await($player, "AddCurrentTurnEffect", effectID: "reverent_rerebrace");
	Await($player, "Sharpen", MZindex: $MZIndex, num: $num, final:true);
	return true;
}