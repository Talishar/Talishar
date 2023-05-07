<?php


//Return 1 if the effect should be removed
function EffectHitEffect($cardID)
{
  global $combatChainState, $CCS_GoesWhereAfterLinkResolves, $defPlayer, $mainPlayer, $CCS_WeaponIndex, $combatChain, $CCS_DamageDealt;
  global $CID_BloodRotPox, $CID_Frailty, $CID_Inertia;
  if(CardType($combatChain[0]) == "AA" && (SearchAuras("CRU028", 1) || SearchAuras("CRU028", 2))) return;
  $attackID = $combatChain[0];
  switch($cardID) {
    case "WTR129": case "WTR130": case "WTR131":
      GiveAttackGoAgain();
      break;
    case "WTR147": case "WTR148": case "WTR149":
      NaturesPathPilgrimageHit();
      break;
    case "WTR206": case "WTR207": case "WTR208": if(IsHeroAttackTarget() && CardType($attackID) == "AA") PummelHit(); break;
    case "WTR209": case "WTR210": case "WTR211": if(CardType($attackID) == "AA") GiveAttackGoAgain(); break;
    case "ARC170-1": case "ARC171-1": case "ARC172-1":
      Draw($mainPlayer);
      return 1;
    case "CRU124":
      if(IsHeroAttackTarget()) PummelHit();
      break;
    case "CRU145": case "CRU146": case "CRU147":
      if(ClassContains($combatChain[0], "RUNEBLADE", $mainPlayer)){
        if ($cardID == "CRU145") $amount = 3;
        else if($cardID == "CRU146") $amount = 2;
        else $amount = 1;
        PlayAura("ARC112", $mainPlayer, $amount);
      }
      break;
    case "CRU084-2":
      PutItemIntoPlayForPlayer("CRU197", $mainPlayer, 0, 2);
      break;
    case "MON034":
      LuminaAscensionHit();
      break;
    case "MON081": case "MON082": case "MON083":
      $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "SOUL";
      break;
    case "MON110": case "MON111": case "MON112":
      DuskPathPilgrimageHit();
      break;
    case "MON193":
      ShadowPuppetryHitEffect();
      break;
    case "MON218":
      if(count(GetSoul($defPlayer)) > 0) {
        BanishFromSoul($defPlayer);
        LoseHealth(1, $defPlayer);
      }
      break;
    case "MON299": case "MON300": case "MON301":
      $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "BOTDECK";
      break;
    case "ELE003":
      if(IsHeroAttackTarget()) {
        PlayAura("ELE111", $defPlayer);
      }
      break;
    case "ELE005":
      if(IsHeroAttackTarget()) {
        $hand = &GetHand($defPlayer);
        $cards = "";
        for($i=0; $i<2 && count($hand) > 0; ++$i)
        {
          $index = GetRandom() % count($hand);
          if($cards != "") $cards .= ",";
          $cards .= $hand[$index];
          unset($hand[$index]);
          $hand = array_values($hand);
        }
        if($cards != "") AddDecisionQueue("CHOOSEBOTTOM", $defPlayer, $cards);
      }
      break;
    case "ELE019": case "ELE020": case "ELE021":
      if(IsHeroAttackTarget()) {
        AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "THEIRARS", 1);
        AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose which card you want to put at the bottom of the deck", 1);
        AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, "<-", 1);
        AddDecisionQueue("MZADDZONE", $mainPlayer, "THEIRBOTDECK", 1);
        AddDecisionQueue("MZREMOVE", $mainPlayer, "-", 1);
      }
      break;
    case "ELE022": case "ELE023": case "ELE024":
      if(IsHeroAttackTarget()) PlayAura("ELE111", $defPlayer);
      break;
    case "ELE035-2":
      if(IsHeroAttackTarget()) {
        AddCurrentTurnEffect("ELE035-3", $defPlayer);
        AddNextTurnEffect("ELE035-3", $defPlayer);
      }
      break;
    case "ELE037-2":
      if(IsHeroAttackTarget()) DamageTrigger($defPlayer, 1, "ATTACKHIT");
      break;
    case "ELE047": case "ELE048": case "ELE049":
      if(IsHeroAttackTarget()) DamageTrigger($defPlayer, 1, "ATTACKHIT");
      break;
    case "ELE066-HIT":
      AddLayer("TRIGGER", $mainPlayer, "ELE066");
      break;
    case "ELE092-BUFF":
      if(IsHeroAttackTarget()) DamageTrigger($defPlayer, 3, "ATTACKHIT");
      break;
    case "ELE151-HIT": case "ELE152-HIT": case "ELE153-HIT":
      if(IsHeroAttackTarget()) PlayAura("ELE111", $defPlayer);
      break;
    case "ELE163":
      if (IsHeroAttackTarget()) PlayAura("ELE111", $defPlayer, 3);
      break;
    case "ELE164":
      if (IsHeroAttackTarget()) PlayAura("ELE111", $defPlayer, 2);
      break;
    case "ELE165":
      if (IsHeroAttackTarget()) PlayAura("ELE111", $defPlayer);
      break;
    case "ELE173":
      if(IsHeroAttackTarget()) DamageTrigger($defPlayer, 1, "ATTACKHIT");
      return 1;
    case "ELE195": case "ELE196": case "ELE197":
      if(IsHeroAttackTarget()) DamageTrigger($defPlayer, 1, "ATTACKHIT");
      break;
    case "ELE198": case "ELE199": case "ELE200":
      if(IsHeroAttackTarget()) {
        if($cardID == "ELE198") $damage = 3;
        else if($cardID == "ELE199") $damage = 2;
        else $damage = 1;
        DamageTrigger($defPlayer, $damage, "ATTACKHIT");
        return 1;
      }
      break;
    case "ELE205":
      if(IsHeroAttackTarget()) {
        PummelHit();
        PummelHit();
      }
      break;
    case "ELE215":
      if(IsHeroAttackTarget()) {
        AddNextTurnEffect($cardID . "-1", $defPlayer);
      }
      break;
    case "EVR047-1": case "EVR048-1": case "EVR049-1":
      $idArr = explode("-", $cardID);
      AddCurrentTurnEffectFromCombat($idArr[0] . "-2", $mainPlayer);
      break;
    case "EVR066-1": case "EVR067-1": case "EVR068-1":
      PutItemIntoPlayForPlayer("CRU197", $mainPlayer);
      return 1;
    case "EVR161-1":
      GainHealth(2, $mainPlayer);
      break;
    case "EVR164": case "EVR165": case "EVR166":
      if($cardID == "EVR164") $amount = 6;
      else if($cardID == "EVR165") $amount = 4;
      else $amount = 2;
      PutItemIntoPlayForPlayer("CRU197", $mainPlayer, 0, $amount);
      return 1;
    case "EVR170-1": case "EVR171-1": case "EVR172-1":
      if(IsHeroAttackTarget()) {
        AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "THEIRITEMS:maxCost=2");
        AddDecisionQueue("MAYCHOOSEMULTIZONE", $mainPlayer, "<-", 1);
        AddDecisionQueue("MZDESTROY", $mainPlayer, "-", 1);
        return 1;
      }
      break;
    case "DVR008-1":
      $char = &GetPlayerCharacter($mainPlayer);
      if(IsHeroAttackTarget()) {
        ++$char[$combatChainState[$CCS_WeaponIndex] + 3];
      }
      break;
    case "DYN028":
      Mangle();
      break;
    case "DYN071":
      AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "THEIRALLY", 1);
      AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose a target to deal " . $combatChainState[$CCS_DamageDealt] . " damage.");
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $mainPlayer, "<-", 1);
      AddDecisionQueue("MZDAMAGE", $mainPlayer, $combatChainState[$CCS_DamageDealt] . ",DAMAGE," . $cardID, 1);
      break;
    case "DYN155":
      if(IsHeroAttackTarget() && HasAimCounter()) {
        AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "THEIRHAND");
        AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose which card you want your opponent to discard", 1);
        AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, "<-", 1);
        AddDecisionQueue("MZDISCARD", $mainPlayer, "HAND,-," . $mainPlayer, 1);
        AddDecisionQueue("MZREMOVE", $mainPlayer, "-", 1);
      }
      break;
    case "DYN185-HIT": case "DYN186-HIT": case "DYN187-HIT":
      if(ClassContains($combatChain[0], "RUNEBLADE", $mainPlayer)) {
        if($cardID == "DYN185-HIT") $amount = 3;
        else if($cardID == "DYN186-HIT") $amount = 2;
        else $amount = 1;
        PlayAura("ARC112", $mainPlayer, $amount, true);
      }
      break;
    case "OUT021":
      if(IsHeroAttackTarget()) PlayAura($CID_BloodRotPox, $defPlayer);
      break;
    case "OUT022":
      if(IsHeroAttackTarget()) PlayAura($CID_Frailty, $defPlayer);
      break;
    case "OUT023":
      if(IsHeroAttackTarget()) PlayAura($CID_Inertia, $defPlayer);
      break;
    case "OUT105":
      if(IsHeroAttackTarget() && HasAimCounter()) {
        AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "THEIRCHAR:minAttack=1;maxAttack=1;type=W");//TODO: Limit to 1H
        AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose a weapon to destroy");
        AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, "<-", 1);
        AddDecisionQueue("MZDESTROY", $mainPlayer, "-", 1);
      }
      break;
    case "OUT112":
      if(IsHeroAttackTarget()) PlayAura($CID_BloodRotPox, $defPlayer);
      break;
    case "OUT113":
      if(IsHeroAttackTarget()) PlayAura($CID_Frailty, $defPlayer);
      break;
    case "OUT114":
      if(IsHeroAttackTarget()) PlayAura($CID_Inertia, $defPlayer);
      break;
    case "OUT140":
      WriteLog("Mask of Shifting Perspectives lets you sink a card");
      BottomDeck($mainPlayer, true, shouldDraw:true);
      break;
    case "OUT143":
      $char = &GetPlayerCharacter($mainPlayer);
      $charClass = CardClass($char[0]);
      $weapons = ($charClass == "NINJA" ? "WTR078,CRU051" : "DYN115,OUT005,OUT007,OUT009");
      AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose a weapon to equip (make sure you choose one in your inventory)");
      AddDecisionQueue("CHOOSECARD", $mainPlayer, $weapons);
      AddDecisionQueue("EQUIPCARD", $mainPlayer, "<-");
      break;
    case "OUT158":
      if(IsHeroAttackTarget())
      {
        AddDecisionQueue("CHOOSECARD", $mainPlayer, $CID_BloodRotPox . "," . $CID_Frailty . "," . $CID_Inertia);
        AddDecisionQueue("PUTPLAY", $defPlayer, "-", 1);
      }
      break;
    case "OUT165": LoseHealth(5, $defPlayer); break;
    case "OUT166": LoseHealth(4, $defPlayer); break;
    case "OUT167": LoseHealth(3, $defPlayer); break;
    case "OUT188_1": if(IsHeroAttackTarget()) { PlayAura("DYN244", $mainPlayer); return 1; } break;
    default:
      break;
  }
  return 0;
}

