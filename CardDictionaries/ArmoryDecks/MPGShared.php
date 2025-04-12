<?php

function MPGPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = ""): string
{
  global $currentPlayer;
  switch ($cardID) {
    case "seismic_eruption_yellow":
      PlayAura("seismic_surge", $currentPlayer, 3, true);
      return "";
    default:
      return "";
  }
}
