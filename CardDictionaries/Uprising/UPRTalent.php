<?php


  function UPRTalentCardType($cardID)
  {
    switch($cardID)
    {
      case "UPR086": return "AA";
      case "UPR090": return "AA";
      case "UPR097": return "AA";
      case "UPR098": case "UPR099": case "UPR100": return "AA";
      case "UPR101": return "AA";
      case "UPR139": return "A";
      case "UPR147": case "UPR148": case "UPR149": return "A";
      default: return "";
    }
  }

  function UPRTalentCardSubType($cardID)
  {
    switch($cardID)
    {
      case "UPR139": return "Affliction,Aura";
      default: return "";
    }
  }

  //Minimum cost of the card
  function UPRTalentCardCost($cardID)
  {
    switch($cardID)
    {
      case "UPR086": return 2;
      case "UPR090": return 2;
      case "UPR097": return 0;
      case "UPR098": case "UPR099": case "UPR100": return 0;
      case "UPR101": return 0;
      case "UPR139": return 0;
      case "UPR147": case "UPR148": case "UPR149": return 1;
      default: return 0;
    }
  }

  function UPRTalentPitchValue($cardID)
  {
    switch($cardID)
    {
      case "UPR086": return 1;
      case "UPR090": return 1;
      case "UPR097": return 1;
      case "UPR098": return 1;
      case "UPR099": return 2;
      case "UPR100": return 3;
      case "UPR101": return 1;
      case "UPR139": return 3;
      case "UPR147": return 1;
      case "UPR148": return 2;
      case "UPR149": return 3;
      default: return 0;
    }
  }

  function UPRTalentBlockValue($cardID)
  {
    switch($cardID)
    {
      case "UPR086": return 2;
      case "UPR090": return 3;
      case "UPR097": return 2;
      case "UPR098": case "UPR099": case "UPR100": return 3;
      case "UPR101": return -1;
      case "UPR139": return 2;
      case "UPR147": case "UPR148": case "UPR149": return 2;
      default: return -1;
    }
  }

  function UPRTalentAttackValue($cardID)
  {
    switch($cardID)
    {
      case "UPR086": return 6;
      case "UPR090": return 4;
      case "UPR097": return 1;
      case "UPR098": case "UPR099": case "UPR100": return 2;
      case "UPR101": return 0;
      default: return 0;
    }
  }

  function UPRTalentPlayAbility($cardID, $from, $resourcesPaid)
  {
    global $currentPlayer, $CS_PlayIndex, $CS_NumRedPlayed;
    $rv = "";
    $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
    switch($cardID)
    {
      case "UPR097":
        if(GetClassState($currentPlayer, $CS_NumRedPlayed) > 0)
        {
          AddDecisionQueue("FINDINDICES", $currentPlayer, "GYCARD,UPR101");
          AddDecisionQueue("CHOOSEDISCARD", $currentPlayer, "<-", 1);
          AddDecisionQueue("REMOVEDISCARD", $currentPlayer, "-", 1);
          AddDecisionQueue("ADDHAND", $currentPlayer, "-", 1);
        }
        return "";
      case "UPR147": case "UPR148": case "UPR149":
        if($cardID == "UPR147") $cost = 3;
        else if($cardID == "UPR148") $cost = 2;
        else $cost = 1;
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose if you want to pay $cost to prevent an arsenal or ally from being frozen");
        AddDecisionQueue("BUTTONINPUT", $otherPlayer, "0," . $cost, 0, 1);
        AddDecisionQueue("PAYRESOURCES", $otherPlayer, "<-", 1);
        AddDecisionQueue("GREATERTHANPASS", $otherPlayer, "0", 1);
        AddDecisionQueue("FINDINDICES", $currentPlayer, "SEARCHMZ,THEIRALLY|THEIRARS", 1);
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose which card you want to freeze", 1);
        AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("MZOP", $currentPlayer, "FREEZE", 1);
        if($from == "ARS") MyDrawCard();
        return "";
      default: return "";
    }
  }

  function UPRTalentHitEffect($cardID)
  {
    switch($cardID)
    {

      default: break;
    }
  }

  function HasRupture($cardID)
  {
    switch($cardID)
    {
      case "UPR098": case "UPR099": case "UPR100": return true;
      default: return false;
    }
  }

  function RuptureActive($beforePlay=false)
  {
    global $combatChainState, $CCS_NumChainLinks;
    $target = ($beforePlay ? 3 : 4);
    if($combatChainState[$CCS_NumChainLinks] >= $target) return true;
    return false;
  }

  function NumDraconicChainLinks()
  {
    global $chainLinks, $combatChain;
    $numLinks = 0;
    for($i=0; $i<count($chainLinks); ++$i)
    {
      if(CardTalent($chainLinks[$i][0]) == "DRACONIC") ++$numLinks;
    }
    if(count($combatChain) > 0 && CardTalent($combatChain[0]) == "DRACONIC") ++$numLinks;
    return $numLinks;
  }

  function NumPhoenixFlameChainLinks()
  {
    global $chainLinks, $combatChain;
    $numLinks = 0;
    for($i=0; $i<count($chainLinks); ++$i)
    {
      if($chainLinks[$i][0] == "UPR101") ++$numLinks;
    }
    if(count($combatChain) > 0 && $combatChain[0] == "UPR101") ++$numLinks;
    return $numLinks;
  }

?>