function EffectAttackModifier($cardID)
{
  $set = CardSet($cardID);
  if($set == "WTR") return WTREffectAttackModifier($cardID);
  else if($set == "ARC") return ARCEffectAttackModifier($cardID);
  else if($set == "CRU") return CRUEffectAttackModifier($cardID);
  else if($set == "MON") return MONEffectAttackModifier($cardID);
  else if($set == "ELE") return ELEEffectAttackModifier($cardID);
  else if($set == "EVR") return EVREffectAttackModifier($cardID);
  else if($set == "DVR") return DVREffectAttackModifier($cardID);
  else if($set == "RVD") return RVDEffectAttackModifier($cardID);
  else if($set == "UPR") return UPREffectAttackModifier($cardID);
  else if($set == "DYN") return DYNEffectAttackModifier($cardID);
  else if($set == "OUT") return OUTEffectAttackModifier($cardID);
  else if($set == "ROG") return ROGUEEffectAttackModifier($cardID);
  return 0;
}

function EffectHasBlockModifier($cardID)
{
  switch($cardID)
  {
    case "MON089":
    case "ELE000-2":
    case "ELE143":
    case "ELE203":
    case "DYN115": case "DYN116":
    case "OUT005": case "OUT006":
    case "OUT007": case "OUT008":
    case "OUT009": case "OUT010":
    case "OUT109":
    case "OUT110":
    case "OUT111":
    return true;
    default: return false;
  }
}

