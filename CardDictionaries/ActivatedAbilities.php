<?php

function AAZAbilityType($cardID, $index=-1, $from="-")
{
  switch($cardID)
  {
    case "AAZ004": return "A";
    default: return "";
  }
}

function AAZAbilityHasGoAgain($cardID)
{
  global $CombatChain;
  switch($cardID)
  {
    case "AAZ004": return true;
    default: return false;
  }
}

function AKOAbilityCost($cardID)
{
  switch($cardID)
  {
    default: return 0;
  }
}

function AKOAbilityType($cardID, $index=-1, $from="-")
{
  switch($cardID)
  {
    case "AKO004": return "A";
    default: return "";
  }
}

function AKOAbilityHasGoAgain($cardID)
{
  global $CombatChain;
  switch($cardID)
  {
    case "AKO004": return true;
    default: return false;
  }
}

function MSTAbilityCost($cardID)
{
  switch($cardID)
  {
    case "MST001": case "MST002": return 3; 
    case "MST003": return 2;
    case "MST004": return 3;
    case "MST006": case "MST007": return 1;
    case "MST025": case "MST026": case "MST027": return 3; 
    case "MST029": case "MST030": return 1;
    case "MST046": case "MST047": case "MST048": return 3;
    case "MST067": return 3;
    case "MST069": case "MST070": return 1;
    case "MST159": return 2;
    case "MST238": return 3;
    default: return 0;
  }
}

function MSTAbilityType($cardID, $index=-1, $from="-")
{
  switch($cardID)
  {
    case "MST001": case "MST002": return "I"; 
    case "MST003": return "AA";
    case "MST004": return "AR";
    case "MST006": case "MST007": return "AR";
    case "MST025": case "MST026": case "MST027": return "I"; 
    case "MST029": case "MST030": return "I";
    case "MST046": case "MST047": case "MST048": return "I"; 
    case "MST067": return "I";
    case "MST069": case "MST070": return "AR";
    case "MST071": case "MST072": case "MST073": case "MST074": return "I";
    case "MST133": return "I";
    case "MST159": return "AA";
    case "MST232": return "I";
    case "MST238": return "I";
    default: return "";
  }
}

function MSTAbilityHasGoAgain($cardID)
{
  global $CombatChain;
  switch($cardID)
  {
    default: return false;
  }
}

  function HVYAbilityCost($cardID)
  {
    switch($cardID)
    {
      case "HVY006": case "HVY007": return 2;
      case "HVY049": case "HVY050": return 3;
      case "HVY090": case "HVY091": return 0;
      case "HVY095": case "HVY096": case "HVY098": case "HVY099": return 1;
      case "HVY134": return 1;
      case "HVY196": case "HVY197": return 3;
      case "HVY245": return 2;
      default: return 0;
    }
  }

  function HVYAbilityType($cardID, $index=-1, $from="-")
  {
    switch($cardID)
    {
      case "HVY006": return "AA";
      case "HVY007": return "AA";
      case "HVY009": return "A";
      case "HVY010": return "A";
      case "HVY049": return "AA";
      case "HVY050": return "AA";
      case "HVY055": return "A";
      case "HVY090": case "HVY091": return "A";
      case "HVY095": case "HVY096": return "AA";
      case "HVY098": return "AR";
      case "HVY099": return "AR";
      case "HVY134": return "AA";
      case "HVY135": return "A";
      case "HVY155": return "A";
      case "HVY175": return "A";
      case "HVY195": case "HVY196": case "HVY197": return "I";
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
      case "HVY010": case "HVY055": case "HVY090": case "HVY091":
      case "HVY135": case "HVY155": case "HVY175":
      case "HVY243":
        return true;
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

  function EVOAbilityType($cardID, $index=-1, $from="")
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
      case "EVO071": 
      case "EVO072": 
        if($from == "PLAY") return "I";
        else return "A";
      case "EVO073": return "A";
      case "EVO075": 
        if($from == "PLAY") return "I";
        else return "A";
      case "EVO076": return "A";
      case "EVO077": 
      case "EVO081": case "EVO082": case "EVO083": 
      case "EVO087": case "EVO088": case "EVO089": 
        if($from == "PLAY") return "I";
        else return "A";
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

  function DestroyTopCardTarget($player)
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
    AddDecisionQueue("DESTROYTOPCARD", $player, "0", 1);
    AddDecisionQueue("SETDQVAR", $player, "1", 1);
    AddDecisionQueue("PASSPARAMETER", $player, "{1}");

    AddDecisionQueue("NOTEQUALPASS", $player, "ELSE");
    AddDecisionQueue("DESTROYTOPCARD", $otherPlayer, "0", 1);
    AddDecisionQueue("SETDQVAR", $otherPlayer, "1", 1);
  }

  function DestroyTopCardOpponent($player)
  {
    $otherPlayer = ($player == 1 ? 2 : 1);
    AddDecisionQueue("WRITELOG", $otherPlayer, "Destroys the top card of Player ". $otherPlayer ." deck", 1);
    AddDecisionQueue("DESTROYTOPCARD", $otherPlayer, "0", 1);
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
