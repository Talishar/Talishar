<?php

function HNTAbilityType($cardID): string
{
  return match ($cardID) {
    "HNT003" => "AR",
    "HNT004" => "AR",
    "HNT005" => "I",
    "HNT006" => "AR",
    "HNT007" => "AR",
    "HNT054" => "I",
    "HNT055" => "I",
    "HNT167" => "I",
    "HNT247" => "I",
    "HNT252" => "I",
    default => ""
  };
}

function HNTAbilityCost($cardID): int
{
  global $currentPlayer, $mainPlayer;
  return match ($cardID) {
    "HNT054" => 3 - ($mainPlayer == $currentPlayer ? NumDraconicChainLinks() : 0),
    "HNT055" => 3 - ($mainPlayer == $currentPlayer ? NumDraconicChainLinks() : 0),
    "HNT167" => 0,
    "HNT252" => 0,
    default => 0
  };
}

function HNTAbilityHasGoAgain($cardID): bool
{
  return match ($cardID) {
    default => false,
  };
}

function HNTEffectAttackModifier($cardID): int
{
  return match ($cardID) {
    "HNT003" => 3,
    "HNT004" => 3,
    "HNT005" => 3,
    "HNT006" => 3,
    "HNT007" => 3,
    "HNT015" => 3,
    "HNT102-BUFF" => 2,
    "HNT127" => 1,
    "HNT236" => -1,
    "HNT258-BUFF" => 2,
    default => 0,
  };
}

function HNTCombatEffectActive($cardID, $attackID): bool
{
  global $mainPlayer, $combatChainState, $CCS_WeaponIndex;
  $dashArr = explode("-", $cardID);
  $cardID = $dashArr[0];
  if ($cardID == "HNT102" & count($dashArr) > 1) {
    if ($dashArr[1] == "BUFF") return CardSubType($attackID) == "Dagger";
    if (DelimStringContains($dashArr[1], "MARK", true)) {
      $id = explode(",", $dashArr[1])[1];
      $character = &GetPlayerCharacter($mainPlayer);
      return $character[$combatChainState[$CCS_WeaponIndex] + 11] == $id;
    }
  }
  if ($cardID == "HNT003" && count($dashArr) > 1 && $dashArr[1] == "HIT") return HasStealth($attackID);
  if ($cardID == "HNT004" && count($dashArr) > 1 && $dashArr[1] == "HIT") return HasStealth($attackID);
  return match ($cardID) {
    "HNT003" => ClassContains($cardID, "ASSASSIN"),
    "HNT004" => ClassContains($cardID, "ASSASSIN"),
    "HNT005" => ClassContains($cardID, "ASSASSIN"),
    "HNT006" => ClassContains($cardID, "ASSASSIN"),
    "HNT007" => CardSubType($attackID) == "Dagger",
    "HNT015" => true,
    "HNT071" => TalentContains($cardID, "DRACONIC", $mainPlayer),
    "HNT074" => TalentContains($cardID, "DRACONIC", $mainPlayer),
    "HNT075" => TalentContains($cardID, "DRACONIC", $mainPlayer),
    "HNT076" => TalentContains($cardID, "DRACONIC", $mainPlayer),
    "HNT116" => true,
    "HNT125" => CardSubType($attackID) == "Dagger",
    "HNT127" => CardSubType($attackID) == "Dagger",
    "HNT167" => DelimStringContains(CardType($attackID), "AA"),
    "HNT236" => true,
    "HNT249" => true,
    "HNT258" => CardNameContains($attackID, "Raydn", $mainPlayer, true),
    default => false,
  };
}

function HNTPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = ""): string
{
  global $currentPlayer, $CS_ArcaneDamagePrevention, $CS_NumSeismicSurgeDestroyed, $CombatChain;
  $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
  switch ($cardID) {
    case "HNT003":
    case "HNT004":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      if (HasStealth($CombatChain->AttackCard()->ID())) AddCurrentTurnEffect("$cardID-HIT", $currentPlayer);
      break;
    case "HNT005":
      WriteLog("We don't know what Graphene Chelicera is yet");
      AddCurrentTurnEffect($cardID, $currentPlayer);
      break;
    case "HNT006":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      if (HasStealth($CombatChain->AttackCard()->ID())) GiveAttackGoAgain();
      break;
    case "HNT007":
      AddCurrentTurnEffect("HNT007", $currentPlayer);
      break;
    case "HNT015":
      AddDecisionQueue("PASSPARAMETER", $currentPlayer, $additionalCosts, 1);
      AddDecisionQueue("MODAL", $currentPlayer, "TARANTULATOXIN", 1);
      break;
    case "HNT054":
    case "HNT055":
      RecurDagger($currentPlayer, 0);
      RecurDagger($currentPlayer, 1);
      break;
    case "HNT057":
      ThrowWeapon("Dagger", $cardID);
      ThrowWeapon("Dagger", $cardID);
      break;
    case "HNT071":
      if(TalentContains($cardID, "DRACONIC", $currentPlayer)) {
        AddCurrentTurnEffect($cardID, $currentPlayer);
      }
      break;
    case "HNT074":
      if(TalentContains($cardID, "DRACONIC", $currentPlayer)) {
        AddCurrentTurnEffect($cardID, $currentPlayer);
      }
      break;
    case "HNT075":
      if(TalentContains($cardID, "DRACONIC", $currentPlayer)) {
        AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYCHAR:type=C&THEIRCHAR:type=C&MYALLY&THEIRALLY", 1);
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a target to deal 2 damage");
        AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("MZDAMAGE", $currentPlayer, "2,DAMAGE," . $cardID, 1);
      }
      break;
    case "HNT076":
      if(TalentContains($cardID, "DRACONIC", $currentPlayer)) {
        AddCurrentTurnEffect($cardID, $currentPlayer);
      }
      break;
    case "HNT102":
      AddDecisionQueue("PASSPARAMETER", $currentPlayer, $additionalCosts, 1);
      AddDecisionQueue("MODAL", $currentPlayer, "LONGWHISKER", 1);
    case "HNT116":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      break;
    case "HNT117":
      $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
      if (TypeContains($CombatChain->AttackCard()->ID(), "W", $currentPlayer) && CanRevealCards($otherPlayer) && !IsAllyAttacking()) {
        AddDecisionQueue("MULTIZONEINDICES", $otherPlayer, "MYHAND", 1);
        AddDecisionQueue("SETDQCONTEXT", $otherPlayer, "Choose a card from hand, action card will be blocked with, non-actions discarded");
        AddDecisionQueue("CHOOSEMULTIZONE", $otherPlayer, "<-", 1);
        AddDecisionQueue("PROVOKE", $otherPlayer, "-", 1);
      }
      break;
    case "HNT158": case "HNT159": case "HNT160":
      if(IsHeroAttackTarget() && CheckMarked($otherPlayer)) {
        PlayAura("HNT167", $currentPlayer);
      }
      break;
    case "HNT165":
      $otherchar = &GetPlayerCharacter($otherPlayer);
      MarkHero($otherPlayer);
      if (CardNameContains($otherchar[0], "Arakni")) {
        GainResources($currentPlayer, 1);
      }
      break;
    case "HNT167":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      break;
    case "HNT236":
      if(!IsAllyAttacking() && CheckMarked($otherPlayer)) {
        AddCurrentTurnEffectNextAttack($cardID, $otherPlayer);
      }
      break;
    case "HNT246":
      DiscardRandom();
      break;
    case "HNT247":
      if(GetClassState($currentPlayer, $CS_NumSeismicSurgeDestroyed) > 0 || SearchAurasForCard("WTR075", $currentPlayer) != "") $prevent = 2;
      else $prevent = 1;
      IncrementClassState($currentPlayer, $CS_ArcaneDamagePrevention, $prevent);
      return CardLink($cardID, $cardID) . " prevent your next arcane damage by " . $prevent;
    case "HNT248":
      $maxSeismicCount = count(explode(",", SearchAurasForCard("WTR075", $currentPlayer)))+1;
      for($i=0; $i < $maxSeismicCount; ++$i) {
        AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "THEIRAURAS:minCost=0;maxCost=".$resourcesPaid."&MYAURAS:minCost=0;maxCost=".$resourcesPaid, 1);
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose an aura with cost " . $resourcesPaid . " or less to destroy", 1);
        AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("MZDESTROY", $currentPlayer, "-", 1);
        AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYAURAS:isCardID=WTR075", 1);
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a Seismic Surge to destroy or pass", 1);
        AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("MZDESTROY", $currentPlayer, "-", 1);
      }
      break;
    case "HNT249":
      if (ComboActive($cardID)) {
        AddDecisionQueue("INPUTCARDNAME", $currentPlayer, "-");
        AddDecisionQueue("SETDQVAR", $currentPlayer, "0");
        AddDecisionQueue("PREPENDLASTRESULT", $currentPlayer, "HNT249-");
        AddDecisionQueue("ADDCURRENTEFFECT", $currentPlayer, "<-");
        AddDecisionQueue("WRITELOG", $currentPlayer, "📣<b>{0}</b> was chosen");
      }
      break;
    case "HNT252":
      $prevent = SearchArsenal($currentPlayer, subtype:"Arrow", faceUp:true) != "" ? 2 : 1;
      IncrementClassState($currentPlayer, $CS_ArcaneDamagePrevention, $prevent);
      return CardLink($cardID, $cardID) . " prevent your next arcane damage by " . $prevent;
    case "HNT255":
      AddDecisionQueue("CHOOSENUMBER", $currentPlayer, "1,2,3,4,5,6");
      AddDecisionQueue("SETDQVAR", $currentPlayer, "0");
      AddDecisionQueue("CHOOSENUMBER", $otherPlayer, "1,2,3,4,5,6");
      AddDecisionQueue("SETDQVAR", $currentPlayer, "1");
      AddDecisionQueue("COMPARENUMBERS", $currentPlayer, "-");
      AddDecisionQueue("SPURLOCKED", $currentPlayer, "-");
      break;
    case "HNT258":
      if (GetResolvedAbilityType($cardID, "HAND") == "AR") {
        AddCurrentTurnEffect($cardID."-BUFF", $currentPlayer);
      }
      else {
        $params = explode("-", $target);
        $uniqueID = $params[1];
        AddCurrentTurnEffect($cardID."-DMG,".$additionalCosts.",".$uniqueID, $currentPlayer);
      }
      break;
    case "HNT259":
      MZChooseAndBanish($currentPlayer, "MYHAND", "HAND,-");
      MZChooseAndBanish($otherPlayer, "MYHAND", "HAND,-");
    default:
      break;
  }
  return "";
}