function EffectBlockModifier($cardID, $index)
{
  global $combatChain, $defPlayer, $mainPlayer;
  switch($cardID) {
    case "MON089":
      if($combatChain[$index] == $cardID) return 1;
      return 0;
    case "ELE000-2":
      return 1;
    case "ELE143":
      return 1;
    case "ELE203":
      return ($combatChain[$index] == "ELE203" ? 1 : 0);
    case "OUT109":
      return (PitchValue($combatChain[$index]) == 1 && HasAimCounter() ? -1 : 0);
    case "OUT110":
      return (PitchValue($combatChain[$index]) == 2 && HasAimCounter() ? -1 : 0);
    case "OUT111":
      return (PitchValue($combatChain[$index]) == 3 && HasAimCounter() ? -1 : 0);
    default:
      return 0;
  }
}

function RemoveEffectsOnChainClose()
{
  global $currentTurnEffects;
  for($i = count($currentTurnEffects) - CurrentTurnPieces(); $i >= 0; $i -= CurrentTurnPieces()) {
    $remove = false;
    $effectArr = explode("-", $currentTurnEffects[$i]);
    $effectArr2 = explode(",", $effectArr[0]);
    switch($effectArr2[0]) {
      case "CRU106": case "CRU107": case "CRU108": //High Speed Impact
      case "CRU109": case "CRU110": case "CRU111": // Combustible Courier
      case "MON035": //V of the Vanguard
      case "MON245": //Exude Confidence
      case "ELE067": case "ELE068": case "ELE069": //Explosive Growth
      case "ELE186": case "ELE187": case "ELE188": //Ball Lightning
      case "UPR049": //Spreading Flames
      case "DYN095": case "DYN096": case "DYN097": //Scramble Pulse
      case "OUT033": case "OUT034": case "OUT035": //Prowl
      case "OUT052": //Head Leads the Tail
      case "OUT071": case "OUT072": case "OUT073": //Deadly Duo
        $remove = 1;
        break;
      default:
        break;
    }
    if($remove) RemoveCurrentTurnEffect($i);
  }
}

function OnAttackEffects($attack)
{
  global $currentTurnEffects, $mainPlayer, $defPlayer;
  $attackType = CardType($attack);
  for($i = count($currentTurnEffects) - CurrentTurnPieces(); $i >= 0; $i -= CurrentTurnPieces()) {
    $remove = false;
    if($currentTurnEffects[$i + 1] == $mainPlayer) {
      switch($currentTurnEffects[$i]) {
        case "ELE085": case "ELE086": case "ELE087":
          if($attackType == "AA") {
            DealArcane(1, 0, "PLAYCARD", $attack, true);
            $remove = true;
          }
          break;
        case "ELE092-DOM":
          AddDecisionQueue("SETDQCONTEXT", $defPlayer, "Do you want to pay 2 to prevent this attack from getting dominate?", 1);
          AddDecisionQueue("BUTTONINPUT", $defPlayer, "0,2", 0, 1);
          AddDecisionQueue("PAYRESOURCES", $defPlayer, "<-", 1);
          AddDecisionQueue("GREATERTHANPASS", $defPlayer, "0", 1);
          AddDecisionQueue("ADDIMMEDIATECURRENTEFFECT", $mainPlayer, $currentTurnEffects[$i] . "ATK", 1);
          break;
        default:
          break;
      }
    }
    if($remove) RemoveCurrentTurnEffect($i);
  }
}

