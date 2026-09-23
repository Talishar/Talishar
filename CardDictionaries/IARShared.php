<?php

function RunechantIndicesAwait($player) {
	$inds = SearchAurasForCard("runechant", $player, false);
	if ($inds != "") {
		$MZInds = [];
		$includedRunechants = [];
		$inds = $inds != "" ? explode(",", $inds) : [];
		foreach ($inds as $ind) {
			$Aura = new AuraCard($ind, $player);
			$choiceKey = "$player-" . $Aura->CardID();
			if (!in_array($choiceKey, $includedRunechants)) {
				$MZInds[] = "MYAURAS-$ind";
				$includedRunechants[] = $choiceKey;
			}
		}
		return implode(",", $MZInds);
	}
	return "PASS";
}

function Usurp($cardID, $player, $from) {
	if (!IsActivated($cardID, $from)) {
		$otherPlayer = 3 - $player;
		$inds = SearchAurasForCard("runechant", $player, false);
		$theirInds = SearchAurasForCard("runechant", $otherPlayer, false);
		if ($inds != "" || $theirInds != "") {
			$MZInds = [];
			$includedRunechants = [];
			$inds = $inds != "" ? explode(",", $inds) : [];
			foreach ($inds as $ind) {
				$Aura = new AuraCard($ind, $player);
				$choiceKey = "$player-" . $Aura->CardID();//$Aura->CardID() == "runechant" ? "runechant" : $player . "-" . $Aura->CardID();
				if (!in_array($choiceKey, $includedRunechants)) {
					$MZInds[] = "MYAURAS-$ind";
					$includedRunechants[] = $choiceKey;
				}
			}
			$theirInds = $theirInds != "" ? explode(",", $theirInds) : [];
			foreach ($theirInds as $ind) {
				$Aura = new AuraCard($ind, $otherPlayer);
				$choiceKey = "$otherPlayer-" . $Aura->CardID();
				if (!in_array($choiceKey, $includedRunechants)) {
					$MZInds[] = "THEIRAURAS-$ind";
					$includedRunechants[] = $choiceKey;
				}
			}
			if (count($MZInds) == 1) {
				AddDecisionQueue("PASSPARAMETER", $player, $MZInds[0], 1);
				AddDecisionQueue("SETDQVAR", $player, "choice", 1);
			}
			else {
				$context = "Usurp a " . CardLink("runechant");
				Await($player, "ChooseMultiZone", "choice", indices:implode(",", $MZInds), context:$context);
			}
			Await($player, "Usurp", cardID:$cardID, final:true);
		}
	}
}

function UsurpAwait($player) {
	global $dqVars, $CS_AdditionalCosts, $Stack, $CS_UsurpedThisTurn;
	$choice = $dqVars["choice"];
	$Runechant = MZIndexToObject($player, $choice);
	$usurpedID = $Runechant->CardID();
	$usurpedPlayer = $Runechant->Player();
	$uid = $Runechant->UniqueID();
	$RunechantLayer = $Stack->FindCardSourceUID($uid);
	$RunechantLayer->Negate(); // it shouldn't have triggered yet
	$Runechant->Destroy();
	$card = GetClass($usurpedID, $usurpedPlayer);
	if ($card != "-") $card->UsurpedEffect();
	WriteLog(CardLink($dqVars["cardID"]) . " usurped a runechant!");
	SetClassState($player, $CS_AdditionalCosts, "USURPED");
	IncrementClassState($player, $CS_UsurpedThisTurn);
}

function HasIncarnate($cardID) {
	$card = GetClass($cardID, 0);
	if ($card != "-") return $card->HasIncarnate();
	return GeneratedHasIncarnate($cardID);
}

function CheckUnique($player) {
	$Allies = new Allies($player);
	$Char = new PlayerCharacter($player);
	$uniqueCards = [];
	$allyCount = $Allies->NumAllies();
	for ($i = 0; $i < $allyCount; ++$i) {
		$AllyCard = $Allies->Card($i, true);
		$cardID = $AllyCard->CardID();
		if (IsUnique($cardID)) $uniqueCards[] = Moniker($cardID);
	}
	$characterCount = $Char->NumCards();
	for ($i = 0; $i < $characterCount; ++$i) {
		$CharCard = $Char->Card($i, true);
		$cardID = $CharCard->CardID();
		if (IsUnique($cardID)) $uniqueCards[] = Moniker($cardID);
	}
	if ($uniqueCards === []) return;

	$uniqueCardSet = array_fill_keys($uniqueCards, true);
	$conflictsByUnique = [];
	for ($i = 0; $i < $allyCount; ++$i) {
		$AllyCard = $Allies->Card($i, true);
		$uniqueCard = Moniker($AllyCard->CardID());
		if (isset($uniqueCardSet[$uniqueCard])) $conflictsByUnique[$uniqueCard][] = "MYALLY-" . $AllyCard->Index();
	}
	for ($i = 0; $i < $characterCount; ++$i) {
		$CharCard = $Char->Card($i, true);
		$uniqueCard = Moniker($CharCard->CardID());
		if (isset($uniqueCardSet[$uniqueCard])) $conflictsByUnique[$uniqueCard][] = "MYCHAR-" . $CharCard->Index();
	}

	foreach ($uniqueCards as $uniqueCard) {
		$conflicts = $conflictsByUnique[$uniqueCard];
		if (count($conflicts) > 1) {
			// for now don't let people kill themselves on accident
			if (($key = array_search('MYCHAR-0', $conflicts)) !== false)
				unset($conflicts[$key]);
			$conflicts = array_values($conflicts);

			$conflicts = implode(",", $conflicts);
			Await($player, "ChooseMultiZone", "choice", may:false, indices:$conflicts, context:"Sacrifice a $uniqueCard to the Unique Rule", subsequent:0);
			Await($player, "ProcessUnique", final:true);
			return;
		}
	}
}

