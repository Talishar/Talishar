<?php


  function ARCRangerPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = "")
  {
    global $currentPlayer;
    switch($cardID)
    {
      case "ARC038": case "ARC039":
        if(ArsenalEmpty($currentPlayer)) return "There is no card in your arsenal.";
        AddDecisionQueue("FINDINDICES", $currentPlayer, "ARSENAL");
        AddDecisionQueue("CHOOSEARSENAL", $currentPlayer, "<-", 1);
        AddDecisionQueue("REMOVEARSENAL", $currentPlayer, "-", 1);
        AddDecisionQueue("ADDBOTDECK", $currentPlayer, "-", 1);
        AddDecisionQueue("PARAMDELIMTOARRAY", $currentPlayer, "0", 1);
        AddDecisionQueue("MULTIREMOVEDECK", $currentPlayer, "-", 1);
        AddDecisionQueue("ADDARSENAL", $currentPlayer, "DECK-UP", 1);
        AddDecisionQueue("ALLCARDSUBTYPEORPASS", $currentPlayer, "Arrow", 1);
        AddDecisionQueue("LASTARSENALADDEFFECT", $currentPlayer, $cardID . ",DECK", 1);
        return "";
      case "ARC040":
        if(!ArsenalEmpty($currentPlayer)) return "Your arsenal is not empty so you cannot load an arrow.";
        LoadArrow($currentPlayer);
        AddDecisionQueue("DRAW", $currentPlayer, "-", 1);
        return "";
      case "ARC041":
        Opt($cardID, 1);
        return "";
      case "ARC042":
        if(!ArsenalEmpty($currentPlayer)) return "Your arsenal is not empty so you cannot load an arrow.";
        LoadArrow($currentPlayer);
        AddDecisionQueue("LASTARSENALADDEFFECT", $currentPlayer, $cardID . ",HAND", 1);
        return "";
      case "ARC044":
        Draw($currentPlayer);
        Draw($currentPlayer);
        Draw($currentPlayer);
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "ARC046":
        AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYDECK:subtype=Arrow");
        AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("MZREMOVE", $currentPlayer, "-", 1);
        AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-");
        AddDecisionQueue("REVEALCARDS", $currentPlayer, "-", 1);
        AddDecisionQueue("MULTIADDTOPDECK", $currentPlayer, "-", 1);
        Reload();
        return "";
      case "ARC047":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        Reload();
        return "";
      case "ARC048": case "ARC049": case "ARC050":
        Reload();
        return "";
      case "ARC051": case "ARC052": case "ARC053":
        if(!ArsenalEmpty($currentPlayer)) return "Did nothing because your arsenal is not empty";
        if($cardID == "ARC051") $count = 4;
        else if($cardID == "ARC052") $count = 3;
        else $count = 2;
        AddDecisionQueue("FINDINDICES", $currentPlayer, "DECKTOPXREMOVE," . $count);
        AddDecisionQueue("SETDQVAR", $currentPlayer, "0", 1);
        AddDecisionQueue("FILTER", $currentPlayer, "LastResult-include-subtype-Arrow", 1);
        AddDecisionQueue("CHOOSECARD", $currentPlayer, "<-", 1);
        AddDecisionQueue("ADDARSENAL", $currentPlayer, "DECK-UP", 1);
        AddDecisionQueue("OP", $currentPlayer, "REMOVECARD");
        AddDecisionQueue("CHOOSEBOTTOM", $currentPlayer, "<-");
        return "";
      case "ARC054": case "ARC055": case "ARC056":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        Reload();
        return "";
      default: return "";
    }
  }

  function ARCRangerHitEffect($cardID)
  {
    global $defPlayer, $combatChainState, $CCS_GoesWhereAfterLinkResolves;
    switch($cardID)
    {
      case "ARC043":
        if(IsHeroAttackTarget()) AddNextTurnEffect($cardID, $defPlayer);
        break;
      case "ARC045":
        $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "HAND";
        break;
      case "ARC060": case "ARC061": case "ARC062":
        if(IsHeroAttackTarget()) AddNextTurnEffect($cardID, $defPlayer);
        break;
      case "ARC066": case "ARC067": case "ARC068":
        $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "BOTDECK";
        break;
      case "ARC069": case "ARC070": case "ARC071":
        if(IsHeroAttackTarget()) PlayerLoseLife($defPlayer, 1);
        break;
      default: break;
    }
    return "";
  }

  function LoadArrow($player, $facing = "UP")
  {
    if(ArsenalFull($player))
    {
      AddDecisionQueue("PASSPARAMETER", $player, "PASS");//Pass any subsequent load effects
      return "Your arsenal is full, so you cannot put an arrow in your arsenal";
    }
    MZMoveCard($player, "MYHAND:subtype=Arrow", "MYARSENAL,HAND," . $facing, may:true, silent:true);
  }

  function Reload($player=0)
  {
    global $currentPlayer;
    if($player == 0) $player = $currentPlayer;
    if(!ArsenalEmpty($player)) { WriteLog("Your arsenal is not empty, so you cannot Reload"); return; }
    MZMoveCard($player, "MYHAND", "MYARSENAL,HAND,DOWN", may:true, silent:true);
  }

  function SuperReload($player=0)
  {
    global $currentPlayer;
    if($player == 0) $player = $currentPlayer;
    if(ArsenalFull($player)) { WriteLog("Your arsenal is full, so you do not arsenal a card"); return; }
    MZMoveCard($player, "MYHAND", "MYARSENAL,HAND,DOWN", may:true, silent:true);
  }

?>
