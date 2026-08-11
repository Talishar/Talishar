<?php

function IsInfected($player) {
  $Auras = new Auras($player);
	for ($i = 0; $i < $Auras->NumAuras(); ++$i) {
		$AuraCard = $Auras->Card($i, true);
		if (SubtypeContains($AuraCard->CardID(), "Disease")) return true;
	}
	return false;
}