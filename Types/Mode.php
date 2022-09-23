<?php

class Mode
{
    public static int $None = 0;
    public static int $Deprecated_1 = 1;
    public static int $PlayCardFromHand = 2;
    public static int $PlayEquipmentAbility = 3;
    public static int $AddToArsenal = 4;
    public static int $PlayFromArsenal = 5;
    public static int $PitchToDeck = 6;
    public static int $InputNumber = 7;
    public static int $ChooseTop = 8;
    public static int $ChooseBottom = 9;
    public static int $PlayItemAbility = 10;
    public static int $ChooseCardFromDeck = 11;
    public static int $HandToTop = 12;
    public static int $HandToBottom = 13;
    public static int $Banish = 14;
    public static int $Deprecated_15 = 15;
    public static int $Choose = 16;
    public static int $ButtonInput = 17;
    public static int $Deprecated_18 = 18;
    public static int $MultiChoose = 19;
    public static int $YesNo = 20;
    public static int $PlayCombatChainAbility = 21;
    public static int $PlayAuraAbility = 22;
    public static int $ChooseCard = 23;
    public static int $PlayAllyAbility = 24;
    public static int $PlayLandmarkAbility = 25;
    public static int $ChainSetting = 26;
    public static int $PlayFromHandByIndex = 27;
    public static int $Pass = 99;
    public static int $BreakCombatChain = 100;
    public static int $PassBlocksAndReactions = 101;
    public static int $ToggleEquipment = 102;
    public static int $TogglePermanent = 103;
    public static int $ToggleOpponentPermanent = 104;
    public static int $Undo = 10000;
    public static int $CancelBlock = 10001;
    public static int $ManualAddActionPoint = 10002;
    public static int $RevertToPriorTurn = 10003;
    public static int $ManualSubActionPoint = 10004;
    public static int $ManualSubHealthPoint = 10005;
    public static int $ManualAddHealthPoint = 10006;
    public static int $ManualSubOpponentHealthPoint = 10007;
    public static int $ManualAddOpponentHealthPoint = 10008;
    public static int $ManualDrawCard = 10009;
    public static int $ManualOpponentDrawCard = 10010;
    public static int $ManualAddCard = 10011;
    public static int $ManualAddResource = 10012;
    public static int $ManualOpponentAddResource = 10013;
    public static int $ManualOpponentSubResource = 10014;
    public static int $ManualSubResource = 10015;
    public static int $QuickRematch = 100000;
    public static int $MainMenu = 100001;
    public static int $Concede = 100002;
    public static int $ReportBug = 100003;
    public static int $Rematch = 100004;
    public static int $CurrentPlayerInactive = 100005;
    public static int $CurrentPlayerActive = 100006;
    public static int $ClaimVictory = 100007;
    public static int $ThumpUp = 100008;
    public static int $ThumpDown = 100009;
    public static int $GrantBadge = 100010;
}

// If true, allows for the case to be doable by any player when they don't
// have the priority.
function IsModeAsync(int $mode): bool
{
  switch ($mode) {
    case Mode::$ChainSetting:
    case Mode::$ToggleEquipment:
    case Mode::$TogglePermanent:
    case Mode::$ToggleOpponentPermanent:
    case Mode::$Undo:
    case Mode::$RevertToPriorTurn:
    case Mode::$QuickRematch:
    case Mode::$MainMenu:
    case Mode::$Concede:
    case Mode::$ReportBug:
    case Mode::$Rematch:
    case Mode::$ClaimVictory:
    case Mode::$ThumpUp:
    case Mode::$ThumpDown:
    case Mode::$GrantBadge:
      return true;
    default:
      return false;
  }
}

function IsModeAllowedForSpectators(int $mode): bool
{
  switch ($mode) {
    case Mode::$MainMenu:
      return true;
    default:
      return false;
  }
}
