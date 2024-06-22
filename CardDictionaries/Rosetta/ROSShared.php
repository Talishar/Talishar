<?php

function ROSAbilityCost($cardID)
{
    switch($cardID) {
        case "ROS008": return 2;
        default: return 0;
      }
}

function ROSAbilityType($cardID, $index = -1)
{
  switch($cardID) {
    case "ROS008": return "I";
    default: return "";
  }
}

function ROSPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts)
{
    global $currentPlayer;
    switch($cardID) {
        case "ROS008":
            PlayAura("ELE110", $currentPlayer);
            return "";
        break;
        default:
            return "";
     }
}