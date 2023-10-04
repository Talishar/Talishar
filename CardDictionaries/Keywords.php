<?php

  function HasCrank($cardID, $player)
  {
    $char = &GetPlayerCharacter($player);
    if(CardNameContains($cardID, "Hyper Driver", $player) && (SearchCharacterForCard($player, "EVO004") || SearchCharacterForCard($player, "EVO005")) && $char[1] < 3) return true;
    switch($cardID) {
      case "EVO070": case "EVO071": case "EVO072":
      case "EVO074":
      case "EVO078": case "EVO079": case "EVO080":
      case "EVO081": case "EVO082": case "EVO083":
      case "EVO084": case "EVO085": case "EVO086":
      case "EVO087": case "EVO088": case "EVO089":
      case "EVO090": case "EVO091": case "EVO092":
      case "EVO093": case "EVO094": case "EVO095":
      case "EVO096": case "EVO097": case "EVO098": return true;
      default: return false;
    }
  }

  function Crank($player, $index)
  {
    AddDecisionQueue("YESNO", $player, "if you want to Crank");
    AddDecisionQueue("NOPASS", $player, "-");
    AddDecisionQueue("PASSPARAMETER", $player, $index, 1);
    AddDecisionQueue("OP", $player, "DOCRANK", 1);
  }

  function DoCrank($player, $index)
  {
    global $CS_NumCranked;
    $items = &GetItems($player);
    if($items[$index+1] <= 0) return;
    --$items[$index+1];
    GainActionPoints(1, $player);
    WriteLog("Player $player cranked");
    IncrementClassState($player, $CS_NumCranked);
    if(CardName($items[$index]) == "Hyper Driver" && ($items[$index+1] <= 0)) DestroyItemForPlayer($player, $index);
  }

  function ProcessTowerEffect($cardID)
  {
    global $CombatChain, $mainPlayer;
    if(!IsHeroAttackTarget()) return;
    switch($cardID)
    {
      case "TCC034":
        MZChooseAndDestroy($mainPlayer, "THEIRCHAR:type=E;maxDef=1");
        break;
      default: break;
    }
  }

  function Scrap($player)
  {
    global $CS_AdditionalCosts;
    MZMoveCard($player, "MYDISCARD:type=E&MYDISCARD:subtype=Item&MYDISCARD:subtype=Evo", "MYBANISH,GY,-", may:true);
    AddDecisionQueue("APPENDCLASSSTATE", $player, $CS_AdditionalCosts . "-SCRAP", 1);
  }

?>
