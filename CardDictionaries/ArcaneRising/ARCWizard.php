<?php

function ARCWizardPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = "")
{
  global $currentPlayer, $CS_NextWizardNAAInstant, $CS_ArcaneDamageTaken;
  $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
  switch ($cardID) {
    case "ARC113":
    case "ARC114":
      AddDecisionQueue("DECKCARDS", $currentPlayer, "0");
      AddDecisionQueue("SETDQVAR", $currentPlayer, "0");
      AddDecisionQueue("ALLCARDTYPEORPASS", $currentPlayer, "A", 1);
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Do you want to banish <0> with Kano?");
      AddDecisionQueue("YESNO", $currentPlayer, "whether to banish a card with Kano", 1);
      AddDecisionQueue("NOPASS", $currentPlayer, "-", 1);
      AddDecisionQueue("PARAMDELIMTOARRAY", $currentPlayer, "0", 1);
      AddDecisionQueue("MULTIREMOVEDECK", $currentPlayer, "0", 1);
      AddDecisionQueue("MULTIBANISH", $currentPlayer, "DECK,INST", 1);
      AddDecisionQueue("PASSPARAMETER", $currentPlayer, "{0}");
      AddDecisionQueue("NONECARDTYPEORPASS", $currentPlayer, "A");
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Kano shows the top of your deck is <0>");
      AddDecisionQueue("OK", $currentPlayer, "whether to banish a card with Kano", 1);
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "-");
      return "";
    case "ARC115":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "ARC116":
      SetClassState($currentPlayer, $CS_NextWizardNAAInstant, 1);
      return "";
    case "ARC117":
      GainResources($currentPlayer, 3);
      return "";
    case "ARC118":
      $damage = GetClassState($otherPlayer, $CS_ArcaneDamageTaken);
      DealArcane($damage, 0, "PLAYCARD", $cardID, resolvedTarget: $target);
      return "";
    case "ARC119":
      DealArcane(ArcaneDamage($cardID), 1, "PLAYCARD", $cardID, resolvedTarget: $target);
      AddDecisionQueue("LESSTHANPASS", $currentPlayer, 1);
      AddDecisionQueue("SETDQVAR", $currentPlayer, "0", 1);
      AddDecisionQueue("DECKCARDS", $currentPlayer, "0", 1);
      AddDecisionQueue("SETDQVAR", $currentPlayer, "1", 1);
      AddDecisionQueue("ALLCARDTYPEORPASS", $currentPlayer, "A", 1);
      AddDecisionQueue("ALLCARDCLASSORPASS", $currentPlayer, "WIZARD", 1);
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose if you want to banish <1> with Sonic Boom", 1);
      AddDecisionQueue("YESNO", $currentPlayer, "if_you_want_to_banish_the_card", 1);
      AddDecisionQueue("NOPASS", $currentPlayer, "-", 1);
      AddDecisionQueue("PARAMDELIMTOARRAY", $currentPlayer, "0", 1);
      AddDecisionQueue("MULTIREMOVEDECK", $currentPlayer, "0", 1);
      AddDecisionQueue("MULTIBANISH", $currentPlayer, "DECK,ARC119-{0}", 1);
      AddDecisionQueue("ELSE", $currentPlayer, "-");
      AddDecisionQueue("PASSPARAMETER", $currentPlayer, "{1}", 1);
      AddDecisionQueue("NULLPASS", $currentPlayer, "-", 1);
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Sonic Boom shows the top of your deck is <1>", 1);
      AddDecisionQueue("OK", $currentPlayer, "whether to banish a card with Sonic Boom", 1);
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "-");
      return "";
    case "ARC120":
      $arcaneBonus = ConsumeArcaneBonus($currentPlayer);
      $damage = ArcaneDamage($cardID) + $arcaneBonus;
      DealArcane($damage, 1, "PLAYCARD", $cardID, resolvedTarget: $target);
      DealArcane($damage, 1, "PLAYCARD", $cardID, resolvedTarget: $target);
      return "";
    case "ARC121":
      DealArcane(ArcaneDamage($cardID), 1, "PLAYCARD", $cardID, resolvedTarget: $target);
      AddDecisionQueue("SETDQVAR", $currentPlayer, "0");
      AddDecisionQueue("LESSTHANPASS", $currentPlayer, 1);
      AddDecisionQueue("YESNO", $currentPlayer, "if_you_want_to_tutor_a_card", 1);
      AddDecisionQueue("NOPASS", $currentPlayer, "-", 1);
      AddDecisionQueue("PASSPARAMETER", $currentPlayer, "{0}", 1);
      AddDecisionQueue("FINDINDICES", $currentPlayer, $cardID, 1);
      AddDecisionQueue("MAYCHOOSEDECK", $currentPlayer, "<-", 1);
      AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-", 1);
      AddDecisionQueue("MULTIADDTOPDECK", $currentPlayer, "-", 1);
      AddDecisionQueue("REVEALCARDS", $currentPlayer, "-", 1);
      return "";
    case "ARC122":
      AddDecisionQueue("PASSPARAMETER", $currentPlayer, $additionalCosts, 1);
      AddDecisionQueue("MODAL", $currentPlayer, "TOMEOFAETHERWIND", 1);
      return "";
    case "ARC123":
    case "ARC124":
    case "ARC125":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "ARC126":
    case "ARC127":
    case "ARC128":
      DealArcane(ArcaneDamage($cardID), 1, "PLAYCARD", $cardID, resolvedTarget: $target);
      AddDecisionQueue("OPTX", $currentPlayer, "<-", 1);
      return "";
    case "ARC129":
    case "ARC130":
    case "ARC131":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      SetClassState($currentPlayer, $CS_NextWizardNAAInstant, 1);
      return "";
    case "ARC132":
    case "ARC133":
    case "ARC134":
      DealArcane(ArcaneDamage($cardID), 1, "PLAYCARD", $cardID, resolvedTarget: $target);
      AddDecisionQueue("BUFFARCANE", $currentPlayer, $cardID, 1);
      return "";
    case "ARC135":
    case "ARC136":
    case "ARC137":
      if ($cardID == "ARC135") $count = 5;
      else if ($cardID == "ARC136") $count = 4;
      else $count = 3;
      AddDecisionQueue("FINDINDICES", $currentPlayer, "DECKTOPXREMOVE," . $count);
      AddDecisionQueue("SETDQVAR", $currentPlayer, "0", 1);
      AddDecisionQueue("CHOOSECARD", $currentPlayer, "<-", 1);
      AddDecisionQueue("MULTIADDTOPDECK", $currentPlayer, "DECK");
      AddDecisionQueue("OP", $currentPlayer, "REMOVECARD", 1);
      AddDecisionQueue("CHOOSEBOTTOM", $currentPlayer, "<-", 1);
      return "";
    case "ARC138":
    case "ARC139":
    case "ARC140":
      DealArcane(ArcaneDamage($cardID), 1, "PLAYCARD", $cardID, resolvedTarget: $target);
      AddDecisionQueue("LESSTHANPASS", $currentPlayer, 1);
      AddDecisionQueue("SETDQVAR", $currentPlayer, "1", 1);
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYHAND:type=A;class=WIZARD;maxCost={1}", 1);
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("MZBANISH", $currentPlayer, "HAND,INST," . $currentPlayer, 1);
      AddDecisionQueue("MZREMOVE", $currentPlayer, "-", 1);
      return "";
    case "ARC141":
    case "ARC142":
    case "ARC143":
    case "ARC144":
    case "ARC145":
    case "ARC146":
    case "ARC147":
    case "ARC148":
    case "ARC149":
      DealArcane(ArcaneDamage($cardID), 0, "PLAYCARD", $cardID, resolvedTarget: $target);
      return "";
    default:
      return "";
  }

}

