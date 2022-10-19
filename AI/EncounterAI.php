<?php

function EncounterAI()
{
  global $currentPlayer, $p2CharEquip, $decisionQueue, $turn, $mainPlayer, $mainPlayerGamestateStillBuilt;
  $currentPlayerIsAI = ($currentPlayer == 2 && $p2CharEquip[0] == "ROGUE001") ? true : false;
  if(!IsGameOver() && $currentPlayerIsAI)
  {
    for($i=0; $i<100 && $currentPlayerIsAI; ++$i)
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
        if(count($character) > CharacterPieces() && CardType($character[CharacterPieces()]) == "W")
        {
          ProcessInput($currentPlayer, 3, "", CharacterPieces(), 0, "");
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
    }
  }
}

?>
