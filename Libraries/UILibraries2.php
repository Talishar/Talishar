<?php

  function BackgroundColor($darkMode)
  {
    if($darkMode) return "rgba(74, 74, 74, 0.9)";
    else return "rgba(235, 235, 235, 0.9)";
  }

  function PopupBorderColor($darkMode)
  {
    if($darkMode) return "#DDD";
    else return "black";
  }

  function Card($cardNumber, $folder, $maxHeight, $action=0, $showHover=0, $overlay=0, $borderColor=0, $counters=0, $actionDataOverride="", $id="", $rotate=false, $ally=false)
  {//
    global $playerID, $gameName, $darkMode, $cardIconSize;
    if($darkMode == null) $darkMode = false;
    if($folder == "crops")
    {
      $cardNumber .= "_cropped";
    }
    $fileExt = ".png";
    if($cardNumber == "ENDTURN" || $cardNumber == "RESUMETURN")
    {
      $folder = str_replace("CardImages", "Images", $folder);
    }
    else if(mb_strpos($folder, "CardImages") !== false)
    {
      $folder = str_replace("CardImages", "WebpImages", $folder);
      $fileExt = ".webp";
    }
    else if($folder == "concat")
    {
      $fileExt = ".webp";
    }
    $actionData = $actionDataOverride != "" ? $actionDataOverride : $cardNumber;
    //Enforce 375x523 aspect ratio as exported (.71)
    $margin = "margin:0px;";
    $border = "";
    if($borderColor != -1) $margin = $borderColor > 0 ? "margin:0px;" : "margin:1px;";
    if($folder == "crops") $margin = "0px;";

    $rv = "<a style='" . $margin . " position:relative; display:inline-block;" . ($action > 0 ? "cursor:pointer;" : "") . "'" . ($showHover > 0 ? " onmouseover='ShowCardDetail(event, this)' onmouseout='HideCardDetail()'" : "") . ($action > 0 ? " onclick='SubmitInput(\"" . $action . "\", \"&cardID=" . $actionData . "\");'" : "") . ">";

    if($borderColor > 0){
      $border = "border-radius:10px; border:2px solid " . BorderColorMap($borderColor) . ";";
    } elseif($folder == "concat"){
      $border = "border-radius:5px; border:1px solid black;";
    } else {
      $border = "border: 1px solid transparent;";
    }
    if($folder == "crops") { $height = $maxHeight; $width = ($height * 1.29); }
    else if($folder == "concat") { $height = $maxHeight; $width = $maxHeight; }
    else if($rotate == false) { $height = $maxHeight; $width = ($maxHeight * .71); }
    else {$height = ($maxHeight * .71); $width = $maxHeight; }

    if($rotate == false){
      $rv .= "<img " . ($id != "" ? "id='".$id."-img' ":"") . "style='" . $border . " height:" . $height . "; width:" . $width . "px;' src='./" . $folder . "/" . $cardNumber . $fileExt . "' />";
      $rv .= "<div " . ($id != "" ? "id='".$id."-ovr' ":"") . "style='visibility:" . ($overlay == 1 ? "visible" : "hidden") . "; width:100%; height:100%; top:0px; left:0px; border-radius:10px; position:absolute; background: rgba(0, 0, 0, 0.5); z-index: 1;'></div>";
    } else {
      // Landmarks Rotation
      $rv .= "<img " . ($id != "" ? "id='".$id."-img' ":"") . "style='transform:rotate(-90deg);" . $border . " height:" . $height . "; width:" . $width . "px;' src='./" . $folder . "/" . $cardNumber . $fileExt . "' />";
      $rv .= "<div " . ($id != "" ? "id='".$id."-ovr' ":"") . "style='transform:rotate(-90deg); visibility:" . ($overlay == 1 ? "visible" : "hidden") . "; width:100%; height:100%; top:0px; left:0px; border-radius:10px; position:absolute; background: rgba(0, 0, 0, 0.5); z-index: 1;'></div>";
    }

  // Counters Style
    if($counters != 0) {
      if($ally){
        $rv .= "<div style='margin: 0; top: 50%; left: 50%; margin-right: -50%; width: 28px; height: 28px; padding: 3px;
        text-align: center; transform: translate(-50%, -50%);
        position:absolute; z-index: 5; font-size:26px; font-weight: 600; color: #EEE; text-shadow: 2px 0 0 #000, 0 -2px 0 #000, 0 2px 0 #000, -2px 0 0 #000;'>" . $counters ."
        <img style='height:". $cardIconSize ."; width:". $cardIconSize ."; opacity: 0.8;
        position: absolute; margin: auto; top: 1; left: -3; right: 0;bottom: 0; z-index:-5;' src='./Images/Life.png'></img></div>";

      } else {
      $rv .= "<div style='margin: 0; top: 50%; left: 50%;
      margin-right: -50%; border-radius: 50%; width: 24px; height: 24px; padding: 5px; border: 3px solid " . PopupBorderColor($darkMode) . "; text-align: center;
      transform: translate(-50%, -50%); position:absolute; z-index: 10; background:" . BackgroundColor($darkMode) . ";
      font-size:22px; font-weight:500;'>" . $counters . "</div>";
      }
    }
    $rv .= "</a>";
    return $rv;
  }

  function BorderColorMap($code)
  {
    switch($code)
    {
      case 1: return "DeepSkyBlue";
      case 2: return "red";
      case 3: return "yellow";
      case 4: return "Gray";
      case 5: return "Tan";
      case 6: return "chartreuse";
      case 7: return "Orchid";
    }
  }

  function CreateButton($playerID, $caption, $mode, $input, $size="", $image="", $tooltip="", $fullRefresh=false, $fullReload=false)
  {
    global $gameName, $authKey;
    if($fullReload) $onClick = "\"document.location.href = './ProcessInput2.php?gameName=$gameName&playerID=$playerID&authKey=$authKey&mode=$mode&buttonInput=$input'\"";
    else $onClick = "'SubmitInput(\"" . $mode . "\", \"&buttonInput=" . $input . "\", " . $fullRefresh .");'";
    if($image != "")
    {
      $rv = "<img style='cursor:pointer;' src='" . $image . "' onclick=$onClick>";
    }
    else $rv = "<button title='$tooltip' " . ($size != "" ? "style='display: inline-block;
      margin: 5px; text-align: center; vertical-align: middle; padding: 3px 6px; border: 1px solid; border-radius: 8px;
      background: #eeeeee; background: -webkit-gradient(linear, left top, left bottom, from(#eeeeee), to(#aaaaaa));
      background: -moz-linear-gradient(top, #eeeeee, #aaaaaa); background: linear-gradient(to bottom, #eeeeee, #aaaaaa); -webkit-box-shadow: #000000 0px 0px 5px 0px; -moz-box-shadow: #000000 0px 0px 5px 0px;
      box-shadow: #000000 0px 0px 5px 0px; text-shadow: #ffffff 1px 1px 1px; font: helvetica; font-weight: 550; color: #111111;
      text-decoration: none; cursor:pointer; font-size:$size;' " : "") . " onclick=$onClick>" . $caption . "</button>";
    return $rv;
  }

  function ProcessInputLink($player, $mode, $input)
  {
    global $gameName;
    return " onmousedown='SubmitInput(\"" . $mode . "\", \"&buttonInput=" . $input . "\");'";
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

  function CreateCheckbox($input, $value)
  {
    $rv = "<input type='checkbox' id='chk" . $input . "' name='chk" . $input . "' value='" . $value . "'>";
    $rv .= "<label for='chk" . $input . "'>Select?</label>";
    return $rv;
  }

  function CreatePopup($id, $fromArr, $canClose, $defaultState=0, $title="", $arrElements=1,$customInput="",$path="./", $big=false, $overCombatChain=false)
  {
    global $combatChain, $darkMode, $cardSize;
    if($darkMode == null) $darkMode = false;
    $top = "50%"; $left = "20%"; $width = "60%"; $height = "45%";
    if($big) { $top = "5%"; $left = "5%";  $width = "80%"; $height = "90%"; }
    if($overCombatChain) { $top = "180px"; $left = "320px"; $width = "auto"; $height = "auto"; }

    $rv = "<div id='" . $id . "' style='overflow-y: auto; background-color:" . BackgroundColor($darkMode) . "; border: 3px solid " . PopupBorderColor($darkMode) . "; border-radius: 7px; z-index:10000; position: absolute; top:" . $top . "; left:" . $left . "; width:" . $width . "; height:" . $height . ";"  . ($defaultState == 0 ? " display:none;" : "") . "'>";

    if($title != "") $rv .= "<h" . ($big ? "2" : "4") . " style='margin-left: 10px; margin-top: 5px; text-align: center;'>" . $title . "</h" . ($big ? "2" : "4") . ">";
    if($canClose == 1) $rv .= "<div style='position:absolute; cursor:pointer; top:-5px; right:5px; font-size:50px; font-weight:lighter;' onclick='(function(){ document.getElementById(\"" . $id . "\").style.display = \"none\";})();'>&#10006;</div>";
    for($i=0; $i<count($fromArr); $i += $arrElements)
    {
      $rv .= Card($fromArr[$i], "concat", $cardSize, 0, 1);
    }
    $rv .= "<div style='margin-left: 5px;'>" . $customInput . "</div>";
    $rv .= "</div>";
    return $rv;
  }

  function CardStatsUI($player)
  {
    $rv = "<div id='cardStats' style='background-color: rgba(255,255,255,0.80); z-index:100; position: absolute; top:120px; left: 50px; right: 250px; bottom:50px;'>";
    $rv .= CardStats($player);
    $rv .= "</div>";
    return $rv;
  }

  function CardStats($player)
  {
    global $TurnStats_DamageThreatened, $TurnStats_DamageDealt, $TurnStats_CardsPlayedOffense, $TurnStats_CardsPlayedDefense, $TurnStats_CardsPitched, $TurnStats_CardsBlocked, $firstPlayer;
    global $TurnStats_ResourcesUsed, $TurnStats_CardsLeft, $TurnStats_DamageBlocked;
    $cardStats = &GetCardStats($player);
    $rv = "<div style='float:left; height:85%;'>";
    $rv .= "<h2>Card Play Stats</h2>";
    $rv .= "<table><tr><td>Card ID</td><td>Times Played</td><td>Times Blocked</td><td>Times Pitched</td></tr>";
    for($i=0; $i<count($cardStats); $i+=CardStatPieces())
    {
      $pitch = PitchValue($cardStats[$i]);
      $timesPlayed = $cardStats[$i+1];
      $playStyle = "";
      if($pitch == 3 && $timesPlayed > 1) $playStyle = "font-weight: bold; color:red;";
      else if($pitch == 3 && $timesPlayed > 0) $playStyle = "font-weight: bold; color:gold;";
      else if($pitch == 2 && $timesPlayed > 4) $playStyle = "font-weight: bold; color:red;";
      else if($pitch == 2 && $timesPlayed > 2) $playStyle = "font-weight: bold; color:gold;";
      $timesPitched = $cardStats[$i+3];
      $pitchStyle = "";
      if($pitch == 1 && $timesPitched > 1) $pitchStyle = "font-weight: bold; color:red;";
      else if($pitch == 1 && $timesPitched > 0) $pitchStyle = "font-weight: bold; color:gold;";
      else if($pitch == 2 && $timesPitched > 4) $pitchStyle = "font-weight: bold; color:red;";
      else if($pitch == 2 && $timesPitched > 2) $pitchStyle = "font-weight: bold; color:gold;";
      $rv .= "<tr><td>" . CardLink($cardStats[$i], $cardStats[$i]) . "</td><td style='" . $playStyle . "'>" . $timesPlayed . "</td><td>" . $cardStats[$i+2] . "</td><td style='" . $pitchStyle . "'>" . $timesPitched . "</td></tr>";
    }
    $rv .= "</table>";
    $rv .= "</div>";
    $turnStats = &GetTurnStats($player);
    $rv .= "<div style='float:left; height:85%;'>";
    $rv .= "<h2>Turn Stats</h2>";
    if($player == $firstPlayer) $rv .= "<i>First turn omitted for first player.</i><br>";
    //Damage stats
    $totalDamageThreatened = 0;
    $totalDamageDealt = 0;
    $totalResourcesUsed = 0;
    $totalCardsLeft = 0;
    $totalDefensiveCards = 0;
    $totalBlocked = 0;
    $numTurns = 0;
    $start = ($player == $firstPlayer ? TurnStatPieces() : 0);//Skip first turn for first player
    for($i=$start; $i<count($turnStats); $i+=TurnStatPieces())
    {
      $totalDamageThreatened += $turnStats[$i + $TurnStats_DamageThreatened];
      $totalDamageDealt += $turnStats[$i + $TurnStats_DamageDealt];
      $totalResourcesUsed += $turnStats[$i + $TurnStats_ResourcesUsed];
      $totalCardsLeft += $turnStats[$i + $TurnStats_CardsLeft];
      $totalDefensiveCards += ($turnStats[$i+$TurnStats_CardsPlayedDefense] + $turnStats[$i+$TurnStats_CardsBlocked]);//TODO: Separate out pitch for offense and defense
      $totalBlocked += $turnStats[$i+$TurnStats_DamageBlocked];
      ++$numTurns;
    }
    if($numTurns > 0)
    {
      $rv .= "Total Damage Threatened: " . $totalDamageThreatened . "<br>";
      $rv .= "Total Damage Dealt: " . $totalDamageDealt . "<br>";
      $rv .= "Average Damage Threatened per turn: " . round($totalDamageThreatened/$numTurns, 2) . "<br>";
      $rv .= "Average Damage Dealt per turn: " . round($totalDamageDealt/$numTurns, 2) . "<br>";
      $totalOffensiveCards = 4*$numTurns - $totalDefensiveCards;
      if($totalOffensiveCards > 0) $rv .= "Average damage threatened per offensive card: " . round($totalDamageThreatened/$totalOffensiveCards, 2) . "<br>";
      $rv .= "Average Resources Used per turn: " . round($totalResourcesUsed/$numTurns, 2) . "<br>";
      $rv .= "Average Cards Left Over per turn: " . round($totalCardsLeft/$numTurns, 2) . "<br>";
      $rv .= "Average Value per turn (Damage threatened + block): " . round(($totalDamageThreatened + $totalBlocked)/$numTurns, 2) . "<br>";
      //Cards per turn stats
      $rv .= "<table><tr><td>Turn Number</td><td>Cards Played</td><td>Cards Blocked</td><td>Cards Pitched</td><td>Resources Used</td><td>Cards Left</td></tr>";
      for($i=0; $i<count($turnStats); $i+=TurnStatPieces())
      {
        $rv .= "<tr><td>" . (($i / TurnStatPieces()) + 1) . "</td><td>" . ($turnStats[$i+$TurnStats_CardsPlayedOffense] + $turnStats[$i+$TurnStats_CardsPlayedDefense]) . "</td><td>" . $turnStats[$i+$TurnStats_CardsBlocked] . "</td><td>" . $turnStats[$i+$TurnStats_CardsPitched] . "</td><td>" . $turnStats[$i+$TurnStats_ResourcesUsed] . "</td><td>" . $turnStats[$i+$TurnStats_CardsLeft] . "</td></tr>";
      }
      $rv .= "</table>";
    }
    $rv .= "</div>";
    return $rv;
  }

  function AttackModifiers($attackModifiers)
  {
    $rv = "";
    for($i=0; $i<count($attackModifiers); $i += 2)
    {
      $idArr = explode("-", $attackModifiers[$i]);
      $cardID = $idArr[0];
      $bonus = $attackModifiers[$i+1];
      if($bonus == 0) continue;
      $cardLink = CardLink($cardID, $cardID);
      $rv .= ($cardLink != "" ? $cardLink : $cardID) . " gives " . ($bonus > 0 ? "+" : "") . $bonus . "<BR>";
    }
    return $rv;
  }

  function PitchColor($pitch)
  {
    switch($pitch)
    {
      case 1: return "red";
      case 2: return "Gold";
      case 3: return "blue";
      default: return "LightSlateGrey";
    }
  }

  function BanishUI($from="")
  {
    global $turn, $currentPlayer, $playerID, $cardSize, $cardSizeAura;
    $rv = "";
    $size = ($from == "HAND" ? $cardSizeAura : 120);
    $banish = GetBanish($playerID);
    for($i=0; $i<count($banish); $i+=BanishPieces()) {
      $action = $currentPlayer == $playerID && IsPlayable($banish[$i], $turn[0], "BANISH", $i) ? 14 : 0;
      $border = CardBorderColor($banish[$i], "BANISH", $action > 0);
      $mod = explode("-", $banish[$i+1])[0];
      if($mod == "INT") $rv .= Card($banish[$i], "concat", $size, 0, 1, 1);//Display intimidated cards grayed out and unplayable
      else if($mod == "TCL" || $mod == "TT" || $mod == "TCC" || $mod == "NT" || $mod == "INST" || $mod == "MON212" || $mod == "ARC119")
        $rv .= Card($banish[$i], "concat", $size, $action, 1, 0, $border, 0, strval($i));//Display banished cards that are playable
      else// if($from != "HAND")
      {
        if(PlayableFromBanish($banish[$i]) || AbilityPlayableFromBanish($banish[$i]))
          $rv .= Card($banish[$i], "concat", $size, $action, 1, 0, $border, 0, strval($i));
        else if($from != "HAND")
          $rv .= Card($banish[$i], "concat", $size, 0, 1, 0, $border);
      }
    }
    return $rv;
  }

  function CardBorderColor($cardID, $from, $isPlayable)
  {
    global $playerID, $currentPlayer, $turn;
    if($playerID != $currentPlayer) return 0;
    if($turn[0] == "B") return ($isPlayable ? 6 : 0);
    if($from == "BANISH")
    {
      if($isPlayable || PlayableFromBanish($cardID)) return 7;
      if(HasBloodDebt($cardID)) return 2;
      if($isPlayable && HasReprise($cardID) && RepriseActive()) return 5;
      if($isPlayable && ComboActive($cardID)) return 5;
      if($isPlayable && HasRupture($cardID) && RuptureActive(true)) return 5;
      return 0;
    }
    if($isPlayable && ComboActive($cardID)) return 3;
    if($isPlayable && HasReprise($cardID) && RepriseActive()) return 3;
    if($isPlayable && HasRupture($cardID) && RuptureActive(true)) return 3;
    else if($isPlayable) return 6;
    return 0;
  }

  function CardLink($caption, $cardNumber)
  {
    //$file = "'./" . "CardImages" . "/" . $cardNumber . ".png'";
    $name = CardName($cardNumber);
    if($name == "") return "";
    $pitchValue = PitchValue($cardNumber);
    switch($pitchValue)
    {
      case 3: $color = "Blue"; break;
      case 2: $color = "GoldenRod"; break;
      case 1: $color = "Red"; break;
      default: $color = "DimGray"; break;
    }
    $file = "'./" . "WebpImages" . "/" . $cardNumber . ".webp'";
    return "<b><span style='color:" . $color . "; cursor:default;' onmouseover=\"ShowDetail(event," . $file . ")\" onmouseout='HideCardDetail()'>" . $name . "</span></b>";
  }

  function MainMenuUI()
  {
    global $playerID;
    $rv = "<table><tr><td>";
    $rv .= CreateButton($playerID, "Main Menu", 100001, 0, "20px", "", "", false, true) . "<BR>";
    $rv .= CreateButton($playerID, "Undo", 10000, 0, "20px", "", "Hotkey: U") . "<BR>";
    $rv .= CreateButton($playerID, "Concede", 100002, 0, "20px") . "<BR>";
    $rv .= CreateButton($playerID, "Report Bug", 100003, 0, "20px") . "<BR>";
    $rv .= GetSettingsUI($playerID) . "<BR>";
    $rv .= "</td><td>";
    $rv .= PreviousTurnSelectionUI();
    $rv .= "</td></tr></table>";
    return $rv;
  }

  function PreviousTurnSelectionUI()
  {
    global $currentTurn, $mainPlayer, $playerID, $firstPlayer;
    $rv = "<h2>Revert to Start of Previous Turn</h2>";
    for($i=1; $i<=$currentTurn; ++$i)
    {
      if($i < $currentTurn - 5) continue;
      for($j=1; $j<=2; ++$j)
      {
        if($i == 1 && $firstPlayer == 2 && $j == 1) continue;//Player 1 never got a turn 1
        if($i == $currentTurn && $j > $mainPlayer) continue;//Player 2 hasn't gotten a turn yet
        $rv .= CreateButton($playerID, "Player $j Turn $i", 10003, $j . "-" . $i, "14px") . "<BR>";
      }
    }
    return $rv;
  }

  function GetTheirBanishForDisplay()
  {
    global $theirBanish;
    $banish = array();
    for($i=0; $i<count($theirBanish); $i+=BanishPieces())
    {
      if($theirBanish[$i+1] == "INT") array_push($banish, "cardBack");
      else array_push($banish, $theirBanish[$i]);
    }
    return $banish;
  }

?>