function ARCWizardHitEffect($cardID)
{
  return "";
}

//Parameters:
//Player = Player controlling the arcane effects
//target =
//0: My Hero + Their Hero
//1: Their Hero only
//2: Any Target
//3: Their Hero + Their Allies
//4: My Hero only (For afflictions)
function DealArcane($damage, $target = 0, $type = "PLAYCARD", $source = "NA", $fromQueue = false, $player = 0, $mayAbility = false, $limitDuplicates = false, $skipHitEffect = false, $resolvedTarget = "", $nbArcaneInstance = 1, $isPassable = 0)
{
  global $currentPlayer, $CS_ArcaneTargetsSelected;
  if ($player == 0) $player = $currentPlayer;
  if ($damage > 0) {
    $damage += CurrentEffectArcaneModifier($source, $player) * $nbArcaneInstance;
    if ($type != "PLAYCARD") WriteLog(CardLink($source, $source) . " is dealing " . $damage . " arcane damage.");
    if ($fromQueue) {
      if (!$limitDuplicates) {
        PrependDecisionQueue("PASSPARAMETER", $player, "{0}");
        PrependDecisionQueue("SETCLASSSTATE", $player, $CS_ArcaneTargetsSelected); //If already selected for arcane multiselect (e.g. Singe/Azvolai)
        PrependDecisionQueue("PASSPARAMETER", $player, "-");
      }
      if (!$skipHitEffect) PrependDecisionQueue("ARCANEHITEFFECT", $player, $source, 1);
      PrependDecisionQueue("DEALARCANE", $player, $damage . "-" . $source . "-" . $type, 1);
      if ($resolvedTarget != "") {
        PrependDecisionQueue("PASSPARAMETER", $currentPlayer, $resolvedTarget);
      } else {
        PrependDecisionQueue("SETDQVAR", $currentPlayer, "0", 1);
        if ($mayAbility) PrependDecisionQueue("MAYCHOOSEMULTIZONE", $player, "<-", 1);
        else PrependDecisionQueue("CHOOSEMULTIZONE", $player, "<-", 1);
        PrependDecisionQueue("SETDQCONTEXT", $player, "Choose a target for <0>");
        PrependDecisionQueue("FINDINDICES", $player, "ARCANETARGET," . $target);
        PrependDecisionQueue("SETDQVAR", $currentPlayer, "0");
        PrependDecisionQueue("PASSPARAMETER", $currentPlayer, $source);
      }
    } else {
      if ($resolvedTarget != "") {
        AddDecisionQueue("PASSPARAMETER", $currentPlayer, $resolvedTarget, ($isPassable ? 1 : 0));
      } else {
        AddDecisionQueue("PASSPARAMETER", $currentPlayer, $source, ($isPassable ? 1 : 0));
        AddDecisionQueue("SETDQVAR", $currentPlayer, "0", ($isPassable ? 1 : 0));
        AddDecisionQueue("FINDINDICES", $player, "ARCANETARGET," . $target, ($isPassable ? 1 : 0));
        AddDecisionQueue("SETDQCONTEXT", $player, "Choose a target for <0>", ($isPassable ? 1 : 0));
        if ($mayAbility) AddDecisionQueue("MAYCHOOSEMULTIZONE", $player, "<-", 1);
        else AddDecisionQueue("CHOOSEMULTIZONE", $player, "<-", 1);
        AddDecisionQueue("SETDQVAR", $currentPlayer, "0", 1);
      }
      AddDecisionQueue("DEALARCANE", $player, $damage . "-" . $source . "-" . $type, 1);
      if (!$skipHitEffect) AddDecisionQueue("ARCANEHITEFFECT", $player, $source, 1);
      if (!$limitDuplicates) {
        AddDecisionQueue("PASSPARAMETER", $player, "-");
        AddDecisionQueue("SETCLASSSTATE", $player, $CS_ArcaneTargetsSelected);
        AddDecisionQueue("PASSPARAMETER", $player, "{0}");
      }
    }
  }
}


