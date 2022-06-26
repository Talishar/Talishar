<?php

  function RVDCardType($cardID)
  {
    switch($cardID)
    {
      //Hero
      case "RVD001": return "C";
      //Equipment
      case "RVD002": return "W";
      case "RVD003": case "RVD004": case "RVD005": case "RVD006": return "E";
      //Mentor
      case "RVD007": return "M";
      //Action
      case "RVD025": return "A";
      //Attack Action
      case "RVD009": return "AA";
      case "RVD013": return "AA";
      case "RVD015": return "AA";
      case "RVD018": return "AA";
      //Defense Reaction
      case "RVD026": return "DR";
      //Bauble
      case "RVD027": return "R";
      default: return "";
    }
  }

  function RVDCardSubType($cardID)
  {
    switch($cardID)
    {
      case "RVD002": return "Club";
      case "RVD003": return "Head";
      case "RVD004": return "Chest";
      case "RVD005": return "Arms";
      case "RVD006": return "Legs";
      default: return "";
    }
  }

  //Minimum cost of the card
  function RVDCardCost($cardID)
  {
    switch($cardID)
    {
      case "RVD002": return 2;
      case "RVD009": return 3;
      case "RVD013": return 3;
      case "RVD015": return 3;
      case "RVD018": return 3;
      default: return 0;
    }
  }

  function RVDPitchValue($cardID)
  {
    switch($cardID)
    {
      case "RVD001": case "RVD002": case "RVD007": return 0;
      case "RVD003": case "RVD004": case "RVD005": case "RVD006": return 0;
      case "RVD009": case "RVD013": return 1;
      case "RVD015": return 2;
      case "RVD018": return 2;
      default: return 3;
    }
  }

  function RVDBlockValue($cardID)
  {
    switch($cardID)
    {
      case "RVD001": case "RVD002": case "RVD013": return -1;
      case "RVD004": return 0;
      case "RVD003": return 1;
      case "RVD018": return 2;
      case "RVD026": return 2;
      default: return 3;
    }
  }

  function RVDAttackValue($cardID)
  {
    switch($cardID)
    {
      case "RVD002": return 4;
      case "RVD009": return 6;
      case "RVD013": case "RVD015": return 6;
      case "RVD018": return 6;
      default: return 0;
    }
  }

  function RVDEffectAttackModifier($cardID)
  {
    switch($cardID)
    {
      case "RVD009": return 2;
      default: return 0;
    }
  }


  function RVDHasGoAgain($cardID)
  {
    global $mainPlayer, $CS_NumAuras;
    switch($cardID)
    {
      case "RVD004": return true;
      case "RVD025": return true;
      default: return false;
    }
  }

  function RVDAbilityType($cardID, $index=-1)
  {
    switch($cardID)
    {
      case "RVD002": return "AA";
      case "RVD004": return "A";
      default: return "";
    }
  }

  function RVDAbilityCost($cardID, $index=-1)
  {
    switch($cardID)
    {
      case "RVD002": return 2;
      case "RVD004": return 0;
      default: return "";
    }
  }

  function RVDPlayAbility($cardID, $from, $resourcesPaid)
  {
    global  $combatChain, $currentPlayer;
    $rv = "";
    switch($cardID)
    {
      case "RVD004":
        $resources = &GetResources($currentPlayer);
        $resources[0] += 1;
        return "Blossom of Spring added 1 resource.";

      case "RVD013":
        MyDrawCard();
        $card = DiscardRandom();
        $rv = "Wrecking Ball discarded" . CardLink($card, $card);
        if(AttackValue($card) >= 6)
        {
          Intimidate();
          $rv .= " and intimidate from discarding a card with 6 or more power";
        }
        $rv .= ".";
        return $rv;
      case "RVD025":
        $rv = "Clearing Bellow Intimidated";
        Intimidate();
        return $rv;
    }
  }

  function RVDHitEffect($cardID)
  {

  }

  function RVDCombatEffectActive($cardID, $attackID)
  {
    global $combatChain, $mainPlayer;
    $params = explode(",", $cardID);
    $cardID = $params[0];
    if(count($params) > 1) $parameter = $params[1];
    switch($cardID)
    {
      // case "RVD009": return ;  Need to be coded.
      default: return false;
    }
  }

  function ChiefRukutanAbility($player, $index)
  {
    $log = "Chief Ruk'utan Intimidates";
    Intimidate();
    $arsenal = &GetArsenal($player);
    ++$arsenal[$index+3];
    if($arsenal[$index+3] == 2)
    {
      $log .= " and searchs for an Alpha Rampage card";
      RemoveArsenal($player, $index);
      BanishCardForPlayer("RVD007", $player, "ARS", "-");
      AddDecisionQueue("FINDINDICES", $player, "DECKCARD,WTR006");
      AddDecisionQueue("CHOOSEDECK", $player, "<-", 1);
      AddDecisionQueue("ADDARSENALFACEUP", $player, "DECK", 1);
      AddDecisionQueue("SHUFFLEDECK", $player, "-", 1);
    }
    WriteLog($log . ".");
  }

?>
