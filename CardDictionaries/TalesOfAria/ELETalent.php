<?php


  function ELETalentCardType($cardID)
  {
    switch($cardID)
    {
      case "ELE000": return "A";
      case "ELE091": return "A";
      case "ELE092": return "A";
      case "ELE093": return "I";
      case "ELE094": case "ELE095": case "ELE096": return "AA";
      case "ELE097": case "ELE098": case "ELE099": return "AA";
      case "ELE100": case "ELE101": case "ELE102": return "AA";
      case "ELE103": case "ELE104": case "ELE105": return "A";
      case "ELE106": case "ELE107": case "ELE108": return "A";
      case "ELE109": return "T";
      case "ELE110": return "T";
      case "ELE111": return "T";
      case "ELE112": return "I";
      case "ELE113": return "A";
      case "ELE114": return "DR";
      case "ELE115": return "E";
      case "ELE116": return "E";
      case "ELE117": return "A";
      case "ELE118": return "A";
      case "ELE119": case "ELE120": case "ELE121": return "AA";
      case "ELE122": case "ELE123": case "ELE124": return "A";
      case "ELE125": case "ELE126": case "ELE127": return "I";
      case "ELE128": case "ELE129": case "ELE130": return "AA";
      case "ELE131": case "ELE132": case "ELE133": return "AA";
      case "ELE134": case "ELE135": case "ELE136": return "AA";
      case "ELE137": case "ELE138": case "ELE139": return "A";
      case "ELE140": case "ELE141": case "ELE142": return "A";
      case "ELE143": return "A";
      case "ELE144": return "E";
      case "ELE145": return "E";
      case "ELE146": return "A";
      case "ELE147": return "I";
      case "ELE148": case "ELE149": case "ELE150": return "AA";
      case "ELE151": case "ELE152": case "ELE153": return "A";
      case "ELE154": case "ELE155": case "ELE156": return "A";
      case "ELE157": case "ELE158": case "ELE159": return "AA";
      case "ELE160": case "ELE161": case "ELE162": return "AA";
      case "ELE163": case "ELE164": case "ELE165": return "A";
      case "ELE166": case "ELE167": case "ELE168": return "A";
      case "ELE169": case "ELE170": case "ELE171": return "A";
      case "ELE172": return "A";
      case "ELE173": return "E";
      case "ELE174": return "E";
      case "ELE175": return "A";
      case "ELE176": return "I";
      case "ELE177": case "ELE178": case "ELE179": return "A";
      case "ELE180": case "ELE181": case "ELE182": return "A";
      case "ELE183": case "ELE184": case "ELE185": return "I";
      case "ELE186": case "ELE187": case "ELE188": return "AA";
      case "ELE189": case "ELE190": case "ELE191": return "AA";
      case "ELE192": case "ELE193": case "ELE194": return "AA";
      case "ELE195": case "ELE196": case "ELE197": return "AA";
      case "ELE198": case "ELE199": case "ELE200": return "A";
      case "ELE201": return "A";
      case "ELE233": case "ELE234": case "ELE235": case "ELE236": return "E";
      default: return "";
    }
  }

  function ELETalentCardSubType($cardID)
  {
    switch($cardID)
    {
      case "ELE000": return "Landmark";
      case "ELE109": return "Aura";
      case "ELE110": return "Aura";
      case "ELE111": return "Aura";
      case "ELE115": return "Head";
      case "ELE116": return "Head";
      case "ELE117": return "Aura";
      case "ELE143": return "Item";
      case "ELE144": return "Chest";
      case "ELE145": return "Chest";
      case "ELE146": return "Aura";
      case "ELE172": return "Item";
      case "ELE173": return "Arms";
      case "ELE174": return "Arms";
      case "ELE175": return "Aura";
      case "ELE201": return "Item";
      case "ELE233": return "Head";
      case "ELE234": return "Chest";
      case "ELE235": return "Arms";
      case "ELE236": return "Legs";
      default: return "";
    }
  }

  //Minimum cost of the card
  function ELETalentCardCost($cardID)
  {
    switch($cardID)
    {
      case "ELE000": return 1;
      case "ELE091": return 2;
      case "ELE092": return 1;
      case "ELE093": return 2;
      case "ELE094": case "ELE095": case "ELE096": return 2;
      case "ELE097": case "ELE098": case "ELE099": return 1;
      case "ELE100": case "ELE101": case "ELE102": return 0;
      case "ELE103": case "ELE104": case "ELE105": return 0;
      case "ELE106": case "ELE107": case "ELE108": return 1;
      case "ELE112": return 0;
      case "ELE113": return 0;
      case "ELE114": return 2;
      case "ELE117": return 3;
      case "ELE118": return 3;
      case "ELE119": case "ELE120": case "ELE121": return 3;
      case "ELE122": case "ELE123": case "ELE124": return 0;
      case "ELE125": case "ELE126": case "ELE127": return 0;
      case "ELE128": case "ELE129": case "ELE130": return 3;
      case "ELE131": case "ELE132": case "ELE133": return 3;
      case "ELE134": case "ELE135": case "ELE136": return 2;
      case "ELE137": case "ELE138": case "ELE139": return 2;
      case "ELE140": case "ELE141": case "ELE142": return 1;
      case "ELE143": return 0;
      case "ELE146": return 2;
      case "ELE147": return 0;
      case "ELE148": case "ELE149": case "ELE150": return 2;
      case "ELE151": case "ELE152": case "ELE153": return 1;
      case "ELE154": case "ELE155": case "ELE156": return 0;
      case "ELE157": case "ELE158": case "ELE159": return 2;
      case "ELE160": case "ELE161": case "ELE162": return 2;
      case "ELE163": case "ELE164": case "ELE165": return 0;
      case "ELE166": case "ELE167": case "ELE168": return 1;
      case "ELE169": case "ELE170": case "ELE171": return 0;
      case "ELE172": return 0;
      case "ELE175": return 1;
      case "ELE176": return 0;
      case "ELE177": case "ELE178": case "ELE179": return 0;
      case "ELE180": case "ELE181": case "ELE182": return 0;
      case "ELE183": case "ELE184": case "ELE185": return 0;
      case "ELE186": case "ELE187": case "ELE188": return 0;
      case "ELE189": case "ELE190": case "ELE191": return 0;
      case "ELE192": case "ELE193": case "ELE194": return 1;
      case "ELE195": case "ELE196": case "ELE197": return 1;
      case "ELE198": case "ELE199": case "ELE200": return 1;
      case "ELE201": return 0;
      default: return 0;
    }
  }

  function ELETalentPitchValue($cardID)
  {
    switch($cardID)
    {
      case "ELE000": return 0;
      case "ELE091": return 2;
      case "ELE092": return 1;
      case "ELE093": return 3;
      case "ELE094": case "ELE097": case "ELE100": case "ELE103": case "ELE106": return 1;
      case "ELE095": case "ELE098": case "ELE101": case "ELE104": case "ELE107": return 2;
      case "ELE096": case "ELE099": case "ELE102": case "ELE105": case "ELE108": return 3;
      case "ELE112": return 1;
      case "ELE113": return 2;
      case "ELE114": return 3;
      //Earth
      case "ELE117": return 1;
      case "ELE118": return 3;
      case "ELE119": case "ELE122": case "ELE125": case "ELE128": case "ELE131": case "ELE134": case "ELE137": case "ELE140": return 1;
      case "ELE120": case "ELE123": case "ELE126": case "ELE129": case "ELE132": case "ELE135": case "ELE138": case "ELE141": return 2;
      case "ELE121": case "ELE124": case "ELE127": case "ELE130": case "ELE133": case "ELE136": case "ELE139": case "ELE142": return 3;
      case "ELE143": return 3;
      case "ELE146": return 3;
      case "ELE147": return 3;
      case "ELE148": case "ELE151": case "ELE154": case "ELE157": case "ELE160": case "ELE163": case "ELE166": case "ELE169": return 1;
      case "ELE149": case "ELE152": case "ELE155": case "ELE158": case "ELE161": case "ELE164": case "ELE167": case "ELE170": return 2;
      case "ELE150": case "ELE153": case "ELE156": case "ELE159": case "ELE162": case "ELE165": case "ELE168": case "ELE171": return 3;
      case "ELE172": return 3;
      case "ELE175": return 2;
      case "ELE176": return 3;
      case "ELE177": case "ELE180": case "ELE183": case "ELE186": case "ELE189": case "ELE192": case "ELE195": case "ELE198": return 1;
      case "ELE178": case "ELE181": case "ELE184": case "ELE187": case "ELE190": case "ELE193": case "ELE196": case "ELE199": return 2;
      case "ELE179": case "ELE182": case "ELE185": case "ELE188": case "ELE191": case "ELE194": case "ELE197": case "ELE200": return 3;
      case "ELE201": return 3;
      default: return 0;
    }
  }

  function ELETalentBlockValue($cardID)
  {
    switch($cardID)
    {
      case "ELE000": return 0;
      case "ELE093": return 0;
      case "ELE113": return 3;
      case "ELE114": return 6;
      case "ELE115": return 0;
      case "ELE116": return 0;
      case "ELE117": return 3;
      case "ELE125": case "ELE126": case "ELE127": return 0;
      case "ELE128": case "ELE129": case "ELE130": return 3;
      case "ELE143": return 0;
      case "ELE144": return 1;
      case "ELE145": return 0;
      case "ELE146": return 3;
      case "ELE147": return 0;
      case "ELE160": case "ELE161": case "ELE162": return 3;
      case "ELE172": return 0;
      case "ELE173": return 0;
      case "ELE175": return 3;
      case "ELE176": return 0;
      case "ELE183": case "ELE184": case "ELE185": return 0;
      case "ELE186": case "ELE187": case "ELE188": return 0;
      case "ELE192": case "ELE193": case "ELE194": return 3;
      case "ELE201": return 0;
      case "ELE233": case "ELE234": case "ELE235": case "ELE236": return 0;
      default: return 2;
    }
  }

  function ELETalentAttackValue($cardID)
  {
    switch($cardID)
    {
      //Elemental
      case "ELE094": return 6;
      case "ELE095": case "ELE097": return 5;
      case "ELE096": case "ELE098": case "ELE100": return 4;
      case "ELE099": case "ELE101": return 3;
      case "ELE102": return 2;
      //Earth
      case "ELE119": case "ELE128": case "ELE131": return 7;
      case "ELE120": case "ELE129": case "ELE132": case "ELE134": return 6;
      case "ELE121": case "ELE130": case "ELE133": case "ELE135": return 5;
      case "ELE136": return 4;
      //Ice
      case "ELE157": case "ELE160": return 6;
      case "ELE148": case "ELE158": case "ELE161": return 5;
      case "ELE149": case "ELE159": case "ELE162": return 4;
      case "ELE150": return 3;
      //Lightning
      case "ELE192": case "ELE195": return 5;
      case "ELE189": case "ELE193": case "ELE196": return 4;
      case "ELE186": case "ELE190": case "ELE194": case "ELE197": return 3;
      case "ELE187": case "ELE191": return 2;
      case "ELE188": return 1;
      default: return 0;
    }
  }

  function ELETalentPlayAbility($cardID, $from, $resourcesPaid)
  {
    global $currentPlayer, $CS_PlayIndex, $mainPlayer, $actionPoints, $combatChainState, $CCS_GoesWhereAfterLinkResolves;
    global $combatChain, $CS_DamagePrevention;
    $rv = "";
    $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
    switch($cardID)
    {
      case "ELE000":
        $rv = "Korshem is a partially manual card. Use the instant ability to destroy it when appropriate. Use the Revert Gamestate button under the Stats page if necessary.";
        if($from == "PLAY")
        {
          DestroyLandmark(GetClassState($currentPlayer, $CS_PlayIndex));
          $rv = "Korshem was destroyed";
        }
        return $rv;
      case "ELE103": case "ELE104": case "ELE105":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "Invigorate gives the next attack you Fuse this turn +" . EffectAttackModifier($cardID) . ".";
      case "ELE106": GainHealth(3, $currentPlayer); return "Rejuvenate gains 3 health.";
      case "ELE107": GainHealth(2, $currentPlayer); return "Rejuvenate gains 2 health.";
      case "ELE108": GainHealth(1, $currentPlayer); return "Rejuvenate gains 1 health.";
      case "ELE112":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "Pulse of Volthaven gives your next Lightning, Ice, or Elemental attack this turn +4.";
      case "ELE113":
        for($i=0; $i<2; ++$i)
        {
          AddDecisionQueue("FINDINDICES", $currentPlayer, $cardID);
          AddDecisionQueue("MAYCHOOSEDISCARD", $currentPlayer, "<-", 1);
          AddDecisionQueue("REMOVEDISCARD", $currentPlayer, "-", 1);
          AddDecisionQueue("MULTIADDTOPDECK", $currentPlayer, "-", 1);
        }
        return "";
      case "ELE114":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "Pulse of Isenloft gives your Ice, Earth, and Elemental action cards +1 defense this turn.";
      case "ELE115":
        AddDecisionQueue("FINDINDICES", $currentPlayer, "ARSENALDOWN");
        AddDecisionQueue("CHOOSEARSENAL", $currentPlayer, "<-", 1);
        AddDecisionQueue("REMOVEARSENAL", $currentPlayer, "-", 1);
        AddDecisionQueue("ADDBOTDECK", $currentPlayer, "-", 1);
        AddDecisionQueue("DRAW", $currentPlayer, "-", 1);
        AddDecisionQueue("ADDCLASSSTATE", $currentPlayer, $CS_DamagePrevention . "-1", 1);
        return "";
      case "ELE116":
        AddDecisionQueue("FINDINDICES", $currentPlayer, $cardID);
        AddDecisionQueue("CHOOSEDISCARD", $currentPlayer, "<-", 1);
        AddDecisionQueue("MULTIREMOVEDISCARD", $currentPlayer, "-", 1);
        AddDecisionQueue("ADDHAND", $currentPlayer, "-", 1);
        return "";
      case "ELE118":
        MyDrawCard();
        MyDrawCard();
        MyDrawCard();
        return "Tome of Harvests let you draw 3 cards.";
      case "ELE119": case "ELE120": case "ELE121":
        if($from == "ARS")
        {
          $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "BOTDECK";
          $rv = "Evergreen goes to the bottom of your deck when the combat chain closes.";
        }
        return $rv;
      case "ELE122": case "ELE123": case "ELE124":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "Weave Earth gives your next Earth or Elemental attack action card this turn +" . EffectAttackModifier($cardID) .", and +1 if it's fused.";
      case "ELE125": case "ELE126": case "ELE127":
       AddDecisionQueue("FINDINDICES", $currentPlayer, $cardID);
       AddDecisionQueue("CHOOSECOMBATCHAIN", $currentPlayer, "<-", 1);
       AddDecisionQueue("COMBATCHAINBUFFDEFENSE", $currentPlayer, PlayBlockModifier($cardID), 1);
       return "Summerwood Shelter gives target defending card " . PlayBlockModifier($cardID) . ".";
      case "ELE131": case "ELE132": case "ELE133":
        AddDecisionQueue("FINDINDICES", $currentPlayer, "ARSENAL");
        AddDecisionQueue("MAYCHOOSEARSENAL", $currentPlayer, "<-", 1);
        AddDecisionQueue("REMOVEARSENAL", $currentPlayer, "-", 1);
        AddDecisionQueue("ADDBOTDECK", $currentPlayer, "-", 1);
        AddDecisionQueue("DRAW", $currentPlayer, "-", 1);
        return "";
      case "ELE137": case "ELE138": case "ELE139":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "Earthlore Surge gives your next attack action card this turn +" . EffectAttackModifier($cardID) .".";
      case "ELE140": case "ELE141": case "ELE142":
        AddDecisionQueue("FINDINDICES", $currentPlayer, $cardID);
        AddDecisionQueue("CHOOSEDISCARD", $currentPlayer, "<-", 1);
        AddDecisionQueue("REMOVEDISCARD", $currentPlayer, "-", 1);
        AddDecisionQueue("ADDBOTDECK", $currentPlayer, "-", 1);
        if($from == "ARS") MyDrawCard();
        return "";
      case "ELE143":
        if($from == "PLAY")
        {
          $index = GetClassState($currentPlayer, $CS_PlayIndex);
          if($index != -1)
          {
            AddCurrentTurnEffect($cardID, $currentPlayer);
            DestroyMyItem($index);
          }
          $rv = "Amulet of Earth gives your attack actions cards +1 attack and +1 defense for the rest of the turn.";
        }
        return $rv;
      case "ELE144":
        AddCurrentTurnEffect($cardID, $otherPlayer);
        return "Heart of Ice makes cards and activated abilities of your opponent cost +1 resource this turn.";
      case "ELE145":
        PlayAura("ELE111", $otherPlayer);
        return "Coat of Frost create a frostbite token for the other player.";
      case "ELE147":
        AddDecisionQueue("BUTTONINPUT", $mainPlayer, "0,2", 0, 1);
        AddDecisionQueue("PAYRESOURCES", $mainPlayer, "<-", 1);
        AddDecisionQueue("GREATERTHANPASS", $mainPlayer, "0", 1);
        AddDecisionQueue("ADDCURRENTEFFECT", $mainPlayer, $cardID, 1);
        return "Blizzard makes the main player pay 2 or be unable for this attack to Go Again.";
      case "ELE151": case "ELE152": case "ELE153":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        AddCurrentTurnEffect($cardID . "-HIT", $currentPlayer);
        return "Ice Quake gives your next attack this turn +" . EffectAttackModifier($cardID) . " and each hit creates a frostbite token.";
      case "ELE154": case "ELE155": case "ELE156":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "Weave Ice gives your next Ice or Elemental attack action card this turn +" . EffectAttackModifier($cardID) . " and Dominate if it's fused.";
      case "ELE163": case "ELE164": case "ELE165":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "Chill to the Bone makes your next Ice or Elemental attack create Frostbite tokens for your opponent.";
      case "ELE166": case "ELE167": case "ELE168":
        if($cardID == "ELE166") $cost = 3;
        else if($cardID == "ELE167") $cost = 2;
        else $cost = 1;
        AddDecisionQueue("BUTTONINPUT", $otherPlayer, "0," . $cost, 0, 1);
        AddDecisionQueue("PAYRESOURCES", $otherPlayer, "<-", 1);
        AddDecisionQueue("GREATERTHANPASS", $otherPlayer, "0", 1);
        AddDecisionQueue("ADDCURRENTEFFECT", $currentPlayer, $cardID, 1);
        if($from == "ARS") MyDrawCard();
        return "";
      case "ELE169": PayOrDiscard($otherPlayer, 3); return "Winter's Bite makes the opponent pay 3 or discard a card.";
      case "ELE170": PayOrDiscard($otherPlayer, 2); return "Winter's Bite makes the opponent pay 2 or discard a card.";
      case "ELE171": PayOrDiscard($otherPlayer, 1); return "Winter's Bite makes the opponent pay 1 or discard a card.";
      case "ELE172":
        if($from == "PLAY")
        {
          $index = GetClassState($currentPlayer, $CS_PlayIndex);
          if($index != -1)
          {
            PayOrDiscard($otherPlayer, 2);
            DestroyMyItem($index);
          }
          $rv = "Amulet of Ice makes your opponent pay 2 resources or discard a card.";
        }
        return $rv;
      //Lightning
      case "ELE173":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "Shock Charmers makes your next attack action card deal 1 damage if it hits.";
      case "ELE176":
        if($currentPlayer == $mainPlayer) {++$actionPoints; $rv = "Blink grants an action point."; }
        return $rv;
      case "ELE177": case "ELE178": case "ELE179":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "Flash gives your next applicable action card Go Again.";
      case "ELE180": case "ELE181": case "ELE182":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "Weave Lightning gives your next lightning or elemental attack +" . EffectAttackModifier($cardID) . " and go again if it's fused.";
      case "ELE183": $combatChain[5] += 3; return "Lightning Press gives the current attack +3.";//TODO: Target card to not hardcode to the first?
      case "ELE184": $combatChain[5] += 2; return "Lightning Press gives the current attack +2.";//TODO: Target card to not hardcode to the first?
      case "ELE185": $combatChain[5] += 1; return "Lightning Press gives the current attack +1.";//TODO: Target card to not hardcode to the first?
      case "ELE186": case "ELE187": case "ELE188":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "ELE189": case "ELE190": case "ELE191":
        if($from == "ARS") { $rv = "Lightning Surge gained Go Again."; GiveAttackGoAgain(); }
        return $rv;
      case "ELE195": case "ELE196": case "ELE197":
        if($from == "PLAY")
        {
          AddCurrentTurnEffect($cardID, $currentPlayer);
          $rv = "Shock Striker deals 1 extra damage if it gets a hero.";
        }
        return $rv;
      case "ELE198": case "ELE199": case "ELE200":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        if($from == "ARS") MyDrawCard();
        return $rv;
      case "ELE201":
        if($from == "PLAY")
        {
          $index = GetClassState($currentPlayer, $CS_PlayIndex);
          if($index != -1)
          {
            AddCurrentTurnEffect($cardID, $currentPlayer);
            DestroyMyItem($index);
          }
          $rv = "Amulet of Lightning gives your next action Go Again.";
        }
        return $rv;
      case "ELE233":
        MyDrawCard();
        AddDecisionQueue("FINDINDICES", $currentPlayer, "HAND");
        AddDecisionQueue("CHOOSEHAND", $currentPlayer, "<-", 1);
        AddDecisionQueue("MULTIREMOVEHAND", $currentPlayer, "-", 1);
        AddDecisionQueue("OPT", $currentPlayer, "<-");
        return "";
      case "ELE234":
        GainResources($currentPlayer, 3);
        return "Deep Blue gives you 3 resources.";
      case "ELE235":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "Cracker Jax gives your next attack action card this turn +1.";
      case "ELE236":
        IncrementClassState($currentPlayer, $CS_DamagePrevention);
        return "Runaways prevents the next 1 damage that would be dealt to you this turn.";
      default: return "";
    }
  }

  function ELETalentHitEffect($cardID)
  {
    global $defPlayer;
    switch($cardID)
    {
      case "ELE148": case "ELE149": case "ELE150":
        PayOrDiscard($defPlayer, 2);
        return "";
      case "ELE157": case "ELE158": case "ELE159":
        PlayAura("ELE111", $defPlayer);
      default: break;
    }
  }

  function SowTomorrowIndices($player, $cardID)
  {
    if($cardID == "ELE140") $minCost = 0;
    else if($cardID == "ELE141") $minCost = 1;
    else $minCost = 2;
    $earth = CombineSearches(SearchDiscard($player, "A", "", -1, $minCost, "", "EARTH"), SearchDiscard($player, "AA", "", -1, $minCost, "", "EARTH"));
    $elemental = CombineSearches(SearchDiscard($player, "A", "", -1, $minCost, "", "ELEMENTAL"), SearchDiscard($player, "AA", "", -1, $minCost, "", "ELEMENTAL"));
    return CombineSearches($earth, $elemental);
  }

  function SummerwoodShelterIndices($player)
  {
    global $combatChain;
    $indices = "";
    for($i=0; $i<count($combatChain); $i += CombatChainPieces())
    {
      if($combatChain[$i+1] == $player)
      {
        $cardType = CardType($combatChain[$i]);
        if($cardType == "A" || $cardType == "AA")
        {
          if(TalentContains($combatChain[$i], "EARTH") || TalentContains($combatChain[$i], "ELEMENTAL"))
          {
            if($indices != "") $indices .= ",";
            $indices .= $i;
          }
        }
      }
    }
    return $indices;
  }

  function PlumeOfEvergrowthIndices($player)
  {
    $indices = CombineSearches(SearchDiscard($player, "A", "", -1, -1, "", "EARTH"), SearchDiscard($player, "AA", "", -1, -1, "", "EARTH"));
    $indices = CombineSearches($indices, SearchDiscard($player, "I", "", -1, -1, "", "EARTH"));
    return $indices;
  }

  function PulseOfCandleholdIndices($player)
  {
    return CombineSearches(SearchDiscard($player, "A", "", -1, -1, "", "EARTH,LIGHTNING,ELEMENTAL"), SearchDiscard($player, "AA", "", -1, -1, "", "EARTH,LIGHTNING,ELEMENTAL"));
  }

  function ExposedToTheElementsEarth($player)
  {
      $otherPlayer = $player == 1 ? 2 : 1;
      PrependDecisionQueue("ADDNEGDEFCOUNTER", $otherPlayer, "-", 1);
      PrependDecisionQueue("CHOOSETHEIRCHARACTER", $player, "<-", 1);
      PrependDecisionQueue("FINDINDICES", $otherPlayer, "EQUIP");
  }

  function ExposedToTheElementsIce($player)
  {
      $otherPlayer = $player == 1 ? 2 : 1;
      PrependDecisionQueue("DESTROYTHEIRCHARACTER", $player, "-", 1);
      PrependDecisionQueue("CHOOSETHEIRCHARACTER", $player, "<-", 1);
      PrependDecisionQueue("FINDINDICES", $otherPlayer, "EQUIP0", 1);
      PrependDecisionQueue("WRITELOG", $player, "Declined_to_pay_for_Exposed_to_the_Elements.", 1);
      PrependDecisionQueue("GREATERTHANPASS", $otherPlayer, "0", 1);
      PrependDecisionQueue("PAYRESOURCES", $otherPlayer, "<-", 1);
      PrependDecisionQueue("BUTTONINPUT", $otherPlayer, "0,2", 0, 1);
      WriteLog("Player " . $otherPlayer . " may choose to pay 2 to prevent their equipment from being destroyed.");
  }

  function KorshemRevealAbility($player)
  {
    WriteLog("Korshem triggered by revealing a card.");
    AddDecisionQueue("BUTTONINPUT", $player, "Gain_a_resource,Gain_a_life,1_Attack,1_Defense");
    AddDecisionQueue("KORSHEM", $player, "-", 1);
  }

?>
