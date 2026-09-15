<?php

function IsInfected($player) {
  $Auras = new Auras($player);
	$auraCount = $Auras->NumAuras();
	for ($i = 0; $i < $auraCount; ++$i) {
		$AuraCard = $Auras->Card($i, true);
		if (SubtypeContains($AuraCard->CardID(), "Disease")) return true;
	}
	return false;
}
