<?php

include "CardSetters.php";
include "CardGetters.php";

function EvaluateCombatChain(&$totalAttack, &$totalDefense, &$attackModifiers = [])
{
  global $CombatChain, $mainPlayer, $currentTurnEffects, $playerID, $combatChainState, $CCS_LinkBaseAttack, $CCS_WeaponIndex;
  global $CCS_WeaponIndex;
  UpdateGameState($playerID);
  BuildMainPlayerGameState();
  $attackType = CardType($CombatChain->AttackCard()->ID());
  $canGainAttack = CanGainAttack($CombatChain->AttackCard()->ID());
  $snagActive = SearchCurrentTurnEffects("CRU182", $mainPlayer) && $attackType == "AA";

  for ($i = 0; $i < $CombatChain->NumCardsActiveLink(); ++$i) {
    $chainCard = $CombatChain->Card($i, true);
    if ($chainCard->ID() == "MST031" && SearchCharacterActive($mainPlayer, "MST130")) {
      $combatChainState[$CCS_LinkBaseAttack] = WardAmount($chainCard->ID(), $mainPlayer, $combatChainState[$CCS_WeaponIndex]);
    }
    if ($chainCard->PlayerID() == $mainPlayer) {
      if ($i == 0) $attack = $combatChainState[$CCS_LinkBaseAttack];
      else $attack = AttackValue($chainCard->ID());
      if ($canGainAttack || $i == 0 || $attack < 0) {
        array_push($attackModifiers, $chainCard->ID());
        array_push($attackModifiers, $attack);
        if ($i == 0) $totalAttack += $attack;
        else AddAttack($totalAttack, $attack);
      }
      $attack = AttackModifier($chainCard->ID(), $chainCard->From(), $chainCard->ResourcesPaid(), $chainCard->RepriseActive()) + $chainCard->AttackValue();
      if (($canGainAttack && !$snagActive) || $attack < 0) {
        array_push($attackModifiers, $chainCard->ID());
        array_push($attackModifiers, $attack);
        AddAttack($totalAttack, $attack);
      }
    } else {
      $totalDefense += BlockingCardDefense($i * CombatChainPieces());
    }
  }
  //Now check current turn effects
  for ($i = 0; $i < count($currentTurnEffects); $i += CurrentTurnPieces()) {
    if (IsCombatEffectActive($currentTurnEffects[$i]) && !IsCombatEffectLimited($i)) {
      if ($currentTurnEffects[$i + 1] == $mainPlayer) {
        $attack = EffectAttackModifier($currentTurnEffects[$i]);
        if (($canGainAttack || $attack < 0) && !($snagActive && ($currentTurnEffects[$i] == $CombatChain->AttackCard()->ID() || CardType(EffectCardID($currentTurnEffects[$i])) == "AR"))) {
          array_push($attackModifiers, $currentTurnEffects[$i]);
          array_push($attackModifiers, $attack);
          AddAttack($totalAttack, $attack);
        }
      }
    }
  }
  if ($combatChainState[$CCS_WeaponIndex] != -1) {
    $attack = 0;
    if ($attackType == "W") {
      $char = &GetPlayerCharacter($mainPlayer);
      $attack = $char[$combatChainState[$CCS_WeaponIndex] + 3];
      if (filter_var($attack, FILTER_VALIDATE_INT) === false) $attack = 0;
    } else if (DelimStringContains(CardSubtype($CombatChain->AttackCard()->ID()), "Aura")) {
      $auras = &GetAuras($mainPlayer);
      if (isset($auras[$combatChainState[$CCS_WeaponIndex]])) $attack = $auras[$combatChainState[$CCS_WeaponIndex] + 3];
    } else if (DelimStringContains(CardSubtype($CombatChain->AttackCard()->ID()), "Ally")) {
      $allies = &GetAllies($mainPlayer);
      if (isset($allies[$combatChainState[$CCS_WeaponIndex]])) $attack = $allies[$combatChainState[$CCS_WeaponIndex] + 9];
    }
    if ($canGainAttack || $attack < 0) {
      array_push($attackModifiers, "ATKCOU"); //Attack Counter image ID
      array_push($attackModifiers, $attack);
      AddAttack($totalAttack, $attack);
    }
  }
  $attack = MainCharacterAttackModifiers($attackModifiers);
  if ($canGainAttack || $attack < 0) {
    AddAttack($totalAttack, $attack);
  }
  $attack = AuraAttackModifiers(0, $attackModifiers);
  if ($canGainAttack || $attack < 0) {
    AddAttack($totalAttack, $attack);
  }
  $attack = ArsenalAttackModifier($attackModifiers);
  if ($canGainAttack || $attack < 0) {
    AddAttack($totalAttack, $attack);
  }
  $attack = ItemAttackModifiers($attackModifiers);
  if ($canGainAttack || $attack < 0) {
    AddAttack($totalAttack, $attack);
  }
}

function AddAttack(&$totalAttack, $amount): void
{
  global $CombatChain, $currentPlayer;
  $attackID = $CombatChain->AttackCard()->ID();
  if (PowerCantBeModified($attackID)) return;
  if ($amount > 0 && $attackID == "OUT100") $amount += 1;
  if ($amount > 0 && SearchCurrentTurnEffects("TER019", $currentPlayer)) {
    $num_thrives_active = CountCurrentTurnEffects("TER019", $currentPlayer); //thrives stack so get all the active effects before applying bonus
    $amount += $num_thrives_active;
  }
  if ($amount > 0) {
    SearchCurrentTurnEffects("TER017-INACTIVE", $currentPlayer, false, false, true);
    SearchCurrentTurnEffects("TER024-INACTIVE", $currentPlayer, false, false, true);
  }
  if ($amount > 0 && ($attackID == "OUT065" || $attackID == "OUT066" || $attackID == "OUT067") && ComboActive()) $amount += 1;
  if ($amount > 0) $amount += PermanentAddAttackAbilities();
  $totalAttack += $amount;
}

function BlockingCardDefense($index)
{
  global $combatChain, $defPlayer, $currentTurnEffects;
  $from = isset($combatChain[$index + 2]) ? $combatChain[$index + 2] : "-";
  $cardID = isset($combatChain[$index]) ? $combatChain[$index] : "-";
  $baseCost = ($from == "PLAY" || $from == "EQUIP" ? AbilityCost($cardID) : (CardCost($cardID) + SelfCostModifier($cardID, $from)));
  $resourcesPaid = (isset($combatChain[$index + 3]) ? intval($combatChain[$index + 3]) : 0) + intval($baseCost);
  $defense = intval(BlockValue($cardID)) + (BlockCantBeModified($cardID) ? 0 : (isset($combatChain[$index + 6]) ? intval(BlockModifier($cardID, $from, $resourcesPaid)) + intval($combatChain[$index + 6]) : 0));
  if (TypeContains($cardID, "E", $defPlayer)) {
    $defCharacter = &GetPlayerCharacter($defPlayer);
    $charIndex = isset($combatChain[$index + 8]) ? SearchCharacterForUniqueID($combatChain[$index + 8], $defPlayer) : null;
    $defense += $defCharacter[$charIndex + 4];
  }
  for ($i = 0; $i < count($currentTurnEffects); $i += CurrentTurnPieces()) {
    if (IsCombatEffectActive($currentTurnEffects[$i]) && !IsCombatEffectLimited($i)) {
      if ($currentTurnEffects[$i + 1] == $defPlayer) {
        $defense += EffectBlockModifier($currentTurnEffects[$i], $index, $from);
      }
    }
  }
  if ($defense < 0) $defense = 0;
  return $defense;
}

function AddCombatChain($cardID, $player, $from, $resourcesPaid, $OriginUniqueID)
{
  global $combatChain, $turn;
  $index = count($combatChain);
  $correction = count($combatChain) % CombatChainPieces();
  if ($correction != 0) $correction = CombatChainPieces() - $correction;
  for ($i = 0; $i < $correction; ++$i) array_push($combatChain, 0);
  array_push($combatChain, $cardID);
  array_push($combatChain, $player);
  array_push($combatChain, $from);
  array_push($combatChain, $resourcesPaid);
  array_push($combatChain, RepriseActive());
  array_push($combatChain, 0);//Attack modifier
  array_push($combatChain, 0);//Defense modifier
  array_push($combatChain, GetUniqueId($cardID, $player));
  array_push($combatChain, $OriginUniqueID);

  if ($turn[0] == "B" || CardType($cardID) == "DR" || DefendingTerm($turn[0])) OnBlockEffects($index, $from);
  CurrentEffectAttackAbility();
  return $index;
}

function DefendingTerm($term)
{
  switch ($term) {
    case "ADDCARDTOCHAINASDEFENDINGCARD": // Pulsewave Harpoon, Pulsewave Protocol, etc.
      return true;
    default:
      return false;
  }
}

function CombatChainPowerModifier($index, $amount)
{
  global $combatChain;
  $combatChain[$index + 5] += $amount;
  ProcessPhantasmOnBlock($index);
  ProcessAllMirage();
}

function StartTurnAbilities()
{
  global $mainPlayer, $defPlayer;
  $mainCharacter = &GetPlayerCharacter($mainPlayer);
  for ($i = count($mainCharacter) - CharacterPieces(); $i >= 0; $i -= CharacterPieces()) CharacterStartTurnAbility($i);
  ArsenalStartTurnAbilities();
  DefCharacterStartTurnAbilities();
  ArsenalStartTurnAbilities();
  AuraStartTurnAbilities();
  PermanentStartTurnAbilities();
  AllyStartTurnAbilities($mainPlayer);
  LandmarkStartTurnAbilities();
  $mainItems = &GetItems($mainPlayer);
  for ($i = count($mainItems) - ItemPieces(); $i >= 0; $i -= ItemPieces()) {
    $mainItems[$i + 2] = "2";
    $mainItems[$i + 3] = ItemUses($mainItems[$i]);
    ItemStartTurnAbility($i);
  }
  $defItems = &GetItems($defPlayer);
  for ($i = 0; $i < count($defItems); $i += ItemPieces()) {
    $defItems[$i + 2] = "2";
    $defItems[$i + 3] = ItemUses($defItems[$i]);
  }
  $defCharacter = &GetPlayerCharacter($defPlayer);
  for ($i = 0; $i < count($defCharacter); $i += CharacterPieces()) {
    $defCharacter[$i + 8] = "0";//Reset Frozen
  }
  $defAllies = &GetAllies($defPlayer);
  for ($i = 0; $i < count($defAllies); $i += AllyPieces()) {
    $defAllies[$i + 3] = "0";//Reset Frozen
  }
  $defArsenal = &GetArsenal($defPlayer);
  for ($i = 0; $i < count($defArsenal); $i += ArsenalPieces()) {
    $defArsenal[$i + 4] = "0";//Reset Frozen
  }
  MZStartTurnMayAbilities();
}

function MZStartTurnMayAbilities()
{
  global $mainPlayer;
  AddDecisionQueue("FINDINDICES", $mainPlayer, "MZSTARTTURN");
  AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose a start turn ability to activate (or pass)", 1);
  AddDecisionQueue("MAYCHOOSEMULTIZONE", $mainPlayer, "<-", 1);
  AddDecisionQueue("MZSTARTTURNABILITY", $mainPlayer, "-", 1);
}

function MZStartTurnIndices()
{
  global $mainPlayer;
  $mainDiscard = &GetDiscard($mainPlayer);
  $cards = "";
  for ($i = 0; $i < count($mainDiscard); $i += DiscardPieces()) {
    switch ($mainDiscard[$i]) {
      case "UPR086":
        if (ThawIndices($mainPlayer) != "") $cards = CombineSearches($cards, SearchMultiZoneFormat($i, "MYDISCARD"));
        break;
      default:
        break;
    }
  }
  return $cards;
}

function ArsenalStartTurnAbilities()
{
  global $mainPlayer;
  $arsenal = &GetArsenal($mainPlayer);
  for ($i = 0; $i < count($arsenal); $i += ArsenalPieces()) {
    switch ($arsenal[$i]) {
      case "MON404":
      case "MON405":
      case "MON406":
      case "MON407":
      case "DVR007":
      case "RVD007":
        if ($arsenal[$i + 1] == "DOWN") {
          AddDecisionQueue("YESNO", $mainPlayer, "if_you_want_to_turn_your_mentor_face_up");
          AddDecisionQueue("NOPASS", $mainPlayer, "-");
          AddDecisionQueue("TURNARSENALFACEUP", $mainPlayer, $i, 1);
        }
        break;
      default:
        break;
    }
  }
}

function ArsenalAttackAbilities()
{
  global $CombatChain, $mainPlayer;
  $attackID = $CombatChain->AttackCard()->ID();
  $arsenal = GetArsenal($mainPlayer);
  for ($i = 0; $i < count($arsenal); $i += ArsenalPieces()) {
    switch ($arsenal[$i]) {
      case "MON406":
        if (CardType($attackID) == "AA" && $arsenal[$i + 1] == "UP") LadyBarthimontAbility($mainPlayer, $i);
        break;
      case "RVD007":
        if (CardType($attackID) == "AA" && ModifiedAttackValue($attackID, $mainPlayer, "CC", source: $arsenal[$i]) >= 6 && $arsenal[$i + 1] == "UP") ChiefRukutanAbility($mainPlayer, $i);
        break;
      default:
        break;
    }
  }
}

function ArsenalAttackModifier(&$attackModifiers)
{
  global $CombatChain, $mainPlayer;
  $attackID = $CombatChain->AttackCard()->ID();
  $attackType = CardType($attackID);
  $arsenal = GetArsenal($mainPlayer);
  $modifier = 0;
  for ($i = 0; $i < count($arsenal); $i += ArsenalPieces()) {
    switch ($arsenal[$i]) {
      case "MON405":
        $modifier += ($arsenal[$i + 1] == "UP" && TypeContains($attackID, "W", $mainPlayer) && Is1H($attackID) ? 1 : 0);
        array_push($attackModifiers, $arsenal[$i]);
        array_push($attackModifiers, $modifier);
        break;
      default:
        break;
    }
  }
  return $modifier;
}

function ArsenalHitEffects()
{
  global $CombatChain, $mainPlayer;
  $attackID = $CombatChain->AttackCard()->ID();
  $arsenal = GetArsenal($mainPlayer);
  $modifier = 0;
  for ($i = 0; $i < count($arsenal); $i += ArsenalPieces()) {
    switch ($arsenal[$i]) {
      case "MON405":
        if ($arsenal[$i + 1] == "UP" && TypeContains($attackID, "W", $mainPlayer)) {
          MinervaThemisAbility($mainPlayer, $i);
          break;
        }
      case "DVR007":
        if ($arsenal[$i + 1] == "UP" && TypeContains($attackID, "W", $mainPlayer) && CardSubType($attackID) == "Sword") {
          HalaGoldenhelmAbility($mainPlayer, $i);
          break;
        }
      default:
        break;
    }
  }
  return $modifier;
}

function ArsenalPlayCardAbilities($cardID)
{
  global $currentPlayer;
  $cardType = CardType($cardID);
  $arsenal = GetArsenal($currentPlayer);
  for ($i = 0; $i < count($arsenal); $i += ArsenalPieces()) {
    switch ($arsenal[$i]) {
      case "MON407":
        if ($arsenal[$i + 1] == "UP" && $cardType == "A") LordSutcliffeAbility($currentPlayer, $i);
        break;
      default:
        break;
    }
  }
}

function HasIncreasedAttack()
{
  global $CombatChain, $combatChainState, $CCS_LinkBaseAttack, $mainPlayer, $combatChain;
  if ($CombatChain->HasCurrentLink()) {
    $attack = 0;
    $defense = 0;
    EvaluateCombatChain($attack, $defense);
    if (SearchCharacterActive($mainPlayer, "MST130") && HasWard($combatChain[0], $mainPlayer) && SubtypeContains($combatChain[0], "Aura", $mainPlayer)) {
      if ($attack > WardAmount($combatChain[0], $mainPlayer)) return true;
      else return false;
    }
    if ($attack > $combatChainState[$CCS_LinkBaseAttack]) return true;
  }
  return false;
}

function DamageTrigger($player, $damage, $type, $source = "NA")
{
  PrependDecisionQueue("DEALDAMAGE", $player, $damage . "-" . $source . "-" . $type);
  return $damage;
}

