<?php

function Usurp($cardID, $player, $from) {
	if (!IsActivated($cardID, $from)) {
		$search = "MYAURAS:isSameName=runechant&THEIRAURAS:isSameName=runechant";
		$context = "Usurp a " . CardLink("runechant");
		Await($player, "MultiZoneIndices", "indices", search:$search, subsequent:0);
		Await($player, "ChooseMultiZone", "choice", context:$context);
		Await($player, "Usurp", cardID:$cardID, final:true);
	}
}

function UsurpAwait($player) {
	global $dqVars, $CS_AdditionalCosts, $Stack;
	$choice = $dqVars["choice"];
	$Runechant = MZIndexToObject($player, $choice);
	$uid = $Runechant->UniqueID();
	$RunechantLayer = $Stack->FindCardSourceUID($uid);
	$RunechantLayer->Negate(); // it shouldn't have triggered yet
	$Runechant->Destroy();
	WriteLog(CardLink($dqVars["cardID"]) . " usurped a runechant!");
	SetClassState($player, $CS_AdditionalCosts, "USURPED");
}