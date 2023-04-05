<?php

use JetBrains\PhpStorm\Language;

require_once("CoreLibraries.php");

$isReactFE = false;

function BackgroundColor($darkMode)
{
  if ($darkMode) return "rgba(74, 74, 74, 0.95)";
  else return "rgba(235, 235, 235, 0.95)";
}

function PopupBorderColor($darkMode)
{
  if ($darkMode) return "#DDD";
  else return "#1a1a1a";
}

function TextCounterColor($darkMode)
{
  if ($darkMode) return "#1a1a1a";
  else return "#EDEDED";
}

//0 Card number = card ID (e.g. WTR000 = Heart of Fyendal)
//1 action = (ProcessInput2 mode)
//2 overlay = 0 is none, 1 is grayed out/disabled
//3 borderColor = Border Color
//4 Counters = number of counters
//5 actionDataOverride = The value to give to ProcessInput2
//6 lifeCounters = Number of life counters
//7 defCounters = Number of defense counters
//8 atkCounters = Number of attack counters
//9 controller = Player that controls it
//10 type = card type
//11 sType = card subtype
//12 restriction = something preventing the card from being played (or "" if nothing)
//13 isBroken = 1 if card is destroyed
//14 onChain = 1 if card is on combat chain (mostly for equipment)
//15 isFrozen = 1 if frozen
//16 shows gem = (0, 1, 2) (0 off, 1 active, 2 inactive)
function ClientRenderedCard($cardNumber, $action = 0, $overlay = 0, $borderColor = 0, $counters = 0, $actionDataOverride = "-", $lifeCounters = 0, $defCounters = 0, $atkCounters = 0, $controller = 0, $type = "", $sType = "", $restriction = "", $isBroken = 0, $onChain = 0, $isFrozen = 0, $gem = 0)
{
  $rv = $cardNumber . " " . $action . " " . $overlay . " " . $borderColor . " " . $counters . " " . $actionDataOverride . " " . $lifeCounters . " " . $defCounters . " " . $atkCounters . " ";
  $rv .= $controller . " " . $type . " " . $sType . " " . $restriction . " " . $isBroken . " " . $onChain . " " . $isFrozen . " " . $gem;
  return $rv;
}

function JSONRenderedCard(
  $cardNumber,
  $action = NULL,
  $overlay = NULL,
  $borderColor = NULL,
  $counters = NULL, // deprecated
  $actionDataOverride = NULL,
  $lifeCounters = NULL, // deprecated
  $defCounters = NULL, // deprecated
  $atkCounters = NULL, // deprecated
  $controller = NULL,
  $type = NULL,
  $sType = NULL,
  $restriction = NULL,
  $isBroken = NULL,
  $onChain = NULL,
  $isFrozen = NULL,
  $gem = NULL,
  $countersMap = new stdClass(), // new object for counters
  $label = NULL,
  $facing = NULL,
  $numUses = NULL
) {
  global $playerID;
  $isSpectator = (isset($playerID) && intval($playerID) == 3 ? true : false);

  $countersMap->counters = property_exists($countersMap, 'counters') ?
    $countersMap->counters : $counters;
  $countersMap->life = property_exists($countersMap, 'life') ?
    $countersMap->life : $lifeCounters;
  $countersMap->defence = property_exists($countersMap, 'defence') ?
    $countersMap->defence :  $defCounters;
  $countersMap->attack = property_exists($countersMap, 'attack') ?
    $atkCounters->attack :  $atkCounters;
  if ($countersMap->counters > 0) {
    $class = CardClass($cardNumber);
    $subtype = CardSubType($cardNumber);
    if ($class == "MECHANOLOGIST" && ($subtype == "Item" || CardType($cardNumber) == "W")) {
      $countersMap->steam = $countersMap->counters;
      $countersMap->counters = 0;
    } else if ($subtype == "Arrow") {
      $countersMap->aim = $countersMap->counters;
      $countersMap->counters = 0;
    } else if ($cardNumber == "WTR150" || $cardNumber == "UPR166") {
      $countersMap->energy = $countersMap->counters;
      $countersMap->counters = 0;
    } else if ($cardNumber == "DYN175") {
      $countersMap->doom = $countersMap->counters;
      $countersMap->counters = 0;
    }
  }

  $countersMap = (object) array_filter((array) $countersMap, function ($val) {
    return !is_null($val);
  });

  if ($isSpectator) $gem = NULL;

  $card = (object) [
    'cardNumber' => $cardNumber,
    'action' => $action,
    'overlay' => $overlay,
    'borderColor' => $borderColor,
    'counters' => $counters,
    'actionDataOverride' => $actionDataOverride,
    'lifeCounters' => $lifeCounters,
    'defCounters' => $defCounters,
    'atkCounters' => $atkCounters,
    'controller' => $controller,
    'type' => $type,
    'sType' => $sType,
    'restriction' => $restriction,
    'isBroken' => $isBroken,
    'onChain' => $onChain,
    'isFrozen' => $isFrozen,
    'countersMap' => $countersMap,
    'label' => $label,
    'facing' => $facing,
    'numUses' => $numUses,
  ];

  if ($gem != NULL) {
    $card->gem = $gem;
  }

  // To reduce space/size strip out all values that are null.
  // On the FE repopulate the null values with the defaults like the binary blob.
  $card = (object) array_filter((array) $card, function ($val) {
    return !is_null($val);
  });

  return $card;
}