function CanDamageBePrevented($player, $damage, $type, $source = "-")
{
  global $mainPlayer;
  $otherPlayer = ($player == 1 ? 2 : 1);
  if ($type == "ARCANE" && SearchCurrentTurnEffects("EVR105", $player)) return false;
  if ($source == "ARC112" && (SearchCurrentTurnEffects("DTD134", $otherPlayer) || SearchCurrentTurnEffects("DTD133", $otherPlayer))) return false;
  if (SearchCurrentTurnEffects("UPR158", $otherPlayer)) return false;
  if ($type == "COMBAT" && SearchCurrentTurnEffects("DTD208", $mainPlayer)) return false;
  if ($source == "DYN005" || $source == "OUT030" || $source == "OUT031" || $source == "OUT032" || $source == "OUT121" || $source == "OUT122" || $source == "OUT123") return false;
  if (($source == "MST127" || $source == "MST128" || $source == "MST129") && NumAttackReactionsPlayed() > 0) return false;
  return true;
}

function DealDamageAsync($player, $damage, $type = "DAMAGE", $source = "NA")
{
  global $CS_DamagePrevention, $combatChain, $CS_ArcaneDamagePrevention, $dqVars, $dqState;
  $classState = &GetPlayerClassState($player);
  if ($type == "COMBAT" || $type == "ATTACKHIT") $source = $combatChain[0];
  $otherPlayer = $player == 1 ? 2 : 1;
  $damage = $damage > 0 ? $damage : 0;
  $damageThreatened = $damage;
  $preventable = CanDamageBePrevented($player, $damage, $type, $source);
  if ($preventable) {
    if ($damage > 0) $damage = CurrentEffectPreventDamagePrevention($player, $type, $damage, $source);
    if (ConsumeDamagePrevention($player)) return 0;//If damage can be prevented outright, don't use up your limited damage prevention
    if ($type == "ARCANE") {
      if ($damage <= $classState[$CS_ArcaneDamagePrevention]) {
        $classState[$CS_ArcaneDamagePrevention] -= $damage;
        $damage = 0;
      } else {
        $damage -= $classState[$CS_ArcaneDamagePrevention];
        $classState[$CS_ArcaneDamagePrevention] = 0;
      }
    }
    if ($damage <= $classState[$CS_DamagePrevention]) {
      CheckIfHauntingRenditionIsActive($player);
      $classState[$CS_DamagePrevention] -= $damage;
      $damage = 0;
    } else {
      CheckIfHauntingRenditionIsActive($player);
      $damage -= $classState[$CS_DamagePrevention];
      $classState[$CS_DamagePrevention] = 0;
    }
  }
  //else: CR 2.0 6.4.10h If damage is not prevented, damage prevention effects are not consumed
  $damage = $damage > 0 ? $damage : 0;
  $damage = CurrentEffectDamagePrevention($player, $type, $damage, $source, $preventable);
  $damage = AuraTakeDamageAbilities($player, $damage, $type, $source);
  $damage = PermanentTakeDamageAbilities($player, $damage, $type, $source);
  $damage = ItemTakeDamageAbilities($player, $damage, $type, $source);
  $damage = CharacterTakeDamageAbilities($player, $damage, $type, $preventable);
  if ($damage == 1 && $preventable && SearchItemsForCard("EVR069", $player) != "") $damage = 0;//Must be last
  $dqVars[0] = $damage;
  if ($type == "COMBAT") $dqState[6] = $damage;
  PrependDecisionQueue("FINALIZEDAMAGE", $player, $damageThreatened . "," . $type . "," . $source);
  if ($damage > 0) AddDamagePreventionSelection($player, $damage, $type, $preventable);
  if ($source == "ARC112") {
    SearchCurrentTurnEffects("DTD134", $otherPlayer, true);
    SearchCurrentTurnEffects("DTD133", $otherPlayer, true);
  }
  ResetAuraStatus($player);
  return $damage;
}

function CheckIfHauntingRenditionIsActive($player): void
{
  if (SearchCurrentTurnEffects("ROS120", $player, true)) {
    PlayAura("ARC112", $player); // Runechant
  }
}


function ResetAuraStatus($player)
{
  $auras = &GetAuras($player);
  for ($i = 0; $i < count($auras); $i += AuraPieces()) {
    switch ($auras[$i]) {
      case "CRU144":
        $auras[$i + 1] = 2;
        break;
      default:
        break;
    }
  }
}

function AddDamagePreventionSelection($player, $damage, $type, $preventable)
{
  PrependDecisionQueue("PROCESSDAMAGEPREVENTION", $player, $damage . "-" . $preventable . "-" . $type, 1);
  PrependDecisionQueue("CHOOSEMULTIZONE", $player, "<-", 1);
  PrependDecisionQueue("SETDQCONTEXT", $player, "Choose a card to prevent damage: " . $damage . " damage left", 1);
  PrependDecisionQueue("FINDINDICES", $player, "DAMAGEPREVENTION," . $type);
}

function FinalizeDamage($player, $damage, $damageThreatened, $type, $source)
{
  global $otherPlayer, $CS_DamageTaken, $combatChainState, $CCS_AttackTotalDamage, $CS_ArcaneDamageTaken, $defPlayer, $mainPlayer;
  $classState = &GetPlayerClassState($player);
  $otherPlayer = $player == 1 ? 2 : 1;
  if ($damage > 0) {
    if ($source != "NA") {
      $damage += CurrentEffectDamageModifiers($player, $source, $type);
      $otherCharacter = &GetPlayerCharacter($otherPlayer);
      $characterID = ShiyanaCharacter($otherCharacter[0]);
      DamageDealtAbilities($player, $damage, $type, $source);
      if ($source == "MON229") AddNextTurnEffect("MON229", $player);
      if (($characterID == "ELE062" || $characterID == "ELE063") && $type == "ARCANE" && $otherCharacter[1] == "2" && CardType($source) == "AA" && !SearchAuras("ELE109", $otherPlayer)) {
        PlayAura("ELE109", $otherPlayer);
      }
      if ($source == "DYN173" && SearchCurrentTurnEffects("DYN173", $mainPlayer, true)) {
        WriteLog("Player " . $mainPlayer . " drew a card and Player " . $otherPlayer . " must discard a card");
        Draw($mainPlayer);
        PummelHit();
      }
    }
    AuraDamageTakenAbilities($player, $damage);
    ItemDamageTakenAbilities($player, $damage);
    CharacterDamageTakenAbilities($player, $damage);
    AuraDamageDealtAbilities($otherPlayer, $damage);
    CharacterDealDamageAbilities($otherPlayer, $damage);
    if (SearchAuras("MON013", $otherPlayer)) {
      LoseHealth(CountAura("MON013", $otherPlayer), $player);
      WriteLog("Lost life from Ode to Wrath");
    }
    $classState[$CS_DamageTaken] += $damage;
    if ($player == $defPlayer && $type == "COMBAT" || $type == "ATTACKHIT") $combatChainState[$CCS_AttackTotalDamage] += $damage;
    if ($type == "ARCANE") $classState[$CS_ArcaneDamageTaken] += $damage;
    CurrentEffectDamageEffects($player, $source, $type, $damage);
  }
  if ($damage > 0 && ($type == "COMBAT" || $type == "ATTACKHIT") && SearchCurrentTurnEffects("ELE037-2", $otherPlayer) && IsHeroAttackTarget()) {
    for ($i = 0; $i < $damage; ++$i) PlayAura("ELE111", $player);
  }
  LogDamageStats($player, $damageThreatened, $damage);
  PlayerLoseHealth($player, $damage);
  return $damage;
}

function DamageDealtAbilities($player, $damage, $type, $source)
{
  global $mainPlayer, $combatChainState, $CCS_AttackFused;
  if (($source == "ELE067" || $source == "ELE068" || $source == "ELE069") && $combatChainState[$CCS_AttackFused]) AddCurrentTurnEffect($source, $mainPlayer);
  if ($source == "DYN612") GainHealth($damage, $mainPlayer);
}

function DoQuell($targetPlayer, $damage)
{
  $quellChoices = QuellChoices($targetPlayer, $damage);
  if ($quellChoices != "0") {
    PrependDecisionQueue("PAYRESOURCES", $targetPlayer, "<-", 1);
    PrependDecisionQueue("AFTERQUELL", $targetPlayer, "-", 1);
    PrependDecisionQueue("BUTTONINPUT", $targetPlayer, $quellChoices);
    PrependDecisionQueue("SETDQCONTEXT", $targetPlayer, "Choose an amount to pay for Quell");
  } else {
    PrependDecisionQueue("PASSPARAMETER", $targetPlayer, "0"); //If no quell, we need to discard the previous last result
  }
}

function PreventLethal($targetPlayer, $damage)
{
  global $CS_NextDamagePrevented;
  if (SearchHandForCard($targetPlayer, "MON405") != "") {
    MZMoveCard($targetPlayer, "MYHAND", "MYBANISH,HAND'-");
    SetClassState($targetPlayer, $CS_NextDamagePrevented, $damage);
  }
  if (SearchArsenalForCard($targetPlayer, "MON405") != "") {
    MZMoveCard($targetPlayer, "MYARS", "MYBANISH,ARS,-");
    SetClassState($targetPlayer, $CS_NextDamagePrevented, $damage);
  }
}

function ProcessDealDamageEffect($cardID)
{
  $set = CardSet($cardID);
  if ($set == "UPR") {
    return UPRDealDamageEffect($cardID);
  }
}

function ArcaneDamagePrevented($player, $cardMZIndex)
{
  $prevented = 0;
  $params = explode("-", $cardMZIndex);
  $zone = $params[0];
  $index = $params[1];
  switch ($zone) {
    case "MYCHAR":
      $source = &GetPlayerCharacter($player);
      break;
    case "MYITEMS":
      $source = &GetItems($player);
      break;
    case "MYAURAS":
      $source = &GetAuras($player);
      break;
  }
  if ($zone == "MYCHAR" && $source[$index + 1] == 0) return;
  $cardID = $source[$index];
  $spellVoidAmount = SpellVoidAmount($cardID, $player);
  if ($spellVoidAmount > 0) {
    if ($zone == "MYCHAR") DestroyCharacter($player, $index);
    else if ($zone == "MYITEMS") DestroyItemForPlayer($player, $index);
    else if ($zone == "MYAURAS") DestroyAura($player, $index);
    $prevented += $spellVoidAmount;
    WriteLog(CardLink($cardID, $cardID) . " was destroyed and prevented " . $spellVoidAmount . " arcane damage.");
  }
  return $prevented;
}

function CurrentEffectDamageModifiers($player, $source, $type)
{
  global $currentTurnEffects;
  $modifier = 0;
  for ($i = count($currentTurnEffects) - CurrentTurnPieces(); $i >= 0; $i -= CurrentTurnPieces()) {
    $remove = 0;
    switch ($currentTurnEffects[$i]) {
      case "ELE059":
      case "ELE060":
      case "ELE061":
        if ($type == "COMBAT" || $type == "ATTACKHIT") ++$modifier;
        break;
      case "ELE186":
      case "ELE187":
      case "ELE188":
        if (TalentContainsAny($source, "LIGHTNING,ELEMENTAL", $player)) ++$modifier;
        break;
      default:
        break;
    }
    if ($remove == 1) RemoveCurrentTurnEffect($i);
  }
  return $modifier;
}

function CurrentEffectDamageEffects($target, $source, $type, $damage)
{
  global $currentTurnEffects, $EffectContext;
  $otherPlayer = ($target == 1 ? 2 : 1);
  if (CardType($source) == "AA" && (SearchAuras("CRU028", 1) || SearchAuras("CRU028", 2))) return;
  for ($i = count($currentTurnEffects) - CurrentTurnPieces(); $i >= 0; $i -= CurrentTurnPieces()) {
    if ($currentTurnEffects[$i + 1] == $target) {
      continue;
    }
    if ($type == "COMBAT" && HitEffectsArePrevented($source)) continue;
    $remove = 0;
    $EffectContext = $currentTurnEffects[$i];
    switch ($currentTurnEffects[$i]) {
      case "ELE044":
      case "ELE045":
      case "ELE046":
        if (IsHeroAttackTarget() && CardType($source) == "AA")
          PlayAura("ELE111", $target);
        break;
      case "ELE050":
      case "ELE051":
      case "ELE052":
        if (IsHeroAttackTarget() && CardType($source) == "AA")
          PayOrDiscard($target, 1);
        break;
      case "ELE064":
        if ($source == "ELE064" && (IsHeroAttackTarget() || $type != "COMBAT"))
          MZMoveCard(($target == 1 ? 2 : 1), "MYDISCARD:type=A", "MYBANISH,GY,INST", may: true);
        break;
      case "UPR106":
      case "UPR107":
      case "UPR108":
        if ((IsHeroAttackTarget() || (IsHeroAttackTarget() == "" && $source != "ELE111")) && $type == "ARCANE") {
          PlayAura("ELE111", $target, $damage);
          $remove = 1;
        }
        break;
      case "HVY102":
        if (IsHeroAttackTarget()) {
          PlayAura("HVY240", $otherPlayer); //Agility
          $remove = 1;
        }
        break;
      case "ROS015": // So technically this procks if you deal damage to yourself but this would need to be refactored in order to make that work. Until someone has this happen, lets just leave it as so.
        if ($source != "ELE111" && $type == "ARCANE") {
          PlayAura("ELE109", $currentTurnEffects[$i + 1], 1);
          $remove = 1;
        }
        break;
      default:
        break;
    }
    if ($remove == 1) RemoveCurrentTurnEffect($i);
  }
}

function AttackDamageAbilities($damageDone)
{
  global $combatChain, $defPlayer;
  $attackID = $combatChain[0];
  switch ($attackID) {
    case "ELE036":
      if (IsHeroAttackTarget() && $damageDone >= NumEquipment($defPlayer)) {
        AddCurrentTurnEffect("ELE036", $defPlayer);
        AddNextTurnEffect("ELE036", $defPlayer);
      }
      break;
    default:
      break;
  }
}

function LoseHealth($amount, $player)
{
  PlayerLoseHealth($player, $amount);
}

function GainHealth($amount, $player, $silent = false, $preventable = true)
{
  global $mainPlayer;
  $otherPlayer = ($player == 1 ? 2 : 1);
  $health = &GetHealth($player);
  $otherHealth = &GetHealth($otherPlayer);
  if ((SearchCurrentTurnEffects("DTD231", 1, remove: true) || SearchCurrentTurnEffects("DTD231", 2, remove: true)) && $preventable) {
    WriteLog("<span style='color:green;'>Somebody poisoned the water hole</span>");
    LoseHealth($amount, $player);
    return false;
  }
  if (SearchCurrentTurnEffects("MON229", $player) && $preventable) {
    WriteLog(CardLink("MON229", "MON229") . " prevented you from gaining life");
    return;
  }
  if ((SearchCharacterForCard($player, "CRU140") || SearchCharacterForCard($otherPlayer, "CRU140") && $preventable) && $health > $otherHealth) {
    WriteLog("Reaping Blade prevented player " . $player . " from gaining " . $amount . " life");
    return false;
  }
  if (!$silent) WriteLog("Player " . $player . " gained " . $amount . " life");
  $health += $amount;

  if ($player == $mainPlayer) {
    $char = &GetPlayerCharacter($player);
    for ($i = 0; $i < count($char); $i += CharacterPieces()) {
      if (intval($char[$i + 1]) < 2) continue;
      switch ($char[$i]) {
        case "ROS013":
          // Now we need to check that we banished 8 earth cards.
          $results = SearchCount(SearchMultiZone($player, "MYBANISH:TALENT=EARTH"));
          if ($results >= 8) {
            AddLayer("TRIGGER", $mainPlayer, $char[$i], 3);
          }
          break;
        case "ROS014":
          // Now we need to check that we banished 4 earth cards.
          $results = SearchCount(SearchMultiZone($player, "MYBANISH:TALENT=EARTH"));
          if ($results >= 4) {
            AddLayer("TRIGGER", $mainPlayer, $char[$i], 3);
          }
          break;
        default:
          break;
      }
    }
  }
  return true;
}

function PlayerLoseHealth($player, $amount)
{
  global $CS_LifeLost;
  $health = &GetHealth($player);
  $amount = AuraLoseHealthAbilities($player, $amount);
  $health -= $amount;
  IncrementClassState($player, $CS_LifeLost, $amount);
  if ($health <= 0) {
    PlayerWon(($player == 1 ? 2 : 1));
  }
}

function IsGameOver()
{
  global $inGameStatus, $GameStatus_Over;
  return $inGameStatus == $GameStatus_Over;
}

