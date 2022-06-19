<?php

  include "CardSetters.php";
  include "CardGetters.php";

function EvaluateCombatChain(&$totalAttack, &$totalDefense, &$attackModifiers=[])
{
  global $combatChain, $mainPlayer, $currentTurnEffects, $defCharacter, $playerID, $combatChainState, $CCS_ChainAttackBuff, $CCS_LinkBaseAttack;
  global $CCS_WeaponIndex, $mainCharacter, $mainAuras;
    UpdateGameState($playerID);
    BuildMainPlayerGameState();
    $attackType = CardType($combatChain[0]);
    $CanGainAttack = !SearchCurrentTurnEffects("CRU035", $mainPlayer) || $attackType != "AA";
    $SnagActive = SearchCurrentTurnEffects("CRU182", $mainPlayer) && $attackType == "AA";
    for($i=1; $i<count($combatChain); $i+=CombatChainPieces())
    {
      $from = $combatChain[$i+1];
      $resourcesPaid = $combatChain[$i+2];

      if($combatChain[$i] == $mainPlayer)
      {
        if($i == 1) $attack = $combatChainState[$CCS_LinkBaseAttack];
        else $attack = AttackValue($combatChain[$i-1]);
        if($CanGainAttack || $i == 1 || $attack < 0)
        {
          array_push($attackModifiers, $combatChain[$i-1]);
          array_push($attackModifiers, $attack);
          $totalAttack += $attack;
        }
        $attack = AttackModifier($combatChain[$i-1], $combatChain[$i+1], $combatChain[$i+2], $combatChain[$i+3]) + $combatChain[$i + 4];
        if(($CanGainAttack && !$SnagActive) || $attack < 0)
        {
          array_push($attackModifiers, $combatChain[$i-1]);
          array_push($attackModifiers, $attack);
          $totalAttack += $attack;
        }
      }
      else
      {
        $defense = BlockValue($combatChain[$i-1]) + BlockModifier($combatChain[$i-1], $from, $resourcesPaid) + $combatChain[$i + 5];
        if(CardType($combatChain[$i-1]) == "E")
        {
          $index = FindDefCharacter($combatChain[$i-1]);
          $defense += $defCharacter[$index+4] + DefCharacterBlockModifier($index);
        }
        if($defense > 0) $totalDefense += $defense;
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
          if($CanGainAttack || $attack < 0)
          {
            array_push($attackModifiers, $currentTurnEffects[$i]);
            array_push($attackModifiers, $attack);
            $totalAttack += $attack;
          }
        }
        else
        {
          $totalDefense += EffectBlockModifier($currentTurnEffects[$i], "", 0);
        }
      }
    }

    if($combatChainState[$CCS_WeaponIndex] != -1)
    {
      $attack = 0;
      if($attackType == "W") $attack = $mainCharacter[$combatChainState[$CCS_WeaponIndex]+3];
      else if(DelimStringContains(CardSubtype($combatChain[0]), "Aura")) $attack = $mainAuras[$combatChainState[$CCS_WeaponIndex]+3];
      if($CanGainAttack || $attack < 0)
      {
        array_push($attackModifiers, "+1 Attack Counters");
        array_push($attackModifiers, $attack);
        $totalAttack += $attack;
      }
    }
    $attack = MainCharacterAttackModifiers();//TODO: If there are both negatives and positives here, this might mess up?...
    if($CanGainAttack || $attack < 0)
    {
      array_push($attackModifiers, "Character/Equipment");
      array_push($attackModifiers, $attack);
      $totalAttack += $attack;
    }
    $attack = AuraAttackModifiers(0);//TODO: If there are both negatives and positives here, this might mess up?...
    if($CanGainAttack || $attack < 0)
    {
      array_push($attackModifiers, "Aura Ability");
      array_push($attackModifiers, $attack);
      $totalAttack += $attack;
    }
    $attack = ArsenalAttackModifier();
    if($CanGainAttack || $attack < 0)
    {
      array_push($attackModifiers, "Arsenal Ability");
      array_push($attackModifiers, $attack);
      $totalAttack += $attack;
    }
    $attack = $combatChainState[$CCS_ChainAttackBuff];
    if($CanGainAttack || $attack < 0)
    {
      array_push($attackModifiers, "Whole combat chain buff");
      array_push($attackModifiers, $attack);
      $totalAttack += $attack;
    }
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
  array_push($combatChain, 0);//Attack modifier
  array_push($combatChain, ResourcesPaidBlockModifier($cardID, $resourcesPaid));//Defense modifier
  if($turn[0] == "B" || CardType($cardID) == "DR") OnBlockEffects($index, $from);
  CurrentEffectAttackAbility();
  return $index;
}

