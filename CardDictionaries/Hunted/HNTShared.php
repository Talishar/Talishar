<?php

function HNTAbilityType($cardID): string
{
  return match ($cardID) {
    "HNT003" => "AR",
    "HNT004" => "AR",
    "HNT005" => "I",
    "HNT006" => "AR",
    "HNT007" => "AR",
    "HNT009" => "AA",
    "HNT010" => "AA",
    "HNT053" => "AA",
    "HNT054" => "I",
    "HNT055" => "I",
    "HNT056" => "AA",
    "HNT100" => "AA",
    "HNT144" => "AR",
    "HNT145" => "I",
    "HNT146" => "AR",
    "HNT147" => "AR",
    "HNT167" => "I",
    "HNT173" => "AR",
    "HNT196" => "AR",
    "HNT215" => "DR",
    "HNT247" => "I",
    "HNT250" => "I",
    "HNT252" => "I",
    "HNT407" => "AR",
    default => ""
  };
}

function HNTAbilityCost($cardID): int
{
  global $currentPlayer, $mainPlayer;
  return match ($cardID) {
    "HNT009" => 2,
    "HNT010" => 2,
    "HNT053" => 1,
    "HNT054" => 3 - ($mainPlayer == $currentPlayer ? NumDraconicChainLinks() : 0),
    "HNT055" => 3 - ($mainPlayer == $currentPlayer ? NumDraconicChainLinks() : 0),
    "HNT056" => 1,
    "HNT100" => 1,
    "HNT215" => 1,
    default => 0
  };
}

function HNTAbilityHasGoAgain($cardID): bool
{
  global $currentPlayer;
  $defPlayer = $currentPlayer == 1 ? 2 : 1;
  return match ($cardID) {
    default => false,
  };
}

function HNTEffectAttackModifier($cardID): int
{
  global $currentPlayer;
  $otherPlayer = $currentPlayer == 1 ? 2 : 1;
  return match ($cardID) {
    "HNT003" => 3,
    "HNT004" => 3,
    "HNT005" => 3,
    "HNT006" => 3,
    "HNT007" => 3,
    "HNT014" => 2,
    "HNT014-FULL" => 3,
    "HNT015" => 3,
    "HNT023" => 3,
    "HNT024" => 2,
    "HNT025" => 1,
    "HNT026" => 3,
    "HNT027" => 2,
    "HNT028" => 1,
    "HNT051-DAGGER" => 3,
    "HNT051-ATTACK" => 3,
    "HNT061" => 1,
    "HNT077" => 3,
    "HNT078" => 3,
    "HNT079" => 3,
    "HNT083" => 1,
    "HNT084" => 1,
    "HNT085" => 1,
    "HNT100" => 1,
    "HNT102-BUFF" => 2,
    "HNT103" => 2,
    "HNT104" => 3,
    "HNT105" => 1,
    "HNT106" => NumDraconicChainLinks() > 1 ? 3 : 2,
    "HNT107" => 4,
    "HNT108" => 3,
    "HNT109" => 2,
    "HNT110" => NumDraconicChainLinks(),
    "HNT111" => 2,
    "HNT112" => 3,
    "HNT113" => NumDraconicChainLinks() > 1 ? 4 : 3,
    "HNT114" => 3,
    "HNT119" => 3,
    "HNT120" => 2,
    "HNT121" => 1,
    "HNT122" => 4,
    "HNT123" => 3,
    "HNT124" => 2,
    "HNT127" => 1,
    "HNT128" => 4,
    "HNT129" => 3,
    "HNT130" => 2,
    "HNT131" => 4,
    "HNT132" => 3,
    "HNT133" => 2,
    "HNT134-BUFF" => 4,
    "HNT135-BUFF" => 3,
    "HNT136-BUFF" => 2,
    "HNT137-NEXTDAGGER" => 3,
    "HNT137-MARKEDBUFF" => 1,
    "HNT138-NEXTDAGGER" => 2,
    "HNT138-MARKEDBUFF" => 1,
    "HNT139-NEXTDAGGER" => 1,
    "HNT139-MARKEDBUFF" => 1,
    "HNT140" => 3,
    "HNT141" => 2,
    "HNT142" => 1,
    "HNT146" => 1,
    "HNT156" => 1,
    "HNT166" => 3,
    "HNT163" => 3,
    "HNT179" => 4,
    "HNT180" => 3,
    "HNT181" => 2,
    "HNT198" => 4,
    "HNT202" => 4,
    "HNT203" => 3,
    "HNT204" => 2,
    "HNT208" => 3,
    "HNT209" => 2,
    "HNT210" => 1,
    "HNT211" => 3,
    "HNT212" => 2,
    "HNT213" => 1,
    "HNT221" => 1,
    "HNT223-AA" => 3,
    "HNT223-WEAPON" => 3,
    "HNT235" => CheckMarked($otherPlayer) ? 1 : 0,
    "HNT236" => -1,
    "HNT237" => 1,
    "HNT239" => 1,
    "HNT241" => 3,
    "HNT242" => 2,
    "HNT243" => 1,
    "HNT258-BUFF" => 2,
    "HNT407" => IsRoyal($otherPlayer) ? 1 : 0,
    default => 0,
  };
}

