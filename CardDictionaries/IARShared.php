<?php

function Usurp($cardID, $player, $from) {
	if (!IsActivated($cardID, $from)) {
		$otherPlayer = 3 - $player;
		$inds = SearchAurasForCard("runechant", $player, false);
		$theirInds = SearchAurasForCard("runechant", $otherPlayer, false);
		if ($inds != "" || $theirInds != "") {
			$MZInds = [];
			$inds = $inds != "" ? explode(",", $inds) : [];
			foreach ($inds as $ind)
				$MZInds[] = "MYAURAS-$ind";
			$theirInds = $theirInds != "" ? explode(",", $theirInds) : [];
			foreach ($theirInds as $ind)
				$MZInds[] = "THEIRAURAS-$ind";
			$context = "Usurp a " . CardLink("runechant");
			Await($player, "ChooseMultiZone", "choice", indices:implode(",", $MZInds), context:$context);
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