function PlayerWon($playerID)
{
  //NOTE: These globals might appear to be unused. It's because they're written by ParseGamefile.
  global $winner, $turn, $gameName, $p1id, $p2id, $p1uid, $p2uid, $p1IsChallengeActive, $p2IsChallengeActive, $conceded, $currentTurn;
  global $p1DeckLink, $p2DeckLink, $inGameStatus, $GameStatus_Over, $firstPlayer, $p1deckbuilderID, $p2deckbuilderID;
  if ($turn[0] == "OVER") return;
  include_once "./MenuFiles/ParseGamefile.php";
  $winner = $playerID;
  if ($playerID == 1 && $p1uid != "") WriteLog("Player 1 (" . $p1uid . ") won! 🎉", $playerID);
  else if ($playerID == 2 && $p2uid != "") WriteLog("Player 2 (" . $p2uid . ") won! 🎉", $playerID);
  else WriteLog("Player " . $winner . " won! 🎉");
  $inGameStatus = $GameStatus_Over;
  $turn[0] = "OVER";
  SetCachePiece($gameName, 14, 99);//$MGS_GameOver
  try {
    logCompletedGameStats();
  } catch (Exception $e) {
  }
}

function UnsetChainLinkBanish()
{
  $p1Banish = new Banish(1);
  $p1Banish->UnsetModifier("TCL");
  $p2Banish = new Banish(2);
  $p2Banish->UnsetModifier("TCL");
}

function UnsetCombatChainBanish()
{
  $p1Banish = new Banish(1);
  $p1Banish->UnsetModifier("TCL");
  $p1Banish->UnsetModifier("TCC");
  $p1Banish->UnsetModifier("TCCGorgonsGaze");
  $p2Banish = new Banish(2);
  $p2Banish->UnsetModifier("TCL");
  $p2Banish->UnsetModifier("TCC");
  $p2Banish->UnsetModifier("TCCGorgonsGaze");
}

function ReplaceBanishModifier($player, $oldMod, $newMod)
{
  $banish = new Banish($player);
  $banish->UnsetModifier($oldMod, $newMod);
}

function UnsetTurnBanish()
{
  global $defPlayer, $mainPlayer;
  $p1Banish = new Banish(1);
  $p1Banish->UnsetModifier("TT");
  $p1Banish->UnsetModifier("INST");
  $p1Banish->UnsetModifier("ARC119");
  $p1Banish->UnsetModifier("TTFromOtherPlayer");
  $p1Banish->UnsetModifier("MST236");
  $p1Banish->UnsetModifier("REMOVEGRAVEYARD");
  $p2Banish = new Banish(2);
  $p2Banish->UnsetModifier("TT");
  $p2Banish->UnsetModifier("INST");
  $p2Banish->UnsetModifier("ARC119");
  $p2Banish->UnsetModifier("TTFromOtherPlayer");
  $p2Banish->UnsetModifier("MST236");
  $p2Banish->UnsetModifier("REMOVEGRAVEYARD");
  UnsetCombatChainBanish();
  ReplaceBanishModifier($defPlayer, "NT", "TT");
  ReplaceBanishModifier($defPlayer, "NTINT", "INT");
  ReplaceBanishModifier($mainPlayer, "NTFromOtherPlayer", "TTFromOtherPlayer");
}

function GetChainLinkCards($playerID = "", $cardType = "", $exclCardTypes = "", $nameContains = "", $subType = "", $exclCardSubTypes = "")
{
  global $combatChain;
  $pieces = "";
  $exclCardTypeArray = explode(",", $exclCardTypes);
  $exclCardSubTypeArray = explode(",", $exclCardSubTypes);

  for ($i = 0; $i < count($combatChain); $i += CombatChainPieces()) {
    $thisType = CardType($combatChain[$i]);
    $thisSubType = CardSubType($combatChain[$i]);
    if (($playerID == "" || $combatChain[$i + 1] == $playerID) && ($cardType == "" || $thisType == $cardType) && ($subType == "" || $thisSubType == $subType) && ($nameContains == "" || CardNameContains($combatChain[$i], $nameContains, $playerID, partial: true))) {
      $excluded = false;
      for ($j = 0; $j < count($exclCardTypeArray); ++$j) {
        if ($thisType == $exclCardTypeArray[$j]) $excluded = true;
      }
      for ($k = 0; $k < count($exclCardSubTypeArray); ++$k) {
        if ($thisSubType != "" && DelimStringContains($thisSubType, $exclCardSubTypeArray[$k])) $excluded = true;
      }
      if ($excluded) continue;
      if ($pieces != "") $pieces .= ",";
      $pieces .= $i;
    }
  }
  return $pieces;
}

function GetChainLinkCardIDs($playerID = "", $cardType = "", $exclCardTypes = "", $nameContains = "", $subType = "", $exclCardSubTypes = "")
{
  global $combatChain;
  $cardIDs = "";
  $exclCardTypeArray = explode(",", $exclCardTypes);
  $exclCardSubTypeArray = explode(",", $exclCardSubTypes);
  for ($i = 0; $i < count($combatChain); $i += CombatChainPieces()) {
    $thisType = CardType($combatChain[$i]);
    $thisSubType = CardSubType($combatChain[$i]);
    if (($playerID == "" || $combatChain[$i + 1] == $playerID) && ($cardType == "" || $thisType == $cardType) && ($subType == "" || $thisSubType == $subType) && ($nameContains == "" || CardNameContains($combatChain[$i], $nameContains, $playerID, partial: true))) {
      $excluded = false;
      for ($j = 0; $j < count($exclCardTypeArray); ++$j) {
        if ($thisType == $exclCardTypeArray[$j]) $excluded = true;
      }
      for ($k = 0; $k < count($exclCardSubTypeArray); ++$k) {
        if ($thisSubType != "" && DelimStringContains($thisSubType, $exclCardSubTypeArray[$k])) $excluded = true;
      }
      if ($excluded) continue;
      if ($cardIDs != "") $cardIDs .= ",";
      $cardIDs .= $combatChain[$i];
    }
  }
  return $cardIDs;
}

function GetTheirEquipmentChoices()
{
  global $currentPlayer;
  return GetEquipmentIndices(($currentPlayer == 1 ? 2 : 1));
}

function FindMyCharacter($cardID)
{
  global $currentPlayer;
  return FindCharacterIndex($currentPlayer, $cardID);
}

function FindDefCharacter($cardID)
{
  global $defPlayer;
  return FindCharacterIndex($defPlayer, $cardID);
}

function ChainLinkResolvedEffects()
{
  global $combatChain, $mainPlayer, $currentTurnEffects, $combatChainState, $CCS_WeaponIndex, $CombatChain;
  $allies = GetAllies($mainPlayer);
  if ($CombatChain->HasCurrentLink()) {
    if ($combatChain[0] == "MON245" && !ExudeConfidenceReactionsPlayable()) AddCurrentTurnEffect($combatChain[0], $mainPlayer, "CC");
    switch ($combatChain[0]) {
      case "CRU051":
      case "CRU052":
        EvaluateCombatChain($totalAttack, $totalBlock);
        for ($i = CombatChainPieces(); $i < count($combatChain); $i += CombatChainPieces()) {
          if (!($totalBlock > 0 && (intval(BlockValue($combatChain[$i])) + BlockModifier($combatChain[$i], "CC", 0) + $combatChain[$i + 6]) > $totalAttack)) {
            UndestroyCurrentWeapon();
          }
        }
        break;
      default:
        break;
    }
  }
  if (IsAllyAttacking() && isset($allies[$combatChainState[$CCS_WeaponIndex] + 2]) && $allies[$combatChainState[$CCS_WeaponIndex] + 2] <= 0) {
    DestroyAlly($mainPlayer, $combatChainState[$CCS_WeaponIndex]);
  }
}

function CombatChainClosedMainCharacterEffects()
{
  global $chainLinks, $mainPlayer;
  $character = &GetPlayerCharacter($mainPlayer);
  for ($i = 0; $i < count($chainLinks); ++$i) {
    for ($j = 0; $j < count($chainLinks[$i]); $j += ChainLinksPieces()) {
      if ($chainLinks[$i][$j + 1] != $mainPlayer) continue;
      $charIndex = FindCharacterIndex($mainPlayer, $chainLinks[$i][$j]);
      if ($charIndex == -1) continue;
      switch ($chainLinks[$i][$j]) {
        case "CRU051":
        case "CRU052":
          if ($character[$charIndex + 7] == "1") DestroyCharacter($mainPlayer, $charIndex);
          break;
        default:
          break;
      }
    }
  }
}

function CombatChainClosedCharacterEffects()
{
  global $chainLinks, $defPlayer, $chainLinkSummary;
  $character = &GetPlayerCharacter($defPlayer);
  for ($i = 0; $i < count($chainLinks); ++$i) {
    $nervesOfSteelActive = $chainLinkSummary[$i * ChainLinkSummaryPieces() + 1] <= 2 && SearchAuras("EVR023", $defPlayer);
    for ($j = 0; $j < count($chainLinks[$i]); $j += ChainLinksPieces()) {
      if ($chainLinks[$i][$j + 1] != $defPlayer) continue;
      $charIndex = FindCharacterIndex($defPlayer, $chainLinks[$i][$j]);
      if ($charIndex == -1) continue;
      if ($chainLinks[$i][$j] == "MON187") {
        $character[$charIndex + 1] = 0;
        BanishCardForPlayer($chainLinks[$i][$j], $defPlayer, "EQUIP", "NA");
      }
      if (!$nervesOfSteelActive) {
        if (HasTemper($chainLinks[$i][$j])) {
          if ($character[$charIndex + 1] != 0 && $character[$charIndex + 6] != 0) {
            $character[$charIndex + 4] -= 1; //Add -1 block counter
            $character[$charIndex + 6] = 0;
          }
          if ((BlockValue($character[$charIndex]) + $character[$charIndex + 4] + BlockModifier($character[$charIndex], "CC", 0) + $chainLinks[$i][$j + 5]) <= 0) {
            DestroyCharacter($defPlayer, $charIndex);
          }
        } else if (HasBattleworn($chainLinks[$i][$j]) && $character[$charIndex + 1] != 0) $character[$charIndex + 4] -= 1;//Add -1 block counter
      }
      if (HasGuardwell($chainLinks[$i][$j]) && $character[$charIndex + 1] != 0) {
        $character[$charIndex + 4] -= (BlockValue($character[$charIndex]) + $character[$charIndex + 4] + BlockModifier($character[$charIndex], "CC", 0) + $chainLinks[$i][$j + 5]);//Add -block value counter
      } else if (HasBladeBreak($chainLinks[$i][$j]) && $character[$charIndex + 1] != 0) DestroyCharacter($defPlayer, $charIndex);
      switch ($chainLinks[$i][$j]) {
        case "MON089":
          if (!DelimStringContains($chainLinkSummary[$i * ChainLinkSummaryPieces() + 3], "ILLUSIONIST") && $chainLinkSummary[$i * ChainLinkSummaryPieces() + 1] >= 6) {
            $character[FindCharacterIndex($defPlayer, "MON089") + 1] = 0;
          }
          break;
        case "MON241":
        case "MON242":
        case "MON243":
        case "MON244":
          $charIndex = FindCharacterIndex($defPlayer, $chainLinks[$i][$j]);
          if (SearchCurrentTurnEffects($chainLinks[$i][$j], $defPlayer, true)) DestroyCharacter($defPlayer, $charIndex);; //Ironhide
          break;
        case "RVD003":
          $deck = new Deck($defPlayer);
          if ($deck->Reveal() && ModifiedAttackValue($deck->Top(), $defPlayer, "DECK", source: "RVD003") < 6) {
            $card = $deck->AddBottom($deck->Top(remove: true), "DECK");
            WriteLog(CardLink("RVD003", "RVD003") . " put " . CardLink($card, $card) . " on the bottom of your deck");
          }
          break;
        default:
          break;
      }
    }
  }
}

function NumDefendedFromHand()
{
  global $CombatChain, $defPlayer;
  $num = 0;
  for ($i = 0; $i < $CombatChain->NumCardsActiveLink(); ++$i) {
    $chainCard = $CombatChain->Card($i, cardNumber: true);
    if ($chainCard->PlayerID() == $defPlayer) {
      if (CardType($chainCard->ID()) != "I" && $chainCard->From() == "HAND") ++$num;
    }
  }
  return $num;
}

function NumCardsBlocking()
{
  global $CombatChain, $defPlayer;
  $num = 0;
  for ($i = 0; $i < $CombatChain->NumCardsActiveLink(); ++$i) {
    $chainCard = $CombatChain->Card($i, cardNumber: true);
    if ($chainCard->PlayerID() == $defPlayer) {
      $type = CardType($chainCard->ID());
      if ($type != "I" && $type != "C") ++$num;
    }
  }
  return $num;
}

function NumCardsNonEquipBlocking()
{
  global $CombatChain, $defPlayer;
  $num = 0;
  for ($i = 0; $i < $CombatChain->NumCardsActiveLink(); ++$i) {
    $chainCard = $CombatChain->Card($i, cardNumber: true);
    if ($chainCard->PlayerID() == $defPlayer) {
      $type = CardType($chainCard->ID());
      if ($type != "E" && $type != "I" && $type != "C") ++$num;
      if (DelimStringContains(CardSubType($chainCard->ID()), "Evo")) --$num;
    }
  }
  return $num;
}

function NumAttacksBlocking()
{
  global $CombatChain, $defPlayer;
  $num = 0;
  for ($i = 0; $i < $CombatChain->NumCardsActiveLink(); ++$i) {
    $chainCard = $CombatChain->Card($i, cardNumber: true);
    if ($chainCard->PlayerID() == $defPlayer && CardType($chainCard->ID()) == "AA") ++$num;
  }
  return $num;
}

function NumActionsBlocking()
{
  global $CombatChain, $defPlayer;
  $num = 0;
  for ($i = 0; $i < $CombatChain->NumCardsActiveLink(); ++$i) {
    $chainCard = $CombatChain->Card($i, cardNumber: true);
    if ($chainCard->PlayerID() == $defPlayer) {
      $type = CardType($chainCard->ID());
      if ($type == "A" || $type == "AA") ++$num;
      if ($type == "E") {
        if (SubtypeContains($chainCard->ID(), "Evo" && $chainCard->ID() != "EVO410b" && $chainCard->ID() != "DYN492b")) {
          if (CardType(GetCardIDBeforeTransform($chainCard->ID())) == "A") ++$num;
        }
      }
    }
  }
  return $num;
}

function GetCardIDBeforeTransform($cardID)
{
  $cardSet = substr($cardID, 0, 3);
  $originalCardIDNum = (intval(substr($cardID, 3, 3)) - 400);
  if ($originalCardIDNum < 100) return $cardSet . "0" . $originalCardIDNum;
  return $cardSet . $originalCardIDNum;
}

function PlayerHasLessHealth($player)
{
  $otherPlayer = ($player == 1 ? 2 : 1);
  return GetHealth($player) < GetHealth($otherPlayer);
}

function GetIndices($count, $add = 0, $pieces = 1)
{
  $indices = "";
  for ($i = 0; $i < $count; $i += $pieces) {
    if ($indices != "") $indices .= ",";
    $indices .= ($i + $add);
  }
  return $indices;
}

function RollDie($player, $fromDQ = false, $subsequent = false, $reroll = false)
{
  global $CS_DieRoll;
  $otherPlayer = ($player == 1 ? 2 : 1);
  $numRolls = 1 + CountCurrentTurnEffects("EVR003", $player);
  $highRoll = 0;
  for ($i = 0; $i < $numRolls; ++$i) {
    $roll = GetRandom(1, 6);
    WriteLog("🎲<b>" . $roll . "</b> was rolled.");
    if ($roll > $highRoll) $highRoll = $roll;
  }
  AddEvent("ROLL", $highRoll);
  SetClassState($player, $CS_DieRoll, $highRoll);
  $playerHasGamblersGloves = HasGamblersGloves($player);
  $otherPlayerHasGamblersGloves = HasGamblersGloves($otherPlayer);
  if (($playerHasGamblersGloves || $otherPlayerHasGamblersGloves) && !$reroll) {
    if ($fromDQ && !$subsequent) PrependDecisionQueue("AFTERDIEROLL", $player, "-");
    if ($playerHasGamblersGloves) GamblersGlovesReroll($player, $player); // reroll your own
    if ($otherPlayerHasGamblersGloves) GamblersGlovesReroll($otherPlayer, $player); //reroll ops
    if (!$fromDQ && !$subsequent) AddDecisionQueue("AFTERDIEROLL", $player, "-");
  } else {
    AfterDieRoll($player);
  }
}

function AfterDieRoll($player)
{
  global $CS_DieRoll, $CS_HighestRoll;
  $roll = GetClassState($player, $CS_DieRoll);
  $skullCrusherIndex = FindCharacterIndex($player, "EVR001");
  if ($skullCrusherIndex > -1 && IsCharacterAbilityActive($player, $skullCrusherIndex)) {
    if ($roll == 1) {
      WriteLog("Skull Crushers was destroyed");
      DestroyCharacter($player, $skullCrusherIndex);
    }
    if ($roll == 5 || $roll == 6) {
      WriteLog("Skull Crushers gives +1 this turn");
      AddCurrentTurnEffect("EVR001", $player);
    }
  }
  if ($roll > GetClassState($player, $CS_HighestRoll)) SetClassState($player, $CS_HighestRoll, $roll);
}

