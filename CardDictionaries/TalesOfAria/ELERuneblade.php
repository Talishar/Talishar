<?php


  function ELERunebladeCardType($cardID)
  {
    switch($cardID)
    {
      case "ELE062": case "ELE063": return "C";
      case "ELE064": return "AA";
      case "ELE065": case "ELE066": return "A";
      case "ELE067": case "ELE068": case "ELE069": return "AA";
      case "ELE070": case "ELE071": case "ELE072": return "AA";
      case "ELE073": case "ELE074": case "ELE075": return "AA";
      case "ELE076": case "ELE077": case "ELE078": return "AA";
      case "ELE079": case "ELE080": case "ELE081": return "AA";
      case "ELE082": case "ELE083": case "ELE084": return "AA";
      case "ELE085": case "ELE086": case "ELE087": return "A";
      case "ELE088": case "ELE089": case "ELE090": return "A";
      case "ELE222": case "ELE223": return "W";
      case "ELE224": case "ELE225": return "E";
      case "ELE226": return "A";
      case "ELE227": case "ELE228": case "ELE229": return "DR";
      case "ELE230": case "ELE231": case "ELE232": return "AA";
      default: return "";
    }
  }

  function ELERunebladeCardSubType($cardID)
  {
    switch($cardID)
    {
      case "ELE222": case "ELE223": return "Sword";
      case "ELE224": case "ELE225": return "Legs";
      case "ELE226": return "Aura";
      default: return "";
    }
  }

  //Minimum cost of the card
  function ELERunebladeCardCost($cardID)
  {
    switch($cardID)
    {
      case "ELE064": return 2;
      case "ELE065": case "ELE066": return 0;
      case "ELE067": case "ELE068": case "ELE069": return 1;
      case "ELE070": case "ELE071": case "ELE072": return 1;
      case "ELE073": case "ELE074": case "ELE075": return 0;
      case "ELE076": case "ELE077": case "ELE078": return 1;
      case "ELE079": case "ELE080": case "ELE081": return 2;
      case "ELE082": case "ELE083": case "ELE084": return 2;
      case "ELE085": case "ELE086": case "ELE087": return 0;
      case "ELE088": case "ELE089": case "ELE090": return 1;
      case "ELE226": return 0;
      case "ELE227": case "ELE228": case "ELE229": return 0;
      case "ELE230": case "ELE231": case "ELE232": return 1;
      default: return 0;
    }
  }

  function ELERunebladePitchValue($cardID)
  {
    switch($cardID)
    {
      case "ELE064": return 1;
      case "ELE065": return 2;
      case "ELE066": return 3;
      case "ELE067": case "ELE070": case "ELE073": case "ELE076": case "ELE079": case "ELE082": case "ELE085": case "ELE088": return 1;
      case "ELE068": case "ELE071": case "ELE074": case "ELE077": case "ELE080": case "ELE083": case "ELE086": case "ELE089": return 2;
      case "ELE069": case "ELE072": case "ELE075": case "ELE078": case "ELE081": case "ELE084": case "ELE087": case "ELE090": return 3;
      case "ELE226": return 3;
      case "ELE227": case "ELE230": return 1;
      case "ELE228": case "ELE231": return 2;
      case "ELE229": case "ELE232": return 3;
      default: return 0;
    }
  }

  function ELERunebladeBlockValue($cardID)
  {
    switch($cardID)
    {
      case "ELE062": case "ELE063": return 0;
      case "ELE065": return 2;
      case "ELE085": case "ELE086": case "ELE087": return 2;
      case "ELE088": case "ELE089": case "ELE090": return 2;
      case "ELE222": case "ELE223": return 0;
      case "ELE224": return 1;
      case "ELE225": return 0;
      case "ELE226": return 2;
      case "ELE227": return 3;
      case "ELE228": return 2;
      case "ELE229": return 1;
      default: return 3;
    }
  }

  function ELERunebladeAttackValue($cardID)
  {
    switch($cardID)
    {
      case "ELE064": case "ELE079": return 6;
      case "ELE076": case "ELE080": case "ELE082": return 5;
      case "ELE070": case "ELE073": case "ELE077": case "ELE081": case "ELE083": return 4;
      case "ELE067": case "ELE071": case "ELE074": case "ELE078": case "ELE084": return 3;
      case "ELE068": case "ELE072": case "ELE075": return 2;
      case "ELE069": return 1;
      case "ELE222": return 2;
      case "ELE223": return 2;
      case "ELE230": return 4;
      case "ELE231": return 3;
      case "ELE232": return 2;
      default: return 0;
    }
  }

  function ELERunebladePlayAbility($cardID, $from, $resourcesPaid)
  {
    global $currentPlayer, $otherPlayer, $CS_ArcaneDamageTaken, $CS_NumNonAttackCards, $CS_NumAttackCards, $combatChainState, $CCS_WeaponIndex;
    global $CS_NextNAAInstant;
    $rv = "";
    switch($cardID)
    {
      case "ELE065":
        DealArcane(1, 0, "PLAYCARD", $cardID);
        return "";
      case "ELE066":
        AddCurrentTurnEffect($cardID . "-HIT", $currentPlayer);
        return "";
      case "ELE067": case "ELE068": case "ELE069":
        DealArcane(1, 0, "PLAYCARD", $cardID);
        return "";
      case "ELE070": case "ELE071": case "ELE072":
        AddDecisionQueue("CLASSSTATEGREATERORPASS", $otherPlayer, $CS_ArcaneDamageTaken . "-1", 1);
        AddDecisionQueue("GIVEATTACKGOAGAIN", $currentPlayer, "-", 1);
        return "";
      case "ELE079": case "ELE080": case "ELE081":
        if(GetClassState($otherPlayer, $CS_ArcaneDamageTaken) > 0)
        {
          AddDecisionQueue("FINDINDICES", $currentPlayer, "GYNAA");
          AddDecisionQueue("MAYCHOOSEDISCARD", $currentPlayer, "<-", 1);
          AddDecisionQueue("REMOVEDISCARD", $currentPlayer, "-", 1);
          AddDecisionQueue("ADDBOTDECK", $currentPlayer, "-", 1);
        }
        return "";
      case "ELE085": case "ELE086": case "ELE087":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "ELE222":
        if(GetClassState($currentPlayer, $CS_NumNonAttackCards) > 0 && GetClassState($currentPlayer, $CS_NumAttackCards) > 0)
        {
          $rv = "Rosetta Thorn deals 2 arcane damage to target hero.";
          DealArcane(2, 0, "PLAYCARD", $cardID);
        }
        return $rv;
      case "ELE223":
        if(GetClassState($currentPlayer, $CS_NumNonAttackCards) > 0 && GetClassState($currentPlayer, $CS_NumAttackCards) > 0)
        {
          $character = &GetPlayerCharacter($currentPlayer);
          ++$character[$combatChainState[$CCS_WeaponIndex]+3];
        }
        return $rv;
      case "ELE224":
        SetClassState($currentPlayer, $CS_NextNAAInstant, 1);
        return "Spellbound Creepers lets you play your next non-attack action as if it were an instant.";
      case "ELE225":
        GiveAttackGoAgain();
        return "Sutcliffe's Suede Hides gives the current attack Go Again.";
      case "ELE227": case "ELE228": case "ELE229":
        DealArcane(1, 0, "PLAYCARD", $cardID);
        return "";
      case "ELE230": case "ELE231": case "ELE232":
        DealArcane(1, 0, "PLAYCARD", $cardID);
        return "";
      default: return "";
    }
  }

  function ELERunebladeHitEffect($cardID)
  {
    switch($cardID)
    {
      default: break;
    }
  }

  function BlossomingSpellbladeDamageEffect($player)
  {
    $otherPlayer = $player == 1 ? 2 : 1;
    AddDecisionQueue("FINDINDICES", $otherPlayer, "GYNAA");
    AddDecisionQueue("MAYCHOOSEDISCARD", $otherPlayer, "<-", 1);
    AddDecisionQueue("REMOVEDISCARD", $otherPlayer, "-", 1);
    AddDecisionQueue("MULTIBANISH", $otherPlayer, "DECK,INST", 1);
  }

?>
