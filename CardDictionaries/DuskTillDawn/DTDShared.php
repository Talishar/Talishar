<?php

function DTDAbilityCost($cardID)
{
  global $Card_Lumi2;
  switch($cardID) {
    case "DTD060": return 1;
    //case $Card_Lumi2: return 2;
    default: return 0;
  }
}

function DTDAbilityType($cardID, $index = -1)
{
  switch($cardID) {
    case "DTD060": return "AR";
    //case $Card_Lumi2: return "I";
    default: return "";
  }
}

function DTDAbilityHasGoAgain($cardID)
{
  switch($cardID) {
    default: return false;
  }
}

function DTDEffectAttackModifier($cardID)
{
  global $mainPlayer;
  $params = explode(",", $cardID);
  $cardID = $params[0];
  if(count($params) > 1) $parameter = $params[1];
  switch($cardID) {
    case "DTD053": return 2;//Prayer of Bellona
    case "DTD057": case "DTD058": case "DTD059": return 1;//Beaming Bravado
    case "DTD060": return 3;
    case "DTD061": return 2;
    case "DTD062": return 1;
    case "DTD069": return 3;//Resounding Courage
    case "DTD070": return 2;
    case "DTD071": return 1;
    case "DTD072": return 3;//Charge of the Light Brigade
    case "DTD073": return 2;
    case "DTD074": return 1;
    case "DTD196": return 1;//Anthem of Spring
    case "DTD232": return 1;//Courage
    default:
      return 0;
  }
}

function DTDCombatEffectActive($cardID, $attackID)
{
  global $combatChainState, $CCS_IsBoosted, $mainPlayer, $combatChainState, $CCS_AttackNumCharged;
  $params = explode(",", $cardID);
  $cardID = $params[0];
  switch($cardID) {
    case "DTD052": return CardType($attackID) == "AA";//Spirit of War
    case "DTD053": return true;//Prayer of Bellona
    case "DTD057": case "DTD058": case "DTD059": return true;//Beaming Bravado
    case "DTD060": case "DTD061": case "DTD062": return true;
    case "DTD066": case "DTD067": case "DTD068": return true;
    case "DTD072": case "DTD073": case "DTD074": return $combatChainState[$CCS_AttackNumCharged] > 0;//Charge of the Light Brigade
    case "DTD069": case "DTD070": case "DTD071": return true;//Resounding Courage
    case "DTD196": return CardType($attackID) == "AA";//Anthem of Spring
    case "DTD198": return true;//Call Down the Lightning
    case "DTD232": return true;//Courage
    default:
      return false;
  }
}

function DTDPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts)
{
  global $currentPlayer, $CS_NumCharged;
  $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
  $rv = "";
  switch($cardID) {
    case "DTD011":
      AddCurrentTurnEffect($cardID, $otherPlayer);
      break;
    case "DTD053":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      $deck = new Deck($currentPlayer);
      if($deck->Reveal() && PitchValue($deck->Top()) == 2)
      {
        $card = $deck->Top(remove:true);
        AddPlayerHand($card, $currentPlayer, "DECK");
        Charge();
      }
      break;
    case "DTD060": case "DTD061": case "DTD062"://V for Valor
      if($from == "PLAY") AddCurrentTurnEffect($cardID, $currentPlayer, from:"PLAY");
      break;
    case "DTD069": case "DTD070": case "DTD071"://Resounding Courage
      AddCurrentTurnEffect($cardID, $currentPlayer);
      if(GetClassState($currentPlayer, $CS_NumCharged) > 0) PlayAura("DTD232", $currentPlayer);
      break;
    case "DTD072": case "DTD073": case "DTD074"://Charge of the Light Brigade
      AddCurrentTurnEffect($cardID, $currentPlayer);
      break;
    case "DTD085": GainHealth(3, $currentPlayer); break;//Blessing of Salvation
    case "DTD086": GainHealth(2, $currentPlayer); break;
    case "DTD087": GainHealth(1, $currentPlayer); break;
    case "DTD196"://Anthem of Spring
      AddCurrentTurnEffect($cardID, $currentPlayer);
      break;
    case "DTD198"://Call Down the Lightning
      AddCurrentTurnEffect($cardID, $currentPlayer);
      break;
    case "DTD219"://Lost in Thought
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "THEIRHAND:type=A&THEIRHAND:type=AA");
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("MZREMOVE", $currentPlayer, "-", 1);
      AddDecisionQueue("ADDBOTDECK", $otherPlayer, "-", 1);
      return "";
    default:
      return "";
  }
}

function DTDHitEffect($cardID)
{
  switch($cardID) {

    default: break;
  }
}

function DoesAttackTriggerMirage()
{
  global $combatChain, $mainPlayer;
  if(ClassContains($combatChain[0], "ILLUSIONIST", $mainPlayer)) return false;
  return CachedTotalAttack() >= 6;
}

function ProcessMirageOnBlock($index)
{
  global $mainPlayer;
  if(IsMirageActive($index) && DoesAttackTriggerMirage())
  {
    AddLayer("LAYER", $mainPlayer, "MIRAGE");
  }
}

function IsMirageActive($index)
{
  global $combatChain;
  if(count($combatChain) == 0) return false;
  return HasMirage($combatChain[$index]);
}

function HasMirage($cardID)
{
  switch($cardID)
  {
    case "DTD218": return true;
    default: return false;
  }
}

function MirageLayer()
{
  global $combatChain, $mainPlayer, $combatChainState, $defPlayer, $turn, $layers;
  if(DoesAttackTriggerMirage())
  {
    for($i=count($combatChain)-CombatChainPieces(); $i>=CombatChainPieces(); $i-=CombatChainPieces())
    {
      if(IsMirageActive($i))
      {
        WriteLog(CardLink($combatChain[$i], $combatChain[$i]) . " is destroyed by Mirage.");
        AddGraveyard($combatChain[$i], $defPlayer, "CC");
        RemoveCombatChain($i);
      }
    }
  }
  else {
    $turn[0] = "D";
    $currentPlayer = $mainPlayer;
    for($i=count($layers)-LayerPieces(); $i >= 0; $i-=LayerPieces())
    {
      if($layers[$i] == "DEFENDSTEP" || ($layers[$i] == "LAYER" && $layers[$i+2] == "MIRAGE"))
      {
        for($j=$i; $j<($i+LayerPieces()); ++$j) unset($layers[$j]);
      }
    }
    $layers = array_values($layers);
  }
}
