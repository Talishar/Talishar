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
      case "ARC038": case "ARC039": case "ARC040": return -1;
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
        AddDecisionQueue("NULLPASS", $currentPlayer, "-", 1);
        AddDecisionQueue("ADDARSENALFACEUP", $currentPlayer, "DECK", 1);
        AddDecisionQueue("ALLCARDSUBTYPEORPASS", $currentPlayer, "Arrow", 1);
        AddDecisionQueue("ADDCURRENTEFFECT", $currentPlayer, $cardID, 1);
        return "";
      case "ARC040":
        if(!ArsenalEmpty($currentPlayer)) return "There is already a card in your arsenal, so you cannot put an arrow in your arsenal.";
        AddDecisionQueue("FINDINDICES", $currentPlayer, "MYHANDARROW");
        AddDecisionQueue("MAYCHOOSEHAND", $currentPlayer, "<-", 1);
        AddDecisionQueue("REMOVEMYHAND", $currentPlayer, "-", 1);
        AddDecisionQueue("ADDARSENALFACEUP", $currentPlayer, "HAND", 1);
        AddDecisionQueue("DRAW", $currentPlayer, "-", 1);
        return "";
      case "ARC041":
        if(ArsenalHasFaceDownCard($currentPlayer))
        {
          SetArsenalFacing("UP", $currentPlayer);
          Opt($cardID, 1);
        }
        return "";
      case "ARC042":
        if(!ArsenalEmpty($currentPlayer)) return "There is already a card in your arsenal, so you cannot put an arrow in your arsenal.";
        AddDecisionQueue("FINDINDICES", $currentPlayer, "MYHANDARROW");
        AddDecisionQueue("MAYCHOOSEHAND", $currentPlayer, "<-", 1);
        AddDecisionQueue("REMOVEMYHAND", $currentPlayer, "-", 1);
        AddDecisionQueue("ADDARSENALFACEUP", $currentPlayer, "HAND", 1);
        AddDecisionQueue("ADDCURRENTEFFECT", $currentPlayer, "ARC042", 1);
        return "";
      case "ARC044":
        MyDrawCard();
        MyDrawCard();
        MyDrawCard();
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "Draws 3 cards and restrict you to playing cards from arsenal this turn.";
      case "ARC046":
        AddDecisionQueue("FINDINDICES", $currentPlayer, "MYDECKARROW");
        AddDecisionQueue("CHOOSEDECK", $currentPlayer, "<-", 1);
        AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-", 1);
        AddDecisionQueue("MULTIADDTOPDECK", $currentPlayer, "-", 1);
        AddDecisionQueue("REVEALCARDS", $currentPlayer, "-", 1);
        Reload();
        return "";
      case "ARC047":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        Reload();
        return "Gives arrows you control go again this turn and allows you to reload.";
      case "ARC048": case "ARC049": case "ARC050":
        Reload();
        return "Take cover allows you to reload.";
      case "ARC051": case "ARC052": case "ARC053":
        if(!ArsenalEmpty($currentPlayer)) return "Did nothing because your arsenal is not empty.";
        if($cardID == "ARC051") $count = 4;
        else if($cardID == "ARC052") $count = 3;
        else $count = 2;
        $deck = &GetDeck($currentPlayer);
        $cards = "";
        $arrows = "";
        for($i=0; $i<$count; ++$i)
        {
          if(count($deck) > 0)
          {
            if($cards != "") $cards .= ",";
            $card = array_shift($deck);
            $cards .= $card;
            if(CardSubtype($card) == "Arrow")
            {
              if($arrows != "") $arrows .= ",";
              $arrows .= $card;
            }
          }
        }
        if($arrows != "")
        {
          AddDecisionQueue("CHOOSECARD", $currentPlayer, $arrows, 1);
          AddDecisionQueue("ADDARSENALFACEUP", $currentPlayer, "DECK");
        }
        if($cards != "")
        {
          AddDecisionQueue("REMOVELAST", $currentPlayer, $cards, 1);
          AddDecisionQueue("CHOOSEBOTTOM", $currentPlayer, "<-", 1);
        }
        return "Lets you load an arrow and rearrange the rest of the cards on the bottom of your deck.";
      case "ARC054": case "ARC055": case "ARC056":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        Reload();
        return "Gives your next Ranger attack action card +" . EffectAttackModifier($cardID) . " and allows you to reload.";
      default: return "";
    }
  }

  function ARCRangerHitEffect($cardID)
  {
    global $defPlayer, $combatChainState, $CCS_GoesWhereAfterLinkResolves;
    switch($cardID)
    {
      case "ARC043":
        if(IsHeroAttackTarget())
        {
          AddNextTurnEffect($cardID, $defPlayer);
        }
        break;
      case "ARC045":
        $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "HAND";
        break;
      case "ARC060": case "ARC061": case "ARC062":
        if(IsHeroAttackTarget())
        {
          AddNextTurnEffect($cardID, $defPlayer);
        }
        break;
      case "ARC066": case "ARC067": case "ARC068":
        $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "BOTDECK";
        break;
      case "ARC069": case "ARC070": case "ARC071":
        if(IsHeroAttackTarget())
        {
          PlayerLoseHealth($defPlayer, 1);
        }
        break;
      default: break;
    }
  }

  function Reload($player=0)
  {
    global $currentPlayer;
    if($player == 0) $player = $currentPlayer;
    if(!ArsenalEmpty($player))
    {
      WriteLog("Your arsenal is not empty, so you cannot Reload.");
      return;
    }
    AddDecisionQueue("FINDINDICES", $player, "HAND");
    AddDecisionQueue("SETDQCONTEXT", $player, "Choose a card to Reload");
    AddDecisionQueue("MAYCHOOSEHAND", $player, "<-", 1);
    AddDecisionQueue("MULTIREMOVEHAND", $player, "-", 1);
    AddDecisionQueue("ADDARSENALFACEDOWN", $player, "HAND", 1);
  }

?>