function CurrentEffectBaseAttackSet($cardID)
{
  global $currentPlayer, $currentTurnEffects;
  $mod = -1;
  for($i = count($currentTurnEffects) - CurrentTurnPieces(); $i >= 0; $i -= CurrentTurnPieces()) {
    if($currentTurnEffects[$i + 1] == $currentPlayer && IsCombatEffectActive($currentTurnEffects[$i])) {
      switch($currentTurnEffects[$i]) {
        case "UPR155": if($mod < 8) $mod = 8; break;
        case "UPR156": if($mod < 7) $mod = 7; break;
        case "UPR157": if($mod < 6) $mod = 6; break;
        default: break;
      }
    }
  }
  return $mod;
}

function CurrentEffectCostModifiers($cardID, $from)
{
  global $currentTurnEffects, $currentPlayer, $CS_PlayUniqueID;
  $costModifier = 0;
  for($i = count($currentTurnEffects) - CurrentTurnPieces(); $i >= 0; $i -= CurrentTurnPieces()) {
    $remove = false;
    if($currentTurnEffects[$i + 1] == $currentPlayer) {
      switch($currentTurnEffects[$i]) {
        case "WTR060": case "WTR061": case "WTR062":
          if(IsAction($cardID)) { $costModifier += 1; $remove = true; }
          break;
        case "WTR075":
          if(ClassContains($cardID, "GUARDIAN", $currentPlayer) && CardType($cardID) == "AA") { $costModifier -= 1; $remove = true; }
          break;
        case "WTR152":
          if(CardType($cardID) == "AA") { $costModifier -= 2; $remove = true; }
          break;
        case "CRU081":
          if(CardType($cardID) == "W" && CardSubType($cardID) == "Sword") { $costModifier -= 1; }
          break;
        case "CRU085-2": case "CRU086-2": case "CRU087-2":
          if(CardType($cardID) == "DR") { $costModifier += 1; $remove = true; }
          break;
        case "CRU141-AA":
          if(CardType($cardID) == "AA") { $costModifier -= CountAura("ARC112", $currentPlayer); $remove = true; }
          break;
        case "CRU141-NAA":
          if(CardType($cardID) == "A") { $costModifier -= CountAura("ARC112", $currentPlayer); $remove = true; }
          break;
        case "ARC060": case "ARC061": case "ARC062":
          if(CardType($cardID) == "AA" || GetAbilityType($cardID, -1, $from) == "AA") { $costModifier += 1; $remove = true; }
          break;
        case "ELE035-1": $costModifier += 1; break;
        case "ELE038": case "ELE039": case "ELE040": $costModifier += 1; break;
        case "ELE144": $costModifier += 1; break;
        case "EVR179":
          if(IsStaticType(CardType($cardID), $from, $cardID)) { $costModifier -= 1; $remove = true; }
          break;
        case "UPR000":
          if(TalentContains($cardID, "DRACONIC", $currentPlayer) && $from != "PLAY" && $from != "EQUIP") {
            $costModifier -= 1;
            --$currentTurnEffects[$i + 3];
            if ($currentTurnEffects[$i + 3] <= 0) $remove = true;
          }
          break;
        case "UPR075": case "UPR076": case "UPR077":
          if(GetClassState($currentPlayer, $CS_PlayUniqueID) == $currentTurnEffects[$i + 2]) { --$costModifier; $remove = true; }
          break;
        case "UPR166":
          if(IsStaticType(CardType($cardID), $from, $cardID) && DelimStringContains(CardSubType($cardID), "Staff")) { $costModifier -= 3; $remove = true; }
          break;
        case "OUT011":
          if(CardType($cardID) == "AR") { $costModifier -= 1; $remove = true; }
          break;
        case "OUT179_1":
          if(CardType($cardID) == "AA") { $costModifier -= 1; $remove = true; }
          break;
        case "ROGUE803":
          if (IsStaticType(CardType($cardID), $from, $cardID)) { $costModifier -= 1; }
          break;
        case "ROGUE024":
          $costModifier += 1;
          break;
        default: break;
      }
      if($remove) RemoveCurrentTurnEffect($i);
    }
  }
  return $costModifier;
}

function CurrentEffectPreventDamagePrevention($player, $type, $damage, $source)
{
  global $currentTurnEffects;
  for($i = count($currentTurnEffects) - CurrentTurnEffectPieces(); $i >= 0; $i -= CurrentTurnEffectPieces()) {
    $remove = false;
    if($currentTurnEffects[$i + 1] == $player) {
      switch ($currentTurnEffects[$i]) {
        case "OUT174":
          if($type != "COMBAT") break;
          $damage += 1;
          $remove = true;
        default: break;
      }
    }
    if($remove) RemoveCurrentTurnEffect($i);
  }
  return $damage;
}

