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

function GetAttackNamedCard() {
	global $CombatChain;
	$buffs = $CombatChain->AttackCard()->StaticBuffs();
	$targetedName = "-";
	foreach (explode(",", $buffs) as $buff) {
		$prefix = explode("|", $buff)[0];
		if ($prefix == "NAMEDCARD")
		$targetedName = explode("|", $buff)[1];
	}
	return $targetedName;
}

static $Diseases = ["frailty", "inertia", "bloodrot_pox"];
static $AllDiseases = ["frailty", "inertia", "bloodrot_pox", "exertion"];