//Rotate is deprecated
function Card($cardNumber, $folder, $maxHeight, $action = 0, $showHover = 0, $overlay = 0, $borderColor = 0, $counters = 0, $actionDataOverride = "", $id = "", $rotate = false, $lifeCounters = 0, $defCounters = 0, $atkCounters = 0, $from = "", $controller = 0)
{
  global $playerID, $darkMode;
  $LanguageJP = ((IsLanguageJP($playerID) && TranslationExist("JP", $cardNumber)) ? true : false);
  if ($darkMode == null) $darkMode = false;
  if ($folder == "crops") {
    $cardNumber .= "_cropped";
  }
  $fileExt = ".png";
  $folderPath = $folder;
  if ($cardNumber == "ENDSTEP" || $cardNumber == "ENDTURN" || $cardNumber == "RESUMETURN" || $cardNumber == "PHANTASM" || $cardNumber == "FINALIZECHAINLINK" || $cardNumber == "DEFENDSTEP" || $cardNumber == "AIM_cropped") {
    $folderPath = str_replace("CardImages", "Images", $folderPath);
    $folderPath = str_replace("concat", "Images", $folderPath);
    $showHover = 0;
    $borderColor = 0;
  } else if ($folder == "concat" && $LanguageJP) { // Japanese
    $folderPath = "concat/JP";
    $fileExt = ".webp";
  } else if ($folder == "WebpImages" && $LanguageJP) { // Japanese
    $folderPath = "WebpImages/JP";
    $fileExt = ".webp";
  } else if (mb_strpos($folder, "CardImages") !== false) {
    $folderPath = str_replace("CardImages", "WebpImages", $folder);
    $fileExt = ".webp";
  } else if ($folder == "concat" || $folder == "./concat" || $folder == "../concat") {
    if (DelimStringContains(CardSubType($cardNumber), "Landmark")) $rotate = true;
    $fileExt = ".webp";
  }
  $actionData = $actionDataOverride != "" ? $actionDataOverride : $cardNumber;
  //Enforce 375x523 aspect ratio as exported (.71)
  $margin = "margin:0px;";
  $border = "";
  if ($borderColor != -1) $margin = $borderColor > 0 ? "margin:0px;" : "margin:1px;";
  if ($borderColor != -1 && $from == "HASSUBCARD") $margin = "margin-bottom:22px; top: 16px;";
  if ($folder == "crops") $margin = "0px;";
  if ($from == "SUBCARD") {
    $rv = "<a style='" . $margin . " position:absolute; display:inline-block;" . ($action > 0 ? "cursor:pointer;" : "") . "'" . ($showHover > 0 ? " onmouseover='ShowCardDetail(event, this)' onmouseout='HideCardDetail()'" : "") . ($action > 0 ? " onclick='SubmitInput(\"" . $action . "\", \"&cardID=" . $actionData . "\");'" : "") . ">";
  } else {
    $rv = "<a style='" . $margin . " position:relative; display:inline-block;" . ($action > 0 ? "cursor:pointer;" : "") . "'" . ($showHover > 0 ? " onmouseover='ShowCardDetail(event, this)' onmouseout='HideCardDetail()'" : "") . ($action > 0 ? " onclick='SubmitInput(\"" . $action . "\", \"&cardID=" . $actionData . "\");'" : "") . ">";
  }
  if ($borderColor > 0) {
    $border = "border-radius:10px; border:2.5px solid " . BorderColorMap($borderColor) . ";";
  } else if ($folder == "concat" || $folder == "./concat" || $folder == "../concat") {
    $border = "border-radius:8px; border:1.5px solid transparent;";
  } else {
    $border = "border: 1px solid transparent;";
  }

  if ($folder == "crops") {
    $height = $maxHeight;
    $width = ($height * 1.29);
  } else if ($folder == "concat" || $folder == "./concat" || $folder == "../concat") {
    $height = $maxHeight;
    $width = $maxHeight;
  } else if ($rotate == false) {
    $height = $maxHeight;
    $width = ($maxHeight * .71);
  } else {
    $height = ($maxHeight * .71);
    $width = $maxHeight;
  }

  if ($controller != 0 && IsPatron($controller) && CardHasAltArt($cardNumber)) $folderPath = "PatreonImages/" . $folderPath;

  $rv .= "<img " . ($id != "" ? "id='" . $id . "-img' " : "") . "style='" . $border . " height:" . $height . "; width:" . $width . "px; position:relative;' src='" . $folderPath . "/" . $cardNumber . $fileExt . "' />";
  $rv .= "<div " . ($id != "" ? "id='" . $id . "-ovr' " : "") . "style='visibility:" . ($overlay == 1 ? "visible" : "hidden") . "; width:100%; height:100%; top:0px; left:0px; border-radius:10px; position:absolute; background: rgba(0, 0, 0, 0.5); z-index: 1;'></div>";

  // Counters Style
  $dynamicScaling = (function_exists("IsDynamicScalingEnabled") ? IsDynamicScalingEnabled($playerID) : false);
  $counterHeight = $dynamicScaling ? intval($maxHeight / 3.3) : 28;
  $imgCounterHeight = $dynamicScaling ? intval($maxHeight / 2) : 44;
  //Attacker Label Style
  if ($counters == "Attacker" || $counters == "Arsenal") {
    $rv .= "<div style='margin: 0px; top: 80%; left: 50%;
    margin-right: -50%; border-radius: 7px; width: fit-content; text-align: center; line-height: 16px; height: 16px; padding: 5px; border: 3px solid " . PopupBorderColor($darkMode) . ";
    transform: translate(-50%, -50%); position:absolute; z-index: 10; background:" . BackgroundColor($darkMode) . ";
    font-size:20px; font-weight:800; color:" . PopupBorderColor($darkMode) . "; user-select: none;'>" . $counters . "</div>";
  }
  //Steam Counters style
  elseif ($counters != 0 && ($from == "ITEMS" || $from == "CHARACTER") && ClassContains($cardNumber, "MECHANOLOGIST")) {
    if ($lifeCounters == 0 && $defCounters == 0 && $atkCounters == 0) {
      $left = "0px";
    } else {
      $left = "-45%";
    }
    $rv .= "<div style=' position:absolute; margin: auto; top: 0; left:" . $left . "; right: 0; bottom: 0; width:" . $imgCounterHeight . "px; height:" . $imgCounterHeight . "px;
   display: flex; justify-content: center; z-index: 100; text-align: center; vertical-align: middle; line-height:" . $imgCounterHeight . "px;
   font-size:" . ($imgCounterHeight - 17) . "px; font-weight: 600;  color: #EEE; text-shadow: 2px 0 0 #000, 0 -2px 0 #000, 0 2px 0 #000, -2px 0 0 #000; user-select: none;'>" . $counters . "
   <img style='position:absolute; top: -2px; width:" . $imgCounterHeight . "px; height:" . $imgCounterHeight . "px; opacity: 0.9; z-index:-1; user-select: none;' src='./Images/SteamCounters.png'></div>";
  }

  //Aim Counters style
  elseif ((($counters != 0 && $from == "ARS") || $atkCounters != 0) && CardSubType($cardNumber) == "Arrow") {
    if ($lifeCounters == 0 && $defCounters == 0) {
      $left = "0px";
    } else {
      $left = "-45%";
    }
    $rv .= "<div style=' position:absolute; margin: auto; top: 0; left:" . $left . "; right: 0; bottom: 0; width:" . $imgCounterHeight . "px; height:" . $imgCounterHeight . "px;
   display: flex; justify-content: center; z-index: 5; text-align: center; vertical-align: middle; line-height:" . $imgCounterHeight . "px;
   font-size:" . ($imgCounterHeight - 20) . "px; font-weight: 600;  color: #EEE; text-shadow: 2px 0 0 #000, 0 -2px 0 #000, 0 2px 0 #000, -2px 0 0 #000; user-select: none;'>
   <img style='position:absolute; top: -2px; width:" . $imgCounterHeight . "px; height:" . $imgCounterHeight . "px; opacity: 0.9; z-index:-1; user-select: none;' src='./Images/aimCounters.png'></div>";
  }

  //Equipments, Hero and default counters style
  elseif ($counters != 0) {
    if ($lifeCounters == 0 && $defCounters == 0 && $atkCounters == 0) {
      $left = "50%";
    } else {
      $left = "30%";
    }
    $rv .= "<div style='margin: 0px;
    top: 52%; left:" . $left . ";
    margin-right: -50%;
    border-radius: 50%;
    width:" . $counterHeight . "px;
    height:" . $counterHeight . "px;
    padding: 6px;
    border: 3px solid #1a1a1a;
    text-align: center;
    transform: translate(-50%, -50%);
    position:absolute; z-index: 10;
    background: rgba(235, 235, 235, 0.9);
    line-height: 1.2;
    font-family: Helvetica; font-size:" . ($counterHeight - 2) . "px; font-weight:550; color: #EDEDED;
    text-shadow: 2px 0 0 #1a1a1a, 0 -2px 0 #1a1a1a, 0 2px 0 #1a1a1a, -2px 0 0 #1a1a1a;
    user-select: none;'>" . $counters . "</div>";
  }

  //-1 Defense & Endurance Counters style
  if ($defCounters != 0) {
    if ($lifeCounters == 0 && $counters == 0) {
      $left = "0px";
    } else {
      $left = "-45%";
    }
    $rv .= "<div style=' position:absolute; margin: auto; top: 0; left:" . $left . "; right: 0; bottom: 0; width:" . $imgCounterHeight . "px; height:" . $imgCounterHeight . "px;
    display: flex;justify-content: center; z-index: 5; text-align: center;vertical-align: middle; line-height:" . $imgCounterHeight . "px;
    font-size:" . ($imgCounterHeight - 17) . "px; font-weight: 600; color: #EEE; text-shadow: 2px 0 0 #000, 0 -2px 0 #000, 0 2px 0 #000, -2px 0 0 #000; user-select: none;'>" . $defCounters . "
    <img style='position:absolute; top: -2px; width:" . $imgCounterHeight . "px; height:" . $imgCounterHeight . "px; opacity: 0.9; z-index:-1; user-select: none;' src='./Images/Defense.png'></div>";
  }

  //Health Counters style
  if ($lifeCounters != 0) {
    if ($defCounters == 0 && $atkCounters == 0) {
      $left = "0px";
    } else {
      $left = "45%";
    }
    $rv .= "<div style=' position:absolute; margin: auto; top: 0; left:" . $left . "; right: 0; bottom: 0; width:" . $imgCounterHeight . "px; height:" . $imgCounterHeight . "px;
    display: flex; justify-content: center; z-index: 5; text-align: center; vertical-align: middle; line-height:" . $imgCounterHeight . "px;
    font-size:" . ($imgCounterHeight - 17) . "px; font-weight: 600;  color: #EEE; text-shadow: 2px 0 0 #000, 0 -2px 0 #000, 0 2px 0 #000, -2px 0 0 #000; user-select: none;'>" . $lifeCounters . "
    <img style='position:absolute; top: -2px; width:" . $imgCounterHeight . "px; height:" . $imgCounterHeight . "px; opacity: 0.9; z-index:-1;' src='./Images/Life.png'></div>";
  }

  //Attack Counters style
  if ($atkCounters != 0 && CardSubType($cardNumber) != "Arrow") {
    if ($lifeCounters == 0 && $counters == 0) {
      $left = "0px";
    } else {
      $left = "-45%";
    }
    $rv .= "<div style=' position:absolute; margin: auto; top: 0; left:" . $left . "; right: 0; bottom: 0; width:" . $imgCounterHeight . "px; height:" . $imgCounterHeight . "px;
    display: flex; justify-content: center; z-index: 5; text-align: center; vertical-align: middle; line-height:" . $imgCounterHeight . "px;
    font-size:" . ($imgCounterHeight - 17) . "px; font-weight: 600;  color: #EEE; text-shadow: 2px 0 0 #000, 0 -2px 0 #000, 0 2px 0 #000, -2px 0 0 #000; user-select: none;'>" . $atkCounters . "
    <img style='position:absolute; top: -2px; width:" . $imgCounterHeight . "px; height:" . $imgCounterHeight . "px; opacity: 0.9; z-index:-1;' src='./Images/Attack.png'></div>";
  }
  $rv .= "</a>";
  return $rv;
}