function ProcessUniqueAwait($player) {
	global $dqVars;
	$choice = $dqVars["choice"];
	$obj = MZIndexToObject($player, $choice);
	if ($obj != "") {
		WriteLog("Sacrificing " . CardLink($obj->CardID()) . " to the Unique Rule!");
		$obj->Destroy(skipDestroy:true);
	}
	CheckUnique($player);
}

function ControlsBlasmo($player) {
	$Character = new PlayerCharacter($player);
	if (CardNameContains($Character->Card(0)->ID(), "Blasmophet", $player))
		return true;
	$Allies = new Allies($player);
	$allyCount = $Allies->NumAllies();
	for ($i = 0; $i < $allyCount; ++$i) {
		$AllyCard = $Allies->Card($i, true);
		if (CardNameContains($AllyCard->CardID(), "Blasmophet", $player))
			return true;
	}
	return false;
}

function BanishFromHand($player, $may=false, $context="", $final=false) {
	if ($context == "") $context = "Choose a card to banish";
	Await($player, "MultiZoneIndices", search:"MYHAND", subsequent:0);
	Await($player, "ChooseMultiZone", may:$may, context:$context);
	Await($player, "MZRemoveAndBanish", from:"HAND", final:$final);
}

function BanishFromArsenal($player, $cardID, $may=false) {
    Await($player, "MultiZoneIndices", search:"MYARS", subsequent:0);
    Await($player, "ChooseMultiZone", may:$may, context:"Banish a card from your arsenal");
    Await($player, "MZRemoveAndBanish", banishedBy:$cardID, from:"ARS", final:true);
}

function HasDecay($cardID) {
	$card = GetClass($cardID, 1);
	if ($card != "-") return $card->HasDecay();
	return false;
}

function BindAwait($player) {
	global $dqVars;
	// this should also trigger the bound cards HitEffect, Claude could you add that here?
	$index = $dqVars["index"];
	$AuraCard = new AuraCard($index, $player);
	$zone = $dqVars["zone"] ?? "MYAURAS";
	$MZindex = $dqVars["MZIndex"] ?? "-";
	if ($MZindex == "-") {
		WriteLog(CardLink($AuraCard->CardID()) . " had no choices to bind to, and so was cleared.");
		$AuraCard->Destroy(true); // clear the aura if it fails to bind
		return;
	}
	$CleanIndex = CleanTarget($player, $MZindex);
	if ($zone == "MYAURAS") {
		$AuraCard->Bind($CleanIndex);
		$obj = MZIndexToObject($player, $MZindex);
		WriteLog(CardLink($AuraCard->CardID()) . " was bound to " . CardLink($obj->CardID()));
	}
}

function DiscardAllyInstead($player, $cardID, $may=true) {
	if (SearchCount(SearchMultizone($player, "MYHAND:subtype=Ally")) > 0) {
		Await($player, "MultiZoneIndices", search:"MYHAND:subtype=Ally", subsequent:0);
		Await($player, "ChooseMultiZone", may:$may, context:"Discard an Ally instead of paying " . CardLink($cardID) . "'s cost?");
		Await($player, "Discard");
		Await($player, "AddCurrentTurnEffect", $player, effectID:"$cardID-PAID", final:true);
	}
}

function IsShadowDamageSource($playerSource) {
	if ($playerSource != 1 && $playerSource != 2) return false;
	$sourceHero = new CharacterCard(0, $playerSource);
	return TalentContains($sourceHero->CardID(), "SHADOW", $playerSource);
}

function ShadowResistPrevention($player, $mzIndex, $damage, $preventable) {
	$zone = $mzIndex[0];
	$index = intval($mzIndex[1]);
	if ($zone == "MYCHAR") $card = new CharacterCard($index, $player);
	else if ($zone == "MYALLY") $card = new AllyCard($index, $player);
	else return -1;
	$prevented = ShadowResistAmount($card->CardID(), $player, $index);
	if (!is_numeric($prevented) || $prevented <= 0) return -1;
	$card->Destroy();
	if ($preventable) $damage -= $prevented;
	return max(0, $damage);
}

function ShadowResistAmount($cardID, $player, $index) {
	$card = GetClass($cardID, $player);
	if ($card != "-") return $card->ShadowResistAmount($index);
	else return 0;
}
