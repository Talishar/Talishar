<?php

  function HVYAbilityCost($cardID)
  {
    switch($cardID)
    {
      case "HVY050": return 3;
      case "HVY090": case "HVY091": return 0;
      case "HVY134": return 1;
      case "HVY245": return 2;
      default: return 0;
    }
  }

  function HVYAbilityType($cardID, $index=-1, $from="-")
  {
    switch($cardID)
    {
      case "HVY050": return "AA";
      case "HVY090": case "HVY091": return "A";
      case "HVY134": return "AA";
      case "HVY245":
        if ($from == "GY")
          return "I";
        else
          return "AA";
      default: return "";
    }
  }

  function HVYAbilityHasGoAgain($cardID)
  {
    global $CombatChain;
    switch($cardID)
    {
      case "HVY090": case "HVY091": return true;
      default: return false;
    }
  }

  function TCCAbilityCost($cardID)
  {
    switch($cardID)
    {
      case "TCC002": return 3;
      case "TCC028": return 3;
      case "TCC050":
        $abilityType = GetResolvedAbilityType($cardID);
        return ($abilityType == "A" ? 3 : 0);
      default: return 0;
    }
  }

  function TCCAbilityType($cardID, $index=-1)
  {
    switch($cardID)
    {
      case "TCC002": return "AA";
      case "TCC028": return "AA";
      case "TCC050": return "A";
      case "TCC051": case "TCC052": case "TCC053": case "TCC054": return "A";
      case "TCC079": return "I";
      case "TCC080": return "I";
      case "TCC082": return "I";
      default: return "";
    }
  }

  function TCCAbilityHasGoAgain($cardID)
  {
    global $CombatChain;
    switch($cardID)
    {
      case "TCC051": case "TCC052": case "TCC053": case "TCC054": return true;
      case "TCC050": return !$CombatChain->HasCurrentLink();
      default: return false;
    }
  }

  function EVOAbilityCost($cardID)
  {
    global $currentPlayer;
    switch($cardID)
    {
      case "EVO004": case "EVO005": return 2;
      case "EVO006": return 1;
      case "EVO007": case "EVO008": return 3;
      case "EVO009":
        $evoAmt = EvoUpgradeAmount($currentPlayer);
        if($evoAmt == 1) return 3;
        else if($evoAmt >= 2) return 1;
        else return 0;
      case "EVO014": case "EVO015": case "EVO016": case "EVO017": return 1;
      case "EVO081": case "EVO082": case "EVO083": return 2;
      case "EVO235": return 2;
      case "EVO247": return 2;
      case "EVO410": return 3;
      default: return 0;
    }
  }

  function EVOAbilityType($cardID, $index=-1)
  {
    global $currentPlayer, $CS_NumCranked, $CS_NumBoosted;
    switch($cardID)
    {
      case "EVO003": return "AA";
      case "EVO004": case "EVO005": return GetClassState($currentPlayer, $CS_NumBoosted) > 0 ? "A" : "";
      case "EVO006": return GetClassState($currentPlayer, $CS_NumCranked) > 0 ? "AA" : "";
      case "EVO007": case "EVO008": return "I";
      case "EVO009": return EvoUpgradeAmount($currentPlayer) >= 1 ? "AA" : "";
      case "EVO014": case "EVO015": case "EVO016": case "EVO017": return "I";
      case "EVO070": return "A";
      case "EVO071": return "I";
      case "EVO072": return "I";
      case "EVO073": return "A";
      case "EVO075": return "I";
      case "EVO076": return "A";
      case "EVO077": return "I";
      case "EVO081": case "EVO082": case "EVO083": return "I";
      case "EVO087": case "EVO088": case "EVO089": return "I";
      case "EVO235": return "AR";
      case "EVO247": return "A";
      case "EVO410": return "AA";
      case "EVO434": case "EVO435": case "EVO436": case "EVO437": return "I";
      case "EVO446": case "EVO447": case "EVO448": case "EVO449": return "I";
      default: return "";
    }
  }

  function EVOAbilityHasGoAgain($cardID)
  {
    switch($cardID)
    {
      case "EVO073": return true;
      case "EVO247": return true;
      default: return false;
    }
  }

  function DestroyTopCard($player)
  {
    $otherPlayer = ($player == 1 ? 2 : 1);
    AddDecisionQueue("PASSPARAMETER", $player, "ELSE");
    AddDecisionQueue("SETDQVAR", $player, "1");
    if(ShouldAutotargetOpponent($player)) {
      AddDecisionQueue("PASSPARAMETER", $player, "Target_Opponent");
    } else {
      AddDecisionQueue("SETDQCONTEXT", $player, "Choose target hero");
      AddDecisionQueue("BUTTONINPUT", $player, "Target_Opponent,Target_Yourself");
    }
    AddDecisionQueue("EQUALPASS", $player, "Target_Opponent");
    AddDecisionQueue("WRITELOG", $player, "Destroys the top card of your deck", 1);
    AddDecisionQueue("DESTROYTOPCARD", $player, "0", 1);
    AddDecisionQueue("SETDQVAR", $player, "1", 1);
    AddDecisionQueue("PASSPARAMETER", $player, "{1}");

    AddDecisionQueue("NOTEQUALPASS", $player, "ELSE");
    AddDecisionQueue("WRITELOG", $otherPlayer, "Destroys the top card of opponent's deck", 1);
    AddDecisionQueue("DESTROYTOPCARD", $otherPlayer, "0", 1);
    AddDecisionQueue("SETDQVAR", $otherPlayer, "1", 1);
  }

  function DestroyItemWithoutSteamCounter($cardID, $player) {
    if (CardNameContains($cardID, "Hyper Driver", $player)) return true;
    switch ($cardID) {
      case "ARC037": case "ARC007": case "ARC019":
      case "DYN093": case "EVR072":
      case "CRU104":
        return true;
      default:
        return false;
    }
  }
?>