function BorderColorMap($code)
{
  switch ($code) {
    case 1:
      return "DeepSkyBlue";
    case 2:
      return "red";
    case 3:
      return "yellow";
    case 4:
      return "Gray";
    case 5:
      return "Tan";
    case 6:
      return "chartreuse";
    case 7:
      return "Orchid";
  }
}

function CreateButton($playerID, $caption, $mode, $input, $size = "", $image = "", $tooltip = "", $fullRefresh = false, $fullReload = false, $prompt = "", $useInput=false)
{
  global $gameName, $authKey;

  // JavaScript to execute on-click
  if ($fullReload)
    $onClick = "document.location.href = \"./ProcessInput2.php?gameName=$gameName&playerID=$playerID&authKey=$authKey&mode=$mode&buttonInput=$input\";";
  else
    $onClick = "SubmitInput(\"" . $mode . "\", \"&buttonInput=" . $input . "\", " . $fullRefresh . ");";

  // If a prompt is given, surround the code with a "confirm()" call
  if ($prompt != "")
    $onClick = "if (confirm(\"" . $prompt . "\")) { " . $onClick . " }";

  if ($image != "")
    $rv = "<img style='cursor:pointer;' src='" . $image . "' onclick='" . $onClick . "'>";
  else if($useInput)
    $rv = "<input type='button' value='$caption' title='$tooltip' " . ($size != "" ? "style='font-size:$size;' " : "") . " onclick='" . $onClick . "'></input>";
  else
    $rv = "<button class='button' title='$tooltip' " . ($size != "" ? "style='font-size:$size;' " : "") . " onclick='" . $onClick . "'>" . $caption . "</button>";

  return $rv;
}

