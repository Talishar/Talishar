<?php

function Usurp($cardID, $player, $from) {
	if (!IsActivated($cardID, $from)) {
		$otherPlayer = 3 - $player;
		$inds = SearchAurasForCard("runechant", $player, false);
		$theirInds = SearchAurasForCard("runechant", $otherPlayer, false);
		WriteLog("HERE: $theirInds");
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
			WriteLog("HERE2: " . implode(",", $MZInds));
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
	global $dqVars, $CS_AdditionalCosts, $Stack;
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
}

function HasIncarnate($cardID) {
	$card = GetClass($cardID, 0);
	if ($card != "-") return $card->HasIncarnate();
	return false;
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
