<?php

  function MONTalentPlayAbility($cardID, $from, $resourcesPaid, $target="-", $additionalCosts = "")
  {
    global $currentPlayer, $mainPlayer, $combatChainState, $CCS_GoesWhereAfterLinkResolves, $CS_NumAddedToSoul, $combatChain, $CS_PlayIndex;
    $otherPlayer = $currentPlayer == 1 ? 2 : 1;
    switch($cardID)
    {
      case "MON000":
        if($from == "PLAY") DestroyLandmark(GetClassState($currentPlayer, $CS_PlayIndex));
        return "";
      case "MON061":
        AddDecisionQueue("FINDINDICES", $currentPlayer, "HAND");
        AddDecisionQueue("MAYCHOOSEHAND", $currentPlayer, "<-");
        AddDecisionQueue("REMOVEMYHAND", $currentPlayer, "-", 1);
        AddDecisionQueue("ADDSOUL", $currentPlayer, "HAND", 1);
        AddDecisionQueue("ALLCARDTALENTORPASS", $currentPlayer, "LIGHT", 1);
        AddDecisionQueue("DRAW", $currentPlayer, "-", 1);
        return "";
      case "MON064":
        $hand = &GetHand($currentPlayer);
        for($i = 0; $i < count($hand); ++$i) {
          AddSoul($hand[$i], $currentPlayer, "HAND");
        }
        $hand = [];
        return "";
      case "MON065":
        Draw($currentPlayer);
        Draw($currentPlayer);
        if(GetClassState($currentPlayer, $CS_NumAddedToSoul) > 0) Draw($currentPlayer);
        return "";
      case "MON066": case "MON067": case "MON068":
        if(count(GetSoul($currentPlayer)) == 0) {
          AddCurrentTurnEffect($cardID, $currentPlayer);
          $rv = "Goes into your soul after the chain link closes";
        }
        return $rv;
      case "MON069": case "MON070": case "MON071":
        if($cardID == "MON069") $count = 4;
        else if($cardID == "MON070") $count = 3;
        else $count = 2;
        for($i=0; $i<$count; ++$i) {
          AddDecisionQueue("FINDINDICES", $currentPlayer, "WEAPON");
          AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
          AddDecisionQueue("ADDATTACKCOUNTERS", $currentPlayer, "1", 1);
        }
        if($currentPlayer == $mainPlayer) AddCurrentTurnEffect($cardID, $currentPlayer);
        else AddNextTurnEffect($cardID, $currentPlayer);
        return "";
      case "MON081": case "MON082": case "MON083":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "MON084": case "MON085": case "MON086":
        if($cardID == "MON084") $amount = 3;
        else if($cardID == "MON085") $amount = 2;
        else $amount = 1;
        if($target == "-") WriteLog("Blinding Beam gives no bonus because it does not have a valid target.");
        else $combatChain[intval($target)+5] -= $amount;
        return "";
      case "MON087":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "MON188":
        AddDecisionQueue("FINDINDICES", $currentPlayer, "HAND");
        AddDecisionQueue("MAYCHOOSEHAND", $currentPlayer, "<-");
        AddDecisionQueue("REMOVEMYHAND", $currentPlayer, "-", 1);
        AddDecisionQueue("MULTIBANISH", $currentPlayer, "HAND,NA", 1);
        AddDecisionQueue("ALLCARDTALENTORPASS", $currentPlayer, "SHADOW", 1);
        AddDecisionQueue("DRAW", $currentPlayer, "-", 1);
        return "";
      case "MON189":
        PlayAlly("MON219", $currentPlayer);
        return "Creates a Blasmophet Ally.";
      case "MON190":
        PlayAlly("MON220", $currentPlayer);
        return "";
      case "MON192":
        if($from == "BANISH") $rv = "Returns to hand";
        return $rv;
      case "MON193":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "MON194":
        Draw($currentPlayer);
        return "";
      case "MON200": case "MON201": case "MON202":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "MON212": case "MON213": case "MON214":
        if($cardID == "MON212") $maxCost = 2;
        else if($cardID == "MON213") $maxCost = 1;
        else $maxCost = 0;
        AddDecisionQueue("FINDINDICES", $currentPlayer, "MON212," . $maxCost);
        AddDecisionQueue("CHOOSEBANISH", $currentPlayer, "<-", 1);
        AddDecisionQueue("BANISHADDMODIFIER", $currentPlayer, "MON212", 1);
        return "";
      case "MON215": case "MON216": case "MON217":
        if($cardID == "MON215") $amount = 3;
        else if($cardID == "MON216") $amount = 2;
        else $amount = 1;
        Opt($cardID, $amount);
        AddDecisionQueue("FINDINDICES", $currentPlayer, "TOPDECK", 1);
        AddDecisionQueue("MULTIREMOVEDECK", $currentPlayer, "<-", 1);
        AddDecisionQueue("MULTIBANISH", $currentPlayer, "DECK,NA", 1);
        AddDecisionQueue("SETDQVAR", $currentPlayer, "0", 1);
        AddDecisionQueue("WRITELOG", $currentPlayer, "<0> was banished.", 1);
        return "";
      case "MON218":
        $theirCharacter = GetPlayerCharacter($otherPlayer);
        if(TalentContains($theirCharacter[0], "LIGHT", $otherPlayer)) {
          if(GetHealth($currentPlayer) > GetHealth($otherPlayer)) {
            AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYDISCARD:type=A&MYDISCARD:type=AA");
            AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
            AddDecisionQueue("MZBANISH", $currentPlayer, "GY,-," . $currentPlayer, 1);
            AddDecisionQueue("MZREMOVE", $currentPlayer, "-", 1);
          }
          AddCurrentTurnEffect($cardID, $currentPlayer);
        }
        return "";
      case "MON219":
        $otherPlayer = $currentPlayer == 2 ? 1 : 2;
        AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYHAND:talent=SHADOW");
        AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("MZREMOVE", $currentPlayer, "-", 1);
        AddDecisionQueue("MULTIBANISH", $currentPlayer, "HAND,NA", 1);
        if(!IsAllyAttackTarget()) {
          AddDecisionQueue("PASSPARAMETER", $otherPlayer, "0", 1);
          AddDecisionQueue("MULTIBANISHSOUL", $otherPlayer, "-", 1);
        }
        return "";
      default: return "";
    }
  }

  function MONTalentHitEffect($cardID)
  {
    global $combatChainState, $CCS_GoesWhereAfterLinkResolves, $defPlayer;
    switch($cardID)
    {
      case "MON072": case "MON073": case "MON074": $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "SOUL"; break;
      case "MON078": case "MON079": case "MON080": $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "SOUL"; break;
      case "MON198":
        if(IsHeroAttackTarget()) {
          $numSoul = count(GetSoul($defPlayer));
          for($i=0; $i<$numSoul; ++$i) BanishFromSoul($defPlayer);
          LoseHealth($numSoul, $defPlayer);
        }
        break;
      case "MON206": case "MON207": case "MON208":
        if(IsHeroAttackTarget()) {
          BanishFromSoul($defPlayer);
          $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "BANISH";
        }
        break;
      default: break;
    }
  }

  function ShadowPuppetryHitEffect()
  {
    global $mainPlayer;
    AddDecisionQueue("DECKCARDS", $mainPlayer, "0", 1);
    AddDecisionQueue("SETDQVAR", $mainPlayer, "0", 1);
    AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose if you want to banish <0> with Shadow Puppetry", 1);
    AddDecisionQueue("YESNO", $mainPlayer, "if_you_want_to_banish_the_card", 1);
    AddDecisionQueue("NOPASS", $mainPlayer, "-", 1);
    AddDecisionQueue("PARAMDELIMTOARRAY", $mainPlayer, "0", 1);
    AddDecisionQueue("MULTIREMOVEDECK", $mainPlayer, "0", 1);
    AddDecisionQueue("MULTIBANISH", $mainPlayer, "DECK,-", 1);
    AddDecisionQueue("SETDQVAR", $mainPlayer, "0", 1);
    AddDecisionQueue("WRITELOG", $mainPlayer, "<0> was banished.", 1);
  }

  function EndTurnBloodDebt()
  {
    global $mainPlayer;
    if(IsImmuneToBloodDebt($mainPlayer)) {
      WriteLog("No blood debt damage was taken because you are immune");
      return;
    }
    $numBloodDebt = SearchCount(SearchBanish($mainPlayer, "", "", -1, -1, "", "", true));
    if($numBloodDebt > 0) {
      LoseHealth($numBloodDebt, $mainPlayer);
      WriteLog("Player $mainPlayer lost $numBloodDebt health from Blood Debt at end of turn", $mainPlayer);
    }
  }

  function IsImmuneToBloodDebt($player)
  {
    global $CS_Num6PowBan;
    $character = &GetPlayerCharacter($player);
    $characterID = ShiyanaCharacter($character[0]);
    if($character[1] == 2 && ($characterID == "MON119" || $characterID == "MON120") && GetClassState($player, $CS_Num6PowBan) > 0) return true;
    return false;
  }

?>
