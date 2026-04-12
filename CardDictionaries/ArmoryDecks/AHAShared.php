<?php

function Sharpen($MZIndex, $player, $num=1) {
  global $CurrentTurnEffects;
  $zone = explode("-", $MZIndex)[0];
  $ind = explode("-", $MZIndex)[1] ?? -1;
  for ($i = $CurrentTurnEffects->NumEffects() - 1; $i >= 0 ; --$i) {
    $Effect = $CurrentTurnEffects->Effect($i, true);
    if ($Effect->PlayerID() != $player) continue;
    switch ($Effect->EffectID()) {
      case "swordmasters_path_red-SHARP":
      case "swordmasters_path_blue-SHARP":
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
        if (is_numeric($ind))
          $CharacterCard = new CharacterCard($ind, $player);
        else {
          $Character = new PlayerCharacter($player);
          $CharacterCard = $Character->FindCardUID($ind);
        }
        if ($CharacterCard->CardID() == "zenith_blade" && SearchCharacterActive($player, "reverent_rerebrace", true)) {
          if (Rerebrace($MZIndex, $player, $num))
            return;
					SearchCurrentTurnEffects("reverent_rerebrace", $player, true);
        }
        $CharacterCard->AddPowerCounters($num);
        if ($CurrentTurnEffects->FindSpecificEffect("SHARPEN", $CharacterCard->UniqueID())->Index() == -1)
          AddCurrentTurnEffect("SHARPEN", $player, "-", $CharacterCard->UniqueID());
        break;
      default:
        break;
    }
  }
}

function Rerebrace($MZIndex, $player, $num) {
	if (SearchCurrentTurnEffects("reverent_rerebrace", $player)) // the replacement effect has already been applied and declined
		return false;
  $message = "if_you_want_to_sharpen_an_additional_time";
	$context = "Choose if you want to destroy " . CardLink("reverent_rerebrace") . " to sharpen an additional time";
  $Sword = MZIndexToObject($player, $MZIndex);
  $uid = $Sword->UniqueID(); // need to use uid to avoid reindexing issues
	Await($player, "YesNo", "choice", message:$message, context:$context, subsequent:0, noPass:false);
	Await($player, "reverent_rerebrace", MZIndex: "MYCHAR-$uid", num: $num, final:true);
	return true;
}