function CacheCombatResult()
{
  global $combatChain, $combatChainState, $CCS_CachedTotalAttack, $CCS_CachedTotalBlock;
  if(count($combatChain) == 0) return;
  $combatChainState[$CCS_CachedTotalAttack] = 0;
  $combatChainState[$CCS_CachedTotalBlock] = 0;
  EvaluateCombatChain($combatChainState[$CCS_CachedTotalAttack], $combatChainState[$CCS_CachedTotalBlock]);
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

function StartTurnAbilities()
{
  global $mainPlayer, $defPlayer;
  $mainCharacter = &GetPlayerCharacter($mainPlayer);
  for($i=count($mainCharacter) - CharacterPieces(); $i>=0; $i -= CharacterPieces())
  {
    CharacterStartTurnAbility($i);
  }
  DefCharacterStartTurnAbilities();
  AuraStartTurnAbilities();
  AllyStartTurnAbilities($mainPlayer);
  $mainItems = &GetItems($mainPlayer);
  for($i=count($mainItems)-ItemPieces(); $i>= 0; $i-=ItemPieces())
  {
    if($mainItems[$i+2] == 1) $mainItems[$i+2] = 2;
    ItemStartTurnAbility($i);
  }
  $defItems = &GetItems($defPlayer);
  for($i=0; $i<count($defItems); $i+=ItemPieces())
  {
    if($defItems[$i+2] == 1) $defItems[$i+2] = 2;
  }
  ArsenalStartTurnAbilities();
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
      case "UPR086": $cards = CombineSearches($cards, SearchMultiZoneFormat($i, "MYDISCARD")); break;
      default: break;
    }
  }
  return $cards;
}

