<?php

function ARCMechanologistPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = "")
{
  global $currentPlayer, $CS_NumBoosted, $actionPoints, $CS_PlayIndex;
  global $CombatChain, $CS_LastDynCost;
  $rv = "";
  switch($cardID) {
    case "ARC003":
      $abilityType = GetResolvedAbilityType($cardID);
      if($abilityType == "A")
      {
        $index = GetClassState($currentPlayer, $CS_PlayIndex);
        $character = new Character($currentPlayer, $index);
        $character->numCounters = 1;
        $character->Finished();
      }
      return "";
    case "ARC004":
      $deck = new Deck($currentPlayer);
      for($i = 0; $i < 2 && !$deck->Empty(); ++$i) {
        $banished = $deck->BanishTop();
        if(ClassContains($banished, "MECHANOLOGIST", $currentPlayer)) GainResources($currentPlayer, 1);
      }
      return "";
    case "ARC005":
      GainActionPoints(1, $currentPlayer);
      return "";
    case "ARC006":
      Draw($currentPlayer);
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "ARC009":
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYDECK:subtype=Item;class=MECHANOLOGIST;minCost=" . (GetClassState($currentPlayer, $CS_LastDynCost) / 2) . ";maxCost=" . (GetClassState($currentPlayer, $CS_LastDynCost) / 2));
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("MZREMOVE", $currentPlayer, "-", 1);
      AddDecisionQueue("PUTPLAY", $currentPlayer, 0, 1);
      AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-");
      if(GetClassState($currentPlayer, $CS_NumBoosted) > 0) AddDecisionQueue("DRAW", $currentPlayer, "-");
      return "";
    case "ARC010":
      if($from == "PLAY") {
        $items = &GetItems($currentPlayer);
        if($CombatChain->HasCurrentLink()) GiveAttackGoAgain();
        else $items[GetClassState($currentPlayer, $CS_PlayIndex)+1] = 1;
      }
      return $rv;
    case "ARC014": case "ARC015": case "ARC016":
      if($cardID == "ARC014") $maxCost = 2;
      else if($cardID == "ARC015") $maxCost = 1;
      else $maxCost = 0;
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYHAND:subtype=Item;maxCost=$maxCost;class=MECHANOLOGIST");
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("MZREMOVE", $currentPlayer, "-", 1);
      AddDecisionQueue("PUTPLAY", $currentPlayer, (GetClassState($currentPlayer, $CS_NumBoosted) > 0 ? 1 : 0), 1);
      return "";
    case "ARC017":
      $index = GetClassState($currentPlayer, $CS_PlayIndex);
      $items = &GetItems($currentPlayer);
      if($index != -1) {
        $items[$index+1] = ($items[$index + 1] == 0 ? 1 : 0);
        if($items[$index+1] == 0) {
          AddCurrentTurnEffect($cardID, $currentPlayer);
          $items[$index+2] = 2;
        }
      }
      return $rv;
    case "ARC018":
      if($from == "PLAY") {
        $items = &GetItems($currentPlayer);
        if(!$CombatChain->HasCurrentLink()) $items[GetClassState($currentPlayer, $CS_PlayIndex) + 1] = 1;
      }
      return $rv;
    case "ARC019":
      $index = GetClassState($currentPlayer, $CS_PlayIndex);
      $items = &GetItems($currentPlayer);
      if($index != -1) {
        AddCurrentTurnEffect($cardID, $currentPlayer);
        --$items[$index+1];
        if($items[$index+1] <= 0) DestroyItemForPlayer($currentPlayer, $index);
        $rv = "Gives your next attack this turn Dominate";
      }
      return $rv;
    case "ARC032": case "ARC033": case "ARC034":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      $boosted = GetClassState($currentPlayer, $CS_NumBoosted) > 0;
      if($boosted) Opt($cardID, 1);
      return "";
    case "ARC035":
      AddCurrentTurnEffect($cardID . "-" . $additionalCosts, $currentPlayer, "PLAY");
      $rv = "";
      return $rv;
    case "ARC037":
      $index = GetClassState($currentPlayer, $CS_PlayIndex);
      $items = &GetItems($currentPlayer);
      if($index != -1) {
        PlayerOpt($currentPlayer, 1);
        --$items[$index+1];
        if($items[$index+1] <= 0) DestroyItemForPlayer($currentPlayer, $index);
      }
      return $rv;
    default: return "";
  }
}

function ARCMechanologistHitEffect($cardID)
{
  global $mainPlayer, $combatChainState, $CCS_GoesWhereAfterLinkResolves;
  switch ($cardID) {
    case "ARC011": case "ARC012": case "ARC013":
      AddCurrentTurnEffectFromCombat($cardID, $mainPlayer);
      break;
    case "ARC018":
      $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "BOTDECK";
      break;
    case "ARC020": case "ARC021": case "ARC022":
      $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "BOTDECK";
      break;
    default: break;
  }
  return "";
}

