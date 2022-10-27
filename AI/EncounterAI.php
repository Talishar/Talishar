<?php

function EncounterAI()
{
  global $currentPlayer, $p2CharEquip, $decisionQueue, $turn, $mainPlayer, $mainPlayerGamestateStillBuilt;
  $currentPlayerIsAI = ($currentPlayer == 2 && IsEncounterAI($p2CharEquip[0])) ? true : false;
  if(!IsGameOver() && $currentPlayerIsAI)
  {
    for($logicCount=0; $logicCount<=10 && $currentPlayerIsAI; ++$logicCount)
    {
      if(count($decisionQueue) > 0)
      {
        $options = explode(",", $turn[2]);
        ContinueDecisionQueue($options[0]);//Just pick the first option
      }
      else if($turn[0] == "B")
      {
        $hand = &GetHand($currentPlayer);
        if(count($hand) > 0 && (CachedTotalAttack() - CachedTotalBlock()) > 1)
        {
          ProcessInput($currentPlayer, 27, "", 0, 0, "");
          CacheCombatResult();
        }
        else PassInput();
      }
      else if($turn[0] == "M" && $mainPlayer == $currentPlayer)//AIs turn
      {
        $character = &GetPlayerCharacter($currentPlayer);
        $hand = &GetHand($currentPlayer);
        $index = -1;
        for($i=0; $i<count($hand) && $index == -1; ++$i) if(CardCost($hand[0]) == 0 && CardType($hand[0]) != "I") $index = $i;
        if($index != -1)
        {
          ProcessInput($currentPlayer, 27, "", 0, $index, "");
          CacheCombatResult();
        }
        else {
          for($i=0; $i<count($character) && $index == -1; $i += CharacterPieces()) if(CardType($character[$i]) != "C") $index = $i;
          if($index != -1)
          {
            ProcessInput($currentPlayer, 3, "", CharacterPieces(), $index, "");
            CacheCombatResult();
          }
          else PassInput();
        }
      }
      else if($turn[0] == "A" && $mainPlayer == $currentPlayer)
      {
        $hand = &GetHand($currentPlayer);
        if(count($hand) > 0 && CardCost($hand[0]) == 0 && CardType($hand[0]) == "I")
        {
          ProcessInput($currentPlayer, 27, "", 0, 0, "");
          CacheCombatResult();
        }
        else PassInput();
      }
      else
      {
        PassInput();
      }
      ProcessMacros();
      $currentPlayerIsAI = ($currentPlayer == 2 ? true : false);
      if($logicCount == 10 && $currentPlayerIsAI)
      {
        for($i=0; $i<=10 && $currentPlayerIsAI; ++$i)
        {
          PassInput();
          $currentPlayerIsAI = ($currentPlayer == 2 ? true : false);
        }
      }
    }
  }
}

function IsEncounterAI($enemyHero)
{
  return str_contains($enemyHero, "ROGUE");
}

?>
