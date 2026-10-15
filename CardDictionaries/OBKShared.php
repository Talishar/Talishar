<?php

function GiveDisease($player) {
	$diseases = SearchMultizone($player, "MYAURAS:subtype=Disease");
	Await($player, "ChooseMultiZone", indices:$diseases, context:"Give a disease to your opponent", subsequent:0);
	Await($player, "GiveAura", final:true);
}

function GiveAuraAwait($player) {
	global $dqVars;
	$otherPlayer = $player == 1 ? 2 : 1;
	$MZIndex = $dqVars["MZIndex"] ?? "-";
	$Disease = MZIndexToObject($player, $MZIndex);
	if ($Disease != "") {
		WriteLog("Player $player gave " . CardLink($Disease->CardID()) . " to Player $otherPlayer!");
		StealAura($player, $Disease->Index(), $otherPlayer, "-", realSteal:false);
	}
}