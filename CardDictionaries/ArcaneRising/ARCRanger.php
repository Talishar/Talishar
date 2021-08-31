<?php


  function ARCRangerCardType($cardID)
  {
    switch($cardID)
    {
      case "ARC038": case "ARC039": return "C";
      case "ARC040": return "W";
      case "ARC041": case "ARC042": return "E";
      case "ARC043": return "AA";
      case "ARC044": return "A";
      case "ARC045": return "AA";
      case "ARC046": case "ARC047": return "A";
      case "ARC048": case "ARC049": case "ARC050": return "DR";
      case "ARC051": case "ARC052": case "ARC053": return "A";
      case "ARC054": case "ARC055": case "ARC056": return "A";
      case "ARC057": case "ARC058": case "ARC059": return "AA";
      case "ARC060": case "ARC061": case "ARC062": return "AA";
      case "ARC063": case "ARC064": case "ARC065": return "AA";
      case "ARC066": case "ARC067": case "ARC068": return "AA";
      case "ARC069": case "ARC070": case "ARC071": return "AA";
      case "ARC072": case "ARC073": case "ARC074": return "AA";
      default: return "";
    }
  }

  function ARCRangerCardSubType($cardID)
  {
    switch($cardID)
    {
      case "ARC040": return "Bow";
      case "ARC041": return "Head";
      case "ARC042": return "Arms";
      case "ARC043": case "ARC045":
      case "ARC057": case "ARC058": case "ARC059":
      case "ARC060": case "ARC061": case "ARC062":
      case "ARC063": case "ARC064": case "ARC065":
      case "ARC066": case "ARC067": case "ARC068":
      case "ARC069": case "ARC070": case "ARC071":
      case "ARC072": case "ARC073": case "ARC074": return "Arrow";
      default: return "";
    }
  }

  //Minimum cost of the card
  function ARCRangerCardCost($cardID)
  {
    switch($cardID)
    {
      case "ARC038": case "ARC039": return 0;
      case "ARC040": return 1;
      case "ARC041": case "ARC042": return 0;
      case "ARC043": case "ARC044": return 1;
      case "ARC045": case "ARC046": case "ARC047": return 0;
      case "ARC048": case "ARC049": case "ARC050": return 0;
      case "ARC051": case "ARC052": case "ARC053": return 1;
      case "ARC054": case "ARC055": case "ARC056": return 0;
      case "ARC057": case "ARC058": case "ARC059": return 1;
      case "ARC060": case "ARC061": case "ARC062": return 1;
      case "ARC063": case "ARC064": case "ARC065": return 0;
      case "ARC066": case "ARC067": case "ARC068": return 1;
      case "ARC069": case "ARC070": case "ARC071": return 0;
      case "ARC072": case "ARC073": case "ARC074": return 1;
      default: return 0;
    }
  }

  function ARCRangerPitchValue($cardID)
  {
    switch($cardID)
    {
      case "ARC038": case "ARC039": case "ARC040": case "ARC041": case "ARC042": return 0;
      case "ARC043": case "ARC044": case "ARC045": return 1;
      case "ARC046": return 3;
      case "ARC047": return 2;
      case "ARC048": case "ARC051": case "ARC054": case "ARC057": case "ARC060": case "ARC063": case "ARC066": case "ARC069": case "ARC072": return 1;
      case "ARC049": case "ARC052": case "ARC055": case "ARC058": case "ARC061": case "ARC064": case "ARC067": case "ARC070": case "ARC073": return 2;
      case "ARC050": case "ARC053": case "ARC056": case "ARC059": case "ARC062": case "ARC065": case "ARC068": case "ARC071": case "ARC074": return 3;
      default: return 3;
    }
  }

  function ARCRangerBlockValue($cardID)
  {
    switch($cardID)
    {
      case "ARC038": case "ARC039": case "ARC040": return 0;
      case "ARC041": return 1;
      case "ARC042": return 0;
      case "ARC044": case "ARC047": return 2;
      case "ARC048": return 4;
      case "ARC049": return 3;
      case "ARC050": return 2;
      case "ARC051": case "ARC052": case "ARC053": return 2;
      case "ARC054": case "ARC055": case "ARC056": return 2;
      default: return 3;
    }
  }

  function ARCRangerAttackValue($cardID)
  {
    switch($cardID)
    {
      case "ARC043": return 5;
      case "ARC045": return 4;
      case "ARC060": case "ARC066": return 5;
      case "ARC057": case "ARC061": case "ARC063": case "ARC067": case "ARC069": case "ARC072": return 4;
      case "ARC058": case "ARC062": case "ARC064": case "ARC068": case "ARC070": case "ARC073": return 3;
      case "ARC059": case "ARC065": case "ARC071": case "ARC074": return 2;
      default: return 0;
    }
  }

  function ARCRangerPlayAbility($cardID, $from, $resourcesPaid)
  {
    global $currentPlayer, $myArsenal;
    switch($cardID)
    {
      case "ARC038": case "ARC039":
        if($myArsenal == "") return "There is no card in your arsenal.";
        AddBottomMyDeck($myArsenal, "ARS");
        $myArsenal = "";
        $newCard = RemoveTopMyDeck();
        if($newCard != "") AddMyArsenal($newCard, "DECK", "UP");
        if(CardSubType($newCard) == "Arrow") AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "ARC040":
        if($myArsenal != "") return "There is already a card in your arsenal, so you cannot put an arrow in your arsenal.";
        AddDecisionQueue("FINDINDICES", $currentPlayer, "MYHANDARROW");
        AddDecisionQueue("MAYCHOOSEHAND", $currentPlayer, "<-", 1);
        AddDecisionQueue("REMOVEMYHAND", $currentPlayer, "-", 1);
        AddDecisionQueue("ADDMYARSENALFACEUP", $currentPlayer, "HAND", 1);
        AddDecisionQueue("DRAW", $currentPlayer, "-", 1);
        return "";
      case "ARC041":
        SetMyArsenalFacing("UP");
        Opt($cardID, 1);
        return "";
      case "ARC042":
        if($myArsenal != "") return "There is already a card in your arsenal, so you cannot put an arrow in your arsenal.";
        AddDecisionQueue("FINDINDICES", $currentPlayer, "MYHANDARROW");
        AddDecisionQueue("MAYCHOOSEHAND", $currentPlayer, "<-", 1);
        AddDecisionQueue("REMOVEMYHAND", $currentPlayer, "-", 1);
        AddDecisionQueue("ADDMYARSENALFACEUP", $currentPlayer, "HAND", 1);
        AddDecisionQueue("ADDCURRENTEFFECT", $currentPlayer, "ARC042", 1);
        return "";
      case "ARC044":
        MyDrawCard();
        MyDrawCard();
        MyDrawCard();
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "Three of a Kind drew three cards and restricted you to playing cards from arsenal this turn.";
      case "ARC046":
        AddDecisionQueue("FINDINDICES", $currentPlayer, "MYDECKARROW");
        AddDecisionQueue("CHOOSEDECK", $currentPlayer, "<-", 1);
        AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-", 1);
        AddDecisionQueue("MULTIADDTOPDECK", $currentPlayer, "-", 1);
        AddDecisionQueue("REVEALCARD", $currentPlayer, "-", 1);
        Reload();
        return "";
      case "ARC047":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        Reload();
        return "Rapid fire gives arrows you control Go Again this turn and allows you to reload.";
      case "ARC048": case "ARC049": case "ARC050":
        Reload();
        return "Take cover allows you to reload.";
      case "ARC054": case "ARC055": case "ARC056":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        Reload();
        return "Take Aim gives your next Ranger attack action card +" . EffectAttackModifier($cardID) . " and allows you to reload.";
      default: return "";
    }
  }

  function ARCRangerHitEffect($cardID)
  {
    global $defPlayer, $defHealth, $combatChainState, $CCS_GoesWhereAfterLinkResolves;
    switch($cardID)
    {
      case "ARC043":
        AddNextTurnEffect($cardID, $defPlayer);
        break;
      case "ARC045":
        $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "HAND";
        break;
      case "ARC060": case "ARC061": case "ARC062":
        AddNextTurnEffect($cardID, $defPlayer);
        break;
      case "ARC066": case "ARC067": case "ARC068":
        $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "BOTDECK";
        break;
      case "ARC069": case "ARC070": case "ARC071":
        PlayerLoseHealth(1, $defHealth);
        break;
      default: break;
    }
  }

  function Reload()
  {
    global $myArsenal, $currentPlayer;
    if($myArsenal != "")
    {
      WriteLog("There is already a card in your arsenal, so you cannot Reload.");
      return;
    }
    AddDecisionQueue("FINDINDICES", $currentPlayer, "MYHAND");
    AddDecisionQueue("MAYCHOOSEHAND", $currentPlayer, "<-", 1);
    AddDecisionQueue("MULTIREMOVEHAND", $currentPlayer, "-", 1);
    AddDecisionQueue("ADDMYARSENAL", $currentPlayer, "HAND", 1);
  }

?>

