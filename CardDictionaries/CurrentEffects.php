<?php

function TCCEffectAttackModifier($cardID): int|string
{
  $idArr = explode(",", $cardID);
  $cardID = $idArr[0];
  return match ($cardID) {
    "TCC037" => 6,
    "TCC038", "TCC042" => 5,
    "TCC043" => 4,
    "TCC057" => $idArr[1],
    "TCC083", "TCC105", "TCC409", "TCC086", "TCC094" => 1,
    default => 0
  };
}

function TCCCombatEffectActive($cardID, $attackID): bool
{
  global $mainPlayer;
  $idArr = explode(",", $cardID);
  $cardID = $idArr[0];
  return match ($cardID) {
    "TCC037", "TCC038", "TCC042", "TCC043" => ClassContains($attackID, "GUARDIAN", $mainPlayer) && CardType($attackID) == "AA",
    "TCC086", "TCC094" => CardNameContains($attackID, "Crouching Tiger", $mainPlayer),
    "TCC035", "HVY063", "TCC057", "TCC083", "TCC105", "TCC409" => true,
    default => false
  };
}

function EVOEffectAttackModifier($cardID): int|string
{
  $idArr = explode(",", $cardID);
  $cardID = $idArr[0];
  return match ($cardID) {
    "EVO090", "EVO091", "EVO092" => $idArr[1],
    "EVO156", "EVO222", "EVO225", "EVO228" => 4,
    "EVO140", "EVO157", "EVO223", "EVO226", "EVO229" => 3,
    "EVO141", "EVO153", "EVO154", "EVO155", "EVO158", "EVO224", "EVO227", "EVO230", "EVO432" => 2,
    "EVO016", "EVO052", "EVO126", "EVO127", "EVO128", "EVO240", "EVO436", "EVO192", "EVO193", "EVO194", "EVO195",
    "EVO196", "EVO197" => 1,
    default => 0,
  };
}

function EVOCombatEffectActive($cardID, $attackID)
{
  global $mainPlayer, $combatChainState, $CCS_IsBoosted, $CS_NumItemsDestroyed;
  $idArr = explode(",", $cardID);
  $cardID = $idArr[0];
  return match ($cardID) {
    "EVO016", "EVO156", "EVO157", "EVO158" => ClassContains($attackID, "MECHANOLOGIST", $mainPlayer),
    "EVO222", "EVO223", "EVO224", "EVO225", "EVO226", "EVO227", "EVO228", "EVO229", "EVO230" => $combatChainState[$CCS_IsBoosted],
    "EVO240", "EVO434", "EVO436" => TypeContains($attackID, "W", $mainPlayer),
    "EVO052", "EVO090", "EVO091", "EVO092", "EVO102", "EVO103", "EVO104", "EVO105", "EVO106", "EVO107", "EVO126",
    "EVO127", "EVO128", "EVO140", "EVO141", "EVO146", "EVO153", "EVO154", "EVO155", "EVO192", "EVO193", "EVO194",
    "EVO195", "EVO196", "EVO197", "EVO432" => true,
    default => false
  };
}

function HVYEffectAttackModifier($cardID): int|string
{
  $idArr = explode(",", $cardID);
  $cardID = $idArr[0];
  return match ($cardID) {
    "HVY041", "HVY042", "HVY043", "HVY211" => $idArr[1],
    "HVY053" => $idArr[1] == "ACTIVE" ? -1 : 0,
    "HVY083-BUFF", "HVY086-BUFF", "HVY109" => 5,
    "HVY084-BUFF", "HVY087-BUFF", "HVY110" => 4,
    "HVY059", "HVY085-BUFF", "HVY088-BUFF", "HVY104-BUFF", "HVY106", "HVY111", "HVY115", "HVY118", "HVY121", "HVY124-BUFF",
    "HVY127", "HVY130-BUFF", "HVY152", "HVY172", "HVY176-BUFF", "HVY192", "HVY213", "HVY214", "HVY215", "HVY235-BUFF" => 3,
    "HVY017", "HVY018", "HVY019", "HVY101", "HVY107", "HVY116", "HVY119", "HVY122", "HVY125-BUFF", "HVY128", "HVY131-BUFF",
    "HVY153", "HVY173", "HVY193", "HVY210", "HVY236-BUFF" => 2,
    "HVY045", "HVY046", "HVY058", "HVY108", "HVY117", "HVY120", "HVY123", "HVY126-BUFF", "HVY129", "HVY132-BUFF", "HVY154",
    "HVY174", "HVY194", "HVY237-BUFF", "HVY241", "HVY247" => 1,
    default => 0
  };
}

