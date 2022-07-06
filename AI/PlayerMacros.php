<?php

function ProcessMacros()
{
  global $currentPlayer, $turn, $actionPoints, $mainPlayer;
  $somethingChanged = true;
  if($turn[0] != "OVER")
  {
    for($i=0; $i<100 && $somethingChanged; ++$i)
    {
      $somethingChanged = false;
      if($turn[0] == "A" && ShouldSkipARs($currentPlayer)) { $somethingChanged = true; PassInput(); }
      else if($turn[0] == "D" && ShouldSkipDRs($currentPlayer)) { $somethingChanged = true; PassInput(); }
      else if(($turn[0] == "B") && IsAllyAttackTarget()) { $somethingChanged = true; PassInput(); }
      else if($turn[0] == "CHOOSEARCANE" && $turn[2] == "0") { $somethingChanged = true; ContinueDecisionQueue("0"); }
      else if($turn[0] == "BUTTONINPUT" && count(explode(",", $turn[2])) == 1) { $somethingChanged = true; ContinueDecisionQueue($turn[2]); }
      else if($turn[0] == "CHOOSEMULTIZONE" && $turn[2] == "THEIRCHAR-0") { $somethingChanged = true; ContinueDecisionQueue($turn[2]); }
      else if($turn[0] == "INSTANT" || ($turn[0] == "M" && ($actionPoints == 0 || $currentPlayer != $mainPlayer)))
      {
        if(HoldPrioritySetting($currentPlayer) == 0 && !HasPlayableCard($currentPlayer, $turn[0]))
        {
          $somethingChanged = true;
          PassInput();
        }
      }
    }
  }
}

function HasPlayableCard($player, $phase)
{
  $character = &GetPlayerCharacter($player);
  for($i=0; $i<count($character); $i+=CharacterPieces())
  {
    if($character[$i+1] == 2 && IsPlayable($character[$i], $phase, "CHAR", $i)) return true;
  }
  $hand = &GetHand($player);
  for($i=0; $i<count($hand); $i+=HandPieces())
  {
    if(IsPlayable($hand[$i], $phase, "HAND", $i)) return true;
  }
  $arsenal = &GetArsenal($player);
  for($i=0; $i<count($arsenal); $i+=ArsenalPieces())
  {
    if(IsPlayable($arsenal[$i], $phase, "ARS", $i)) return true;
  }
  $items = &GetItems($player);
  for($i=0; $i<count($items); $i+=ItemPieces())
  {
    if(IsPlayable($items[$i], $phase, "PLAY", $i)) return true;
  }
  $banish = &GetBanish($player);
  for($i=0; $i<count($banish); $i+=BanishPieces())
  {
    if(IsPlayable($banish[$i], $phase, "BANISH", $i)) return true;
  }
  $auras = &GetItems($player);
  for($i=0; $i<count($auras); $i+=AuraPieces())
  {
    if(IsPlayable($auras[$i], $phase, "PLAY", $i)) return true;
  }
  //TODO: Combat chain? Landmarks? Allies?
  return false;
}

function PlayerMacrosCardPlayed()
{
  global $turn, $currentPlayer, $SET_PassDRStep;
  $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
  if($turn[0] == "A")
  {
    ChangeSetting($otherPlayer, $SET_PassDRStep, 0);
  }
}

?>
