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

  function Clash($cardID, $effectController="")
  {
    $p1Power = -1; $p2Power = -1;
    for($i=1; $i<=2; ++$i) {
      $deck = new Deck($i);
      if($deck->Reveal()) {
        if($i == 1) $p1Power = ModifiedAttackValue($deck->Top(), 1, "DECK", source:$cardID);
        else $p2Power = ModifiedAttackValue($deck->Top(), 2, "DECK", source:$cardID);
      }
    }
    if($p1Power > 0 && $p1Power > $p2Power) { $winner = 1; $loser = 2; }
    else if($p2Power > 0 && $p2Power > $p1Power) { $winner = 2; $loser = 1; }
    $loserChar = &GetPlayerCharacter($loser);
    $loserHero = ShiyanaCharacter($loserChar[0], $loser);
    if($loserHero == "HVY047" || $loserHero == "HVY048") {
      AddDecisionQueue("SETDQCONTEXT", $loser, "Choose if you want to pay a gold to re-do Clash");
      AddDecisionQueue("YESNO", $loser, "-");
      AddDecisionQueue("NOPASS", $loser, "-");
      AddDecisionQueue("WRITELOG", $loser, "YES", 1);
      AddDecisionQueue("ELSE", $loser, "-");
      AddDecisionQueue("MECHANIC", $winner, "WONCLASH", 1);
    }
    else {
      WonClashAbility($winner, $cardID, $effectController);
    }
  }

  function WonClashAbility($playerID, $cardID, $effectController="") {
    WriteLog("Player " . $playerID . " won the Clash");
    switch($cardID)
    {
      case "HVY162":
        PlayAura("HVY240", $playerID);
        break;
      case "HVY239":
        PutItemIntoPlayForPlayer("DYN243", $playerID, effectController:$effectController);
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

    AddOnWagerEffects();
  }

  function AddOnWagerEffects($canPass = true) {
    global $currentPlayer, $CCS_WagersThisLink;
    AddDecisionQueue("INCREMENTCOMBATCHAINSTATE", $currentPlayer, $CCS_WagersThisLink, $canPass);
    $char = &GetPlayerCharacter($currentPlayer);
    if($char[1] == 2 && ($char[0] == "HVY045" || $char[0] == "HVY046")) {
      AddDecisionQueue("PASSPARAMETER", $currentPlayer, $char[0], $canPass ? 1 : 0);
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
    $numWagersWon = 0;
    for($i=0; $i<count($currentTurnEffects); $i+=CurrentTurnPieces()) {
      $hasWager = true;
      switch($currentTurnEffects[$i]) {
        case "HVY057":
          PutItemIntoPlayForPlayer("DYN243", $wonWager, effectController:$mainPlayer);//Gold
          PlayAura("TCC105", $wonWager);//Might
          PlayAura("TCC107", $wonWager);//Vigor
          break;
        case "HVY086": case "HVY087": case "HVY088":
          PlayAura("TCC105", $wonWager);//Might
          break;
        case "HVY103-1":
          PlayAura("HVY240", $wonWager);//Agility
          break;
        case "HVY103-2":
          PutItemIntoPlayForPlayer("DYN243", $wonWager, effectController:$mainPlayer);//Gold
          break;
        case "HVY103-3":
          PlayAura("TCC107", $wonWager);//Vigor
          break;
        case "HVY130":
          PlayAura("TCC107", $wonWager);//Vigor
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
        case "HVY216": case "HVY217": case "HVY218":
          PutItemIntoPlayForPlayer("DYN243", $wonWager, effectController:$mainPlayer);//Gold
          break;
        case "HVY235":
          PutItemIntoPlayForPlayer("DYN243", $wonWager, effectController:$mainPlayer);//Gold
          break;
        default:
          $hasWager = false;
          break;
      }
      if($hasWager) ++$numWagersWon;
    }
    if($numWagersWon > 0 && $wonWager == $mainPlayer) {
      $char = &GetPlayerCharacter($mainPlayer);
      $hero = ShiyanaCharacter($char[0]);
      if($char[1] == 2 && ($hero == "HVY092" || $hero == "HVY093")) {
        PutItemIntoPlayForPlayer("DYN243", $mainPlayer, effectController:$mainPlayer);//Gold
        WriteLog(CardLink($hero, $hero) . " wins the favor of the crowd!");
      }
    }
  }

  function HasUniversal($cardID)
  {
    switch($cardID) {
      case "HVY216": case "HVY217": case "HVY218": return true;
      default: break;
    }
    return false;
  }

?>