function CurrentEffectDamagePrevention($player, $type, $damage, $source, $preventable)
{
  global $currentPlayer, $currentTurnEffects;
  for($i = count($currentTurnEffects) - CurrentTurnEffectPieces(); $i >= 0 && $damage > 0; $i -= CurrentTurnEffectPieces()) {
    $remove = false;
    if($currentTurnEffects[$i + 1] == $player) {
      $effects = explode("-", $currentTurnEffects[$i]);
      switch($effects[0]) {
        case "ARC035":
          if($preventable) $damage -= intval($effects[1]);
          $remove = true;
          break;
        case "CRU041":
          if($type == "COMBAT") {
            if($preventable) $damage -= 3;
            $remove = true;
          }
          break;
        case "CRU042":
          if($type == "COMBAT") {
            if($preventable) $damage -= 2;
            $remove = true;
          }
          break;
        case "CRU043":
          if($type == "COMBAT") {
            if($preventable) $damage -= 1;
            $remove = true;
          }
          break;
        case "EVR033": case "EVR034": case "EVR035":
          if($source == $currentTurnEffects[$i + 2]) {
            if($preventable)
            {
              $origDamage = $damage;
              $damage -= $currentTurnEffects[$i + 3];
              if($damage < 0) $damage = 0;
              $currentTurnEffects[$i + 3] -= $origDamage;
            }
            if ($currentTurnEffects[$i + 3] <= 0) $remove = true;
          }
          break;
        case "EVR180":
          if($preventable) $damage -= 1;
          $remove = true;
          break;
        case "UPR183":
          if($preventable) $damage -= 1;
          $remove = true;
          break;
        case "UPR221": case "UPR222": case "UPR223":
          if($source == $currentTurnEffects[$i+2])
          {
            if($preventable)
            {
              $sourceDamage = $damage;
              $damage -= $currentTurnEffects[$i+3];
              $currentTurnEffects[$i+3] -= $sourceDamage;
            }
            if($currentTurnEffects[$i+3] <= 0) $remove = true;
          }
          break;
        case "OUT175": case "OUT176": case "OUT177": case "OUT178":
          if($preventable) $damage -= 1;
          $remove = true;
          break;
        case "OUT228":
          if($preventable && $damage <= 3) { $damage = 0; $remove = true; }
          break;
        case "OUT229":
          if($preventable && $damage <= 2) { $damage = 0; $remove = true; }
          break;
        case "OUT230":
          if($preventable && $damage == 1) { $damage = 0; $remove = true; }
          break;
        case "OUT231":
          if($type == "COMBAT") {
            if($preventable) $damage -= 4;
            $remove = true;
          }
          break;
        case "OUT232":
          if($type == "COMBAT") {
            if($preventable) $damage -= 3;
            $remove = true;
          }
          break;
        case "OUT233":
          if($type == "COMBAT") {
            if($preventable) $damage -= 2;
            $remove = true;
          }
          break;
        default:
          break;
      }
      if($remove) RemoveCurrentTurnEffect($i);
    }
  }
  return $damage;
}

function CurrentEffectAttackAbility()
{
  global $currentTurnEffects, $combatChain, $mainPlayer;
  global $CS_PlayIndex;
  if(count($combatChain) == 0) return;
  $attackID = $combatChain[0];
  $attackType = CardType($attackID);
  for($i = count($currentTurnEffects) - CurrentTurnPieces(); $i >= 0; $i -= CurrentTurnPieces()) {
    $remove = false;
    if($currentTurnEffects[$i + 1] == $mainPlayer) {
      switch ($currentTurnEffects[$i]) {
        case "EVR056":
          if ($attackType == "W") {
            $character = &GetPlayerCharacter($mainPlayer);
            ++$character[GetClassState($mainPlayer, $CS_PlayIndex) + 3];
          }
          break;
        case "MON183": case "MON184": case "MON185":
          if($currentTurnEffects[$i] == "MON183") $maxCost = 2;
          else if($currentTurnEffects[$i] == "MON184") $maxCost = 1;
          else $maxCost = 0;
          if($attackType == "AA" && CardCost($attackID) <= $maxCost) {
            WriteLog("Seeds of Agony dealt 1 damage.");
            DealArcane(1, 0, "PLAYCARD", $currentTurnEffects[$i], true);
            $remove = true;
          }
          break;
        default:
          break;
      }
    }
    if($remove) RemoveCurrentTurnEffect($i);
  }
}

