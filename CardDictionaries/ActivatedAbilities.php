<?php

function AKOAbilityCost($cardID): int
{
  return 0;
}

function AKOAbilityType($cardID, $index = -1, $from = "-"): string
{
  return match ($cardID) {
    "AKO004" => "A",
    default => ""
  };
}

function AKOAbilityHasGoAgain($cardID): bool
{
  return match ($cardID) {
    "AKO004" => true,
    default => false
  };
}

function HVYAbilityCost($cardID): int
{
  return match ($cardID) {
    "HVY049", "HVY050", "HVY196", "HVY197" => 3,
    "HVY006", "HVY007", "HVY245" => 2,
    "HVY095", "HVY096", "HVY098", "HVY099", "HVY134" => 1,
    default => 0
  };
}

function HVYAbilityType($cardID, $index = -1, $from = "-"): string
{
  return match ($cardID) {
    "HVY134", "HVY096", "HVY095", "HVY007", "HVY049", "HVY050", "HVY006" => "AA",
    "HVY175", "HVY155", "HVY135", "HVY091", "HVY010", "HVY055", "HVY090", "HVY009" => "A",
    "HVY099", "HVY098" => "AR",
    "HVY195", "HVY196", "HVY197" => "I",
    "HVY245" => $from == "GY" ? "I" : "AA",
    default => ""
  };
}

function HVYAbilityHasGoAgain($cardID): bool
{
  return match ($cardID) {
    "HVY010", "HVY055", "HVY090", "HVY091", "HVY135", "HVY155", "HVY175", "HVY243" => true,
    default => false
  };
}

function TCCAbilityCost($cardID): int
{
  return match ($cardID) {
    "TCC002", "TCC028" => 3,
    "TCC050" => GetResolvedAbilityType($cardID) == "A" ? 3 : 0,
    default => 0
  };
}

function TCCAbilityType($cardID, $index = -1): string
{
  return match ($cardID) {
    "TCC002", "TCC028" => "AA",
    "TCC050", "TCC051", "TCC052", "TCC053", "TCC054" => "A",
    "TCC079", "TCC080", "TCC082" => "I",
    default => ""
  };
}

function TCCAbilityHasGoAgain($cardID): bool
{
  global $CombatChain;
  return match ($cardID) {
    "TCC051", "TCC052", "TCC053", "TCC054" => true,
    "TCC050" => !$CombatChain->HasCurrentLink(),
    default => false
  };
}

function EVOAbilityCost($cardID): int
{
  global $currentPlayer;
  return match ($cardID) {
    "EVO009" => (EvoUpgradeAmount($currentPlayer) == 1 ? 3 : EvoUpgradeAmount($currentPlayer) >= 2) ? 1 : 0,
    "EVO007", "EVO410", "EVO008" => 3,
    "EVO004", "EVO083", "EVO082", "EVO235", "EVO247", "EVO081", "EVO005" => 2,
    "EVO017", "EVO016", "EVO015", "EVO014", "EVO006" => 1,
    default => 0
  };
}

function EVOAbilityType($cardID, $index = -1, $from = ""): string
{
  global $currentPlayer, $CS_NumCranked, $CS_NumBoosted;
  return match ($cardID) {
    "EVO004", "EVO005" => GetClassState($currentPlayer, $CS_NumBoosted) > 0 ? "A" : "",
    "EVO006" => GetClassState($currentPlayer, $CS_NumCranked) > 0 ? "AA" : "",
    "EVO009" => EvoUpgradeAmount($currentPlayer) >= 1 ? "AA" : "",
    "EVO071", "EVO075", "EVO089", "EVO088", "EVO087", "EVO083", "EVO082", "EVO081", "EVO077", "EVO072" => $from == "PLAY" ? "I" : "A",
    "EVO410", "EVO003" => "AA",
    "EVO007", "EVO017", "EVO016", "EVO015", "EVO014", "EVO437", "EVO436", "EVO435", "EVO434", "EVO449", "EVO448", "EVO447", "EVO446", "EVO008" => "I",
    "EVO073", "EVO076", "EVO247", "EVO070" => "A",
    "EVO235" => "AR",
    default => ""
  };
}

function EVOAbilityHasGoAgain($cardID): bool
{
  return match ($cardID) {
    "EVO073", "EVO247" => true,
    default => false
  };
}

function DestroyTopCardTarget($player): void
{
  $otherPlayer = ($player == 1 ? 2 : 1);
  AddDecisionQueue("PASSPARAMETER", $player, "ELSE");
  AddDecisionQueue("SETDQVAR", $player, "1");
  if (ShouldAutotargetOpponent($player)) {
    AddDecisionQueue("PASSPARAMETER", $player, "Target_Opponent");
  } else {
    AddDecisionQueue("SETDQCONTEXT", $player, "Choose target hero");
    AddDecisionQueue("BUTTONINPUT", $player, "Target_Opponent,Target_Yourself");
  }
  AddDecisionQueue("EQUALPASS", $player, "Target_Opponent");
  AddDecisionQueue("DESTROYTOPCARD", $player, "0", 1);
  AddDecisionQueue("SETDQVAR", $player, "1", 1);
  AddDecisionQueue("PASSPARAMETER", $player, "{1}");

  AddDecisionQueue("NOTEQUALPASS", $player, "ELSE");
  AddDecisionQueue("DESTROYTOPCARD", $otherPlayer, "0", 1);
  AddDecisionQueue("SETDQVAR", $otherPlayer, "1", 1);
}

function DestroyTopCardOpponent($player): void
{
  $otherPlayer = ($player == 1 ? 2 : 1);
  AddDecisionQueue("WRITELOG", $otherPlayer, "Destroys the top card of Player " . $otherPlayer . " deck", 1);
  AddDecisionQueue("DESTROYTOPCARD", $otherPlayer, "0", 1);
}

function DestroyItemWithoutSteamCounter($cardID, $player): bool
{
  if (CardNameContains($cardID, "Hyper Driver", $player)) return true;
  return match ($cardID) {
    "ARC037", "ARC007", "ARC019", "DYN093", "EVR072", "CRU104" => true,
    default => false
  };
}

function ASBAbilityType($cardID, $index = -1): string
{
  return match ($cardID) {
    "ASB004" => "I",
    default => ""
  };
}

?>
