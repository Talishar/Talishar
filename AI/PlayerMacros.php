<?php

function ProcessMacros()
{
  global $currentPlayer, $turn, $actionPoints, $mainPlayer, $layers, $decisionQueue, $numPass;
  $somethingChanged = true;
  for($i=0; $i<$numPass; ++$i)
  {
    PassInput();
  }
  if(!IsGameOver())
  {
    for($i=0; $i<10 && $somethingChanged; ++$i)
    {
      $somethingChanged = false;
      if($turn[0] == "A" && ShouldSkipARs($currentPlayer)) { $somethingChanged = true; PassInput(); }
      else if($turn[0] == "D" && ShouldSkipDRs($currentPlayer)) { $somethingChanged = true; PassInput(); }
      else if(($turn[0] == "B") && IsAllyAttackTarget()) { $somethingChanged = true; PassInput(); }
      else if($turn[0] == "CHOOSEARCANE" && $turn[2] == "0") { $somethingChanged = true; ContinueDecisionQueue("0"); }
      else if($turn[0] == "CHOOSEARSENAL" && $turn[2] == "0") { $somethingChanged = true; ContinueDecisionQueue($turn[2]); }
      else if((count($decisionQueue) == 0 || $decisionQueue[0] == "INSTANT") && count($layers) > 0 && $layers[count($layers)-LayerPieces()] == "ENDSTEP" && count($layers) < (LayerPieces() * 3)) { $somethingChanged = true; PassInput(); }
      else if($turn[0] == "INSTANT" || ($turn[0] == "M" && ($actionPoints == 0 || $currentPlayer != $mainPlayer)))
      {
        if(HoldPrioritySetting($currentPlayer) == 0 && !HasPlayableCard($currentPlayer, $turn[0]))
        {
          $somethingChanged = true;
          PassInput();
        }
        if($turn[0] == "INSTANT" && count($layers) > 0)
        {
          if($layers[0] == "FINALIZECHAINLINK" && HoldPrioritySetting($currentPlayer) != "1") { $somethingChanged = true; PassInput(); }
          else if($layers[0] == "DEFENDSTEP" && HoldPrioritySetting($currentPlayer) != "1") { $somethingChanged = true; PassInput(); }
          else if($layers[5] != "-")//Means there is a unique ID
          {
            $subtype = CardSubType($layers[2]);
            if(DelimStringContains($subtype, "Aura") && GetAuraGemState($layers[1], $layers[2]) == 0) { $somethingChanged = true; PassInput(); }
            if(DelimStringContains($subtype, "Item") && GetItemGemState($layers[1], $layers[2]) == 0) { $somethingChanged = true; PassInput(); }
          }
        }
      }
      else if(AutopassPhaseWithOneOption($turn[0]) && SearchCount($turn[2]) == 1) { $somethingChanged = true; ContinueDecisionQueue($turn[2]); }
      if($turn[0] == "B" || $turn[0] == "D")
      {
        $threshold = ShortcutAttackThreshold($currentPlayer);
        if($threshold == "99") { $somethingChanged = true; PassInput(); }
        else if($threshold == "1")
        {
          CacheCombatResult();
          if(CachedTotalAttack() <= 1) { $somethingChanged = true; PassInput(); }
        }
      }
    }
  }
}

function AutopassPhaseWithOneOption($phase)
{
  switch($phase)
  {
    case "BUTTONINPUT": case "CHOOSEMULTIZONE": case "CHOOSECHARACTER": case "CHOOSECOMBATCHAIN":
      return true;
    default: return false;
  }
}

function HasPlayableCard($player, $phase)
{
  $restriction = "";
  $character = &GetPlayerCharacter($player);
  for($i=0; $i<count($character); $i+=CharacterPieces())
  {
    if($character[$i+1] == 2 && IsPlayable($character[$i], $phase, "CHAR", $i, $restriction, $player)) return true;
  }
  $hand = &GetHand($player);
  for($i=0; $i<count($hand); $i+=HandPieces())
  {
    if(IsPlayable($hand[$i], $phase, "HAND", $i, $restriction, $player)) return true;
  }
  global $combatChain;
  for ($i = 0; $i < count($combatChain); $i += CombatChainPieces())
  {
    if(IsPlayable($combatChain[$i], $phase, "CC", $i, $restriction, $player)) return true;
  }
  $arsenal = &GetArsenal($player);
  for($i=0; $i<count($arsenal); $i+=ArsenalPieces())
  {
    if(IsPlayable($arsenal[$i], $phase, "ARS", $i, $restriction, $player)) return true;
  }
  $items = &GetItems($player);
  for($i=0; $i<count($items); $i+=ItemPieces())
  {
    if(IsPlayable($items[$i], $phase, "PLAY", $i, $restriction, $player)) return true;
  }
  $banish = &GetBanish($player);
  for($i=0; $i<count($banish); $i+=BanishPieces())
  {
    if(IsPlayable($banish[$i], $phase, "BANISH", $i, $restriction, $player)) return true;
  }
  $auras = &GetItems($player);
  for($i=0; $i<count($auras); $i+=AuraPieces())
  {
    if(IsPlayable($auras[$i], $phase, "PLAY", $i, $restriction, $player)) return true;
  }
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