//target type return values
//-1: no target
//0: My Hero + Their Hero
//1: Their Hero only
//2: Any Target
//3: Their Hero + Their Allies
//4: My Hero only (For afflictions)
function PlayRequiresTarget($cardID)
{
  switch ($cardID) {
    case "ARC118":
      return 0;//Blazing Aether
    case "ARC119":
      return 1;//Sonic Boom
    case "ARC120":
      return 1;//Forked Lightning
    case "ARC121":
      return 1;//Lesson in Lava
    case "ARC126":
    case "ARC127":
    case "ARC128":
      return 1;//Aether Spindle
    case "ARC132":
    case "ARC133":
    case "ARC134":
      return 1;//Aether Flare
    case "ARC138":
    case "ARC139":
    case "ARC140":
      return 1;//Reverberate
    case "ARC141":
    case "ARC142":
    case "ARC143":
      return 0;//Scalding Rain
    case "ARC144":
    case "ARC145":
    case "ARC146":
      return 0;//Zap
    case "ARC147":
    case "ARC148":
    case "ARC149":
      return 0;//Voltic Bolt
    case "CRU162":
      return 1;//Chain Lightning
    case "CRU168":
    case "CRU169":
    case "CRU170":
      return 0;//Foreboding Bolt
    case "CRU171":
    case "CRU172":
    case "CRU173":
      return 0;//Rousing Aether
    case "CRU174":
    case "CRU175":
    case "CRU176":
      return 0;//Snapback
    case "EVR123":
      return 1;//Aether Wildfire
    case "EVR124":
      return 1;//Scour
    case "EVR125":
    case "EVR126":
    case "EVR127":
      return 0;//Emeritus Scolding
    case "EVR134":
    case "EVR135":
    case "EVR136":
      return 0;//Timekeeper's Whim
    case "UPR104":
      return 2;//Encase
    case "UPR105":
      return 0;//Freezing Point
    case "UPR109":
      return 0;//Ice Eternal
    case "UPR110":
    case "UPR111":
    case "UPR112":
      return 2;//Succumb to Winter
    case "UPR113":
    case "UPR114":
    case "UPR115":
      return 2;//Aether Icevein
    case "UPR119":
    case "UPR120":
    case "UPR121":
      return 2;//Icebind
    case "UPR122":
    case "UPR123":
    case "UPR124":
      return 2;//Polar Cap
    case "UPR127":
    case "UPR128":
    case "UPR129":
      return 2;//Aether Hail
    case "UPR130":
    case "UPR131":
    case "UPR132":
      return 2;//Frosting
    case "UPR133":
    case "UPR134":
    case "UPR135":
      return 2;//Ice Bolt
    case "UPR165":
      return 0;//Waning Moon
    case "UPR170":
    case "UPR171":
    case "UPR172":
      return 2;//Dampen
    case "UPR173":
    case "UPR174":
    case "UPR175":
      return 2;//Aether Dart
    case "UPR179":
    case "UPR180":
    case "UPR181":
      return 1;//Singe
    case "DYN194":
      return 0;//Mind Warp
    case "DYN195":
      return 0;//Swell Tidings
    case "DYN197":
    case "DYN198":
    case "DYN199":
      return 0;//Aether Quickening
    case "DYN203":
    case "DYN204":
    case "DYN205":
      return 0;//Prognosticate
    case "DYN206":
    case "DYN207":
    case "DYN208":
      return 0;//Sap
    case "ROS166":
      return 2;//Destructive Aethertide
    case "ROS167"://eternal inferno
      return 2;
    case "ROS176":
    case "ROS177":
    case "ROS178":
      return 0;//Pop the Bubble
    case "ROS186":
    case "ROS187":
    case "ROS188":
      return 2;//Arcane Twining
    case "ROS189":
    case "ROS190":
    case "ROS191":
      return 0;//Etchings of Arcana
    case "ROS195": 
    case "ROS196": 
    case "ROS197":
      return 0; //Open the Flood Gates
    case "ROS198":
    case "ROS199":
    case "ROS200":
      return 0;//Overflow the Aetherwell
    case "ROS201":
    case "ROS202":
    case "ROS203":
      return 0;//Perennial Aetherbloom
    case "ROS207":
    case "ROS208":
    case "ROS209":
      return 0;//Trailblazing Aether
    case "HVY252":
      return 1;
    default:
      return -1;
  }
}

