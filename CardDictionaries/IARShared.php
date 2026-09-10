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
	for ($i = 0; $i < $Allies->NumAllies(); ++$i) {
		$AllyCard = $Allies->Card($i, true);
		if (IsUnique($AllyCard->CardID())) $uniqueCards[] = Moniker($AllyCard->CardID());
	}
	for ($i = 0; $i < $Char->NumCards(); ++$i) {
		$CharCard = $Char->Card($i, true);
		if (IsUnique($CharCard->CardID())) $uniqueCards[] = Moniker($CharCard->CardID());
	}

	foreach ($uniqueCards as $uniqueCard) {
		$conflicts = [];
		for ($i = 0; $i < $Allies->NumAllies(); ++$i) {
			$AllyCard = $Allies->Card($i, true);
			if (Moniker($AllyCard->CardID()) == $uniqueCard) $conflicts[] = "MYALLY-" . $AllyCard->Index();
		}
		for ($i = 0; $i < $Char->NumCards(); ++$i) {
			$CharCard = $Char->Card($i, true);
			if (Moniker($CharCard->CardID()) == $uniqueCard) $conflicts[] = "MYCHAR-" . $CharCard->Index();
		}
		if (count($conflicts) > 1) {
			// for now don't let people kill themselves on accident
			if (($key = array_search('MYCHAR-0', $conflicts)) !== false)
				unset($conflicts[$key]);
			$conflicts = array_values($conflicts);

			$conflicts = implode(",", $conflicts);
			Await($player, "ChooseMultiZone", "choice", indices:$conflicts, context:"Sacrifice a $uniqueCard to the Unique Rule", subsequent:0);
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
	for ($i = 0; $i < $Allies->NumAllies(); ++$i) {
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

function BanishFromArsenal($player, $cardID) {
    Await($player, "MultiZoneIndices", search:"MYARS", subsequent:0);
    Await($player, "ChooseMultiZone", context:"Banish a card from your arsenal");
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

function CheckShadowResist($player, $damage, $source = "-", $type="-", $preventable=true) {
	$caption = "Choose a card with Shadow Resist to prevent damage (or pass)";
	if (!$preventable)
		$caption .= GetDamagePreventionWarning($player, $damage, $type, $source, " ");
	Await($player, "ProcessShadowResist", source:$source, type:$type, preventable:$preventable, prepend:true);
	Await($player, "ChooseMultiZone", may:true, context:$caption, prepend:true);
	Await($player, "SearchShadowResist", "indices", damage:$damage, subsequent:0, prepend:true);
}

function ProcessShadowResistAwait($player) {
	global $dqVars, $CS_PreventionCache;
	$damage = $dqVars["damage"] ?? 0;
	$preventable = $dqVars["preventable"] ?? true;
	$source = $dqVars["source"] ?? "-";
	$type = $dqVars["type"] ?? "-";
	$prevented = 0;
	$choice = $dqVars["MZIndex"] ?? "PASS";
	if ($choice != "PASS") {
		$permanentObject = MZIndexToObject($player, $choice);
		$prevented = ShadowResistAmount($permanentObject->CardID(), $player, $permanentObject->Index());
		$permanentObject->Destroy();
		if($prevented > 0) LogDamagePreventedStats($player, min($damage, $prevented));
		if ($preventable) $damage -= $prevented;
		if ($damage < 0) $damage = 0;
		if ($damage > 0) CheckShadowResist($player, $damage, $source, $type, $preventable);
		PrependDecisionQueue("INCREMENTCLASSSTATEBY", $player, $CS_PreventionCache, 1);
		PrependDecisionQueue("PASSPARAMETER", $player, $prevented, 1);
	}
}

function SearchShadowResistAwait($player) {
	global $dqVars;
	$damage = $dqVars["damage"] ?? 0;
	return SearchShadowResistIndices($player, $damage);
}

function SearchShadowResistIndices($player, $damage) {
	$inds = [];
	$Character = new PlayerCharacter($player);
	for ($i = 0; $i < $Character->NumCards(); ++$i) {
		$CharacterCard = $Character->Card($i, true);
		if (!$CharacterCard->IsActive()) continue;
		$index = $CharacterCard->Index();
		if (ShadowResistAmount($CharacterCard->CardID(), $player, $index) > 0)
			$inds[] = "MYCHAR-$index";
	}
	$Allies = new Allies($player);
	for ($i = 0; $i < $Allies->NumAllies(); ++$i) {
		$AllyCard = $Allies->Card($i, true);
		$index = $AllyCard->Index();
		if (ShadowResistAmount($AllyCard->CardID(), $player, $index) > 0)
			$inds[] = "MYALLY-$index";
	}
	return implode(",", $inds);
}

function ShadowResistAmount($cardID, $player, $index) {
	$card = GetClass($cardID, $player);
	if ($card != "-") return $card->ShadowResistAmount($index);
	else return 0;
}