function HNTCombatEffectActive($cardID, $attackID, $flicked = false): bool
{
  global $mainPlayer, $combatChainState, $CCS_WeaponIndex, $defPlayer, $combatChain;
  $dashArr = explode("-", $cardID);
  $cardID = $dashArr[0];
  if ($cardID == "HNT102" & count($dashArr) > 1) {
    if ($dashArr[1] == "BUFF") return SubtypeContains($attackID, "Dagger", $mainPlayer);
    if (DelimStringContains($dashArr[1], "MARK", true)) {
      $id = explode(",", $dashArr[1])[1];
      $character = &GetPlayerCharacter($mainPlayer);
      return $character[$combatChainState[$CCS_WeaponIndex] + 11] == $id;
    }
  }
  if ($cardID == "HNT003" && count($dashArr) > 1 && $dashArr[1] == "HIT") return HasStealth($attackID);
  if ($cardID == "HNT004" && count($dashArr) > 1 && $dashArr[1] == "HIT") return HasStealth($attackID);
  if ($cardID == "HNT167" && count($dashArr) > 1 && $dashArr[1] == "ATTACK") return DelimStringContains(CardType($attackID), "AA");
  if ($cardID == "HNT223" && count($dashArr) > 1 && $dashArr[1] == "AA") return DelimStringContains(CardType($attackID), "AA");
  if ($cardID == "HNT223" && count($dashArr) > 1 && $dashArr[1] == "WEAPON") return DelimStringContains(CardType($attackID), "W");
  if ($cardID == "HNT134" || $cardID == "HNT135" || $cardID == "HNT136" && count($dashArr) > 1 && $dashArr[1] == "BUFF") return SubtypeContains($attackID, "Dagger", $mainPlayer);
  if ($cardID == "HNT137" || $cardID == "HNT138" || $cardID == "HNT139" && count($dashArr) > 1 && $dashArr[1] == "NEXTDAGGER") return SubtypeContains($attackID, "Dagger", $mainPlayer);
  if ($cardID == "HNT137" || $cardID == "HNT138" || $cardID == "HNT139" && count($dashArr) > 1 && $dashArr[1] == "MARKEDBUFF") return CheckMarked($defPlayer);
  return match ($cardID) {
    "HNT003" => ClassContains($attackID, "ASSASSIN", $mainPlayer),
    "HNT004" => ClassContains($attackID, "ASSASSIN", $mainPlayer),
    "HNT005" => HasStealth($attackID),
    "HNT006" => ClassContains($attackID, "ASSASSIN", $mainPlayer),
    "HNT007" => SubtypeContains($attackID, "Dagger", $mainPlayer),
    "HNT014" => HasStealth($attackID),
    "HNT015" => true,
    "HNT023" => HasStealth($attackID),
    "HNT024" => HasStealth($attackID),
    "HNT025" => HasStealth($attackID),
    "HNT026" => HasStealth($attackID),
    "HNT027" => HasStealth($attackID),
    "HNT028" => HasStealth($attackID),
    "HNT051" => true,
    "HNT061" => SubtypeContains($attackID, "Dagger", $mainPlayer),
    "HNT071" => TalentContains($attackID, "DRACONIC", $mainPlayer),
    "HNT074" => TalentContains($attackID, "DRACONIC", $mainPlayer),
    "HNT075" => TalentContains($attackID, "DRACONIC", $mainPlayer),
    "HNT076" => TalentContains($attackID, "DRACONIC", $mainPlayer),
    "HNT077" => true,
    "HNT078" => true,
    "HNT079" => true,
    "HNT083" => TalentContains($attackID, "DRACONIC", $mainPlayer),
    "HNT084" => TalentContains($attackID, "DRACONIC", $mainPlayer),
    "HNT085" => TalentContains($attackID, "DRACONIC", $mainPlayer),
    "HNT100" => true,
    "HNT101" => true,
    "HNT103" => SubtypeContains($attackID, "Dagger", $mainPlayer),
    "HNT104" => SubtypeContains($attackID, "Dagger", $mainPlayer),
    "HNT105" => SubtypeContains($attackID, "Dagger", $mainPlayer),
    "HNT106" => SubtypeContains($attackID, "Dagger", $mainPlayer),
    "HNT107" => SubtypeContains($attackID, "Dagger", $mainPlayer),
    "HNT108" => SubtypeContains($attackID, "Dagger", $mainPlayer),
    "HNT109" => SubtypeContains($attackID, "Dagger", $mainPlayer),
    "HNT110" => SubtypeContains($attackID, "Dagger", $mainPlayer),
    "HNT111" => SubtypeContains($attackID, "Dagger", $mainPlayer),
    "HNT112" => SubtypeContains($attackID, "Dagger", $mainPlayer),
    "HNT113" => SubtypeContains($attackID, "Dagger", $mainPlayer),
    "HNT114" => SubtypeContains($attackID, "Dagger", $mainPlayer),
    "HNT119" => SubtypeContains($attackID, "Dagger", $mainPlayer),
    "HNT120" => SubtypeContains($attackID, "Dagger", $mainPlayer),
    "HNT121" => SubtypeContains($attackID, "Dagger", $mainPlayer),
    "HNT122" => SubtypeContains($attackID, "Dagger", $mainPlayer),
    "HNT123" => SubtypeContains($attackID, "Dagger", $mainPlayer),
    "HNT124" => SubtypeContains($attackID, "Dagger", $mainPlayer),
    "HNT116" => true,
    "HNT125" => SubtypeContains($attackID, "Dagger", $mainPlayer),
    "HNT127" => SubtypeContains($attackID, "Dagger", $mainPlayer),
    "HNT128" => SubtypeContains($attackID, "Dagger", $mainPlayer),
    "HNT129" => SubtypeContains($attackID, "Dagger", $mainPlayer),
    "HNT130" => SubtypeContains($attackID, "Dagger", $mainPlayer),
    "HNT131" => SubtypeContains($attackID, "Dagger", $mainPlayer),
    "HNT132" => SubtypeContains($attackID, "Dagger", $mainPlayer),
    "HNT133" => SubtypeContains($attackID, "Dagger", $mainPlayer),
    "HNT140" => SubtypeContains($attackID, "Dagger", $mainPlayer),
    "HNT141" => SubtypeContains($attackID, "Dagger", $mainPlayer),
    "HNT142" => SubtypeContains($attackID, "Dagger", $mainPlayer),
    "HNT144" => true,
    "HNT146" => true,
    "HNT147" => true,
    "HNT156" => TalentContains($attackID, "DRACONIC", $mainPlayer),
    "HNT163" => true,
    "HNT166" => TalentContains($attackID, "DRACONIC", $mainPlayer),
    "HNT179" => SubtypeContains($attackID, "Dagger", $mainPlayer),
    "HNT180" => SubtypeContains($attackID, "Dagger", $mainPlayer),
    "HNT181" => SubtypeContains($attackID, "Dagger", $mainPlayer),
    "HNT185" => SubtypeContains($attackID, "Dagger", $mainPlayer) || $flicked,
    "HNT186" => SubtypeContains($attackID, "Dagger", $mainPlayer) || $flicked,
    "HNT187" => SubtypeContains($attackID, "Dagger", $mainPlayer) || $flicked,
    "HNT198" => SubtypeContains($attackID, "Dagger", $mainPlayer) || $flicked,
    "HNT202" => SubtypeContains($attackID, "Dagger", $mainPlayer),
    "HNT203" => SubtypeContains($attackID, "Dagger", $mainPlayer),
    "HNT204" => SubtypeContains($attackID, "Dagger", $mainPlayer),
    "HNT208" => SubtypeContains($attackID, "Dagger", $mainPlayer),
    "HNT209" => SubtypeContains($attackID, "Dagger", $mainPlayer),
    "HNT210" => SubtypeContains($attackID, "Dagger", $mainPlayer),
    "HNT211" => SubtypeContains($attackID, "Dagger", $mainPlayer),
    "HNT212" => SubtypeContains($attackID, "Dagger", $mainPlayer),
    "HNT213" => SubtypeContains($attackID, "Dagger", $mainPlayer),
    "HNT221" => true,
    "HNT236" => true,
    "HNT237" => true,
    "HNT239" => true,
    "HNT240" => AttackValue($attackID) <= 3,
    "HNT241" => CheckMarked($defPlayer),
    "HNT242" => CheckMarked($defPlayer),
    "HNT243" => CheckMarked($defPlayer),
    "HNT249" => true,
    "HNT258" => CardNameContains($attackID, "Raydn", $mainPlayer, true),
    "HNT407" => ContractType($attackID) != "",
    default => false,
  };
}

function HNTPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = ""): string
{
  global $currentPlayer, $CS_ArcaneDamagePrevention, $CS_NumSeismicSurgeDestroyed, $CombatChain, $CS_NumRedPlayed, $CS_AtksWWeapon, $CS_NumAttackCards;
  global $CS_NumNonAttackCards, $CS_NumBoosted, $combatChain;
  $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
  switch ($cardID) {
    case "HNT003":
    case "HNT004":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      if (HasStealth($CombatChain->AttackCard()->ID())) AddCurrentTurnEffect("$cardID-HIT", $currentPlayer);
      break;
    case "HNT005":
      EquipWeapon($currentPlayer, "HNT053");
      AddCurrentTurnEffectNextAttack($cardID, $currentPlayer);
      break;
    case "HNT006":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      if (HasStealth($CombatChain->AttackCard()->ID())) GiveAttackGoAgain();
      break;
    case "HNT007":
      AddCurrentTurnEffect("HNT007", $currentPlayer);
      break;
    case "HNT013":
      if (GetResolvedAbilityType($cardID, "HAND") == "I") {
        AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYDISCARD:subtype=Trap");
        AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("MZOP", $currentPlayer, "GETCARDID", 1);
        AddDecisionQueue("BANISHCARD", $currentPlayer, "DISCARD,TT", 1);
        AddDecisionQueue("UNDERTRAPDOOR", $currentPlayer, "<-", 1);
      }
      break;
    case "HNT014":
      global $CombatChain;
      if (IsHeroAttackTarget() && CheckMarked($otherPlayer)) {
        AddCurrentTurnEffect("$cardID-FULL", $currentPlayer);
        AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYDISCARD:hasStealth=1");
        AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("SETDQVAR", $currentPlayer, "1", 1);
        AddDecisionQueue("MZOP", $currentPlayer, "GETCARDID", 1);
        AddDecisionQueue("CURRENTATTACKBECOMES", $currentPlayer, "-", 1);
        AddDecisionQueue("PASSPARAMETER", $currentPlayer, "{1}", 1);
        AddDecisionQueue("MZBANISH", $currentPlayer, "-", 1);
        AddDecisionQueue("MZREMOVE", $currentPlayer, "-", 1);
      }
      else AddCurrentTurnEffect("$cardID", $currentPlayer);
    case "HNT015":
      AddDecisionQueue("PASSPARAMETER", $currentPlayer, $additionalCosts, 1);
      AddDecisionQueue("MODAL", $currentPlayer, "TARANTULATOXIN", 1);
      break;
    case "HNT017":
    case "HNT018":
    case "HNT019":
      if (IsHeroAttackTarget())
      {
        ThrowWeapon("Dagger", $cardID, true);
      }
      break;
    case "HNT020":
    case "HNT021":
    case "HNT022":
      if (IsHeroAttackTarget() && CheckMarked($otherPlayer)) EquipWeapon($currentPlayer, "HNT053");
      break;
    case "HNT023":
    case "HNT024":
    case "HNT025":
      GiveAttackGoAgain();
      AddCurrentTurnEffect($cardID, $currentPlayer);
      break;
    case "HNT026":
    case "HNT027":
    case "HNT028":
      EquipWeapon($currentPlayer, "HNT053");
      AddCurrentTurnEffect($cardID, $currentPlayer);
      break;
    case "HNT044":
    case "HNT045":
    case "HNT046":
      if (GetResolvedAbilityType($cardID, "HAND") == "I") {
        MarkHero($otherPlayer);
      }
      break;
    case "HNT047":
    case "HNT048":
    case "HNT049":
      if (IsHeroAttackTarget() && CheckMarked($otherPlayer)) GiveAttackGoAgain();
      break;
    case "HNT051":
      AddDecisionQueue("PASSPARAMETER", $currentPlayer, $additionalCosts, 1);
      AddDecisionQueue("MODAL", $currentPlayer, "TWOSIDES", 1);
      break;
    case "HNT053":
      if (IsHeroAttackTarget() && CheckMarked($otherPlayer)) GiveAttackGoAgain();
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
    case "HNT058":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      break;
    case "HNT059":
    case "HNT060":
      if(IsHeroAttackTarget() && NumDraconicChainLinks() >= 2) {
        GiveAttackGoAgain();
        PlayAura("HNT167", $currentPlayer);
      }
      break;
    case "HNT061":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      break;
    case "HNT071":
      $uniqueID = $CombatChain->AttackCard()->UniqueID();
      if(TalentContains($cardID, "DRACONIC", $currentPlayer)) {
        AddCurrentTurnEffect("$cardID-$uniqueID", $currentPlayer);
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
    case "HNT077":
    case "HNT078":
    case "HNT079":
      if(TalentContains($cardID, "DRACONIC", $currentPlayer)) {
        AddCurrentTurnEffect($cardID, $currentPlayer);
      }
      break;
    case "HNT080":
    case "HNT081":
    case "HNT082":
      if(TalentContains($cardID, "DRACONIC", $currentPlayer) && IsHeroAttackTarget()) {
        ThrowWeapon("Dagger", $cardID, true);
      }
      break;
    case "HNT083":
    case "HNT084":
    case "HNT085":
      AddCurrentTurnEffectNextAttack($cardID, $currentPlayer);
      break;
    case "HNT101":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      break;
    case "HNT102":
      AddDecisionQueue("PASSPARAMETER", $currentPlayer, $additionalCosts, 1);
      AddDecisionQueue("MODAL", $currentPlayer, "LONGWHISKER", 1);
      break;
    case "HNT103":
    case "HNT104":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      if (NumDraconicChainLinks() >=2) PlayAura("HNT167", $currentPlayer);
      break;
    case "HNT105":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      $character = &GetPlayerCharacter($currentPlayer);
      $weaponIndex1 = CharacterPieces();
      $weaponIndex2 = CharacterPieces() * 2;
      if(SubtypeContains($character[$weaponIndex1], "Dagger")) AddCharacterUses($currentPlayer, $weaponIndex1, 1);
      if(SubtypeContains($character[$weaponIndex2], "Dagger")) AddCharacterUses($currentPlayer, $weaponIndex2, 1);
      break;
    case "HNT106":
    case "HNT107":
    case "HNT108":
    case "HNT109":
    case "HNT110":
    case "HNT111":
    case "HNT112":
    case "HNT113":
    case "HNT114":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      break;
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
    case "HNT119":
    case "HNT120":
    case "HNT121":
      AddCurrentTurnEffectNextAttack($cardID, $currentPlayer);
      break;
    case "HNT122":
    case "HNT123":
    case "HNT124":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      break;
    case "HNT128":
    case "HNT129":
    case "HNT130":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      break;
    case "HNT131":
    case "HNT132":
    case "HNT133":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      break;
    case "HNT134":
    case "HNT135":
    case "HNT136":
      AddCurrentTurnEffect($cardID."-BUFF", $currentPlayer);
      AddCurrentTurnEffect($cardID."-GOAGAIN", $currentPlayer);
      break;
    case "HNT137":
    case "HNT138":
    case "HNT139":
      AddCurrentTurnEffect($cardID."-NEXTDAGGER", $currentPlayer);
      AddCurrentTurnEffect($cardID."-MARKEDBUFF", $currentPlayer);
      break;
    case "HNT140":
    case "HNT141":
    case "HNT142":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      break;
    case "HNT144":
      MarkHero($otherPlayer);
      break;
    case "HNT145":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      break;
    case "HNT148":
      GainResources($currentPlayer, 1);
      AddCurrentTurnEffect($cardID, $currentPlayer);
      break;
    case "HNT149":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      break;
    case "HNT152":
      $otherChar = &GetPlayerCharacter($otherPlayer);
      if (CardNameContains($otherChar[0], "Arakni")) {
        MarkHero($otherPlayer);
      }
      break;
    case "HNT154":
        $cardRemoved = BubbleToTheSurface();
        if($cardRemoved == "") { AddCurrentTurnEffect("HNT154-7", $currentPlayer); return "You cannot reveal cards."; }
        else {
          BanishCardForPlayer($cardRemoved, $currentPlayer, "DECK", "TT", "HNT154");
        }
      break;
    case "HNT155":
      GainResources($currentPlayer, 1);
      Draw($currentPlayer, effectSource:$cardID);
      break;
    case "HNT156":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      AddCurrentTurnEffect($cardID, $otherPlayer);
      break;
    case "HNT158": case "HNT159": case "HNT160":
      if(IsHeroAttackTarget() && CheckMarked($otherPlayer)) {
        PlayAura("HNT167", $currentPlayer);
      }
      break;
    case "HNT161":
      if(GetClassState($currentPlayer, $CS_NumRedPlayed) > 1 && IsHeroAttackTarget()){
        MarkHero($otherPlayer);
      }
      break;
    case "HNT164":
      PlayAura("HNT167", $currentPlayer);
      break;
    case "HNT165":
      $otherChar = &GetPlayerCharacter($otherPlayer);
      MarkHero($otherPlayer);
      if (CardNameContains($otherChar[0], "Arakni")) {
        GainResources($currentPlayer, 1);
      }
      break;
    case "HNT166":
      AddCurrentTurnEffectNextAttack($cardID, $currentPlayer);
      break;
    case "HNT167":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      break;
    case "HNT173":
      ThrowWeapon("Dagger", $cardID);
      break;
    case "HNT175":
      ThrowWeapon("Dagger", $cardID, onHitDraw: true);
      break;
    case "HNT179":
    case "HNT180":
    case "HNT181":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      Retrieve($currentPlayer, "Dagger");
      break;
    case "HNT182":
    case "HNT183":
    case "HNT184":
      Retrieve($currentPlayer, "Dagger");
      break;
    case "HNT185":
    case "HNT186":
    case "HNT187":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      break;
    case "HNT188":
    case "HNT189":
    case "HNT190":
      if(IsHeroAttackTarget()) throwWeapon("Dagger", $cardID, true);
      break;
    case "HNT196":
      GiveAttackGoAgain();
      break;
    case "HNT197":
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYCHAR:subtype=Dagger", 1);
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a dagger to attack an additional time and discount", 1);
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("EXTRAATTACK", $currentPlayer, "<-", 1);
      AddDecisionQueue("PERFORATE", $currentPlayer, "<-", 1);
      AddDecisionQueue("DRAW", $currentPlayer, "-", 1);
      break;
    case "HNT198":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      AddCurrentTurnEffect("$cardID-HIT", $currentPlayer);
      break;
    case "HNT202":
    case "HNT203":
    case "HNT204":     
      AddCurrentTurnEffect($cardID, $currentPlayer);
      AddDecisionQueue("FINDINDICES", $otherPlayer, "HAND");
      AddDecisionQueue("REVEALHANDCARDS", $otherPlayer, "-", 1);
      AddDecisionQueue("IFTYPEREVEALED", $otherPlayer, "AR", 1);
      AddDecisionQueue("MARKHERO", $otherPlayer, "-", 1);
      break;
    case "HNT208":
    case "HNT209":
    case "HNT210":
    case "HNT211":
    case "HNT212":
    case "HNT213":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      break;
    case "HNT215":
      if (!SearchCurrentTurnEffects($cardID, $currentPlayer)) AddCurrentTurnEffect($cardID, $currentPlayer);
      $ind = SearchCharacterForCard($currentPlayer, $cardID);
      $char = &GetPlayerCharacter($currentPlayer);
      $char[$ind + 6] = 1;
      AddDecisionQueue("CHARFLAGDESTROY", $currentPlayer, FindCharacterIndex($currentPlayer, $cardID), 1);
      break;
    case "HNT221":
      $myMaxCards = SearchCount(SearchDiscard($currentPlayer, maxAttack:1, minAttack:1));
      $oppMaxCards = SearchCount(SearchDiscard($otherPlayer, maxAttack:1, minAttack:1));
      $maxCards = min($myMaxCards, $oppMaxCards);
      for ($i = 0; $i < $maxCards; $i++) {
        AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYDISCARD:maxAttack=1;minAttack=1",1);
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a card to banish", 1);
        AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("MZBANISH", $currentPlayer, "GY,-," . $currentPlayer . ",1", 1);
        AddDecisionQueue("MZREMOVE", $currentPlayer, "-", 1);
        AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "THEIRDISCARD:maxAttack=1;minAttack=1");
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a card to banish", 1);
        AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("MZBANISH", $currentPlayer, "GY,-," . $currentPlayer . ",1", 1);
        AddDecisionQueue("MZREMOVE", $currentPlayer, "-", 1);
        AddDecisionQueue("ADDCURRENTEFFECT", $currentPlayer, $cardID, 1);
      }
      break;
    case "HNT223":
      if(GetClassState($currentPlayer, $CS_AtksWWeapon) > 0) AddCurrentTurnEffect($cardID."-AA", $currentPlayer);
      if(GetClassState($currentPlayer, $CS_NumAttackCards) > 0) AddCurrentTurnEffect($cardID."-WEAPON", $currentPlayer);
      break;
    case "HNT226";
      AddDecisionQueue("FINDINDICES", $otherPlayer, "HAND");
      AddDecisionQueue("REVEALHANDCARDS", $otherPlayer, "-", 1);
      AddDecisionQueue("IFTYPEREVEALED", $otherPlayer, "AR", 1);
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYDECK:type=DR", 1);
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("MZREMOVE", $currentPlayer, "-", 1);
      AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-", 1);
      AddDecisionQueue("REVEALCARDS", $currentPlayer, "-", 1);
      AddDecisionQueue("MULTIADDTOPDECK", $currentPlayer, "-", 1);
      break;
    case "HNT229":
      MarkHero($otherPlayer);
      break;
    case "HNT232":
    case "HNT233":
    case "HNT234":
      if (GetResolvedAbilityType($cardID, "HAND") == "I") {
        MarkHero($otherPlayer);
      }
      break;
    case "HNT236":
      if(!IsAllyAttacking() && CheckMarked($otherPlayer)) {
        AddCurrentTurnEffectNextAttack($cardID, $otherPlayer);
      }
      break;
    case "HNT237";
      AddCurrentTurnEffect($cardID, $currentPlayer);
      MarkHero($otherPlayer);
      break;
    case "HNT239":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      break;
    case "HNT240":
      AddCurrentTurnEffectNextAttack($cardID, $currentPlayer);
      break;
    case "HNT241":
    case "HNT242":
    case "HNT243":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      MarkHero($otherPlayer);
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
    case "HNT250":
      if(GetClassState($currentPlayer, $CS_NumBoosted) > 1) AddCurrentTurnEffect($cardID."-2", $currentPlayer);
      else AddCurrentTurnEffect($cardID."-1", $currentPlayer);
      break;
    case "HNT251":
      AddDecisionQueue("INPUTCARDNAME", $currentPlayer, "-");
      AddDecisionQueue("SETDQVAR", $currentPlayer, "0");
      AddDecisionQueue("WRITELOG", $currentPlayer, "📣<b>{0}</b> was chosen");
      AddDecisionQueue("NULLTIMEZONE", $currentPlayer, SearchItemForLastIndex($cardID, $currentPlayer).",{0}");
      return "";
    case "HNT252":
      $prevent = SearchArsenal($currentPlayer, subtype:"Arrow", faceUp:true) != "" ? 2 : 1;
      IncrementClassState($currentPlayer, $CS_ArcaneDamagePrevention, $prevent);
      return CardLink($cardID, $cardID) . " prevent your next arcane damage by " . $prevent;
    case "HNT254":
      PlayAura("ARC112", $currentPlayer, GetClassState($currentPlayer, $CS_NumNonAttackCards), isToken:true);
      if (GetClassState($currentPlayer, piece: $CS_NumNonAttackCards) >= 3) GiveAttackGoAgain();
      break;
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
      break;
    case "HNT407":
      AddCurrentTurnEffect("HNT407", $currentPlayer);
      SetArsenalFacing("UP", $currentPlayer);
      break;
    default:
      break;
  }
  return "";
}

