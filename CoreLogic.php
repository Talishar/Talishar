<?php

  include "CardSetters.php";
  include "CardGetters.php";

function EvaluateCombatChain(&$totalAttack, &$totalDefense, &$attackModifiers=[])
{
  global $combatChain, $mainPlayer, $currentTurnEffects, $defCharacter, $playerID, $combatChainState, $CCS_LinkBaseAttack;
  global $CCS_WeaponIndex, $mainCharacter, $mainAuras;
    UpdateGameState($playerID);
    BuildMainPlayerGameState();
    $attackType = CardType($combatChain[0]);
    $canGainAttack = CanGainAttack();
    $snagActive = SearchCurrentTurnEffects("CRU182", $mainPlayer) && $attackType == "AA";
    for($i=1; $i<count($combatChain); $i+=CombatChainPieces())
    {
      $from = $combatChain[$i+1];
      $resourcesPaid = $combatChain[$i+2];

      if($combatChain[$i] == $mainPlayer)
      {
        if($i == 1) $attack = $combatChainState[$CCS_LinkBaseAttack];
        else $attack = AttackValue($combatChain[$i-1]);
        if($canGainAttack || $i == 1 || $attack < 0)
        {
          array_push($attackModifiers, $combatChain[$i-1]);
          array_push($attackModifiers, $attack);
          if($i == 1) $totalAttack += $attack;
          else AddAttack($totalAttack, $attack);
        }
        $attack = AttackModifier($combatChain[$i-1], $combatChain[$i+1], $combatChain[$i+2], $combatChain[$i+3]) + $combatChain[$i + 4];
        if(($canGainAttack && !$snagActive) || $attack < 0)
        {
          array_push($attackModifiers, $combatChain[$i-1]);
          array_push($attackModifiers, $attack);
          AddAttack($totalAttack, $attack);
        }
      }
      else
      {
        $totalDefense += BlockingCardDefense($i-1, $combatChain[$i+1], $combatChain[$i+2]);
      }
    }

    //Now check current turn effects
    for($i=0; $i<count($currentTurnEffects); $i+=CurrentTurnPieces())
    {
      if(IsCombatEffectActive($currentTurnEffects[$i]) && !IsCombatEffectLimited($i))
      {
        if($currentTurnEffects[$i+1] == $mainPlayer)
        {
          $attack = EffectAttackModifier($currentTurnEffects[$i]);
          if(($canGainAttack || $attack < 0) && !($snagActive && $currentTurnEffects[$i] == $combatChain[0]))
          {
            array_push($attackModifiers, $currentTurnEffects[$i]);
            array_push($attackModifiers, $attack);
            AddAttack($totalAttack, $attack);
          }
        }
      }
    }

    if($combatChainState[$CCS_WeaponIndex] != -1)
    {
      $attack = 0;
      if($attackType == "W") $attack = $mainCharacter[$combatChainState[$CCS_WeaponIndex]+3];
      else if(DelimStringContains(CardSubtype($combatChain[0]), "Aura")) $attack = $mainAuras[$combatChainState[$CCS_WeaponIndex]+3];
      if($canGainAttack || $attack < 0)
      {
        array_push($attackModifiers, "+1 Attack Counters");
        array_push($attackModifiers, $attack);
        AddAttack($totalAttack, $attack);
      }
    }
    $attack = MainCharacterAttackModifiers();
    if($canGainAttack || $attack < 0)
    {
      array_push($attackModifiers, "Character/Equipment");
      array_push($attackModifiers, $attack);
      AddAttack($totalAttack, $attack);
    }
    $attack = AuraAttackModifiers(0);
    if($canGainAttack || $attack < 0)
    {
      array_push($attackModifiers, "Aura Ability");
      array_push($attackModifiers, $attack);
      AddAttack($totalAttack, $attack);
    }
    $attack = ArsenalAttackModifier();
    if($canGainAttack || $attack < 0)
    {
      array_push($attackModifiers, "Arsenal Ability");
      array_push($attackModifiers, $attack);
      AddAttack($totalAttack, $attack);
    }
}

function AddAttack(&$totalAttack, $amount)
{
  global $combatChain;
  if($amount > 0 && $combatChain[0] == "OUT100") $amount += 1;
  if($amount > 0 && ($combatChain[0] == "OUT065" || $combatChain[0] == "OUT066" || $combatChain[0] == "OUT067") && ComboActive()) $amount += 1;
  $totalAttack += $amount;
}

function BlockingCardDefense($index, $from="", $resourcesPaid=-1)
{
  global $combatChain, $defPlayer, $mainPlayer, $currentTurnEffects;
  $from = $combatChain[$index+2];
  $resourcesPaid = $combatChain[$index+3];
  $defense = BlockValue($combatChain[$index]) + BlockModifier($combatChain[$index], $from, $resourcesPaid) + $combatChain[$index + 6];
  if(CardType($combatChain[$index]) == "E")
  {
    $defCharacter = &GetPlayerCharacter($defPlayer);
    $charIndex = FindDefCharacter($combatChain[$index]);
    $defense += $defCharacter[$charIndex+4];
  }
  for ($i = 0; $i < count($currentTurnEffects); $i += CurrentTurnPieces()) {
    if (IsCombatEffectActive($currentTurnEffects[$i]) && !IsCombatEffectLimited($i)) {
      if ($currentTurnEffects[$i + 1] == $defPlayer) {
        $defense += EffectBlockModifier($currentTurnEffects[$i], index:$index);
      }
    }
  }
  if($defense < 0) $defense = 0;
  return $defense;
}

function AddCombatChain($cardID, $player, $from, $resourcesPaid)
{
  global $combatChain, $turn;
  $index = count($combatChain);
  array_push($combatChain, $cardID);
  array_push($combatChain, $player);
  array_push($combatChain, $from);
  array_push($combatChain, $resourcesPaid);
  array_push($combatChain, RepriseActive());
  array_push($combatChain, ResourcesPaidAttackModifier($cardID, $resourcesPaid));//Attack modifier
  array_push($combatChain, ResourcesPaidBlockModifier($cardID, $resourcesPaid));//Defense modifier
  if($turn[0] == "B" || CardType($cardID) == "DR") OnBlockEffects($index, $from);
  CurrentEffectAttackAbility();
  return $index;
}

function CombatChainPowerModifier($index, $amount)
{
  global $combatChain;
  $combatChain[$index+5] += $amount;
  ProcessPhantasmOnBlock($index);
}

function CacheCombatResult()
{
  global $combatChain, $combatChainState, $CCS_CachedTotalAttack, $CCS_CachedTotalBlock, $CCS_CachedDominateActive, $CCS_CachedNumBlockedFromHand, $CCS_CachedOverpowerActive;
  global $CSS_CachedNumActionBlocked, $CCS_CachedNumDefendedFromHand;
  if(count($combatChain) == 0) return;
  $combatChainState[$CCS_CachedTotalAttack] = 0;
  $combatChainState[$CCS_CachedTotalBlock] = 0;
  EvaluateCombatChain($combatChainState[$CCS_CachedTotalAttack], $combatChainState[$CCS_CachedTotalBlock]);
  $combatChainState[$CCS_CachedDominateActive] = (IsDominateActive() ? "1" : "0");
  if ($combatChainState[$CCS_CachedNumBlockedFromHand] == 0) $combatChainState[$CCS_CachedNumBlockedFromHand] = NumBlockedFromHand();
  $combatChainState[$CCS_CachedOverpowerActive] = (IsOverpowerActive() ? "1" : "0");
  $combatChainState[$CSS_CachedNumActionBlocked] = NumActionBlocked();
  $combatChainState[$CCS_CachedNumDefendedFromHand] = NumDefendedFromHand(); //Reprise
}

function CachedTotalAttack()
{
  global $combatChainState, $CCS_CachedTotalAttack;
  return $combatChainState[$CCS_CachedTotalAttack];
}

function CachedTotalBlock()
{
  global $combatChainState, $CCS_CachedTotalBlock;
  return $combatChainState[$CCS_CachedTotalBlock];
}

function CachedDominateActive()
{
  global $combatChainState, $CCS_CachedDominateActive;
  return ($combatChainState[$CCS_CachedDominateActive] == "1" ? true : false);
}

function CachedOverpowerActive()
{
  global $combatChainState, $CCS_CachedOverpowerActive;
  return ($combatChainState[$CCS_CachedOverpowerActive] == "1" ? true : false);
}

function CachedNumBlockedFromHand() //Dominate
{
  global $combatChainState, $CCS_CachedNumBlockedFromHand;
  return $combatChainState[$CCS_CachedNumBlockedFromHand];
}

function CachedNumDefendedFromHand() //Reprise
{
  global $combatChainState, $CCS_CachedNumDefendedFromHand;
  return $combatChainState[$CCS_CachedNumDefendedFromHand];
}

function CachedNumActionBlocked()
{
  global $combatChainState, $CSS_CachedNumActionBlocked;
  return $combatChainState[$CSS_CachedNumActionBlocked];
}