function CreateButtonAPI($playerID, $caption, $mode, $input, $size = null, $image = null, $tooltip = null, $fullRefresh = false, $fullReload = false, $prompt = null)
{
  $button = new stdClass();
  $button->mode = $mode;
  $button->buttonInput = $input;
  $button->fullRefresh = $fullRefresh;
  $button->prompt = $prompt;
  $button->imgURL = $image;
  $button->tooltip = $tooltip;
  $button->caption = $caption;
  $button->sizeOverride = $size;
  $button->fullReload = $fullReload;
  return $button;
}


function ProcessInputLink($player, $mode, $input, $event = 'onmousedown', $fullRefresh = false, $prompt = "")
{
  global $gameName;

  $jsCode = "SubmitInput(\"" . $mode . "\", \"&buttonInput=" . $input . "\", " . $fullRefresh . ");";
  // If a prompt is given, surround the code with a "confirm()" call
  if ($prompt != "")
    $jsCode = "if (confirm(\"" . $prompt . "\")) { " . $jsCode . " }";

  return " " . $event . "='" . $jsCode . "'";
}

function CreateForm($playerID, $caption, $mode, $count)
{
  global $gameName;
  $rv = "<form>";
  $rv .= "<input type='button' onclick='chkSubmit(" . $mode . ", " . $count . ")' value='" . $caption . "'>";
  $rv .= "<input type='hidden' id='gameName' name='gameName' value='" . $gameName . "'>";
  $rv .= "<input type='hidden' id='playerID' name='playerID' value='" . $playerID . "'>";
  $rv .= "<input type='hidden' id='mode' name='mode' value='" . $mode . "'>";
  $rv .= "<input type='hidden' id='chkCount' name='chkCount' value='" . $count . "'>";
  return $rv;
}

function CreateTextForm($playerID, $caption, $mode)
{
  global $gameName;
  $rv = "<form>";
  $rv .= "<input type='button' onclick='textSubmit(" . $mode . ")' value='" . $caption . "'>";
  $rv .= "<input type='hidden' id='gameName' name='gameName' value='" . $gameName . "'>";
  $rv .= "<input type='hidden' id='playerID' name='playerID' value='" . $playerID . "'>";
  $rv .= "<input type='hidden' id='mode' name='mode' value='" . $mode . "'>";
  $rv .= "<input type='text' id='inputText' name='inputText' onkeypress='suppressEventPropagation(event)'>";
  return $rv;
}

//input = ?
//value = ?
//immediateSubmitMode = If set, add onchange event to submit immediately instead of form submit
//defaultChecked = Will be checked by default if true
//label = label to display
function CreateCheckbox($input, $value, $immediateSubmitMode = -1, $defaultChecked = false, $label = "&nbsp;", $fullRefresh = false)
{
  global $playerID;
  $submitLink = "";
  $check = "";
  if ($immediateSubmitMode != -1) $submitLink = ProcessInputLink($playerID, $immediateSubmitMode, $input, "onchange", $fullRefresh);
  if ($defaultChecked) $check = " checked='checked'";
  $rv = "<input type='checkbox' " . $submitLink . " id='chk" . $input . "' name='chk" . $input . "' value='" . $value . "' " . $check . ">";
  $rv .= "<label for='chk" . $input . "'>" . $label . "</label>";
  return $rv;
}

function CreateCheckboxAPI($input, $value, $immediateSubmitMode = -1, $defaultChecked = false, $label = "&nbsp;", $fullRefresh = false)
{
  $option = new stdClass();
  global $playerID;
  $submitLink = "";
  if ($immediateSubmitMode != -1) $submitLink = ProcessInputLink($playerID, $immediateSubmitMode, $input, "onchange", $fullRefresh);
  $option->submitLink = $submitLink;
  $option->input = $input;
  $option->value = $value;
  $option->check = $defaultChecked;
  $option->label = $label;
  return $option;
}


function CreateRadioButton($input, $value, $immediateSubmitMode, $currentInput, $label = "&nbsp;")
{
  global $playerID;
  $submitLink = "";
  $check = "";
  if ($immediateSubmitMode != -1) $submitLink = ProcessInputLink($playerID, $immediateSubmitMode, $input, "onchange", true);
  if ($currentInput == $input) $check = " checked='checked'";
  $rv = "<input type='radio' " . $submitLink . " id='radio" . $input . "' name='radio" . $input . "' value='" . $value . "' " . $check . ">";
  $rv .= "<label for='radio" . $input . "'>$label</label>";
  return $rv;
}