function HNTHitEffect($cardID, $uniqueID = -1): void
{
  global $mainPlayer, $defPlayer, $CS_LastAttack, $CCS_GoesWhereAfterLinkResolves, $chainLinkSummary, $combatChainState;
  $dashArr = explode("-", $cardID);
  $cardID = $dashArr[0];
  switch ($cardID) {
    case "HNT009":
      MarkHero($defPlayer);
      break;
    case "HNT010":
      AddDecisionQueue("YESNO", $mainPlayer, "if you want to destroy " . CardLink($cardID, $cardID) . " and mark the opponent", 0, 1);
      AddDecisionQueue("NOPASS", $mainPlayer, "-", 1);
      AddDecisionQueue("HUNTSMANMARK", $mainPlayer, $uniqueID);
      break;
    case "HNT012":
      WriteLog("Player $defPlayer loses 1 life.");
      LoseHealth(1, $defPlayer);
      break;
    case "HNT032":
    case "HNT033":
    case "HNT034":
      AddDecisionQueue("FINDINDICES", $defPlayer, "HAND");
      AddDecisionQueue("SETDQCONTEXT", $defPlayer, "Choose a card to banish", 1);
      AddDecisionQueue("CHOOSEHAND", $defPlayer, "<-", 1);
      AddDecisionQueue("MULTIREMOVEHAND", $defPlayer, "-", 1);
      AddDecisionQueue("BANISHCARD", $defPlayer, "HAND,-", 1);
      break;
    case "HNT035":
    case "HNT036":
    case "HNT037":
      MZMoveCard($mainPlayer, "THEIRARS", "THEIRBANISH,ARS,-," . $mainPlayer, false);
      break;
    case "HNT038":
    case "HNT039":
    case "HNT040":
      MarkHero($defPlayer);
      break;
    case "HNT064":
      ThrowWeapon("Dagger", $cardID, true);
      break;
    case "HNT067":
    case "HNT069":
      MarkHero($defPlayer);
      break;
    case "HNT072":
      if(isPreviousLinkDraconic()) {
        $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "-"; 
        BanishCardForPlayer("HNT072", $mainPlayer, "COMBATCHAIN", "TT", $mainPlayer); # throw Devotion Never Dies to banish. it can be played this turn (TT)
      }
      break;
    case "HNT074":
      DestroyArsenal($defPlayer, effectController:$mainPlayer);
      break;
    case "HNT076":
      AddDecisionQueue("FINDINDICES", $defPlayer, "EQUIP");
      AddDecisionQueue("CHOOSETHEIRCHARACTER", $mainPlayer, "<-", 1);
      AddDecisionQueue("MODDEFCOUNTER", $defPlayer, "-1", 1);
      AddDecisionQueue("DESTROYEQUIPDEF0", $mainPlayer, "-", 1);
      break;
    case "HNT092":
    case "HNT093":
    case "HNT094":
    case "HNT095":
    case "HNT096":
    case "HNT097":
      MarkHero($defPlayer);
      break;
    case "HNT174":
      ThrowWeapon("Dagger", $cardID, destroy: false);
      break;
    case "HNT224":
    case "HNT225":
      MarkHero($defPlayer);
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
  $effectIndex = SearchCurrentTurnEffectsForIndex("HNT244", $player);
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
    AddDecisionQueue("MAYCHOOSECARD", $player, "<-", 1);
    AddDecisionQueue("EQUIPCARDGRAVEYARD", $player, "<-", 1);
  }
}

