<?php

include "Search.php";
include "CardLogic.php";
include "AuraAbilities.php";
include "ItemAbilities.php";
include "AllyAbilities.php";
include "PermanentAbilities.php";
include "LandmarkAbilities.php";
include "CharacterAbilities.php";
include "WeaponLogic.php";
include "MZLogic.php";
include "Classes/Deck.php";
include "DecisionQueue/DecisionQueueEffects.php";
include "CurrentEffectAbilities.php";
include "CombatChain.php";

function PlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = "-")
{
  global $currentPlayer, $layers;
  $set = CardSet($cardID);
  $class = CardClass($cardID);
  if($target != "-")
  {
    $targetArr = explode("-", $target);
    if($targetArr[0] == "LAYERUID") { $targetArr[0] = "LAYER"; $targetArr[1] = SearchLayersForUniqueID($targetArr[1]); }
    $target = $targetArr[0] . "-" . $targetArr[1];
  }
  if(($set == "ELE" || $set == "UPR") && $additionalCosts != "-" && HasFusion($cardID)) {
    FuseAbility($cardID, $currentPlayer, $additionalCosts);
  }
  $cardID = ShiyanaCharacter($cardID);
  if($set == "WTR") return WTRPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts);
  else if($set == "ARC") {
    switch($class) {
      case "MECHANOLOGIST": return ARCMechanologistPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts);
      case "RANGER": return ARCRangerPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts);
      case "RUNEBLADE": return ARCRunebladePlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts);
      case "WIZARD": return ARCWizardPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts);
      case "GENERIC": return ARCGenericPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts);
      default: return "";
    }
  }
  else if($set == "CRU") return CRUPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts);
  else if($set == "MON") {
    switch ($class) {
      case "BRUTE": return MONBrutePlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts);
      case "ILLUSIONIST": return MONIllusionistPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts);
      case "RUNEBLADE": return MONRunebladePlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts);
      case "WARRIOR": return MONWarriorPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts);
      case "GENERIC": return MONGenericPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts);
      case "NONE": return MONTalentPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts);
      default: return "";
    }
  }
  else if($set == "ELE") {
    switch ($class) {
      case "GUARDIAN": return ELEGuardianPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts);
      case "RANGER": return ELERangerPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts);
      case "RUNEBLADE": return ELERunebladePlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts);
      default: return ELETalentPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts);
    }
  }
  else if($set == "EVR") return EVRPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts);
  else if($set == "UPR") return UPRPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts);
  else if($set == "DVR") return DVRPlayAbility($cardID, $from, $resourcesPaid);
  else if($set == "RVD") return RVDPlayAbility($cardID, $from, $resourcesPaid);
  else if($set == "DYN") return DYNPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts);
  else if($set == "OUT") return OUTPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts);
  else return ROGUEPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts);
}

function PitchAbility($cardID)
{
  global $currentPlayer, $CS_NumAddedToSoul;

  $pitchValue = PitchValue($cardID);
  if(GetClassState($currentPlayer, $CS_NumAddedToSoul) > 0 && SearchCharacterActive($currentPlayer, "MON060") && TalentContains($cardID, "LIGHT", $currentPlayer)) {
    $resources = &GetResources($currentPlayer);
    $resources[0] += 1;
  }
  if($pitchValue == 1) {
    $talismanOfRecompenseIndex = GetItemIndex("EVR191", $currentPlayer);
    if($talismanOfRecompenseIndex > -1) {
      WriteLog("Talisman of Recompense gained 3 instead of 1 and destroyed itself");
      DestroyItemForPlayer($currentPlayer, $talismanOfRecompenseIndex);
      GainResources($currentPlayer, 2);
    }
    if(SearchCharacterActive($currentPlayer, "UPR001") || SearchCharacterActive($currentPlayer, "UPR002") || SearchCurrentTurnEffects("UPR001-SHIYANA", $currentPlayer) || SearchCurrentTurnEffects("UPR002-SHIYANA", $currentPlayer)) {
      WriteLog("Dromai creates an Ash");
      PutPermanentIntoPlay($currentPlayer, "UPR043");
    }
  }
  switch($cardID) {
    case "WTR000": case "ARC000": case "CRU000": case "OUT000":
      AddLayer("TRIGGER", $currentPlayer, $cardID);
      break;
    case "EVR000":
      PlayAura("WTR075", $currentPlayer);
      break;
    case "UPR000":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      break;
    default:
      break;
  }
}

function CountPitch(&$pitch, $min = 0, $max = 9999)
{
  $pitchCount = 0;
  for($i = 0; $i < count($pitch); ++$i) {
    $cost = CardCost($pitch[$i]);
    if($cost >= $min && $cost <= $max) ++$pitchCount;
  }
  return $pitchCount;
}

function Draw($player, $mainPhase = true)
{
  global $EffectContext, $mainPlayer;
  $otherPlayer = ($player == 1 ? 2 : 1);
  if($mainPhase && $player != $mainPlayer) {
    $talismanOfTithes = SearchItemsForCard("EVR192", $otherPlayer);
    if($talismanOfTithes != "") {
      $indices = explode(",", $talismanOfTithes);
      DestroyItemForPlayer($otherPlayer, $indices[0]);
      WriteLog(CardLink("EVR192", "EVR192") . " prevented a draw and was destroyed");
      return "";
    }
  }
  if($mainPhase && (SearchAurasForCard("UPR138", $otherPlayer) != "" || SearchAurasForCard("UPR138", $player) != "")) {
    WriteLog("Draw prevented by " . CardLink("UPR138", "UPR138"));
    return "";
  }
  $deck = &GetDeck($player);
  $hand = &GetHand($player);
  if(count($deck) == 0) return -1;
  if(CurrentEffectPreventsDraw($player, $mainPhase)) return -1;
  array_push($hand, array_shift($deck));
  if($mainPhase && (SearchCharacterActive($otherPlayer, "EVR019") || (SearchCurrentTurnEffects("EVR019-SHIYANA", $otherPlayer) && SearchCharacterActive($otherPlayer, "CRU097")))) PlayAura("WTR075", $otherPlayer);
  if(SearchCharacterActive($player, "EVR020")) {
    if($EffectContext != "-") {
      $cardType = CardType($EffectContext);
      if($cardType == "A" || $cardType == "AA") PlayAura("WTR075", $player);
    }
  }
  if($mainPhase)
  {
    $numBrainstorm = CountCurrentTurnEffects("DYN196", $player);
    if($numBrainstorm > 0)
    {
      $character = &GetPlayerCharacter($player);
      for($i=0; $i<$numBrainstorm; ++$i) DealArcane(1, 2, "TRIGGER", $character[0]);
    }
  }
  $hand = array_values($hand);
  return $hand[count($hand) - 1];
}

function MyDrawCard()
{
  global $currentPlayer;
  Draw($currentPlayer);
}

function EquipPayAdditionalCosts($cardIndex, $from)
{
  global $currentPlayer;
  $character = &GetPlayerCharacter($currentPlayer);
  $cardID = $character[$cardIndex];
  $cardID = ShiyanaCharacter($cardID);
  switch($cardID) {
    case "WTR150": //Tunic energy counters
      $character[$cardIndex+2] -= 3;
      break;
    case "CRU177": //Talishar rust counters
      $character[$cardIndex+1] = 1;
      ++$character[$cardIndex+2];
      break;
    case "WTR037": case "WTR038":
    case "ARC003": case "ARC113": case "ARC114":
    case "CRU024": case "CRU101":
    case "MON029": case "MON030":
    case "ELE173":
    case "OUT096":
      break; //Unlimited uses
    case "ELE224": //Spellbound Creepers - Bind counters
      ++$character[$cardIndex + 2];//Add a counter
      --$character[$cardIndex + 5];
      if($character[$cardIndex + 5] == 0) $character[$cardIndex + 1] = 1;
      break;
    case "UPR151": //Ghostly Touch - Haunt counters
      $character[$cardIndex+2] -= 1;//Remove a counter
      --$character[$cardIndex+5];
      if($character[$cardIndex+5] == 0) $character[$cardIndex + 1] = 1;
      break;
    case "UPR166": //Alluvion Constellas - Energy counters
      $character[$cardIndex+2] -= 2;
      break;
    case "DYN088": //Hanabi Blaster - Steam counters, once per turn
      $character[$cardIndex+2] -= 2;
      $character[$cardIndex+1] = 1;
      break;
    case "DYN492a":
      --$character[$cardIndex+ 2];
      BanishCardForPlayer("DYN492a", $currentPlayer, "-");
      break;
    case "WTR005": case "WTR042": case "WTR080": case "WTR151": case "WTR152": case "WTR153": case "WTR154":
    case "ARC005": case "ARC042": case "ARC079": case "ARC116": case "ARC117": case "ARC151": case "ARC153": case "ARC154":
    case "CRU006": case "CRU025": case "CRU081": case "CRU102": case "CRU122": case "CRU141":
    case "MON061": case "MON090": case "MON108": case "MON188": case "MON230": case "MON238": case "MON239": case "MON240":
    case "ELE116": case "ELE145": case "ELE214": case "ELE225": case "ELE233": case "ELE234": case "ELE235": case "ELE236":
    case "EVR053": case "EVR103": case "EVR137":
    case "DVR004": case "DVR005":
    case "RVD004":
    case "UPR004": case "UPR047": case "UPR085": case "UPR125": case "UPR137": case "UPR159": case "UPR167":
    case "DYN046": case "DYN117": case "DYN118": case "DYN171": case "DYN235":
    case "OUT011": case "OUT049": case "OUT095": case "OUT098": case "OUT140": case "OUT141": case "OUT157": case "OUT158":
    case "OUT175": case "OUT176": case "OUT177": case "OUT178": case "OUT179": case "OUT180": case "OUT181": case "OUT182":
      DestroyCharacter($currentPlayer, $cardIndex);
      break;
    default:
      --$character[$cardIndex+5];
      if($character[$cardIndex+5] == 0) $character[$cardIndex+1] = 1; //By default, if it's used, set it to used
      break;
  }
}