function HasGamblersGloves($player)
{
  $gamblersGlovesIndex = FindCharacterIndex($player, "CRU179");
  return $gamblersGlovesIndex != -1 && IsCharacterAbilityActive($player, $gamblersGlovesIndex);
}

function IsCharacterAbilityActive($player, $index, $checkGem = false)
{
  $character = &GetPlayerCharacter($player);
  if ($checkGem && $character[$index + 9] == 0) return false;
  if ($character[$index + 12] == "DOWN") return false;
  return $character[$index + 1] == 2;
}

function GetDieRoll($player)
{
  global $CS_DieRoll;
  return GetClassState($player, $CS_DieRoll);
}

function ClearDieRoll($player)
{
  global $CS_DieRoll;
  return SetClassState($player, $CS_DieRoll, 0);
}

function CanPlayAsInstant($cardID, $index = -1, $from = "")
{
  global $currentPlayer, $CS_NextWizardNAAInstant, $CS_NextNAAInstant, $CS_CharacterIndex, $CS_ArcaneDamageTaken, $CS_NumWizardNonAttack;
  global $mainPlayer, $CS_PlayedAsInstant, $CS_NumCharged, $CS_LifeLost, $CS_NumAddedToSoul;
  global $combatChainState;
  $otherPlayer = $currentPlayer == 1 ? 2 : 1;
  $cardType = CardType($cardID);
  $subtype = CardSubType($cardID);
  $otherCharacter = &GetPlayerCharacter($otherPlayer);
  if (CardNameContains($cardID, "Lumina Ascension", $currentPlayer) && SearchItemsForCard("DYN066", $currentPlayer) != "") return true;
  if ($cardType == "A" && GetClassState($currentPlayer, $CS_NextWizardNAAInstant) && ClassContains($cardID, "WIZARD", $currentPlayer)) return true;
  if (GetClassState($currentPlayer, $CS_NumWizardNonAttack) && ($cardID == "CRU174" || $cardID == "CRU175" || $cardID == "CRU176")) return true;
  if ($currentPlayer != $mainPlayer && ($cardID == "CRU165" || $cardID == "CRU166" || $cardID == "CRU167")) return true;
  if ($cardType == "A" && GetClassState($currentPlayer, $CS_NextNAAInstant)) return true;
  if ($cardType == "C" || $cardType == "E" || $cardType == "W") {
    if ($index == -1) $index = GetClassState($currentPlayer, $CS_CharacterIndex);
    if (SearchCharacterEffects($currentPlayer, $index, "INSTANT")) return true;
  }
  if ($from == "BANISH") {
    $banish = GetBanish($currentPlayer);
    if ($index < count($banish)) {
      $mod = explode("-", $banish[$index + 1])[0];
      if (($cardType == "I" && ($mod == "TCL" || $mod == "TT" || $mod == "TCC" || $mod == "NT" || $mod == "MON212" || $mod == "MST236")) || $mod == "INST" || $mod == "ARC119") return true;
    }
  }
  if (GetClassState($currentPlayer, $CS_PlayedAsInstant) == "1") return true;
  if ($cardID == "ELE106" || $cardID == "ELE107" || $cardID == "ELE108") {
    return PlayerHasFused($currentPlayer);
  } else if ($cardID == "DTD085" || $cardID == "DTD086" || $cardID == "DTD087") {
    return GetClassState($currentPlayer, $CS_NumAddedToSoul);
  } else if ($cardID == "DTD088" || $cardID == "DTD089" || $cardID == "DTD090") {
    return GetClassState($currentPlayer, $CS_NumAddedToSoul);
  } else if ($cardID == "CRU143") {
    return GetClassState($otherPlayer, $CS_ArcaneDamageTaken) > 0;
  } else if ($cardID == "DTD140") return GetClassState($currentPlayer, $CS_LifeLost) > 0 || GetClassState($otherPlayer, $CS_LifeLost) > 0;
  else if ($cardID == "DTD141") return GetClassState($currentPlayer, $CS_LifeLost) > 0 || GetClassState($otherPlayer, $CS_LifeLost) > 0;
  if (SearchCurrentTurnEffects("MST027", $currentPlayer) && SubtypeContains($cardID, "Aura", $currentPlayer) && $from != "PLAY") return true;
  if (SubtypeContains($cardID, "Evo")) {
    if (SearchCurrentTurnEffects("EVO007", $currentPlayer) || SearchCurrentTurnEffects("EVO008", $currentPlayer)) return true;
    if (SearchCurrentTurnEffects("EVO129", $currentPlayer) || SearchCurrentTurnEffects("EVO130", $currentPlayer) || SearchCurrentTurnEffects("EVO131", $currentPlayer)) return true;
  }
  if ($from == "ARS" && $cardType == "A" && $currentPlayer != $mainPlayer && ColorContains($cardID, 3, $currentPlayer) && (SearchCharacterActive($currentPlayer, "EVR120") || SearchCharacterActive($currentPlayer, "UPR102") || SearchCharacterActive($currentPlayer, "UPR103") || (SearchCharacterActive($currentPlayer, "CRU097") && SearchCurrentTurnEffects($otherCharacter[0] . "-SHIYANA", $currentPlayer) && IsIyslander($otherCharacter[0])))) return true;
  if (ClassContains($cardID, "ILLUSIONIST", $currentPlayer) && DelimStringContains($subtype, "Aura") && SearchCurrentTurnEffects("MST155-INST", $currentPlayer) && CardCost($cardID) <= 2) return true;
  if (ClassContains($cardID, "ILLUSIONIST", $currentPlayer) && DelimStringContains($subtype, "Aura") && SearchCurrentTurnEffects("MST156-INST", $currentPlayer) && CardCost($cardID) <= 1) return true;
  if (ClassContains($cardID, "ILLUSIONIST", $currentPlayer) && DelimStringContains($subtype, "Aura") && SearchCurrentTurnEffects("MST157-INST", $currentPlayer) && CardCost($cardID) <= 0) return true;
  if (DelimStringContains($subtype, "Aura") && SearchCurrentTurnEffects("ROS251", $currentPlayer)) return true;
  $isStaticType = IsStaticType($cardType, $from, $cardID);
  $abilityType = "-";
  if ($isStaticType) $abilityType = GetAbilityType($cardID, $index, $from);
  if (($cardType == "AR" || ($abilityType == "AR" && $isStaticType)) && IsReactionPhase() && $currentPlayer == $mainPlayer) return true;
  if (($cardType == "DR" || ($abilityType == "DR" && $isStaticType)) && IsReactionPhase() && $currentPlayer != $mainPlayer && IsDefenseReactionPlayable($cardID, $from)) return true;
  if ($from == "DECK" && (SearchCharacterActive($currentPlayer, "EVO001") || SearchCharacterActive($currentPlayer, "EVO002"))) return true;
  switch ($cardID) {
    case "HVY143":
    case "HVY144":
    case "HVY145":
    case "HVY163":
    case "HVY164":
    case "HVY165":
    case "HVY186":
    case "HVY187":
    case "HVY188":
    case "ROS206":
    case "ROS205":
    case "ROS204":
    case "ROS188":
    case "ROS187":
    case "ROS186":
    case "ROS106":
    case "ROS105":
    case "ROS104":
    case "ROS057":
    case "ROS056":
    case "ROS055":
    case "HVY209":
    case "ROS120":
    case "ROS169":
      return $from == "HAND";
    case "MST134":
    case "MST135":
    case "MST136":
      return SearchAuras("MON104", $currentPlayer);
    case "ROS119":
      return GetClassState($otherPlayer, $CS_ArcaneDamageTaken) > 0;
    default:
      break;
  }
  return false;
}

function ClassOverride($cardID, $player = "")
{
  global $currentTurnEffects;
  $cardClass = "";
  $otherPlayer = ($player == 1 ? 2 : 1);
  $otherCharacter = &GetPlayerCharacter($otherPlayer);
  $mainCharacter = &GetPlayerCharacter($player);

  // With the rules as of today it's correct. HVY Release Notes Disclaimer. CR2.6 - 6.3.6. Continuous effects that remove a property, or part of a property, from an object do not removeproperties, or parts of properties, that were added by another effect.
  if (HasUniversal($cardID)) { //Universal
    $cardClass = CardClass($mainCharacter[0]);
  }
  if (SearchCurrentTurnEffects("DYN215-" . str_replace(' ', '_', CardName($cardID)), $player)) { //Phantasmal Symbiosis
    if ($cardClass != "") $cardClass .= ",";
    $cardClass .= "ILLUSIONIST";
  }
  for ($i = 0; $i < count($currentTurnEffects); $i += CurrentTurnEffectPieces()) {
    if (!isset($currentTurnEffects[$i + 1])) continue;
    if ($currentTurnEffects[$i + 1] != $player) continue;
    $classToAdd = "";
    switch ($currentTurnEffects[$i]) {
      case "MON095":
      case "MON096":
      case "MON097":
        $classToAdd = "ILLUSIONIST";
        break; //Phantasmify
      case "EVR150":
      case "EVR151":
      case "EVR152":
        $classToAdd = "ILLUSIONIST";
        break; //Veiled Intentions
      case "UPR155":
      case "UPR156":
      case "UPR157":
        $classToAdd = "ILLUSIONIST";
        break; //Transmogrify
      default:
        break;
    }
    if ($classToAdd != "") {
      if ($cardClass != "") $cardClass .= ",";
      $cardClass .= $classToAdd;
    }
  }
  if (SearchCurrentTurnEffects($otherCharacter[0] . "-SHIYANA", $player)) { //Shiyana
    if ($cardClass != "") $cardClass .= ",";
    $cardClass .= CardClass($otherCharacter[0]) . ",SHAPESHIFTER";
  }
  if (!SearchCurrentTurnEffects("UPR187", $player)) { //Erase Face
    if ($cardClass != "") $cardClass .= ",";
    $cardClass .= CardClass($cardID);
  }
  if ($cardClass == "") return "NONE";
  return $cardClass;
}

function NameOverride($cardID, $player = "")
{
  $name = CardName($cardID);
  if (SearchCurrentTurnEffects("OUT183", $player)) $name = "";
  return $name;
}

function ColorOverride($cardID, $player = "")
{
  $pitch = PitchValue($cardID);
  if (SearchCurrentTurnEffects("MST194", $player)) $pitch = 0;
  if (SearchCurrentTurnEffects("MST195", $player)) $pitch = 0;
  if (SearchCurrentTurnEffects("MST196", $player)) $pitch = 0;
  return $pitch;
}

function ClassContains($cardID, $class, $player = "")
{
  $cardClass = ClassOverride($cardID, $player);
  return DelimStringContains($cardClass, $class);
}

function ColorContains($cardID, $color, $player = "")
{
  $cardColor = ColorOverride($cardID, $player);
  return DelimStringContains($cardColor, $color);
}

function TypeContains($cardID, $type, $player = "")
{
  $cardType = CardType($cardID);
  return DelimStringContains($cardType, $type);
}

function SubtypeContains($cardID, $subtype, $player = "", $uniqueID = "")
{
  global $currentTurnEffects;
  $cardSubtype = CardSubtype($cardID);
  if ($cardID == "EVO013") {
    for ($i = 0; $i < count($currentTurnEffects); $i += CurrentTurnPieces()) {
      $effect = explode(",", $currentTurnEffects[$i]);
      if ($effect[0] == "EVO013-" . $uniqueID) return $effect[1];
    }
  }
  if ($cardID == "ROS246") {
    for ($i = 0; $i < count($currentTurnEffects); $i += CurrentTurnPieces()) {
      $effect = explode(",", $currentTurnEffects[$i]);
      if ($effect[0] == "ROS246-" . $uniqueID) return $effect[1];
    }
  }
  return DelimStringContains($cardSubtype, $subtype);
}

function CardNameContains($cardID, $name, $player = "", $partial = false) // This isn't actually a contains operation. It's an equals unless you turn partial to true.
{
  $cardName = NameOverride($cardID, $player);
  if ($partial) {
    $cardName = explode(" ", $cardName);
    $cardName = implode(",", $cardName);
  }
  if ($cardName == $name) return true; //Card is breaking due to comma
  return DelimStringContains($cardName, $name, $partial);
}

function TalentOverride($cardID, $player = "")
{
  global $currentTurnEffects;
  $cardTalent = "";
  for ($i = 0; $i < count($currentTurnEffects); $i += CurrentTurnEffectPieces()) {
    $talentToAdd = "";
    if (!isset($currentTurnEffects[$i + 1])) continue;
    if ($currentTurnEffects[$i + 1] != $player) continue;
    switch ($currentTurnEffects[$i]) {
      case "UPR060":
      case "UPR061":
      case "UPR062":
        $talentToAdd = "DRACONIC"; //Brand of Cinderclaw
      default:
        break;
    }
    if ($talentToAdd != "") {
      if ($cardTalent == "NONE") $cardTalent = "";
      if ($cardTalent != "") $cardTalent .= ",";
      $cardTalent .= $talentToAdd;
    }
  }
  if (!SearchCurrentTurnEffects("UPR187", $player)) { //Erase Face
    if ($cardTalent != "") $cardTalent .= ",";
    $cardTalent .= CardTalent($cardID);
  }
  if ($cardTalent == "") return "NONE";
  return $cardTalent;
}

function TalentContains($cardID, $talent, $player = "")
{
  $cardTalent = TalentOverride($cardID, $player);
  return DelimStringContains($cardTalent, $talent);
}

//talents = comma delimited list of talents to check
function TalentContainsAny($cardID, $talents, $player = "")
{
  $cardTalent = TalentOverride($cardID, $player);
  //Loop over current turn effects to find modifiers
  $talentArr = explode(",", $talents);
  for ($i = 0; $i < count($talentArr); ++$i) {
    if (DelimStringContains($cardTalent, $talentArr[$i])) return true;
  }
  return false;
}

function RevealCards($cards, $player = "")
{
  global $currentPlayer;
  if ($player == "") $player = $currentPlayer;
  if (!CanRevealCards($player)) return false;
  if ($cards == "") return true;
  $cardArray = explode(",", $cards);
  $string = "";
  for ($i = 0; $i < count($cardArray); ++$i) {
    if ($string != "") $string .= ", ";
    $string .= CardLink($cardArray[$i], $cardArray[$i]);
    AddEvent("REVEAL", $cardArray[$i]);
  }
  $string .= (count($cardArray) == 1 ? " is" : " are");
  $string .= " revealed";
  WriteLog($string);
  if ($player != "" && SearchLandmark("ELE000")) {
    KorshemRevealAbility($player);
  }
  return true;
}