//Parameters:
//Player = Player controlling the arcane effects
//target =
// 0: My Hero + Their Hero
// 1: Their Hero only
// 2: Any Target
// 3: Their Hero + Their Allies
// 4: My Hero only (For afflictions)
// 5: Their Allies only
function GetArcaneTargetIndices($player, $target): string
{
  global $CS_ArcaneTargetsSelected;
  $otherPlayer = ($player == 1 ? 2 : 1);
  if ($target == 4) return "MYCHAR-0";
  if ($target != 4 && $target != 5) $rv = "THEIRCHAR-0";
  else $rv = "";
  if (($target == 0 && !ShouldAutotargetOpponent($player)) || $target == 2) $rv .= ",MYCHAR-0";
  if ($target == 2) {
    $theirAllies = &GetAllies($otherPlayer);
    for ($i = 0; $i < count($theirAllies); $i += AllyPieces()) $rv .= ",THEIRALLY-" . $i;
    $myAllies = &GetAllies($player);
    for ($i = 0; $i < count($myAllies); $i += AllyPieces()) $rv .= ",MYALLY-" . $i;
  } else if ($target == 3 || $target == 5) {
    $theirAllies = &GetAllies($otherPlayer);
    for ($i = 0; $i < count($theirAllies); $i += AllyPieces()) {
      if ($rv != "") $rv .= ",";
      $rv .= "THEIRALLY-" . $i;
    }
  }
  $targets = explode(",", $rv);
  $targetsSelected = GetClassState($player, $CS_ArcaneTargetsSelected);
  for ($i = count($targets) - 1; $i >= 0; --$i) if (DelimStringContains($targetsSelected, $targets[$i])) unset($targets[$i]);
  return implode(",", $targets);
}