function CurrentEffectPlayAbility($cardID, $from)
{
  global $currentTurnEffects, $currentPlayer, $actionPoints, $CS_LastDynCost;

  if(DynamicCost($cardID) != "") $cost = GetClassState($currentPlayer, $CS_LastDynCost);
  else $cost = CardCost($cardID);

  for($i = count($currentTurnEffects) - CurrentTurnPieces(); $i >= 0; $i -= CurrentTurnPieces()) {
    $remove = false;
    if($currentTurnEffects[$i + 1] == $currentPlayer) {
      switch($currentTurnEffects[$i]) {
        case "ARC209":
          $cardType = CardType($cardID);
          if (($cardType == "A" || $cardType == "AA") && $cost >= 0) {
            ++$actionPoints;
            $remove = true;
          }
          break;
        case "ARC210":
          $cardType = CardType($cardID);
          if (($cardType == "A" || $cardType == "AA") && $cost >= 1) {
            ++$actionPoints;
            $remove = true;
          }
          break;
        case "ARC211":
          $cardType = CardType($cardID);
          if (($cardType == "A" || $cardType == "AA") && $cost >= 2) {
            ++$actionPoints;
            $remove = true;
          }
          break;
        default:
          break;
      }
      if($remove) RemoveCurrentTurnEffect($i);
    }
  }
  return false;
}

function CurrentEffectPlayOrActivateAbility($cardID, $from)
{
  global $currentTurnEffects, $currentPlayer;

  for($i = count($currentTurnEffects) - CurrentTurnPieces(); $i >= 0; $i -= CurrentTurnPieces()) {
    $remove = false;
    if($currentTurnEffects[$i + 1] == $currentPlayer) {
      switch($currentTurnEffects[$i]) {
        case "MON153": case "MON154":
          $cardType = CardType($cardID);
          if(($cardType == "AA" || $cardType == "W" || $cardType == "T") && (ClassContains($cardID, "RUNEBLADE", $currentPlayer) || TalentContains($cardID, "SHADOW", $currentPlayer))) {
            GiveAttackGoAgain();
            $remove = true;
          }
          break;
        default:
          break;
      }
      if($remove) RemoveCurrentTurnEffect($i);
    }
  }
  $currentTurnEffects = array_values($currentTurnEffects); //In case any were removed
  return false;
}

function CurrentEffectGrantsNonAttackActionGoAgain($cardID)
{
  global $currentTurnEffects, $currentPlayer;
  $hasGoAgain = false;
  for($i = count($currentTurnEffects) - CurrentTurnPieces(); $i >= 0; $i -= CurrentTurnPieces()) {
    $remove = false;
    if($currentTurnEffects[$i + 1] == $currentPlayer) {
      switch($currentTurnEffects[$i]) {
        case "MON153": case "MON154":
          if(ClassContains($cardID, "RUNEBLADE", $currentPlayer) || TalentContains($cardID, "SHADOW", $currentPlayer)) {
            $hasGoAgain = true;
            $remove = true;
          }
          break;
        case "ELE177":
          if(CardCost($cardID) >= 0) {
            $hasGoAgain = true;
            $remove = true;
          }
          break;
        case "ELE178":
          if(CardCost($cardID) >= 1) {
            $hasGoAgain = true;
            $remove = true;
          }
          break;
        case "ELE179":
          if(CardCost($cardID) >= 2) {
            $hasGoAgain = true;
            $remove = true;
          }
          break;
        case "ELE201":
          $hasGoAgain = true;
          $remove = true;
          break;
        case "ARC185-GA":
          $hasGoAgain = ($cardID == "ARC212" || $cardID == "ARC213" || $cardID == "ARC214");
          break;
        default:
          break;
      }
    }
    if($remove) RemoveCurrentTurnEffect($i);
  }
  return $hasGoAgain;
}

function CurrentEffectGrantsGoAgain()
{
  global $currentTurnEffects, $mainPlayer, $combatChainState, $CCS_AttackFused;
  for($i = 0; $i < count($currentTurnEffects); $i += CurrentTurnEffectPieces()) {
    if($currentTurnEffects[$i + 1] == $mainPlayer && IsCombatEffectActive($currentTurnEffects[$i]) && !IsCombatEffectLimited($i)) {
      switch ($currentTurnEffects[$i]) {
        case "WTR144": case "WTR145": case "WTR146": return true;
        case "WTR154": return true;
        case "ARC047": return true;
        case "ARC160-3": return true;
        case "CRU053": return true;
        case "CRU055": return true;
        case "CRU084": return true;
        case "CRU091-1": case "CRU092-1": case "CRU093-1": return true;
        case "CRU122": return true;
        case "CRU145": case "CRU146": case "CRU147": return true;
        case "MON141": case "MON142": case "MON143": return true;
        case "MON165": case "MON166": case "MON167": return true;
        case "MON193": return true;
        case "MON247": return true;
        case "MON260-2": case "MON261-2": case "MON262-2": return true;
        case "ELE031-1": return true;
        case "ELE034-2": return true;
        case "ELE091-GA": return true;
        case "ELE177": case "ELE178": case "ELE179": return true;
        case "ELE180": case "ELE181": case "ELE182": return $combatChainState[$CCS_AttackFused] == 1;
        case "ELE201": return true;
        case "EVR017": return true;
        case "EVR044": case "EVR045": case "EVR046": return true;
        case "EVR161-3": return true;
        case "DVR008": return true;
        case "DVR019": return true;
        case "UPR081": case "UPR082": case "UPR083": return true;
        case "UPR094": return true;
        case "DYN076": case "DYN077": case "DYN078": return true;
        case "ROGUE710-GA": return true;
        default:
          break;
      }
    }
  }
  return false;
}

