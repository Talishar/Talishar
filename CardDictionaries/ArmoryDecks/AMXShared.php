<?php

function AMXAbilityType($cardID, $index = -1, $from = "-"): string
{
  global $currentPlayer, $CS_NumCranked;
  return match ($cardID) {
    "bank_breaker" => GetClassState($currentPlayer, $CS_NumCranked) > 0 ? "AA" : "",
    default => ""
  };
}

function AMXAbilityHasGoAgain($cardID): bool
{
  return match ($cardID) {
    default => false
  };
}

function AMXEffectPowerModifier($cardID): int
{
  return match ($cardID) {
    "twintek_charging_station_red" => 3,
    "fist_pump" => 1,
    default => 0
  };
}

function AMXCombatEffectActive($cardID, $attackID): bool
{
  global $combatChainState, $CCS_IsBoosted;
  return match($cardID) {
    "bank_breaker" => true,
    "twintek_charging_station_red" => $combatChainState[$CCS_IsBoosted],
    "fist_pump" => SubtypeContains($attackID, "Wrench"),
    default => false
  };
}

function AMXAbilityCost($cardID): int
{
  return match($cardID) {
    "bank_breaker" => 1,
    default => 0
  };
}

function AMXPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = ""): string
{
  global $currentPlayer, $CS_CharacterIndex;
  switch ($cardID) {
    case "bank_breaker":
      $character = &GetPlayerCharacter($currentPlayer);
      $index = GetClassState($currentPlayer, $CS_CharacterIndex);
      if (count(explode(",", $character[$index + 10])) > 0 && $character[$index + 10] != "-") {
        CharacterChooseSubcard($currentPlayer, $index, isMandatory:false);
        AddDecisionQueue("MULTIBANISH", $currentPlayer, "EQUIP,-", 1);
        AddDecisionQueue("ADDCURRENTEFFECT", $currentPlayer, "bank_breaker", 1);
      }
      return "";
    case "clamp_press_blue":
      // penetration script has something here, and I don't know why
      // leaving it blank for now
      return "";
    case "construct_bank_breaker_yellow":
      $conditionsMet = CheckIfConstructBankBreakerConditionsAreMet($currentPlayer);
      if ($conditionsMet != "") return $conditionsMet;
      $char = &GetPlayerCharacter($currentPlayer);
      // Add the new weapon stuff so we can put cards under it
      PutCharacterIntoPlayForPlayer("bank_breaker", $currentPlayer);
      // We don't want function calls in every iteration check
      $charCount = count($char);
      $charPieces = CharacterPieces();
      $bankBreakerIndex = $charCount - $charPieces; // we pushed it, so should be the last element
      //Congrats, you have met the requirement to build the wrench! Let's remove the old stuff
      for ($i = $charCount - $charPieces; $i >= 0; $i -= $charPieces) {
        if(CardType($char[$i]) == "W" && SubtypeContains($char[$i], "Wrench") && $i != $bankBreakerIndex) {
          RemoveCharacterAndAddAsSubcardToCharacter($currentPlayer, $i, $bankBreakerIndex);
        }
      }

      $hyperDrivers = SearchMultizone($currentPlayer, "MYITEMS:isSameName=hyper_driver_red");
      $hyperDrivers = str_replace("MYITEMS-", "", $hyperDrivers); // MULTICHOOSEITEMS expects indexes only but SearchItems does not have a sameName parameter
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Select 3 Hyper Driver to put under " . CardLink("bank_breaker", "bank_breaker"));
      AddDecisionQueue("MULTICHOOSEITEMS", $currentPlayer, "3-$hyperDrivers-3");
      AddDecisionQueue("SPECIFICCARD", $currentPlayer, "CONSTRUCTNITROMECHANOID,$bankBreakerIndex", 1);
      return "";
    case "twintek_charging_station_red":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYDISCARD:isSameName=hyper_driver_red");
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("MZREMOVE", $currentPlayer, "<-", 1);
      AddDecisionQueue("ADDTOPDECK", $currentPlayer, "", 1);
      AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "", 1);
      AddDecisionQueue("GAINRESOURCES", $currentPlayer, 1, 1);
      return "";
    default:
      return "";
  }
}

function CheckIfConstructBankBreakerConditionsAreMet($player)
{
  $hasWrench = false;
  $char = &GetPlayerCharacter($player);
  for ($i = 0; $i < count($char); $i += CharacterPieces()) {
    $characterCardID = $char[$i];
    if ($char[$i + 1] == 0) continue;
    if (CardType($characterCardID) == "W" && SubtypeContains($characterCardID, "Wrench")) $hasWrench = true;
  }
  if (!$hasWrench) return "You do not meet the wrench requirement";
  if (SearchCount(SearchMultizone($player, "MYITEMS:isSameName=hyper_driver")) < 3) return "You don't meet the ".CardLink("hyper_driver", "hyper_driver_red")." requirement";
  return "";
}