function HasBoost($cardID)
{
  switch ($cardID) {
    case "ARC011": case "ARC012": case "ARC013":
    case "ARC020": case "ARC021": case "ARC022":
    case "ARC023": case "ARC024": case "ARC025":
    case "ARC026": case "ARC027": case "ARC028":
    case "ARC029": case "ARC030": case "ARC031":
    case "CRU106": case "CRU107": case "CRU108":
    case "CRU109": case "CRU110": case "CRU111":
    case "EVR073": case "EVR074": case "EVR075":
    case "EVR079": case "EVR080": case "EVR081":
    case "DYN090":
    case "DYN095": case "DYN096": case "DYN097":
		case "DYN101": case "DYN102": case "DYN103":
		case "DYN104": case "DYN105": case "DYN106":
    case "TCC016":
    case "EVO138": case "EVO141":
    case "EVO147": case "EVO148": case "EVO149":
    case "EVO150": case "EVO151": case "EVO152":
    case "EVO162": case "EVO163": case "EVO164":
    case "EVO165": case "EVO166": case "EVO167":
    case "EVO168": case "EVO169": case "EVO170":
    case "EVO171": case "EVO172": case "EVO173":
    case "EVO177": case "EVO178": case "EVO179":
    case "EVO183": case "EVO184": case "EVO185":
    case "EVO186": case "EVO187": case "EVO188":
    case "EVO189": case "EVO190": case "EVO191":
    case "EVO192": case "EVO193": case "EVO194":
    case "EVO195": case "EVO196": case "EVO197":
    case "EVO198": case "EVO199": case "EVO200":
    case "EVO201": case "EVO202": case "EVO203":
    case "EVO204": case "EVO205": case "EVO206":
    case "EVO207": case "EVO208": case "EVO209":
    case "EVO210": case "EVO211": case "EVO212":
    case "EVO213": case "EVO214": case "EVO215":
    case "EVO216": case "EVO217": case "EVO218":
      return true;
    default: return false;
  }
}

function Boost()
{
  global $currentPlayer;
  AddDecisionQueue("YESNO", $currentPlayer, "if_you_want_to_boost");
  AddDecisionQueue("NOPASS", $currentPlayer, "-", 1);
  AddDecisionQueue("OP", $currentPlayer, "BOOST", 1);
  if(SearchCurrentTurnEffects("CRU102", $currentPlayer)) {
    AddDecisionQueue("DRAW", $currentPlayer, "-", 1);
    MZMoveCard($currentPlayer, "MYHAND", "MYTOPDECK", silent:true);
  }
}

function DoBoost($player, $boostCount = 1)
{
  global $combatChainState, $CS_NumBoosted, $CCS_NumBoosted, $CCS_IsBoosted;
  $deck = new Deck($player);
  $isGoAgainGranted = false;
  for ($i = 0; $i < $boostCount; $i++) {
    if($deck->Empty()) { WriteLog("Could not boost"); return; }
    ItemBoostEffects();
    GainActionPoints(CountCurrentTurnEffects("ARC006", $player), $player);
    $cardID = $deck->Top(remove:true);
    SelfBoostEffects($player, $cardID);
    OnBoostedEffects($player, $cardID);
    if(CardSubType($cardID) == "Item" && SearchCurrentTurnEffects("DYN091-2", $player, true)) PutItemIntoPlay($cardID);
    else BanishCardForPlayer($cardID, $player, "DECK", "BOOST");
    $grantsGA = ClassContains($cardID, "MECHANOLOGIST", $player);
    WriteLog("Boost banished " . CardLink($cardID, $cardID) . " and " . ($grantsGA ? "DID" : "did NOT") . " grant go again");
    IncrementClassState($player, $CS_NumBoosted);
    ++$combatChainState[$CCS_NumBoosted];
    $combatChainState[$CCS_IsBoosted] = 1;
    if($grantsGA) {
      GiveAttackGoAgain();
      $isGoAgainGranted = true;
    }
  }
  return $isGoAgainGranted;
}

function OnBoostedEffects($player, $boosted)
{
  if(SearchCharacterForCard($player, "EVO011") && CardName($boosted) == "Hyper Driver") {
    $char = &GetPlayerCharacter($player);
    $index = FindCharacterIndex($player, "EVO011");
    ++$char[$index+2];//EVO TODO: Make this actually put the card underneath
    if($char[$index+2] >= 3) Draw($player, fromCardEffect:false);
  }
  switch($boosted)
  {
    case "EVO177": case "EVO178": case "EVO179":
      AddDecisionQueue("SETDQCONTEXT", $player, "Choose a Hyper Driver to get a steam counter", 1);
      AddDecisionQueue("MULTIZONEINDICES", $player, "MYITEMS:sameName=ARC036");
      AddDecisionQueue("CHOOSEMULTIZONE", $player, "<-", 1);
      AddDecisionQueue("MZADDSTEAMCOUNTER", $player, "-", 1);
      break;
    default: break;
  }
}

function SelfBoostEffects($player, $boosted)
{
  global $layers;
  $cardID = $layers[0];
  switch($cardID) {
    case "EVO192": case "EVO193": case "EVO194":
    case "EVO195": case "EVO196": case "EVO197":
      if(SubtypeContains($boosted, "Item", $player) || IsEquipment($boosted, $player)) AddCurrentTurnEffect($cardID, $player);
      break;
    default: break;
  }
}

function ItemBoostEffects()
{
  global $currentPlayer;
  $items = &GetItems($currentPlayer);
  for($i = count($items) - ItemPieces(); $i >= 0; $i -= ItemPieces()) {
    switch($items[$i]) {
      case "ARC036":
      case "DYN110": case "DYN111": case "DYN112": case "EVO234":
        if($items[$i+2] == 2) AddLayer("TRIGGER", $currentPlayer, $items[$i], $i, "-", $items[$i + 4]);
        break;
      case "EVR072":
        if($items[$i+2] == 2) {
          WriteLog(CardLink($items[$i], $items[$i]) . " gives the attack +2");
          --$items[$i+1];
          $items[$i+2] = 1;
          AddCurrentTurnEffect("EVR072", $currentPlayer, "PLAY");
          if($items[$i+1] <= 0) DestroyItemForPlayer($currentPlayer, $i);
        }
        break;
      case "EVO090": case "EVO091": case "EVO092":
        AddCurrentTurnEffect($items[$i] . "," . $items[$i+1], $currentPlayer, "PLAY");
        DestroyItemForPlayer($currentPlayer, $i);
        break;
      default: break;
    }
  }
}