function CurrentEffectArcaneModifier($source, $player): int|string
{
  global $currentTurnEffects;
  $modifier = 0;
  for ($i = count($currentTurnEffects) - CurrentTurnPieces(); $i >= 0; $i -= CurrentTurnPieces()) {
    $remove = false;
    $effectArr = explode(",", $currentTurnEffects[$i]);
    switch ($effectArr[0]) {
      case "EVR123":
        $cardType = CardType($source);
        if ($cardType == "A" || $cardType == "AA") $modifier += $effectArr[1];
        break;
      case "DYN192":
        if (ActionsThatDoArcaneDamage($source) || ActionsThatDoXArcaneDamage($source)) {
          if ($currentTurnEffects[$i + 1] != $player) break;
          $modifier += $effectArr[1];
          $remove = true;
        }
        break;
      case "ROS000":
      case "ROS015-AMP":
      case "ROS168"://sigil of aether
      case "ROS204-AMP":
      case "ROS078":
      case "ROS186":
      case "ROS187":
      case "ROS188":
      case "ROS204":
      case "ROS205":
      case "ROS206":
      case "MST234":
      case "ROS165":
        if ($currentTurnEffects[$i + 1] != $player) break;
        $modifier += 1;
        $remove = true;
        break;
      case "ROS021":
        if ($currentTurnEffects[$i + 1] != $player) break;
        $modifier += $effectArr[1];
        $remove = true;
        break;
      case "ROS033":
        if ($currentTurnEffects[$i + 1] != $player) break;
        $modifier += 3;
        $remove = true;
        break;
      case "ROS163-AMP":
        if ($currentTurnEffects[$i + 1] != $player) break;
        $modifier += 1;
        $remove = true;
        break;
      case "ROS186":
      case "ROS187":
      case "ROS188":
      case "ROS192":
      case "ROS193":
      case "ROS194":
        if ($currentTurnEffects[$i + 1] != $player) break;
        $modifier += $effectArr[1];
        $remove = true;
        break;
      default:
        break;
    }
    if ($remove) RemoveCurrentTurnEffect($i);
  }
  return $modifier;
}

function ArcaneDamage($cardID): int
{
  //Blaze - Replacement effects aren't considered when evaluating how much an effect does so Emeritus Scolding (blu) would require 2 counters.
  global $mainPlayer, $currentPlayer, $CS_ArcaneDamageTaken, $resourcesPaid;
  $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
  return match ($cardID) {
    "ARC147", "EVR134", "UPR105", "UPR133", "UPR110", "UPR113", "DYN195" => 5,
    "ARC126", "ARC141", "ARC148", "CRU171", "EVR125", "EVR123", "EVR135", "UPR170", "UPR134", "UPR127", "UPR122",
    "UPR111", "UPR114", "DYN197", "ROS167", "ROS204" => 4,
    "ARC119", "ARC121", "ARC127", "ARC132", "ARC138", "ARC142", "ARC144", "ARC149", "EVR126", "EVR136", "DYN198",
    "DYN203", "DYN206", "CRU162", "CRU168", "CRU172", "CRU174", "UPR173", "UPR171", "UPR135", "UPR130", "UPR128",
    "UPR123", "UPR112", "UPR115", "UPR104", "UPR119", "ROS176", "ROS186", "ROS189", "ROS195", "ROS198", "ROS201", "ROS207", "ROS173",
    "ROS205" => 3,
    "ARC120", "CRU169", "CRU173", "CRU175", "EVR127", "UPR174", "UPR172", "UPR131", "UPR129", "UPR124", "UPR120",
    "DYN194", "DYN199", "DYN204", "DYN207", "ROS177", "ROS187", "ROS190", "ROS196", "ROS199", "ROS202", "ROS208", "ROS174", "ARC128",
    "ARC133", "ARC139", "ARC143", "ARC145", "ROS206" => 2,
    "ARC134", "ARC140", "ARC146", "CRU170", "CRU176", "UPR175", "UPR179", "UPR180", "UPR181", "UPR132", "UPR121",
    "DYN205", "DYN208", "HVY252", "ROS166", "ROS178", "ROS188", "ROS191", "ROS197", "ROS200", "ROS203", "ROS209", "ROS175" => 1,
    "EVR124" => 0,
    default => -1,
  };
}

function ActionsThatDoXArcaneDamage($cardID)
{
  switch ($cardID) {
    case "ARC118":
    case "EVR124":
    case "UPR109":
      return true;
    default:
      return false;
  }
}

