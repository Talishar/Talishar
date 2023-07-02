<?php

function DTDAbilityCost($cardID)
{
  switch($cardID) {
    case "DTD001": case "DTD002": return 2;
    case "DTD046": return 2;
    case "DTD060": return 1;
    case "DTD075": case "DTD076": case "DTD077": case "DTD078": return 0;
    case "DTD205": return 3;
    case "DTD207": return 1;
    case "DTD405": case "DTD406": case "DTD407": case "DTD408"://Angels
    case "DTD409": case "DTD410": case "DTD411": case "DTD412": return 2;
    default: return 0;
  }
}

function DTDAbilityType($cardID, $index = -1)
{
  switch($cardID) {
    case "DTD001": case "DTD002": return "I";
    case "DTD046": return "AA";
    case "DTD060": return "AR";
    case "DTD075": case "DTD076": case "DTD077": case "DTD078": return "I";
    case "DTD205": return "AA";
    case "DTD207": return "A";
    case "DTD405": case "DTD406": case "DTD407": case "DTD408"://Angels
    case "DTD409": case "DTD410": case "DTD411": case "DTD412": return "AA";
    default: return "";
  }
}

function DTDAbilityHasGoAgain($cardID)
{
  switch($cardID) {
    case "DTD207": return true;
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
    case "DTD033": return 2;
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
    case "DTD082": case "DTD083": case "DTD084": return 1;
    case "DTD196": return 1;//Anthem of Spring
    case "DTD232": return 1;//Courage
    default:
      return 0;
  }
}

function DTDCombatEffectActive($cardID, $attackID)
{
  global $combatChainState, $CCS_IsBoosted, $mainPlayer, $combatChainState, $CCS_AttackNumCharged, $combatChain;
  global $Card_LifeBanner, $Card_ResourceBanner;
  $params = explode(",", $cardID);
  $cardID = $params[0];
  switch($cardID) {
    case "DTD010": return true;
    case "DTD033": return SubtypeContains($combatChain[0], "Angel", $mainPlayer);
    case "DTD051": return CardType($attackID) == "AA";//Beckoning Light
    case "DTD052": return CardType($attackID) == "AA";//Spirit of War
    case "DTD053": return true;//Prayer of Bellona
    case "DTD057": case "DTD058": case "DTD059": return true;//Beaming Bravado
    case "DTD060": case "DTD061": case "DTD062": return true;
    case "DTD063": case "DTD064": case "DTD065": return true;//Glaring Impact
    case "DTD066": case "DTD067": case "DTD068": return true;
    case "DTD069": case "DTD070": case "DTD071": return true;//Resounding Courage
    case "DTD072": case "DTD073": case "DTD074": return $combatChainState[$CCS_AttackNumCharged] > 0;//Charge of the Light Brigade
    case "DTD082": case "DTD083": case "DTD084": return true;
    case "DTD094": case "DTD095": case "DTD096": return true;
    case "DTD196": return CardType($attackID) == "AA";//Anthem of Spring
    case "DTD198": return true;//Call Down the Lightning
    case "DTD206": return true;
    case "DTD207": return SubtypeContains($combatChain[0], "Sword", $mainPlayer);//Ironsong Versus
    case "DTD232": return true;//Courage
    case $Card_LifeBanner: return true;
    case $Card_ResourceBanner: return true;
    default:
      return false;
  }
}

function DTDPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts)
{
  global $currentPlayer, $CS_NumCharged, $CS_DamagePrevention, $CS_NumCardsDrawn, $combatChain;
  $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
  $rv = "";
  switch($cardID) {
    case "DTD001": case "DTD002":
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYPERM:subtype=Figment");
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a figment to awaken");
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("AWAKEN", $currentPlayer, "-", 1);
      return "";
    case "DTD005":
      PlayAura("DYN244", $currentPlayer); // Creates Ponder
      return "";
    case "DTD008":
      DealArcane(1, 2, "PLAYCARD", $cardID, false, $currentPlayer);
      return "";
    case "DTD009":
      MZMoveCard($currentPlayer, "MYDISCARD:type=A;pitch=2", "MYTOPDECK"); 
      return;
    case "DTD010":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "DTD011":
      AddCurrentTurnEffect($cardID, $otherPlayer);
      return "";
    case "DTD033":
      GiveAttackGoAgain();
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "DTD038": case "DTD039": case "DTD040":
      if($cardID == "DTD038") $amount = 3;
      else if($cardID == "DTD039") $amount = 2;
      else $amount = 1;
      if($target != "-") $combatChain[intval($target)+5] -= $amount;
      return "";
    case "DTD053":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      $deck = new Deck($currentPlayer);
      if($deck->Reveal() && PitchValue($deck->Top()) == 2)
      {
        $card = $deck->Top(remove:true);
        AddPlayerHand($card, $currentPlayer, "DECK");
        Charge();
      }
      return "";
    case "DTD060": case "DTD061": case "DTD062"://V for Valor
      if($from == "PLAY") AddCurrentTurnEffect($cardID, $currentPlayer, from:"PLAY");
      return "";
    case "DTD069": case "DTD070": case "DTD071"://Resounding Courage
      AddCurrentTurnEffect($cardID, $currentPlayer);
      if(GetClassState($currentPlayer, $CS_NumCharged) > 0) PlayAura("DTD232", $currentPlayer);
      return "";
    case "DTD072": case "DTD073": case "DTD074"://Charge of the Light Brigade
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "DTD075": case "DTD076": case "DTD077": case "DTD078":
      IncrementClassState($currentPlayer, $CS_DamagePrevention, 2);
      return "";
    case "DTD082": case "DTD083": case "DTD084"://Lay to Rest
      $theirChar = &GetPlayerCharacter($otherPlayer);
      if(TalentContains($theirChar[0], "SHADOW", $otherPlayer)) AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "DTD085": GainHealth(3, $currentPlayer); break;//Blessing of Salvation
    case "DTD086": GainHealth(2, $currentPlayer); break;
    case "DTD087": GainHealth(1, $currentPlayer); break;
    case "DTD088": case "DTD089": case "DTD090"://Cleansing Light
      if($cardID == "DTD088") $targetPitch = 1;
      else if($cardID == "DTD089") $targetPitch = 2;
      else if($cardID == "DTD090") $targetPitch = 3;
      WriteLog($taregtPitch);
      MZChooseAndDestroy($currentPlayer, "THEIRAURAS:pitch=" . $targetPitch . "&MYAURAS:pitch=" . $targetPitch);
      return "";
    case "DTD091": case "DTD092": case "DTD093":
      if(SearchPitchForColor($currentPlayer, 2) > 0) GiveAttackGoAgain();
      return "";
    case "DTD0100": case "DTD101": case "DTD102":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "DTD140":
      PlayAura("ARC112", $currentPlayer);
      return "";
    case "DTD196"://Anthem of Spring
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "DTD198"://Call Down the Lightning
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "DTD207"://Ironsong Versus
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "DTD219"://Lost in Thought
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "THEIRHAND:type=A&THEIRHAND:type=AA");
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("MZREMOVE", $currentPlayer, "-", 1);
      AddDecisionQueue("ADDBOTDECK", $otherPlayer, "-", 1);
      return "";
    case "DTD228":
      if(GetClassState($otherPlayer, $CS_NumCardsDrawn) >= 2)
      {
        IncrementClassState($currentPlayer, $CS_DamagePrevention, 3);
        WriteLog("Prevents the next 3 damage");
      }
      return "";
    default:
      return "";
  }
}

function DTDHitEffect($cardID)
{
  switch($cardID) {
    case "DTD082": case "DTD083": case "DTD084":
      WriteLog("The banish face down effect of this card is not implemented yet. Choose the card in chat and enforce play restrictions manually.");
      break;
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
