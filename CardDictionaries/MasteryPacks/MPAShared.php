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

function CureThem($player, $target="-", $final=false, $may=false) {
	$zone = $target == "self" ? "MYAURAS" : "THEIRAURAS";
	$context = $target == "self" ? "Cure yourself" : "Kindly cure your opponent's disease";
	Await($player, "MultiZoneIndices", "indices", search:"$zone:subtype=Disease", subsequent:0);
	Await($player, "ChooseMultiZone", "MZInd", context:$context, may:$may);
	Await($player, "MZDestroy", final:$final);
}

function GetTargetsAssassinAA($player, $contract=false, $stealth=false) {
	$attacks = TargetAttack($player);
    $targets = [];
    foreach($attacks as $attack) {
		$obj = MZIndexToObject($player, $attack);
		$attackID = $obj->ID();
		if ($contract && ContractType($attackID) == "") continue;
		if ($stealth && !HasStealth($attackID)) continue;
		if (TypeContains($attackID, "AA") && ClassContains($attackID, "ASSASSIN", $player))
			$targets[] = $attack;
    }
    return implode(",", $targets);
}

function DeathTouch($player, $cardID) {
	global $Diseases, $defPlayer;
	$indices = [];
	foreach($Diseases as $disease)
		$indices[] = "CARDID-$disease";
	$indices = implode(",", $indices);
	$context = "infect your opponent";
	Await($player, "ChooseMultiZone", "cardID", indices:$indices, context:$context, subsequent:0);
	Await($defPlayer, "PlayAura", effectController:$player, effectSource:$cardID, effectAgent:$player, final:true);
}

static $Diseases = ["frailty", "inertia", "bloodrot_pox"];
static $AllDiseases = ["frailty", "inertia", "bloodrot_pox", "exertion"];
