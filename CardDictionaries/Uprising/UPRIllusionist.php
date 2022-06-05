<?php


  function UPRIllusionistCardType($cardID)
  {
    switch($cardID)
    {
      case "UPR001": case "UPR002": return "C";
      case "UPR003": return "W";
      case "UPR004": return "E";
      case "UPR005": return "A";
      case "UPR008": return "A";
      case "UPR009": return "A";
      case "UPR011": return "A";
      case "UPR018": case "UPR019": case "UPR020": return "AA";
      case "UPR033": case "UPR034": case "UPR035": return "A";
      case "UPR036": case "UPR037": case "UPR038": return "A";
      case "UPR042": case "UPR043": return "T";
      case "UPR408": return "-";
      case "UPR409": return "-";
      case "UPR411": return "-";
      default: return "";
    }
  }

  function UPRIllusionistCardSubType($cardID)
  {
    switch($cardID)
    {
      case "UPR009": case "UPR011": return "Invocation";
      case "UPR003": return "Scepter";
      case "UPR004": return "Arms";
      case "UPR005": return "Aura";
      case "UPR042": return "Dragon,Ally";
      case "UPR043": return "Ash";
      case "UPR408": return "Dragon,Ally";
      case "UPR409": return "Dragon,Ally";
      case "UPR411": return "Dragon,Ally";
      default: return "";
    }
  }

  //Minimum cost of the card
  function UPRIllusionistCardCost($cardID)
  {
    switch($cardID)
    {
      case "UPR001": case "UPR002": return -1;
      case "UPR004": return -1;
      case "UPR005": return 0;
      case "UPR008": return 4;
      case "UPR009": return 0;
      case "UPR011": return 1;
      case "UPR018": case "UPR019": case "UPR020": return 1;
      case "UPR033": case "UPR034": case "UPR035": return 1;
      case "UPR036": case "UPR037": case "UPR038": return 0;
      case "UPR042": case "UPR043": return -1;
      default: return 0;
    }
  }

  function UPRIllusionistPitchValue($cardID)
  {
    switch($cardID)
    {
      case "UPR005": return 1;
      case "UPR008": return 1;
      case "UPR009": return 1;
      case "UPR011": return 1;
      case "UPR018": case "UPR033": case "UPR036": return 1;
      case "UPR019": case "UPR034": case "UPR037": return 2;
      case "UPR020": case "UPR035": case "UPR038": return 3;
      default: return 0;
    }
  }

  function UPRIllusionistBlockValue($cardID)
  {
    switch($cardID)
    {
      case "UPR004": return 0;
      case "UPR005": return 3;
      case "UPR008": return 3;
      case "UPR009": return 3;
      case "UPR011": return 3;
      case "UPR018": case "UPR019": case "UPR020": return 3;
      case "UPR033": case "UPR034": case "UPR035": return 2;
      case "UPR036": case "UPR037": case "UPR038": return 2;
      default: return -1;
    }
  }

  function UPRIllusionistAttackValue($cardID)
  {
    switch($cardID)
    {
      case "UPR018": return 3;
      case "UPR019": return 2;
      case "UPR020": return 1;
      case "UPR042": return 1;
      case "UPR408": return 4;
      case "UPR409": return 2;
      case "UPR411": return 4;
      default: return 0;
    }
  }

  function UPRIllusionistPlayAbility($cardID, $from, $resourcesPaid)
  {
    global $currentPlayer, $CS_PitchedForThisCard, $CS_DamagePrevention;
    $rv = "";
    switch($cardID)
    {
      case "UPR004":
        Transform($currentPlayer, "Ash", "UPR042");
        return "";
      case "UPR008":
        Transform($currentPlayer, "Ash", "UPR408");
        return "";
      case "UPR009":
        Transform($currentPlayer, "Ash", "UPR409");
        return "";
      case "UPR011":
        Transform($currentPlayer, "Ash", "UPR411");
        return "";
      case "UPR018": case "UPR019": case "UPR020":
        Transform($currentPlayer, "Ash", "UPR042");
        return "";
      case "UPR033": case "UPR034": case "UPR035":
        PutPermanentIntoPlay($currentPlayer, "UPR043");
        if($cardID == "UPR033") $maxTransform = 3;
        else if($cardID == "UPR034") $maxTransform = 2;
        else $maxTransform = 1;
        for($i=0; $i<$maxTransform; ++$i)
        {
          Transform($currentPlayer, "Ash", "UPR042", true);
        }
        return "";
      case "UPR036": case "UPR037": case "UPR038":
        Transform($currentPlayer, "Ash", "UPR042");
        AddDecisionQueue("MZGETUNIQUEID", $currentPlayer, "-");
        AddDecisionQueue("ADDLIMITEDCURRENTEFFECT", $currentPlayer, $cardID . "," . "HAND");
        return "";
      case "UPR408":
        $deck = &GetDeck($currentPlayer);
        if(count($deck) == 0) return "You have no cards in your deck.";
        $wasRevealed = RevealCards($deck[0]);
        if($wasRevealed && PitchValue($deck[0]) == 1)
        {
          $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
          AddDecisionQueue("FINDINDICES", $otherPlayer, "HAND");
          AddDecisionQueue("CHOOSETHEIRHAND", $currentPlayer, "<-", 1);
          AddDecisionQueue("MULTIREMOVEHAND", $otherPlayer, "-", 1);
          AddDecisionQueue("MULTIBANISH", $otherPlayer, "HAND,NA", 1);
        }
        return "";
      case "UPR409":
        //TODO: Limit duplicate targets?
        DealArcane(1, 2, "PLAYCARD", $cardID, false, $currentPlayer);
        DealArcane(1, 2, "PLAYCARD", $cardID, false, $currentPlayer);
        return "";
      default: return "";
    }
  }

  function UPRIllusionistHitEffect($cardID)
  {
    global $defPlayer, $combatChainState, $CCS_AttackFused;
    switch($cardID)
    {

      default: break;
    }
  }

  function Transform($player, $materialType, $into, $optional=false)
  {
    AddDecisionQueue("FINDINDICES", $player, "PERMSUBTYPE," . $materialType);
    AddDecisionQueue("SETDQCONTEXT", $player, "Choose a material to transform", 1);
    if($optional) AddDecisionQueue("MAYCHOOSEPERMANENT", $player, "<-", 1);
    else AddDecisionQueue("CHOOSEPERMANENT", $player, "<-", 1);
    AddDecisionQueue("TRANSFORM", $player, $into, 1);
  }

  function ResolveTransform($player, $materialIndex, $into)
  {
    $materialType = RemovePermanent($player, $materialIndex);
    return PlayAlly($into, $player, $materialType);//Right now transform only happens into allies
  }

?>
