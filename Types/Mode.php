<?php

enum Mode: int
{
    case None = 0;
    case Deprecated_1 = 1;
    case PlayCardFromHand = 2;
    case PlayEquipmentAbility = 3;
    case AddToArsenal = 4;
    case PlayFromArsenal = 5;
    case PitchToDeck = 6;
    case InputNumber = 7;
    case ChooseTop = 8;
    case ChooseBottom = 9;
    case PlayItemAbility = 10;
    case ChooseCardFromDeck = 11;
    case HandToTop = 12;
    case HandToBottom = 13;
    case Banish = 14;
    case Deprecated_15 = 15;
    case Choose = 16;
    case ButtonInput = 17;
    case Deprecated_18 = 18;
    case MultiChoose = 19;
    case YesNo = 20;
    case PlayCombatChainAbility = 21;
    case PlayAuraAbility = 22;
    case ChooseCard = 23;
    case PlayAllyAbility = 24;
    case PlayLandmarkAbility = 25;
    case ChainSetting = 26;
    case PlayFromHandByIndex = 27;
    case Pass = 99;
    case BreakCombatChain = 100;
    case PassBlocksAndReactions = 101;
    case ToggleEquipment = 102;
    case TogglePermanent = 103;
    case ToggleOpponentPermanent = 104;
    case Undo = 10000;
    case CancelBlock = 10001;
    case ManualAddActionPoint = 10002;
    case RevertToPriorTurn = 10003;
    case ManualSubActionPoint = 10004;
    case ManualSubHealthPoint = 10005;
    case ManualAddHealthPoint = 10006;
    case ManualSubOpponentHealthPoint = 10007;
    case ManualAddOpponentHealthPoint = 10008;
    case ManualDrawCard = 10009;
    case ManualOpponentDrawCard = 10010;
    case ManualAddCard = 10011;
    case ManualAddResource = 10012;
    case ManualOpponentAddResource = 10013;
    case ManualOpponentSubResource = 10014;
    case ManualSubResource = 10015;
    case QuickRematch = 100000;
    case MainMenu = 100001;
    case Concede = 100002;
    case ReportBug = 100003;
    case Rematch = 100004;
    case CurrentPlayerInactive = 100005;
    case CurrentPlayerActive = 100006;
    case ClaimVictory = 100007;
    case ThumpUp = 100008;
    case ThumpDown = 100009;
    case GrantBadge = 100010;
}

// If true, allows for the case to be doable by any player when they don't
// have the priority.
function IsModeAsync(Mode $mode): bool
{
  switch ($mode) {
    case Mode::ChainSetting:
    case Mode::ToggleEquipment:
    case Mode::TogglePermanent:
    case Mode::ToggleOpponentPermanent:
    case Mode::Undo:
    case Mode::RevertToPriorTurn:
    case Mode::QuickRematch:
    case Mode::MainMenu:
    case Mode::Concede:
    case Mode::ReportBug:
    case Mode::Rematch:
    case Mode::ClaimVictory:
    case Mode::ThumpUp:
    case Mode::ThumpDown:
    case Mode::GrantBadge:
      return true;
    default:
      return false;
  }
}

function IsModeAllowedForSpectators(Mode $mode): bool
{
  switch ($mode) {
    case Mode::MainMenu:
      return true;
    default:
      return false;
  }
}