function DecisionQueueStaticEffect($phase, $player, $parameter, $lastResult)
{
  global $redirectPath, $playerID, $gameName;
  global $currentPlayer, $combatChain, $defPlayer;
  global $combatChainState, $CCS_CurrentAttackGainedGoAgain, $actionPoints;
  global $defCharacter, $CS_NumCharged, $otherPlayer;
  global $CS_NumFusedEarth, $CS_NumFusedIce, $CS_NumFusedLightning, $CS_NextNAACardGoAgain, $CCS_AttackTarget;
  global $CS_LayerTarget, $dqVars, $mainPlayer, $lastPlayed, $dqState, $CS_AbilityIndex, $CS_CharacterIndex;
  global $CS_AdditionalCosts, $CS_AlluvionUsed, $CS_MaxQuellUsed, $CS_DamageDealt, $CS_ArcaneTargetsSelected, $inGameStatus;
  global $CS_ArcaneDamageDealt, $MakeStartTurnBackup, $CCS_AttackTargetUID, $chainLinkSummary, $chainLinks, $MakeStartGameBackup;
  $rv = "";
  switch ($phase) {
    case "FINDINDICES":
      UpdateGameState($currentPlayer);
      BuildMainPlayerGamestate();
      $parameters = explode(",", $parameter);
      $parameter = $parameters[0];
      if(count($parameters) > 1) $subparam = $parameters[1];
      else $subparam = "";
      switch($parameter) {
        case "ARCANETARGET": $rv = GetArcaneTargetIndices($player, $subparam); break;
        case "DAMAGEPREVENTION":
          $rv = GetDamagePreventionIndices($player);
          break;
        case "DAMAGEPREVENTIONTARGET": $rv = GetDamagePreventionTargetIndices(); break;
        case "WTR083":
          $rv = SearchDeckForCard($player, "WTR081");
          if($rv != "") $rv = count(explode(",", $rv)) . "-" . $rv;
          break;
        case "WTR081":
          $rv = LordOfWindIndices($player);
          if($rv != "") $rv = count(explode(",", $rv)) . "-" . $rv;
          break;
        case "ARC079":
          $rv = CombineSearches(SearchDiscard($player, "AA", "", -1, -1, "RUNEBLADE"), SearchDiscard($player, "A", "", -1, -1, "RUNEBLADE"));
          break;
        case "ARC121": $rv = SearchDeck($player, "", "", $lastResult, -1, "WIZARD"); break;
        case "CRU143": $rv = SearchDiscard($player, "AA", "", -1, -1, "RUNEBLADE"); break;
        case "DECK": $rv = SearchDeck($player); break;
        case "TOPDECK":
          $deck = &GetDeck($player);
          if(count($deck) > 0) $rv = "0";
          break;
        case "DECKTOPXINDICES":
          $deck = &GetDeck($player);
          for($i=0; $i<$subparam && $i<count($deck); ++$i)
          {
            if($rv != "") $rv .= ",";
            $rv .= $i;
          }
          break;
        case "DECKTOPXREMOVE":
          $deck = new Deck($player);
          $rv = $deck->Top(true, $subparam);
          break;
        case "PERMSUBTYPE":
          if($subparam == "Aura") $rv = SearchAura($player, "", $subparam);
          else $rv = SearchPermanents($player, "", $subparam);
          break;
        case "MZSTARTTURN": $rv = MZStartTurnIndices(); break;
        case "HAND":
          $hand = &GetHand($player);
          $rv = GetIndices(count($hand));
          break;
        //This one requires CHOOSEMULTIZONECANCEL
        case "HANDPITCH": $rv = SearchHand($player, "", "", -1, -1, "", "", false, false, $subparam); break;
        case "HANDACTIONMAXCOST": $rv = CombineSearches(SearchHand($player, "A", "", $subparam), SearchHand($player, "AA", "", $subparam)); break;
        case "MULTIHAND":
          $hand = &GetHand($player);
          $rv = count($hand) . "-" . GetIndices(count($hand));
          break;
        case "MULTIHANDAA":
          $search = SearchHand($player, "AA");
          $rv = SearchCount($search) . "-" . $search;
          break;
        case "ARSENAL":
          $arsenal = &GetArsenal($player);
          $rv = GetIndices(count($arsenal), 0, ArsenalPieces());
          break;
        //These are needed because MZ search doesn't have facedown parameter
        case "ARSENALDOWN": $rv = GetArsenalFaceDownIndices($player); break;
        case "ARSENALUP": $rv = GetArsenalFaceUpIndices($player); break;
        case "ITEMSMAX": $rv = SearchItems($player, "", "", $subparam); break;
        case "EQUIP": $rv = GetEquipmentIndices($player); break;
        case "EQUIP0": $rv = GetEquipmentIndices($player, 0); break;
        case "EQUIPCARD": $rv = FindCharacterIndex($player, $subparam); break;
        case "EQUIPONCC": $rv = GetEquipmentIndices($player, onCombatChain:true); break;
        case "CCAA": $rv = SearchCombatChainLink($player, "AA"); break;
        case "CCDEFLESSX": $rv = SearchCombatChainLink($player, "", "", -1, -1, "", "", false, false, -1, false, -1, $subparam); break;
        case "HANDAAMAXCOST": $rv = SearchHand($player, "AA", "", $subparam); break;
        case "MYHANDAA": $rv = SearchHand($player, "AA"); break;
        case "MYHANDARROW": $rv = SearchHand($player, "", "Arrow"); break;
        case "MYDISCARDARROW": $rv = SearchDiscard($player, "", "Arrow"); break;
        case "MAINHAND":
          $hand = &GetHand($mainPlayer);
          $rv = GetIndices(count($hand)); break;
        case "HANDEARTH": $rv = SearchHand($player, "", "", -1, -1, "", "EARTH"); break;
        case "HANDICE": $rv = SearchHand($player, "", "", -1, -1, "", "ICE"); break;
        case "HANDLIGHTNING": $rv = SearchHand($player, "", "", -1, -1, "", "LIGHTNING"); break;
        case "BANISHTYPE": $rv = SearchBanish($player, $subparam); break;
        case "GY":
          $discard = &GetDiscard($player);
          $rv = GetIndices(count($discard));
          break;
        case "GYTYPE": $rv = SearchDiscard($player, $subparam); break;
        case "GYAA": $rv = SearchDiscard($player, "AA"); break;
        case "GYNAA": $rv = SearchDiscard($player, "A"); break;
        case "GYCLASSAA": $rv = SearchDiscard($player, "AA", "", -1, -1, $subparam); break;
        case "GYCLASSNAA": $rv = SearchDiscard($player, "A", "", -1, -1, $subparam); break;
        case "GYCARD": $rv = SearchDiscardForCard($player, $subparam); break;
        case "WEAPON": $rv = WeaponIndices($player, $player, $subparam); break;
        case "MON020": case "MON021": case "MON022": $rv = SearchDiscard($player, "", "", -1, -1, "", "", false, true); break;
        case "MON033-1":
          $soul = &GetSoul($player);
          $rv = GetIndices(count($soul), 1);
          break;
        case "MON033-2": $rv = CombineSearches(SearchDeck($player, "A", "", $lastResult), SearchDeck($player, "AA", "", $lastResult)); break;
        case "MON125": $rv = SearchDeck($player, "", "", -1, -1, "", "", true); break;
        case "MON156": $rv = SearchHand($player, "", "", -1, -1, "", "", true); break;
        case "MON158": $rv = InvertExistenceIndices($player); break;
        case "MON159": case "MON160": case "MON161": $rv = SearchDiscard($player, "A", "", -1, -1, "", "", true); break;
        case "MON212": $rv = SearchBanish($player, "AA", "", $subparam); break;
        case "MON266-1": $rv = SearchHand($player, "AA", "", -1, -1, "", "", false, false, -1, false, 3); break;
        case "MON266-2": $rv = SearchDeckForCard($player, "MON296", "MON297", "MON298"); break;
        case "MON303": $rv =  SearchDiscard($player, "AA", "", 2); break;
        case "MON304": $rv = SearchDiscard($player, "AA", "", 1); break;
        case "MON305": $rv = SearchDiscard($player, "AA", "", 0); break;
        case "ELE006": $rv = SearchDeck($player, "AA", "", CountAura("WTR075", $player), -1, "GUARDIAN"); break;
        case "ELE113": $rv = PulseOfCandleholdIndices($player); break;
        case "ELE116": $rv = PlumeOfEvergrowthIndices($player); break;
        case "ELE125": case "ELE126": case "ELE127": $rv = SummerwoodShelterIndices($player); break;
        case "ELE140": case "ELE141": case "ELE142": $rv = SowTomorrowIndices($player, $parameter); break;
        case "EVR178": $rv = SearchDeckForCard($player, "MON281", "MON282", "MON283"); break;
        case "HEAVE": $rv = HeaveIndices(); break;
        case "BRAVOSTARSHOW": $rv = BravoStarOfTheShowIndices(); break;
        case "AURACLASS": $rv = SearchAura($player, "", "", -1, -1, $subparam); break;
        case "DECKAURAMAXCOST": $rv = SearchDeck($player, "", "Aura", $subparam); break;
        case "CROWNOFREFLECTION": $rv = SearchHand($player, "", "Aura", -1, -1, "ILLUSIONIST"); break;
        case "LIFEOFPARTY": $rv = LifeOfThePartyIndices(); break;
        case "COALESCENTMIRAGE": $rv = SearchHand($player, "", "Aura", -1, 0, "ILLUSIONIST"); break;
        case "MASKPOUNCINGLYNX": $rv = SearchDeck($player, "AA", "", -1, -1, "", "", false, false, -1, false, 2); break;
        case "SHATTER": $rv = ShatterIndices($player, $subparam); break;
        case "KNICKKNACK": $rv = KnickKnackIndices($player); break;
        case "CASHOUT": $rv = CashOutIndices($player); break;
        case "UPR086": $rv = ThawIndices($player); break;
        case "QUELL": $rv = QuellIndices($player); break;
        case "SOUL": $rv = SearchSoul($player, talent:"LIGHT"); break;
        default: $rv = ""; break;
      }
      return ($rv == "" ? "PASS" : $rv);
    case "MULTIZONEINDICES":
      $rv = SearchMultizone($player, $parameter);
      return ($rv == "" ? "PASS" : $rv);
    case "PUTPLAY":
      $subtype = CardSubType($lastResult);
      if ($subtype == "Item") {
        PutItemIntoPlayForPlayer($lastResult, $player, ($parameter != "-" ? $parameter : 0));
      } else if (DelimStringContains($subtype, "Aura")) {
        PlayAura($lastResult, $player);
        PlayAbility($lastResult, "-", 0);
      }
      return $lastResult;
    case "DRAW":
      return Draw($player);
    case "MULTIBANISH":
      if($lastResult == "") return $lastResult;
      $cards = explode(",", $lastResult);
      $params = explode(",", $parameter);
      if(count($params) < 3) array_push($params, "");
      $mzIndices = "";
      for ($i = 0; $i < count($cards); ++$i) {
        $index = BanishCardForPlayer($cards[$i], $player, $params[0], $params[1], $params[2]);
        if ($mzIndices != "") $mzIndices .= ",";
        $mzIndices .= "BANISH-" . $index;
      }
      $dqState[5] = $mzIndices;
      return $lastResult;
    case "DUPLICITYBANISH":
      $cards = explode(",", $lastResult);
      $params = explode(",", $parameter);
      $mzIndices = "";

      if (cardType($cards[0]) == "A") {
        $isPlayable = $params[1];
      } else {
        $isPlayable = "-";
      }

      for ($i = 0; $i < count($cards); ++$i) {
        $index = BanishCardForPlayer($cards[$i], $player, $params[0], $isPlayable);
        if ($mzIndices != "") $mzIndices .= ",";
        $mzIndices .= "BANISH-" . $index;
      }
      $dqState[5] = $mzIndices;
      return $lastResult;
    case "REMOVECOMBATCHAIN":
      $cardID = $combatChain[$lastResult];
      for ($i = CombatChainPieces() - 1; $i >= 0; --$i) {
        unset($combatChain[$lastResult + $i]);
      }
      $combatChain = array_values($combatChain);
      return $cardID;
    case "REMOVESOUL":
      $soul = &GetSoul($player);
      $cardID = $soul[$lastResult];
      unset($soul[$lastResult]);
      $soul = array_values($soul);
      return $cardID;
    case "COMBATCHAINPOWERMODIFIER":
      CombatChainPowerModifier($lastResult, $parameter);
      return $lastResult;
    case "COMBATCHAINDEFENSEMODIFIER":
      if($parameter < 0) {
        $defense = BlockingCardDefense($lastResult);
        if($parameter < $defense * -1) $parameter = $defense * -1;
      }
      $combatChain[$lastResult + 6] += $parameter;
      return $lastResult;
    case "REMOVEDISCARD":
      $discard = &GetDiscard($player);
      $cardID = $discard[$lastResult];
      unset($discard[$lastResult]);
      $discard = array_values($discard);
      return $cardID;
    case "REMOVEMYHAND":
      $hand = &GetHand($player);
      $cardID = $hand[$lastResult];
      unset($hand[$lastResult]);
      $hand = array_values($hand);
      return $cardID;
    case "HANDCARD":
      $hand = &GetHand($player);
      $cardID = $hand[$lastResult];
      return $cardID;
    case "MULTIREMOVEDISCARD":
      $discard = &GetDiscard($player);
      $cards = "";
      if (!is_array($lastResult)) $lastResult = explode(",", $lastResult);
      $cardsRemoved = "";
      for ($i = 0; $i < count($lastResult); ++$i) {
        if ($cards != "") $cards .= ",";
        $cards .= $discard[$lastResult[$i]];
        if($parameter == "1") WriteLog(CardLink($discard[$lastResult[$i]], $discard[$lastResult[$i]]));
        unset($discard[$lastResult[$i]]);
      }
      $discard = array_values($discard);
      return $cards;
    case "MULTIREMOVEMYSOUL":
      for ($i = 0; $i < $lastResult; ++$i) BanishFromSoul($player);
      return $lastResult;
    case "ADDHAND":
      AddPlayerHand($lastResult, $player, "-");
      return $lastResult;
    case "ADDMYPITCH":
      $pitch = &GetPitch($player);
      array_push($pitch, $lastResult);
      return $lastResult;
    case "PITCHABILITY":
      PitchAbility($lastResult);
      return $lastResult;
    case "ADDARSENALFACEUP":
      $params = explode("-", $parameter);
      if (count($params) > 1) AddArsenal($lastResult, $player, $params[0], "UP", $params[1]);
      else AddArsenal($lastResult, $player, $params[0], "UP");
      return $lastResult;
    case "ADDARSENALFACEDOWN":
      AddArsenal($lastResult, $player, $parameter, "DOWN");
      return $lastResult;
    case "TURNARSENALFACEUP":
      $arsenal = &GetArsenal($player);
      $arsenal[$lastResult + 1] = "UP";
      return $lastResult;
    case "REMOVEARSENAL":
      $index = $lastResult;
      $arsenal = &GetArsenal($player);
      $cardToReturn = $arsenal[$index];
      RemoveArsenalEffects($player, $cardToReturn);
      for($i = $index + ArsenalPieces() - 1; $i >= $index; --$i) {
        unset($arsenal[$i]);
      }
      $arsenal = array_values($arsenal);
      return $cardToReturn;
    case "MULTIADDHAND":
      $cards = explode(",", $lastResult);
      $hand = &GetHand($player);
      $log = "";
      for($i = 0; $i < count($cards); ++$i) {
        if($parameter == "1") {
          if($log != "") $log .= ", ";
          if($i != 0 && $i == count($cards) - 1) $log .= "and ";
          $log .= CardLink($cards[$i], $cards[$i]);
        }
        array_push($hand, $cards[$i]);
      }
      if($log != "") WriteLog($log . " added to hand");
      return $lastResult;
    case "MULTIREMOVEHAND":
      $cards = "";
      $hand = &GetHand($player);
      if(!is_array($lastResult)) $lastResult = explode(",", $lastResult);
      for($i = 0; $i < count($lastResult); ++$i) {
        if($cards != "") $cards .= ",";
        $cards .= $hand[$lastResult[$i]];
        unset($hand[$lastResult[$i]]);
      }
      $hand = array_values($hand);
      return $cards;
    case "ADDLAYER":
      AddLayer("TRIGGER", $player, $parameter);
      return $lastResult;
    case "DESTROYCHARACTER":
      DestroyCharacter($player, $lastResult);
      return $lastResult;
    case "DESTROYEQUIPDEF0":
      $character = &GetPlayerCharacter($defPlayer);
      if (BlockValue($character[$lastResult]) + $character[$lastResult + 4] <= 0) {
        WriteLog(CardLink($character[$lastResult], $character[$lastResult]) . " was destroyed.");
        DestroyCharacter($defPlayer, $lastResult);
      }
      return "";
    case "CHARFLAGDESTROY":
      $character = &GetPlayerCharacter($player);
      $character[$parameter + 7] = 1;
      return $lastResult;
    case "ADDCHARACTEREFFECT":
      $characterEffects = &GetCharacterEffects($player);
      array_push($characterEffects, $lastResult);
      array_push($characterEffects, $parameter);
      return $lastResult;
    case "ADDMZBUFF":
      $lrArr = explode("-", $lastResult);
      $characterEffects = &GetCharacterEffects($player);
      array_push($characterEffects, $lrArr[1]);
      array_push($characterEffects, $parameter);
      return $lastResult;
    case "ADDMZUSES":
      $lrArr = explode("-", $lastResult);
      switch($lrArr[0]) {
        case "MYCHAR": case "THEIRCHAR": AddCharacterUses($player, $lrArr[1], $parameter); break;
        default: break;
      }
      return $lastResult;
    case "MZOP":
      switch ($parameter)
      {
        case "FREEZE": MZFreeze($lastResult); break;
        case "GAINCONTROL": MZGainControl($player, $lastResult); break;
        case "GETCARDID": return GetMZCard($player, $lastResult);
        case "GETCARDINDEX": $mzArr = explode("-", $lastResult); return $mzArr[1];
        case "GETUNIQUEID":
          $mzArr = explode("-", $lastResult);
          $zone = &GetMZZone($player, $mzArr[0]);
          switch($mzArr[0]) {
            case "ALLY": case "MYALLY": case "THEIRALLY": return $zone[$mzArr[1] + 5];
            case "BANISH": case "MYBANISH": case "THEIRBANISH": return $zone[$mzArr[1] + 2];
            default: return "-1";
          }
        default: break;
      }
      return $lastResult;
    case "OP":
      switch($parameter)
      {
        case "DESTROYFROZENARSENAL": DestroyFrozenArsenal($player); return "";
        case "GIVEATTACKGOAGAIN": GiveAttackGoAgain(); return $lastResult;
        case "REMOVECARD":
          if($lastResult == "") return $dqVars[0];
          $cards = explode(",", $dqVars[0]);
          for($i = 0; $i < count($cards); ++$i) {
            if($cards[$i] == $lastResult) {
              unset($cards[$i]);
              $cards = array_values($cards);
              break;
            }
          }
          return implode(",", $cards);
        default: return $lastResult;
      }
    case "FILTER":
      $params = explode("-", $parameter);
      $from = $params[0];
      $relationship = $params[1];//exclude other or include
      $type = $params[2];
      $compare = $params[3];
      $input = [];
      switch($from)
      {
        case "LastResult": $input = explode(",", $lastResult); for($i=0; $i<count($input); ++$i) $input[$i] = $input[$i] . "-" . $input[$i]; break;
        case "CombatChain":
          $lastResultArr = explode(",", $lastResult);
          for($i=0; $i<count($lastResultArr); ++$i) array_push($input, $combatChain[$lastResultArr[$i]+CCOffset($type)] . "-" . $lastResultArr[$i]);
        default: break;
      }
      $output = [];
      for($i=0; $i<count($input); ++$i)
      {
        $inputArr = explode("-", $input[$i]);
        $passFilter = ($relationship == "include" ? false : true);
        switch($type)
        {
          case "type": if(CardType($inputArr[0]) == $compare) $passFilter = !$passFilter; break;
          case "subtype": if(SubtypeContains($inputArr[0], $compare, $player)) $passFilter = !$passFilter; break;
          case "player": if($inputArr[0] == $compare) $passFilter = !$passFilter; break;
          default: break;
        }
        if($passFilter) array_push($output, $inputArr[1]);
      }
      return (count($output) > 0 ? implode(",", $output) : "PASS");
    case "PASSPARAMETER":
      return $parameter;
    case "DISCARDCARD":
      AddGraveyard($lastResult, $player, $parameter);
      CardDiscarded($player, $lastResult);
      WriteLog(CardLink($lastResult, $lastResult) . " was discarded");
      return $lastResult;
    case "ADDDISCARD":
      AddGraveyard($lastResult, $player, $parameter);
      return $lastResult;
    case "ADDBOTDECK":
      $deck = &GetDeck($player);
      array_push($deck, $lastResult);
      return $lastResult;
    case "MULTIADDDECK":
      $deck = &GetDeck($player);
      $cards = explode(",", $lastResult);
      for($i = 0; $i < count($cards); ++$i) array_push($deck, $cards[$i]);
      return $lastResult;
    case "MULTIADDTOPDECK":
      $deck = &GetDeck($player);
      $cards = explode(",", $lastResult);
      for($i = 0; $i < count($cards); ++$i) {
        if($parameter == "1") WriteLog(CardLink($cards[$i], $cards[$i]));
        array_unshift($deck, $cards[$i]);
      }
      return $lastResult;
    case "MULTIREMOVEDECK":
      if(!is_array($lastResult)) $lastResult = ($lastResult == "" ? [] : explode(",", $lastResult));
      $cards = "";
      $deck = &GetDeck($player);
      for($i = 0; $i < count($lastResult); ++$i) {
        if($cards != "") $cards .= ",";
        $cards .= $deck[$lastResult[$i]];
        unset($deck[$lastResult[$i]]);
      }
      $deck = array_values($deck);
      return $cards;
    case "PLAYAURA":
      PlayAura($parameter, $player);
      break;
    case "DESTROYALLY":
      DestroyAlly($player, $lastResult);
      break;
    case "PARAMDELIMTOARRAY":
      return explode(",", $parameter);
    case "ADDSOUL":
      AddSoul($lastResult, $player, $parameter);
      return $lastResult;
    case "SHUFFLEDECK":
      $zone = &GetDeck($player);
      $destArr = [];
      if($parameter == "SKIPSEED") { global $randomSeeded; $randomSeeded = true; }
      while(count($zone) > 0) {
        $index = GetRandom(0, count($zone) - 1);
        array_push($destArr, $zone[$index]);
        unset($zone[$index]);
        $zone = array_values($zone);
      }
      $zone = $destArr;
      return $lastResult;
    case "EXHAUSTCHARACTER":
      $character = &GetPlayerCharacter($player);
      $character[$parameter + 1] = 1;
      return $parameter;
    case "DECKCARDS":
      $indices = explode(",", $parameter);
      $deck = &GetDeck($player);
      $rv = "";
      for($i = 0; $i < count($indices); ++$i) {
        if(count($deck) <= $i) continue;
        if($rv != "") $rv .= ",";
        $rv .= $deck[$i];
      }
      return ($rv == "" ? "PASS" : $rv);
    case "SHOWSELECTEDMODE":
      $rv = implode(" ", explode("_", $lastResult));
      WriteLog(CardLink($parameter, $parameter) . " mode is: " . $rv);
      return $lastResult;
    case "SHOWSELECTEDMODES":
      $rv = "";
      for($i = 0; $i < count($lastResult); ++$i) {
        if($rv != "") $rv .= " and ";
        $rv .= implode(" ", explode("_", $lastResult[$i]));
      }
      WriteLog(CardLink($parameter, $parameter) . " modes are: " . $rv);
      return $lastResult;
    case "SHOWSELECTEDHANDCARD":
      $hand = &GetHand($player);
      WriteLog(CardLink($hand[$lastResult], $hand[$lastResult]) . " was selected.");
      return $lastResult;
    case "REVEALCARDS":
      $cards = (is_array($lastResult) ? implode(",", $lastResult) : $lastResult);
      $revealed = RevealCards($cards, $player);
      return ($revealed ? $lastResult : "PASS");
    case "REVEALHANDCARDS":
      $indices = (is_array($lastResult) ? $lastResult : explode(",", $lastResult));
      $hand = &GetHand($player);
      $cards = "";
      for($i = 0; $i < count($indices); ++$i) {
        if($cards != "") $cards .= ",";
        $cards .= $hand[$indices[$i]];
      }
      $revealed = RevealCards($cards, $player);
      return ($revealed ? $cards : "PASS");
    case "WRITELOG":
      WriteLog(implode(" ", explode("_", $parameter)));
      return $lastResult;
    case "WRITECARDLOG":
      $message = implode(" ", explode("_", $parameter)) . CardLink($lastResult, $lastResult);
      WriteLog($message);
      return $lastResult;
    case "ADDNEGDEFCOUNTER":
      if($lastResult == "") return $lastResult;
      $character = &GetPlayerCharacter($player);
      $character[$lastResult + 4] = intval($character[$lastResult + 4]) - 1;
      WriteLog(CardLink($character[$lastResult], $character[$lastResult]) . " gained a negative counter.");
      return $lastResult;
    case "ADDEQUIPCOUNTER":
      $character = &GetPlayerCharacter($player);
      $character[$lastResult + 3] += 1;
      WriteLog("A counter was added to " . CardLink($character[$lastResult], $character[$lastResult]));
      return $lastResult;
    case "REMOVENEGDEFCOUNTER":
      $character = &GetPlayerCharacter($player);
      $character[$lastResult + 4] += 1;
      WriteLog("A negative counter was removed from " . CardLink($character[$lastResult], $character[$lastResult]));
      return $lastResult;
    case "REMOVECOUNTER":
      $character = &GetPlayerCharacter($player);
      $character[$lastResult + 2] -= 1;
      WriteLog(CardLink($parameter, $parameter) . " removed a counter from " . CardLink($character[$lastResult], $character[$lastResult]) . ".");
      return $lastResult;
    case "ADDIMMEDIATECURRENTEFFECT":
      AddCurrentTurnEffect($parameter, $player, "PLAY");
      return "1";
    case "ADDCURRENTEFFECT":
      AddCurrentTurnEffect($parameter, $player);
      return "1";
    case "ADDCURRENTANDNEXTTURNEFFECT":
      AddCurrentTurnEffect($parameter, $player);
      AddNextTurnEffect($parameter, $player);
      return "1";
    case "ADDLIMITEDCURRENTEFFECT":
      $params = explode(",", $parameter);
      AddCurrentTurnEffect($params[0], $player, $params[1], $lastResult);
      return $lastResult;
    case "ADDARSENALUNIQUEIDCURRENTEFFECT":
      $arsenal = &GetArsenal($player);
      $params = explode(",", $parameter);
      AddCurrentTurnEffect($params[0], $player, $params[1], $arsenal[$lastResult + 5]);
      return $lastResult;
    case "ADDAIMCOUNTER":
      $arsenal = &GetArsenal($player);
      $arsenal[$lastResult + 3] += 1;
      return $lastResult;
    case "OPTX":
      Opt("NA", $parameter);
      return $lastResult;
    case "SETCLASSSTATE":
      SetClassState($player, $parameter, $lastResult);
      return $lastResult;
    case "SETCLASSSTATEMULTICHOOSETEXT":
      $value = $lastResult[0] . "," . $lastResult[1];
      SetClassState($player, $parameter, $value);
      return $lastResult;
    case "GAINACTIONPOINTS":
      $actionPoints += $parameter;
      return $lastResult;
    case "EQUALPASS":
      if($lastResult == $parameter) return "PASS";
      return 1;
    case "NOTEQUALPASS":
      if($lastResult != $parameter) return "PASS";
      return 1;
    case "NOPASS":
      if ($lastResult == "NO") return "PASS";
      return 1;
    case "SANDSCOURGREATBOW":
      if($lastResult == "NO") ReloadArrow($player);
      else {
        AddDecisionQueue("PARAMDELIMTOARRAY", $player, "0", 1);
        AddDecisionQueue("MULTIREMOVEDECK", $player, "-", 1);
        AddDecisionQueue("NULLPASS", $player, "-", 1);
        AddDecisionQueue("ADDARSENALFACEUP", $player, "DECK-1", 1);
        AddDecisionQueue("ALLCARDSUBTYPEORPASS", $player, "Arrow", 1);
      }
      return $lastResult;
    case "NULLPASS":
      if($lastResult == "") return "PASS";
      return $lastResult;
    case "ELSE":
      if($lastResult == "PASS") return "0";
      else if ($lastResult == "NO") return "NO";
      else return "PASS";
    case "FINDCURRENTEFFECTPASS":
      if(SearchCurrentTurnEffects($parameter, $player)) return "PASS";
      return $lastResult;
    case "LESSTHANPASS":
      if($lastResult < $parameter) return "PASS";
      return $lastResult;
    case "GREATERTHANPASS":
      if($lastResult > $parameter) return "PASS";
      return $lastResult;
    case "EQUIPDEFENSE":
      $char = &GetPlayerCharacter($player);
      $defense = BlockValue($char[$lastResult]) + $char[$lastResult + 4];
      if($defense < 0) $defense = 0;
      return $defense;
    case "ALLCARDTYPEORPASS":
      $cards = explode(",", $lastResult);
      for($i = 0; $i < count($cards); ++$i) {
        if(CardType($cards[$i]) != $parameter) return "PASS";
      }
      return $lastResult;
    case "NONECARDTYPEORPASS":
      $cards = explode(",", $lastResult);
      for($i = 0; $i < count($cards); ++$i) {
        if(CardType($cards[$i]) == $parameter) return "PASS";
      }
      return $lastResult;
    case "ALLCARDSUBTYPEORPASS":
      $cards = explode(",", $lastResult);
      for($i = 0; $i < count($cards); ++$i) {
        if(CardSubtype($cards[$i]) != $parameter) return "PASS";
      }
      return $lastResult;
    case "ALLCARDTALENTORPASS":
      $cards = explode(",", $lastResult);
      for($i = 0; $i < count($cards); ++$i) {
        if(!TalentContains($cards[$i], $parameter, $player)) return "PASS";
      }
      return $lastResult;
    case "ALLCARDSCOMBOORPASS":
      $cards = explode(",", $lastResult);
      for($i = 0; $i < count($cards); ++$i) {
        if(!HasCombo($cards[$i])) return "PASS";
      }
      return $lastResult;
    case "ALLCARDMAXCOSTORPASS":
      $cards = explode(",", $lastResult);
      for($i = 0; $i < count($cards); ++$i) {
        if(CardCost($cards[$i]) > $parameter) return "PASS";
      }
      return $lastResult;
    case "ALLCARDCLASSORPASS":
      $cards = explode(",", $lastResult);
      for($i = 0; $i < count($cards); ++$i) {
        if(!ClassContains($cards[$i], $parameter, $player)) return "PASS";
      }
      return $lastResult;
    case "CLASSSTATEGREATERORPASS":
      $parameters = explode("-", $parameter);
      $state = $parameters[0];
      $threshold = $parameters[1];
      if(GetClassState($player, $state) < $threshold) return "PASS";
      return 1;
    case "CHARREADYORPASS":
      $char = &GetPlayerCharacter($player);
      if($char[$parameter + 1] != 2) return "PASS";
      return 1;
    case "LORDOFWIND":
      $number = 0;
       if($lastResult != "") {
        $number = count(explode(",", $lastResult));
       }
      AddResourceCost($player, $number);
      AddCurrentTurnEffect("WTR081-" . $number, $player);
      return $number;
    case "IRONHIDE":
      $character = &GetPlayerCharacter($player);
      $index = FindCharacterIndex($player, $combatChain[$parameter]);
      $character[$index + 4] += 2;
      return $lastResult;
    case "VAMBRACE":
      $character = &GetPlayerCharacter($player);
      $index = FindCharacterIndex($player, $combatChain[$parameter]);
      $character[$index + 4] += 1;
      return $lastResult;
    case "BOOST":
      global $CS_NumBoosted, $CCS_NumBoosted, $CCS_IsBoosted;
      $deck = &GetDeck($currentPlayer);
      if(count($deck) == 0) {
        WriteLog("Could not boost. No cards left in deck.");
        return;
      }
      ItemBoostEffects();
      $actionPoints += CountCurrentTurnEffects("ARC006", $currentPlayer);
      $cardID = $deck[0];
      if(CardSubType($cardID) == "Item" && SearchCurrentTurnEffects("DYN091-2", $player, true)) {
        PutItemIntoPlay($cardID);
      }
      else BanishCardForPlayer($cardID, $currentPlayer, "DECK", "BOOST");
      unset($deck[0]);
      $deck = array_values($deck);
      $grantsGA = ClassContains($cardID, "MECHANOLOGIST", $currentPlayer);
      WriteLog("Boost banished " . CardLink($cardID, $cardID) . " and " . ($grantsGA ? "DID" : "did NOT") . " grant go again");
      IncrementClassState($currentPlayer, $CS_NumBoosted);
      ++$combatChainState[$CCS_NumBoosted];
      $combatChainState[$CCS_IsBoosted] = 1;
      if($grantsGA) GiveAttackGoAgain();
      return $grantsGA;
    case "VOFTHEVANGUARD":
      if($parameter == "1" && TalentContains($lastResult, "LIGHT")) {
        WriteLog("V of the Vanguard gives all attacks on this combat chain +1");
        AddCurrentTurnEffect("MON035", $player);
      }
      $hand = &GetHand($player);
      if(count($hand) > 0) {
        PrependDecisionQueue("VOFTHEVANGUARD", $player, "1", 1);
        PrependDecisionQueue("CHARGE", $player, "-", 1);
      }
      return "1";
    case "TRIPWIRETRAP":
      if($lastResult == 0) {
        WriteLog("Hit effects are prevented by " . CardLink("CRU126", "CRU126") . " this chain link");
        HitEffectsPreventedThisLink();
      }
      return 1;
    case "ATTACKMODIFIER":
      $amount = intval($parameter);
      $combatChain[5] += $amount;
      return 1;
    case "SONATAARCANIX":
      $cards = explode(",", $lastResult);
      $numAA = 0;
      $numNAA = 0;
      $AAIndices = "";
      for($i = 0; $i < count($cards); ++$i) {
        $cardType = CardType($cards[$i]);
        if($cardType == "A") ++$numNAA;
        else if($cardType == "AA") {
          ++$numAA;
          if($AAIndices != "") $AAIndices .= ",";
          $AAIndices .= $i;
        }
      }
      $numMatch = ($numAA > $numNAA ? $numNAA : $numAA);
      if($numMatch == 0) return "PASS";
      return $numMatch . "-" . $AAIndices . "-" . $numMatch;
    case "SONATAARCANIXSTEP2":
      $numArcane = count(explode(",", $lastResult));
      DealArcane($numArcane, 0, "PLAYCARD", "MON231", true);
      return 1;
    case "SOULREAPING":
      $cards = explode(",", $lastResult);
      if(count($cards) > 0) AddCurrentTurnEffect("MON199", $player);
      $numBD = 0;
      for($i = 0; $i < count($cards); ++$i) if (HasBloodDebt($cards[$i])) {
        ++$numBD;
      }
      GainResources($player, $numBD);
      return 1;
    case "CHARGE":
      DQCharge();
      return "1";
    case "FINISHCHARGE":
      IncrementClassState($player, $CS_NumCharged);
      return $lastResult;
    case "DEALDAMAGE":
      $target = (is_array($lastResult) ? $lastResult : explode("-", $lastResult));
      $targetPlayer = ($target[0] == "MYCHAR" || $target[0] == "MYALLY" ? $player : ($player == 1 ? 1 : 2));
      $parameters = explode("-", $parameter);
      $damage = $parameters[0];
      $source = $parameters[1];
      $type = $parameters[2];
      if($target[0] == "THEIRALLY" || $target[0] == "MYALLY") {
        $allies = &GetAllies($targetPlayer);
        if($allies[$target[1] + 6] > 0) {
          $damage -= 3;
          if($damage < 0) $damage = 0;
          --$allies[$target[1] + 6];
        }
        $allies[$target[1] + 2] -= $damage;
        if($damage > 0) AllyDamageTakenAbilities($targetPlayer, $target[1]);
        if($allies[$target[1] + 2] <= 0) DestroyAlly($targetPlayer, $target[1]);
        return $damage;
      } else {
        PrependDecisionQueue("TAKEDAMAGE", $targetPlayer, $parameter);
        DoQuell($targetPlayer, $damage);
      }
      return $damage;
    case "TAKEDAMAGE":
      $params = explode("-", $parameter);
      $damage = intval($params[0]);
      $source = (count($params) > 1 ? $params[1] : "-");
      $type = (count($params) > 2 ? $params[2] : "-");
      if(!CanDamageBePrevented($player, $damage, "DAMAGE")) $lastResult = 0;
      $damage -= intval($lastResult);
      $damage = DealDamageAsync($player, $damage, $type, $source);
      if($type == "COMBAT") $dqState[6] = $damage;
      return $damage;
    case "AFTERQUELL":
      $maxQuell = GetClassState($player, $CS_MaxQuellUsed);
      if($lastResult > 0) WriteLog("Player $player prevented $lastResult damage with Quell", $player);
      if($lastResult > $maxQuell) SetClassState($player, $CS_MaxQuellUsed, $lastResult);
      return $lastResult;
    case "SPELLVOIDCHOICES":
      $damage = $parameter;
      if($lastResult != "PASS") {
        $prevented = ArcaneDamagePrevented($player, $lastResult);
        $damage -= $prevented;
        if($damage < 0) $damage = 0;
        $dqVars[0] = $damage;
        if($damage > 0) CheckSpellvoid($player, $damage);
      }
      PrependDecisionQueue("INCDQVAR", $player, "1", 1);
      return $prevented;
    case "DEALARCANE":
      $dqState[7] = $lastResult;
      $target = explode("-", $lastResult);
      $targetPlayer = ($target[0] == "MYCHAR" || $target[0] == "MYALLY" ? $player : ($player == 1 ? 2 : 1));
      $parameters = explode("-", $parameter);
      $damage = $parameters[0];
      $source = $parameters[1];
      $type = $parameters[2];
      if($type == "PLAYCARD") {
        $damage += ConsumeArcaneBonus($player);
        WriteLog(CardLink($source, $source) . " is dealing " . $damage . " arcane damage");
      }
      if($target[0] == "THEIRALLY" || $target[0] == "MYALLY") {
        $allies = &GetAllies($targetPlayer);
        if($allies[$target[1] + 6] > 0) {
          $damage -= 3;
          if ($damage < 0) $damage = 0;
          --$allies[$target[1] + 6];
        }
        $allies[$target[1] + 2] -= $damage;
        if($damage > 0) AllyDamageTakenAbilities($targetPlayer, $target[1]);
        if($allies[$target[1] + 2] <= 0) {
          DestroyAlly($targetPlayer, $target[1]);
        } else {
          AppendClassState($player, $CS_ArcaneTargetsSelected, $lastResult);
        }
        return "";
      }
      AppendClassState($player, $CS_ArcaneTargetsSelected, $lastResult);
      $target = $targetPlayer;
      $sourceType = CardType($source);
      if($sourceType == "A" || $sourceType == "AA") $damage += CountCurrentTurnEffects("ELE065", $player);
      $arcaneBarrier = ArcaneBarrierChoices($target, $damage);
      PrependDecisionQueue("TAKEARCANE", $target, $damage . "-" . $source . "-" . $player);
      PrependDecisionQueue("PASSPARAMETER", $target, "{1}");
      CheckSpellvoid($target, $damage);
      PrependDecisionQueue("INCDQVAR", $target, "1", 1);
      DoQuell($target, $damage);
      PrependDecisionQueue("INCDQVAR", $target, "1", 1);
      PrependDecisionQueue("PAYRESOURCES", $target, "<-", 1);
      PrependDecisionQueue("ARCANECHOSEN", $target, "-", 1, 1);
      PrependDecisionQueue("CHOOSEARCANE", $target, $arcaneBarrier, 1, 1);
      PrependDecisionQueue("SETDQVAR", $target, "0", 1);
      PrependDecisionQueue("PASSPARAMETER", $target, $damage . "-" . $source, 1);
      PrependDecisionQueue("SETDQVAR", $target, "1", 1);
      PrependDecisionQueue("PASSPARAMETER", $target, "0", 1);
      return $parameter;
    case "ARCANEHITEFFECT":
      if($dqVars[0] > 0) ArcaneHitEffect($player, $parameter, $dqState[7], $dqVars[0]); //player, source, target, damage
      return $lastResult;
    case "ARCANECHOSEN":
      if($lastResult > 0) {
        if(SearchCharacterActive($player, "UPR166")) {
          $char = &GetPlayerCharacter($player);
          $index = FindCharacterIndex($player, "UPR166");
          if($char[$index + 2] < 4 && GetClassState($player, $CS_AlluvionUsed) == 0) {
            ++$char[$index + 2];
            SetClassState($player, $CS_AlluvionUsed, 1);
          }
        }
      }
      return $lastResult;
    case "TAKEARCANE":
      $parameters = explode("-", $parameter);
      $damage = $parameters[0];
      $source = $parameters[1];
      $playerSource = $parameters[2];
      if(!CanDamageBePrevented($player, $damage, "ARCANE")) $lastResult = 0;
      $damage = DealDamageAsync($player, $damage - $lastResult, "ARCANE", $source);
      if($damage < 0) $damage = 0;
      if($damage > 0) IncrementClassState($playerSource, $CS_ArcaneDamageDealt, $damage);
      WriteLog("Player " . $player . " took $damage arcane damage from " . CardLink($source, $source), $player);
      if(DelimStringContains(CardSubType($source), "Ally") && $damage > 0) ProcessDealDamageEffect($source); // Interaction with Burn Them All! + Nekria
      $dqVars[0] = $damage;
      return $damage;
    case "PAYRESOURCES":
      $resources = &GetResources($player);
      $lastResult = intval($lastResult);
      if($lastResult < 0) $resources[0] += (-1 * $lastResult);
      else if($resources[0] > 0) {
        $res = $resources[0];
        $resources[0] -= $lastResult;
        $lastResult -= $res;
        if($resources[0] < 0) $resources[0] = 0;
      }
      if($lastResult > 0) {
        $hand = &GetHand($player);
        if(count($hand) == 0) {
          WriteLog("You have resources to pay for a declared effect, but have no cards to pitch. Reverting gamestate prior to that declaration.");
          RevertGamestate();
        }
        PrependDecisionQueue("PAYRESOURCES", $player, $parameter, 1);
        PrependDecisionQueue("SUBPITCHVALUE", $player, $lastResult, 1);
        PrependDecisionQueue("PITCHABILITY", $player, "-", 1);
        PrependDecisionQueue("ADDMYPITCH", $player, "-", 1);
        PrependDecisionQueue("REMOVEMYHAND", $player, "-", 1);
        PrependDecisionQueue("CHOOSEHANDCANCEL", $player, "<-", 1);
        PrependDecisionQueue("SETDQCONTEXT", $player, "Choose a card to pitch", 1);
        PrependDecisionQueue("FINDINDICES", $player, "HAND", 1);
      }
      return $parameter;
    case "BLIZZARDLOG":
      if($lastResult > 0) WriteLog($lastResult . " was paid for " . CardLink("ELE147", "ELE147"));
      else WriteLog("Target attack lost and can't gain go again due to " . CardLink("ELE147", "ELE147"));
      return $lastResult;
    case "ADDCLASSSTATE":
      $parameters = explode("-", $parameter);
      IncrementClassState($player, $parameters[0], $parameters[1]);
      return 1;
    case "APPENDCLASSSTATE":
      $parameters = explode("-", $parameter);
      AppendClassState($player, $parameters[0], $parameters[1]);
      return $lastResult;
    case "AFTERFUSE":
      $params = explode("-", $parameter);
      $card = $params[0];
      $elements = $params[1];
      $elementArray = explode(",", $elements);
      for ($i = 0; $i < count($elementArray); ++$i) {
        $element = $elementArray[$i];
        switch ($element) {
          case "EARTH": IncrementClassState($player, $CS_NumFusedEarth); break;
          case "ICE": IncrementClassState($player, $CS_NumFusedIce); break;
          case "LIGHTNING": IncrementClassState($player, $CS_NumFusedLightning); break;
          default: break;
        }
        AppendClassState($player, $CS_AdditionalCosts, $elements);
        CurrentTurnFuseEffects($player, $element);
        AuraFuseEffects($player, $element);
        $lastPlayed[3] = (GetClassState($player, $CS_AdditionalCosts) == HasFusion($card) || IsAndOrFuse($card) ? "FUSED" : "UNFUSED");
      }
      return $lastResult;
    case "SUBPITCHVALUE":
      return $parameter - PitchValue($lastResult);
    case "BUFFARCANE":
      AddCurrentTurnEffect($parameter . "-" . $lastResult, $player);
      return $lastResult;
    case "BUFFARCANEPREVLAYER":
      global $layers;
      $index = 0;
      for($index=0; $index<count($layers) && $layers[$index] == "TRIGGER"; $index+=LayerPieces());
      AddCurrentTurnEffect("CRU161", $player, "PLAY", $layers[$index+6]);
      return $lastResult;
    case "DREADBORE":
      $arsenal = &GetArsenal($player);
      AddCurrentTurnEffect($parameter, $player, "HAND", $arsenal[count($arsenal) - ArsenalPieces() + 5]);
      return 1;
    case "AZALEA":
      $arsenal = &GetArsenal($player);
      AddCurrentTurnEffect($parameter, $player, "DECK", $arsenal[count($arsenal) - ArsenalPieces() + 5]);
      return 1;
    case "BULLEYESBRACERS":
      $arsenal = &GetArsenal($player);
      AddCurrentTurnEffect($parameter, $player, "HAND", $arsenal[count($arsenal) - ArsenalPieces() + 5]);
      return 1;
    case "AWAKENINGTOKENS":
      $num = GetHealth($player == 1 ? 2 : 1) - GetHealth($player);
      for ($i = 0; $i < $num; ++$i) {
        PlayAura("WTR075", $player);
      }
      return 1;
    case "DIMENXXIONALGATEWAY":
      if (ClassContains($lastResult, "RUNEBLADE", $player)) DealArcane(1, 0, "PLAYCARD", "MON161", true);
      if (TalentContains($lastResult, "SHADOW", $player)) {
        PrependDecisionQueue("MULTIBANISH", $player, "DECK,-", 1);
        PrependDecisionQueue("MULTIREMOVEDECK", $player, "<-", 1);
        PrependDecisionQueue("FINDINDICES", $player, "TOPDECK", 1);
        PrependDecisionQueue("NOPASS", $player, "-", 1);
        PrependDecisionQueue("YESNO", $player, "if_you_want_to_banish_the_card", 1);
      }
      return $lastResult;
    case "INVERTEXISTENCE":
      if($lastResult == "")
      {
        WriteLog("No cards were selected, so Invert Existence did not banish any cards");
        return $lastResult;
      }
      $cards = explode(",", $lastResult);
      $numAA = 0;
      $numNAA = 0;
      $message = "Invert existence banished ";
      for ($i = 0; $i < count($cards); ++$i) {
        $type = CardType($cards[$i]);
        if ($type == "AA") ++$numAA;
        else if ($type == "A") ++$numNAA;
        if($i >= 1) $message .= ", ";
        if($i != 0 && $i == count($cards) - 1) $message .= "and ";
        $message .= CardLink($cards[$i], $cards[$i]);
      }
      WriteLog($message . ".");
      if ($numAA == 1 && $numNAA == 1) DealArcane(2, 0, "PLAYCARD", "MON158", true, $player);
      return $lastResult;
    case "ROUSETHEANCIENTS":
      $cards = (is_array($lastResult) ? $lastResult : explode(",", $lastResult));
      $totalAV = 0;
      for($i = 0; $i < count($cards); ++$i) {
        $totalAV += AttackValue($cards[$i]);
      }
      if($totalAV >= 13) {
        AddCurrentTurnEffect("MON247", $player);
        WriteLog(CardLink("MON247", "MON247") . " got +7 and go again");
      }
      return $lastResult;
    case "BEASTWITHIN":
      $deck = &GetDeck($player);
      if(count($deck) == 0) {
        LoseHealth(9999, $player);
        WriteLog("Your deck has no cards, so " . CardLink("CRU007", "CRU007") . " continues damaging you until you die");
        return 1;
      }
      $card = array_shift($deck);
      LoseHealth(1, $player);
      WriteLog(CardLink("CRU007", "CRU007") . " banished " . CardLink($card, $card) . " and lost 1 health");
      if(AttackValue($card) >= 6) {
        BanishCardForPlayer($card, $player, "DECK", "-");
        $banish = &GetBanish($player);
        RemoveBanish($player, count($banish) - BanishPieces());
        AddPlayerHand($card, $player, "BANISH");
      } else {
        BanishCardForPlayer($card, $player, "DECK", "-");
        PrependDecisionQueue("BEASTWITHIN", $player, "-");
      }
      return 1;
    case "CROWNOFDICHOTOMY":
      $lastType = CardType($lastResult);
      $indicesParam = ($lastType == "A" ? "GYCLASSAA,RUNEBLADE" : "GYCLASSNAA,RUNEBLADE");
      PrependDecisionQueue("REVEALCARDS", $player, "-", 1);
      PrependDecisionQueue("DECKCARDS", $player, "0", 1);
      PrependDecisionQueue("MULTIADDTOPDECK", $player, "-", 1);
      PrependDecisionQueue("MULTIREMOVEDISCARD", $player, "-", 1);
      PrependDecisionQueue("CHOOSEDISCARD", $player, "<-", 1);
      PrependDecisionQueue("FINDINDICES", $player, $indicesParam);
      return 1;
    case "GENESIS":
      if(TalentContains($lastResult, "LIGHT", $player)) Draw($player, false);
      if(ClassContains($lastResult, "ILLUSIONIST", $player)) PlayAura("MON104", $player);
      return 1;
    case "GIVEACTIONGOAGAIN":
      if($parameter == "A") SetClassState($player, $CS_NextNAACardGoAgain, 1);
      else if($parameter == "AA") GiveAttackGoAgain();
      return 1;
    case "PROCESSATTACKTARGET":
      $combatChainState[$CCS_AttackTarget] = $lastResult;
      $mzArr = explode("-", $lastResult);
      $zone = &GetMZZone($defPlayer, $mzArr[0]);
      $uid = "-";
      switch($mzArr[0])
      {
        case "MYALLY": case "THEIRALLY": $uid = $zone[$mzArr[1]+5]; break;
        case "MYAURAS": case "THEIRAURAS": $uid = $zone[$mzArr[1]+6]; break;
        default: break;
      }
      $combatChainState[$CCS_AttackTargetUID] = $uid;
      WriteLog(GetMZCardLink($defPlayer, $lastResult) . " was chosen as the attack target");
      return 1;
    case "STARTTURNABILITIES":
      StartTurnAbilities();
      return 1;
    case "DRAWTOINTELLECT":
      $deck = &GetDeck($player);
      $hand = &GetHand($player);
      $char = &GetPlayerCharacter($player);
      for ($i = 0; $i < CharacterIntellect($char[0]); ++$i) {
        array_push($hand, array_shift($deck));
      }
      return 1;
    case "ROLLDIE":
      $roll = RollDie($player, true, $parameter == "1");
      return $roll;
    case "SETCOMBATCHAINSTATE":
      $combatChainState[$parameter] = $lastResult;
      return $lastResult;
    case "BANISHADDMODIFIER":
      $banish = &GetBanish($player);
      $banish[$lastResult + 1] = $parameter;
      return $lastResult;
    case "SETLAYERTARGET":
      global $layers;
      $target = $lastResult;
      $targetArr = explode("-", $target);
      if($targetArr[0] == "LAYER") $target = "LAYERUID-" . $layers[intval($targetArr[1]) + 6];
      for($i=0; $i<count($layers); $i+=LayerPieces())
      {
        if($layers[$i] == $parameter)
        {
          $layers[$i+3] = $target;
        }
      }
      return $lastResult;
    case "SHOWSELECTEDTARGET":
      if(substr($lastResult, 0, 5) == "THEIR") {
        $otherP = ($player == 1 ? 2 : 1);
        WriteLog(GetMZCardLink($otherP, $lastResult) . " was targeted");
      } else {
        WriteLog(GetMZCardLink($player, $lastResult) . " was targeted");
      }
      return $lastResult;
    case "MULTIZONEFORMAT":
      return SearchMultizoneFormat($lastResult, $parameter);
    case "MULTIZONETOKENCOPY":
      $mzArr = explode("-", $lastResult);
      $source = $mzArr[0];
      $index = $mzArr[1];
      switch($source) {
        case "MYAURAS": TokenCopyAura($player, $index); break;
        default: break;
      }
      return $lastResult;
    case "COUNTITEM":
      return CountItem($parameter, $player);
    case "FINDANDDESTROYITEM":
      $mzArr = explode("-", $parameter);
      $cardID = $mzArr[0];
      $number = $mzArr[1];
      for($i = 0; $i < $number; ++$i) {
        $index = GetItemIndex($cardID, $player);
        if($index != -1) DestroyItemForPlayer($player, $index);
      }
      return $lastResult;
    case "COUNTPARAM":
      $array = explode(",", $parameter);
      return count($array) . "-" . $parameter;
    case "VALIDATEALLSAMENAME":
      if($parameter == "DECK") {
        $zone = &GetDeck($player);
      }
      if(count($lastResult) == 0) return "PASS";
      $name = CardName($zone[$lastResult[0]]);
      for($i = 1; $i < count($lastResult); ++$i) {
        if(CardName($zone[$lastResult[$i]]) != $name) {
          WriteLog("You selected cards that do not have the same name. Reverting gamestate prior to that effect.");
          RevertGamestate();
          return "PASS";
        }
      }
      return $lastResult;
    case "PREPENDLASTRESULT":
      return $parameter . $lastResult;
    case "APPENDLASTRESULT":
      return $lastResult . $parameter;
    case "LASTRESULTPIECE":
      $pieces = explode("-", $lastResult);
      return $pieces[$parameter];
    case "IMPLODELASTRESULT":
      if(!is_array($lastResult)) return $lastResult;
      return ($lastResult == "" ? "PASS" : implode($parameter, $lastResult));
    case "VALIDATECOUNT":
      if(count($lastResult) != $parameter) {
        WriteLog("The count from the last step is incorrect. Reverting gamestate prior to that effect.");
        RevertGamestate();
        return "PASS";
      }
      return $lastResult;
    case "SOULHARVEST":
      $numBD = 0;
      $discard = GetDiscard($player);
      for($i = 0; $i < count($lastResult); ++$i) {
        if(HasBloodDebt($discard[$lastResult[$i]])) ++$numBD;
      }
      if($numBD > 0) AddCurrentTurnEffect("MON198," . $numBD, $player);
      return $lastResult;
    case "ADDATTACKCOUNTERS":
      $lastResults = explode("-", $lastResult);
      $zone = $lastResults[0];
      $zoneDS = &GetMZZone($player, $zone);
      $index = $lastResults[1];
      if($zone == "MYCHAR" || $zone == "THEIRCHAR") $zoneDS[$index + 3] += $parameter;
      else if($zone == "MYAURAS" || $zone == "THEIRAURAS") $zoneDS[$index + 3] += $parameter;
      return $lastResult;
    case "FINALIZEDAMAGE":
      $params = explode(",", $parameter);
      $damage = $dqVars[0];
      $damageThreatened = $params[0];
      if($damage > $damageThreatened)//Means there was excess damage prevention prevention
      {
        $damage = $damageThreatened;
        $dqVars[0] = $damage;
        $dqState[6] = $damage;
      }
      return FinalizeDamage($player, $damage, $damageThreatened, $params[1], $params[2]);
    case "SETDQVAR":
      $dqVars[$parameter] = $lastResult;
      return $lastResult;
    case "INCDQVAR":
      $dqVars[$parameter] = intval($dqVars[$parameter]) + intval($lastResult);
      return $lastResult;
    case "DECDQVAR":
      $dqVars[$parameter] = intval($dqVars[$parameter]) - 1;
      return $lastResult;
    case "DIVIDE":
      return floor($lastResult / $parameter);
    case "DQVARPASSIFSET":
      if ($dqVars[$parameter] == "1") return "PASS";
      return "PROCEED";
    case "LORDSUTCLIFFE":
      if ($lastResult == "PASS") return $lastResult;
      LordSutcliffeAfterDQ($player, $parameter);
      return $lastResult;
    case "BINGO":
      if($lastResult == "") WriteLog("No card was revealed for " . CardLink("EVR156","EVR156") . ".");
      $cardType = CardType($lastResult);
      if($cardType == "AA") {
        WriteLog(CardLink("EVR156","EVR156") . " gained go again.");
        GiveAttackGoAgain();
      } else if($cardType == "A") {
        WriteLog(CardLink("EVR156","EVR156") . " draw a card.");
        Draw($player);
      } else WriteLog(CardLink("EVR156","EVR156") . "... did not hit the mark.");
      return $lastResult;
    case "ADDCARDTOCHAIN":
      AddCombatChain($lastResult, $player, $parameter, 0);
      return $lastResult;
    case "ATTACKWITHIT":
      PlayCardSkipCosts($lastResult, "DECK");
      return $lastResult;
    case "HEAVE":
      PrependDecisionQueue("PAYRESOURCES", $player, "<-");
      AddArsenal($lastResult, $player, "HAND", "UP");
      $heaveValue = HeaveValue($lastResult);
      for($i = 0; $i < $heaveValue; ++$i) {
        PlayAura("WTR075", $player);
      }
      WriteLog("You must pay " . HeaveValue($lastResult) . " resources to heave this");
      return HeaveValue($lastResult);
    case "BRAVOSTARSHOW":
      $hand = &GetHand($player);
      $cards = "";
      $hasLightning = false;
      $hasIce = false;
      $hasEarth = false;
      for($i = 0; $i < count($lastResult); ++$i) {
        if($cards != "") $cards .= ",";
        $card = $hand[$lastResult[$i]];
        if(TalentContains($card, "LIGHTNING")) $hasLightning = true;
        if(TalentContains($card, "ICE")) $hasIce = true;
        if(TalentContains($card, "EARTH")) $hasEarth = true;
        $cards .= $card;
      }
      if(RevealCards($cards, $player) && $hasLightning && $hasIce && $hasEarth) {
        WriteLog("Bravo, Star of the Show gives the next attack with cost 3 or more +2, Dominate, and go again");
        AddCurrentTurnEffect("EVR017", $player);
      }
      return $lastResult;
    case "SETDQCONTEXT":
      $dqState[4] = implode("_", explode(" ", $parameter));
      return $lastResult;
    case "AFTERDIEROLL":
      AfterDieRoll($player);
      return $lastResult;
    case "PICKACARD":
      $hand = &GetHand(($player == 1 ? 2 : 1));
      $rand = GetRandom(0, count($hand) - 1);
      if(RevealCards($hand[$rand], $player) && CardName($hand[$dqVars[0]]) == CardName($hand[$rand])) {
        WriteLog("Bingo! Your opponent tossed you a silver.");
        PutItemIntoPlayForPlayer("EVR195", $player);
      }
      return $lastResult;
    case "MODAL":
      return ModalAbilities($player, $parameter, $lastResult);
    case "SCOUR":
      WriteLog("Scour deals " . $parameter . " arcane damage");
      DealArcane($parameter, 0, "PLAYCARD", "EVR124", true, $player, resolvedTarget: ($player == 1 ? 2 : 1));
      return "";
    case "SETABILITYTYPE":
      $lastPlayed[2] = $lastResult;
      $index = GetAbilityIndex($parameter, GetClassState($player, $CS_CharacterIndex), $lastResult);
      SetClassState($player, $CS_AbilityIndex, $index);
      $names = explode(",", GetAbilityNames($parameter, GetClassState($player, $CS_CharacterIndex)));
      WriteLog(implode(" ", explode("_", $names[$index])) . " ability was chosen.");
      return $lastResult;
    case "MZSTARTTURNABILITY":
      MZStartTurnAbility($player, $lastResult);
      return "";
    case "MZDAMAGE":
      $lastResultArr = explode(",", $lastResult);
      $params = explode(",", $parameter);
      for($i = 0; $i < count($lastResultArr); ++$i) {
        $mzIndex = explode("-", $lastResultArr[$i]);
        $target = (substr($mzIndex[0], 0, 2) == "MY") ? $player : ($player == 1 ? 2 : 1);
        DamageTrigger($target, $params[0], $params[1], GetMZCard($target, $lastResultArr[$i]));
      }
      return $lastResult;
    case "MZDESTROY":
      return MZDestroy($player, $lastResult);
    case "MZUNDESTROY":
      return MZUndestroy($player, $parameter, $lastResult);
    case "MZBANISH":
      return MZBanish($player, $parameter, $lastResult);
    case "MZREMOVE":
       return MZRemove($player, $lastResult);
    case "MZDISCARD":
      return MZDiscard($player, $parameter, $lastResult);
    case "MZADDZONE":
      return MZAddZone($player, $parameter, $lastResult);
    case "GAINRESOURCES":
      GainResources($player, $parameter);
      return $lastResult;
    case "TRANSFORM":
      return "ALLY-" . ResolveTransform($player, $lastResult, $parameter);
    case "TRANSFORMPERMANENT":
      return "PERMANENT-" . ResolveTransformPermanent($player, $lastResult, $parameter);
    case "TRANSFORMAURA":
      return "AURA-" . ResolveTransformAura($player, $lastResult, $parameter);
    case "AFTERTHAW":
      $otherPlayer = ($player == 1 ? 2 : 1);
      $params = explode("-", $lastResult);
      if($params[0] == "MYAURAS") DestroyAura($player, $params[1]);
      else UnfreezeMZ($player, $params[0], $params[1]);
      return "";
    case "SUCCUMBTOWINTER":
      $params = explode("-", $lastResult);
      $otherPlayer = ($player == 1 ? 2 : 1);
      if($params[0] == "THEIRALLY") {
        $allies = &GetAllies($otherPlayer);
        WriteLog(CardLink($params[2], $params[2]) . " destroyed your frozen ally.");
        if($allies[$params[1] + 8] == "1") DestroyAlly($otherPlayer, $params[1]);
      } else {
        DestroyFrozenArsenal($otherPlayer);
        WriteLog(CardLink($params[2], $params[2]) . " destroyed your frozen arsenal card.");
        break;
      }
      return $lastResult;
    case "STARTGAME":
      $inGameStatus = "1";
      $MakeStartTurnBackup = true;
      $MakeStartGameBackup = true;
      return 0;
    case "QUICKREMATCH":
      $currentTime = round(microtime(true) * 1000);
      SetCachePiece($gameName, 2, $currentTime);
      SetCachePiece($gameName, 3, $currentTime);
      ClearGameFiles($gameName);
      include "MenuFiles/ParseGamefile.php";
      header("Location: " . $redirectPath . "/Start.php?gameName=$gameName&playerID=$playerID");
      exit;
    case "REMATCH":
      global $GameStatus_Rematch, $inGameStatus;
      if($lastResult == "YES")
      {
        $inGameStatus = $GameStatus_Rematch;
        ClearGameFiles($gameName);
      }
      return 0;
    case "PLAYERTARGETEDABILITY":
      PlayerTargetedAbility($player, $parameter, $lastResult);
      return "";
    case "DQPAYORDISCARD":
      PayOrDiscard($player, $parameter);
      return "";
    case "SPECIFICCARD":
      return SpecificCardLogic($player, $parameter, $lastResult);
    case "MZADDSTEAMCOUNTER":
      $lastResultArr = explode(",", $lastResult);
      $otherPlayer = ($player == 1 ? 2 : 1);
      $params = explode(",", $parameter);
      for($i = 0; $i < count($lastResultArr); ++$i) {
        $mzIndex = explode("-", $lastResultArr[$i]);
        switch($mzIndex[0]) {
          case "MYITEMS":
            $items = &GetItems($player);
            $items[$mzIndex[1] + 1 ] += 1;
            WriteLog(CardLink($items[$mzIndex[1]], $items[$mzIndex[1]]) . " gained a steam counter");
            break;
          default: break;
        }
      }
      return $lastResult;
    case "HITEFFECT":
      ProcessHitEffect($parameter);
      return $parameter;
    case "SURAYA":
      DealArcane(1, 2, "ABILITY", $parameter, true);
      return $lastResult;
    case "PROCESSDAMAGEPREVENTION":
      $mzIndex = explode("-", $lastResult);
      $params =  explode("-", $parameter);
      switch($mzIndex[0])
      {
        case "MYAURAS": $damage = AuraTakeDamageAbility($player, intval($mzIndex[1]), $params[0], $params[1]); break;
        case "MYCHAR": $damage = CharacterTakeDamageAbility($player, intval($mzIndex[1]), $params[0], $params[1]); break;
        case "MYALLY": $damage = AllyTakeDamageAbilities($player, intval($mzIndex[1]), $params[0], $params[1]); break;
        default: break;
      }
      if($damage < 0) $damage = 0;
      $dqVars[0] = $damage;
      $dqState[6] = $damage;
      if($damage > 0) AddDamagePreventionSelection($player, $damage, $params[1]);
      return $damage;
    case "CARDDISCARDED":
      CardDiscarded($player, $lastResult, $parameter);
      return $lastResult;
    case "AMULETOFECHOES":
      PlayerTargetedAbility($player, "AMULETOFECHOES", $lastResult);
      return "";
    case "EQUIPCARD":
      EquipCard($player, $parameter);
      return "";
    default:
      return "NOTSTATIC";
  }
}

function ImperialWarHorn($player, $term)
{
  AddDecisionQueue("MULTIZONEINDICES", $player, $term . "ALLY&" . $term . "AURAS&"  . $term . "ITEMS&LANDMARK");
  AddDecisionQueue("SETDQCONTEXT", $player, "Choose a card to destroy", 1);
  AddDecisionQueue("CHOOSEMULTIZONE", $player, "<-", 1);
  AddDecisionQueue("MZDESTROY", $player, "-", 1);
}

function CharacterTriggerInGraveyard($cardID)
{
  switch ($cardID) {
    case "DYN117": case "DYN118": return true;
    case "OUT011": return true;
    default: return false;
  }
}