function ListDracDaggersGraveyard($player) {
  $weapons = "";
  $char = &GetPlayerCharacter($player);
  $graveyard = &GetDiscard($player);
  foreach ($graveyard as $cardID) {
    if (TypeContains($cardID, "W", $player) && SubtypeContains($cardID, "Dagger")) {
      if (TalentContains($cardID, "DRACONIC")) {
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

function ChaosTransform($characterID, $mainPlayer, $toAgent = false, $choice = -1)
{
  global $CS_OriginalHero;
  $char = &GetPlayerCharacter($mainPlayer);
  if ($characterID == "HNT001" || $characterID == "HNT002" || $toAgent) {
    if ($choice == -1) {
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
    }
    else $transformTarget = $choice;
    WriteLog(CardName($characterID) . " becomes " . CardName($transformTarget));
    if (GetClassState($mainPlayer, $CS_OriginalHero) == "-") {
      SetClassState($mainPlayer, $CS_OriginalHero, $characterID);
    }
  }
  else {
    $transformTarget = GetClassState($mainPlayer, $CS_OriginalHero);
    if ($transformTarget == "-"){
      WriteLog("Something has gone wrong, please submit a bug report");
      $transformTarget = "HNT001";
    }
    SetClassState($mainPlayer, $CS_OriginalHero, "-");
  }
  $char[0] = $transformTarget;
  //don't trigger trapdoor if you transfrom from trapdoor into trapdoor
  if ($transformTarget == "HNT008" && $characterID != "HNT008") {
    AddDecisionQueue("YESNO", $mainPlayer, ":_banish_a_card_to_".CardLink("HNT008", "HNT008")."?");
    AddDecisionQueue("NOPASS", $mainPlayer, "-");
    AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "MYDECK", 1);
    AddDecisionQueue("MAYCHOOSEMULTIZONE", $mainPlayer, "<-", 1);
    AddDecisionQueue("TRAPDOOR", $mainPlayer, "-", 1);
    AddDecisionQueue("SHUFFLEDECK", $mainPlayer, "-", 1);
  }
}

function AddedOnHit($cardID) //tracks whether a card adds an on-hit to its applicable attack (for kiss of death)
{
  return match($cardID) {
    "EVR176" => true,
    "DYN118" => true,
    "OUT021" => true,
    "OUT022" => true,
    "OUT023" => true,
    "OUT143" => true,
    "OUT158" => true,
    "OUT165" => true,
    "MST105-HIT" => true,
    "HNT003-HIT" => true,
    "HNT004-HIT" => true,
    "HNT051" => true,
    "HNT208" => true,
    "HNT209" => true,
    "HNT210" => true,
    default => false
  };
}

function IsStaticBuff($cardID) {//tracks buffs that attach themselves to a card, even if it transforms
  //for now only tracking dagger buffs, ideally we'd want to track all static buffs
  return match($cardID) {
    "OUT151" => true,
    "HNT179" => true,
    "HNT180" => true,
    "HNT181" => true,
    "HNT198" => true,
    "HNT202" => true,
    default => false
  };
}

function BubbleToTheSurface()
{
  global $currentPlayer;
  if(!CanRevealCards($currentPlayer)) return "";
    $cardRemoved = "";
    $deck = &GetDeck($currentPlayer);
    $cardsToReveal = "";
    for($i=0; $i<count($deck); ++$i)
    {
      if($cardsToReveal != "") $cardsToReveal .= ",";
      $cardsToReveal .= $deck[$i];
      if(PitchValue($deck[$i]) == 1)
            {
        $cardRemoved = $deck[$i];
        unset($deck[$i]);
        $deck = array_values($deck);
        RevealCards($cardsToReveal);
        AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-");
        return $cardRemoved;
      }
    }
    return $cardRemoved;
  }

  function Retrieve($player, $subtype)
  {
    if (SearchDiscard($player, subtype:$subtype) != "") {
      AddDecisionQueue("YESNO", $player, "if_you_want_to_pay_a_resource_to_retrieve_a_$subtype");
      AddDecisionQueue("NOPASS", $player, "-", 1);
      AddDecisionQueue("PASSPARAMETER", $player, "1", 1);
      AddDecisionQueue("PAYRESOURCES", $player, "<-", 1);
      AddDecisionQueue("MULTIZONEINDICES", $player, "MYDISCARD:subtype=$subtype", 1);
      AddDecisionQueue("SETDQCONTEXT", $player, "Choose a dagger to equip", 1);
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $player, "<-", 1);
      AddDecisionQueue("MZOP", $player, "GETCARDID", 1);
      AddDecisionQueue("EQUIPCARDGRAVEYARD", $player, "<-", 1);
  }
  }