function CreatePopup($id, $fromArr, $canClose, $defaultState = 0, $title = "", $arrElements = 1, $customInput = "", $path = "./", $big = false, $overCombatChain = false, $additionalComments = "", $size = 0)
{
  global $darkMode, $cardSize, $playerID;
  $style = "";
  $overCC = 1000;
  $darkMode = IsDarkMode($playerID);
  $top = "54%";
  $left = "25%";
  $width = "50%";
  $height = "30%";
  if ($size == 2) {
    $top = "10%";
    $left = "25%";
    $width = "50%";
    $height = "80%";
    $overCC = 1001;
  }
  if ($big) {
    $top = "5%";
    $left = "5%";
    $width = "80%";
    $height = "90%";
    $overCC = 1001;
  }
  if ($overCombatChain) {
    $top = "220px";
    $left = "305px";
    $width = "auto";
    $height = "auto";
    $overCC = 100;
  }

  $rv = "<div id='" . $id . "' style='overflow-y: auto; background-color:" . BackgroundColor($darkMode) . "; border: 3px solid " . PopupBorderColor($darkMode) . "; border-radius: 7px; z-index:" . $overCC . "; position: absolute; top:" . $top . "; left:" . $left . "; width:" . $width . "; height:" . $height . ";"  . ($defaultState == 0 ? " display:none;" : "") . "'>";

  if ($title != "") $rv .= "<h" . ($big ? "1" : "3") . " style='margin-left: 10px; margin-top: 5px; margin-bottom: 10px; text-align: center; user-select: none;'>" . $title . "</h" . ($big ? "1" : "3") . ">";
  if ($canClose == 1) $rv .= "<div style='position:absolute; top:0px; right:45px;'><div title='Click to close' style='position: fixed; cursor:pointer; font-size:50px;' onclick='(function(){ document.getElementById(\"" . $id . "\").style.display = \"none\";})();'>&#10006;</div></div>";
  if ($additionalComments != "") $rv .= "<h" . ($big ? "3" : "4") . " style='margin-left: 10px; margin-top: 5px; margin-bottom: 10px; text-align: center;'>" . $additionalComments . "</h" . ($big ? "3" : "4") . ">";
  for ($i = 0; $i < count($fromArr); $i += $arrElements) {
    $rv .= Card($fromArr[$i], $path . "concat", $cardSize, 0, 1);
  }
  if (IsGameOver()) $style = "text-align: center;";
  else $style = "font-size: 18px; margin-left: 10px; line-height: 22px; align-items: center;";
  $rv .= "<div style='" . $style . "'>" . $customInput . "</div>";
  $rv .= "</div>";
  return $rv;
}

function CreatePopupAPI($id, $fromArr, $canClose, $defaultState = 0, $title = "", $arrElements = 1, $customInput = "", $path = "./", $big = false, $overCombatChain = false, $additionalComments = "", $size = 0, $cardsArray = [])
{
  $result = new stdClass();
  $result->size = $size;
  $result->big = $big;
  $result->overCombatChain = $overCombatChain;
  $result->id = $id;
  $result->title = $title;
  $result->canClose = $canClose;
  $result->additionalComments = $additionalComments;
  $cards = array();
  for ($i = 0; $i < count($fromArr); $i += $arrElements) {
    array_push($cards, JSONRenderedCard($fromArr[$i]));
  }
  if (count($cardsArray) > 0) {
    $cards = $cardsArray;
  }
  $result->cards = $cards;
  $result->customInput = $customInput;
  return $result;
}


function CardStatsUI($player)
{
  global $darkMode;
  $rv = "<div id='cardStats' style='background-color:" . BackgroundColor($darkMode) . "; z-index:100; position: absolute; top:120px; left: 50px; right: 250px; bottom:50px;'>";
  $rv .= CardStats($player);
  $rv .= "</div>";
  return $rv;
}