function DoesAttackHaveGoAgain()
{
  global $CombatChain, $combatChainState, $CCS_CurrentAttackGainedGoAgain, $mainPlayer, $defPlayer, $CS_Num6PowDisc;
  global $CS_NumAuras, $CS_ArcaneDamageTaken, $CS_AnotherWeaponGainedGoAgain, $CS_NumRedPlayed, $CS_NumNonAttackCards;
  global $CS_NumItemsDestroyed, $CS_PlayIndex, $CCS_WeaponIndex, $CS_NumCharged, $CS_NumCardsDrawn, $CS_Transcended;
  global $CS_NumLightningPlayed, $CS_DamageTaken, $CCS_NumInstantsPlayedByAttackingPlayer, $CS_ActionsPlayed;
  if (!$CombatChain->HasCurrentLink()) return false;
  $attackID = $CombatChain->AttackCard()->ID();
  $attackType = CardType($attackID);
  $attackSubtype = CardSubType($attackID);
  $isAura = DelimStringContains(CardSubtype($attackID), "Aura");
  //Prevention Natural Go Again
  if (CurrentEffectPreventsGoAgain()) return false;
  if (SearchCurrentTurnEffects("ELE147", $mainPlayer)) return false;

  //Natural Go Again
  if (!$isAura && HasGoAgain($attackID)) return true;

  //Prevention Grant Go Again
  if (SearchAuras("UPR139", $mainPlayer)) return false;

  //Grant go Again
  $auras = &GetAuras($mainPlayer);
  $actionsPlayed = explode(",", GetClassState($mainPlayer, $CS_ActionsPlayed));
  $numActions = count($actionsPlayed);
  if (ClassContains($attackID, "ILLUSIONIST", $mainPlayer)) {
    if (SearchCharacterForCard($mainPlayer, "MON003") && SearchPitchForColor($mainPlayer, 2) > 0) return true;
    if ($isAura && SearchCharacterForCard($mainPlayer, "MON088")) return true;
  }
  if ($isAura && SearchCharacterForCard($mainPlayer, "MST130") && isset($auras[$combatChainState[$CCS_WeaponIndex] + 3]) ? $auras[$combatChainState[$CCS_WeaponIndex] + 3] > 0 : false) return true;
  if ($combatChainState[$CCS_CurrentAttackGainedGoAgain] == 1 || CurrentEffectGrantsGoAgain() || MainCharacterGrantsGoAgain()) {
    $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 1;
    return true;
  }
  if ($attackType == "AA" && ClassContains($attackID, "ILLUSIONIST", $mainPlayer) && SearchAuras("MON013", $mainPlayer)) return true;
  if (DelimStringContains($attackSubtype, "Dragon") && GetClassState($mainPlayer, $CS_NumRedPlayed) > 0 && (SearchCharacterActive($mainPlayer, "UPR001") || SearchCharacterActive($mainPlayer, "UPR002") || SearchCurrentTurnEffects("UPR001-SHIYANA", $mainPlayer) || SearchCurrentTurnEffects("UPR002-SHIYANA", $mainPlayer))) return true;
  if (SearchItemsForCard("EVO097", $mainPlayer) != "" && $attackType == "AA" && ClassContains($CombatChain->AttackCard()->ID(), "MECHANOLOGIST", $mainPlayer)) return true;
  if (SearchCurrentTurnEffectsForCycle("HVY127", "HVY128", "HVY129", $mainPlayer) && ClassContains($CombatChain->AttackCard()->ID(), "WARRIOR", $mainPlayer) && NumAttacksBlocking() > 0) return true;
  if (SearchCurrentTurnEffects("MST094", $mainPlayer) && PitchValue($CombatChain->AttackCard()->ID()) == 3 && $CombatChain->AttackCard()->From() != "PLAY") return true;
  //the last action in numActions is going to be the current chain link
  //so we want the second to last to be current funnel, and 3rd to last to be lightning
  if (count($actionsPlayed) > 2 && $actionsPlayed[$numActions-2] == "ROS074" && TalentContains($actionsPlayed[$numActions-3], "LIGHTNING")) return true;
  $mainPitch = &GetPitch($mainPlayer);
  switch ($attackID) {
    case "WTR078":
      return SearchCount(SearchPitch($mainPlayer, minCost: 0, maxCost: 0)) > 0;
    case "WTR083":
    case "WTR084":
      return ComboActive($attackID);
    case "WTR095":
    case "WTR096":
    case "WTR097":
      return ComboActive($attackID);
    case "WTR104":
    case "WTR105":
    case "WTR106":
      return ComboActive($attackID);
    case "WTR110":
    case "WTR111":
    case "WTR112":
      return ComboActive($attackID);
    case "WTR161":
      $deck = new Deck($mainPlayer);
      return $deck->Empty();
    case "ARC197":
    case "ARC198":
    case "ARC199":
      return GetClassState($mainPlayer, $CS_NumNonAttackCards) > 0;
    case "CRU004":
    case "CRU005":
      return GetClassState($mainPlayer, $CS_Num6PowDisc) > 0;
    case "CRU010":
    case "CRU011":
    case "CRU012":
      if (NumCardsNonEquipBlocking() < 2) return true;
    case "CRU057":
    case "CRU058":
    case "CRU059":
      return ComboActive($attackID);
    case "CRU060":
    case "CRU061":
    case "CRU062":
      return ComboActive($attackID);
    case "CRU151":
    case "CRU152":
    case "CRU153":
      return GetClassState($defPlayer, $CS_ArcaneDamageTaken) > 0;
    case "MON036":
    case "MON037":
    case "MON038":
      return GetClassState($mainPlayer, $CS_NumCharged) > 0;
    case "MON054":
    case "MON055":
    case "MON056":
      return GetClassState($mainPlayer, $CS_NumCharged) > 0;
    case "MON180":
    case "MON181":
    case "MON182":
      return GetClassState($defPlayer, $CS_ArcaneDamageTaken) > 0;
    case "MON199":
    case "MON220":
      return (count(GetSoul($defPlayer)) > 0 && !IsAllyAttackTarget());
    case "MON223":
    case "MON224":
    case "MON225":
      return NumCardsNonEquipBlocking() < 2;
    case "MON248":
    case "MON249":
    case "MON250":
      return SearchHighestAttackDefended() < CachedTotalAttack();
    case "MON293":
    case "MON294":
    case "MON295":
      return SearchPitchHighestAttack($mainPitch) > AttackValue($attackID);
    case "ELE216":
    case "ELE217":
    case "ELE218":
      return CachedTotalAttack() > AttackValue($attackID);
    case "EVR105":
      return GetClassState($mainPlayer, $CS_NumAuras) > 0;
    case "EVR138":
      return FractalReplicationStats("GoAgain");
    case "UPR046":
      return NumDraconicChainLinks() >= 2;
    case "UPR063":
    case "UPR064":
    case "UPR065":
      return NumDraconicChainLinks() >= 2;
    case "UPR069":
    case "UPR070":
    case "UPR071":
      return NumDraconicChainLinks() >= 2;
    case "UPR048":
      return NumChainLinksWithName("Phoenix Flame") >= 1;
    case "UPR092":
      return GetClassState($mainPlayer, $CS_NumRedPlayed) > 1;
    case "DYN047":
      return ComboActive($attackID);
    case "DYN056":
    case "DYN057":
    case "DYN058":
      return (ComboActive($attackID));
    case "DYN069":
    case "DYN070":
      return GetClassState($mainPlayer, $CS_AnotherWeaponGainedGoAgain) != "-";
    case "EVO009":
      return EvoUpgradeAmount($mainPlayer) >= 3;
    case "EVO111":
    case "EVO112":
    case "EVO113":
      return GetClassState($mainPlayer, $CS_NumItemsDestroyed) > 0;
    case "HVY095":
      $character = &GetPlayerCharacter($mainPlayer);
      return SearchCurrentTurnEffectsForUniqueID($character[$combatChainState[$CCS_WeaponIndex] + 11]) != -1 && SearchCurrentTurnEffects($attackID, $mainPlayer);
    case "HVY134":
      return true;
    case "MST083":
      return GetClassState($mainPlayer, $CS_Transcended) > 0;
    case "MST161":
      return ComboActive($attackID);
    case "MST164":
    case "MST165":
    case "MST166":
      return ComboActive($attackID);
    case "HVY166":
    case "HVY167":
    case "HVY168":
      return GetClassState($mainPlayer, $CS_NumCardsDrawn) > 0;
    case "MST176":
    case "MST177":
    case "MST178":
      return ComboActive($attackID);
    case "AUR011":
    case "AUR024":
    case "ROS009":
      return GetClassState($mainPlayer, $CS_NumLightningPlayed) > 0;
    case "ROS074":
      //the last action in numActions is going to be the current chain link
      //so we want the second to last
      return count($actionsPlayed) > 1 && TalentContains($actionsPlayed[$numActions-2], "LIGHTNING");
    case "ROS089":
    case "ROS090":
    case "ROS091":
      if (isset($combatChainState[$CCS_NumInstantsPlayedByAttackingPlayer])) { // the first time this is checked in a chain it isn't set but the rest of the time it can be checked.
        return $combatChainState[$CCS_NumInstantsPlayedByAttackingPlayer] > 0;
      } else return false;
    case "ROS101":
    case "ROS102":
    case "ROS103":
      return GetClassState($defPlayer, $CS_DamageTaken) > 0;
    case "ROS149":
    case "ROS150":
    case "ROS151":
      return GetClassState($mainPlayer, $CS_NumAuras) > 0;
    case "ROS245":
      return ComboActive($attackID);
    default:
      return false;
  }
}

function UndestroyCurrentWeapon()
{
  global $combatChainState, $CCS_WeaponIndex, $mainPlayer;
  $index = $combatChainState[$CCS_WeaponIndex];
  $char = &GetPlayerCharacter($mainPlayer);
  $char[$index + 7] = "0";
}

function DestroyCurrentWeapon()
{
  global $combatChainState, $CCS_WeaponIndex, $mainPlayer;
  $index = $combatChainState[$CCS_WeaponIndex];
  $char = &GetPlayerCharacter($mainPlayer);
  $char[$index + 7] = "1";
}

function AttackDestroyed($attackID)
{
  global $mainPlayer, $combatChainState, $CCS_GoesWhereAfterLinkResolves;
  switch ($attackID) {
    case "EVR139":
      MirragingMetamorphDestroyed();
      break;
    case "EVR144":
    case "EVR145":
    case "EVR146":
      CoalescentMirageDestroyed();
      break;
    case "EVR147":
    case "EVR148":
    case "EVR149":
      PlayAura("MON104", $mainPlayer);
      break;
    case "UPR021":
    case "UPR022":
    case "UPR023":
      PutPermanentIntoPlay($mainPlayer, "UPR043");
      break;
    case "UPR027":
    case "UPR028":
    case "UPR029":
      PutPermanentIntoPlay($mainPlayer, "UPR043");
      break;
    default:
      break;
  }
  AttackDestroyedEffects($attackID);
  CharacterAttackDestroyedAbilities($attackID);
  $numMercifulRetribution = SearchCount(SearchAurasForCard("MON012", $mainPlayer));
  if ($numMercifulRetribution > 0 && TalentContains($attackID, "LIGHT", $mainPlayer)) {
    AddDecisionQueue("PASSPARAMETER", $mainPlayer, $attackID);
    AddDecisionQueue("ADDSOUL", $mainPlayer, "CC");
    $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "-";
  }
  for ($i = 0; $i < $numMercifulRetribution; ++$i) {
    AddDecisionQueue("ADDTRIGGER", $mainPlayer, "MON012," . $attackID);
  }
}

function AttackDestroyedEffects($attackID)
{
  global $currentTurnEffects, $mainPlayer;
  for ($i = 0; $i < count($currentTurnEffects); $i += CurrentTurnEffectPieces()) {
    switch ($currentTurnEffects[$i]) {
      case "EVR150":
      case "EVR151":
      case "EVR152":
        Draw($mainPlayer);
        break;
      default:
        break;
    }
  }
}

function CloseCombatChain($chainClosed = "true")
{
  global $turn, $currentPlayer, $mainPlayer, $combatChainState, $CCS_AttackTarget, $layers;
  if (count($layers) <= LayerPieces() && isPriorityStep($layers[0])) $layers = [];//In case there's another combat chain related layer like defense step
  PrependLayer("FINALIZECHAINLINK", $mainPlayer, $chainClosed);
  $turn[0] = "M";
  $currentPlayer = $mainPlayer;
  $combatChainState[$CCS_AttackTarget] = "NA";
}

function UndestroyCharacter($player, $index)
{
  $char = &GetPlayerCharacter($player);
  $char[$index + 1] = 2;
  $char[$index + 4] = 0;
}

function DestroyCharacter($player, $index, $skipDestroy = false, $wasBanished = false)
{
  if ($index == -1) return "";
  global $CombatChain;
  $char = &GetPlayerCharacter($player);
  $char[$index + 1] = 0;
  $char[$index + 2] = 0;
  $char[$index + 3] = 0;
  $char[$index + 4] = 0;
  $cardID = $char[$index];
  if ($char[$index + 6] == 1) $CombatChain->Remove(GetCombatChainIndex($cardID, $player));
  $char[$index + 6] = 0;
  if (!isSubcardEmpty($char, $index)) {
    $subcards = explode(',', $char[$index + 10]);
    $subcardsCount = count($subcards);
    for ($i = 0; $i < $subcardsCount; $i++) AddGraveyard($subcards[$i], $player, "CHAR");
  }
  $char[$index + 10] = "-";
  if (!$skipDestroy) {
    if (HasWard($cardID, $player)) WardPoppedAbility($player, $char[$index]);
    if (HasWard($cardID, $player) && ClassContains($cardID, "ILLUSIONIST", $player)) PhantomTidemawDestroy($player);
    if (!$wasBanished) AddGraveyard($cardID, $player, "CHAR");
    CharacterDestroyEffect($cardID, $player);
  }
  return $cardID;
}

function RemoveCharacter($player, $index)
{
  if ($index == -1) return "";
  $char = &GetPlayerCharacter($player);
  for ($i = 0; $i < CharacterPieces(); ++$i) {
    unset($char[$index + $i]);
  }
}

function RemoveCharacterAndAddAsSubcardToCharacter($player, $index, &$newCharactersSubcardIndex)
{
  global $CombatChain;
  $char = &GetPlayerCharacter($player);
  $cardID = $char[$index];
  if ($char[0] == "EVO410") AddSoul($cardID, $player, "-");
  if ($char[$index + 6] == 1) $CombatChain->Remove(GetCombatChainIndex($cardID, $player));
  if (!isSubcardEmpty($char, $index)) {
    $subcards = explode(',', $char[$index + 10]);
    $subcardsCount = count($subcards);
    for ($i = 0; $i < $subcardsCount; $i++) {
      if ($char[0] == "EVO410") AddSoul($subcards[$i], $player, "-");
      if (isSubcardEmpty($char, $newCharactersSubcardIndex)) $char[$newCharactersSubcardIndex + 10] = $subcards[$i];
      else $char[$newCharactersSubcardIndex + 10] = $char[$newCharactersSubcardIndex + 10] . "," . $subcards[$i];
    }
  }
  CharacterDestroyEffect($cardID, $player);
  if (isSubcardEmpty($char, $newCharactersSubcardIndex)) $char[$newCharactersSubcardIndex + 10] = $cardID;
  else $char[$newCharactersSubcardIndex + 10] = $char[$newCharactersSubcardIndex + 10] . "," . $cardID;
  $characterPieces = CharacterPieces();
  if ($newCharactersSubcardIndex > $index) $newCharactersSubcardIndex -= $characterPieces;
  for ($i = 0; $i < $characterPieces; $i++) array_splice($char, $index, 1);
  UpdateSubcardCounterCount($player, $newCharactersSubcardIndex);
}

function RemoveItemAndAddAsSubcardToCharacter($player, $itemIndex, $newCharactersSubcardIndex)
{
  $items = &GetItems($player);
  $char = &GetPlayerCharacter($player);
  $itemPieces = ItemPieces();
  $cardID = $items[$itemIndex];
  if (isSubcardEmpty($char, $newCharactersSubcardIndex)) $char[$newCharactersSubcardIndex + 10] = $cardID;
  else $char[$newCharactersSubcardIndex + 10] = $char[$newCharactersSubcardIndex + 10] . "," . $cardID;
  for ($i = 0; $i < $itemPieces; $i++) array_splice($items, $itemIndex, 1);
  if ($char[$newCharactersSubcardIndex] == "DYN492a") UpdateSubcardCounterCount($player, $newCharactersSubcardIndex);
}

function UpdateSubcardCounterCount($player, $index)
{
  $char = &GetPlayerCharacter($player);

  if (empty($char[$index + 10])) $char[$index + 2] = 0;
  else $char[$index + 2] = count(explode(",", $char[$index + 10]));
}

function RemoveArsenalEffects($player, $cardToReturn, $uniqueID)
{
  if ($uniqueID == SearchCurrentTurnEffects("EVR087", $player, returnUniqueID: true)) SearchCurrentTurnEffects("EVR087", $player, true);
  if ($uniqueID == SearchCurrentTurnEffects("ARC042", $player, returnUniqueID: true)) SearchCurrentTurnEffects("ARC042", $player, true);
  if ($cardToReturn == "ARC057") {
    SearchCurrentTurnEffects("ARC057", $player, true);
  }
  if ($cardToReturn == "ARC058") {
    SearchCurrentTurnEffects("ARC058", $player, true);
  }
  if ($cardToReturn == "ARC059") {
    SearchCurrentTurnEffects("ARC059", $player, true);
  }
}

function LookAtHand($player)
{
  $hand = &GetHand($player);
  $cards = "";
  for ($i = 0; $i < count($hand); $i += HandPieces()) {
    if ($cards != "") $cards .= ",";
    $cards .= $hand[$i];
  }
  RevealCards($cards, $player);
}

function LookAtArsenal($player)
{
  $arsenal = &GetArsenal($player);
  for ($i = 0; $i < count($arsenal); $i += ArsenalPieces()) {
    if ($arsenal[$i + 1] == "DOWN") {
      RevealCards($arsenal[$i], $player);
    }
  }
}

function GainActionPoints($amount = 1, $player = 0)
{
  global $actionPoints, $mainPlayer, $currentPlayer;
  if ($player == 0) $player = $currentPlayer;
  if ($player == $mainPlayer) $actionPoints += $amount;
}

function AddCharacterUses($player, $index, $numToAdd)
{
  $character = &GetPlayerCharacter($player);
  if ($character[$index + 1] == 0) return;
  $character[$index + 1] = 2;
  $character[$index + 5] += $numToAdd;
}

