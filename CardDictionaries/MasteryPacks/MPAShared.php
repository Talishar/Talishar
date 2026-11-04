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

function NumDaggerHits() {
	global $combatChainState, $CCS_FlickedDamage, $chainLinks, $chainLinkSummary;
    $numDaggerHits = 0;
    $count = count($chainLinks);
    $chainLinkSummaryPieces = ChainLinkSummaryPieces();
    for($i=0; $i<$count; ++$i)
    {
      if(SubtypeContains($chainLinks[$i][0], "Dagger") && $chainLinkSummary[$i*$chainLinkSummaryPieces] > 0) 
        ++$numDaggerHits;
    }
    $numDaggerHits += GetCombatChainState($CCS_FlickedDamage);
	return $numDaggerHits;
}

function CureThem($player, $final=false) {
	Await($player, "MultiZoneIndices", "indices", search:"THEIRAURAS:subtype=Disease", subsequent:0);
	Await($player, "ChooseMultiZone", "MZInd", context:"Kindly cure your opponent's disease");
	Await($player, "MZDestroy", final:$final);
}

function GetTargetsAssassinAA($player) {
	$attacks = TargetAttack($player);
    $targets = [];
    foreach($attacks as $attack) {
      $obj = MZIndexToObject($player, $attack);
      if (TypeContains($obj->ID(), "AA") && ClassContains($obj->ID(), "ASSASSIN", $player))
        $targets[] = $attack;
    }
    return implode(",", $targets);
}

static $Diseases = ["frailty", "inertia", "bloodrot_pox"];
static $AllDiseases = ["frailty", "inertia", "bloodrot_pox", "exertion"];
