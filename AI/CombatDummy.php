<?php

function CombatDummyAI()
{
  global $currentPlayer, $p2CharEquip, $decisionQueue, $turn, $mainPlayer;
  $currentPlayerIsAI = ($currentPlayer == 2 && $p2CharEquip[0] == "DUMMY") ? true : false;
  $canceled = false;
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
        for($i=0; $i<count($character) && $index == -1; $i += CharacterPieces()) if(CardType($character[$i]) != "C") $index = $i;
        $cardID = $character[$index];
        $from = "EQUIP";
        $baseCost = AbilityCost($cardID);
        $frostbitesPaid = AuraCostModifier();
        $cost = $baseCost + CurrentEffectCostModifiers($cardID, $from) + $frostbitesPaid + CharacterCostModifier($cardID, $from);

        if($index != -1 && $cost == 0)
        {
          $wasSuccessful = ProcessInput($currentPlayer, 3, "", CharacterPieces(), $index, "");
          if($wasSuccessful) CacheCombatResult();
          else PassInput();
        }
        else PassInput();
      }
      else
      {
        PassInput();
      }
      ProcessMacros();
      $currentPlayerIsAI = ($currentPlayer == 2 && $p2CharEquip[0] == "DUMMY") ? true : false;
    }
  }
}

function IsPlayerAI($playerID)
{
  $char = &GetPlayerCharacter($playerID);
  if($char[0] == "DUMMY") return true;
  return false;
}

?>