function HaveUnblockedEquip($player)
{
  $restriction = ""; // This just needs to exist cause IsBlockRestricted uses a reference op on it.
  $char = &GetPlayerCharacter($player);
  for ($i = CharacterPieces(); $i < count($char); $i += CharacterPieces()) {
    if ($char[$i + 1] == 0) continue;//If broken
    if ($char[$i + 6] == 1) continue;//On combat chain
    if ($char[$i + 12] == "DOWN") continue; //Face-down
    if (!TypeContains($char[$i], "E", $player)) continue;
    if (BlockValue($char[$i]) == -1) continue;
    if (IsBlockRestricted($char[$i], $restriction, $player)) continue; //If restricted via something like stasis cell
    return true;
  }
  return false;
}

function NumEquipBlock()
{
  global $combatChain, $defPlayer, $combatChainState, $CCS_RequiredEquipmentBlock;
  $numEquipBlock = 0;
  for ($i = CombatChainPieces(); $i < count($combatChain); $i += CombatChainPieces()) {
    if (DelimStringContains(CardSubType($combatChain[$i]), "Evo") && $combatChain[$i + 1] == $defPlayer && $combatChainState[$CCS_RequiredEquipmentBlock] < 1) ++$numEquipBlock; // Working, but technically wrong until we get CardTypeContains
    else if (TypeContains($combatChain[$i], "E", $defPlayer) && $combatChain[$i + 1] == $defPlayer) ++$numEquipBlock;
  }
  return $numEquipBlock;
}

function HaveUnblockedNegCounterEquip($player)
{
  $char = &GetPlayerCharacter($player);
  for ($i = CharacterPieces(); $i < count($char); $i += CharacterPieces()) {
    if ($char[$i + 1] == 0) continue;//If broken
    if ($char[$i + 4] == 0) continue;//No negative counters
    if ($char[$i + 6] == 1) continue;//On combat chain
    if ($char[$i + 12] == "DOWN") continue; //Face-down
    if (!TypeContains($char[$i], "E", $player)) continue;
    if (BlockValue($char[$i]) == -1) continue;
    return true;
  }
  return false;
}

function NumNegCounterEquipBlock()
{
  global $combatChain, $defPlayer, $combatChainState, $CCS_RequiredNegCounterEquipmentBlock;
  $numNegCounterEquipBlock = 0;
  for ($i = CombatChainPieces(); $i < count($combatChain); $i += CombatChainPieces()) {
    if (DelimStringContains(CardSubType($combatChain[$i]), "Evo") && $combatChain[$i + 1] == $defPlayer && $combatChain[$i + 4] < 0 && $combatChainState[$CCS_RequiredNegCounterEquipmentBlock] < 1) ++$numNegCounterEquipBlock;
    else if (TypeContains($combatChain[$i], "E", $defPlayer) && $combatChain[$i + 1] == $defPlayer && $combatChain[$i + 4] < 0) ++$numNegCounterEquipBlock;
  }
  return $numNegCounterEquipBlock;
}

function CanPassPhase($phase)
{
  global $combatChainState, $CCS_RequiredEquipmentBlock, $currentPlayer, $CCS_RequiredNegCounterEquipmentBlock;
  if ($phase == "B" && HaveUnblockedEquip($currentPlayer) && NumEquipBlock() < $combatChainState[$CCS_RequiredEquipmentBlock]) return false;
  if ($phase == "B" && HaveUnblockedNegCounterEquip($currentPlayer) && NumNegCounterEquipBlock() < $combatChainState[$CCS_RequiredNegCounterEquipmentBlock]) return false;
  switch ($phase) {
    case "P":
      return 0;
    case "PDECK":
      return 0;
    case "CHOOSEDECK":
      return 0;
    case "CHOOSETHEIRDECK":
      return 0;
    case "HANDTOPBOTTOM":
      return 0;
    case "CHOOSECOMBATCHAIN":
      return 0;
    case "CHOOSECHARACTER":
      return 0;
    case "CHOOSEHAND":
      return 0;
    case "CHOOSEHANDCANCEL":
      return 0;
    case "MULTICHOOSEDISCARD":
      return 0;
    case "CHOOSEDISCARDCANCEL":
      return 0;
    case "CHOOSEARCANE":
      return 0;
    case "CHOOSEARSENAL":
      return 0;
    case "CHOOSEDISCARD":
      return 0;
    case "MULTICHOOSEHAND":
      return 0;
    case "CHOOSEMULTIZONE":
      return 0;
    case "CHOOSEBANISH":
      return 0;
    case "MULTICHOOSEBANISH":
      return 0;
    case "BUTTONINPUTNOPASS":
      return 0;
    case "CHOOSEFIRSTPLAYER":
      return 0;
    case "MULTICHOOSEDECK":
      return 0;
    case "CHOOSEPERMANENT":
      return 0;
    case "MULTICHOOSETEXT":
      return 0;
    case "CHOOSEMYSOUL":
      return 0;
    case "CHOOSEMYAURA":
      return 0;
    case "CHOOSECARDID":
      return 0;
    case "OVER":
      return 0;
    case "BUTTONINPUT":
      return 0;
    default:
      return 1;
  }
}

//Returns true if done for that player
function EndTurnPitchHandling($player)
{
  global $currentPlayer, $turn;
  $pitch = &GetPitch($player);
  if (count($pitch) == 0) return true;
  else if (count($pitch) == 1) {
    PitchDeck($player, 0);
    return true;
  } else {
    $currentPlayer = $player;
    $turn[0] = "PDECK";
    return false;
  }
}

function ResolveGoAgain($cardID, $player, $from)
{
  global $CS_NextNAACardGoAgain, $actionPoints, $mainPlayer, $CS_ActionsPlayed;
  $actionsPlayed = explode(",", GetClassState($player, $CS_ActionsPlayed));
  $cardType = CardType($cardID);
  $goAgainPrevented = CurrentEffectPreventsGoAgain();
  WriteLog("processing go again for  " . $cardID);
  if (IsStaticType($cardType, $from, $cardID)) {
    $hasGoAgain = AbilityHasGoAgain($cardID);
    if (!$hasGoAgain && GetResolvedAbilityType($cardID, $from) == "A") $hasGoAgain = CurrentEffectGrantsNonAttackActionGoAgain($cardID, $from);
  } else {
    $hasGoAgain = HasGoAgain($cardID);
    if (GetClassState($player, $CS_NextNAACardGoAgain) && $cardType == "A") {
      $hasGoAgain = true;
      SetClassState($player, $CS_NextNAACardGoAgain, 0);
    }
    
    if (count($actionsPlayed) > 2 && TalentContains($actionsPlayed[2], "LIGHTNING") && $actionsPlayed[1] == "ROS074") $hasGoAgain = true;
    if ($cardType == "AA" && SearchCurrentTurnEffects("ELE147", $player)) $hasGoAgain = false;
    if ($cardType == "A") $hasGoAgain = CurrentEffectGrantsNonAttackActionGoAgain($cardID, $from) || $hasGoAgain;
    if ($cardType == "A" && $hasGoAgain && (SearchAuras("UPR190", 1) || SearchAuras("UPR190", 2))) $hasGoAgain = false;
    if ($cardType == "I") $hasGoAgain = CurrentEffectGrantsInstantGoAgain($cardID, $from);
  }
  if ($player == $mainPlayer && $hasGoAgain && !$goAgainPrevented) ++$actionPoints;
}

function PitchDeck($player, $index)
{
  $deck = new Deck($player);
  $cardID = RemovePitch($player, $index);
  $deck->AddBottom($cardID, "PITCH");
}

function GetUniqueId($cardID = "", $player = "")
{
  global $permanentUniqueIDCounter;
  ++$permanentUniqueIDCounter;
  return $player . ";" . $cardID . ";" . $permanentUniqueIDCounter;
}

function IsHeroAttackTarget()
{
  $target = explode("-", GetAttackTarget());
  return $target[0] == "THEIRCHAR";
}

function IsAllyAttackTarget()
{
  $target = explode("-", GetAttackTarget());
  return $target[0] == "THEIRALLY";
}

function IsSpecificAllyAttackTarget($player, $index, $uniqueID)
{
  global $combatChainState, $CCS_AttackTargetUID;
  $mzTarget = GetAttackTarget();
  $mzArr = explode("-", $mzTarget);
  if ($mzArr[0] == "ALLY" || $mzArr[0] == "MYALLY" || $mzArr[0] == "THEIRALLY") {
    return $index == intval($mzArr[1]) && $uniqueID == $combatChainState[$CCS_AttackTargetUID];
  }
  return false;
}

function IsSpecificAuraAttackTarget($player, $index, $uniqueID)
{
  global $combatChainState, $CCS_AttackTargetUID;
  $mzTarget = GetAttackTarget();
  $mzArr = explode("-", $mzTarget);
  if ($mzArr[0] == "AURAS" || $mzArr[0] == "MYAURAS" || $mzArr[0] == "THEIRALLYAURAS") {
    return $index == intval($mzArr[1]) && $uniqueID == $combatChainState[$CCS_AttackTargetUID];
  }
  return false;
}

function IsAllyAttacking()
{
  global $combatChain;
  if (count($combatChain) == 0) return false;
  return DelimStringContains(CardSubtype($combatChain[0]), "Ally");
}

function IsSpecificAllyAttacking($player, $index)
{
  global $combatChain, $combatChainState, $CCS_WeaponIndex, $mainPlayer;
  if (count($combatChain) == 0) return false;
  if ($mainPlayer != $player) return false;
  $weaponIndex = intval($combatChainState[$CCS_WeaponIndex]);
  if ($weaponIndex == -1) return false;
  if ($weaponIndex != $index) return false;
  if (!DelimStringContains(CardSubtype($combatChain[0]), "Ally")) return false;
  return true;
}

function IsSpecificAuraAttacking($player, $index)
{
  global $combatChain, $combatChainState, $CCS_WeaponIndex, $mainPlayer;
  if (count($combatChain) == 0) return false;
  if ($mainPlayer != $player) return false;
  $weaponIndex = intval($combatChainState[$CCS_WeaponIndex]);
  if ($weaponIndex == -1) return false;
  if ($weaponIndex != $index) return false;
  if (!DelimStringContains(CardSubtype($combatChain[0]), "Aura")) return false;
  return true;
}

function CanRevealCards($player)
{
  $otherPlayer = ($player == 1 ? 2 : 1);
  if (SearchAurasForCard("UPR138", $player) != "" || SearchAurasForCard("UPR138", $otherPlayer) != "") {
    WriteLog("Reveal prevented by " . CardLink("UPR138", "UPR138"));
    return false;
  }
  return true;
}

function BaseAttackModifiers($attackID, $attackValue)
{
  global $currentTurnEffects, $mainPlayer;
  for ($i = 0; $i < count($currentTurnEffects); $i += CurrentTurnEffectPieces()) {
    if ($currentTurnEffects[$i + 1] != $mainPlayer) continue;
    if (!IsCombatEffectActive($currentTurnEffects[$i])) continue;
    $effects = explode("-", $currentTurnEffects[$i]);
    switch ($effects[0]) {
      case "EVR094":
      case "EVR095":
      case "EVR096":
        $attackValue = ceil($attackValue / 2);
        break;
      case "UPR151":
        if ($attackID == "UPR551") $attackValue = $effects[1];
        break;
      default:
        break;
    }
  }
  return $attackValue;
}

function GetDamagePreventionIndices($player, $type)
{
  $rv = "";

  $auras = &GetAuras($player);
  $indices = "";
  for ($i = 0; $i < count($auras); $i += AuraPieces()) {
    if (AuraDamagePreventionAmount($player, $i, $type) > 0 || HasWard($auras[$i], $player)) {
      if ($indices != "") $indices .= ",";
      $indices .= $i;
    }
  }
  $mzIndices = SearchMultiZoneFormat($indices, "MYAURAS");

  $char = &GetPlayerCharacter($player);
  $indices = "";
  for ($i = 0; $i < count($char); $i += CharacterPieces()) {
    if ($char[$i + 1] != 0 && WardAmount($char[$i], $player) > 0 && $char[$i + 12] == "UP") {
      if ($indices != "") $indices .= ",";
      $indices .= $i;
    }
  }
  $indices = SearchMultiZoneFormat($indices, "MYCHAR");
  $mzIndices = CombineSearches($mzIndices, $indices);

  $items = &GetItems($player);
  $itemCount = count($items);
  $indices = "";
  for ($i = 0; $i < $itemCount; $i += ItemPieces()) {
    if (ItemDamagePeventionAmount($player, $i) > 0) {
      if ($indices != "") $indices .= ",";
      $indices .= $i;
    }
  }
  $indices = SearchMultizoneFormat($indices, "MYITEMS");
  $mzIndices = CombineSearches($mzIndices, $indices);

  $ally = &GetAllies($player);
  $indices = "";
  for ($i = 0; $i < count($ally); $i += AllyPieces()) {
    if ($ally[$i + 1] != 0 && WardAmount($ally[$i], $player) > 0) {
      if ($indices != "") $indices .= ",";
      $indices .= $i;
    }
  }
  $indices = SearchMultiZoneFormat($indices, "MYALLY");
  $mzIndices = CombineSearches($mzIndices, $indices);
  $rv = $mzIndices;

  return $rv;
}

function GetDamagePreventionTargetIndices()
{
  global $combatChain, $currentPlayer;
  $otherPlayer = $currentPlayer == 1 ? 2 : 1;
  $rv = "";
  $rv = SearchMultizone($otherPlayer, "LAYER");
  if (count($combatChain) > 0) $rv = CombineSearches($rv, "CC-0");
  if (SearchLayer($otherPlayer, "W") == "" && (count($combatChain) == 0 || CardType($combatChain[0]) != "W")) {
    $rv = CombineSearches($rv, SearchMultiZoneFormat(SearchCharacter($otherPlayer, type: "W"), "THEIRCHAR"));
  }
  $rv = CombineSearches($rv, SearchMultiZoneFormat(SearchAllies($otherPlayer), "THEIRALLY"));
  $rv = CombineSearches($rv, SearchMultiZoneFormat(SearchAura($otherPlayer), "THEIRAURAS"));
  $rv = CombineSearches($rv, SearchMultiZoneFormat(SearchAura($currentPlayer), "MYAURAS"));
  $rv = CombineSearches($rv, SearchMultiZoneFormat(SearchItems($otherPlayer), "THEIRITEMS"));
  if (ArsenalHasFaceUpCard($otherPlayer)) $rv = CombineSearches($rv, SearchMultiZoneFormat(SearchArsenal($otherPlayer), "THEIRARS"));
  $rv = CombineSearches($rv, SearchMultiZoneFormat(SearchCharacter($otherPlayer, type: "C"), "THEIRCHAR"));
  return $rv;
}

function SelfCostModifier($cardID, $from)
{
  global $CS_NumCharged, $currentPlayer, $combatChain, $layers, $CS_NumVigorDestroyed, $CS_NumCardsDrawn;
  switch ($cardID) {
    case "ARC080":
    case "ARC082":
    case "ARC088":
    case "ARC089":
    case "ARC090":
    case "ARC094":
    case "ARC095":
    case "ARC096":
    case "ARC097":
    case "ARC098":
    case "ARC099":
    case "ARC100":
    case "ARC101":
    case "ARC102":
      return (-1 * NumRunechants($currentPlayer));
    case "MON032":
      return (-1 * (2 * GetClassState($currentPlayer, $CS_NumCharged)));
    case "MON084":
    case "MON085":
    case "MON086":
      return TalentContains($combatChain[$layers[3]], "SHADOW") ? -1 : 0;
    case "DYN104":
    case "DYN105":
    case "DYN106":
      return SearchMultizone($currentPlayer, "MYITEMS:isSameName=ARC036") != "" ? -1 : 0;
    case "OUT056":
    case "OUT057":
    case "OUT058":
      return (ComboActive($cardID) ? -2 : 0);
    case "OUT074":
    case "OUT075":
    case "OUT076":
      return (ComboActive($cardID) ? -1 : 0);
    case "OUT145":
    case "OUT146":
    case "OUT147":
      return (-1 * DamageDealtBySubtype("Dagger"));
    case "WTR206":
    case "WTR207":
    case "WTR208":
      if (GetPlayerCharacter($currentPlayer)[0] == "ROGUE030") {
        return -1;
      } else return 0;
    case "DTD171":
      return ($from == "BANISH" ? -2 : 0);
    case "DTD175":
    case "DTD176":
    case "DTD177":
      return ($from == "BANISH" ? -2 : 0);
    case "DTD178":
    case "DTD179":
    case "DTD180":
      return ($from == "BANISH" ? -2 : 0);
    case "DTD213":
      return (-1 * NumRunechants($currentPlayer));
    case "EVO064":
    case "EVO065":
    case "EVO066":
    case "TCC012":
    case "TCC023":
      return EvoUpgradeAmount($currentPlayer) * -1;
    case "EVO054":
    case "EVO055":
    case "EVO056":
      return EvoUpgradeAmount($currentPlayer) >= 2 ? -3 : 0;
    case "EVO183":
    case "EVO184":
    case "EVO185":
      return SearchMultizone($currentPlayer, "MYITEMS:isSameName=ARC036") != "" ? -1 : 0;
    case "EVO225":
    case "EVO226":
    case "EVO227":
      return SearchCount(SearchMultizone($currentPlayer, "MYITEMS:isSameName=ARC036")) * -1;
    case "HVY058":
      if (GetClassState($currentPlayer, $CS_NumVigorDestroyed) > 0 || CountAura("HVY242", $currentPlayer) > 0) return -1;
      else return 0;
    case "HVY183":
    case "HVY184":
    case "HVY185":
      return (GetClassState($currentPlayer, $CS_NumCardsDrawn) > 0 ? -1 : 0);
    case "HVY251":
      return (-1 * NumRunechants($currentPlayer));
    default:
      return 0;
  }
}