function CardStats($player)
{
  global $CardStats_TimesPlayed, $CardStats_TimesBlocked, $CardStats_TimesPitched;
  global $TurnStats_DamageThreatened, $TurnStats_DamageDealt, $TurnStats_CardsPlayedOffense, $TurnStats_CardsPlayedDefense, $TurnStats_CardsPitched, $TurnStats_CardsBlocked, $firstPlayer;
  global $TurnStats_ResourcesUsed, $TurnStats_CardsLeft, $TurnStats_DamageBlocked, $darkMode;
  if (AreStatsDisabled($player)) return "";
  $otherPlayer = ($player == 1 ? 2 : 1);
  $cardStats = &GetCardStats($player);
  $rv = "<div style='float:left; width:49%; height:85%;'>";
  $rv .= "<h2 style='text-align:center'>Card Play Stats</h2>";
  $rv .= "<table style='text-align:center; margin-left:10px; margin-top:10px; width:100%; border-spacing: 0; border-collapse: collapse; font-size: 1em; line-height: 24px;'><tr>";
  $rv .= "<td style='border-bottom: 1px solid black; border-top: 1px solid black;'>Card ID</td>";
  $rv .= "<td style='border-bottom: 1px solid black; border-top: 1px solid black;'>Times<br>Played</td> ";
  $rv .= "<td style='border-bottom: 1px solid black; border-top: 1px solid black;'>Times<br>Blocked</td>";
  $rv .= "<td style='border-bottom: 1px solid black; border-top: 1px solid black;'>Times<br>Pitched</td>";
  $rv .= "</tr>";
  $BackgroundColor = "";
  if ($darkMode) {
    $lighterColor = "rgba(94, 94, 94, 0.95)";
    $darkerColor = "rgba(74, 74, 74, 0.95)";;
  } else {
    $lighterColor = "rgba(215, 215, 215, 0.95)";
    $darkerColor = "rgba(235, 235, 235, 0.95)";
  }
  for ($i = 0; $i < count($cardStats); $i += CardStatPieces()) {
    $BackgroundColor = ($BackgroundColor == $lighterColor ? $darkerColor : $lighterColor);
    $pitch = PitchValue($cardStats[$i]);
    $timesPlayed = $cardStats[$i + $CardStats_TimesPlayed];
    $playStyle = "";
    if ($pitch == 3 && $timesPlayed > 1) $playStyle = "font-weight: bold; color:red;";
    else if ($pitch == 3 && $timesPlayed > 0) $playStyle = "font-weight: bold; color:GoldenRod;";
    else if ($pitch == 2 && $timesPlayed > 4) $playStyle = "font-weight: bold; color:red;";
    else if ($pitch == 2 && $timesPlayed > 2) $playStyle = "font-weight: bold; color:GoldenRod;";
    $timesPitched = $cardStats[$i + $CardStats_TimesPitched];
    $pitchStyle = "";
    if ($pitch == 1 && $timesPitched > 1) $pitchStyle = "font-weight: bold; color:red;";
    else if ($pitch == 1 && $timesPitched > 0) $pitchStyle = "font-weight: bold; color:GoldenRod;";
    else if ($pitch == 2 && $timesPitched > 4) $pitchStyle = "font-weight: bold; color:red;";
    else if ($pitch == 2 && $timesPitched > 2) $pitchStyle = "font-weight: bold; color:GoldenRod;";
    $timesBlocked = $cardStats[$i + $CardStats_TimesBlocked];
    $rv .= "<tr style='background-color:" . $BackgroundColor . ";'>";
    $rv .= "<td>" . CardLink($cardStats[$i], $cardStats[$i]) . "</td>";
    $rv .= "<td style='" . $playStyle . "'>" . $timesPlayed . "</td>";
    $rv .= "<td>" . $timesBlocked . "</td>";
    $rv .= "<td style='" . $pitchStyle . "'>" . $timesPitched . "</td>";
    $rv .= "</tr>";
  }
  $rv .= "</table>";
  $rv .= "</div>";
  $turnStats = &GetTurnStats($player);
  $otherPlayerTurnStats = &GetTurnStats($otherPlayer);
  $rv .= "<div style='float:right; width:49%; height:85%;';>";
  $rv .= "<h2 style='text-align:center'>Turn Stats</h2>";
  if ($player == $firstPlayer) $rv .= "<i>First turn omitted for first player</i><br>";
  //Damage stats
  $totalDamageThreatened = 0;
  $totalDamageDealt = 0;
  $totalResourcesUsed = 0;
  $totalCardsLeft = 0;
  $totalDefensiveCards = 0;
  $totalBlocked = 0;
  $numTurns = 0;
  $start = ($player == $firstPlayer ? TurnStatPieces() : 0); // TODO: Not skip first turn for first player
  for ($i = $start; $i < count($turnStats); $i += TurnStatPieces()) {
    $totalDamageThreatened += $turnStats[$i + $TurnStats_DamageThreatened];
    $totalDamageDealt += $turnStats[$i + $TurnStats_DamageDealt];
    $totalResourcesUsed += $turnStats[$i + $TurnStats_ResourcesUsed];
    $totalCardsLeft += $turnStats[$i + $TurnStats_CardsLeft];
    $totalDefensiveCards += ($turnStats[$i + $TurnStats_CardsPlayedDefense] + $turnStats[$i + $TurnStats_CardsBlocked]); //TODO: Separate out pitch for offense and defense
    $totalBlocked += $turnStats[$i + $TurnStats_DamageBlocked];
    ++$numTurns;
  }
  if ($numTurns > 0) {
    $rv .= "Total Damage Threatened: " . $totalDamageThreatened . "<br>";
    $rv .= "Total Damage Dealt: " . $totalDamageDealt . "<br>";
    $rv .= "Average Damage Threatened per turn: " . round($totalDamageThreatened / $numTurns, 2) . "<br>";
    $rv .= "Average Damage Dealt per turn: " . round($totalDamageDealt / $numTurns, 2) . "<br>";
    $totalOffensiveCards = 4 * $numTurns - $totalDefensiveCards;
    if ($totalOffensiveCards > 0) $rv .= "Average damage threatened per offensive card: " . round($totalDamageThreatened / $totalOffensiveCards, 2) . "<br>";
    $rv .= "Average Resources Used per turn: " . round($totalResourcesUsed / $numTurns, 2) . "<br>";
    $rv .= "Average Cards Left Over per turn: " . round($totalCardsLeft / $numTurns, 2) . "<br>";
    $rv .= "Average Value per turn (Damage threatened + block): " . round(($totalDamageThreatened + $totalBlocked) / $numTurns, 2) . "<br>";

    //Cards per turn stats
    $rv .= "<table style='text-align:center; margin-right:10px; width: 100%; margin-top:10px; border-spacing: 0; border-collapse: collapse; font-size: 1em; line-height: 24px; font-weight:'><tr>";
    $rv .= "<td style='border-bottom: 1px solid black; border-top: 1px solid black;'>Turn<br>Number</td>";
    $rv .= "<td style='border-bottom: 1px solid black; border-top: 1px solid black;'>Cards<br>Played</td>";
    $rv .= "<td style='border-bottom: 1px solid black; border-top: 1px solid black;'>Cards<br>Blocked</td>";
    $rv .= "<td style='border-bottom: 1px solid black; border-top: 1px solid black;'>Cards<br>Pitched</td>";
    $rv .= "<td style='border-bottom: 1px solid black; border-top: 1px solid black;'>Resources<br>Used</td>";
    $rv .= "<td style='border-bottom: 1px solid black; border-top: 1px solid black;'>Cards<br>Left</td>";
    $rv .= "<td style='border-bottom: 1px solid black; border-top: 1px solid black;'>Damage<br>Dealt</td>";
    $rv .= "<td style='border-bottom: 1px solid black; border-top: 1px solid black;'>Damage<br>Taken</td>";
    $rv .= "</tr>";

    for ($i = 0; $i < count($turnStats); $i += TurnStatPieces()) {
      $BackgroundColor = ($BackgroundColor == $lighterColor ? $darkerColor : $lighterColor);
      $rv .= "<tr style='background-color:" . $BackgroundColor . ";'>";
      $rv .= "<td>" . (($i / TurnStatPieces()) + 1) . "</td>";
      $rv .= "<td>" . ($turnStats[$i + $TurnStats_CardsPlayedOffense] + $turnStats[$i + $TurnStats_CardsPlayedDefense]) . "</td>";
      $rv .= "<td>" . $turnStats[$i + $TurnStats_CardsBlocked] . "</td>";
      $rv .= "<td>" . $turnStats[$i + $TurnStats_CardsPitched] . "</td>";
      $rv .= "<td>" . $turnStats[$i + $TurnStats_ResourcesUsed] . "</td>";
      $rv .= "<td>" . $turnStats[$i + $TurnStats_CardsLeft] . "</td>";
      $rv .= "<td>" . $turnStats[$i + $TurnStats_DamageDealt] . "</td>";
      $rv .= "<td>" . $otherPlayerTurnStats[$i + $TurnStats_DamageDealt] . "</td>";
      $rv .= "</tr>";
    }
    $rv .= "</table>";
  }
  $rv .= "</div>";
  return $rv;
}

