<?php

function CombatDummyAI()
{
  global $currentPlayer, $p2CharEquip, $decisionQueue, $turn;
  $currentPlayerIsAI = ($currentPlayer == 2 && $p2CharEquip[0] == "DUMMY") ? true : false;
  if(!IsGameOver() && $currentPlayerIsAI)
  {
    for($i=0; $i<100 && $currentPlayerIsAI; ++$i)
    {
      if(count($decisionQueue) > 0)
      {
        $options = explode(",", $turn[2]);
        ContinueDecisionQueue($options[0]);//Just pick the first option
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
