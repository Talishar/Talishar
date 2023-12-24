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
      case "TCC036":
        MZDestroy($mainPlayer, SearchMultizone($mainPlayer, "THEIRAURAS:type=T"));
      default: break;
    }
  }

  function Scrap($player)
  {
    global $CS_AdditionalCosts;
    MZMoveCard($player, "MYDISCARD:type=E&MYDISCARD:subtype=Item&MYDISCARD:subtype=Evo", "MYBANISH,GY,-", may:true);
    AddDecisionQueue("APPENDCLASSSTATE", $player, $CS_AdditionalCosts . "-SCRAP", 1);
  }

  function Clash($cardID)
  {
    $p1Power = -1; $p2Power = -1;
    for($i=1; $i<=2; ++$i) {
      $deck = new Deck($i);
      if($deck->Reveal()) {
        if($i == 1) $p1Power = AttackValue($deck->Top());
        else $p2Power = AttackValue($deck->Top());
      }
    }
    if($p1Power > 0 && $p1Power > $p2Power) WonClashAbility(1, $cardID);
    else if($p2Power > 0 && $p2Power > $p1Power) WonClashAbility(2, $cardID);
  }

  function WonClashAbility($playerID, $cardID) {
    WriteLog("Player " . $playerID . " won the Clash");
    switch($cardID)
    {
      case "HVY162":
        PlayAura("HVY240", $playerID);
        break;
      case "HVY239":
        PutItemIntoPlayForPlayer("DYN243", $playerID);
        break;
      default: break;
    }
  }

  function AskWager($cardID) {
    global $currentPlayer;
    AddDecisionQueue("PASSPARAMETER", $currentPlayer, $cardID);
    AddDecisionQueue("SETDQVAR", $currentPlayer, "0");
    AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Do you want to wager for <0>?");
    AddDecisionQueue("YESNO", $currentPlayer, "-");
    AddDecisionQueue("NOPASS", $currentPlayer, "-");
    AddDecisionQueue("ADDCURRENTEFFECT", $currentPlayer, $cardID . "!PLAY", 1);

    //Add on wager effects
    $char = &GetPlayerCharacter($currentPlayer);
    if($char[1] == 2 && ($char[0] == "HVY045" || $char[0] == "HVY046")) {
      AddDecisionQueue("PASSPARAMETER", $currentPlayer, $char[0], 1);
      AddDecisionQueue("SETDQVAR", $currentPlayer, "0", 1);
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Do you want to pay 2 for <0> to give overpower and +1?", 1);
      AddDecisionQueue("YESNO", $currentPlayer, "-", 1, 1);
      AddDecisionQueue("NOPASS", $currentPlayer, "-", 1);
      AddDecisionQueue("PASSPARAMETER", $currentPlayer, 2, 1);
      AddDecisionQueue("PAYRESOURCES", $currentPlayer, "-", 1);
      AddDecisionQueue("ADDCURRENTEFFECT", $currentPlayer, $char[0], 1);

    }
  }

  function ResolveWagers() {
    global $mainPlayer, $defPlayer, $combatChainState, $CCS_DamageDealt, $currentTurnEffects;
    $wonWager = $combatChainState[$CCS_DamageDealt] > 0 ? $mainPlayer : $defPlayer;
    for($i=0; $i<count($currentTurnEffects); $i+=CurrentTurnPieces()) {
      switch($currentTurnEffects[$i]) {
        case "HVY057":
          PutItemIntoPlayForPlayer("DYN243", $wonWager);//Gold
          PlayAura("TCC105", $wonWager);//Might
          PlayAura("TCC107", $wonWager);//Vigor
          break;
        case "HVY086": case "HVY087": case "HVY088":
          PlayAura("TCC105", $wonWager);//Might
          break;
        case "HVY149":
          PlayAura("TCC105", $wonWager);//Might
          break;
        case "HVY169":
          PlayAura("HVY240", $wonWager);//Agility
          break;;
        case "HVY189":
          PlayAura("TCC107", $wonWager);//Vigor
          break;
        case "HVY216":
          PutItemIntoPlayForPlayer("DYN243", $wonWager);//Gold
          break;
        default: break;
      }
    }
  }

?>