function CurrentEffectPreventsGoAgain()
{
  global $currentTurnEffects, $mainPlayer;
  for($i = 0; $i < count($currentTurnEffects); $i += CurrentTurnEffectPieces()) {
    if($currentTurnEffects[$i + 1] == $mainPlayer) {
      switch($currentTurnEffects[$i]) {
        case "WTR044": return true;
        default: break;
      }
    }
  }
  return false;
}

function CurrentEffectPreventsDefenseReaction($from)
{
  global $currentTurnEffects, $currentPlayer;
  $reactionPrevented = false;
  for($i = 0; $i < count($currentTurnEffects); $i += CurrentTurnEffectPieces()) {
    if($currentTurnEffects[$i + 1] == $currentPlayer) {
      switch($currentTurnEffects[$i]) {
        case "CRU123":
          if($from == "ARS" && IsCombatEffectActive($currentTurnEffects[$i])) $reactionPrevented = true;
          break;
        case "CRU135-1": case "CRU136-1": case "CRU137-1":
          if($from == "HAND" && IsCombatEffectActive($currentTurnEffects[$i])) $reactionPrevented = true;
          break;
        case "EVR091-1": case "EVR092-1": case "EVR093-1":
          if($from == "ARS" && IsCombatEffectActive($currentTurnEffects[$i])) $reactionPrevented = true;
          break;
        default:
          break;
      }
    }
  }
  return $reactionPrevented;
}

function CurrentEffectPreventsDraw($player, $isMainPhase)
{
  global $currentTurnEffects;
  for($i = 0; $i < count($currentTurnEffects); $i += CurrentTurnEffectPieces()) {
    if($currentTurnEffects[$i + 1] == $player) {
      switch ($currentTurnEffects[$i]) {
        case "WTR045": return $isMainPhase;
        default: break;
      }
    }
  }
  return false;
}

function CurrentEffectIntellectModifier()
{
  global $currentTurnEffects, $mainPlayer;
  $intellectModifier = 0;
  for($i = count($currentTurnEffects) - CurrentTurnEffectPieces(); $i >= 0; $i -= CurrentTurnEffectPieces()) {
    if($currentTurnEffects[$i + 1] == $mainPlayer) {
      switch($currentTurnEffects[$i]) {
        case "WTR042": case "ARC161": case "CRU028": case "MON000": case "MON246":
          $intellectModifier += 1;
          break;
        default: break;
      }
    }
  }
  return $intellectModifier;
}

function CurrentEffectEndTurnAbilities()
{
  global $currentTurnEffects, $mainPlayer;
  for($i = count($currentTurnEffects) - CurrentTurnPieces(); $i >= 0; $i -= CurrentTurnPieces()) {
    $remove = false;
    $cardID = substr($currentTurnEffects[$i], 0, 6);
    if(SearchCurrentTurnEffects($cardID . "-UNDER", $currentTurnEffects[$i + 1])) {
      AddNextTurnEffect($currentTurnEffects[$i], $currentTurnEffects[$i + 1]);
    }
    switch($currentTurnEffects[$i]) {
      case "MON069": case "MON070": case "MON071":
      case "EVR056":
        if($mainPlayer == $currentTurnEffects[$i + 1]) {
          $char = &GetPlayerCharacter($currentTurnEffects[$i + 1]);
          for($j = 0; $j < count($char); $j += CharacterPieces()) {
            if(CardType($char[$j]) == "W") $char[$j + 3] = 0;
          }
          $remove = true;
        }
        break;
      default: break;
    }
    if($remove) RemoveCurrentTurnEffect($i);
  }
}

function IsCombatEffectActive($cardID)
{
  global $combatChain, $currentPlayer;
  if(count($combatChain) == 0) return;
  if($cardID == "AIM") return true;
  $cardID = ShiyanaCharacter($cardID);
  $attackID = $combatChain[0];
  $set = CardSet($cardID);
  if($set == "WTR") return WTRCombatEffectActive($cardID, $attackID);
  else if($set == "ARC") return ARCCombatEffectActive($cardID, $attackID);
  else if($set == "CRU") return CRUCombatEffectActive($cardID, $attackID);
  else if($set == "MON") return MONCombatEffectActive($cardID, $attackID);
  else if($set == "ELE") return ELECombatEffectActive($cardID, $attackID);
  else if($set == "EVR") return EVRCombatEffectActive($cardID, $attackID);
  else if($set == "DVR") return DVRCombatEffectActive($cardID, $attackID);
  else if($set == "UPR") return UPRCombatEffectActive($cardID, $attackID);
  else if($set == "DYN") return DYNCombatEffectActive($cardID, $attackID);
  else if($set == "OUT") return OUTCombatEffectActive($cardID, $attackID);
  else if($set == "ROG") return ROGUECombatEffectActive($cardID, $attackID);
}

