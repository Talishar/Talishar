<?php

function OUTAbilityCost($cardID)
{
  switch($cardID)
  {
    case "OUT096": return 3;
    default: return 0;
  }
}

  function OUTAbilityType($cardID, $index=-1)
  {
    switch ($cardID)
    {
      case "OUT096": return "I";
      default: return "";
    }
  }

  function OUTAbilityHasGoAgain($cardID)
  {
    switch ($cardID)
    {
      default: return false;
    }
  }

  function OutEffectAttackModifier($cardID)
  {
    $idArr = explode("-", $cardID);
    $cardID = $idArr[0];
    switch ($cardID)
    {
      default: return 0;
    }
  }

  function OUTCombatEffectActive($cardID, $attackID)
  {
    global $mainPlayer;
    $idArr = explode("-", $cardID);
    $cardID = $idArr[0];
    switch ($cardID)
    {

      default: return false;
    }
  }

  function OUTHasGoAgain($cardID)
  {
    switch ($cardID)
    {
      case "OUT160": return true;
      default: return false;
    }
  }

  function OUTPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = "")
  {
    global $currentPlayer, $CS_PlayIndex;
    global $CID_Frailty;
    $rv = "";
    switch ($cardID)
    {
      case "OUT096":
        $deck = new Deck($currentPlayer);
        if($deck->Reveal())
        {
          $topCard = $deck->Top(remove:true);
          if(CardSubType($topCard) == "Arrow")
          {
            if(!ArsenalFull($currentPlayer)) AddArsenal($topCard, $currentPlayer, "DECK", "UP");
            DestroyCharacter($currentPlayer, GetClassState($currentPlayer, $CS_PlayIndex));
            $rv = "The top card was an arrow, so Quiver of Rustling Leaves is destroyed.";
          }
        }
        return $rv;
      case "OUT160":
        $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
        if(!ArsenalFull($currentPlayer))
        {
          AddDecisionQueue("FINDINDICES", $currentPlayer, "GYAA");
          AddDecisionQueue("CHOOSEDISCARD", $currentPlayer, "<-", 1);
          AddDecisionQueue("MULTIREMOVEDISCARD", $currentPlayer, "-", 1);
          AddDecisionQueue("ADDARSENALFACEDOWN", $currentPlayer, "GY", 1);
          PummelHit($currentPlayer, true);
        }
        if(!ArsenalFull($otherPlayer))
        {
          AddDecisionQueue("FINDINDICES", $otherPlayer, "GYAA");
          AddDecisionQueue("CHOOSEDISCARD", $otherPlayer, "<-", 1);
          AddDecisionQueue("MULTIREMOVEDISCARD", $otherPlayer, "-", 1);
          AddDecisionQueue("ADDARSENALFACEDOWN", $otherPlayer, "GY", 1);
          PummelHit($otherPlayer, true);
        }
        PlayAura("DYN244", $currentPlayer);
        PlayAura($CID_Frailty, $otherPlayer);
        return "";
      default: return "";
    }
  }

  function OUTHitEffect($cardID)
  {
    global $mainPlayer, $defPlayer;
    global $CID_BloodRotPox, $CID_Frailty, $CID_Inertia;
    $attackID = $combatChain[0];
    switch ($cardID)
    {
      case "OUT021":
        if(IsHeroAttackTarget()) PlayAura($CID_BloodRotPox, $defPlayer);
        break;
      case "OUT022":
        if(IsHeroAttackTarget()) PlayAura($CID_Frailty, $defPlayer);
        break;
      case "OUT023":
        if(IsHeroAttackTarget()) PlayAura($CID_Inertia, $defPlayer);
        break;
      case "OUT024": case "OUT025": case "OUT026":
        if(IsHeroAttackTarget()) PlayAura($CID_BloodRotPox, $defPlayer);
        break;
      case "OUT036": case "OUT037": case "OUT038":
        if(IsHeroAttackTarget()) PlayAura($CID_Inertia, $defPlayer);
        break;
      case "OUT039": case "OUT040": case "OUT041":
        if(IsHeroAttackTarget()) PlayAura($CID_Frailty, $defPlayer);
        break;
      default: break;
    }
  }

  function HasStealth($cardID)
  {
    switch($cardID)
    {
      case "OUT024": case "OUT025": case "OUT026":
      case "OUT036": case "OUT037": case "OUT038":
      case "OUT039": case "OUT040": case "OUT041":
        return true;
      default:
        return false;
    }
  }

?>