function AttackModifiers($attackModifiers)
{
  $rv = "";
  for ($i = 0; $i < count($attackModifiers); $i += 2) {
    $idArr = explode("-", $attackModifiers[$i]);
    $cardID = $idArr[0];
    $bonus = $attackModifiers[$i + 1];
    if ($bonus == 0) continue;
    $cardLink = CardLink($cardID, $cardID);
    $rv .= "&#8226; " . ($cardLink != "" ? $cardLink : $cardID) . " gives " . ($bonus > 0 ? "+" : "") . $bonus . "<BR>";
  }
  return $rv;
}

function PitchColor($pitch)
{
  switch ($pitch) {
    case 1:
      return "red";
    case 2:
      return "GoldenRod";
    case 3:
      return "blue";
    default:
      return "LightSlateGrey";
  }
}

function BanishUI($from = "")
{
  global $turn, $currentPlayer, $playerID, $cardSize, $cardSizeAura;
  $rv = "";
  $size = ($from == "HAND" ? $cardSizeAura : 120);
  $banish = GetBanish($playerID);
  for ($i = 0; $i < count($banish); $i += BanishPieces()) {
    $action = $currentPlayer == $playerID && IsPlayable($banish[$i], $turn[0], "BANISH", $i) ? 14 : 0;
    $mod = explode("-", $banish[$i + 1])[0];
    $border = CardBorderColor($banish[$i], "BANISH", $action > 0, $mod);
    if ($mod == "INT") $rv .= Card($banish[$i], "concat", $size, 0, 1, 1); //Display intimidated cards grayed out and unplayable
    else if ($mod == "TCL" || $mod == "TT" || $mod == "TCC" || $mod == "NT" || $mod == "INST" || $mod == "MON212" || $mod == "ARC119")
      $rv .= Card($banish[$i], "concat", $size, $action, 1, 0, $border, 0, strval($i)); //Display banished cards that are playable
    else // if($from != "HAND")
    {
      if (PlayableFromBanish($banish[$i], $banish[$i+1]) || AbilityPlayableFromBanish($banish[$i]))
        $rv .= Card($banish[$i], "concat", $size, $action, 1, 0, $border, 0, strval($i));
      else if ($from != "HAND")
        $rv .= Card($banish[$i], "concat", $size, 0, 1, 0, $border);
    }
  }
  return $rv;
}

function BanishUIMinimal($from = "")
{
  global $turn, $currentPlayer, $playerID, $cardSizeAura, $MyCardBack, $mainPlayer;
  $rv = "";
  $size = ($from == "HAND" ? $cardSizeAura : 120);
  $banish = GetBanish($playerID);
  for ($i = 0; $i < count($banish); $i += BanishPieces()) {
    $action = $currentPlayer == $playerID && IsPlayable($banish[$i], $turn[0], "BANISH", $i) ? 14 : 0;
    $mod = explode("-", $banish[$i + 1])[0];
    $border = CardBorderColor($banish[$i], "BANISH", $action > 0, $mod);
    if ($mod == "INT") {
      if ($rv != "") $rv .= "|";
      if ($playerID == 3) ClientRenderedCard(cardNumber: $MyCardBack, overlay: 1, controller: $playerID);
      else $rv .= ClientRenderedCard(cardNumber: $banish[$i], overlay: 1, controller: $playerID);
    }
    else {
      if ($action > 0) {
        if ($rv != "") $rv .= "|";
        $rv .= ClientRenderedCard(cardNumber: $banish[$i], action: $action, borderColor: $border, actionDataOverride: strval($i), controller: $playerID);
      }
      else if ($from != "HAND")
      {
        $rv .= Card($banish[$i], "concat", $size, 0, 1, 0, $border);
      }
    }
  }
  return $rv;
}

function TheirBanishUIMinimal($from = "")
{
  global $playerID, $cardSizeAura, $TheirCardBack, $turn, $mainPlayer;
  $rv = "";
  $size = ($from == "HAND" ? $cardSizeAura : 120);
  $otherPlayer = ($playerID == 1 ? 2 : 1);
  $banish = GetBanish($otherPlayer);
  for ($i = 0; $i < count($banish); $i += BanishPieces()) {
    $mod = explode("-", $banish[$i + 1])[0];
    if ($mod == "INT") {
      if ($rv != "") $rv .= "|";
      $rv .= ClientRenderedCard(cardNumber: $TheirCardBack, overlay: 1, controller: $playerID);
    } else {
      if ($otherPlayer == $mainPlayer && IsPlayable($banish[$i], $turn[0], "BANISH", $i, $restriction, $otherPlayer)) {
        if ($rv != "") $rv .= "|";
        $rv .= ClientRenderedCard(cardNumber: $banish[$i], controller: $otherPlayer);
      } else if ($from != "HAND")
        $rv .= Card($banish[$i], "concat", $size, 0, 1, 0);
    }
  }
  return $rv;
}

