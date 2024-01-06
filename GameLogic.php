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
include "Classes/Banish.php";
include "Classes/Card.php";
include "Classes/CombatChain.php";
include "Classes/Deck.php";
include "Classes/Discard.php";
include "DecisionQueue/DecisionQueueEffects.php";
include "CurrentEffectAbilities.php";
include "CombatChain.php";

function DecisionQueueStaticEffect($phase, $player, $parameter, $lastResult)
{
  global $redirectPath, $playerID, $gameName;
  global $currentPlayer, $combatChain, $CombatChain, $defPlayer;
  global $combatChainState, $EffectContext;
  global $defCharacter, $CS_NumCharged, $otherPlayer;
  global $CS_NumFusedEarth, $CS_NumFusedIce, $CS_NumFusedLightning, $CS_NextNAACardGoAgain, $CCS_AttackTarget;
  global $CS_LayerTarget, $dqVars, $mainPlayer, $lastPlayed, $dqState, $CS_AbilityIndex, $CS_CharacterIndex;
  global $CS_AdditionalCosts, $CS_AlluvionUsed, $CS_MaxQuellUsed, $CS_DamageDealt, $CS_ArcaneTargetsSelected, $inGameStatus;
  global $CS_ArcaneDamageDealt, $MakeStartTurnBackup, $CCS_AttackTargetUID, $chainLinkSummary, $chainLinks, $MakeStartGameBackup;
  $rv = "";
  switch($phase) {
    case "FINDINDICES":
      UpdateGameState($currentPlayer);
      BuildMainPlayerGamestate();
      $parameters = explode(",", $parameter);
      $parameter = $parameters[0];
      if(count($parameters) > 1) $subparam = $parameters[1];
      else $subparam = "";
      switch($parameter) {
        case "GETINDICES": $rv = GetIndices($subparam); break;
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
        case "ARC121": $rv = SearchDeck($player, "", "", $lastResult, -1, "WIZARD"); break;
        case "CRU143": $rv = SearchDiscard($player, "AA", "", -1, -1, "RUNEBLADE"); break;
        case "DECK": $rv = SearchDeck($player); break;
        case "TOPDECK":
          $deck = new Deck($player);
          if(!$deck->Empty()) $rv = "0";
          break;
        case "DECKTOPXINDICES":
        $deck = new Deck($player);
          $amount = ($subparam > $deck->RemainingCards() ? $deck->RemainingCards() : $subparam);
          $rv = GetIndices($amount);
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
        case "HANDMINPOWER": $rv = SearchHand($player, minAttack:$subparam); break;
        case "HANDACTIONMAXCOST": $rv = CombineSearches(SearchHand($player, "A", "", $subparam), SearchHand($player, "AA", "", $subparam)); break;
        case "MULTIHAND":
          $hand = &GetHand($player);
          if(count($hand) == 0) $rv = "";
          else $rv = count($hand) . "-" . GetIndices(count($hand));
          break;
        case "MULTIBANISH":
          $banish = new Banish($player);
          $rv = $subparam . "-" . GetIndices($banish->NumCards()*BanishPieces(), 0, BanishPieces());
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
        case "ITEMSMAX": $rv = SearchItems($player, "", "", $subparam); break;
        case "EQUIP": $rv = GetEquipmentIndices($player); break;
        case "EQUIP0": $rv = GetEquipmentIndices($player, 0); break;
        case "EQUIPCARD": $rv = FindCharacterIndex($player, $subparam); break;
        case "EQUIPONCC": $rv = GetEquipmentIndices($player, onCombatChain:true); break;
        case "CCAA": $rv = SearchCombatChainLink($player, "AA"); break;
        case "CCDEFLESSX":
          $rv = SearchCombatChainLink($player, "", "", -1, -1, "", "", false, false, -1, false, -1, $subparam);
          if($rv[0] == "0" && (strlen($rv) == 0 || $rv[1] == ",")) $rv = substr($rv, 2);
          break;
        case "MYHANDARROW": $rv = SearchHand($player, "", "Arrow"); break;
        case "MYDISCARDARROW": $rv = SearchDiscard($player, "", "Arrow"); break;
        case "GY":
          $discard = &GetDiscard($player);
          $rv = GetIndices(count($discard));
          break;
        case "WEAPON": $rv = WeaponIndices($player, $player, $subparam); break;
        case "MON020": case "MON021": case "MON022": $rv = SearchDiscard($player, "", "", -1, -1, "", "", false, true); break;
        case "MON033-1":
          $soul = &GetSoul($player);
          $rv = GetIndices(count($soul), 1);
          break;
        case "MON033-2": $rv = CombineSearches(SearchDeck($player, "A", "", $lastResult), SearchDeck($player, "AA", "", $lastResult)); break;
        case "MON158": $rv = InvertExistenceIndices($player); break;//This makes sense because it's a multi
        case "ELE113": $rv = PulseOfCandleholdIndices($player); break;//This makes sense because it's a multi
        case "ELE125": case "ELE126": case "ELE127": $rv = MZToIndices(SearchMultizone($player, "COMBATCHAINLINK:type=A;talent=EARTH,ELEMENTAL&COMBATCHAINLINK:type=AA;talent=EARTH,ELEMENTAL")); break;
        case "EVR178": $rv = SearchDeckForCard($player, "MON281", "MON282", "MON283"); break;
        case "HEAVE": $rv = HeaveIndices(); break;
        case "BRAVOSTARSHOW": $rv = BravoStarOfTheShowIndices(); break;
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
        case "MON104": $rv = SearchAurasForCard("MON104",$player); break;
        case "HVY016":
          $hand = &GetHand($player);
          $rv = [];
          for($i=0; $i<count($hand); $i+=HandPieces()) {
            if(CardType($hand[$i]) == "AA" && ModifiedAttackValue($hand[$i], $player, "HAND", "HVY016") >= 6) array_push($rv, $i);
          }
          $rv = implode(",", $rv);
          $rv = SearchCount($rv) . "-" . $rv;
          break;
        default: $rv = ""; break;
      }
      return ($rv == "" ? "PASS" : $rv);
    case "MULTIZONEINDICES":
      $rv = SearchMultizone($player, $parameter);
      return ($rv == "" ? "PASS" : $rv);
    case "PUTPLAY":
      $subtype = CardSubType($lastResult);
      if($subtype == "Item") {
        PutItemIntoPlayForPlayer($lastResult, $player, ($parameter != "-" ? $parameter : 0));
      } else if(DelimStringContains($subtype, "Aura")) {
        PlayAura($lastResult, $player);
        PlayAbility($lastResult, "-", 0);
      }
      return $lastResult;
    case "PLAYABILITY":
      PlayAbility($lastResult, "-", 0);
      return $lastResult;
    case "DRAW":
      return Draw($player);
    case "MULTIBANISH":
      if($lastResult == "") return $lastResult;
      $cards = explode(",", $lastResult);
      $params = explode(",", $parameter);
      if(count($params) < 3) array_push($params, "");
      $mzIndices = "";
      for($i = 0; $i < count($cards); ++$i) {
        $index = BanishCardForPlayer($cards[$i], $player, $params[0], $params[1], $params[2]);
        if($mzIndices != "") $mzIndices .= ",";
        $mzIndices .= "BANISH-" . $index;
      }
      $dqState[5] = $mzIndices;
      return $lastResult;
    case "REMOVECOMBATCHAIN":
      return $CombatChain->Remove($lastResult);
    case "COMBATCHAINPOWERMODIFIER":
      CombatChainPowerModifier($lastResult, $parameter);
      return $lastResult;
    case "COMBATCHAINDEFENSEMODIFIER":
      if($parameter < 0) {
        $defense = BlockingCardDefense($lastResult);
        if($parameter < $defense * -1) $parameter = $defense * -1;
      }
      $combatChain[$lastResult+6] += $parameter;
      return $lastResult;
    case "HALVEBASEDEFENSE":
      $combatChain[$lastResult+6] -= floor(BlockValue($combatChain[$lastResult])/2);
      return $lastResult;
    case "COMBATCHAINCHARACTERDEFENSEMODIFIER":
      $character = &GetPlayerCharacter($player);
      $index = FindCharacterIndex($player, $combatChain[$parameter]);
      $character[$index+4] += $lastResult;
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
      if(!is_array($lastResult)) $lastResult = explode(",", $lastResult);
      $cardsRemoved = "";
      for($i = 0; $i < count($lastResult); ++$i) {
        if($cards != "") $cards .= ",";
        $cards .= $discard[$lastResult[$i]];
        if($parameter == "1") WriteLog(CardLink($discard[$lastResult[$i]], $discard[$lastResult[$i]]));
        unset($discard[$lastResult[$i]]);
      }
      $discard = array_values($discard);
      return $cards;
    case "MULTIBANISHSOUL":
      if(!is_array($lastResult)) $lastResult = explode(",", $lastResult);
      for($i = count($lastResult)-1; $i >= 0; --$i) BanishFromSoul($player, $lastResult[$i]);
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
    case "ADDARSENAL":
      $params = explode("-", $parameter);
      $from = (count($params) > 0 ? $params[0] : "-");
      $facing = (count($params) > 1 ? $params[1] : "DOWN");
      AddArsenal($lastResult, $player, $from, $facing);
      return $lastResult;
    case "TURNARSENALFACEUP":
      $arsenal = &GetArsenal($player);
      $arsenal[$lastResult+1] = "UP";
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
    case "DESTROYCHARACTER":
      DestroyCharacter($player, $lastResult);
      return $lastResult;
    case "DESTROYEQUIPDEF0":
      $character = &GetPlayerCharacter($defPlayer);
      if(BlockValue($character[$lastResult]) + $character[$lastResult+4] <= 0 && BlockValue($character[$lastResult]) != -1) {
        WriteLog(CardLink($character[$lastResult], $character[$lastResult]) . " was destroyed");
        DestroyCharacter($defPlayer, $lastResult);
      }
      return "";
    case "CHARFLAGDESTROY":
      $character = &GetPlayerCharacter($player);
      $character[$parameter+7] = 1;
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
      $paramArr = explode(",", $parameter);
      $parameter = $paramArr[0];
      switch($parameter)
      {
        case "FREEZE": MZFreeze($lastResult); break;
        case "GAINCONTROL": MZGainControl($player, $lastResult); break;
        case "GETCARDID": return GetMZCard($player, $lastResult);
        case "GETCARDINDEX": $mzArr = explode("-", $lastResult); return $mzArr[1];
        case "GETCARDINDICES":
          $arr = explode(",", $lastResult);
          $output = [];
          for($i=0; $i<count($arr); ++$i) {
            $mzArr = explode("-", $arr[$i]);
            array_push($output, $mzArr[1]);
          }
          return implode(",", $output);
        case "GETUNIQUEID":
          $mzArr = explode("-", $lastResult);
          $zone = &GetMZZone($player, $mzArr[0]);
          switch($mzArr[0]) {
            case "ALLY": case "MYALLY": case "THEIRALLY": return $zone[$mzArr[1]+5];
            case "BANISH": case "MYBANISH": case "THEIRBANISH": return $zone[$mzArr[1]+2];
            default: return "-1";
          }
        case "LASTMZINDEX": return MZLastIndex($player, $lastResult);
        case "SETPIECE":
          $pieceArr = explode("=", $paramArr[1]);
          $mzArr = explode("-", $lastResult);
          $zone = &GetMZZone($player, $mzArr[0]);
          $zone[$mzArr[1]+$pieceArr[0]] = $pieceArr[1];
          break;
        case "TURNBANISHFACEDOWN":
          $mzArr = explode("-", $lastResult);
          TurnBanishFaceDown(substr($mzArr[0], 0, 2) == "MY" ? $player : ($player == 1 ? 2 : 1), $mzArr[1]);
          break;
        case "ADDITIONALUSE":
          $mzArr = explode("-", $lastResult);
          $character = &GetPlayerCharacter($player);
          ++$character[$mzArr[1] + 5];
          if($character[$mzArr[1] + 1] == 1) $character[$mzArr[1] + 1] = 2;
          break;
        case "ADDSUBCARD":
          $mzArr = explode("-", $lastResult);
          $character = &GetPlayerCharacter($player);
          if($character[$mzArr[1]+10] != "-") {
            $character[$mzArr[1]+10] .= "," . $paramArr[1];
          }
          else $character[$mzArr[1]+10] = $paramArr[1];
          ++$character[$mzArr[1]+2]; // Update the counter
          break;
        default: break;
      }
      return $lastResult;
    case "OP":
      switch($parameter)
      {
        case "DESTROYFROZENARSENAL": DestroyFrozenArsenal($player); return "";
        case "GIVEATTACKGOAGAIN": GiveAttackGoAgain(); return $lastResult;
        case "BOOST":
          if (is_numeric($lastResult)) return DoBoost($player, intval($lastResult));
          return DoBoost($player);
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
        case "LOSEHEALTH": LoseHealth($lastResult, $player); return $lastResult;
        case "BANISHHAND": BanishHand($player); return $lastResult;
        case "DOCRANK": DoCrank($player, $lastResult); return $lastResult;
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
      $deck = new Deck($player);
      $deck->AddBottom($lastResult);
      return $lastResult;
    case "MULTIADDDECK":
      $deck = new Deck($player);
      $cards = explode(",", $lastResult);
      for($i = 0; $i < count($cards); ++$i) $deck->AddBottom($cards[$i]);
      return $lastResult;
    case "MULTIADDTOPDECK":
      $deck = new Deck($player);
      $cards = explode(",", $lastResult);
      for($i = 0; $i < count($cards); ++$i) {
        if($parameter == "1") WriteLog(CardLink($cards[$i], $cards[$i]));
        $deck->AddTop($cards[$i]);
      }
      return $lastResult;
    case "MULTIREMOVEDECK":
      if(is_array($lastResult)) $lastResult = ($lastResult == [] ? "" : implode(",", $lastResult));
      $deck = new Deck($player);
      return $deck->Remove($lastResult);
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
      $character[$parameter+1] = 1;
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
    case "DESTROYTOPCARD":
      $deck = new Deck($player);
      AddGraveyard($deck->Top(remove:true), $player, "TOP");
      return $lastResult;
    case "SHOWMODES":
      if(is_array($lastResult)) $modes = $lastResult;
      else {
        $modes = [];
        array_push($modes, $lastResult);
      }
      $text = "";
      for($i = 0; $i < count($modes); ++$i) {
        if($text != "") $text .= ", ";
        if($i > 0 && $i == count($modes)-1) $text .= " and ";
        $text .= implode(" ", explode("_", $modes[$i]));
      }
      if($text == "") $text = "None";
      WriteLog("Selected mode" . (count($modes) > 1 ? "s" : "") . " for " . CardLink($parameter, $parameter) . (count($modes) > 1 ? " are" : " is") . ": " . $text);
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
    case "ADDCURRENTEFFECT":
      $params = explode("!", $parameter);
      AddCurrentTurnEffect($params[0], $player, (count($params) > 1 ? $params[1] : ""));
      return "1";
    case "ADDCURRENTEFFECTLASTRESULT":
      $params = explode("!", $parameter);
      AddCurrentTurnEffect($params[0].$lastResult, $player, (count($params) > 1 ? $params[1] : ""));
      return $lastResult;
    case "ADDSTASISTURNEFFECT":
      $character = &GetPlayerCharacter($player);
      $effect = $parameter . $character[$lastResult];
      AddCurrentTurnEffect($effect, $player);
      AddNextTurnEffect($effect, $player);
      if($player == $mainPlayer) AddNextTurnEffect($effect, $player, numTurns:2); //If played at instant speed from Dash
      return $lastResult;
    case "EQUIPCANTDEFEND":
      $character = &GetPlayerCharacter($player);
      $effect = $parameter . $character[$lastResult];
      AddCurrentTurnEffect($effect, $player);
      return $lastResult;
    case "ADDCURRENTANDNEXTTURNEFFECT":
      AddCurrentTurnEffect($parameter, $player);
      AddNextTurnEffect($parameter, $player);
      return "1";
    case "ADDTHEIRNEXTTURNEFFECT":
      $numTurns = $player == $mainPlayer ? 2 : 1;
      AddNextTurnEffect($parameter, $player, numTurns:$numTurns);
      return "1";
    case "ADDLIMITEDCURRENTEFFECT":
      $params = explode(",", $parameter);
      AddCurrentTurnEffect($params[0], $player, $params[1], $lastResult);
      return $lastResult;
    case "ADDAIMCOUNTER":
      $arsenal = &GetArsenal($player);
      $arsenal[$lastResult+3] += 1;
      return $lastResult;
    case "OPTX":
      Opt("NA", $parameter);
      return $lastResult;
    case "SETCLASSSTATE":
      $data = is_array($lastResult) ? implode(",", $lastResult) : $lastResult;
      SetClassState($player, $parameter, $data);
      return $lastResult;
    case "GETCLASSSTATE":
      return GetClassState($player, $parameter);
    case "GAINACTIONPOINTS":
      GainActionPoints($parameter, $player);
      return $lastResult;
    case "EQUALPASS":
      if($lastResult == $parameter) return "PASS";
      return 1;
    case "NOTEQUALPASS":
      if($lastResult != $parameter) return "PASS";
      return 1;
    case "NOPASS":
      if($lastResult == "NO") return "PASS";
      return 1;
    case "NULLPASS":
      if($lastResult == "") return "PASS";
      return $lastResult;
    case "ELSE":
      if($lastResult == "PASS") return "0";
      else if($lastResult == "NO") return "NO";
      else return "PASS";
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
    case "ALLCARDPITCHORPASS":
      $cards = explode(",", $lastResult);
      for($i = 0; $i < count($cards); ++$i) {
        if(PitchValue($cards[$i]) != $parameter) return "PASS";
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
    case "VOFTHEVANGUARD":
      if($parameter == "1" && TalentContains($lastResult, "LIGHT", $player)) {
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
      return $parameter;
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
      case "REELIN":
        $cards = explode(",", $lastResult);
        $TrapIndices = "";
        $numMatch = 0;
        for($i = 0; $i < count($cards); ++$i) {
          if(DelimStringContains(CardSubType($cards[$i]), "Trap")) {
            if($TrapIndices != "") $TrapIndices .= ",";
            $TrapIndices .= $i;
            ++$numMatch;
          }
        }
        if($numMatch == 0) return "PASS";
        return "4" . "-" . $TrapIndices . "-" . "0";
    case "SONATAARCANIXSTEP2":
      $numArcane = count(explode(",", $lastResult));
      DealArcane($numArcane, 0, "PLAYCARD", "MON231", true);
      return 1;
    case "CHARGE":
      DQCharge();
      return "1";
    case "FINISHCHARGE":
      //Abilities when you charge it
      global $Card_CourageBanner, $Card_QuickenBanner, $Card_SpellbaneBanner, $Card_LifeBanner, $Card_BlockBanner, $Card_ResourceBanner;
      switch($lastResult)
      {
        case $Card_CourageBanner: PlayAura("DTD232", $player); break;
        case $Card_QuickenBanner: PlayAura("WTR225", $player); break;
        case $Card_SpellbaneBanner: PlayAura("DYN246", $player); break;
        case $Card_LifeBanner: AddCurrentTurnEffect($Card_LifeBanner, $player); break;
        case $Card_BlockBanner: AddCurrentTurnEffect($Card_BlockBanner, $player); break;
        case $Card_ResourceBanner: AddCurrentTurnEffect($Card_ResourceBanner, $player); break;
        default: break;
      }
      WriteLog("This card was charged: " . CardLink($lastResult, $lastResult));
      IncrementClassState($player, $CS_NumCharged);
      global $CCS_AttackNumCharged;
      if(CardType($EffectContext) == "AA") ++$combatChainState[$CCS_AttackNumCharged];
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
        if($allies[$target[1]+6] > 0) {
          $damage -= 3;
          if($damage < 0) $damage = 0;
          --$allies[$target[1]+6];
        }
        $allies[$target[1]+2] -= $damage;
        if($damage > 0) AllyDamageTakenAbilities($targetPlayer, $target[1]);
        if($allies[$target[1]+2] <= 0) DestroyAlly($targetPlayer, $target[1]);
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
      if(!CanDamageBePrevented($player, $damage, "DAMAGE", $source)) $lastResult = 0;
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
    case "THREATENARCANE":
      DealArcane(1, 2, "ABILITY", $parameter, true);
      return $lastResult;
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
        if($allies[$target[1]+6] > 0) {
          $damage -= 3;
          if($damage < 0) $damage = 0;
          --$allies[$target[1]+6];
        }
        $allies[$target[1]+2] -= $damage;
        $dqVars[0] = $damage;
        if($damage > 0) AllyDamageTakenAbilities($targetPlayer, $target[1]);
        if($allies[$target[1]+2] <= 0) {
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
          if($char[$index+2] < 4 && GetClassState($player, $CS_AlluvionUsed) == 0) {
            ++$char[$index+2];
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
      if(!CanDamageBePrevented($player, $damage, "ARCANE", $source)) $lastResult = 0;
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
        PitchCard($player, skipGain:true);
      }
      return $parameter;
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
      for($i = 0; $i < count($elementArray); ++$i) {
        $element = $elementArray[$i];
        switch($element) {
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
    case "GAINPITCHVALUE":
      $resources = &GetResources($player);
      $resources[0] += PitchValue($lastResult);
      return $lastResult;
    case "BUFFARCANE":
      AddCurrentTurnEffect($parameter . "-" . $lastResult, $player);
      return $lastResult;
    case "BUFFARCANEPREVLAYER":
      global $layers;
      $index = 0;
      for($index=0; $index<count($layers) && $layers[$index] == "TRIGGER"; $index+=LayerPieces());
      AddCurrentTurnEffect("CRU161", $player, "PLAY", $layers[$index+6]);
      return $lastResult;
    case "LASTARSENALADDEFFECT":
      $params = explode(",", $parameter);
      $arsenal = &GetArsenal($player);
      if(count($arsenal) > 0 && count($params) == 2) AddCurrentTurnEffect($params[0], $player, $params[1], $arsenal[count($arsenal) - ArsenalPieces() + 5]);
      return $lastResult;
    case "INVERTEXISTENCE":
      if($lastResult == "") {
        WriteLog("No cards were selected, so Invert Existence did not banish any cards");
        return $lastResult;
      }
      $cards = explode(",", $lastResult);
      $numAA = 0;
      $numNAA = 0;
      $message = "Invert existence banished ";
      for($i = 0; $i < count($cards); ++$i) {
        $type = CardType($cards[$i]);
        if($type == "AA") ++$numAA;
        else if($type == "A") ++$numNAA;
        if($i >= 1) $message .= ", ";
        if($i != 0 && $i == count($cards) - 1) $message .= "and ";
        $message .= CardLink($cards[$i], $cards[$i]);
      }
      WriteLog($message);
      if($numAA == 1 && $numNAA == 1) DealArcane(2, 0, "PLAYCARD", "MON158", true, $player);
      return $lastResult;
    case "ROUSETHEANCIENTS":
      $cards = (is_array($lastResult) ? $lastResult : explode(",", $lastResult));
      $totalAV = 0;
      for($i = 0; $i < count($cards); ++$i) {
        $totalAV += intval(ModifiedAttackValue($cards[$i], $player, "HAND", source:"MON247"));
      }
      if($totalAV >= 13) {
        AddCurrentTurnEffect("MON247", $player);
        WriteLog(CardLink("MON247", "MON247") . " got +7 and go again");
      }
      return $lastResult;
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
      global $CS_NumCardsDrawn;
      $char = &GetPlayerCharacter($player);
      for($i = 0; $i < CharacterIntellect($char[0]); ++$i) Draw($player, mainPhase:false, fromCardEffect:false);
      SetClassState($player, $CS_NumCardsDrawn, 0);//Don't make initial draw count for Hold the Line
      return 1;
    case "ROLLDIE":
      $roll = RollDie($player, true, $parameter == "1");
      return $roll;
    case "SETCOMBATCHAINSTATE":
      $combatChainState[$parameter] = $lastResult;
      return $lastResult;
    case "INCREMENTCOMBATCHAINSTATE":
      $combatChainState[$parameter] = $combatChainState[$parameter] + 1;
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
      $targetPlayer = (substr($lastResult, 0, 5) == "THEIR" ? ($player == 1 ? 2 : 1) : $player);
      WriteLog(GetMZCardLink($targetPlayer, $lastResult) . " was targeted");
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
      $lastResultArr = explode("-", $lastResult);
      $zone = $lastResultArr[0];
      $zoneDS = &GetMZZone($player, $zone);
      $index = $lastResultArr[1];
      if($zone == "MYCHAR" || $zone == "THEIRCHAR") $zoneDS[$index+3] += $parameter;
      else if($zone == "MYAURAS" || $zone == "THEIRAURAS") $zoneDS[$index+3] += $parameter;
      else if($zone == "MYALLY" || $zone == "THEIRALLY") $zoneDS[$index+9] += $parameter;
      return $lastResult;
      case "ADDALLATTACKCOUNTERS":
      $lastResult = str_replace(",", "-", $lastResult);
      $lastResultArr = explode("-", $lastResult);
      $zone = $lastResultArr[0];
      $zoneDS = &GetMZZone($player, $zone);
      for ($i=1; $i < count($lastResultArr); $i += 2) {
        if($zone == "MYALLY" || $zone == "THEIRALLY") $zoneDS[$lastResultArr[$i]+9] += $parameter;
      }
      return $lastResult;
    case "MODDEFCOUNTER":
      if($lastResult == "") return $lastResult;
      $character = &GetPlayerCharacter($player);
      $character[$lastResult+4] = intval($character[$lastResult+4]) + $parameter;
      if($parameter < 0) WriteLog(CardLink($character[$lastResult], $character[$lastResult]) . " got a negative defense counter");
      return $lastResult;
    case "REMOVECOUNTER":
      $character = &GetPlayerCharacter($player);
      $character[$lastResult+2] -= 1;
      WriteLog(CardLink($parameter, $parameter) . " removed a counter from " . CardLink($character[$lastResult], $character[$lastResult]));
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
    case "INCDQVARIFNOTPASS":
      if ($lastResult != "PASS") $dqVars[$parameter] = intval($dqVars[$parameter]) + 1;
      return $lastResult;
    case "DIVIDE":
      return floor($lastResult / $parameter);
    case "DQVARPASSIFSET":
      if($dqVars[$parameter] == "1") return "PASS";
      return "PROCEED";
    case "LORDSUTCLIFFE":
      if($lastResult == "PASS") return $lastResult;
      LordSutcliffeAfterDQ($player, $parameter);
      return $lastResult;
    case "BINGO":
      if($lastResult == "") WriteLog("No card was revealed for " . CardLink("EVR156","EVR156"));
      $cardType = CardType($lastResult);
      if($cardType == "AA") {
        WriteLog(CardLink("EVR156","EVR156") . " gained go again");
        GiveAttackGoAgain();
      } else if($cardType == "A") {
        WriteLog(CardLink("EVR156","EVR156") . " draw a card");
        Draw($player);
      } else WriteLog(CardLink("EVR156","EVR156") . "... did not hit the mark");
      return $lastResult;
    case "ADDCARDTOCHAINASDEFENDINGCARD":
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
    case "SETABILITYTYPEATTACK":
      $index = GetAbilityIndex($parameter, GetClassState($player, $CS_CharacterIndex), "Attack");
      SetClassState($player, $CS_AbilityIndex, $index);
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
    case "MZDESTROY": return MZDestroy($player, $lastResult);
    case "MZUNDESTROY": return MZUndestroy($player, $parameter, $lastResult);
    case "MZBANISH": return MZBanish($player, $parameter, $lastResult);
    case "MZREMOVE": return MZRemove($player, $lastResult);
    case "MZDISCARD": return MZDiscard($player, $parameter, $lastResult);
    case "MZADDZONE": return MZAddZone($player, $parameter, $lastResult);
    case "MZSWITCHPLAYER": return MZSwitchPlayer($lastResult);
    case "GAINRESOURCES":
      GainResources($player, $parameter);
      return $lastResult;
    case "TRANSFORM":
      return "ALLY-" . ResolveTransform($player, $lastResult, $parameter);
    case "TRANSFORMPERMANENT":
      return "PERMANENT-" . ResolveTransformPermanent($player, $lastResult, $parameter);
    case "TRANSFORMAURA":
      return "AURA-" . ResolveTransformAura($player, $lastResult, $parameter);
    case "TRANSFORMHERO":
      return ResolveTransformHero($player, $parameter, $lastResult);
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
        WriteLog(CardLink($params[2], $params[2]) . " destroyed your frozen ally");
        if($allies[$params[1]+8] == "1") DestroyAlly($otherPlayer, $params[1]);
      } else {
        DestroyFrozenArsenal($otherPlayer);
        WriteLog(CardLink($params[2], $params[2]) . " destroyed your frozen arsenal card");
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
      $parameterArr = explode(",", $parameter);
      $parameter = $parameterArr[0];
      if (count($parameterArr) > 0) $initiator = $parameterArr[1];
      else $initiator = "";
      return SpecificCardLogic($player, $parameter, $lastResult, $initiator);
    case "MZADDSTEAMCOUNTER":
      $lastResultArr = explode(",", $lastResult);
      $otherPlayer = ($player == 1 ? 2 : 1);
      $params = explode(",", $parameter);
      for($i = 0; $i < count($lastResultArr); ++$i) {
        $mzIndex = explode("-", $lastResultArr[$i]);
        switch($mzIndex[0]) {
          case "MYITEMS":
            $items = &GetItems($player);
            $items[$mzIndex[1]+1] += 1;
            WriteLog(CardLink($items[$mzIndex[1]], $items[$mzIndex[1]]) . " gained a steam counter");
            break;
          default: break;
        }
      }
      return $lastResult;
    case "MZREMOVESTEAMCOUNTER":
      $lastResultArr = explode(",", $lastResult);
      $otherPlayer = ($player == 1 ? 2 : 1);
      $params = explode(",", $parameter);
      $removedSteamCounterCount = 0;
      for($i = 0; $i < count($lastResultArr); ++$i) {
        $mzIndex = explode("-", $lastResultArr[$i]);
        switch($mzIndex[0]) {
          case "THEIRITEMS": case "MYITEMS":
            $controller = str_starts_with($mzIndex[0], "MY") ? $player : $otherPlayer;
            $items = &GetItems($controller);
            $removedSteamCounterCount = $items[$mzIndex[1]+1];
            $items[$mzIndex[1]+1] = 0;
            WriteLog(CardLink($items[$mzIndex[1]], $items[$mzIndex[1]]) . " lost all their steam counters");
            if (DestroyItemWithoutSteamCounter($items[$mzIndex[1]], $controller)) {
              DestroyItemForPlayer($controller, $mzIndex[1]);
            }
            break;
          case "THEIRCHAR": case "MYCHAR":
            $controller = str_starts_with($mzIndex[0], "MY") ? $player : $otherPlayer;
            $characters = &GetPlayerCharacter($controller);
            $removedSteamCounterCount = $characters[$mzIndex[1]+2];
            $characters[$mzIndex[1]+2] = 0;
            WriteLog(CardLink($characters[$mzIndex[1]], $characters[$mzIndex[1]]) . " lost all their steam counters");
            break;
          default: break;
        }
      }
      return $controller . "-" . $removedSteamCounterCount;
    case "SYSTEMFAILURE":
      $lastResultArr = explode("-", $lastResult);
      if (count($lastResultArr) < 2) return "";
      $removedSteamCounterCount = $lastResultArr[1];
      if ($removedSteamCounterCount >= 2) {
        return DealDamageAsync($lastResultArr[0], 2);
      }
      return "";
    case "HITEFFECT":
      ProcessHitEffect($parameter);
      return $parameter;
    case "AWAKEN":
      $mzArr = explode("-", $lastResult);
      $permanents = &GetPermanents($player);
      $cardID = $permanents[$mzArr[1]];
      $num = intval(substr($cardID, 3));
      $num += 400;
      $newCardID = substr($cardID, 0, 3) . $num;
      WriteLog(CardLink($cardID, $cardID) . " awakened into " . CardLink($newCardID, $newCardID));
      RemovePermanent($player, $mzArr[1]);
      PlayAlly($newCardID, $player);
      return "1";
    case "PROCESSDAMAGEPREVENTION":
      $mzIndex = explode("-", $lastResult);
      $params =  explode("-", $parameter);
      switch($mzIndex[0])
      {
        case "MYAURAS": $damage = AuraTakeDamageAbility($player, intval($mzIndex[1]), $params[0], $params[1]); break;
        case "MYCHAR": $damage = CharacterTakeDamageAbility($player, intval($mzIndex[1]), $params[0], $params[1]); break;
        case "MYALLY": $damage = AllyTakeDamageAbilities($player, intval($mzIndex[1]), $params[0], $params[1]); break;
        case "MYITEMS": $damage = ChosenItemTakeDamageAbilities($player, intval($mzIndex[1]), $params[0], $params[1]); break;
        default: break;
      }
      if($damage < 0) $damage = 0;
      $dqVars[0] = $damage;
      $dqState[6] = $damage;
      if($damage > 0) AddDamagePreventionSelection($player, $damage, $params[1]);
      return $damage;
    case "EQUIPCARD":
      if (str_contains($parameter, "-")) {
        $from = explode('-', $parameter)[1];
        $parameter = explode('-', $parameter)[0];
        if ($from == "INVENTORY") {
          $inventory = &GetInventory($player);
          $indexToRemove = array_search($parameter, $inventory);
          if ($indexToRemove !== false) unset($inventory[$indexToRemove]);
        }
      }
      if(CardType($parameter) == "W") EquipWeapon($player, $parameter);
      else EquipEquipment($player, $parameter);
      return "";
    case "ROGUEMIRRORGAMESTART":
      $deck = &GetDeck($player);
      for($mirrorAmount = 0; $mirrorAmount < 7; ++$mirrorAmount) { array_unshift($deck, $lastResult); }
      return $lastResult;
    case "ROGUEMIRRORTURNSTART":
      $deck = &GetDeck($player);
      $hand = &GetHand($player);
      if(count($deck) > 3)
      {
        $optionOne = rand(0, count($deck)-1);
        $optionTwo = rand(0, count($deck)-1);
        $optionThree = rand(0, count($deck)-1);
        if($optionOne == $optionTwo)
        {
          if($optionOne == 0) ++$optionTwo;
          else --$optionTwo;
        }
        for($i = 0; $i < 5 && ($optionThree == $optionOne || $optionThree == $optionOne); ++$i)
        {
          if($optionThree <= 4) $optionThree += 3;
          else --$optionThree;
        }
        $deck[$optionOne] = $hand[$lastResult];
        $deck[$optionTwo] = $hand[$lastResult];
        $deck[$optionThree] = $hand[$lastResult];
      }
      else
      {
        for($deckCount = 0; $deckCount < count($deck); ++$deckCount) { $deck[$deckCount] = $hand[$lastResult]; }
      }
      return $lastResult;
    case "ROGUEDECKCARDSTURNSTART":
      $deck = &GetDeck($player);
      $hand = &GetHand($player);
      array_unshift($deck, $hand[$lastResult]);
      return $lastResult;
    case "GETTARGETOFATTACK":
      $params = explode(",", $parameter);
      if(CardType($params[0]) == "AA" || GetResolvedAbilityType($params[0], $params[1]) == "AA") GetTargetOfAttack($params[0]);
      return $lastResult;
    case "INTIMIDATE":
      if ($parameter != "-") $player = $parameter;
      else $player = $lastResult == "MYCHAR-0" ? $currentPlayer : $defPlayer;
      WriteLog("Player {$player} was targeted to intimidate.");
      $hand = &GetHand($player);
      if(count($hand) == 0) {
        WriteLog("Intimidate did nothing because there are no cards in their hand");
        return;
      }
      $index = GetRandom() % count($hand);
      BanishCardForPlayer($hand[$index], $player, "HAND", "INT");
      RemoveHand($player, $index);
      WriteLog("Player {$player} banishes a card face down");
      return $player;
    case "REMOVESUBCARD":
      $char = &GetPlayerCharacter($player);
      $subcards = explode(",", $char[$parameter+10]);
      $subcardsCount = count($subcards);
      $cardID = "";
      for ($i = 0; $i < $subcardsCount; $i++) {
        if (is_array($lastResult)) {
          if (in_array($subcards[$i], $lastResult)) {
            if ($cardID == "") $cardID = $subcards[$i];
            else $cardID = $cardID . "," . $subcards[$i];
            array_splice($lastResult, array_search($subcards[$i], $lastResult), 1);
            array_splice($subcards, $i, 1);
            $i--; $subcardsCount--;
            if (count($lastResult) == 0) break;
          }
        } else {
            if ($subcards[$i] == $lastResult) {
              $cardID = $subcards[$i];
              array_splice($subcards, $i, 1);
              break;
            }
          }
      }
      $char[$parameter+10] = implode(",", $subcards);
      UpdateSubcardCounterCount($currentPlayer, $parameter);
      return $cardID;
      case "REMOVECOUNTERAURAORDESTROY":
        $auras = &GetAuras($player);
        if($lastResult == "YES") --$auras[$parameter+2];
        else {
          DestroyAuraUniqueID($player, $auras[$parameter+6]);
          WriteLog(CardLink($auras[$parameter], $auras[$parameter]) . " was destroyed");
        }
      return "";
      case "REMOVECOUNTERITEMORDESTROY":
        $items = &GetItems($player);
        if($lastResult == "YES") --$items[$parameter+1];
        else {
          DestroyItemForPlayer($player, $parameter); 
          WriteLog(CardLink($items[$parameter], $items[$parameter]) . " was destroyed");
        }
      return"";
    default:
      return "NOTSTATIC";
  }
}
