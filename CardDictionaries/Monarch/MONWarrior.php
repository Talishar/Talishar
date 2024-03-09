<?php

  function MONWarriorPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = "")
  {
    global $CS_NumCharged, $currentPlayer, $CS_AtksWWeapon, $CS_LastAttack;
    global $combatChainState, $CCS_WeaponIndex;
    $rv = "";
    switch($cardID)
    {
      case "MON029": case "MON030":
        if(HasIncreasedAttack()) GiveAttackGoAgain();
        return "";
      case "MON033":
        AddDecisionQueue("ATTACKMODIFIER", $currentPlayer, intval($additionalCosts), 1);
        if(GetClassState($currentPlayer, $CS_NumCharged) > 0) {
          AddDecisionQueue("FINDINDICES", $currentPlayer, "MON033-2", 1);
          AddDecisionQueue("MAYCHOOSEDECK", $currentPlayer, "<-", 1);
          AddDecisionQueue("MULTIADDHAND", $currentPlayer, "-", 1);
          AddDecisionQueue("REVEALCARDS", $currentPlayer, "-", 1);
          AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-");
        }
        return "";
      case "MON034":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        $character = &GetPlayerCharacter($currentPlayer);
        if(GetClassState($currentPlayer, $CS_NumCharged) > 0) {
          for($i=0; $i<count($character); $i+=CharacterPieces()) {
            if(CardType($character[$i]) == "W" && $character[$i+1] != 0) { $character[$i+1] = 2; ++$character[$i+5]; }
          }
        }
        return "";
      case "MON036": case "MON037": case "MON038":
        if(GetClassState($currentPlayer, $CS_NumCharged) > 0) GiveAttackGoAgain();
        return "";
      case "MON105":
        if(GetClassState($currentPlayer, $CS_LastAttack) != "MON106") return "";
        AddCharacterEffect($currentPlayer, $combatChainState[$CCS_WeaponIndex], $cardID);
        return "";
      case "MON106":
        if(GetClassState($currentPlayer, $CS_LastAttack) != "MON105") return "";
        AddCharacterEffect($currentPlayer, $combatChainState[$CCS_WeaponIndex], $cardID);
        return "";
      case "MON108":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "MON109":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "MON110": case "MON111": case "MON112":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "MON113": case "MON114": case "MON115":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "MON116": case "MON117": case "MON118":
        if(GetClassState($currentPlayer, $CS_AtksWWeapon) == 0) return "Does nothing because there were no weapon attacks this turn";
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      default: return "";
    }
  }

  function MONWarriorHitEffect($cardID)
  {
    global $mainPlayer, $CS_NumCharged, $combatChainState, $CCS_GoesWhereAfterLinkResolves;
    switch($cardID)
    {
      case "MON042": case "MON043": case "MON044":
        if(GetClassState($mainPlayer, $CS_NumCharged) > 0) Draw($mainPlayer);
        break;
      case "MON048": case "MON049": case "MON050":
        if(GetClassState($mainPlayer, $CS_NumCharged) > 0) $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "SOUL";
        break;
      default: break;
    }
  }

  function DuskPathPilgrimageHit()
  {
    global $mainPlayer, $combatChainState, $CCS_WeaponIndex;
    $mainCharacter = &GetPlayerCharacter($mainPlayer);
    if($mainCharacter[$combatChainState[$CCS_WeaponIndex]+1] == 0) return;
    $mainCharacter[$combatChainState[$CCS_WeaponIndex]+1] = 2;
    ++$mainCharacter[$combatChainState[$CCS_WeaponIndex]+5];
  }

  function Charge($may=true, $player="")
  {
    global $currentPlayer;
    if($player == "") $player = $currentPlayer;
    $hand = &GetHand($player);
    if(count($hand) == 0) { WriteLog("No cards in hand to charge"); return; }
    AddDecisionQueue("FINDINDICES", $player, "HAND");
    AddDecisionQueue("SETDQCONTEXT", $player, "Choose a card to charge", 1);
    if($may) AddDecisionQueue("MAYCHOOSEHAND", $player, "<-");
    else AddDecisionQueue("CHOOSEHAND", $player, "<-");
    AddDecisionQueue("REMOVEMYHAND", $player, "-", 1);
    AddDecisionQueue("ADDSOUL", $player, "HAND", 1);
    AddDecisionQueue("FINISHCHARGE", $player, "-", 1);
  }

  function DQCharge()
  {
    global $currentPlayer;
    $hand = &GetHand($currentPlayer);
    if(count($hand) == 0) { WriteLog("No cards in hand to charge"); return; }
    PrependDecisionQueue("FINISHCHARGE", $currentPlayer, "-", 1);
    PrependDecisionQueue("ADDSOUL", $currentPlayer, "HAND", 1);
    PrependDecisionQueue("REMOVEMYHAND", $currentPlayer, "-", 1);
    PrependDecisionQueue("MAYCHOOSEHAND", $currentPlayer, "<-");
    PrependDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a card to charge", 1);
    PrependDecisionQueue("FINDINDICES", $currentPlayer, "HAND");
  }

  function HaveCharged($player)
  {
    global $CS_NumCharged;
    return GetClassState($player, $CS_NumCharged) > 0;
  }

  function MinervaThemisAbility($player, $index)
  {
    $arsenal = &GetArsenal($player);
    ++$arsenal[$index+3];
    if($arsenal[$index+3] == 3) MentorTrigger($player, $index);
  }

?>