function CardBorderColor($cardID, $from, $isPlayable, $mod = "-")
{
  global $playerID, $currentPlayer, $turn;
  if ($playerID != $currentPlayer) return 0;
  if ($turn[0] == "B") return ($isPlayable ? 6 : 0);
  if ($from == "BANISH") {
    if ($isPlayable || PlayableFromBanish($cardID, $mod)) return 7;
    if (HasBloodDebt($cardID)) return 2;
    if ($isPlayable && HasReprise($cardID) && RepriseActive()) return 5;
    if ($isPlayable && ComboActive($cardID)) return 5;
    if ($isPlayable && HasRupture($cardID) && RuptureActive(true)) return 5;
    return 0;
  }
  if ($isPlayable && ComboActive($cardID)) return 3;
  if ($isPlayable && HasReprise($cardID) && RepriseActive()) return 3;
  if ($isPlayable && HasRupture($cardID) && RuptureActive(true, (CardType($cardID) != "AA"))) return 3;
  else if ($isPlayable) return 6;
  return 0;
}

function CardLink($caption, $cardNumber, $recordMenu = false)
{
  //$file = "'./" . "CardImages" . "/" . $cardNumber . ".png'";
  global $darkMode, $playerID, $isReactFE;
  if ($isReactFE) {
    return "{{" . $cardNumber . "|" . CardName($cardNumber) . "|" . PitchValue($cardNumber) . "}}";
  }

  $name = CardName($cardNumber);
  if ($name == "") return "";
  $pitchValue = PitchValue($cardNumber);
  $pitchText = "";
  if ($recordMenu) {
    $color = "#DDD";
  } else {
    switch ($pitchValue) {
      case 3:
        $color = "#009DDF";
        $pitchText = " (3)";
        break;
      case 2:
        $color = "GoldenRod";
        $pitchText = " (2)";
        break;
      case 1:
        $color = "#AF1518";
        $pitchText = " (1)";
        break;
      default:
        if ($darkMode) {
          $color = "#1a1a1a";
          break;
        } else {
          $color = "#AAA";
          break;
        }
    }
  }
  if (function_exists("IsColorblindMode") && !IsColorblindMode($playerID)) $pitchText = "";
  $file = "'./" . "WebpImages" . "/" . $cardNumber . ".webp'";
  return "<b><span style='color:" . $color . "; cursor:default;' onmouseover=\"ShowDetail(event," . $file . ")\" onmouseout='HideCardDetail()'>" . $name . $pitchText . "</span></b>";
}

function MainMenuUI()
{
  global $playerID, $gameName, $redirectPath, $authKey;
  // TODO: Have as a global variable.
  $reactFE = "https://fe.talishar.net/game/play";
  $rv = "<table class='table-MainMenu'><tr><td class='table-td-MainMenu'>";
  $rv .= GetSettingsUI($playerID) . "<BR>";
  $rv .= "</td><td style='width:45%;  margin-top: 10px; vertical-align:top;'>";
  $rv .= "<div style='font-size:24px;'><a href='" . $reactFE . "?gameName=$gameName&playerID=$playerID'>Play in Beta UI</a></div><br>";
  $rv .= CreateButton($playerID, "Home Page", 100001, 0, "24px", "", "", false, true) . "<BR>";
  $rv .= CreateButton($playerID, "Concede", 100002, 0, "24px", prompt: "⚠️ Do you really want to concede ?") . "<BR><BR>";
  $rv .= CreateButton($playerID, "Report Bug", 100003, 0, "24px") . "<BR>";
  $rv .= CreateButton($playerID, "Undo", 10000, 0, "24px", "", "Hotkey: U") . "<BR>";

  $rv .= PreviousTurnSelectionUI() . "<BR>";
  $rv .= "<img style='width: 66vh; height: 33vh;' src='./Images/ShortcutMenu.png'>";
  $isSpectateEnabled = GetCachePiece($gameName, 9) == "1";
  if($isSpectateEnabled) $rv .= "<div><input class='GameLobby_Input' onclick='copyText()' style='width:40%;' type='text' id='gameLink' value='" . $reactFE . "?gameName=$gameName&playerID=3'>&nbsp;<button class='GameLobby_Button' style='margin-left:3px;' onclick='copyText()'>Copy Spectate Link</button></div><br>";
  else $rv .= CreateButton($playerID, "Enable Spectating", 100013, 0, "24px", "", "Enable Spectating", 1) . "<BR>";
  if (isset($_SESSION["userid"])) {
    $userID = $_SESSION["userid"];
    $badges = GetMyAwardableBadges($userID);
    for ($i = 0; $i < count($badges); ++$i) {
      $rv .= CreateButton($playerID, "Give Badge", 100010, 0, "24px") . "<BR>";
    }
  }
  $rv .= "</td></tr></table>";
  return $rv;
}

function PreviousTurnSelectionUI()
{
  global $playerID, $gameName;
  $rv = "<h3>Revert to Start of Previous Turn</h3>"; // TODO: Revert Player 1 Turn 1 to the start of the game.
  $rv .= CreateButton($playerID, "This Turn", 10003, "beginTurnGamestate.txt", "20px") . "<BR>";
  $lastTurnFN = "lastTurnGamestate.txt";
  if (file_exists("./Games/" . $gameName . "/" . $lastTurnFN)) $rv .= CreateButton($playerID, "Last Turn", 10003, $lastTurnFN, "20px") . "<BR>";
  return $rv;
}

function GetTheirBanishForDisplay($playerID)
{
  global $theirBanish;
  $TheirCardBack = GetCardBack($playerID == 1 ? 2 : 1);
  $banish = array();
  for ($i = 0; $i < count($theirBanish); $i += BanishPieces()) {
    if ($theirBanish[$i + 1] == "INT" || $theirBanish[$i + 1] == "UZURI") array_push($banish, $TheirCardBack);
    else array_push($banish, $theirBanish[$i]);
  }
  return $banish;
}

function GetMyBanishForDisplay($playerID)
{
  global $myBanish;
  $myCardBack = GetCardBack($playerID == 1 ? 1 : 2);
  $banish = array();
  for ($i = 0; $i < count($myBanish); $i += BanishPieces()) {
    if ($myBanish[$i + 1] == "INT" || $myBanish[$i + 1] == "UZURI") array_push($banish, $myCardBack);
    else array_push($banish, $myBanish[$i]);
  }
  return $banish;
}