function IsAlternativeCostPaid($cardID, $from)
{
  global $currentTurnEffects, $currentPlayer, $combatChainState, $CCS_WasRuneGate;
  $isAlternativeCostPaid = false;
  for ($i = count($currentTurnEffects) - CurrentTurnPieces(); $i >= 0; $i -= CurrentTurnPieces()) {
    $remove = false;
    if ($currentTurnEffects[$i + 1] == $currentPlayer) {
      switch ($currentTurnEffects[$i]) {
        case "ARC185":
        case "CRU188":
        case "MON199":
        case "MON257":
        case "EVR161":
        case "HVY176-PAID":
        case "MST131":
          $isAlternativeCostPaid = true;
          $remove = true;
          break;
        default:
          break;
      }
      if ($remove) RemoveCurrentTurnEffect($i);
    }
  }
  if ($from == "BANISH" && SearchAuras("ARC112", $currentPlayer) > 0 && HasRunegate($cardID) && SearchCount(SearchAurasForCard("ARC112", $currentPlayer)) >= CardCost($cardID)) {
    $combatChainState[$CCS_WasRuneGate] = 1;
    return true;
  }
  return $isAlternativeCostPaid;
}

function BanishCostModifier($from, $index, $cost)
{
  global $currentPlayer;
  $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
  if ($from != "BANISH" && $from != "THEIRBANISH") return 0;
  $from == "BANISH" ? $banish = GetBanish($currentPlayer) : $banish = GetBanish($otherPlayer);
  $mod = explode("-", $banish[$index + 1]);
  switch ($mod[0]) {
    case "ARC119":
      return -1 * intval($mod[1]);
    case "TCCGorgonsGaze":
      return -1 * intval($cost);
    default:
      return 0;
  }
}

function IsCurrentAttackName($name)
{
  $names = GetCurrentAttackNames();
  for ($i = 0; $i < count($names); ++$i) {
    if ($name == $names[$i]) return true;
  }
  return false;
}

function IsCardNamed($player, $cardID, $name)
{
  global $currentTurnEffects;
  if (CardName($cardID) == $name) return true;
  for ($i = 0; $i < count($currentTurnEffects); $i += CurrentTurnEffectPieces()) {
    $effectArr = explode("-", $currentTurnEffects[$i]);
    $name = CurrentEffectNameModifier($effectArr[0], (count($effectArr) > 1 ? GamestateUnsanitize($effectArr[1]) : "N/A"), $player);
    //You have to do this at the end, or you might have a recursive loop -- e.g. with OUT052
    if ($name != "" && $currentTurnEffects[$i + 1] == $player) return true;
  }
  return false;
}

function GetCurrentAttackNames()
{
  global $combatChain, $currentTurnEffects, $mainPlayer;
  $names = [];
  if (count($combatChain) == 0) return $names;
  array_push($names, CardName($combatChain[0]));
  for ($i = 0; $i < count($currentTurnEffects); $i += CurrentTurnEffectPieces()) {
    $effectArr = explode("-", $currentTurnEffects[$i]);
    $name = CurrentEffectNameModifier($effectArr[0], (count($effectArr) > 1 ? GamestateUnsanitize($effectArr[1]) : "N/A"), $mainPlayer);
    //You have to do this at the end, or you might have a recursive loop -- e.g. with OUT052
    if ($name != "" && $currentTurnEffects[$i + 1] == $mainPlayer && IsCombatEffectActive($effectArr[0]) && !IsCombatEffectLimited($i)) {
      array_push($names, $name);
    }
  }
  return $names;
}

function SerializeCurrentAttackNames()
{
  $names = GetCurrentAttackNames();
  $serializedNames = "";
  for ($i = 0; $i < count($names); ++$i) {
    if ($serializedNames != "") $serializedNames .= ",";
    $serializedNames .= GamestateSanitize($names[$i]);
  }
  return $serializedNames;
}

function HasAttackName($name)
{
  global $chainLinkSummary;
  for ($i = 0; $i < count($chainLinkSummary); $i += ChainLinkSummaryPieces()) {
    $names = explode(",", $chainLinkSummary[$i + 4]);
    for ($j = 0; $j < count($names); ++$j) {
      if ($name == GamestateUnsanitize($names[$j])) return true;
    }
  }
  return false;
}

function HitEffectsArePrevented($cardID)
{
  global $combatChainState, $CCS_ChainLinkHitEffectsPrevented, $mainPlayer, $layers, $defPlayer;
  if (CardType($cardID) == "AA" && (SearchAuras("CRU028", 1) || SearchAuras("CRU028", 2))) return true;
  if (SearchCurrentTurnEffects("MST079-HITPREVENTION", $defPlayer)) return true;
  if ($combatChainState[$CCS_ChainLinkHitEffectsPrevented]) SearchCurrentTurnEffects("OUT108", $mainPlayer, true);
  return $combatChainState[$CCS_ChainLinkHitEffectsPrevented];
}

function HitEffectsPreventedThisLink()
{
  global $combatChainState, $CCS_ChainLinkHitEffectsPrevented;
  $combatChainState[$CCS_ChainLinkHitEffectsPrevented] = 1;
}

function HitsInRow()
{
  global $chainLinkSummary;
  $numHits = 0;
  for ($i = count($chainLinkSummary) - ChainLinkSummaryPieces(); $i >= 0 && intval($chainLinkSummary[$i + 5]) > 0; $i -= ChainLinkSummaryPieces()) {
    ++$numHits;
  }
  return $numHits;
}

function HitsInCombatChain()
{
  global $chainLinkSummary, $combatChainState, $CCS_HitThisLink;
  $numHits = intval($combatChainState[$CCS_HitThisLink]);
  for ($i = count($chainLinkSummary) - ChainLinkSummaryPieces(); $i >= 0; $i -= ChainLinkSummaryPieces()) {
    $numHits += intval($chainLinkSummary[$i + 5]);
  }
  return $numHits;
}

function NumAttacksHit()
{
  global $chainLinkSummary;
  $numHits = 0;
  for ($i = count($chainLinkSummary) - ChainLinkSummaryPieces(); $i >= 0; $i -= ChainLinkSummaryPieces()) {
    if ($chainLinkSummary[$i] > 0) ++$numHits;
  }
  return $numHits;
}

function NumChainLinks()
{
  global $chainLinkSummary, $combatChain;
  $numLinks = count($chainLinkSummary) / ChainLinkSummaryPieces();
  if (count($combatChain) > 0) ++$numLinks;
  return $numLinks;
}

function ClearGameFiles($gameName)
{
  unlink("./Games/" . $gameName . "/gamestateBackup.txt");
  unlink("./Games/" . $gameName . "/beginTurnGamestate.txt");
  unlink("./Games/" . $gameName . "/lastTurnGamestate.txt");
}

function PlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = "-")
{
  global $currentPlayer, $layers, $CS_NumCrouchingTigerPlayedThisTurn, $currentTurnEffects;
  $cardType = CardType($cardID);
  $cardID = ShiyanaCharacter($cardID);
  $set = CardSet($cardID);
  $class = CardClass($cardID);
  if ($target != "-") {
    $targetArr = explode("-", $target);
    if ($targetArr[0] == "LAYERUID") {
      $targetArr[0] = "LAYER";
      $targetArr[1] = SearchLayersForUniqueID($targetArr[1]);
    }
    if (isset($targetArr[1])) $target = $targetArr[0] . "-" . $targetArr[1];
    else $target = $targetArr[0];
  }
  if (($set == "ELE" || $set == "UPR") && $additionalCosts != "-" && HasFusion($cardID)) {
    FuseAbility($cardID, $currentPlayer, $additionalCosts);
  }
  if (IsCardNamed($currentPlayer, $cardID, "Crouching Tiger")) IncrementClassState($currentPlayer, $CS_NumCrouchingTigerPlayedThisTurn);
  if ($set == "WTR") return WTRPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts);
  else if ($set == "ARC") {
    switch ($class) {
      case "MECHANOLOGIST":
        return ARCMechanologistPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts);
      case "RANGER":
        return ARCRangerPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts);
      case "RUNEBLADE":
        return ARCRunebladePlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts);
      case "WIZARD":
        return ARCWizardPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts);
      case "GENERIC":
        return ARCGenericPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts);
      default:
        return "";
    }
  } else if ($set == "CRU") return CRUPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts);
  else if ($set == "MON") {
    switch ($class) {
      case "BRUTE":
        return MONBrutePlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts);
      case "ILLUSIONIST":
        return MONIllusionistPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts);
      case "RUNEBLADE":
        return MONRunebladePlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts);
      case "WARRIOR":
        return MONWarriorPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts);
      case "GENERIC":
        return MONGenericPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts);
      case "NONE":
        return MONTalentPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts);
      default:
        return "";
    }
  } else if ($set == "ELE") {
    switch ($class) {
      case "GUARDIAN":
        return ELEGuardianPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts);
      case "RANGER":
        return ELERangerPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts);
      case "RUNEBLADE":
        return ELERunebladePlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts);
      default:
        return ELETalentPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts);
    }
  } else if ($set == "EVR") return EVRPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts);
  else if ($set == "UPR") return UPRPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts);
  else if ($set == "DVR") return DVRPlayAbility($cardID);
  else if ($set == "RVD") return RVDPlayAbility($cardID);
  else if ($set == "DYN") return DYNPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts);
  else if ($set == "OUT") return OUTPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts);
  else if ($set == "DTD") return DTDPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts);
  else if ($set == "TCC") return TCCPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts);
  else if ($set == "EVO") return EVOPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts);
  else if ($set == "HVY") return HVYPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts);
  else if ($set == "AKO") return AKOPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts);
  else if ($set == "MST") return MSTPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts);
  else if ($set == "ASB") return ASBPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts);
  else if ($set == "AAZ") return AAZPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts);
  else if ($set == "ROS") return ROSPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts);
  else if ($set == "TER") return TERPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts);
  else if ($set == "AUR") return AURPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts);
  else if ($set == "AIO") return AIOPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts);
  else {
    switch ($cardID) {
      case "LGS176":
      case "LGS177":
      case "LGS178":
        $deck = new Deck($currentPlayer);
        if (!$deck->Empty()) if (ColorContains($deck->BanishTop(), PitchValue($cardID), $currentPlayer)) PlayAura("ARC112", $currentPlayer, 1, true);
        return "";
      case "HER117":
        $character = &GetPlayerCharacter($currentPlayer);
        $index = SearchCurrentTurnEffectsForIndex("HER117", $currentPlayer);
        $dynCost = explode("-", $currentTurnEffects[$index]);
        MZMoveCard($currentPlayer, "MYHAND:type=A;class=WIZARD;arcaneDamage=" . $dynCost[1], "MYBANISH,HAND,INST," . $cardID);
        return "";
      default:
        break;
    }
  }
  return ROGUEPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts);
}