function ActionsThatDoArcaneDamage($cardID)
{
  switch ($cardID) {
    case "ARC119":
    case "ARC120":
    case "ARC121":
    case "ARC126":
    case "ARC127":
    case "ARC128":
    case "ARC132":
    case "ARC133":
    case "ARC134":
    case "ARC138":
    case "ARC139":
    case "ARC140":
    case "ARC141":
    case "ARC142":
    case "ARC143":
    case "ARC144":
    case "ARC145":
    case "ARC146":
    case "ARC147":
    case "ARC148":
    case "ARC149":
      return true;
    case "CRU162":
    case "CRU168":
    case "CRU169":
    case "CRU170":
    case "CRU171":
    case "CRU172":
    case "CRU173":
    case "CRU174":
    case "CRU175":
    case "CRU176":
      return true;
    case "EVR123":
    case "EVR125":
    case "EVR126":
    case "EVR127":
    case "EVR134":
    case "EVR135":
    case "EVR136":
      return true;
    case "UPR104":
    case "UPR105":
    case "UPR110":
    case "UPR111":
    case "UPR112":
    case "UPR113":
    case "UPR114":
    case "UPR115":
    case "UPR119":
    case "UPR120":
    case "UPR121":
    case "UPR122":
    case "UPR123":
    case "UPR124":
    case "UPR127":
    case "UPR128":
    case "UPR129":
    case "UPR130":
    case "UPR131":
    case "UPR132":
    case "UPR133":
    case "UPR134":
    case "UPR135":
    case "UPR170":
    case "UPR171":
    case "UPR172":
    case "UPR173":
    case "UPR174":
    case "UPR175":
    case "UPR179":
    case "UPR180":
    case "UPR181":
      return true;
    case "DYN194":
    case "DYN195":
    case "DYN197":
    case "DYN198":
    case "DYN199":
    case "DYN203":
    case "DYN204":
    case "DYN205":
    case "DYN206":
    case "DYN207":
    case "DYN208":
      return true;
    case "HVY252":
      return true;
    case "ROS166":
    case "ROS167":
    case "ROS173":
    case "ROS174":
    case "ROS175":
    case "ROS176":
    case "ROS177":
    case "ROS178":
    case "ROS186":
    case "ROS187":
    case "ROS188":
    case "ROS189":
    case "ROS190":
    case "ROS191":
    case "ROS195":
    case "ROS196":
    case "ROS197":
    case "ROS198":
    case "ROS199":
    case "ROS200":
    case "ROS201":
    case "ROS202":
    case "ROS203":
    case "ROS207":
    case "ROS208":
    case "ROS209":
    case "ROS204":
    case "ROS205":
    case "ROS206":
      return true;
    default:
      return false;
  }
}

function ArcaneBarrierChoices($playerID, $max)
{
  global $currentTurnEffects;
  $barrierArray = [];
  for ($i = 0; $i < 4; ++$i) $barrierArray[$i] = 0;
  $character = GetPlayerCharacter($playerID);
  $total = 0;
  for ($i = 0; $i < count($character); $i += CharacterPieces()) {
    if ($character[$i + 1] == 0 || $character[$i + 12] == "DOWN") continue;
    switch ($character[$i]) {
      case "ARC005":
        ++$barrierArray[1];
        $total += 1;
        break;
      case "ARC041":
        ++$barrierArray[1];
        $total += 1;
        break;
      case "ARC042":
        ++$barrierArray[1];
        $total += 1;
        break;
      case "ARC079":
        ++$barrierArray[1];
        $total += 1;
        break;
      case "ARC116":
        ++$barrierArray[2];
        $total += 2;
        break;
      case "ARC117":
        ++$barrierArray[1];
        $total += 1;
        break;
      case "ARC150":
        if (PlayerHasLessHealth($playerID)) {
          ++$barrierArray[3];
          $total += 3;
        }
        break;
      case "ARC155":
      case "ARC156":
      case "ARC157":
      case "ARC158":
        ++$barrierArray[1];
        $total += 1;
        break;
      case "CRU006":
        ++$barrierArray[2];
        $total += 2;
        break;
      case "CRU102":
        ++$barrierArray[2];
        $total += 2;
        break;
      case "CRU161":
        ++$barrierArray[1];
        $total += 1;
        break;
      case "ELE144":
        ++$barrierArray[1];
        $total += 1;
        break;
      case "EVR103":
        ++$barrierArray[1];
        $total += 1;
        break;
      case "EVR137":
        ++$barrierArray[1];
        $total += 1;
        break;
      case "EVR155":
        ++$barrierArray[1];
        $total += 1;
        break;
      case "UPR152":
        ++$barrierArray[1];
        $total += 1;
        break;
      case "UPR159":
        ++$barrierArray[1];
        $total += 1;
        break;
      case "UPR166":
        ++$barrierArray[1];
        $total += 1;
        break;
      case "UPR167":
        ++$barrierArray[1];
        $total += 1;
        break;
      case "OUT094":
        ++$barrierArray[1];
        $total += 1;
        break;
      case "DTD106":
        ++$barrierArray[1];
        $total += 1;
        break;
      case "DTD136":
        ++$barrierArray[1];
        $total += 1;
        break;
      case "DTD211":
        ++$barrierArray[2];
        $total += 2;
        break;
      case "MST228":
      case "MST628":
        ++$barrierArray[1];
        $total += 1;
        break;
      case "MST229":
      case "MST629":
        ++$barrierArray[1];
        $total += 1;
        break;
      case "MST230":
      case "MST630":
        ++$barrierArray[1];
        $total += 1;
        break;
      case "MST231":
      case "MST631":
        ++$barrierArray[1];
        $total += 1;
        break;
      case "AAZ005":
        ++$barrierArray[1];
        $total += 1;
        break;
      case "ROS071":
        ++$barrierArray[1];
        $total += 1;
        break;
      case "ROS239":
        ++$barrierArray[1];
        $total += 1;
        break;
      case "ROS240":
        ++$barrierArray[1];
        $total += 1;
        break;
      case "ROS241":
        ++$barrierArray[1];
        $total += 1;
        break;
      case "ROS242":
        ++$barrierArray[1];
        $total += 1;
        break;
      case "ROS246":
        ++$barrierArray[1];
        $total += 1;
        break;
      case "ROS249":
      case "ROS250":
        ++$barrierArray[1];
        $total += 1;
        break;
      default:
        break;
    }
  }
  $items = GetItems($playerID);
  for ($i = 0; $i < count($items); $i += ItemPieces()) {
    switch ($items[$i]) {
      case "ARC163":
        ++$barrierArray[1];
        $total += 1;
        break;
      default:
        break;
    }
  }
  $allies = GetAllies($playerID);
  for ($i = 0; $i < count($allies); $i += AllyPieces()) {
    switch ($allies[$i]) {
      case "UPR042":
        ++$barrierArray[1];
        $total += 1;
        break;
      default:
        break;
    }
  }
  for ($i = 0; $i < count($currentTurnEffects); $i += CurrentTurnPieces()) {
    switch ($currentTurnEffects[$i]) {
      case "ARC017":
        ++$barrierArray[2];
        $total += 2;
        break;
      default:
        break;
    }
  }
  $choiceArray = [];
  array_push($choiceArray, 0);
  if ($barrierArray[1] > 0) array_push($choiceArray, 1);
  if ($barrierArray[2] > 0 || ($barrierArray[1] > 1 && $max > 1 && $total >= 2)) array_push($choiceArray, 2);
  if ($barrierArray[3] > 0 || ($max > 2 && $total >= 3)) array_push($choiceArray, 3);
  for ($i = 4; $i <= $max; ++$i) {
    if ($i <= $total) array_push($choiceArray, $i);
  }
  return implode(",", $choiceArray);
}