function IsCombatEffectPersistent($cardID)
{
  global $currentPlayer;
  $effectArr = explode(",", $cardID);
  $cardID = ShiyanaCharacter($effectArr[0]);
  switch($cardID) {
    case "WTR007": case "WTR038": case "WTR039": return true;
    case "ARC047": return true;
    case "ARC160-1": return true;
    case "ARC170-1": case "ARC171-1": case "ARC172-1": return true;
    case "CRU025": case "CRU053": case "CRU084-2": case "CRU105": case "CRU122": case "CRU124": case "CRU188": return true;
    case "MON034": case "MON035": case "MON087": case "MON089": case "MON108": case "MON109": case "MON218": case "MON239": case "MON245": return true;
    case "ELE044": case "ELE045": case "ELE046": return true;
    case "ELE047": case "ELE048": case "ELE049": return true;
    case "ELE050": case "ELE051": case "ELE052": return true;
    case "ELE059": case "ELE060": case "ELE061": return true;
    case "ELE066-HIT": return true;
    case "ELE067": case "ELE068": case "ELE069": return true;
    case "ELE091-BUFF": case "ELE091-GA": return true;
    case "ELE092-DOM": case "ELE092-BUFF": return true;
    case "ELE143": return true;
    case "ELE151-HIT": case "ELE152-HIT": case "ELE153-HIT": return true;
    case "ELE173": return true;
    case "ELE198": case "ELE199": case "ELE200": return true;
    case "ELE203": return true;
    case "EVR001": return true;
    case "EVR019": return true;
    case "EVR066-1": case "EVR067-1": case "EVR068-1": return true;
    case "EVR090": return true;
    case "EVR160": return true;
    case "EVR164": case "EVR165": case "EVR166": return true;
    case "EVR170-1": case "EVR171-1": case "EVR172-1": return true;
    case "EVR186": return true;
    case "DVR008-1": return true;
    case "UPR036": case "UPR037": case "UPR038": return true;
    case "UPR047": return true;
    case "UPR049": return true;
    case "DYN009": return true;
    case "DYN049": return true;
    case "DYN085": case "DYN086": case "DYN087": return true;
    case "DYN089-UNDER": return true;
    case "DYN154": return true;
    case "OUT052": case "OUT140": case "OUT141": case "OUT144": case "OUT188_1": return true;
    case "ROGUE018": return true;
    case "ROGUE601": return true;
    case "ROGUE603": return true;
    case "ROGUE612": case "ROGUE613": case "ROGUE614": case "ROGUE615": case "ROGUE616": return true;
    case "ROGUE702": return true;
    case "ROGUE704": return true;
    case "ROGUE707": return true;
    case "ROGUE710-GA": return true;
    case "ROGUE710-DO": return true;
    case "ROGUE711": return true;
    case "ROGUE802": return true;
    case "ROGUE805": return true;
    case "ROGUE806": return true;
    default:
      return false;
  }
}

function BeginEndPhaseEffects()
{
  global $currentTurnEffects, $mainPlayer, $EffectContext;
  EndTurnBloodDebt(); //Must be done before resetting character (e.g. sleep dart)
  for($i = 0; $i < count($currentTurnEffects); $i += CurrentTurnPieces()) {
    $EffectContext = $currentTurnEffects[$i];
    switch($currentTurnEffects[$i]) {
      case "EVR106":
        if(CountAura("ARC112", $mainPlayer) > 0) WriteLog(CardLink($currentTurnEffects[$i], $currentTurnEffects[$i]) . " destroyed your Runechant tokens");
        DestroyAllThisAura($currentTurnEffects[$i + 1], "ARC112");
        break;
      case "UPR200": case "UPR201": case "UPR202":
        Draw($currentTurnEffects[$i + 1]);
        break;
      default:
        break;
    }
  }
}

function BeginEndPhaseEffectTriggers()
{
  global $currentTurnEffects, $mainPlayer;
  for($i = 0; $i < count($currentTurnEffects); $i += CurrentTurnPieces()) {
    switch($currentTurnEffects[$i]) {
      case "ELE215-1":
        AddLayer("TRIGGER", $mainPlayer, "ELE215", $currentTurnEffects[$i+1], "-", "-");
        break;
      case "DYN153":
        AddLayer("TRIGGER", $mainPlayer, "DYN153", $currentTurnEffects[$i+1], "-", "-");
        break;
      default: break;
    }
  }
}

function ActivateAbilityEffects()
{
  global $currentPlayer, $currentTurnEffects;
  for($i = count($currentTurnEffects) - CurrentTurnPieces(); $i >= 0; $i -= CurrentTurnPieces()) {
    $remove = false;
    if($currentTurnEffects[$i + 1] == $currentPlayer) {
      switch($currentTurnEffects[$i]) {
        case "ELE004-HIT":
          WriteLog(CardLink("ELE004", "ELE004") . " created a frostbite");
          PlayAura("ELE111", $currentPlayer);
          break;
        default:
          break;
      }
    }
    if($remove) RemoveCurrentTurnEffect($i);
  }
  $currentTurnEffects = array_values($currentTurnEffects);
}

function CurrentEffectNameModifier($effectID, $effectParameter)
{
  $name = "";
  switch($effectID)
  {
    case "OUT049":
      $name = $effectParameter;
      break;
    case "OUT068": case "OUT069": case "OUT070":
      $name = $effectParameter;
      break;
    default: break;
  }
  return $name;
}

?>