function PitchAbility($cardID)
{
  global $currentPlayer, $CS_NumAddedToSoul;

  $pitchValue = PitchValue($cardID);
  if (GetClassState($currentPlayer, $CS_NumAddedToSoul) > 0 && SearchCharacterActive($currentPlayer, "MON060") && TalentContains($cardID, "LIGHT", $currentPlayer)) {
    $resources = &GetResources($currentPlayer);
    $resources[0] += 1;
  }
  if ($pitchValue == 1) {
    $talismanOfRecompenseIndex = GetItemIndex("EVR191", $currentPlayer);
    if ($talismanOfRecompenseIndex > -1) {
      WriteLog("Talisman of Recompense gained 3 instead of 1 and destroyed itself");
      DestroyItemForPlayer($currentPlayer, $talismanOfRecompenseIndex);
      GainResources($currentPlayer, 2);
    }
    if (ColorContains($cardID, 1, $currentPlayer) && SearchCharacterActive($currentPlayer, "UPR001") || SearchCharacterActive($currentPlayer, "UPR002") || SearchCurrentTurnEffects("UPR001-SHIYANA", $currentPlayer) || SearchCurrentTurnEffects("UPR002-SHIYANA", $currentPlayer)) {
      WriteLog("Dromai creates an Ash");
      PutPermanentIntoPlay($currentPlayer, "UPR043");
    }
  }
  if (SubtypeContains($cardID, "Chi", $currentPlayer)
    && SearchCharacterAlive($currentPlayer, "MST027")
    && SearchCharacterForCard($currentPlayer, "MST027")
    && GetCharacterGemState($currentPlayer, "MST027") == 1
    && !SearchCurrentTurnEffects("MERIDIANWARD", $currentPlayer)) {
    AddLayer("TRIGGER", $currentPlayer, "MST027");
  }
  switch ($cardID) {
    case "WTR000":
    case "ARC000":
    case "CRU000":
    case "OUT000":
    case "DTD000":
    case "ROS000":
      AddLayer("TRIGGER", $currentPlayer, $cardID);
      break;
    case "EVO000": // Technically wrong, it should be a trigger, but since we can't reorder those it works better gameplay-wise to not have that one as a trigger
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYITEMS:hasCrank=true");
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a card with Crank to get a steam counter", 1);
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("MZADDCOUNTER", $currentPlayer, "-", 1);
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

function UnityEffect($cardID)
{
  global $defPlayer;
  switch ($cardID) {
    case "DTD079"://United We Stand
      $char = &GetPlayerCharacter($defPlayer);
      if ($char[0] == "MON029" || $char[0] == "MON030") PlayAura("DTD232", $defPlayer);
      else if ($char[0] == "WTR038" || $char[0] == "WTR039") PlayAura("WTR075", $defPlayer);
      else if ($char[0] == "ELE062" || $char[0] == "ELE063") PlayAura("ELE109", $defPlayer);
      else if ($char[0] == "ELE062" || $char[0] == "ELE063") PlayAura("ELE109", $defPlayer);
      else if ($char[0] == "WTR113" || $char[0] == "WTR114") PlayAura("DTD232", $defPlayer);
      else if ($char[0] == "ELE031" || $char[0] == "ELE032") PlayAura("ELE110", $defPlayer);
      else if ($char[0] == "ELE001" || $char[0] == "ELE002") PlayAura("DYN246", $defPlayer);
      else if ($char[0] == "MON001" || $char[0] == "MON002" || $char[0] == "DTD001" || $char[0] == "DTD002") PlayAura("MON104", $defPlayer);
      else if ($char[0] == "CRU097") PlayAura("DTD233", $defPlayer);
      break;
    case "DTD196"://Anthem of Spring
      PlayAura("ELE109", $defPlayer);
      break;
    case "DTD197"://Northern Winds
      PlayAura("DYN246", $defPlayer);
      break;
    case "DTD198":
      PlayAura("ELE110", $defPlayer);
      break;
    case "DTD203":
      PlayAura("WTR075", $defPlayer);
      break;
    case "DTD206":
      AddCurrentTurnEffect("DTD206", $defPlayer);
      break;
    case "DTD208":
      PlayAura("DTD232", $defPlayer);
      break;
    case "DTD215":
      PlayAura("DTD233", $defPlayer);
      break;
    default:
      break;
  }
}

function Draw($player, $mainPhase = true, $fromCardEffect = true)
{
  global $EffectContext, $mainPlayer, $CS_NumCardsDrawn;
  $otherPlayer = ($player == 1 ? 2 : 1);
  if ($mainPhase && $player != $mainPlayer) {
    $talismanOfTithes = SearchItemsForCard("EVR192", $otherPlayer);
    if ($talismanOfTithes != "") {
      $indices = explode(",", $talismanOfTithes);
      DestroyItemForPlayer($otherPlayer, $indices[0]);
      WriteLog(CardLink("EVR192", "EVR192") . " prevented a draw and was destroyed");
      return "";
    }
  }
  if ($fromCardEffect && (SearchAurasForCard("UPR138", $otherPlayer) != "" || SearchAurasForCard("UPR138", $player) != "")) {
    WriteLog("Draw prevented by " . CardLink("UPR138", "UPR138"));
    return "";
  }
  $deck = new Deck($player);
  $hand = &GetHand($player);
  if ($deck->Empty()) return -1;
  if (CurrentEffectPreventsDraw($player, $mainPhase)) return -1;
  $cardID = $deck->Top(remove: true);
  if ($mainPhase && (SearchAurasForCard("DTD170", 1) != "" || SearchAurasForCard("DTD170", 2) != "")) {
    BanishCardForPlayer($cardID, $player, "DECK", "TT", $player);
  } else {
    array_push($hand, $cardID);
    IncrementClassState($player, $CS_NumCardsDrawn, 1);
  }
  if ($mainPhase && (SearchCharacterActive($otherPlayer, "EVR019") || (SearchCurrentTurnEffects("EVR019-SHIYANA", $otherPlayer) && SearchCharacterActive($otherPlayer, "CRU097")))) PlayAura("WTR075", $otherPlayer);
  if (SearchCharacterActive($player, "EVR020")) {
    if ($EffectContext != "-") {
      $cardType = CardType($EffectContext);
      if ($cardType == "A" || $cardType == "AA") PlayAura("WTR075", $player);
    }
  }
  if ($mainPhase && SearchCharacterActive($otherPlayer, "ROGUE026")) {
    $health = &GetHealth($otherPlayer);
    $health += -10;
    if ($health < 1) {
      $health = 1;
      WriteLog("NO! You will not banish me! I refuse!");
    }
  }
  if ($mainPhase) {
    $numBrainstorm = CountCurrentTurnEffects("DYN196", $player);
    if ($numBrainstorm > 0) {
      $character = &GetPlayerCharacter($player);
      for ($i = 0; $i < $numBrainstorm; ++$i) DealArcane(1, 2, "TRIGGER", $character[0]);
    }
  }
  PermanentDrawCardAbilities($player);
  $hand = array_values($hand);
  return $hand[count($hand) - 1];
}

function ChooseToPay($player, $cardID, $amounts)
{
  AddDecisionQueue("SETDQCONTEXT", $player, "Choose how much to pay for " . CardLink($cardID, $cardID));
  AddDecisionQueue("BUTTONINPUT", $player, $amounts);
  AddDecisionQueue("PAYRESOURCES", $player, "<-", 1);
  AddDecisionQueue("LESSTHANPASS", $player, "1", 1);
}

function WardPoppedAbility($player, $cardID)
{
  if (SearchCharacterActive($player, "DYN213", setInactive: true)) {
    GainResources($player, 1);
    WriteLog("Player " . $player . " gained 1 resource from " . CardLink("DYN213", "DYN213"));
  }
  if (SearchCharacterActive($player, "DTD217", setInactive: true) || $cardID == "DTD217") {
    AddDecisionQueue("YESNO", $player, "if_you_want_to_pay_1_to_create_a_ponder");
    AddDecisionQueue("NOPASS", $player, "-");
    AddDecisionQueue("PAYRESOURCES", $player, "1", 1);
    AddDecisionQueue("PLAYAURA", $player, "DYN244-1", 1);
  }
  if ($cardID == "ROS251") {
    AddCurrentTurnEffect("ROS251", $player);
  }
}

function BanishHand($player)
{
  $hand = &GetHand($player);
  $banishedCards = "";
  for ($i = 0; $i < count($hand); ++$i) {
    if ($banishedCards != "") $banishedCards .= ",";
    $banishedCards .= $hand[$i];
    BanishCardForPlayer($hand[$i], $player, "HAND", "-", $player);
  }
  $hand = [];
  return $banishedCards;
}

function EvoOnPlayHandling($player)
{
  if (SearchCurrentTurnEffects("EVO007", $player, true) || SearchCurrentTurnEffects("EVO008", $player, true)) Draw($player);
}

function EvoHandling($cardID, $player, $from)
{
  global $dqVars, $CombatChain;
  $char = &GetPlayerCharacter($player);
  $slot = "";
  $otherPlayer = $player == 1 ? 2 : 1;
  if (SubtypeContains($cardID, "Head")) $slot = "Head";
  else if (SubtypeContains($cardID, "Chest")) $slot = "Chest";
  else if (SubtypeContains($cardID, "Arms")) $slot = "Arms";
  else if (SubtypeContains($cardID, "Legs")) $slot = "Legs";
  for ($i = 0; $i < count($char); $i += CharacterPieces()) {
    if (SubtypeContains($char[$i], $slot)) {
      if (SubtypeContains($char[$i], "Base") && $char[$i + 1] != 0) {
        $CombatChain->Remove(GetCombatChainIndex($char[$i], $player));
        CharacterAddSubcard($player, $i, $char[$i]);
        $fromCardID = $char[$i];
        $char[$i + 2] = 0;//Reset counters
        $char[$i + 4] = 0;//Reset defense counters
        $char[$i + 6] = 0;//Not on chain anymore
        $char[$i] = substr($cardID, 0, 3) . (intval(substr($cardID, 3, 3)) + 400);
        $char[$i + 7] = 0;//Should not be flagged for destruction
        $char[$i + 8] = 0;//Should not be frozen
        $char[$i + 9] = CharacterDefaultActiveState($char[$i]);
        $dqVars[1] = $i;
        EvoTransformAbility($char[$i], $fromCardID, $player);
      } else {
        if (substr($from, 0, 5) != "THEIR") AddGraveyard($cardID, $player, "HAND", $player);
        else AddGraveyard($cardID, $otherPlayer, "GRAVEYARD", $player);
        WriteLog("<b>*ERR0R* // No base of that type equipped //</b>");
      }
      break;
    }
  }
}

function CharacterAddSubcard($player, $index, $card)
{
  $char = &GetPlayerCharacter($player);
  if (isSubcardEmpty($char, $index)) $char[$index + 10] = $card;
  else $char[$index + 10] = $char[$index + 10] . "," . $card;
}

function CharacterChooseSubcard($player, $index, $fromDQ = false, $count = 1, $isMandatory = true)
{
  $character = &GetPlayerCharacter($player);
  $subcards = explode(",", $character[$index + 10]);
  $subcardsCount = count($subcards);
  $chooseMultizoneData = "";
  for ($i = 0; $i < $subcardsCount; $i++) {
    if ($chooseMultizoneData == "") $chooseMultizoneData = "CARDID-" . $subcards[$i];
    else $chooseMultizoneData = $chooseMultizoneData . ",CARDID-" . $subcards[$i];
  }
  if ($chooseMultizoneData != "") {
    if ($count == 1) {
      AddDecisionQueue("SETDQCONTEXT", $player, "Choose a subcard to banish from " . CardName($character[$index]));
      if ($isMandatory) AddDecisionQueue("CHOOSEMULTIZONE", $player, $chooseMultizoneData);
      else AddDecisionQueue("MAYCHOOSEMULTIZONE", $player, $chooseMultizoneData);
      AddDecisionQueue("MZOP", $player, "GETCARDINDEX", 1);
      if ($character[0] == "EVO410") AddDecisionQueue("REMOVESOUL", $player, $index, 1);
      AddDecisionQueue("REMOVESUBCARD", $player, $index, 1);
    } else {
      AddDecisionQueue("SETDQCONTEXT", $player, "Choose " . $count . " subcards to banish from " . CardName($character[$index]));
      AddDecisionQueue("MULTICHOOSESUBCARDS", $player, $count . "-" . str_replace("CARDID-", "", $chooseMultizoneData) . "-" . $count);
      if ($character[0] == "EVO410") AddDecisionQueue("REMOVESOUL", $player, $index);
      AddDecisionQueue("REMOVESUBCARD", $player, $index);
    }
  }
}

function EvoHasUnderCard($player, $index)
{
  $char = &GetPlayerCharacter($player);
  return $char[$index + 10] != "";
}

function EvoTransformAbility($toCardID, $fromCardID, $player = "")
{
  $otherPlayer = ($player == 1 ? 2 : 1);
  switch ($toCardID) {
    case "EVO026":
    case "EVO426":
      if (SubtypeContains($fromCardID, "Evo", $player) && CardName($fromCardID) != CardName($toCardID))
        AddCurrentTurnEffect($toCardID, $player);
      break;
    case "EVO027":
    case "EVO427":
      if (SubtypeContains($fromCardID, "Evo", $player) && CardName($fromCardID) != CardName($toCardID))
        GainResources($player, 3);
      break;
    case "EVO028":
    case "EVO428":
      if (SubtypeContains($fromCardID, "Evo", $player) && CardName($fromCardID) != CardName($toCardID)) {
        MZMoveCard($player, "MYDISCARD:type=AA;maxAttack=6;minAttack=6", "MYTOPDECK-4", true);
      }
      break;
    case "EVO029":
    case "EVO429":
      if (SubtypeContains($fromCardID, "Evo", $player) && CardName($fromCardID) != CardName($toCardID))
        GainActionPoints(1, $player);
      break;
    case "EVO050":
    case "EVO450":
      MZChooseAndBanish($player, "MYHAND", "HAND,-", may: true);
      AddDecisionQueue("DRAW", $player, "-", 1);
      break;
    case "EVO051":
    case "EVO451":
      GainResources($player, 1);
      break;
    case "EVO052":
    case "EVO452":
      AddCurrentTurnEffect("EVO052", $player);
      break;
    case "EVO053":
    case "EVO453":
      GiveAttackGoAgain();
      break;
    case "MST228":
    case "MST628":
      MZMoveCard($player, "MYBANISH:type=AA;class=MECHANOLOGIST&MYBANISH:type=A;class=MECHANOLOGIST", "MYTOPDECK", true, true);
      break;
    case "MST229":
    case "MST629":
      AddCurrentTurnEffect("MST229", $player);
      break;
    case "MST230":
    case "MST630":
      AddDecisionQueue("MULTIZONEINDICES", $player, "MYCHAR:type=C&THEIRCHAR:type=C&MYALLY&THEIRALLY", 1);
      AddDecisionQueue("SETDQCONTEXT", $player, "Choose a target to deal 1 damage");
      AddDecisionQueue("CHOOSEMULTIZONE", $player, "<-", 1);
      AddDecisionQueue("MZDAMAGE", $player, "1,DAMAGE," . $toCardID, 1);
      break;
    case "MST231":
    case "MST631":
      AddCurrentTurnEffect("MST231", $player);
      break;
    default:
      break;
  }
  switch ($fromCardID) {
    case "EVO426":
      if (TypeContains($toCardID, "C", $player)) {
        AddCurrentTurnEffect($fromCardID, $player);
        AddCurrentTurnEffect($fromCardID, $player);
      } else if (SubtypeContains($toCardID, "Evo", $player) && CardName($fromCardID) != CardName($toCardID)) {
        AddCurrentTurnEffect($fromCardID, $player);
      }
      break;
    case "EVO427":
      if (TypeContains($toCardID, "C", $player)) {
        GainResources($player, 6);
      } else if (SubtypeContains($toCardID, "Evo", $player) && CardName($fromCardID) != CardName($toCardID)) {
        GainResources($player, 3);
      }
      break;
    case "EVO428":
      if (TypeContains($toCardID, "C", $player)) {
        MZMoveCard($player, "MYDISCARD:type=AA;minAttack=6", "MYTOPDECK-4", true);
        MZMoveCard($player, "MYDISCARD:type=AA;minAttack=6", "MYTOPDECK-4", true);
      } else if (SubtypeContains($toCardID, "Evo", $player) && CardName($fromCardID) != CardName($toCardID)) {
        MZMoveCard($player, "MYDISCARD:type=AA;minAttack=6", "MYTOPDECK-4", true);
      }
      break;
    case "EVO429":
      if (TypeContains($toCardID, "C", $player)) {
        GainActionPoints(2, $player);
      } else if (SubtypeContains($toCardID, "Evo", $player) && CardName($fromCardID) != CardName($toCardID)) {
        GainActionPoints(1, $player);
      }
      break;
    default:
      break;
  }
}

function EvoUpgradeAmount($player)
{
  $amount = 0;
  $amount += SearchCount(SearchCharacter($player, subtype: "Evo"));
  if (FindCharacterIndex($player, "EVO410") != -1) $amount += 2; //Only +2 as we already find EVO410 and EVO410b counted in the search SearchCount(SearchCharacter($player, subtype:"Evo"))
  return $amount;
}

function EquipmentsUsingSteamCounter($charID)
{
  switch ($charID) {
    case "EVO014":
    case "EVO015":
    case "EVO016":
    case "EVO017":
      return true;
    default:
      return false;
  }
}

function CheckIfConstructNitroMechanoidConditionsAreMet($currentPlayer)
{
  $hasHead = false;
  $hasChest = false;
  $hasArms = false;
  $hasLegs = false;
  $hasWeapon = false;
  $char = &GetPlayerCharacter($currentPlayer);
  for ($i = 0; $i < count($char); $i += CharacterPieces()) {
    $characterCardID = $char[$i];
    if ($char[$i + 1] == 0) continue;
    if (!ClassContains($characterCardID, "MECHANOLOGIST", $currentPlayer)) continue;
    if (CardType($characterCardID) == "W") $hasWeapon = true;
    else {
      if (SubtypeContains($characterCardID, "Head", $currentPlayer, $char[$i + 11])) $hasHead = true;
      if (SubtypeContains($characterCardID, "Chest", $currentPlayer, $char[$i + 11])) $hasChest = true;
      if (SubtypeContains($characterCardID, "Arms", $currentPlayer, $char[$i + 11])) $hasArms = true;
      if (SubtypeContains($characterCardID, "Legs", $currentPlayer, $char[$i + 11])) $hasLegs = true;
    }
  }
  if (!$hasHead || !$hasChest || !$hasArms || !$hasLegs || !$hasWeapon) return "You do not meet the equipment requirement";
  if (SearchCount(SearchMultizone($currentPlayer, "MYITEMS:isSameName=ARC036")) < 3) return "You do not meet the Hyper Driver requirement";
  return "";
}

function CheckIfSingularityConditionsAreMet($currentPlayer)
{
  $hasWeapon = false;
  $evoCount = 0;
  $char = &GetPlayerCharacter($currentPlayer);
  $charCount = count($char);
  $charPieces = CharacterPieces();
  for ($i = 0; $i < $charCount; $i += $charPieces) {
    if ($char[$i + 1] == 0) continue;
    if (CardType($char[$i]) == "W") $hasWeapon = true;
    if (SubtypeContains($char[$i], "Evo")) $evoCount++;
  }
  if (!$hasWeapon) return "You do not meet the weapon requirement";
  if ($evoCount < 4) return "You do not meet the Evo requirement";
  return "";
}

function CanOnlyTargetHeroes($cardID)
{
  switch ($cardID) {
    case "TCC011":
      return true;
    default:
      return false;
  }
}

function NonHitEffects($cardID)
{
  global $mainPlayer, $defPlayer, $currentTurnEffects;
  for ($i = 0; $i < count($currentTurnEffects); $i += CurrentTurnPieces()) {
    if ($currentTurnEffects[$i] == $cardID && $currentTurnEffects[$i + 1] == $mainPlayer) {
      switch ($currentTurnEffects[$i]) {
        case "HVY012":
          RemoveCurrentTurnEffect($i);
          $banish = new Banish($defPlayer);
          $banishedCard = $banish->FirstCardWithModifier($cardID);
          if ($banishedCard == null) break;
          $banishIndex = $banishedCard->Index();
          if ($banishIndex > -1) AddPlayerHand($banish->Remove($banishIndex), $defPlayer, "BANISH");
          break;
        default:
          break;
      }
    }
  }
}

function MentorTrigger($player, $index, $specificCard = "")
{
  $cardID = RemoveArsenal($player, $index);
  BanishCardForPlayer($cardID, $player, "ARS", "-");
  if ($specificCard != "") AddDecisionQueue("MULTIZONEINDICES", $player, "MYDECK:cardID=$specificCard");
  else AddDecisionQueue("MULTIZONEINDICES", $player, "MYDECK:specOnly=true");
  AddDecisionQueue("MAYCHOOSEMULTIZONE", $player, "<-", 1);
  AddDecisionQueue("MZADDZONE", $player, "MYARS,DECK,UP", 1);
  AddDecisionQueue("MZREMOVE", $player, "-", 1);
  AddDecisionQueue("SHUFFLEDECK", $player, "-");
}