function HNTHitEffect($cardID): void
{
  global $mainPlayer, $defPlayer;
  $dashArr = explode("-", $cardID);
  $cardID = $dashArr[0];
  switch ($cardID) {
    case "HNT074":
      DestroyArsenal($defPlayer, effectController:$mainPlayer);
      break;
    case "HNT076":
      AddDecisionQueue("FINDINDICES", $defPlayer, "EQUIP");
      AddDecisionQueue("CHOOSETHEIRCHARACTER", $mainPlayer, "<-", 1);
      AddDecisionQueue("MODDEFCOUNTER", $defPlayer, "-1", 1);
      AddDecisionQueue("DESTROYEQUIPDEF0", $mainPlayer, "-", 1);
      break;
    default:
      break;
  }
}

function MarkHero($player): string
{
  WriteLog("Player " . $player . " is now marked!");
  if (!SearchCurrentTurnEffects("HNT244", $player)) AddCurrentTurnEffect("HNT244", $player);
  $character = &GetPlayerCharacter($player);
  $character[13] = 1;
  return "";
}

function CheckMarked($player): bool
{
  $character = &GetPlayerCharacter($player);
  return $character[13] == 1;
}

function RemoveMark($player)
{
  $effectIndex = SearchCurrentTurnEffects("HNT244", $player);
  if ($effectIndex > -1) RemoveCurrentTurnEffect($effectIndex);
  $character = &GetPlayerCharacter($player);
  $character[13] = 0;
}