function StartTurnAbilities()
{
  global $mainPlayer, $defPlayer;
  $mainCharacter = &GetPlayerCharacter($mainPlayer);
  for($i=count($mainCharacter) - CharacterPieces(); $i>=0; $i -= CharacterPieces())
  {
    CharacterStartTurnAbility($i);
  }
  DefCharacterStartTurnAbilities();
  ArsenalStartTurnAbilities();
  AuraStartTurnAbilities();
  AllyStartTurnAbilities($mainPlayer);
  $mainItems = &GetItems($mainPlayer);
  for($i=count($mainItems)-ItemPieces(); $i>= 0; $i-=ItemPieces())
  {
    $mainItems[$i+2] = "2";
    $mainItems[$i+3] = ItemUses($mainItems[$i]);
    ItemStartTurnAbility($i);
  }
  $defItems = &GetItems($defPlayer);
  for($i=0; $i<count($defItems); $i+=ItemPieces())
  {
    $defItems[$i+2] = "2";
    $defItems[$i+3] = ItemUses($defItems[$i]);
  }
  $defCharacter = &GetPlayerCharacter($defPlayer);
  for($i=0; $i<count($defCharacter); $i+=CharacterPieces())
  {
    $defCharacter[$i+8] = "0";//Reset Frozen
  }
  $defAllies = &GetAllies($defPlayer);
  for($i=0; $i<count($defAllies); $i+=AllyPieces())
  {
    $defAllies[$i+3] = "0";//Reset Frozen
  }
  $defArsenal = &GetArsenal($defPlayer);
  for($i=0; $i<count($defArsenal); $i+=ArsenalPieces())
  {
    $defArsenal[$i+4] = "0";//Reset Frozen
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
  for($i=0; $i<count($mainDiscard); $i+=DiscardPieces())
  {
    switch($mainDiscard[$i])
    {
      case "UPR086":
        if(ThawIndices($mainPlayer) != "")
        {
          $cards = CombineSearches($cards, SearchMultiZoneFormat($i, "MYDISCARD")); break;
        }
      default: break;
    }
  }
  return $cards;
}

function MZStartTurnAbility($cardID, $MZIndex)
{
  global $currentPlayer, $otherPlayer;
  switch($cardID)
  {
    case "UPR086":
      AddDecisionQueue("PASSPARAMETER", $currentPlayer, $MZIndex);
      AddDecisionQueue("MZREMOVE", $currentPlayer, "-", 1);
      AddDecisionQueue("MULTIBANISH", $currentPlayer, "GY,-", 1);
      AddDecisionQueue("FINDINDICES", $currentPlayer, "UPR086");
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("AFTERTHAW", $currentPlayer, "<-", 1);
      break;
    default: break;
  }
}

function ArsenalStartTurnAbilities()
{
  global $mainPlayer;
  $arsenal = &GetArsenal($mainPlayer);
  for($i=0; $i<count($arsenal); $i+=ArsenalPieces())
  {
    switch($arsenal[$i])
    {
      case "MON404": case "MON405": case "MON406": case "MON407": case "DVR007": case "RVD007":
        if($arsenal[$i+1] == "DOWN")
        {
          AddDecisionQueue("YESNO", $mainPlayer, "if_you_want_to_turn_your_mentor_card_face_up");
          AddDecisionQueue("NOPASS", $mainPlayer, "-");
          AddDecisionQueue("PASSPARAMETER", $mainPlayer, $i, 1);
          AddDecisionQueue("TURNARSENALFACEUP", $mainPlayer, $i, 1);
        }
        break;
      default: break;
    }
  }
}

function ArsenalAttackAbilities()
{
  global $combatChain, $mainPlayer;
  $attackID = $combatChain[0];
  $attackType = CardType($attackID);
  $attackVal = AttackValue($attackID);
  $arsenal = GetArsenal($mainPlayer);
  for($i=0; $i<count($arsenal); $i+=ArsenalPieces())
  {
    switch($arsenal[$i])
    {
      case "MON406": if($attackType == "AA" && $arsenal[$i+1] == "UP") LadyBarthimontAbility($mainPlayer, $i); break;
      case "RVD007": if($attackType == "AA" && $attackVal >= 6 && $arsenal[$i+1] == "UP") ChiefRukutanAbility ($mainPlayer, $i); break;
      default: break;
    }
  }
}

function ArsenalAttackModifier()
{
  global $combatChain, $mainPlayer;
  $attackID = $combatChain[0];
  $attackType = CardType($attackID);
  $arsenal = GetArsenal($mainPlayer);
  $modifier = 0;
  for($i=0; $i<count($arsenal); $i+=ArsenalPieces())
  {
    switch($arsenal[$i])
    {
      case "MON405": $modifier += ($arsenal[$i+1] == "UP" && $attackType == "W" && Is1H($attackID) ? 1 : 0); break;
      default: break;
    }
  }
  return $modifier;
}

function ArsenalHitEffects()
{
  global $combatChain, $mainPlayer;
  $attackID = $combatChain[0];
  $attackType = CardType($attackID);
  $attackSubType = CardSubType($attackID);
  $arsenal = GetArsenal($mainPlayer);
  $modifier = 0;
  for($i=0; $i<count($arsenal); $i+=ArsenalPieces())
  {
    switch($arsenal[$i])
    {
      case "MON405": if($arsenal[$i+1] == "UP" && $attackType == "W") MinervaThemisAbility($mainPlayer, $i); break;
      case "DVR007": if($arsenal[$i+1] == "UP" && $attackType == "W" && $attackSubType == "Sword") HalaGoldenhelmAbility($mainPlayer, $i); break;
      default: break;
    }
  }
  return $modifier;
}

function CharacterPlayCardAbilities($cardID, $from)
{
  global $currentPlayer, $CS_NumLess3PowAAPlayed, $CS_NumAttacks;
  $character = &GetPlayerCharacter($currentPlayer);
  for($i=0; $i<count($character); $i+=CharacterPieces())
  {
    if($character[$i+1] != 2) continue;
    $characterID = $character[$i];
    if ($i == 0 && $character[0] == "CRU097") {
      $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
      $otherCharacter = &GetPlayerCharacter($otherPlayer);
      if (SearchCurrentTurnEffects($otherCharacter[0] . "-SHIYANA", $currentPlayer)) {
        $characterID = $otherCharacter[0];
      }
    }
    switch($characterID)
    {
      case "UPR158":
        if(GetClassState($currentPlayer, $CS_NumLess3PowAAPlayed) == 2 && AttackValue($cardID) <= 2)
        {
          AddCurrentTurnEffect($characterID, $currentPlayer);
          WriteLog(CardLink($characterID, $characterID) . " gives the attack +1 and makes the damage unable to be prevented.");
          $character[$i+1] = 1;
        }
        break;
      case "CRU046":
        if (GetClassState($currentPlayer, $CS_NumAttacks) == 2) {
          AddCurrentTurnEffect($characterID, $currentPlayer);
          $character[$i + 1] = 1;
        }
        break;
      default:
        break;
    }
  }
}

function MainCharacterPlayCardAbilities($cardID, $from)
{
  global $currentPlayer, $mainPlayer, $CS_NumNonAttackCards, $CS_NumBoostPlayed;
  $character = &GetPlayerCharacter($currentPlayer);
  for ($i = 0; $i < count($character); $i += CharacterPieces()) {
    if ($character[$i + 1] != 2) continue;
    $characterID = $character[$i];
    if ($i == 0 && $character[0] == "CRU097") {
      $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
      $otherCharacter = &GetPlayerCharacter($otherPlayer);
      if (SearchCurrentTurnEffects($otherCharacter[0] . "-SHIYANA", $currentPlayer)) {
        $characterID = $otherCharacter[0];
      }
    }
    switch ($characterID) {
      case "ARC075": case "ARC076": //Viserai
        if (!IsStaticType(CardType($cardID), $from, $cardID) && ClassContains($cardID, "RUNEBLADE", $currentPlayer)) {
          AddLayer("TRIGGER", $currentPlayer, $characterID, $cardID);
        }
        break;
      case "CRU161":
        if (ActionsThatDoArcaneDamage($cardID) && SearchCharacterActive($currentPlayer, "CRU161") && IsCharacterActive($currentPlayer, FindCharacterIndex($currentPlayer, "CRU161"))) {
          AddLayer("TRIGGER", $currentPlayer, "CRU161");
        }
        break;
      case "ELE062": case "ELE063":
        if (CardType($cardID) == "A" && GetClassState($currentPlayer, $CS_NumNonAttackCards) == 2 && $from != "PLAY") {
          AddLayer("TRIGGER", $currentPlayer, $characterID);
        }
        break;
      case "EVR120": case "UPR102": case "UPR103": //Iyslander
        if ($currentPlayer != $mainPlayer && TalentContains($cardID, "ICE", $currentPlayer) && !IsStaticType(CardType($cardID), $from, $cardID)) {
          AddLayer("TRIGGER", $currentPlayer, $characterID);
        }
        break;
      case "DYN088":
        $numBoostPlayed = 0;
        if (HasBoost($cardID))
        {
          $numBoostPlayed = GetClassState($currentPlayer, $CS_NumBoostPlayed) + 1;
          SetClassState($currentPlayer, $CS_NumBoostPlayed, $numBoostPlayed);
        }
        if ($numBoostPlayed == 3)
        {
          $index = FindCharacterIndex($currentPlayer, "DYN088");
          ++$character[$index + 2];
        }
        break;
      case "DYN113": case "DYN114":
        if(ContractType($cardID) != "")
        {
          AddLayer("TRIGGER", $currentPlayer, $characterID);
        }
        break;
      case "OUT003":
        if(HasStealth($cardID))
        {
          WriteLog("Arakni gives the attack Go Again.");
          GiveAttackGoAgain();
          $character[$i + 1] = 1;//Once per turn
        }
        break;
      case "OUT091": case "OUT092": //Riptide
        if ($from == "HAND") {
          AddLayer("TRIGGER", $currentPlayer, $characterID, $cardID);
        }
        break;
      default:
        break;
    }
  }
}

function ArsenalPlayCardAbilities($cardID)
{
  global $currentPlayer;
  $cardType = CardType($cardID);
  $arsenal = GetArsenal($currentPlayer);
  for($i=0; $i<count($arsenal); $i+=ArsenalPieces())
  {
    switch($arsenal[$i])
    {
      case "MON407": if($arsenal[$i+1] == "UP" && $cardType == "A") LordSutcliffeAbility($currentPlayer, $i); break;
      default: break;
    }
  }
}

function HasIncreasedAttack()
{
  global $combatChain;
  if(count($combatChain) > 0)
  {
    $attack = 0;
    $defense = 0;
    EvaluateCombatChain($attack, $defense);
    if($attack > AttackValue($combatChain[0])) return true;
  }
  return false;
}

function DamageTrigger($player, $damage, $type, $source="NA")
{
  AddDecisionQueue("DEALDAMAGE", $player, $damage . "-" . $source . "-" . $type);
  return $damage;
}

function CanDamageBePrevented($player, $damage, $type, $source="-")
{
  $otherPlayer = $player == 1 ? 2 : 1;
  if($type == "ARCANE" && SearchCurrentTurnEffects("EVR105", $player)) return false;
  if(SearchCurrentTurnEffects("UPR158", $otherPlayer)) return false;
  if($source == "DYN005" || $source == "OUT030" || $source == "OUT031" || $source == "OUT032"|| $source == "OUT121" || $source == "OUT122" || $source == "OUT123") return false;
  return true;
}

function DealDamageAsync($player, $damage, $type="DAMAGE", $source="NA")
{
  global $CS_DamagePrevention, $combatChainState, $combatChain, $mainPlayer;
  global $CCS_AttackFused, $CS_ArcaneDamagePrevention, $currentPlayer, $dqVars, $dqState;

  $classState = &GetPlayerClassState($player);
  $Items = &GetItems($player);

  if($type == "COMBAT" || $type == "ATTACKHIT") $source = $combatChain[0];
  $otherPlayer = $player == 1 ? 2 : 1;
  $damage = $damage > 0 ? $damage : 0;
  $damageThreatened = $damage;
  $preventable = CanDamageBePrevented($player, $damage, $type, $source);
  if($preventable)
  {
    $damage = CurrentEffectPreventDamagePrevention($player, $type, $damage, $source);
    if(ConsumeDamagePrevention($player)) return 0;//If damage can be prevented outright, don't use up your limited damage prevention
    if($type == "ARCANE")
    {
      if($damage <= $classState[$CS_ArcaneDamagePrevention])
      {
        $classState[$CS_ArcaneDamagePrevention] -= $damage;
        $damage = 0;
      }
      else
      {
        $damage -= $classState[$CS_ArcaneDamagePrevention];
        $classState[$CS_ArcaneDamagePrevention] = 0;
      }
    }
    if($damage <= $classState[$CS_DamagePrevention])
    {
      $classState[$CS_DamagePrevention] -= $damage;
      $damage = 0;
    }
    else
    {
      $damage -= $classState[$CS_DamagePrevention];
      $classState[$CS_DamagePrevention] = 0;
    }
  }
  //else: CR 2.0 6.4.10h If damage is not prevented, damage prevention effects are not consumed
  $damage = $damage > 0 ? $damage : 0;
  $damage = CurrentEffectDamagePrevention($player, $type, $damage, $source, $preventable);
  $damage = AuraTakeDamageAbilities($player, $damage, $type);
  $damage = PermanentTakeDamageAbilities($player, $damage, $type);
  $damage = ItemTakeDamageAbilities($player, $damage, $type);
  if($damage == 1 && $preventable && SearchItemsForCard("EVR069", $player) != "") $damage = 0;//Must be last
  $dqVars[0] = $damage;
  if($type == "COMBAT") $dqState[6] = $damage;
  PrependDecisionQueue("FINALIZEDAMAGE", $player, $damageThreatened . "," . $type . "," . $source);
  if($damage > 0)
  {
    AddDamagePreventionSelection($player, $damage, $preventable);
  }
  return $damage;
}

function AddDamagePreventionSelection($player, $damage, $preventable)
{
  PrependDecisionQueue("PROCESSDAMAGEPREVENTION", $player, $damage . "-" . $preventable, 1);
  PrependDecisionQueue("CHOOSEMULTIZONE", $player, "<-", 1);
  PrependDecisionQueue("SETDQCONTEXT", $player, "Choose a card to prevent damage", 1);
  PrependDecisionQueue("FINDINDICES", $player, "DAMAGEPREVENTION");
}

function FinalizeDamage($player, $damage, $damageThreatened, $type, $source)
{
  global $otherPlayer, $CS_DamageTaken, $combatChainState, $CCS_AttackTotalDamage, $CS_ArcaneDamageTaken, $defPlayer, $mainPlayer;
  global $CCS_AttackFused;
  $classState = &GetPlayerClassState($player);
  $Auras = &GetAuras($player);
  $otherPlayer = $player == 1 ? 2 : 1;
  if($damage > 0)
  {
    if($source != "NA")
    {
      $damage += CurrentEffectDamageModifiers($player, $source, $type);
      $otherCharacter = &GetPlayerCharacter($otherPlayer);
      if(($otherCharacter[0] == "ELE062" || $otherCharacter[0] == "ELE063" || SearchCurrentTurnEffects("ELE062-SHIYANA", $mainPlayer) || SearchCurrentTurnEffects("ELE063-SHIYANA", $mainPlayer))
      && $type == "ARCANE" && $otherCharacter[1] == "2" && CardType($source) == "AA" && !SearchAuras("ELE109", $otherPlayer)) {
        PlayAura("ELE109", $otherPlayer);
      }
      if(($source == "ELE067" || $source == "ELE068" || $source == "ELE069") && $combatChainState[$CCS_AttackFused]) AddCurrentTurnEffect($source, $mainPlayer);
      if($source == "DYN173" && SearchCurrentTurnEffects("DYN173", $mainPlayer, true)) {
        WriteLog("Player " . $mainPlayer . " drew a card and Player " . $otherPlayer . " must discard a card");
        Draw($mainPlayer);
        PummelHit();
      }
      if ($source == "DYN612") GainHealth($damage, $mainPlayer);
    }

    AuraDamageTakenAbilities($Auras, $damage);
    ItemDamageTakenAbilities($player, $damage);
    // The second condition after the OR is for when Merciful is destroyed, the target is lost for some reason
    if(SearchAuras("MON013", $otherPlayer) && (IsHeroAttackTarget() || !IsAllyAttackTarget() && $source == "MON012")) { LoseHealth(CountAura("MON013", $otherPlayer), $player); WriteLog("Lost 1 health from Ode to Wrath."); }
    $classState[$CS_DamageTaken] += $damage;
    if($player == $defPlayer && $type == "COMBAT" || $type == "ATTACKHIT") $combatChainState[$CCS_AttackTotalDamage] += $damage;
    if($source == "MON229") AddNextTurnEffect("MON229", $player);
    if($type == "ARCANE") $classState[$CS_ArcaneDamageTaken] += $damage;
    CurrentEffectDamageEffects($player, $source, $type, $damage);
  }
  if($damage > 0 && ($type == "COMBAT" || $type == "ATTACKHIT") && SearchCurrentTurnEffects("ELE037-2", $otherPlayer) && IsHeroAttackTarget())
  { for($i=0; $i<$damage; ++$i) PlayAura("ELE111", $player); }
  PlayerLoseHealth($player, $damage);
  LogDamageStats($player, $damageThreatened, $damage);
  return $damage;
}

function ProcessDealDamageEffect($cardID)
{
  $set = CardSet($cardID);
  if($set == "UPR") {
    return UPRDealDamageEffect($cardID);
  }
}

function ArcaneDamagePrevented($player, $cardMZIndex)
{
  $prevented = 0;
  $params = explode("-", $cardMZIndex);
  $zone = $params[0];
  $index = $params[1];
  switch($zone)
  {
    case "MYCHAR": $source = &GetPlayerCharacter($player); break;
    case "MYITEMS": $source = &GetItems($player); break;
    case "MYAURAS": $source = &GetAuras($player); break;
  }
  if($zone == "MYCHAR" && $source[$index+1] == 0) return;
  $cardID = $source[$index];
  $spellVoidAmount = SpellVoidAmount($cardID, $player);
  if($spellVoidAmount > 0)
  {
    if($zone == "MYCHAR") DestroyCharacter($player, $index);
    else if($zone == "MYITEMS") DestroyItemForPlayer($player, $index);
    else if($zone == "MYAURAS") DestroyAura($player, $index);
    $prevented += $spellVoidAmount;
    WriteLog(CardLink($cardID, $cardID) . " was destroyed and prevented " . $spellVoidAmount . " arcane damage.");
  }
  return $prevented;
}

function CurrentEffectDamageModifiers($player, $source, $type)
{
  global $currentTurnEffects;
  $modifier = 0;
  for($i=count($currentTurnEffects)-CurrentTurnPieces(); $i >= 0; $i-=CurrentTurnPieces())
  {
    $remove = 0;
    switch($currentTurnEffects[$i])
    {
      case "ELE059": case "ELE060": case "ELE061": if($type == "COMBAT" || $type == "ATTACKHIT") ++$modifier; break;
      case "ELE186": case "ELE187": case "ELE188": if(TalentContainsAny($source, "LIGHTNING,ELEMENTAL", $player)) ++$modifier; break;
      default: break;
    }
    if($remove == 1) RemoveCurrentTurnEffect($i);
  }
  return $modifier;
}

function CurrentEffectDamageEffects($target, $source, $type, $damage)
{
  global $currentTurnEffects;
  if (CardType($source) == "AA" && (SearchAuras("CRU028", 1) || SearchAuras("CRU028", 2))) return;
  for($i=count($currentTurnEffects)-CurrentTurnPieces(); $i >= 0; $i-=CurrentTurnPieces())
  {
    if($currentTurnEffects[$i+1] == $target) continue;
    $remove = 0;
    switch($currentTurnEffects[$i])
    {
      case "ELE044": case "ELE045": case "ELE046": if(IsHeroAttackTarget() && CardType($source) == "AA") PlayAura("ELE111", $target); break;
      case "ELE050": case "ELE051": case "ELE052": if(IsHeroAttackTarget() && CardType($source) == "AA") PayOrDiscard($target, 1); break;
      case "ELE064": if(IsHeroAttackTarget() && $source == "ELE064") BlossomingSpellbladeDamageEffect($target); break;
      case "UPR106": case "UPR107": case "UPR108":
        if((IsHeroAttackTarget() || (IsHeroAttackTarget() == "" && $source != "ELE111")) && $type == "ARCANE") {
          PlayAura("ELE111", $target, $damage); $remove = 1;
        } break;
      default: break;
    }
    if($remove == 1) RemoveCurrentTurnEffect($i);
  }
}

function AttackDamageAbilities($damageDone)
{
  global $combatChain, $defPlayer;
  $attackID = $combatChain[0];
  switch($attackID)
  {
    case "ELE036":
      if(IsHeroAttackTarget() && $damageDone >= NumEquipment($defPlayer))
      { AddCurrentTurnEffect("ELE036", $defPlayer); AddNextTurnEffect("ELE036", $defPlayer); }
      break;
    default: break;
  }
}

function LoseHealth($amount, $player)
{
  PlayerLoseHealth($player, $amount);
}

function GainHealth($amount, $player)
{
  $otherPlayer = ($player == 1 ? 2 : 1);
  $health = &GetHealth($player);
  $otherHealth = &GetHealth($otherPlayer);
  if(SearchCurrentTurnEffects("MON229", $player)) { WriteLog(CardLink("MON229","MON229") . " prevented you from gaining health."); return; }
  if(SearchCharacterForCard($player, "CRU140") || SearchCharacterForCard($otherPlayer, "CRU140"))
  {
    if($health > $otherHealth)
    {
      WriteLog("Reaping Blade prevented player " . $player . " from gaining " . $amount . " health.");
      return false;
    }
  }
  WriteLog("Player " . $player . " gained " . $amount . " health.");
  $health += $amount;
  return true;
}

function PlayerLoseHealth($player, $amount)
{
  $health = &GetHealth($player);
  $amount = AuraLoseHealthAbilities($player, $amount);
  $health -= $amount;
  if($health <= 0)
  {
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
  global $winner, $turn, $gameName, $p1id, $p2id, $p1uid, $p2uid, $p1IsChallengeActive, $p2IsChallengeActive, $conceded, $currentTurn;
  global $p1DeckLink, $p2DeckLink, $inGameStatus, $GameStatus_Over, $firstPlayer, $p1deckbuilderID, $p2deckbuilderID;
  if($turn[0] == "OVER") return;
  include_once "./MenuFiles/ParseGamefile.php";

  $winner = $playerID;
  if ($playerID == 1 && $p1uid != "") WriteLog($p1uid . " wins!", $playerID);
  elseif ($playerID == 2 && $p2uid != "") WriteLog($p2uid . " wins!", $playerID);
  else WriteLog("Player " . $winner . " wins!");

  $inGameStatus = $GameStatus_Over;
  $turn[0] = "OVER";
  try {
    logCompletedGameStats();
  } catch (Exception $e) {

  }

  if(!$conceded || $currentTurn >= 3) {
    //If this happens, they left a game in progress -- add disconnect logging?
  }
}

function UnsetBanishModifier($player, $modifier, $newMod="DECK")
{
  $banish = &GetBanish($player);
  for($i=0; $i<count($banish); $i+=BanishPieces())
  {
    $cardModifier = explode("-", $banish[$i+1])[0];
    if($cardModifier == $modifier) $banish[$i+1] = $newMod;
  }
}

function UnsetChainLinkBanish()
{
  UnsetBanishModifier(1, "TCL");
  UnsetBanishModifier(2, "TCL");
}

function UnsetCombatChainBanish()
{
  UnsetBanishModifier(1, "TCC");
  UnsetBanishModifier(2, "TCC");
  UnsetBanishModifier(1, "TCL");
  UnsetBanishModifier(2, "TCL");
}

function ReplaceBanishModifier($player, $oldMod, $newMod)
{
  UnsetBanishModifier($player, $oldMod, $newMod);
}

function UnsetTurnBanish()
{
  global $defPlayer;
  UnsetBanishModifier(1, "TT");
  UnsetBanishModifier(1, "INST");
  UnsetBanishModifier(2, "TT");
  UnsetBanishModifier(2, "INST");
  UnsetBanishModifier(1, "ARC119");
  UnsetBanishModifier(2, "ARC119");
  UnsetCombatChainBanish();
  ReplaceBanishModifier($defPlayer, "NT", "TT");
}

function GetChainLinkCards($playerID="", $cardType="", $exclCardTypes="")
{
  global $combatChain;
  $pieces = "";
  $exclArray=explode(",", $exclCardTypes);
  for($i=0; $i<count($combatChain); $i+=CombatChainPieces())
  {
    $thisType = CardType($combatChain[$i]);
    if(($playerID == "" || $combatChain[$i+1] == $playerID) && ($cardType == "" || $thisType == $cardType))
    {
      $excluded = false;
      for($j=0; $j<count($exclArray); ++$j)
      {
        if($thisType == $exclArray[$j]) $excluded = true;
      }
      if($excluded) continue;
      if($pieces != "") $pieces .= ",";
      $pieces .= $i;
    }
  }
  return $pieces;
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
  global $combatChain, $mainPlayer, $currentTurnEffects;
  if($combatChain[0] == "MON245" && !ExudeConfidenceReactionsPlayable())
  {
    AddCurrentTurnEffect($combatChain[0], $mainPlayer, "CC");
  }
  switch($combatChain[0])
  {
    case "CRU051": case "CRU052":
      EvaluateCombatChain($totalAttack, $totalBlock);
      for ($i = CombatChainPieces(); $i < count($combatChain); $i += CombatChainPieces()) {
        if (!($totalBlock > 0 && (intval(BlockValue($combatChain[$i])) + BlockModifier($combatChain[$i], "CC", 0) + $combatChain[$i + 6]) > $totalAttack)) {
          UndestroyCurrentWeapon();
        }
      }
      break;
      default: break;
  }
}

function CombatChainClosedMainCharacterEffects()
{
  global $chainLinks, $chainLinkSummary, $combatChain, $mainPlayer;
  $character = &GetPlayerCharacter($mainPlayer);
  for($i=0; $i<count($chainLinks); ++$i)
  {
    for($j=0; $j<count($chainLinks[$i]); $j += ChainLinksPieces())
    {
      if($chainLinks[$i][$j+1] != $mainPlayer) continue;
      $charIndex = FindCharacterIndex($mainPlayer, $chainLinks[$i][$j]);
      if($charIndex == -1) continue;
      switch($chainLinks[$i][$j])
      {
        case "CRU051": case "CRU052":
          if($character[$charIndex+7] == "1") DestroyCharacter($mainPlayer, $charIndex);
          break;
        default: break;
      }
    }
  }
}

function CombatChainClosedCharacterEffects()
{
  global $chainLinks, $defPlayer, $chainLinkSummary, $combatChain;
  $character = &GetPlayerCharacter($defPlayer);
  for($i=0; $i<count($chainLinks); ++$i)
  {
    $nervesOfSteelActive = $chainLinkSummary[$i * ChainLinkSummaryPieces() + 1] <= 2 && SearchAuras("EVR023", $defPlayer);
    for($j=0; $j<count($chainLinks[$i]); $j += ChainLinksPieces())
    {
      if($chainLinks[$i][$j+1] != $defPlayer) continue;
      $charIndex = FindCharacterIndex($defPlayer, $chainLinks[$i][$j]);
      if($charIndex == -1) continue;
      if(!$nervesOfSteelActive)
      {
        if(HasTemper($chainLinks[$i][$j]))
        {
          $character[$charIndex+4] -= 1;//Add -1 block counter
          if((BlockValue($character[$charIndex]) + $character[$charIndex + 4] + BlockModifier($character[$charIndex], "CC", 0) + $chainLinks[$i][$j + 5]) <= 0)
          {
            DestroyCharacter($defPlayer, $charIndex);
          }
        }
        if(HasBattleworn($chainLinks[$i][$j]))
        {
          $character[$charIndex+4] -= 1;//Add -1 block counter
        }
        else if(HasBladeBreak($chainLinks[$i][$j]))
        {
          DestroyCharacter($defPlayer, $charIndex);
        }
        else if (HasDynamicBlock($chainLinks[$i][$j])) {
          if ($character[$charIndex + 4] != 0) DestroyCharacter($defPlayer, $charIndex);
        }
      }
      switch($chainLinks[$i][$j])
      {
        case "MON089":
          if(!DelimStringContains($chainLinkSummary[$i * ChainLinkSummaryPieces() + 3], "ILLUSIONIST") && $chainLinkSummary[$i * ChainLinkSummaryPieces() + 1] >= 6)
          {
            $character[FindCharacterIndex($defPlayer, "MON089")+1] = 0;
          }
          break;
        case "RVD003":
          Writelog("Processing " . Cardlink($chainLinks[$i][$j], $chainLinks[$i][$j]) . " trigger: ");
          $deck = &GetDeck($defPlayer);
          $rv = "";
          if (count($deck) == 0) $rv .= "Your deck is empty. No card is revealed.";
          $wasRevealed = RevealCards($deck[0]);
          if ($wasRevealed) {
            if (AttackValue($deck[0]) < 6) {
              WriteLog("The card was put on the bottom of your deck.");
              array_push($deck, array_shift($deck));
            }
          }
          break;
        default: break;
      }
    }
  }
}

// CR 2.1 - 5.3.4c A card with the type defense reaction becomes a defending card and is moved onto the current chain link instead of being moved to the graveyard.
function NumDefendedFromHand() //Reprise
{
  global $combatChain, $defPlayer;
  $num = 0;
  for($i=0; $i<count($combatChain); $i += CombatChainPieces())
  {
    if($combatChain[$i+1] == $defPlayer)
    {
      $type = CardType($combatChain[$i]);
      if($type != "I" && $combatChain[$i+2] == "HAND") ++$num;
    }
  }
  return $num;
}

function NumBlockedFromHand() //Dominate
{
  global $combatChain, $defPlayer, $layers;
  $num = 0;
  for ($i = 0; $i < count($combatChain); $i += CombatChainPieces()) {
    if ($combatChain[$i + 1] == $defPlayer) {
      $type = CardType($combatChain[$i]);
      if ($type != "I" && $combatChain[$i + 2] == "HAND") ++$num;
    }
  }
  for ($i = 0; $i < count($layers); $i += LayerPieces()) {
    $params = explode("|", $layers[$i + 2]);
    if ($params[0] == "HAND" && CardType($layers[$i]) == "DR") ++$num;
  }
  return $num;
}

function NumActionBlocked()
{
  global $combatChain, $defPlayer;
  $num = 0;
  for ($i = 0; $i < count($combatChain); $i += CombatChainPieces()) {
    if ($combatChain[$i + 1] == $defPlayer) {
      $type = CardType($combatChain[$i]);
      if ($type == "A" || $type == "AA") ++$num;
    }
  }
  return $num;
}

function NumCardsBlocking()
{
  global $combatChain, $defPlayer;
  $num = 0;
  for($i=0; $i<count($combatChain); $i += CombatChainPieces())
  {
    if($combatChain[$i+1] == $defPlayer)
    {
      $type = CardType($combatChain[$i]);
      if($type != "I" && $type != "C") ++$num;
    }
  }
  return $num;
}

function NumCardsNonEquipBlocking()
{
  global $combatChain, $defPlayer;
  $num = 0;
  for ($i = 0; $i < count($combatChain); $i += CombatChainPieces()) {
    if ($combatChain[$i + 1] == $defPlayer) {
      $type = CardType($combatChain[$i]);
      if ($type != "E" && $type != "I" && $type != "C") ++$num;
    }
  }
  return $num;
}

function NumAttacksBlocking()
{
  global $combatChain, $defPlayer;
  $num = 0;
  for($i=0; $i<count($combatChain); $i += CombatChainPieces())
  {
    if($combatChain[$i+1] == $defPlayer)
    {
      if(CardType($combatChain[$i]) == "AA") ++$num;
    }
  }
  return $num;
}

function NumActionsBlocking()
{
  global $combatChain, $defPlayer;
  $num = 0;
  for($i=0; $i<count($combatChain); $i += CombatChainPieces())
  {
    if($combatChain[$i+1] == $defPlayer)
    {
      $cardType = CardType($combatChain[$i]);
      if($cardType == "A" || $cardType == "AA") ++$num;
    }
  }
  return $num;
}

function NumNonAttackActionBlocking()
{
  global $combatChain, $defPlayer;
  $num = 0;
  for($i=0; $i<count($combatChain); $i += CombatChainPieces())
  {
    if($combatChain[$i+1] == $defPlayer)
    {
      $type = CardType($combatChain[$i]);
      if($type == "A") ++$num;
    }
  }
  return $num;
}

function NumReactionBlocking()
{
  global $combatChain, $defPlayer;
  $num = 0;
  for($i=0; $i<count($combatChain); $i += CombatChainPieces())
  {
    if($combatChain[$i+1] == $defPlayer)
    {
      $type = CardType($combatChain[$i]);
      if($type == "AR" || $type == "DR") ++$num;
    }
  }
  return $num;
}

function IHaveLessHealth()
{
  global $currentPlayer;
  return PlayerHasLessHealth($currentPlayer);
}

function DefHasLessHealth()
{
  global $defPlayer;
  return PlayerHasLessHealth($defPlayer);
}

function PlayerHasLessHealth($player)
{
  $otherPlayer = ($player == 1 ? 2 : 1);
  return GetHealth($player) < GetHealth($otherPlayer);
}

function PlayerHasFewerEquipment($player)
{
  $otherPlayer = ($player == 1 ? 2 : 1);
  $thisChar = &GetPlayerCharacter($player);
  $thatChar = &GetPlayerCharacter($otherPlayer);
  $thisEquip = 0;
  $thatEquip = 0;
  for($i=0; $i<count($thisChar); $i+=CharacterPieces())
  {
    if($thisChar[$i+1] != 0 && CardType($thisChar[$i]) == "E") ++$thisEquip;
  }
  for($i=0; $i<count($thatChar); $i+=CharacterPieces())
  {
    if($thatChar[$i+1] != 0 && CardType($thatChar[$i]) == "E") ++$thatEquip;
  }
  return $thisEquip < $thatEquip;
}

function GetIndices($count, $add=0, $pieces=1)
{
  $indices = "";
  for($i=0; $i<$count; $i+=$pieces)
  {
    if($indices != "") $indices .= ",";
    $indices .= ($i + $add);
  }
  return $indices;
}

function GetMyHandIndices()
{
  global $currentPlayer;
  return GetIndices(count(GetHand($currentPlayer)));
}

function GetDefHandIndices()
{
  global $defPlayer;
  return GetIndices(count(GetHand($defPlayer)));
}

function CurrentAttack()
{
  global $combatChain;
  if(count($combatChain) == 0) return "";
  return $combatChain[0];
}

function RollDie($player, $fromDQ=false, $subsequent=false)
{
  global $CS_DieRoll;
  $numRolls = 1 + CountCurrentTurnEffects("EVR003", $player);
  $highRoll = 0;
  for($i=0; $i<$numRolls; ++$i)
  {
    $roll = GetRandom(1, 6);
    WriteLog($roll . " was rolled.");
    if($roll > $highRoll) $highRoll = $roll;
  }
  AddEvent("ROLL", $highRoll);
  SetClassState($player, $CS_DieRoll, $highRoll);
  $GGActive = HasGamblersGloves(1) || HasGamblersGloves(2);
  if($GGActive)
  {
    if($fromDQ && !$subsequent) PrependDecisionQueue("AFTERDIEROLL", $player, "-");
    GamblersGloves($player, $player, $fromDQ);
    GamblersGloves(($player == 1 ? 2 : 1), $player, $fromDQ);
    if(!$fromDQ && !$subsequent) AddDecisionQueue("AFTERDIEROLL", $player, "-");
  }
  else
  {
    if(!$subsequent) AfterDieRoll($player);
  }
}

function AfterDieRoll($player)
{
  global $CS_DieRoll, $CS_HighestRoll;
  $roll = GetClassState($player, $CS_DieRoll);
  $skullCrusherIndex = FindCharacterIndex($player, "EVR001");
  if($skullCrusherIndex > -1 && IsCharacterAbilityActive($player, $skullCrusherIndex))
  {
    if($roll == 1) { WriteLog("Skull Crushers was destroyed."); DestroyCharacter($player, $skullCrusherIndex); }
    if($roll == 5 || $roll == 6) { WriteLog("Skull Crushers gives +1 this turn."); AddCurrentTurnEffect("EVR001", $player); }
  }
  if($roll > GetClassState($player, $CS_HighestRoll)) SetClassState($player, $CS_HighestRoll, $roll);
}

function HasGamblersGloves($player)
{
  $gamblersGlovesIndex = FindCharacterIndex($player, "CRU179");
  return $gamblersGlovesIndex != -1 && IsCharacterAbilityActive($player, $gamblersGlovesIndex);
}

function GamblersGloves($player, $origPlayer, $fromDQ)
{
  $gamblersGlovesIndex = FindCharacterIndex($player, "CRU179");
  if(HasGamblersGloves($player))
  {
    if($fromDQ)
    {
      PrependDecisionQueue("ROLLDIE", $origPlayer, "1", 1);
      PrependDecisionQueue("DESTROYCHARACTER", $player, "-", 1);
      PrependDecisionQueue("PASSPARAMETER", $player, $gamblersGlovesIndex, 1);
      PrependDecisionQueue("NOPASS", $player, "-");
      PrependDecisionQueue("YESNO", $player, "if_you_want_to_destroy_Gambler's_Gloves_to_reroll_the_result");
    }
    else
    {
      AddDecisionQueue("YESNO", $player, "if_you_want_to_destroy_Gambler's_Gloves_to_reroll_the_result");
      AddDecisionQueue("NOPASS", $player, "-");
      AddDecisionQueue("PASSPARAMETER", $player, $gamblersGlovesIndex, 1);
      AddDecisionQueue("DESTROYCHARACTER", $player, "-", 1);
      AddDecisionQueue("ROLLDIE", $origPlayer, "1", 1);
    }
  }
}

function IsCharacterAbilityActive($player, $index, $checkGem=false)
{
  $character = &GetPlayerCharacter($player);
  if($checkGem && $character[$index+9] == 0) return false;
  return $character[$index+1] == 2;
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

function CanPlayAsInstant($cardID, $index=-1, $from="")
{
  global $currentPlayer, $CS_NextWizardNAAInstant, $CS_NextNAAInstant, $CS_CharacterIndex, $CS_ArcaneDamageTaken, $CS_NumWizardNonAttack;
  global $mainPlayer, $CS_PlayedAsInstant;
  $otherPlayer = $currentPlayer == 1 ? 2 : 1;
  $cardType = CardType($cardID);
  $otherCharacter = &GetPlayerCharacter($otherPlayer);
  if($cardID == "MON034" && SearchItemsForCard("DYN066", $currentPlayer) != "") return true;
  if(GetClassState($currentPlayer, $CS_NextWizardNAAInstant))
  {
    if(ClassContains($cardID, "WIZARD", $currentPlayer) && $cardType == "A") return true;
  }
  if(GetClassState($currentPlayer, $CS_NumWizardNonAttack) && ($cardID == "CRU174" || $cardID == "CRU175" || $cardID == "CRU176")) return true;
  if($currentPlayer != $mainPlayer && ($cardID == "CRU165" || $cardID == "CRU166" || $cardID == "CRU167")) return true;
  if(GetClassState($currentPlayer, $CS_NextNAAInstant))
  {
    if($cardType == "A") return true;
  }
  if($cardType == "C" || $cardType == "E" || $cardType == "W")
  {
    if($index == -1) $index = GetClassState($currentPlayer, $CS_CharacterIndex);
    if(SearchCharacterEffects($currentPlayer, $index, "INSTANT")) return true;
  }
  if($from == "BANISH")
  {
    $banish = GetBanish($currentPlayer);
    if($index < count($banish))
    {
      $mod = explode("-", $banish[$index+1])[0];
      if(($cardType == "I" && ($mod == "TCL" || $mod == "TT" || $mod == "TCC" || $mod == "NT" || $mod == "MON212")) || $mod == "INST" || $mod == "ARC119") return true;
    }
  }
  if(GetClassState($currentPlayer, $CS_PlayedAsInstant) == "1") return true;
  if($cardID == "ELE106" || $cardID == "ELE107" || $cardID == "ELE108") { return PlayerHasFused($currentPlayer); }
  if($cardID == "CRU143") { return GetClassState($otherPlayer, $CS_ArcaneDamageTaken) > 0; }
  if($from == "ARS" && $cardType == "A" && $currentPlayer != $mainPlayer && PitchValue($cardID) == 3 && (SearchCharacterActive($currentPlayer, "EVR120") || SearchCharacterActive($currentPlayer, "UPR102") || SearchCharacterActive($currentPlayer, "UPR103") || (SearchCharacterActive($currentPlayer, "CRU097") && SearchCurrentTurnEffects($otherCharacter[0] . "-SHIYANA", $currentPlayer) && isIyslander($otherCharacter[0])))) return true;
  $isStaticType = IsStaticType($cardType, $from, $cardID);
  $abilityType = "-";
  if($isStaticType) $abilityType = GetAbilityType($cardID, $index, $from);
  if(($cardType == "AR" || ($abilityType == "AR" && $isStaticType)) && IsReactionPhase() && $currentPlayer == $mainPlayer) return true;
  if(($cardType == "DR" || ($abilityType == "DR" && $isStaticType)) && IsReactionPhase() && $currentPlayer != $mainPlayer && IsDefenseReactionPlayable($cardID, $from)) return true;
  return false;
}

function ClassOverride($cardID, $player="")
{
  global $currentTurnEffects;
  $cardClass = CardClass($cardID);
  if ($cardClass == "NONE") $cardClass = "";
  $otherPlayer = ($player == 1 ? 2 : 1);
  $otherCharacter = &GetPlayerCharacter($otherPlayer);

  if(SearchCurrentTurnEffects("UPR187", $player)) return "NONE";//Erase Face
  if(SearchCurrentTurnEffects($otherCharacter[0] . "-SHIYANA", $player)) {
    if ($cardClass != "") $cardClass .= ",";
    $cardClass .= CardClass($otherCharacter[0]) . ",SHAPESHIFTER";
  }

  for($i=0; $i<count($currentTurnEffects); $i+=CurrentTurnEffectPieces())
  {
    if($currentTurnEffects[$i+1] != $player) continue;
    $toAdd = "";
    switch($currentTurnEffects[$i])
    {
      case "MON095": case "MON096": case "MON097": $toAdd = "ILLUSIONIST";
      case "EVR150": case "EVR151": case "EVR152": $toAdd = "ILLUSIONIST";
      case "UPR155": case "UPR156": case "UPR157": $toAdd = "ILLUSIONIST";
      default: break;
    }
    if($toAdd != "")
    {
      if($cardClass != "") $cardClass .= ",";
      $cardClass .= $toAdd;
    }
  }
  if($cardClass == "") return "NONE";
  return $cardClass;
}

function NameOverride($cardID, $player="")
{

}

function ClassContains($cardID, $class, $player="")
{
  $cardClass = ClassOverride($cardID, $player);
  return DelimStringContains($cardClass, $class);
}

function SubtypeContains($cardID, $subtype, $player="")
{
  $cardSubtype = CardSubtype($cardID);
  return DelimStringContains($cardSubtype, $subtype);
}

function CardNameContains($cardID, $name, $player="")
{
  $cardName = NameOverride($cardID, $player);
  return DelimStringContains($cardName, $name);
}

function TalentOverride($cardID, $player="")
{
  global $currentTurnEffects;
  $cardTalent = CardTalent($cardID);

  // CR 2.2.1 - 6.3.6. Continuous effects that remove a property, or part of a property, from an object do not remove properties, or parts of properties, that were added by another effect.
  if(SearchCurrentTurnEffects("UPR187", $player)) $cardTalent = "NONE";

  for($i=0; $i<count($currentTurnEffects); $i+=CurrentTurnEffectPieces())
  {
    $toAdd = "";
    if($currentTurnEffects[$i+1] != $player) continue;
    switch($currentTurnEffects[$i])
    {
      case "UPR060": case "UPR061": case "UPR062": $toAdd = "DRACONIC";
      default: break;
    }
    if($toAdd != "")
    {
      if($cardTalent == "NONE") $cardTalent = "";
      if($cardTalent != "") $cardTalent .= ",";
      $cardTalent .= $toAdd;
    }
  }
  return $cardTalent;
}

function TalentContains($cardID, $talent, $player="")
{
  $cardTalent = TalentOverride($cardID, $player);
  return DelimStringContains($cardTalent, $talent);
}

//talents = comma delimited list of talents to check
function TalentContainsAny($cardID, $talents, $player="")
{
  $cardTalent = TalentOverride($cardID, $player);
  //Loop over current turn effects to find modifiers
  $talentArr = explode(",", $talents);
  for($i=0; $i<count($talentArr); ++$i)
  {
    if(DelimStringContains($cardTalent, $talentArr[$i])) return true;
  }
  return false;
}

function RevealCards($cards, $player="")
{
  global $currentPlayer;
  if($player == "") $player = $currentPlayer;
  if(!CanRevealCards($player)) return false;
  $cardArray = explode(",", $cards);
  $string = "";
  for($i=0; $i<count($cardArray); ++$i)
  {
    if($string != "") $string .= ", ";
    $string .= CardLink($cardArray[$i], $cardArray[$i]);
    AddEvent("REVEAL", $cardArray[$i]);
  }
  $string .= (count($cardArray) == 1 ? " is" : " are");
  $string .= " revealed.";
  WriteLog($string);
  if($player != "" && SearchLandmark("ELE000")) KorshemRevealAbility($player);
  return true;
}

function DoesAttackHaveGoAgain()
{
  global $combatChain, $combatChainState, $CCS_CurrentAttackGainedGoAgain, $mainPlayer, $defPlayer, $CS_NumRedPlayed, $CS_NumNonAttackCards;
  global $CS_NumAuras, $CS_ArcaneDamageTaken, $myDeck, $CS_AnotherWeaponGainedGoAgain;

  if(count($combatChain) == 0) return false;//No combat chain, so no
  $attackType = CardType($combatChain[0]);
  $attackSubtype = CardSubType($combatChain[0]);
  if(CurrentEffectPreventsGoAgain()) return false;
  if(SearchCurrentTurnEffects("ELE147", $mainPlayer)) return false; //Blizzard
  if(HasGoAgain($combatChain[0])) return true;
  if(ClassContains($combatChain[0], "ILLUSIONIST", $mainPlayer))
  {
    if(SearchCharacterForCard($mainPlayer, "MON003") && SearchPitchForColor($mainPlayer, 2) > 0) return true;
    if(DelimStringContains(CardSubtype($combatChain[0]), "Aura") && SearchCharacterForCard($mainPlayer, "MON088")) return true;
  }
  if(SearchAuras("UPR139", $mainPlayer)) return false;//Hypothermia
  if($combatChainState[$CCS_CurrentAttackGainedGoAgain] == 1 || CurrentEffectGrantsGoAgain() || MainCharacterGrantsGoAgain()) return true;
  if(ClassContains($combatChain[0], "ILLUSIONIST", $mainPlayer))
  {
    if($attackType == "AA" && SearchAuras("MON013", $mainPlayer)) return true;
  }
  if(DelimStringContains($attackSubtype, "Dragon") && GetClassState($mainPlayer, $CS_NumRedPlayed) > 0 && (SearchCharacterActive($mainPlayer, "UPR001") || SearchCharacterActive($mainPlayer, "UPR002") || SearchCurrentTurnEffects("UPR001-SHIYANA", $mainPlayer) || SearchCurrentTurnEffects("UPR002-SHIYANA", $mainPlayer))) return true;

  // Unnatural Go Again - Important for Hypotermia
  $mainPitch = &GetPitch($mainPlayer);
  switch ($combatChain[0])
  {
    case "WTR083": case "WTR084":
      return ComboActive($combatChain[0]);
    case "WTR095": case "WTR096": case "WTR097":
      return ComboActive($combatChain[0]);
    case "WTR104": case "WTR105": case "WTR106":
      return ComboActive($combatChain[0]);
    case "WTR110": case "WTR111": case "WTR112":
      return ComboActive($combatChain[0]);
    case "WTR161":
      return count($myDeck) == 0;
    case "ARC197": case "ARC198": case "ARC199":
      return GetClassState($mainPlayer, $CS_NumNonAttackCards) > 0;
    case "CRU010": case "CRU011": case "CRU012":
      if(NumCardsNonEquipBlocking() < 2) return true;
    case "CRU057": case "CRU058": case "CRU059":
    case "CRU060": case "CRU061": case "CRU062":
      return ComboActive($combatChain[0]);
    case "CRU151": case "CRU152": case "CRU153":
      return GetClassState($defPlayer, $CS_ArcaneDamageTaken) > 0;
    case "MON180": case "MON181": case "MON182":
      return GetClassState($defPlayer, $CS_ArcaneDamageTaken) > 0;
    case "MON199": case "MON220":
      return (count(GetSoul($defPlayer)) > 0 && !IsAllyAttackTarget());
    case "MON223": case "MON224": case "MON225":
      return NumCardsNonEquipBlocking() < 2;
    case "MON248": case "MON249": case "MON250":
      return SearchHighestAttackDefended() < CachedTotalAttack();
    case "MON293": case "MON294": case "MON295":
      return SearchPitchHighestAttack($mainPitch) > AttackValue($combatChain[0]);
    case "ELE216": case "ELE217": case "ELE218":
      return CachedTotalAttack() > AttackValue($combatChain[0]);
    case "ELE216": case "ELE217": case "ELE218":
      return HasIncreasedAttack();
    case "EVR105":
      return GetClassState($mainPlayer, $CS_NumAuras) > 0;
    case "EVR138":
      return FractalReplicationStats("GoAgain");
    case "UPR046":
    case "UPR063": case "UPR064": case "UPR065":
    case "UPR069": case "UPR070": case "UPR071":
      return NumDraconicChainLinks() >= 2;
    case "UPR048":
      return NumPhoenixFlameChainLinks() >= 1;
    case "UPR092":
      return GetClassState($mainPlayer, $CS_NumRedPlayed) > 1;
    case "DYN047":
      return (ComboActive($combatChain[0]));
    case "DYN056": case "DYN057": case "DYN058":
      return (ComboActive($combatChain[0]));
    case "DYN069": case "DYN070":
      $anotherWeaponGainedGoAgain = GetClassState($mainPlayer, $CS_AnotherWeaponGainedGoAgain);
      if (SameWeaponEquippedTwice()) return $anotherWeaponGainedGoAgain != "-";
      else return $anotherWeaponGainedGoAgain != "-" && $anotherWeaponGainedGoAgain != $combatChain[0];
    default: break;
  }
  return false;
}

function IsEquipUsable($player, $index)
{
  $character = &GetPlayerCharacter($player);
  if($index >= count($character) || $index < 0) return false;
  return $character[$index + 1] == 2;
}


function UndestroyCurrentWeapon()
{
  global $combatChainState, $CCS_WeaponIndex, $mainPlayer;
  $index = $combatChainState[$CCS_WeaponIndex];
  $char = &GetPlayerCharacter($mainPlayer);
  $char[$index+7] = "0";
}

function DestroyCurrentWeapon()
{
  global $combatChainState, $CCS_WeaponIndex, $mainPlayer;
  $index = $combatChainState[$CCS_WeaponIndex];
  $char = &GetPlayerCharacter($mainPlayer);
  $char[$index+7] = "1";
}

function AttackDestroyed($attackID)
{
  global $mainPlayer, $combatChainState, $CCS_GoesWhereAfterLinkResolves;
  $type = CardType($attackID);
  $character = &GetPlayerCharacter($mainPlayer);
  switch($attackID)
  {
    case "EVR139": MirragingMetamorphDestroyed(); break;
    case "EVR144": case "EVR145": case "EVR146": CoalescentMirageDestroyed(); break;
    case "EVR147": case "EVR148": case "EVR149": PlayAura("MON104", $mainPlayer); break;
    case "UPR021": case "UPR022": case "UPR023": PutPermanentIntoPlay($mainPlayer, "UPR043"); break;
    case "UPR027": case "UPR028": case "UPR029": PutPermanentIntoPlay($mainPlayer, "UPR043"); break;
    default: break;
  }
  AttackDestroyedEffects($attackID);
  for($i=0; $i<SearchCount(SearchAurasForCard("MON012", $mainPlayer)); ++$i)
  {
    if(TalentContains($attackID, "LIGHT", $mainPlayer)) $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "SOUL";
    DealArcane(1, 0, "STATIC", "MON012", false, $mainPlayer);
  }
  if($type == "AA" && ClassContains($attackID, "ILLUSIONIST", $mainPlayer))
  {
    for($i=0; $i<count($character); $i += CharacterPieces())
    {
      if($character[$i+1] == 0) continue;
      switch($character[$i]) {
        case 'MON089':
          if ($character[$i+5] > 0){
            AddDecisionQueue("YESNO", $mainPlayer, "if_you_want_to_pay_1_to_gain_an_action_point", 0, 1);
            AddDecisionQueue("NOPASS", $mainPlayer, "-", 1);
            AddDecisionQueue("PASSPARAMETER", $mainPlayer, 1, 1);
            AddDecisionQueue("PAYRESOURCES", $mainPlayer, "<-", 1);
            AddDecisionQueue("GAINACTIONPOINTS", $mainPlayer, "1", 1);
            AddDecisionQueue("WRITELOG", $mainPlayer, "Player_" . $mainPlayer . "_gained_an_action_point_from_" . CardLink($character[$i], $character[$i]), 1);
            --$character[$i+5];
          }
          break;
        default: break;
      }
    }
  }
  if(ClassContains($attackID, "ILLUSIONIST", $mainPlayer) && SearchCharacterActive($mainPlayer, "UPR152"))
  {
    AddDecisionQueue("YESNO", $mainPlayer, "if_you_want_to_pay_3_to_gain_an_action_point", 0, 1);
    AddDecisionQueue("NOPASS", $mainPlayer, "-", 1);
    AddDecisionQueue("PASSPARAMETER", $mainPlayer, 3, 1);
    AddDecisionQueue("PAYRESOURCES", $mainPlayer, "<-", 1);
    AddDecisionQueue("GAINACTIONPOINTS", $mainPlayer, "1", 1);
    AddDecisionQueue("FINDINDICES", $mainPlayer, "EQUIPCARD,UPR152", 1);
    AddDecisionQueue("DESTROYCHARACTER", $mainPlayer, "-", 1);
  }
}

function AttackDestroyedEffects($attackID)
{
  global $currentTurnEffects, $mainPlayer;
  for($i=0; $i<count($currentTurnEffects); $i+=CurrentTurnEffectPieces())
  {
    switch($currentTurnEffects[$i])
    {
      case "EVR150": case "EVR151": case "EVR152": Draw($mainPlayer); break;
      default: break;
    }
  }
}

function CloseCombatChain($chainClosed="true")
{
  global $turn, $currentPlayer, $mainPlayer, $combatChainState, $CCS_AttackTarget, $layers;
  $layers = [];//In case there's another combat chain related layer like defense step
  PrependLayer("FINALIZECHAINLINK", $mainPlayer, $chainClosed);
  $turn[0] = "M";
  $currentPlayer = $mainPlayer;
  $combatChainState[$CCS_AttackTarget] = "NA";
}

function UndestroyCharacter($player, $index)
{
  $char = &GetPlayerCharacter($player);
  $char[$index+1] = 2;
  $char[$index+4] = 0;
}

function DestroyCharacter($player, $index)
{
  $char = &GetPlayerCharacter($player);
  $char[$index+1] = 0;
  $char[$index+4] = 0;
  $cardID = $char[$index];
  AddGraveyard($cardID, $player, "CHAR");
  CharacterDestroyEffect($cardID, $player);
  return $cardID;
}

function RemoveArsenalEffects($player, $cardToReturn){
  SearchCurrentTurnEffects("EVR087", $player, true); //If Dreadbore was played before, its effect on the removed Arsenal card should be removed
  SearchCurrentTurnEffects("ARC042", $player, true); //If Bull's Eye Bracers was played before, its effect on the removed Arsenal card should be removed
  if($cardToReturn == "ARC057" ){SearchCurrentTurnEffects("ARC057", $player, true);} //If the card removed from arsenal is 'Head Shot', remove its current turn effect.
  if($cardToReturn == "ARC058" ){SearchCurrentTurnEffects("ARC058", $player, true);} //Else, another 'Head Shot' played this turn would get dubble buff.
  if($cardToReturn == "ARC059" ){SearchCurrentTurnEffects("ARC059", $player, true);}
}

function LookAtHand($player)
{
  $hand = &GetHand($player);
  $cards = "";
  for($i=0; $i<count($hand); $i+=HandPieces())
  {
    if($cards != "") $cards .= ",";
    $cards .= $hand[$i];
  }
  RevealCards($cards, $player);
}

function GainActionPoints($amount=1, $player=0)
{
  global $actionPoints, $mainPlayer, $currentPlayer;
  if($player == 0) $player = $currentPlayer;
  if($player == $mainPlayer) $actionPoints += $amount;
}

function AddCharacterUses($player, $index, $numToAdd)
{
  $character = &GetPlayerCharacter($player);
  if($character[$index+1] == 0) return;
  $character[$index+1] = 2;
  $character[$index+5] += $numToAdd;
}

function HaveUnblockedEquip($player)
{
  $char = &GetPlayerCharacter($player);
  for($i=CharacterPieces(); $i<count($char); $i+=CharacterPieces())
  {
    if($char[$i+1] == 0) continue;//If broken
    if($char[$i+6] == 1) continue;//On combat chain
    if(CardType($char[$i]) != "E") continue;
    if(BlockValue($char[$i]) == -1) continue;
    return true;
  }
  return false;
}

function NumEquipBlock()
{
  global $combatChain, $defPlayer;
  $numEquipBlock = 0;
  for($i=CombatChainPieces(); $i<count($combatChain); $i+=CombatChainPieces())
  {
    if(CardType($combatChain[$i]) == "E" && $combatChain[$i + 1] == $defPlayer) ++$numEquipBlock;
  }
  return $numEquipBlock;
}

  function CanPassPhase($phase)
  {
    global $combatChainState, $CCS_RequiredEquipmentBlock, $currentPlayer;
    if($phase == "B" && HaveUnblockedEquip($currentPlayer) && NumEquipBlock() < $combatChainState[$CCS_RequiredEquipmentBlock]) return false;
    switch($phase)
    {
      case "P": return 0;
      case "PDECK": return 0;
      case "CHOOSEDECK": return 0;
      case "HANDTOPBOTTOM": return 0;
      case "CHOOSECOMBATCHAIN": return 0;
      case "CHOOSECHARACTER": return 0;
      case "CHOOSEHAND": return 0;
      case "CHOOSEHANDCANCEL": return 0;
      case "MULTICHOOSEDISCARD": return 0;
      case "CHOOSEDISCARDCANCEL": return 0;
      case "CHOOSEARCANE": return 0;
      case "CHOOSEARSENAL": return 0;
      case "CHOOSEDISCARD": return 0;
      case "MULTICHOOSEHAND": return 0;
      case "CHOOSEMULTIZONE": return 0;
      case "CHOOSEBANISH": return 0;
      case "BUTTONINPUTNOPASS": return 0;
      case "CHOOSEFIRSTPLAYER": return 0;
      case "MULTICHOOSEDECK": return 0;
      case "CHOOSEPERMANENT": return 0;
      case "MULTICHOOSETEXT": return 0;
      case "CHOOSEMYSOUL": return 0;
      case "OVER": return 0;
      default: return 1;
    }
  }

  //Returns true if done for that player
  function EndTurnPitchHandling($player)
  {
    global $currentPlayer, $turn;
    $pitch = &GetPitch($player);
    if(count($pitch) == 0)
    {
      return true;
    }
    else if(count($pitch) == 1)
    {
      PitchDeck($player, 0);
      return true;
    }
    else
    {
      $currentPlayer = $player;
      $turn[0] = "PDECK";
      return false;
    }
  }

  function ResolveGoAgain($cardID, $player, $from)
  {
    global $CS_NextNAACardGoAgain, $actionPoints, $mainPlayer;
    $cardType = CardType($cardID);
    $goAgainPrevented = CurrentEffectPreventsGoAgain();
    if(IsStaticType($cardType, $from, $cardID))
    {
      $hasGoAgain = AbilityHasGoAgain($cardID);
      if(!$hasGoAgain && GetResolvedAbilityType($cardID, $from) == "A") $hasGoAgain = CurrentEffectGrantsNonAttackActionGoAgain($cardID);
    }
    else
    {
      $hasGoAgain = HasGoAgain($cardID);
      if(GetClassState($player, $CS_NextNAACardGoAgain) && $cardType == "A")
      {
        $hasGoAgain = true;
        SetClassState($player, $CS_NextNAACardGoAgain, 0);
      }
      if($cardType == "AA" && SearchCurrentTurnEffects("ELE147", $player)) $hasGoAgain = false;
      if($cardType == "A") $hasGoAgain = CurrentEffectGrantsNonAttackActionGoAgain($cardID) || $hasGoAgain;
      if($cardType == "A" && $hasGoAgain && (SearchAuras("UPR190", 1) || SearchAuras("UPR190", 2))) $hasGoAgain = false;
    }
    if($player == $mainPlayer && $hasGoAgain && !$goAgainPrevented) ++$actionPoints;
  }

  function PitchDeck($player, $index)
  {
    $pitch = &GetPitch($player);
    $deck = &GetDeck($player);
    array_push($deck, $pitch[$index]);
    unset($pitch[$index]);
    $pitch = array_values($pitch);
  }

  function GetUniqueId()
  {
    global $permanentUniqueIDCounter;
    ++$permanentUniqueIDCounter;
    return $permanentUniqueIDCounter;
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

  function IsSpecificAllyAttackTarget($player, $index)
  {
    $mzTarget = GetAttackTarget();
    $mzArr = explode("-", $mzTarget);
    if($mzArr[0] == "ALLY" || $mzArr[0] == "MYALLY" || $mzArr[0] == "THEIRALLY")
    {
      return $index == intval($mzArr[1]);
    }
    return false;
  }

  function IsAllyAttacking()
  {
    global $combatChain;
    if(count($combatChain) == 0) return false;
    return DelimStringContains(CardSubtype($combatChain[0]), "Ally");
  }

  function IsSpecificAllyAttacking($player, $index)
  {
    global $combatChain, $combatChainState, $CCS_WeaponIndex, $mainPlayer;
    if(count($combatChain) == 0) return false;
    if($mainPlayer != $player) return false;
    $weaponIndex = intval($combatChainState[$CCS_WeaponIndex]);
    if($weaponIndex == -1) return false;
    if($weaponIndex != $index) return false;
    if(!DelimStringContains(CardSubtype($combatChain[0]), "Ally")) return false;
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
    if(SearchAurasForCard("UPR138", $player) != "" || SearchAurasForCard("UPR138", $otherPlayer) != "") {
      WriteLog("Action prevented by " . CardLink("UPR138", "UPR138"));
      return false;
    }
    return true;
  }

  function BaseAttackModifiers($attackValue)
  {
    global $combatChainState, $CCS_LinkBaseAttack, $currentTurnEffects, $mainPlayer;
    for($i=0; $i<count($currentTurnEffects); $i+=CurrentTurnEffectPieces())
    {
      if($currentTurnEffects[$i+1] != $mainPlayer) continue;
      if(!IsCombatEffectActive($currentTurnEffects[$i])) continue;
      switch($currentTurnEffects[$i])
      {
        case "EVR094": case "EVR095": case "EVR096": $attackValue = ceil($attackValue/2); break;
        default: break;
      }
    }
    return $attackValue;
  }

  function GetDefaultLayerTarget()
  {
    global $layers, $combatChain, $currentPlayer;
    if(count($combatChain) > 0) return $combatChain[0];
    if(count($layers) > 0)
    {
      for($i=count($layers)-LayerPieces(); $i>=0; $i-=LayerPieces())
      {
        if($layers[$i+1] != $currentPlayer) return $layers[$i];
      }
    }
    return "-";
  }

function GetDamagePreventionIndices($player)
{
  $rv = "";
  $auras = &GetAuras($player);
  $indices = "";
  for($i=0; $i<count($auras); $i+=AuraPieces())
  {
    if(AuraDamagePreventionAmount($player, $i) > 0)
    {
      if($indices != "") $indices .= ",";
      $indices .= $i;
    }
  }
  $mzIndices = SearchMultiZoneFormat($indices, "MYAURAS");

  $char = &GetPlayerCharacter($player);
  $indices = "";
  for($i=0; $i<count($char); $i+=CharacterPieces())
  {
    if($char[$i+1] != 0 && WardAmount($char[$i]) > 0)
    {
      if($indices != "") $indices .= ",";
      $indices .= $i;
    }
  }
  $indices = SearchMultiZoneFormat($indices, "MYCHAR");
  $mzIndices = CombineSearches($mzIndices, $indices);

  $ally = &GetAllies($player);
  $indices = "";
  for($i=0; $i<count($ally); $i+=AllyPieces())
  {
    if($ally[$i+1] != 0 && WardAmount($ally[$i]) > 0)
    {
      if($indices != "") $indices .= ",";
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
  if (count($combatChain) > 0) {
    if ($rv != "") $rv .= ",";
    $rv .= "CC-0";
  }
  if (SearchLayer($otherPlayer, "W") == "" && (count($combatChain) == 0 || CardType($combatChain[0]) != "W")) {
    $theirWeapon = SearchMultiZoneFormat(SearchCharacter($otherPlayer, type: "W"), "THEIRCHAR");
    $rv = CombineSearches($rv, $theirWeapon);
  }
  $theirAllies = SearchMultiZoneFormat(SearchAllies($otherPlayer), "THEIRALLY");
  $rv = CombineSearches($rv, $theirAllies);
  $theirAuras = SearchMultiZoneFormat(SearchAura($otherPlayer), "THEIRAURAS");
  $rv = CombineSearches($rv, $theirAuras);
  if (ArsenalHasFaceUpCard($otherPlayer)) {
    $theirArsenal = SearchMultiZoneFormat(SearchArsenal($otherPlayer), "THEIRARS");
    $rv = CombineSearches($rv, $theirArsenal);
  }
  $theirHero = SearchMultiZoneFormat(SearchCharacter($otherPlayer, type: "C"), "THEIRCHAR");
  $rv = CombineSearches($rv, $theirHero);
  return $rv;
}

function SameWeaponEquippedTwice()
{
  global $mainPlayer;
  $char = &GetPlayerCharacter($mainPlayer);
  $weaponIndex = explode(",", SearchCharacter($mainPlayer, "W"));
  if (count($weaponIndex) > 1 && $char[$weaponIndex[0]] == $char[$weaponIndex[1]]) return true;
  return false;
}

function SelfCostModifier($cardID)
{
  global $CS_NumCharged, $currentPlayer, $combatChain, $CS_LayerTarget;
  switch($cardID) {
    case "ARC080":
    case "ARC082":
    case "ARC088": case "ARC089": case "ARC090":
    case "ARC094": case "ARC095": case "ARC096":
    case "ARC097": case "ARC098": case "ARC099":
    case "ARC100": case "ARC101": case "ARC102":
      return (-1 * NumRunechants($currentPlayer));
    case "MON032":
      return (-1 * (2 * GetClassState($currentPlayer, $CS_NumCharged)));
    case "MON084": case "MON085": case "MON086":
      return TalentContains($combatChain[GetClassState($currentPlayer, $CS_LayerTarget)], "SHADOW") ? -1 : 0;
    case "DYN104": case "DYN105": case "DYN106":
      return CountItem("ARC036", $currentPlayer) > 0 || CountItem("DYN111", $currentPlayer) > 0 || CountItem("DYN112", $currentPlayer) > 0 ? -1 : 0;
    case "OUT056": case "OUT057": case "OUT058":
      return (ComboActive($cardID) ? -2 : 0);
    case "OUT074": case "OUT075": case "OUT076":
      return (ComboActive($cardID) ? -1 : 0);
    case "OUT145": case "OUT146": case "OUT147":
      return (-1 * DamageDealtBySubtype("Dagger"));
    default: return 0;
  }
}

function IsAlternativeCostPaid($cardID, $from)
{
  global $currentTurnEffects, $currentPlayer;
  $isAlternativeCostPaid = false;
  for($i = count($currentTurnEffects) - CurrentTurnPieces(); $i >= 0; $i -= CurrentTurnPieces()) {
    $remove = false;
    if($currentTurnEffects[$i + 1] == $currentPlayer) {
      switch($currentTurnEffects[$i]) {
        case "ARC185": case "CRU188": case "MON199": case "MON257": case "EVR161":
          $isAlternativeCostPaid = true;
          $remove = true;
          break;
        default:
          break;
      }
      if($remove) RemoveCurrentTurnEffect($i);
    }
  }
  return $isAlternativeCostPaid;
}

function BanishCostModifier($from, $index)
{
  global $currentPlayer;
  if($from != "BANISH") return 0;
  $banish = GetBanish($currentPlayer);
  $mod = explode("-", $banish[$index + 1]);
  switch($mod[0]) {
    case "ARC119": return -1 * intval($mod[1]);
    default: return 0;
  }
}

function IsCurrentAttackName($name)
{
  $names = GetCurrentAttackNames();
  for($i=0; $i<count($names); ++$i)
  {
    if($name == $names[$i]) return true;
  }
  return false;
}

function GetCurrentAttackNames()
{
  global $combatChain, $currentTurnEffects, $mainPlayer;
  $names = [];
  if(count($combatChain) == 0) return $names;
  array_push($names, CardName($combatChain[0]));
  for($i=0; $i<count($currentTurnEffects); $i+=CurrentTurnEffectPieces())
  {
    $name = "";
    $effectArr = explode("-", $currentTurnEffects[$i]);
    switch($effectArr[0])
    {
      case "OUT049":
        $name = (count($effectArr) > 1 ? $effectArr[1] : "N/A");
        break;
      case "OUT068": case "OUT069": case "OUT070":
        $name = (count($effectArr) > 1 ? $effectArr[1] : "N/A");
        break;
      default: break;
    }
    //You have to do this at the end, or you might have a recursive loop -- e.g. with OUT052
    if($name != "" && $currentTurnEffects[$i+1] == $mainPlayer && IsCombatEffectActive($effectArr[0]) && !IsCombatEffectLimited($i)) array_push($names, $name);
  }
  return $names;
}

function SerializeCurrentAttackNames()
{
  $names = GetCurrentAttackNames();
  $serializedNames = "";
  for($i=0; $i<count($names); ++$i)
  {
    if($serializedNames != "") $serializedNames .= ",";
    $serializedNames .= GamestateSanitize($names[$i]);
  }
  return $serializedNames;
}

function HasAttackName($name)
{
  global $chainLinkSummary;
  for($i=0; $i<count($chainLinkSummary); $i+=ChainLinkSummaryPieces())
  {
    $names = explode(",", $chainLinkSummary[$i+4]);
    for($j=0; $j<count($names); ++$j)
    {
      if($name == GamestateUnsanitize($names[$j])) return true;
    }
  }
  return false;
}

function HasPlayedAttackReaction()
{
  global $combatChain, $mainPlayer;
  for($i=CombatChainPieces(); $i<count($combatChain); $i+=CombatChainPieces())
  {
    if($combatChain[$i+1] != $mainPlayer) continue;
    if(CardType($combatChain[$i]) == "AR" || GetResolvedAbilityType($combatChain[$i])) return true;
  }
  return false;
}

function HitEffectsPreventedThisLink()
{
  global $combatChainState, $CCS_ChainLinkHitEffectsPrevented;
  $combatChainState[$CCS_ChainLinkHitEffectsPrevented] = 1;
}

function EffectPreventsHit()
{
  global $currentTurnEffects, $mainPlayer, $combatChain;
  $preventsHit = false;
  for($i=count($currentTurnEffects)-CurrentTurnPieces(); $i >= 0; $i-=CurrentTurnPieces())
  {
    if($currentTurnEffects[$i+1] != $mainPlayer) continue;
    $remove = 0;
    switch($currentTurnEffects[$i])
    {
      case "OUT108": if(CardType($combatChain[0]) == "AA") { $preventsHit = true; $remove = 1; } break;
      default: break;
    }
    if($remove == 1) RemoveCurrentTurnEffect($i);
  }
  return $preventsHit;
}

function HitsInRow()
{
  global $chainLinkSummary;
  $numHits = 0;
  for($i=count($chainLinkSummary)-ChainLinkSummaryPieces(); $i>=0 && $chainLinkSummary[$i+5] == "1"; $i-=ChainLinkSummaryPieces())
  {
    ++$numHits;
  }
  return $numHits;
}

function HitsInCombatChain()
{
  global $chainLinkSummary;
  $numHits = 0;
  for($i=count($chainLinkSummary)-ChainLinkSummaryPieces(); $i>=0; $i-=ChainLinkSummaryPieces())
  {
    if($chainLinkSummary[$i+5] == "1") ++$numHits;
  }
  return $numHits;
}

function NumAttacksHit()
{
    global $chainLinkSummary;
    $numHits = 0;
    for($i=count($chainLinkSummary)-ChainLinkSummaryPieces(); $i>=0; $i-=ChainLinkSummaryPieces())
    {
      if($chainLinkSummary[$i] > 0) ++$numHits;
    }
    return $numHits;
}

function NumChainLinks()
{
  global $chainLinkSummary, $combatChain;
  $numLinks = count($chainLinkSummary)/ChainLinkSummaryPieces();
  if(count($combatChain) > 0) ++$numLinks;
  return $numLinks;
}