function HVYCombatEffectActive($cardID, $attackID)
{
  global $mainPlayer, $CombatChain;
  $idArr = explode(",", $cardID);
  $cardID = $idArr[0];
  return match ($cardID) {
    "HVY041", "HVY042", "HVY043" => ClassContains($CombatChain->AttackCard()->ID(), "BRUTE", $mainPlayer),
    "HVY083-BUFF", "HVY084-BUFF", "HVY085-BUFF", "HVY086-BUFF", "HVY087-BUFF", "HVY088-BUFF" => ClassContains($CombatChain->AttackCard()->ID(), "GUARDIAN", $mainPlayer),
    "HVY090", "HVY091" => TypeContains($attackID, "W", $mainPlayer) && !IsAllyAttackTarget(),
    "HVY101" => TypeContains($attackID, "W", $mainPlayer),
    "HVY104", "HVY104-BUFF", "HVY121", "HVY122", "HVY123", "HVY124-BUFF", "HVY125-BUFF", "HVY126-BUFF", "HVY127",
    "HVY128", "HVY129", "HVY130-BUFF", "HVY131-BUFF", "HVY132-BUFF" => ClassContains($CombatChain->AttackCard()->ID(), "WARRIOR", $mainPlayer),
    "HVY152", "HVY153", "HVY154" => ClassContains($CombatChain->AttackCard()->ID(), "BRUTE", $mainPlayer) || ClassContains($CombatChain->AttackCard()->ID(), "GUARDIAN", $mainPlayer),
    "HVY172", "HVY173", "HVY174" => ClassContains($CombatChain->AttackCard()->ID(), "BRUTE", $mainPlayer) || ClassContains($CombatChain->AttackCard()->ID(), "WARRIOR", $mainPlayer),
    "HVY176-BUFF" => CachedWagerActive(),
    "HVY192", "HVY193", "HVY194" => ClassContains($CombatChain->AttackCard()->ID(), "WARRIOR", $mainPlayer) || ClassContains($CombatChain->AttackCard()->ID(), "GUARDIAN", $mainPlayer),
    "HVY247" => HasCombo($attackID),
    "HVY254-1" => str_contains(NameOverride($CombatChain->AttackCard()->ID(), $mainPlayer), "Herald"),
    "HVY254-2" => DelimStringContains(CardSubType($CombatChain->AttackCard()->ID()), "Angel"),
    "HVY246" => ClassContains($CombatChain->AttackCard()->ID(), "ASSASSIN", $mainPlayer),
    "HVY045", "HVY046", "HVY058", "HVY052", "HVY053", "HVY055-PAID", "HVY059", "HVY083", "HVY084", "HVY085", "HVY086",
    "HVY087", "HVY088", "HVY099", "HVY136", "HVY149", "HVY150", "HVY151", "HVY169", "HVY170", "HVY171", "HVY189", "HVY190",
    "HVY191", "HVY202", "HVY203", "HVY204", "HVY205", "HVY206", "HVY210", "HVY213", "HVY214", "HVY215", "HVY216", "HVY217",
    "HVY218", "HVY211", "HVY235-BUFF", "HVY236-BUFF", "HVY237-BUFF", "HVY240", "HVY241", "HVY106", "HVY107", "HVY108",
    "HVY109", "HVY110", "HVY111", "HVY115", "HVY116", "HVY117", "HVY118", "HVY119", "HVY120" => true,
    default => false
  };
}

?>