function RecurDagger($player, $mode) //$mode == 0 for left, and 1 for right
{
  $char = &GetPlayerCharacter($player);
  if ($char[CharacterPieces() * ($mode + 1) + 1] == 0) { //Only Equip if there is a broken weapon/off-hand
    AddDecisionQueue("LISTDRACDAGGERGRAVEYARD", $player, "-");
    AddDecisionQueue("NULLPASS", $player, "-", 1);
    AddDecisionQueue("SETDQCONTEXT", $player, "Choose a dagger to equip", 1);
    AddDecisionQueue("CHOOSECARD", $player, "<-", 1);
    AddDecisionQueue("EQUIPCARDGRAVEYARD", $player, "<-", 1);
  }
}

function ListDracDaggersGraveyard($player) {
  $weapons = "";
  $char = &GetPlayerCharacter($player);
  $graveyard = &GetDiscard($player);
  foreach ($graveyard as $cardID) {
    if (TypeContains($cardID, "W", $player) && SubtypeContains($cardID, "Dagger")) {
      if (TalentContains($cardID, "")) {
        if ($weapons != "") $weapons .= ",";
        $weapons .= $cardID;
      }
    }
  }
  if ($weapons == "") {
    WriteLog("Player " . $player . " doesn't have any dagger in their graveyard");
  }
  return $weapons;
}

function ChaosTransform($characterID, $mainPlayer)
{
  $char = &GetPlayerCharacter($mainPlayer);
  if ($characterID == "HNT001" || $characterID == "HNT002") {
    $roll = GetRandom(1, 6);
    $transformTarget = match ($roll) {
      1 => "HNT003",
      2 => "HNT004",
      3 => "HNT005",
      4 => "HNT006",
      5 => "HNT007",
      6 => "HNT008",
      default => $characterID,
    };
    WriteLog(CardName($characterID) . " becomes " . CardName($transformTarget));
    //storing the original hero under the transformed hero
    $char[10] = ($char[10] == "-") ? $characterID : $char[10] . ",$characterID";
  }
  else {
    $transformTarget = "";
    WriteLog(CardName($characterID) . " returns to the brood.");
    $subCards = "";
    foreach (explode(",", $char[10]) as $subCard) {
      if ($subCard == "HNT001" || $subCard == "HNT002") {
        $transformTarget = $subCard;
      }
      else $subCards .= "$subCard,";
    }
    if ($transformTarget == ""){
      WriteLog("Something has gone wrong, please submit a bug report");
      $transformTarget = "HNT001";
    }
    // removing the subcardded hero
    $char[10] = $subCards == "" ? "-" : substr($subCards, 0, -1);
  }
  $char[0] = $transformTarget;
  if ($transformTarget == "HNT008") {
    AddDecisionQueue("YESNO", $mainPlayer, ":_banish_a_card_to_arakni_trapdoor?");
    AddDecisionQueue("NOPASS", $mainPlayer, "-");
    AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "MYDECK", 1);
    AddDecisionQueue("MAYCHOOSEMULTIZONE", $mainPlayer, "<-", 1);
    AddDecisionQueue("TRAPDOOR", $mainPlayer, "-", 1);
    AddDecisionQueue("SHUFFLEDECK", $mainPlayer, "-", 1);
  }
}