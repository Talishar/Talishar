<?php

function CombatDummyAI()
{
  global $currentPlayer, $p2CharEquip, $decisionQueue, $turn;
  $currentPlayerIsAI = ($currentPlayer == 2 && $p2CharEquip[0] == "DUMMY") ? true : false;
  if($turn[0] != "OVER" && $currentPlayerIsAI)
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
      $currentPlayerIsAI = ($currentPlayer == 2 && $p2CharEquip[0] == "DUMMY") ? true : false;
    }
  }
}

?>