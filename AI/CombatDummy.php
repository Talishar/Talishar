<?php

include_once "EncounterAI.php";

function CombatDummyAI()
{
  global $currentPlayer, $decisionQueue, $turn, $mainPlayer;
  $currentPlayerIsAI = IsPlayerAI($currentPlayer) ? true : false;
  $canceled = false;
  if($currentPlayerIsAI) {
    EncounterAI();
    return;
  }
  if(!IsGameOver() && $currentPlayerIsAI)
  {
    for($i=0; $i<100 && $currentPlayerIsAI; ++$i)
    {
      if(count($decisionQueue) > 0)
      {
        if($turn[2] == "if_you_want_to_pay_3_to_avoid_taking_2_damage") ContinueDecisionQueue("NO");
        else {
          $options = explode(",", $turn[2]);
          ContinueDecisionQueue($options[0]);//Just pick the first option
        }
      }
      else if($turn[0] == "M" && $mainPlayer == $currentPlayer && !$canceled)//AIs turn
      {
        $character = &GetPlayerCharacter($currentPlayer);
        $index = -1;
        for($j=0; $j<count($character) && $index == -1; $j += CharacterPieces()) if(CardType($character[$j]) != "C") $index = $j;
        $cardID = $character[$index];
        $from = "EQUIP";
        $baseCost = AbilityCost($cardID);
        $frostbitesPaid = AuraCostModifier($cardID);
        $cost = $baseCost + CurrentEffectCostModifiers($cardID, $from) + $frostbitesPaid + CharacterCostModifier($cardID, $from, CardCost($cardID, $from));

        if($index != -1 && $cost == 0)
        {
          $wasSuccessful = ProcessInput($currentPlayer, 3, "", CharacterPieces(), $index, "");
          if($wasSuccessful) CacheCombatResult();
          else PassInput();
        }
        else PassInput();
      }
      ProcessMacros();
      $currentPlayerIsAI = IsPlayerAI($currentPlayer) ? true : false;
    }
  }
}

function IsPlayerAI($playerID)
{
  global $p2IsAI;
  if($playerID == 2 && $p2IsAI == "1") return true;
  return false;
}
?>