function MZStartTurnAbility($cardID)
{
  switch($cardID)
  {
    case "UPR086": break;
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
          AddDecisionQueue("YESNO", $mainPlayer, "if_you_want_to_flip_over_your_mentor_card");
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
  global $currentPlayer, $mainPlayer, $CS_NumNonAttackCards, $CS_NumLess3PowAAPlayed;
  $character = &GetPlayerCharacter($currentPlayer);
  for($i=0; $i<count($character); $i+=CharacterPieces())
  {
    if($character[$i+1] != 2) continue;
    switch($character[$i])
    {
      case "EVR120": case "UPR102": case "UPR103":
        if($currentPlayer != $mainPlayer && TalentContains($cardID, "ICE"))
        {
          PlayAura("ELE111", $mainPlayer);
          WriteLog("Iyslander created a Frostbite token for playing an ice card.");
        }
        break;
      case "ARC075": case "ARC076":
        if(!IsStaticType(CardType($cardID), $from, $cardID)) ViseraiPlayCard($cardID);
        break;
      case "ELE062": case "ELE063":
        if(CardType($cardID) == "A" && GetClassState($currentPlayer, $CS_NumNonAttackCards) == 2)
        {
          PlayAura("ELE110", $currentPlayer);
          WriteLog("Briar created an Embodiment of Lightning aura.");
        }
        break;
      case "UPR158":
        if(GetClassState($currentPlayer, $CS_NumLess3PowAAPlayed) == 2 && AttackValue($cardID) <= 2)
        {
          AddCurrentTurnEffect($character[$i], $currentPlayer);
          WriteLog("Tiger Strike Shuko gives the attack +1 and makes the damage unable to be prevented.");
          $character[$i+1] = 1;
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
  AddDecisionQueue("DEALDAMAGE", $player, $damage . "-" . $source . "-" . $type, 1);
  return $damage;
}

function CanDamageBePrevented($player, $damage, $type, $source="-")
{
  if($type == "ARCANE" && SearchCurrentTurnEffects("EVR105", $player)) return false;
  if(SearchCurrentTurnEffects("UPR158", $player)) return false;
  return true;
}

function DealDamageAsync($player, $damage, $type="DAMAGE", $source="NA")
{
  global $CS_DamagePrevention, $combatChainState, $combatChain;
  global $CCS_AttackFused, $CS_ArcaneDamagePrevention;

  $classState = &GetPlayerClassState($player);
  $Items = &GetItems($player);

  if($type == "COMBAT" || $type == "ATTACKHIT") $source = $combatChain[0];
  $otherPlayer = $player == 1 ? 2 : 1;
  $damage = $damage > 0 ? $damage : 0;
  $damageThreatened = $damage;
  if(CanDamageBePrevented($player, $damage, $type, $source))
  {
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
    $damage -= CurrentEffectDamagePrevention($player, $type, $damage);
    for($i=count($Items) - ItemPieces(); $i >= 0 && $damage > 0; $i -= ItemPieces())
    {
      if($Items[$i] == "CRU104")
      {
        if($damage > $Items[$i+1]) { $damage -= $Items[$i+1]; $Items[$i+1] = 0; }
        else { $Items[$i+1] -= $damage; $damage = 0; }
        if($Items[$i+1] <= 0) DestroyItem($Items, $i);
      }
    }
  }
  $damage = $damage > 0 ? $damage : 0;
  $damage = AuraTakeDamageAbilities($player, $damage, $type);
  $damage = PermanentTakeDamageAbilities($player, $damage, $type);
  if($damage == 1 && SearchItemsForCard("EVR069", $player) != "") $damage = 0;//Must be last
  if($damage > 0 && $source != "NA")
  {
    $damage += CurrentEffectDamageModifiers($source, $type);
    $otherCharacter = &GetPlayerCharacter($otherPlayer);
    if(($otherCharacter[0] == "ELE062" || $otherCharacter[0] == "ELE063") && $otherCharacter[1] == "2" && CardType($source) == "AA" && !SearchAuras("ELE109", $otherPlayer)) PlayAura("ELE109", $otherPlayer);
    if(($source == "ELE067" || $source == "ELE068" || $source == "ELE069") && $combatChainState[$CCS_AttackFused])
    { AddCurrentTurnEffect($source, $otherPlayer); }
  }
  PrependDecisionQueue("FINALIZEDAMAGE", $player, $damageThreatened . "," . $type . "," . $source);
  //Now prepend orderable replacement effects
  if($type == "ARCANE") PrependArcaneDamageReplacement($player, $damage);
  return $damage;
}

function FinalizeDamage($player, $damage, $damageThreatened, $type, $source)
{
  global $otherPlayer, $CS_DamageTaken, $combatChainState, $CCS_AttackTotalDamage, $CS_ArcaneDamageTaken, $defPlayer;
  $classState = &GetPlayerClassState($player);
  $Auras = &GetAuras($player);
  $otherPlayer = $player == 1 ? 2 : 1;
  if($damage > 0)
  {
    AuraDamageTakenAbilities($Auras, $damage);
    ItemDamageTakenAbilities($player, $damage);
    if(SearchAuras("MON013", $otherPlayer)) { LoseHealth(CountAura("MON013", $otherPlayer), $player); WriteLog("Lost 1 health from Ode to Wrath."); }
    $classState[$CS_DamageTaken] += $damage;
    if($player == $defPlayer && $type == "COMBAT" || $type == "ATTACKHIT") $combatChainState[$CCS_AttackTotalDamage] += $damage;
    if($source == "MON229") AddNextTurnEffect("MON229", $player);
    if($type == "ARCANE") $classState[$CS_ArcaneDamageTaken] += $damage;
    CurrentEffectDamageEffects($player, $source, $type, $damage);
  }
  if($damage > 0 && ($type == "COMBAT" || $type == "ATTACKHIT") && SearchCurrentTurnEffects("ELE037-2", $otherPlayer))
  { for($i=0; $i<$damage; ++$i) PlayAura("ELE111", $player); }
  PlayerLoseHealth($player, $damage);
  LogDamageStats($player, $damageThreatened, $damage);
  return $damage;
}

function PrependArcaneDamageReplacement($player, $damage)
{
  $character = &GetPlayerCharacter($player);
  $search = SearchArcaneReplacement($player, "MYCHAR");
  $indices = SearchMultizoneFormat($search, "MYCHAR");//TODO: Add items, use FINDINDICES
  $search = SearchArcaneReplacement($player, "MYITEMS");
  $indices2 = SearchMultizoneFormat($search, "MYITEMS");//TODO: Add items, use FINDINDICES
  $indices = CombineSearches($indices, $indices2);
  if($indices != "")
  {
    PrependDecisionQueue("ONARCANEDAMAGEPREVENTED", $player, $damage);
    PrependDecisionQueue("MAYCHOOSEMULTIZONE", $player, $indices, 1);
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
  }
  $cardID = $source[$index];
  $spellVoidAmount = SpellVoidAmount($cardID);
  if($spellVoidAmount > 0)
  {
    if($zone == "MYCHAR") DestroyCharacter($player, $index);
    else if($zone == "MYITEMS") DestroyItemForPlayer($player, $index);
    $prevented += $spellVoidAmount;
    WriteLog(CardLink($cardID, $cardID) . " was destroyed and prevented " . $spellVoidAmount . " arcane damage.");
  }
  return $prevented;
}

function CurrentEffectDamageModifiers($source, $type)
{
  global $currentTurnEffects;
  $modifier = 0;
  for($i=count($currentTurnEffects)-CurrentTurnPieces(); $i >= 0; $i-=CurrentTurnPieces())
  {
    $remove = 0;
    switch($currentTurnEffects[$i])
    {
      case "ELE059": case "ELE060": case "ELE061": if($type == "COMBAT" || $type == "ATTACKHIT") ++$modifier; break;
      case "ELE186": case "ELE187": case "ELE188": if(TalentContains($source, "LIGHTNING") || TalentContains($source, "ELEMENTAL")) ++$modifier; break;
      default: break;
    }
    if($remove == 1) RemoveCurrentTurnEffect($i);
  }
  return $modifier;
}

function CurrentEffectDamageEffects($player, $source, $type, $damage)
{
  global $currentTurnEffects;
  for($i=count($currentTurnEffects)-CurrentTurnPieces(); $i >= 0; $i-=CurrentTurnPieces())
  {
    $remove = 0;
    switch($currentTurnEffects[$i])
    {
      case "ELE044": case "ELE045": case "ELE046": if(CardType($source) == "AA") PlayAura("ELE111", $player); break;
      case "ELE050": case "ELE051": case "ELE052": if(CardType($source) == "AA") PayOrDiscard($player, 1); break;
      case "ELE064": BlossomingSpellbladeDamageEffect($player); break;
      case "UPR106": case "UPR107": case "UPR108": if($type == "ARCANE") { PlayAura("ELE111", $player, $damage); $remove = 1; } break;
      default: break;
    }
    if($remove == 1) RemoveCurrentTurnEffect($i);
  }
}

function AttackDamageAbilities()
{
  global $combatChain, $defPlayer, $combatChainState, $CCS_AttackTotalDamage;
  $attackID = $combatChain[0];
  switch($attackID)
  {
    case "ELE036":
      if($combatChainState[$CCS_AttackTotalDamage] >= NumEquipment($defPlayer))
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
  if(SearchCurrentTurnEffects("MON229", $player)) { WriteLog("Dread Scythe prevented you from gaining health."); return; }
  if(SearchCharacterForCard($player, "CRU140") || SearchCharacterForCard($otherPlayer, "CRU140"))
  {
    if($health > $otherHealth) return false;
  }
  PlayerGainHealth($amount, $health);
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

function PlayerGainHealth($amount, &$health)
{
  $health += $amount;
}

function PlayerWon($playerID)
{
  global $winner;
  $winner = $playerID;
  WriteLog("Player " . $playerID . " wins!");
}

function UnsetBanishModifier($player, $modifier, $newMod="DECK")
{
  global $mainPlayer;
  $banish = &GetBanish($mainPlayer);
  for($i=0; $i<count($banish); $i+=BanishPieces())
  {
    if($banish[$i+1] == $modifier) $banish[$i+1] = $newMod;
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

function UnsetMyCombatChainBanish()
{
  global $currentPlayer;
  UnsetBanishModifier($currentPlayer, "TCC");
}

function ReplaceBanishModifier($player, $oldMod, $newMod)
{
  UnsetBanishModifier($player, $oldMod, $newMod);
}

function UnsetTurnBanish()
{
  UnsetBanishModifier(1, "TT");
  UnsetBanishModifier(1, "INST");
  UnsetBanishModifier(2, "TT");
  UnsetBanishModifier(2, "INST");
  UnsetCombatChainBanish();
  ReplaceBanishModifier(1, "NT", "TT");
  ReplaceBanishModifier(2, "NT", "TT");
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

function GetMainPlayerComboCards()
{
  return GetComboCards();
}

function GetComboCards()
{
  global $mainPlayer;
  $deck = &GetDeck($mainPlayer);
  $combo = "";
  for($i=0; $i<count($deck); ++$i)
  {
    if(HasCombo($deck[$i]))
    {
      if($combo != "") $combo .= ",";
      $combo .= $i;
    }
  }
  return $combo;
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

function DestroyItem(&$Items, $index)
{
  unset($Items[$index]);
  unset($Items[$index+1]);
  unset($Items[$index+2]);
  $Items = array_values($Items);
}

function ChainLinkResolvedEffects()
{
  global $combatChain;
  for($i=0; $i<count($combatChain); $i+=CombatChainPieces())
  {
    switch($combatChain[$i])
    {
      case "MON245": break;
      default: break;
    }
  }
}

function CombatChainClosedCharacterEffects()
{
  global $chainLinks, $defPlayer, $chainLinkSummary;
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
          if((BlockValue($character[$charIndex]) + $character[$charIndex + 4]) <= 0)
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
      }
    }
  }
}

function NumBlockedFromHand()
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

//CR 2.0 7.4.2c Defense Reaction abilities do not count as defending cards
function NumCardsBlocking()
{
  global $combatChain, $defPlayer;
  $num = 0;
  for($i=0; $i<count($combatChain); $i += CombatChainPieces())
  {
    if($combatChain[$i+1] == $defPlayer)
    {
      $type = CardType($combatChain[$i]);
      if($type != "E" && $type != "I" && $type != "C") ++$num;
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
  return GetIndices(count(GetHand($currentPlayer)));
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
    $roll = random_int(1, 6);
    WriteLog($roll . " was rolled.");
    if($roll > $highRoll) $highRoll = $roll;
  }
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

function IsCharacterAbilityActive($player, $index)
{
  $character = &GetPlayerCharacter($player);
  return $character[$index+1] == 2;
}

function GetDieRoll($player)
{
  global $CS_DieRoll;
  return GetClassState($player, $CS_DieRoll);
}

function CanPlayAsInstant($cardID, $index=-1, $from="")
{
  global $currentPlayer, $CS_NextWizardNAAInstant, $CS_NextNAAInstant, $CS_CharacterIndex, $CS_ArcaneDamageTaken, $CS_NumWizardNonAttack;
  global $mainPlayer;
  $otherPlayer = $currentPlayer == 1 ? 2 : 1;
  $cardType = CardType($cardID);
  if(GetClassState($currentPlayer, $CS_NextWizardNAAInstant))
  {
    if(CardClass($cardID) == "WIZARD" && $cardType == "A") return true;
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
    $mod = explode("-", $banish[$index+1])[0];
    if($mod == "INST" || $mod == "ARC119") return true;
  }
  if($cardID == "ELE106" || $cardID == "ELE107" || $cardID == "ELE108") { return PlayerHasFused($currentPlayer); }
  if($cardID == "CRU143") { return GetClassState($otherPlayer, $CS_ArcaneDamageTaken) > 0; }
  if($from == "ARS" && $cardType == "A" && $currentPlayer != $mainPlayer && PitchValue($cardID) == 3 && (SearchCharacterActive($currentPlayer, "EVR120") || SearchCharacterActive($currentPlayer, "UPR102") || SearchCharacterActive($currentPlayer, "UPR103"))) return true;
  if($cardType == "AR" && IsReactionPhase() && $currentPlayer == $mainPlayer) return true;
  if($cardType == "DR" && IsReactionPhase() && $currentPlayer != $mainPlayer && IsDefenseReactionPlayable($cardID, $from)) return true;
  return false;
}

function TalentContains($cardID, $talent)
{
  $cardTalent = CardTalent($cardID);
  $talents = explode(",", $cardTalent);
  for($i=0; $i<count($talents); ++$i)
  {
    if($talents[$i] == $talent) return true;
  }
  return false;
}

function RevealCards($cards)
{
  $cardArray = explode(",", $cards);
  $string = "";
  for($i=0; $i<count($cardArray); ++$i)
  {
    if($string != "") $string .= ", ";
    $string .= CardLink($cardArray[$i], $cardArray[$i]);
  }
  $string .= (count($cardArray) == 1 ? " is" : " are");
  $string .= " revealed.";
  WriteLog($string);
  return true;
}

function DoesAttackHaveGoAgain()
{
  global $combatChain, $combatChainState, $CCS_CurrentAttackGainedGoAgain, $mainPlayer, $CS_NumRedPlayed;
  if(count($combatChain) == 0) return false;//No combat chain, so no
  $attackType = CardType($combatChain[0]);
  $attackSubtype = CardSubType($combatChain[0]);
  if(CurrentEffectPreventsGoAgain()) return false;
  if(SearchAuras("UPR139", $mainPlayer)) return false;//Hypothermia
  if(HasGoAgain($combatChain[0]) || $combatChainState[$CCS_CurrentAttackGainedGoAgain] == 1 || CurrentEffectGrantsGoAgain() || MainCharacterGrantsGoAgain()) return true;
  if(CardClass($combatChain[0]) == "ILLUSIONIST")
  {
    if(SearchCharacterForCard($mainPlayer, "MON003") && SearchPitchForColor($mainPlayer, 2) > 0) return true;
    if($attackType == "AA" && SearchAuras("MON013", $mainPlayer)) return true;
    if(DelimStringContains(CardSubtype($combatChain[0]), "Aura") && SearchCharacterForCard($mainPlayer, "MON088")) return true;
  }
  if(DelimStringContains($attackSubtype, "Dragon") && GetClassState($mainPlayer, $CS_NumRedPlayed) > 0 && SearchCharacterActive($mainPlayer, "UPR001") || SearchCharacterActive($mainPlayer, "UPR002")) return true;
  return false;
}

function IsEquipUsable($player, $index)
{
  $character = &GetPlayerCharacter($player);
  if($index >= count($character) || $index < 0) return false;
  return $character[$index + 1] == 2;
}

function DestroyCurrentWeapon()
{
  global $combatChainState, $CCS_WeaponIndex, $mainPlayer;
  $index = $combatChainState[$CCS_WeaponIndex];
  DestroyCharacter($mainPlayer, $index);
}

function AttackDestroyed($attackID)
{
  global $mainPlayer, $combatChainState, $CCS_GoesWhereAfterLinkResolves;
  $type = CardType($attackID);
  $class = CardClass($attackID);
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
    $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "SOUL";
    DealArcane(1, 0, "STATIC", "MON012", false, $mainPlayer);
  }
  if($type == "AA" && $class == "ILLUSIONIST" && SearchCharacterForCard($mainPlayer, "MON089"))
  {
    AddDecisionQueue("YESNO", $mainPlayer, "if_you_want_to_pay_1_to_gain_an_action_point", 0, 1);
    AddDecisionQueue("NOPASS", $mainPlayer, "-", 1);
    AddDecisionQueue("PASSPARAMETER", $mainPlayer, 1, 1);
    AddDecisionQueue("PAYRESOURCES", $mainPlayer, "<-", 1);
    AddDecisionQueue("GAINACTIONPOINTS", $mainPlayer, "1", 1);
  }
  if($class == "ILLUSIONIST" && SearchCharacterForCard($mainPlayer, "UPR152"))
  {
    AddDecisionQueue("YESNO", $mainPlayer, "if_you_want_to_pay_3_to_gain_an_action_point", 0, 1);
    AddDecisionQueue("NOPASS", $mainPlayer, "-", 1);
    AddDecisionQueue("PASSPARAMETER", $mainPlayer, 1, 1);
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
  global $turn, $currentPlayer, $mainPlayer;
  AddDecisionQueue("FINALIZECHAINLINK", $mainPlayer, $chainClosed);
  $turn[0] = "M";
  $currentPlayer = $mainPlayer;
}

function DestroyCharacter($player, $index)
{
  $char = &GetPlayerCharacter($player);
  $char[$index+1] = 0;
  $cardID = $char[$index];
  AddGraveyard($cardID, $player, "CHAR");
  CharacterDestroyEffect($cardID, $player);
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
  RevealCards($cards);
}

function GainActionPoints($amount=1)
{
  global $actionPoints;
  $actionPoints += $amount;
}

function AddCharacterUses($player, $index, $numToAdd)
{
  $character = &GetPlayerCharacter($player);
  if($character[$index+1] == 0) return;
  $character[$index+1] = 2;
  $character[$index+5] += $numToAdd;
}

  function CanPassPhase($phase)
  {
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

?>