function CheckSpellvoid($player, $damage)
{
  $spellvoidChoices = SearchSpellvoidIndices($player);
  if ($spellvoidChoices != "") {
    PrependDecisionQueue("SPELLVOIDCHOICES", $player, $damage, 1);
    PrependDecisionQueue("MAYCHOOSEMULTIZONE", $player, $spellvoidChoices);
    PrependDecisionQueue("SETDQCONTEXT", $player, "Choose if you want to use a Spellvoid equipment");
  }
}


function ArcaneHitEffect($player, $source, $target, $damage)
{
  switch ($source) {
    case "UPR104":
      if (MZIsPlayer($target) && $damage > 0) {
        AddDecisionQueue("SPECIFICCARD", MZPlayerID($player, $target), "ENCASEDAMAGE", 1);
      }
      break;
    case "UPR113":
    case "UPR114":
    case "UPR115":
      if (MZIsPlayer($target)) PayOrDiscard(MZPlayerID($player, $target), 2, true);
      break;
    case "UPR119":
    case "UPR120":
    case "UPR121":
      if (MZIsPlayer($target) && $damage > 0) {
        AddDecisionQueue("MULTIZONEINDICES", $player, "THEIRARS", 1);
        AddDecisionQueue("SETDQCONTEXT", $player, "Choose a card to freeze", 1);
        AddDecisionQueue("CHOOSEMULTIZONE", $player, "<-", 1);
        AddDecisionQueue("MZOP", $player, "FREEZE", 1);
      }
      break;
    case "UPR122":
    case "UPR123":
    case "UPR124":
      if (MZIsPlayer($target) && $damage > 0) {
        AddDecisionQueue("PLAYAURA", MZPlayerID($player, $target), "ELE111-1", 1);
      }
      break;
    case "ROS168":
      AddCurrentTurnEffect($source, $player);
      Writelog(CardLink($source, $cardID) . " is amping 1");
      break;
    default:
      break;
  }

  if ($damage > 0 && CardType($source) != "W" && SearchCurrentTurnEffects("UPR125", $player, true)) AddDecisionQueue("OP", MZPlayerID($player, $target), "DESTROYFROZENARSENAL");

  if (HasSurge($source) && $damage > ArcaneDamage($source)) {
    ProcessSurge($source, $player, $target);
  }
  AuraDamageEffects($source);
}

