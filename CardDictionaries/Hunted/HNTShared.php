<?php

function HNTAbilityType($cardID): string
{
  return match ($cardID) {
    "HNT054" => "I",
    "HNT055" => "I",
    "HNT167" => "I",
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
    default => 0,
  };
}

function HNTCombatEffectActive($cardID, $attackID): bool
{
  global $mainPlayer;
  $dashArr = explode("-", $cardID);
  $cardID = $dashArr[0];
  return match ($cardID) {
    "HNT071" => TalentContains($cardID, "DRACONIC", $mainPlayer),
    "HNT074" => TalentContains($cardID, "DRACONIC", $mainPlayer),
    "HNT075" => TalentContains($cardID, "DRACONIC", $mainPlayer),
    "HNT076" => TalentContains($cardID, "DRACONIC", $mainPlayer),
    "HNT116" => true,
    "HNT167" => DelimStringContains(CardType($attackID), "AA"),
    "HNT249" => true,
    default => false,
  };
}

function HNTPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = ""): string
{
  global $currentPlayer, $CS_ArcaneDamagePrevention;
  $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
  switch ($cardID) {
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
    case "HNT116":
      AddCurrentTurnEffect($cardID, $currentPlayer);
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
      return CardLink($cardID, $cardID) . " reduces your next arcane damage by " . $prevent;
    default:
      break;
  }
  return "";
}

function HNTHitEffect($cardID): void
{
  global $mainPlayer, $defPlayer;
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
  WriteLog($player . " is now marked!");
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
  $character = &GetPlayerCharacter($player);
  $character[13] = 0;
}

function RecurDagger($player, $mode) //$mode == 0 for left, and 1 for right
{
  $weapons = "";
  $char = &GetPlayerCharacter($player);
  $graveyard = &GetDiscard($player);
  if ($char[CharacterPieces() * ($mode + 1) + 1] == 0) { //Only Equip if there is a broken weapon/off-hand
    foreach ($graveyard as $cardID) {
      if (TypeContains($cardID, "W", $player) && SubtypeContains($cardID, "Dagger")) {
        if (TalentContains($cardID, "DRACONIC")) {
          if ($weapons != "") $weapons .= ",";
          $weapons .= $cardID;
        }
      };
    }
    if ($weapons == "") {
      WriteLog("Player " . $player . " doesn't have any dagger in their graveyard");
      return;
    }
    AddDecisionQueue("SETDQCONTEXT", $player, "Choose a dagger to equip");
    AddDecisionQueue("CHOOSECARD", $player, $weapons);
    AddDecisionQueue("EQUIPCARDGRAVEYARD", $player, "<-");
  }
}
