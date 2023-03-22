<?php


//Return 1 if the effect should be removed
function EffectHitEffect($cardID)
{
  global $combatChainState, $CCS_GoesWhereAfterLinkResolves, $defPlayer, $mainPlayer, $CCS_WeaponIndex, $combatChain, $CCS_DamageDealt;
  global $CID_BloodRotPox, $CID_Frailty, $CID_Inertia;
  if (CardType($combatChain[0]) == "AA" && (SearchAuras("CRU028", 1) || SearchAuras("CRU028", 2))) return;
  switch ($cardID) {
    case "WTR129": case "WTR130": case "WTR131":
      GiveAttackGoAgain();
      break;
    case "WTR147": case "WTR148": case "WTR149":
      NaturesPathPilgrimageHit();
      break;
    case "ARC170-1": case "ARC171-1": case "ARC172-1":
      MainDrawCard();
      return 1;
    case "CRU124":
      if (IsHeroAttackTarget()) {
        PummelHit();
      }
      break;
    case "CRU145": case "CRU146": case "CRU147":
      if (ClassContains($combatChain[0], "RUNEBLADE", $mainPlayer)){
        if ($cardID == "CRU145") $amount = 3;
        else if ($cardID == "CRU146") $amount = 2;
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
      if (count(GetSoul($defPlayer)) > 0) {
        BanishFromSoul($defPlayer);
        LoseHealth(1, $defPlayer);
      }
      break;
    case "MON299": case "MON300": case "MON301":
      $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "BOTDECK";
      break;
    case "ELE003":
      if (IsHeroAttackTarget()) {
        PlayAura("ELE111", $defPlayer);
      }
      break;
    case "ELE005":
      if (IsHeroAttackTarget()) {
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
      if (IsHeroAttackTarget()) {
        AddDecisionQueue("FINDINDICES", $mainPlayer, "SEARCHMZ,THEIRARS", 1);
        AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose which card you want to put at the bottom of the deck", 1);
        AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, "<-", 1);
        AddDecisionQueue("MZADDZONE", $mainPlayer, "THEIRBOTDECK", 1);
        AddDecisionQueue("MZREMOVE", $mainPlayer, "-", 1);
      }
      break;
    case "ELE022": case "ELE023": case "ELE024":
      if (IsHeroAttackTarget()) {
        PlayAura("ELE111", $defPlayer);
      }
      break;
    case "ELE035-2":
      if (IsHeroAttackTarget()) {
        AddCurrentTurnEffect("ELE035-3", $defPlayer);
        AddNextTurnEffect("ELE035-3", $defPlayer);
      }
      break;
    case "ELE037-2":
      if (IsHeroAttackTarget()) {
        DamageTrigger($defPlayer, 1, "ATTACKHIT");
      }
      break;
    case "ELE047": case "ELE048": case "ELE049":
      if (IsHeroAttackTarget()) {
        DamageTrigger($defPlayer, 1, "ATTACKHIT");
      }
      break;
    case "ELE066-HIT":
      AddLayer("TRIGGER", $mainPlayer, "ELE066");
      break;
    case "ELE092-BUFF":
      if (IsHeroAttackTarget()) {
        DamageTrigger($defPlayer, 3, "ATTACKHIT");
      }
      break;
    case "ELE151-HIT": case "ELE152-HIT": case "ELE153-HIT":
      if (IsHeroAttackTarget()) {
        PlayAura("ELE111", $defPlayer);
      }
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
      if (IsHeroAttackTarget()) {
        DamageTrigger($defPlayer, 1, "ATTACKHIT");
      }
      return 1;
    case "ELE195": case "ELE196": case "ELE197":
      if (IsHeroAttackTarget()) {
        DamageTrigger($defPlayer, 1, "ATTACKHIT");
      }
      break;
    case "ELE198": case "ELE199": case "ELE200":
      if (IsHeroAttackTarget()) {
        if ($cardID == "ELE198") $damage = 3;
        else if ($cardID == "ELE199") $damage = 2;
        else $damage = 1;
        DamageTrigger($defPlayer, $damage, "ATTACKHIT");
        return 1;
      }
      break;
    case "ELE205":
      if (IsHeroAttackTarget()) {
        PummelHit();
        PummelHit();
      }
      break;
    case "ELE215":
      if (IsHeroAttackTarget()) {
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
      if (IsHeroAttackTarget()) {
        AddDecisionQueue("FINDINDICES", $defPlayer, "ITEMSMAX,2");
        AddDecisionQueue("CHOOSETHEIRITEM", $mainPlayer, "<-", 1);
        AddDecisionQueue("DESTROYITEM", $defPlayer, "<-", 1);
        return 1;
      }
      break;
    case "DVR008-1":
      $char = &GetPlayerCharacter($mainPlayer);
      if (IsHeroAttackTarget()) {
        ++$char[$combatChainState[$CCS_WeaponIndex] + 3];
      }
      break;
    case "DYN028":
      AddDecisionQueue("FINDINDICES", $mainPlayer, "CRU026");
      AddDecisionQueue("CHOOSETHEIRCHARACTER", $mainPlayer, "<-", 1);
      AddDecisionQueue("DESTROYTHEIRCHARACTER", $mainPlayer, "-", 1);
      break;
    case "DYN071":
      AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "THEIRALLY", 1);
      AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose a target to deal " . $combatChainState[$CCS_DamageDealt] . " damage.");
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $mainPlayer, "<-", 1);
      AddDecisionQueue("MZDAMAGE", $mainPlayer, $combatChainState[$CCS_DamageDealt] . ",DAMAGE," . $cardID, 1);
      break;
    case "DYN155":
      if (IsHeroAttackTarget() && SearchCurrentTurnEffects("AIM", $mainPlayer)) {
        AddDecisionQueue("FINDINDICES", $mainPlayer, "SEARCHMZ,THEIRHAND");
        AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose which card you want your opponent to discard", 1);
        AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, "<-", 1);
        AddDecisionQueue("MZDISCARD", $mainPlayer, "HAND,-," . $mainPlayer, 1);
        AddDecisionQueue("MZREMOVE", $mainPlayer, "-", 1);
      }
      break;
    case "DYN185-HIT": case "DYN186-HIT": case "DYN187-HIT":
      if (ClassContains($combatChain[0], "RUNEBLADE", $mainPlayer)) {
        if ($cardID == "DYN185-HIT") $amount = 3;
        else if ($cardID == "DYN186-HIT") $amount = 2;
        else $amount = 1;
        PlayAura("ARC112", $mainPlayer, $amount, true);
      }
      break;
    case "OUT105":
      if (IsHeroAttackTarget() && SearchCurrentTurnEffects("AIM", $mainPlayer)) {
        AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "THEIRCHAR:minAttack=1;maxAttack=1;type=W");//TODO: Limit to 1H
        AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose a weapon to destroy");
        AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, "<-", 1);
        AddDecisionQueue("MULTIZONEDESTROY", $mainPlayer, "-", 1);
      }
      break;
    case "OUT112":
      if (IsHeroAttackTarget()) PlayAura($CID_BloodRotPox, $defPlayer);
      break;
    case "OUT113":
      if (IsHeroAttackTarget()) PlayAura($CID_Frailty, $defPlayer);
      break;
    case "OUT114":
      if (IsHeroAttackTarget()) PlayAura($CID_Inertia, $defPlayer);
      break;
    case "OUT140":
      WriteLog("Mask of Shifting Perspectives lets you sink a card.");
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
    case "OUT188_1": PlayAura("DYN244", $mainPlayer); return 1;
    default:
      break;
  }
  return 0;
}

?>