function ProcessSurge($cardID, $player, $target)
{
  global $mainPlayer;
  $targetPlayer = MZPlayerID($player, $target);
  switch ($cardID) {
    case "DYN194":
      $hand = &GetHand($targetPlayer);
      $numToDraw = count($hand) - 1;
      if ($numToDraw < 0) $numToDraw = 0;
      $deck = &GetDeck($targetPlayer);
      while (count($hand) > 0) array_push($deck, array_shift($hand));
      for ($i = 0; $i < $numToDraw; ++$i) array_push($hand, array_shift($deck));
      WriteLog("Mind Warp warps the target's mind.");
      AddDecisionQueue("SHUFFLEDECK", $targetPlayer, "-");
      break;
    case "DYN195":
      PlayAura("DYN244", $player);
      WriteLog(CardLink($cardID, $cardID) . " created a " . CardLink("DYN244", "DYN244") . " token");
      break;
    case "DYN197":
    case "DYN198":
    case "DYN199":
      if (CurrentEffectPreventsGoAgain() || $player != $mainPlayer) break;
      GainActionPoints();
      WriteLog(CardLink($cardID, $cardID) . " gained go again");
      break;
    case "DYN203":
    case "DYN204":
    case "DYN205":
      PlayerOpt($player, 1);
      break;
    case "DYN206":
    case "DYN207":
    case "DYN208":
      AddDecisionQueue("MULTIZONEINDICES", $player, "THEIRCHAR:type=E;hasEnergyCounters=true");
      AddDecisionQueue("SETDQCONTEXT", $player, "Remove an energy counter from a card");
      AddDecisionQueue("CHOOSEMULTIZONE", $player, "<-", 1);
      AddDecisionQueue("MZOP", $player, "GETCARDINDEX", 1);
      AddDecisionQueue("REMOVECOUNTER", $targetPlayer, $cardID, 1);
      break;
    case "ROS166":
      if (MZIsPlayer($target)) {
        MZChooseAndDestroy($player, "THEIRARS");
      }
      break;
      case "ROS167"://eternal inferno
        BanishCardForPlayer("ROS167", $player, "MYDISCARD", "TT", "ROS167");
        $discard = &GetDiscard($player);
        for ($i == 0; $i < DiscardPieces(); $i++){
          array_pop($discard);
        }
        $banish = GetBanish($player);
        break;
    case "ROS176":
    case "ROS177":
    case "ROS178":
      MZChooseAndDestroy($player, "THEIRAURAS");
      break;
    case "ROS189":
    case "ROS190":
    case "ROS191":
      WriteLog("Surge active, returning a sigil from graveyard to hand");
      MZMoveCard($player, "MYDISCARD:subtype=Aura;nameIncludes=Sigil", "MYHAND", may: true);
      break;
    case "ROS195": 
    case "ROS196": 
    case "ROS197":
      WriteLog("Surge active, drawing 2 cards");
      Draw($player);
      Draw($player);
      break;
    case "ROS198":
    case "ROS199":
    case "ROS200":
      WriteLog("Surge active, gaining 2 resources");
      GainResources($player, 2);
      break;
    case "ROS201":
    case "ROS202":
    case "ROS203": //perennial aetherbloom
      WriteLog("Surge active, returning to the bottom of the deck");
      AddBottomDeck($cardID, $player, "STACK"); //create a copy on the bottom
      $discard = &GetDiscard($player);
      for ($i == 0; $i < DiscardPieces(); $i++){
        array_pop($discard);
      }
      break;
    case "ROS173":
    case "ROS174":
    case "ROS175":
      WriteLog("Surge Active, gaining 1 life and returning sigils to the deck");
      GainHealth(1, $player);
      $auras = &GetAuras($player);
      for ($i = count($auras) - AuraPieces(); $i >= 0; $i -= AuraPieces()) {
        $auraName = CardName($auras[$i]);
        if (DelimStringContains($auraName, "Sigil", partial: true)) {
          AddBottomDeck($auras[$i], $player, "STACK");
          RemoveAura($player, $i, $auras[$i + 4]);
        }
      }
      AddDecisionQueue("SHUFFLEDECK", $player, "-");
      break;
    case "ROS207":
    case "ROS208":
    case "ROS209":
      if (CurrentEffectPreventsGoAgain() || $player != $mainPlayer) break;
      GainActionPoints();
      WriteLog(CardLink($cardID, $cardID) . " gained go again");
      break;
    default:
      break;
  }
}
