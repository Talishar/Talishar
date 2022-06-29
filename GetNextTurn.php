<?php

  include 'Libraries/HTTPLibraries.php';

  //We should always have a player ID as a URL parameter
  $gameName=$_GET["gameName"];
  if(!IsGameNameValid($gameName)) { echo("Invalid game name."); exit; }
  $playerID=TryGet("playerID", 3);
  $authKey=TryGet("authKey", 3);
  $lastUpdate = TryGet("lastUpdate", 0);

  include "HostFiles/Redirector.php";
  include "Libraries/SHMOPLibraries.php";
  include "WriteLog.php";

  $count = 0;
  $cacheVal = GetCachePiece($gameName, 1);
  while($lastUpdate != 0 && $cacheVal < $lastUpdate)
  {
    usleep(50000);//50 milliseconds
    $currentTime = round(microtime(true) * 1000);
    $cacheVal = GetCachePiece($gameName, 1);
    SetCachePiece($gameName, $playerID+1, $currentTime);
    ++$count;
    if($count == 100) break;
    $otherP = ($playerID == 1 ? 2 : 1);
    $oppLastTime = GetCachePiece($gameName, $otherP+1);
    $oppStatus = GetCachePiece($gameName, $otherP+3);
    //WriteLog($oppStatus);
    if(($currentTime - $oppLastTime) > 5000 && ($oppStatus == "0"))
    {
      WriteLog("Opponent has disconnected. Waiting to reconnect.");
      SetCachePiece($gameName, 1, $currentTime);
      SetCachePiece($gameName, $otherP+3, "1");
    }
    else if(($currentTime - $oppLastTime) > 60000 && $oppStatus == "1")
    {
      WriteLog("Opponent has left the game.");
      SetCachePiece($gameName, 1, $currentTime);
      SetCachePiece($gameName, $otherP+3, "2");
    }
  }

  if($lastUpdate != 0 && $cacheVal < $lastUpdate) { echo "0"; exit; }
  else {
  //First we need to parse the game state from the file
  include "ParseGamestate.php";
  include "GameTerms.php";
  include "GameLogic.php";
  include "Libraries/UILibraries2.php";
  include "Libraries/StatFunctions.php";
  include "Libraries/PlayerSettings.php";

  if($turn[0] == "REMATCH")
  {
    include "MenuFiles/ParseGamefile.php";
    include "MenuFiles/WriteGamefile.php";
    if($gameStatus == $MGS_GameStarted)
    {
      include "AI/CombatDummy.php";
      $origDeck = "./Games/" . $gameName . "/p1DeckOrig.txt";
      if(file_exists($origDeck)) copy($origDeck, "./Games/" . $gameName . "/p1Deck.txt");
      $origDeck = "./Games/" . $gameName . "/p2DeckOrig.txt";
      if(file_exists($origDeck)) copy($origDeck, "./Games/" . $gameName . "/p2Deck.txt");
      $gameStatus = (IsPlayerAI(2) ? $MGS_ReadyToStart : $MGS_ChooseFirstPlayer);
      $firstPlayer = 1;
      $firstPlayerChooser = ($winner == 1 ? 2 : 1);
      unlink("./Games/" . $gameName . "/gamestate.txt");

      $errorFileName = "./BugReports/CreateGameFailsafe.txt";
      $errorHandler = fopen($errorFileName, "a");
      date_default_timezone_set('America/Chicago');
      $errorDate = date('m/d/Y h:i:s a');
      $errorOutput = "Rematch failsafe hit for game $gameName at $errorDate";
      fwrite($errorHandler, $errorOutput . "\r\n");
      fclose($errorHandler);

      WriteLog("Player $firstPlayerChooser lost and will choose first player for the rematch.");
    }
    WriteGameFile();
    $currentTime = round(microtime(true) * 1000);
    SetCachePiece($gameName, 2, $currentTime);
    SetCachePiece($gameName, 3, $currentTime);
    echo("1234REMATCH"); exit;
  }
  echo(strval(round(microtime(true) * 1000)) . "ENDTIMESTAMP");

  $targetAuth = ($playerID == 1 ? $p1Key : $p2Key);
  if($playerID != 3 && $authKey != $targetAuth) exit;

  if($currentPlayer == $playerID) { $icon = "ready.png"; $readyText = "You are the player with priority."; }
  else { $icon = "notReady.png"; $readyText = "The other player has priority."; }

  if(count($turn) == 0)
  {
    RevertGamestate();
    SetCachePiece($gameName, 1, strval(round(microtime(true) * 1000)));
    exit();
  }

  echo("<div id='iconHolder' style='display:none;'>" . $icon . "</div>");

  $cardSize = 120;
  $cardSizeAura = 95;
  $cardSizeEquipment = 95;
  $cardEquipmentWidth = intval($cardSizeEquipment * 0.71);
  $cardWidth = intval($cardSize * 0.72);
  $cardHeight = intval($cardSize * 0.72);
  $cardIconSize = 40;
  $cardIconLeft = 30;
  $cardIconTop = 30;
  $bigCardSize = 200;
  $permLeft = intval(GetCharacterLeft("E", "Arms")) + $cardWidth + 20;
  $permWidth = "calc(50% - " . ($cardWidth/2 + $cardWidth + 20 + $permLeft) . "px)";
  $permHeight = $cardSize * 2 + 20;

  $darkMode = IsDarkMode($playerID);
  $manualMode = IsManualMode($playerID);

  if($darkMode) $backgroundColor = "rgba(74, 74, 74, 0.9)";
  else $backgroundColor = "rgba(235, 235, 235, 0.9)";

  $blankZone = ($darkMode ? "blankZoneDark" : "blankZone");
  $borderColor = ($darkMode ? "#DDD" : "black");
  $fontColor = ($darkMode ? "black" : "#EEE");

  //Display background
  if(IsDarkPlainMode($playerID))
    echo("<div style='position:absolute; z-index:-100; left:0px; top:0px; width:100%; height:100%;'><img style='object-fit: cover; height:100%; width:100%;' src='./Images/black-fabric.jpg' /></div>");
  else if(IsDarkMode($playerID))
    echo("<div style='position:absolute; z-index:-100; left:0px; top:0px; width:100%; height:100%;'><img style='object-fit: cover; height:100%; width:100%;' src='./Images/flicflak.jpg' /></div>");
  else if(IsPlainMode($playerID))
    echo("<div style='position:absolute; z-index:-100; left:0px; top:0px; width:100%; height:100%;'><img style='object-fit: cover; height:100%; width:100%;' src='./Images/gray-fabric.jpg' /></div>");
  else
    echo("<div style='position:absolute; z-index:-100; left:0px; top:0px; width:100%; height:100%;'><img style='height:100%; width:100%;' src='./Images/findCenterBackground.jpg' /></div>");

  echo("<div style='position:absolute; right:300px; top:calc(50% - 100px); height:200px; z-index:100;'>
      <span style='position:absolute; text-align:center; width:27px; font-weight: 550; font-size: 24px; top:149px; left:28px;'>$myHealth</span>");
  echo(($manualMode ? "<span style='position:absolute; top:120px; left:65px;'>" . CreateButton($playerID, "-1", 10005, 0, "20px") . CreateButton($playerID, "+1", 10006, 0, "20px") . "</span>": ""));
  echo("<span style='position:absolute; font-size: 24px; font-weight: 550; top:23px; left:28px;'>$theirHealth</span>");
  echo(($manualMode ? "<span style='position:absolute; top:0px; left:65px;'>" . CreateButton($playerID, "-1", 10007, 0, "20px") . CreateButton($playerID, "+1", 10008, 0, "20px") . "</span>": ""));
  if(IsDarkMode($playerID)) echo("<img style='height:200px;' src='./Images/DuoLifeDark.png' />");
  else echo("<img style='height:200px;' src='./Images/DuoLife.png' />");
  echo("<div style='position:absolute; top:37px; left:-130px; z-index:-5;'><img style='height:125px; width:150px;' src='./Images/passBG.png' /></div>");
  if(CanPassPhase($turn[0]) && $currentPlayer == $playerID) echo("<div title='Space is the shortcut to pass.' " . ProcessInputLink($playerID, 99, 0) . " class='passButton' style='position:absolute; top:62px; left:-200px; z-index:-1; cursor:pointer; height:75px; width:225px;'><span style='position:absolute; left:100px; top:15px; color:#DDD; font-family:Helvetica; font-size:36px; user-select: none;'>Pass</span></div>");
  else echo("<div title='Space is the shortcut to pass.' class='passInactive' style='position:absolute; top:62px; left:-200px; z-index:-1; height:75px; width:225px;'><span style='position:absolute; left:100px; top:15px; color:gray; font-family:Helvetica; font-size:36px; user-select: none;'>Pass</span></div>");
  echo("<div style='position:absolute; top:117px; left:-150px; z-index:-4;'><img style='height:60px; width:170px;' src='./Images/p1APTracker.png' /><span style='position:absolute; left:85; top:20; z-index:10; font-weight:550; font-size:30px;'>" . $actionPoints . "AP" . "</span>");
  echo(($manualMode ? "<span style='position:absolute; top:85%; right:7%; display: inline-block;';>" . CreateButton($playerID, "-1", 10004, 0, "20px") . CreateButton($playerID, "+1", 10002, 0, "20px") . "</span>": ""));
  echo("</div></div>");

  //Now display the screen for this turn
  echo("<span style='position:fixed;  bottom:0px; left:15%; right:15%; z-index:10; display:inline-block; justity-content: center; font-size:30px; text-align:center;'>");


  echo(($manualMode ? "Add to hand: <input id='manualAddCardToHand' type='text' /><input type='button' value='Add' onclick='AddCardToHand()' />" : ""));

  //Tell the player what to pick
  if($turn[0] != "OVER")
  {
    if($currentPlayer != $playerID)
    {
      echo("<span style='display:inline-block; background-color: " . $backgroundColor . "; border: 2px solid " . $borderColor . "; border-radius: 3px; font-size:26px;'><img height='25px;' style='margin-left:3px; vertical-align: -3px;' title='" . $readyText . "' src='./HostFiles/" . $icon . "'/><b> Waiting for other player to choose " . TypeToPlay($turn[0]) . "&nbsp</b></span>");
    }
    else
    {
      echo("<span style='display:inline-block; background-color: " . $backgroundColor . "; border: 2px solid " . $borderColor . "; border-radius: 3px; font-size:26px;'><img height='25px;' style='margin-left:3px; vertical-align: -3px;' title='" . $readyText . "' src='./HostFiles/" . $icon . "'/><b> " . GetPhaseHelptext() . "&nbsp");
      if($turn[0] == "P" || $turn[0] == "CHOOSEHANDCANCEL" || $turn[0] == "CHOOSEDISCARDCANCEL") echo(" (" . ($turn[0] == "P" ? $myResources[0] . " of " . $myResources[1] . " " : "") . "or " . CreateButton($playerID, "Cancel", 10000, 0, "20px") . ")");
      if(CanPassPhase($turn[0]))
      {
        //echo(" (or " . CreateButton($playerID, "Pass", 99, 0, "20px", "", "Hotkey: Space"));
        if($turn[0] == "B") echo(" (" . CreateButton($playerID, "Undo Block", 10001, 0, "20px") . " " . CreateButton($playerID, "Pass Block and Reactions", 101, 0, "20px", "", "Reactions will not be skipped if the opponent reacts") . ")");
        //echo(")");
      }
      //if(($turn[0] == "B" || $turn[0] == "D") && IsDominateActive()) echo("[Dominate is active]");
      echo("</b></span>");
    }
  }
  if(IsManualMode($playerID)) echo("&nbsp;&nbsp;" . CreateButton($playerID, "Turn Off Manual Mode", 26, $SET_ManualMode . "-0", "20px", "", "", true));
  echo("</span>");

  //Display Current Turn Effects
  $friendlyEffects = "";
  $opponentEffects = "";
  for($i=0; $i<count($currentTurnEffects); $i+=CurrentTurnPieces())
  {
    $effect = "";
    $border = ($playerID == $currentTurnEffects[$i+1] ? "2px solid blue" : "2px solid red");
    $cardID = explode("-", $currentTurnEffects[$i])[0];
    $cardID = explode(",", $cardID)[0];
    $effect .= "<div style='width:86px; height:66px; margin:2px; border:" . $border . ";'>";
    //$effect .= "<img style='object-fit: cover; height:100%; width:100%;' src='./crops/" . $cardID . "_cropped.png' />";
    $effect .= Card($cardID, "crops", 65, 0, 1);
    $effect .= "</div>";
    if($playerID == $currentTurnEffects[$i+1]) $friendlyEffects .= $effect;
    else $opponentEffects .= $effect;
  }
  //TODO: Make this better by refactoring the above to a function
  if(GetClassState($playerID, $CS_NextArcaneBonus) > 0) $friendlyEffects .= "<div title='Next arcane bonus: " . GetClassState($playerID, $CS_NextArcaneBonus) . "' style='width:86px; height:66px; margin:2px; border:2px solid blue;'>" . Card("CRU161", "crops", 67, 0, 0) . "</div>";
  if(GetClassState(($playerID == 1 ? 2 : 1), $CS_NextArcaneBonus) > 0) $opponentEffects .= "<div title='Next arcane bonus: " . GetClassState(($playerID == 1 ? 2 : 1), $CS_NextArcaneBonus) . "' style='width:86px; height:66px; margin:2px; border:2px solid red;'>" . Card("CRU161", "crops", 67, 0, 0) . "</div>";
  echo("<div style='position:fixed; height:100%; width:100px; left:0px; top:0px;'>");
  echo("<div style='font-weight: bold; text-align:center; padding-bottom:4px; border-bottom: 4px solid ".$borderColor."; font-size:18px; font-weight: 600; color: ".$fontColor."; text-shadow: 2px 0 0 ".$borderColor.", 0 -2px 0 ".$borderColor.", 0 2px 0 ".$borderColor.", -2px 0 0 ".$borderColor.";'>Opponent<br>Effects</div>");
  echo($opponentEffects);
  echo("<div style='bottom:0px; position:absolute;'>");
  echo($friendlyEffects);
  echo("<div style='font-weight: bolder; width:100px; text-align:center; padding-top:4px; border-top: 4px solid ".$borderColor."; font-size:18px; font-weight: 600; color: ".$fontColor."; text-shadow: 2px 0 0 ".$borderColor.", 0 -2px 0 ".$borderColor.", 0 2px 0 ".$borderColor.", -2px 0 0 ".$borderColor.";'>Your<br>Effects</div>");
  echo("</div>");
  echo("</div>");


  $displayCombatChain = count($combatChain) > 0;

  if($displayCombatChain)
  {
    $totalAttack = 0;
    $totalDefense = 0;
    $chainAttackModifiers = [];
    EvaluateCombatChain($totalAttack, $totalDefense, $chainAttackModifiers);
    echo(CreatePopup("attackModifierPopup", [], 1, 0, "Attack Modifiers", 1, AttackModifiers($chainAttackModifiers)));
  }

  echo("<div style='position:absolute; left:300px; top:230px; z-index:0;'>");

  //Display the combat chain
    echo("<table><tr>");
  if($displayCombatChain)
  {
    echo("<td style='font-size:28px; font-weight:bold;'>$totalAttack</td>");
    echo("<td><img onclick='ShowPopup(\"attackModifierPopup\");' style='cursor:pointer; height:30px; width:30px; display:inline-block;' src='./Images/Attack.png' /></td>");
    echo("<td><img style='height:30px; width:30px; display:inline-block;' src='./Images/Defense.png' /></td>");
    echo("<td style='font-size:28px; font-weight:bold;'>$totalDefense</td>");
    $damagePrevention = GetDamagePrevention($defPlayer);
    if($damagePrevention > 0) echo("<td style='font-size:24px; font-weight:bold;'>&nbsp;<div title='$damagePrevention damage prevention' style='cursor:default; height:36px; width:36px; display:inline-block; font-size:30px; background-image: url(\"./Images/damagePrevention.png\"); background-size:cover;'>" . GetDamagePrevention($defPlayer) . "</div></td>");
    if(IsDominateActive()) echo("<td style='font-size:24px; font-weight:bold;'><img style='height:40px; display:inline-block;' src='./Images/dominate.png' /></td>");
    if(DoesAttackHaveGoAgain()) echo("<td><img title='This attack has Go Again.' style='height:30px; width:30px; display:inline-block;' src='./Images/goAgain.png' /></td>");
    //if($lastPlayed[3] == "FUSED") echo("<td><img title='This card was fused.' style='height:30px; width:30px; display:inline-block;' src='./Images/fuse2.png' /></td>");
  }
    echo("<td>");
    for($i=0; $i<count($chainLinks); ++$i)
    {
      if($i==0) { $iconLeft = 10; $linkWidth = 60; $linkImage = "chainLinkLeft.png"; }
      //else if($i==count($chainLinks)-1) { $iconLeft = 27; $linkWidth = 60; $linkImage = "chainLinkRight.png"; }
      else { $iconLeft = 25; $linkWidth = 70; $linkImage = "chainLink.png"; }
      echo("<div style='position:relative; display:inline-block;'><img title='Chain Link $i' style='height:30px; width:" . $linkWidth . "px;' src='./Images/$linkImage'>");
      $damage = $chainLinkSummary[$i * ChainLinkSummaryPieces()];
      $linkOverlay = ($damage > 0 ? "./Images/hit.png" : "./Images/Defense.png");
      $linkTitle = ($damage > 0 ? "Hit for $damage damage" : "Fully Blocked");
      echo("<div title='$linkTitle' style='position:absolute; left:" . $iconLeft . "px; top:4px;'><img style='width:22px; height:22px;' src='$linkOverlay' /></div>");
      echo("</img></div>");
    }
    if(count($chainLinks) > 0)
    {
      echo("<div title='Break the Combat Chain' " . ProcessInputLink($playerID, 100, 0) . " class='breakChain' style='height:30px; width:60px; position:relative; display:inline-block;'></div>");
    }
    echo("</td>");
    echo("</tr></table>");
  if($displayCombatChain)
  {
    for($i=0; $i<count($combatChain); $i+=CombatChainPieces()) {
      $action = $currentPlayer == $playerID && $turn[0] != "P" && $currentPlayer == $combatChain[$i+1] && AbilityPlayableFromCombatChain($combatChain[$i]) && IsPlayable($combatChain[$i], $turn[0], "PLAY", $i) ? 21 : 0;
      $actionDisabled = 0;
      echo(Card($combatChain[$i], "concat", $cardSize, $action, 1, $actionDisabled, $combatChain[$i+1] == $playerID ? 1 : 2, 0, strval($i)));
    }
  }

  echo("</div>");//Combat chain div


  //if($turn[0] == "INSTANT" && ($playerID == $turn[1] || count($layers) > 0))
  if(count($layers) > 0)
  {
    $content = "";
    $content .= "<div style='font-size:24px; margin-left:5px; margin-bottom:5px;'><b>Layers</b>&nbsp;<i style='font-size:16px; margin-right: 5px;'>(Priority settings can be adjusted in the menu)</i></div>";
    if(CardType($layers[0]) == "AA" || IsWeapon($layers[0]))
    {
      $attackTarget = GetAttackTarget();
      if($attackTarget != "NA")
      {
        $content .= "&nbsp;Attack Target: " . GetMZCardLink($defPlayer, $attackTarget);
      }
    }
    $content .= "<div style='margin-left:2px; margin-bottom:2px' display:inline;'>";
    for($i=count($layers)-LayerPieces(); $i>=0; $i-=LayerPieces())
    {
      $content .= Card($layers[$i], "concat", $cardSize, 0, 1, 0, $layers[$i+1] == $playerID ? 1 : 2);
    }
    $content .= "</div>";
    echo CreatePopup("INSTANT", [], 0, 1, "", 1, $content, "./", false, true);
  }

  if($turn[0] == "OVER")
  {
    $content = CreateButton($playerID, "Main Menu", 100001, 0, "20px", "", "", false, true);
    if($playerID == 1) $content .= "&nbsp;" . CreateButton($playerID, "Rematch", 100004, 0, "20px");
    if($playerID == 1) $content .= "&nbsp;" . CreateButton($playerID, "Quick Rematch", 100000, 0, "20px");
    $content .= CardStats($playerID);
    echo CreatePopup("OVER", [], 0, 1, "Player " . $winner . " Won! ", 1, $content, "./", true);
  }

  if($turn[0] == "DYNPITCH" && $turn[1] == $playerID)
  {
    $content = "";
    $content .= "<div display:inline;'>";
    $options = explode(",", $turn[2]);
    for($i=0; $i<count($options); ++$i)
    {
      $content .= CreateButton($playerID, $options[$i], 7, $options[$i], "20px");
    }
    $content .= "</div>";
    echo CreatePopup("DYNPITCH", [], 0, 1, "Choose " . TypeToPlay($turn[0]), 1, $content);
  }

  if(($turn[0] == "BUTTONINPUT" || $turn[0] == "CHOOSEARCANE" || $turn[0] == "BUTTONINPUTNOPASS") && $turn[1] == $playerID)
  {
    $content = "<div display:inline;'>";
    if($turn[0] == "CHOOSEARCANE")
    {
      $vars = explode("-", $dqVars[0]);
      $content .= "<div>Source: " . CardLink($vars[1], $vars[1]) . " Total Damage: " . $vars[0] . "</div>";
    }
    $options = explode(",", $turn[2]);
    for($i=0; $i<count($options); ++$i)
    {
      $content .= CreateButton($playerID, str_replace("_", " ", $options[$i]), 17, strval($options[$i]), "20px");
    }
    $content .= "</div>";
    echo CreatePopup("BUTTONINPUT", [], 0, 1, GetPhaseHelptext(), 1, $content);
  }

  if($turn[0] == "YESNO" && $turn[1] == $playerID)
  {
    $content = CreateButton($playerID, "Yes", 20, "YES", "14px");
    $content .= CreateButton($playerID, "No", 20, "NO", "14px");
    if(GetDQHelpText() != "-") $caption = implode(" ", explode("_", GetDQHelpText()));
    else $caption = "Choose " . TypeToPlay($turn[0]);
    echo CreatePopup("YESNO", [], 0, 1, $caption, 1, $content);
  }

  if(($turn[0] == "OPT" || $turn[0] == "CHOOSETOP" || $turn[0] == "CHOOSEBOTTOM" || $turn[0] == "CHOOSECARD") && $turn[1] == $playerID)
  {
    $content = "<table><tr>";
    $options = explode(",", $turn[2]);
    for($i=0; $i<count($options); ++$i)
    {
      $content .= "<td>";
      $content .= "<table><tr><td>";
      $content .= Card($options[$i], "concat", $cardSize, 0, 1);
      $content .= "</td></tr><tr><td>";
      if($turn[0] == "CHOOSETOP" || $turn[0] == "OPT") $content .= CreateButton($playerID, "Top", 8, $options[$i], "14px");
      if($turn[0] == "CHOOSEBOTTOM" || $turn[0] == "OPT") $content .= CreateButton($playerID, "Bottom", 9, $options[$i], "14px");
      if($turn[0] == "CHOOSECARD") $content .= CreateButton($playerID, "Choose", 23, $options[$i], "14px");
      $content .= "</td></tr>";
      $content .= "</table>";
      $content .= "</td>";
    }
    $content .= "</tr></table>";
    echo CreatePopup("OPT", [], 0, 1, "Choose " . TypeToPlay($turn[0]), 1, $content);
  }

  if($turn[0] == "HANDTOPBOTTOM" && $turn[1] == $playerID)
  {
    $content = "<table><tr>";
    for($i=0; $i<count($myHand); ++$i)
    {
      $content .= "<td>";
      $content .= Card($myHand[$i], "concat", $cardSize, 0, 1);
      $content .= "</td>";
    }
    $content .= "</tr><tr>";
    for($i=0; $i<count($myHand); ++$i)
    {
      $content .= "<td><span>";
      $content .= CreateButton($playerID, "Top", 12, $i, "14px");
      $content .= "</span><span>";
      $content .= CreateButton($playerID, "Bottom", 13, $i, "14px");
      $content .= "</span>";
      $content .= "</td>";
    }
    $content .= "</tr></table>";
    echo CreatePopup("HANDTOPBOTTOM", [], 0, 1, "Choose " . TypeToPlay($turn[0]), 1, $content);
  }

  if(($turn[0] == "MAYCHOOSEMULTIZONE" || $turn[0] == "CHOOSEMULTIZONE") && $turn[1] == $playerID)
  {
    $content = "";
    $content .= "<div display:inline;'>";
    $options = explode(",", $turn[2]);
    $otherPlayer = $playerID == 2 ? 1 : 2;
    $theirAllies = &GetAllies($otherPlayer);
    $myAllies = &GetAllies($playerID);
    for($i=0; $i<count($options); ++$i)
    {
      $option = explode("-", $options[$i]);
      if($option[0] == "MYAURAS") $source = $myAuras;
      else if($option[0] == "THEIRAURAS") $source = $theirAuras;
      else if($option[0] == "THEIRALLY") $source = $theirAllies;
      else if($option[0] == "THEIRARS") $source = $theirArsenal;
      else if($option[0] == "MYCHAR") $source = $myCharacter;
      else if($option[0] == "THEIRCHAR") $source = $theirCharacter;
      else if($option[0] == "MYITEMS") $source = $myItems;
      else if($option[0] == "LAYER") $source = $layers;
      else if($option[0] == "MYHAND") $source = $myHand;
      else if($option[0] == "MYDISCARD") $source = $myDiscard;
      else if($option[0] == "MYALLY") $source = $myAllies;
      $card = $source[intval($option[1])];
      if($option[0] == "THEIRARS" && $theirArsenal[$option[1]+1] == "DOWN") $card = "CardBack";
      $content .= Card($card, "concat", $cardSize, 16, 1, 0, 0, 0, $options[$i]);
    }
    $content .= "</div>";
    echo CreatePopup("CHOOSEMULTIZONE", [], 0, 1, GetPhaseHelptext(), 1, $content);
  }

  if($turn[0] == "CHOOSEDECK" && $turn[1] == $playerID)
  {
    ChoosePopup($myDeck, $turn[2], 11, "Choose a card from your deck");
  }

  if($turn[0] == "CHOOSEBANISH" && $turn[1] == $playerID)
  {
    ChoosePopup($myBanish, $turn[2], 16, "Choose a card from your banish", BanishPieces());
  }

  if(($turn[0] == "MAYCHOOSEARSENAL" || $turn[0] == "CHOOSEARSENAL" || $turn[0] == "CHOOSEARSENALCANCEL") && $turn[1] == $playerID)
  {
    ChoosePopup($myArsenal, $turn[2], 16, "Choose a card from your arsenal", ArsenalPieces());
  }

  if(($turn[0] == "CHOOSEPERMANENT" || $turn[0] == "MAYCHOOSEPERMANENT") && $turn[1] == $playerID)
  {
    $myPermanents = &GetPermanents($playerID);
    ChoosePopup($myPermanents, $turn[2], 16, GetPhaseHelptext(), PermanentPieces());
  }

  if(($turn[0] == "CHOOSETHEIRHAND") && $turn[1] == $playerID)
  {
    ChoosePopup($theirHand, $turn[2], 16, "Choose a card from your opponent hand");
  }

  if(($turn[0] == "CHOOSETHEIRAURA") && $turn[1] == $playerID)
  {
    ChoosePopup($theirAuras, $turn[2], 16, "Choose one of your opponent auras");
  }

  if(($turn[0] == "CHOOSEDISCARD" || $turn[0] == "MAYCHOOSEDISCARD" || $turn[0] == "CHOOSEDISCARDCANCEL") && $turn[1] == $playerID)
  {
    $caption = "Choose a card from your discard";
    if(GetDQHelpText() != "-") $caption = implode(" ", explode("_", GetDQHelpText()));
    ChoosePopup($myDiscard, $turn[2], 16, $caption);
  }

  if(($turn[0] == "MAYCHOOSETHEIRDISCARD") && $turn[1] == $playerID)
  {
    ChoosePopup($theirDiscard, $turn[2], 16, "Choose a card from your opponent discard");
  }

  if($turn[0] == "CHOOSECOMBATCHAIN" && $turn[1] == $playerID)
  {
    ChoosePopup($combatChain, $turn[2], 16, "Choose a card from the combat chain", CombatChainPieces());
  }

  if($turn[0] == "CHOOSECHARACTER" && $turn[1] == $playerID)
  {
    ChoosePopup($myCharacter, $turn[2], 16, "Choose a card from your character/equipment", CharacterPieces());
  }

  if($turn[0] == "CHOOSETHEIRCHARACTER" && $turn[1] == $playerID)
  {
    ChoosePopup($theirCharacter, $turn[2], 16, "Choose a card from your opponent character/equipment", CharacterPieces());
  }

  if($turn[0] == "CHOOSETHEIRITEM" && $turn[1] == $playerID)
  {
    ChoosePopup($theirItems, $turn[2], 16, "Choose one of your opponent items", ItemPieces());
  }

  if($turn[0] == "PDECK" && $currentPlayer == $playerID)
  {
    $content = "";
    for($i=0; $i<count($myPitch); $i+=1) {
      $content .= Card($myPitch[$i], "concat", $cardSize, 6, 1);
    }
    echo CreatePopup("PITCH", [], 0, 1, "Choose a card from your Pitch Zone to add to the bottom of your deck", 1, $content);
  }

  if(($turn[0] == "MULTICHOOSETHEIRDISCARD" || $turn[0] == "MULTICHOOSEDISCARD" || $turn[0] == "MULTICHOOSEHAND" || $turn[0] == "MULTICHOOSEDECK" || $turn[0] == "MULTICHOOSETEXT" || $turn[0] == "MULTICHOOSETHEIRDECK") && $currentPlayer == $playerID)
  {
    $content = "";
    echo("<div display:inline;'>");
    $params = explode("-", $turn[2]);
    $options = explode(",", $params[1]);
    $caption = "<h3>Choose up to " . $params[0] . " card" . ($params[0] > 1 ? "s." : ".") . "<h3>";
    if(GetDQHelpText() != "-") $caption = "<h3>" . implode(" ", explode("_", GetDQHelpText())) . "</h3>";
    $content .= CreateForm($playerID, "Submit", 19, count($options));
    $content .= "<table><tr>";
    for($i=0; $i<count($options); ++$i)
    {
      $content .= "<td>";
      $content .= CreateCheckbox($i, strval($options[$i]));
      $content .= "</td>";
    }
    $content .= "</tr><tr>";
    for($i=0; $i<count($options); ++$i)
    {
      $content .= "<td>";
      if($turn[0] == "MULTICHOOSEDISCARD") $content .= Card($myDiscard[$options[$i]], "CardImages", $bigCardSize);
      else if($turn[0] == "MULTICHOOSETHEIRDISCARD") $content .= Card($theirDiscard[$options[$i]], "CardImages", $bigCardSize);
      else if($turn[0] == "MULTICHOOSEHAND") $content .= Card($myHand[$options[$i]], "CardImages", $bigCardSize);
      else if($turn[0] == "MULTICHOOSEDECK") $content .= Card($myDeck[$options[$i]], "CardImages", $bigCardSize);
      else if($turn[0] == "MULTICHOOSETHEIRDECK") $content .= Card($theirDeck[$options[$i]], "CardImages", $bigCardSize);
      else if($turn[0] == "MULTICHOOSETEXT") $content .= str_replace("_", " ", $options[$i]);
      $content .= "</td>";
    }
    $content .= "</tr></table>";
    $content .= "</form>";
    $content .= "</div>";
    echo CreatePopup("MULTICHOOSE", [], 0, 1, $caption, 1, $content);
  }

  //Display opponent's stuff
  $theirBanishDisplay = GetTheirBanishForDisplay();
  echo(CreatePopup("theirPitchPopup", $theirPitch, 1, 0, "Opponent's Pitch Zone"));
  echo(CreatePopup("theirDiscardPopup", $theirDiscard, 1, 0, "Opponent's Discard Zone"));
  echo(CreatePopup("theirBanishPopup", $theirBanishDisplay, 1, 0, "Opponent's Banish Zone"));
  if(count($theirSoul) > 0) echo(CreatePopup("theirSoulPopup", $theirSoul, 1, 0, "Opponent's Soul"));

  //Opponent hand
  echo("<div style='position: fixed; top: 0px; left: calc(50% + 135px); height: 50px; display:inline;'><span style='margin-top: -30px; margin-right: 2px; height:100%; text-align: center; font-size:16px; font-weight: 550; color: ".$fontColor."; text-shadow: 2px 0 0 ".$borderColor.", 0 -2px 0 ".$borderColor.", 0 2px 0 ".$borderColor.", -2px 0 0 ".$borderColor."; vertical-align:middle; display:inline-block;'>Opponent<br>Hand:</span>");
  for($i=0; $i<count($theirHand); ++$i) {
    echo(Card("cardBack", "CardImages", 50, 0, 0, 0, -1));
  }
  if(count($theirSoul) > 0) echo("<div title='Click to view the cards in your opponent Soul.' style='padding-left:5px; cursor:pointer; position:relative; display:inline-block; height:50px; font-size:20; text-align:center;' onclick='ShowPopup(\"theirSoulPopup\");'><img style='height:50px; width:50px;' src='./Images/soulIcon.png'></img>
  <div style='position:relative; top:-20px; font-size:18px; font-weight: 600; color: ".$fontColor."; text-shadow: 2px 0 0 ".$borderColor.", 0 -2px 0 ".$borderColor.", 0 2px 0 ".$borderColor.", -2px 0 0 ".$borderColor.";
  display:inline-block;'>" . count($theirSoul) . " cards</div></div>");
  echo("</div>");


  //Show deck, discard, pitch, banish
  //Display Their Discard
  echo("<div title='Click to view the cards in your opponent's Graveyard.' style='cursor:pointer; position:fixed; right:" . GetZoneRight("DISCARD") . "; top:" . GetZoneTop("THEIRDISCARD") .";' onclick='ShowPopup(\"theirDiscardPopup\");'>");
  $card = (count($theirDiscard) > 0 ? $theirDiscard[count($theirDiscard)-1] : $blankZone);
  echo(Card($card, "concat", $cardSizeAura, 0, 0, 0, 0, count($theirDiscard)));
  echo("</div>");

  //Display Their Deck
  echo("<div style='position:fixed; right:" . GetZoneRight("DECK") . "; top:" . GetZoneTop("THEIRDECK") .";'>");
  echo(($manualMode ? "<span style='position:absolute; left:13px; bottom:0px; z-index:1000;'>" . CreateButton($playerID, "Draw", 10010, 0, "20px") . "</span>": ""));
  $deckImage = (count($theirDeck) > 0 ? "cardBack" : $blankZone);
  echo(Card($deckImage, "concat", $cardSizeAura, 0, 0, 0, 0, count($theirDeck)));
  echo("</div>");

  //Display Their Banish
  echo("<div style='position:fixed; right:" . GetZoneRight("BANISH") . "; top:" . GetZoneTop("THEIRBANISH") .";'>");
  $card = (count($theirBanish) > 0 ? ($theirBanish[count($theirBanish)-BanishPieces()+1] == "INT" ? "cardBack": $theirBanish[count($theirBanish)-BanishPieces()]) : $blankZone);
  echo(Card($card, "concat", $cardSizeAura, 0, 0, 0, 0));
  if(TalentContains($theirCharacter[0], "SHADOW"))
  {
    $theirBD = SearchCount(SearchBanish(($playerID == 1 ? 2 : 1), "", "", -1, -1, "", "", true));
    $bdImage = IsImmuneToBloodDebt(($playerID == 1 ? 2 : 1)) ? "bloodDebtImmune2.png" : "bloodDebt2.png";
    echo("<img title='Blood Debt' style='position:absolute; top:20px; left:-45px; width:34px;' src='./Images/" . $bdImage . "'><div style='position:absolute; top:41px; left:-44px; width:34px; font-size:24px; font-weight:500; text-align:center;'>" . $theirBD . "</div></img>");
  }


  echo("<span title='Click to see your opponent Banish Zone.' onclick='ShowPopup(\"theirBanishPopup\");' style='left:" . $cardIconLeft . "px; top:" . $cardIconTop . "px; cursor:pointer; position:absolute; display:inline-block;'>
  <img style=' opacity:0.8; height:". $cardIconSize ."; width:". $cardIconSize ."; display: block; margin-left: auto; margin-right: auto;' src='./Images/banish.png'>
  <div style='margin: 0; top: 50%; left: 50%; margin-right: -50%; width: 28px; height: 28px; padding: 3px;
  text-align: center; transform: translate(-50%, -50%);
  position:absolute; z-index: 5; font-size:26px; font-weight: 600; color: #EEE; text-shadow: 3px 0 0 #000, 0 -2px 0 #000, 0 2px 0 #000, -2px 0 0 #000;'>" . count($theirBanish)/BanishPieces() . "</div></img></span>");

  echo("</div>");

  //Display Their Pitch
  echo("<div style='position:fixed; right:" . GetZoneRight("PITCH") . "; top:" . GetZoneTop("THEIRPITCH") .";'>");
  $card = (count($theirPitch) > 0 ? $theirPitch[count($theirPitch)-PitchPieces()] : $blankZone);
  echo(Card($card, "concat", $cardSizeAura, 0, 0));
  echo("<span title='Click to see your opponent Pitch Zone.' onclick='ShowPopup(\"theirPitchPopup\");' style='left:" . $cardIconLeft . "px; top:" . $cardIconTop . "px; cursor:pointer; position:absolute; display:inline-block;'><img style='opacity:0.8; height:". $cardIconSize ."; width:". $cardIconSize .";' src='./Images/Resource.png'>
  <div style='margin: 0; top: 50%; left: 50%; margin-right: -50%; width: 28px; height: 28px; padding: 3px;
  text-align: center; transform: translate(-50%, -50%);
  position:absolute; z-index: 5; font-size:26px; font-weight: 600; color: #EEE; text-shadow: 3px 0 0 #000, 0 -2px 0 #000, 0 2px 0 #000, -2px 0 0 #000;'>" . $theirResources[0] . "</div></img></span>");
  echo(($manualMode ? "<span style='position:absolute; top: 62%; right:3%;'>" . CreateButton($playerID, "-1", 10014, 0, "20px") . CreateButton($playerID, "+1", 10013, 0, "20px") . "</span>": ""));
  echo("</div>");



  //Now display their Auras and Items
  if(count($landmarks) > 0)
  {
    echo("<div style='position: fixed; top:105px; left: calc(50% + 200px); display:inline;'>");
    echo("<h3>Landmark:</h3>");
    for($i=0; $i<count($landmarks); $i+=LandmarkPieces())
    {
      $playable = IsPlayable($landmarks[$i], $turn[0], "PLAY", $i, $restriction);
      $action = ($playable && $currentPlayer == $playerID ? 25 : 0) ;
      $border = CardBorderColor($landmarks[$i], "PLAY", $playable);
      $counters = 0;
      echo(Card($landmarks[$i], "concat", $cardSizeAura, $action, 1, 0, $border, $counters, strval($i), "", true));
    }
    echo("</div>");
  }

  $permTop = 10;
  $theirPermHeight = $cardSize + 80;
  echo("<div style='overflow-y:auto; position: fixed; top:" . $permTop . "px; left:" . $permLeft . "px; height:200px; width:" . $permWidth . "; height:" . $theirPermHeight . "px;'>");
  DisplayTiles(($playerID == 1 ? 2 : 1));
  if(count($theirAuras) > 0)
  {
    for($i=0; $i<count($theirAuras); $i+=AuraPieces())
    {
      if(IsTileable($theirAuras[$i])) continue;
      $counters = $theirAuras[$i+2] > 0 ? $theirAuras[$i+2] : $theirAuras[$i+3];//TODO: Show both
      echo(Card($theirAuras[$i], "concat", $cardSizeAura, 0, 1, $theirAuras[$i+1] != 2 ? 1 : 0, 0, $counters) . "&nbsp");
    }
  }
  if(count($theirItems) > 0)
  {
    for($i=0; $i<count($theirItems); $i+=ItemPieces())
    {
      if(IsTileable($theirItems[$i])) continue;
      echo(Card($theirItems[$i], "concat", $cardSizeAura, 0, 1, $theirItems[$i+2] !=2 ? 1 : 0, 0, $theirItems[$i+1]) . "&nbsp");
    }
  }
  if($playerID == 3)
  {
    $otherPlayer = $playerID == 2 ? 2 : 1;
  } else {
    $otherPlayer = $playerID == 2 ? 1 : 2;
  }
  $theirAllies = GetAllies($otherPlayer);
  if(count($theirAllies) > 0)
  {
    for($i=0; $i<count($theirAllies); $i+=AllyPieces())
    {
      echo("<div style='position:relative; display:inline;'>");
      echo(Card($theirAllies[$i], "concat", $cardSizeAura, 0, 1, $theirAllies[$i+1] !=2 ? 1 : 0, 0, $theirAllies[$i+6], "", "", False, $theirAllies[$i+2]) . "&nbsp");
      if($theirAllies[$i+3] == 1) echo("<img title='Frozen' style='position:absolute; z-index:100; top:-77px; left:6px; height:" . $cardHeight . "; width:" . $cardWidth . ";' src='./Images/frozenOverlay.png' />");
      echo("</div>");
    }
  }
  $theirPermanents = &GetPermanents($otherPlayer);
  if(count($theirPermanents) > 0)
  {
    for($i=0; $i<count($theirPermanents); $i+=PermanentPieces())
    {
      //if(IsTileable($theirPermanents[$i])) continue;
      //$playable = ($currentPlayer == $playerID ? IsPlayable($theirPermanents[$i], $turn[0], "PLAY", $i, $restriction) : false);
      //$border = CardBorderColor($theirPermanents[$i], "PLAY", $playable);
      echo(Card($theirPermanents[$i], "concat", $cardSizeAura, 0, 1));
    }
  }
  $theirAllies = GetAllies($otherPlayer);
  if(count($theirAllies) > 0)
  {
    for($i=0; $i<count($theirAllies); $i+=AllyPieces())
    {
      echo(Card($theirAllies[$i], "concat", $cardSizeAura, 0, 1, $theirAllies[$i+1] !=2 ? 1 : 0, 0, $theirAllies[$i+2], "", "", False, True) . "&nbsp");
      if($theirAllies[$i+3] == 1) echo("<img title='Frozen' style='position:absolute; z-index:100; top:5px; left:6px; height:" . $cardHeight . "; width:" . $cardWidth . ";' src='./Images/frozenOverlay.png' />");
    }
  }
    echo("</div>");

  //Now display their character and equipment
  $numWeapons = 0;
  for($i=0; $i<count($theirCharacter); $i+=CharacterPieces())
  {
    $type = CardType($theirCharacter[$i]);//NOTE: This is not reliable type
    $sType = CardSubType($theirCharacter[$i]);
    if($type == "W") { ++$numWeapons; if($numWeapons > 1) {$type = "E"; $sType = "Off-Hand";} }
    $counters = CardType($theirCharacter[$i]) == "W" ? $theirCharacter[$i+3] : $theirCharacter[$i+4];
    if($theirCharacter[$i+2] > 0) $counters = $theirCharacter[$i+2];//TODO: display both kinds of counters?
    echo("<div style='z-index:5; position:fixed; left:" . GetCharacterLeft($type, $sType) . "; top:" . GetCharacterTop($type, $sType) .";'>");
    echo(Card($theirCharacter[$i], "concat", $cardSizeEquipment, 0, 1, $theirCharacter[$i+1] !=2 ? 1 : 0, 0, $counters));
    if($theirCharacter[$i+8] == 1) echo("<img title='Frozen' style='position:absolute; z-index:100; top:5px; left:6px; height:" . $cardHeight . "; width:" . $cardWidth . ";' src='./Images/frozenOverlay.png' />");
    if($theirCharacter[$i+6] == 1) echo("<img title='On Combat Chain' style='position:absolute; z-index:100; top:-25px; left:5px; width:" . $cardWidth . "' src='./Images/onChain.png' />");
    if($theirCharacter[$i+1] == 0) echo("<img title='Equipment Broken' style='position:absolute; z-index:100; width:" . $cardEquipmentWidth . "; bottom: 8px; left:14px;' src='./Images/brokenEquip.png' />");
    echo("</div>");
  }
  echo("</div>");
  //Now display their arsenal
  if($theirArsenal != "")
  {
    echo("<div title='Your opponent's Arsenal' style='position: fixed; top:0px; left:" . GetCharacterLeft("C", "") . "; display:inline-block;'>");
    for($i=0; $i<count($theirArsenal); $i+=ArsenalPieces())
    {
      echo("<div style='position:relative; display:inline;'>");
      if($theirArsenal[$i+1] == "UP") echo(Card($theirArsenal[$i], "concat", $cardSizeAura, 0, 1, $theirArsenal[$i+2] == 0 ? 1 : 0, 0, $theirArsenal[$i+3]));
      else echo(Card("cardBack", "concat", $cardSizeAura, 0, 0));
      if($theirArsenal[$i+4] == 1) echo("<img title='Frozen' style='position:absolute; z-index:100; top:-77px; left:6px; height:" . $cardHeight . "; width:" . $cardWidth . ";' src='./Images/frozenOverlay.png' />");
      echo("</div>");
    }
    echo("</div>");
  }

  echo(CreatePopup("myPitchPopup", $myPitch, 1, 0, "Your Pitch"));
  echo(CreatePopup("myDiscardPopup", $myDiscard, 1, 0, "Your Discard"));
  echo(CreatePopup("myBanishPopup", [], 1, 0, "Your Banish", 1, BanishUI()));
  echo(CreatePopup("myStatsPopup", [], 1, 0, "Your Game Stats", 1, CardStats($playerID), "./", true));
  echo(CreatePopup("menuPopup", [], 1, 0, "Main Menu", 1, MainMenuUI(), "./", true));
  if(count($mySoul) > 0) echo(CreatePopup("mySoulPopup", $mySoul, 1, 0, "My Soul"));

  $restriction = "";
  $actionType = "HAND";
  if(strpos($turn[0], "CHOOSEHAND") !== false && $turn[0] != "MULTICHOOSEHAND") $actionType = 16;
  $handLeft = "calc(50% - " . ((count($myHand) * ($cardWidth + 15))/2) . "px)";
  echo("<div style='position:fixed; left:" . $handLeft . "; bottom: 35px;'>");//Hand div
  for($i=0; $i<count($myHand); ++$i) {
    if($playerID == 3)
    {
      echo(Card("cardBack", "concat", $cardSizeAura, 0, 0, 0, 0));
    }
    else
    {
      $playable = $turn[0] == "ARS" || IsPlayable($myHand[$i], $turn[0], "HAND", -1, $restriction) || ($actionType == 16 && strpos("," . $turn[2] . ",", "," . $i . ",") !== false);
      $border = CardBorderColor($myHand[$i], "HAND", $playable);
      $actionData = $actionType == 16 ? strval($i) : "";
      echo("<span style='position:relative; margin:1px;'>");
      echo(Card($myHand[$i], "concat", $cardSizeAura, $currentPlayer == $playerID && $playable ? $actionType : 0, 1 , 0, $border, 0, $actionData));
      if($restriction != "") echo("<img title='Restricted by " . CardName($restriction) . "' style='position:absolute; z-index:100; top:-57px; left:25px;' src='./Images/restricted.png' />");
      echo("</span>");
    }
  }
  echo(BanishUI("HAND"));
  echo("</div>");//End hand div

  //Now display arsenal
  if(count($myArsenal) > 0)
  {
    $arsenalLeft = (count($myArsenal) == ArsenalPieces() ? "calc(50% - " . (intval($cardWidth/2) + 6) . "px)" : "calc(50% - " . (intval($cardWidth) + 14) . "px)");
    echo("<div style='position:fixed; left:" . $arsenalLeft . "; bottom:" . (intval(GetCharacterBottom("C", "")) - $cardSize + 18) . "px;'>");//arsenal div
    for($i=0; $i<count($myArsenal); $i+=ArsenalPieces())
    {
      echo("<div style='position:relative; display:inline-block'>");
      if($playerID == 3)
      {
        echo(Card("cardBack", "concat", $cardSizeAura, 0, 0, 0, 0));
      }
      else
      {
        $playable = $turn[0] != "P" && IsPlayable($myArsenal[$i], $turn[0], "ARS", $i, $restriction);
        $border = CardBorderColor($myArsenal[$i], "ARS", $playable);
        $counters = $myArsenal[$i+3];
        echo("<div style='position:relative; margin:1px;>");
        echo(Card($myArsenal[$i], "concat", $cardSizeAura, $currentPlayer == $playerID && $playable ? "ARS" : 0, 1, $myArsenal[$i+2] > 0 ? 0 : 1, $border, $counters, strval($i)));
        $iconHeight = $cardSize / 2 - 15;
        $iconLeft = $cardWidth/2 - intval($iconHeight*.71/2) + 5;
        if($myArsenal[$i+1] == "UP") echo("<img style='position:absolute; left:" . $iconLeft . "px; bottom:3px; height:" . $iconHeight . "px; ' src='./Images/faceUp.png' title='This arsenal card is face up.'></img>");
        else echo("<img style='position:absolute; left:" . $iconLeft . "px; bottom:3px; height:" . $iconHeight . "px; ' src='./Images/faceDown.png' title='This arsenal card is face down.'></img>");
        echo("</div>");
      }
      if($myArsenal[$i+4] == 1) echo("<img title='Frozen' style='position:absolute; z-index:100; top:6px; left:7px; height:" . $cardHeight . "; width:" . $cardWidth . ";' src='./Images/frozenOverlay.png' />");
      echo("</div>");
    }
    echo("</div>");//End arsenal div
  }

  //Now display Auras and items
  $permTop = intval(GetCharacterBottom("C", "")) + $cardSize - 250;
  $permHeight = $cardSize + 100;
  echo("<div style='overflow-y:auto; position: fixed; Bottom:" . $permTop . "px; left:" . $permLeft . "px; width:" . $permWidth . "; max-height:" . $permHeight . "px;'>");
  DisplayTiles($playerID);
  if(count($myAuras) > 0)
  {
    for($i=0; $i<count($myAuras); $i+=AuraPieces())
    {
      if(IsTileable($myAuras[$i])) continue;
      $playable = ($currentPlayer == $playerID ? $myAuras[$i+1] == 2 && IsPlayable($myAuras[$i], $turn[0], "PLAY", $i, $restriction): false);
      $border = CardBorderColor($myAuras[$i], "PLAY", $playable);
      $counters = $myAuras[$i+2] > 0 ? $myAuras[$i+2] : $myAuras[$i+3];//TODO: Show both
      echo(Card($myAuras[$i], "concat", $cardSizeAura, $currentPlayer == $playerID && $turn[0] != "P" && $playable ? 22 : 0, 1, $myAuras[$i+1] != 2 ? 1 : 0, $border, $counters, strval($i)) . "&nbsp");
    }
  }
  if(count($myItems) > 0)
  {
    for($i=0; $i<count($myItems); $i+=ItemPieces())
    {
      if(IsTileable($myItems[$i])) continue;
      $playable = ($currentPlayer == $playerID ? IsPlayable($myItems[$i], $turn[0], "PLAY", $i, $restriction) : false);
      $border = CardBorderColor($myItems[$i], "PLAY", $playable);
      echo(Card($myItems[$i], "concat", $cardSizeAura, $currentPlayer == $playerID && $turn[0] != "P" && $playable ? 10 : 0, 1, $myItems[$i+2] !=2 ? 1 : 0, $border, $myItems[$i+1], strval($i)) . "&nbsp");
    }
  }
  $myAllies = GetAllies($playerID);
  if(count($myAllies) > 0)
  {
    for($i=0; $i<count($myAllies); $i+=AllyPieces())
    {
      echo("<div style='position:relative; display:inline;'>");
      $playable = IsPlayable($myAllies[$i], $turn[0], "PLAY", $i, $restriction) && $myAllies[$i+1] == 2;
      $border = CardBorderColor($myAllies[$i], "PLAY", $playable);
      echo(Card($myAllies[$i], "concat", $cardSizeAura, $currentPlayer == $playerID && $turn[0] != "P" && $playable ? 24 : 0, 1, $myAllies[$i+1] !=2 ? 1 : 0, $border, $myAllies[$i+6], strval($i), "", False, $myAllies[$i+2]) . "&nbsp");
      if($myAllies[$i+3] == 1) echo("<img title='Frozen' style='position:absolute; z-index:100; top:-77px; left:6px; height:" . $cardHeight . "; width:" . $cardWidth . ";' src='./Images/frozenOverlay.png' />");
      echo("</div>");
    }
  }
  $myPermanents = &GetPermanents($playerID);
  if(count($myPermanents) > 0)
  {
    for($i=0; $i<count($myPermanents); $i+=PermanentPieces())
    {
      //if(IsTileable($myPermanents[$i])) continue;
      //$playable = ($currentPlayer == $playerID ? IsPlayable($myPermanents[$i], $turn[0], "PLAY", $i, $restriction) : false);
      //$border = CardBorderColor($myPermanents[$i], "PLAY", $playable);
      echo(Card($myPermanents[$i], "concat", $cardSizeAura, 0, 1) . "&nbsp");
    }
  }
  $myAllies = GetAllies($playerID);
  if(count($myAllies) > 0)
  {
    for($i=0; $i<count($myAllies); $i+=AllyPieces())
    {
      $playable = IsPlayable($myAllies[$i], $turn[0], "PLAY", $i, $restriction) && $myAllies[$i+1] == 2;
      $border = CardBorderColor($myAllies[$i], "PLAY", $playable);
      echo(Card($myAllies[$i], "concat", $cardSizeAura, $currentPlayer == $playerID && $turn[0] != "P" && $playable ? 24 : 0, 1, $myAllies[$i+1] !=2 ? 1 : 0, $border, $myAllies[$i+2], strval($i), "", False, True) . "&nbsp");
      if($myAllies[$i+3] == 1) echo("<img title='Frozen' style='position:absolute; z-index:100; top:5px; left:6px; height:" . $cardHeight . "; width:" . $cardWidth . ";' src='./Images/frozenOverlay.png' />");
    }
  }
  echo("</div>");

  //Now display my character and equipment
  $numWeapons = 0;
  for($i=0; $i<count($myCharacter); $i+=CharacterPieces())
  {
    $counters = CardType($myCharacter[$i]) == "W" ? $myCharacter[$i+3] : $myCharacter[$i+4];
    if($myCharacter[$i+2] > 0) $counters = $myCharacter[$i+2];//TODO: Display both kinds of counters
    $playable = $myCharacter[$i+1] == 2 && IsPlayable($myCharacter[$i], $turn[0], "CHAR", $i, $restriction);
    $border = CardBorderColor($myCharacter[$i], "CHAR", $playable);
    $type = CardType($myCharacter[$i]);
    $sType = CardSubType($myCharacter[$i]);
    if($type == "W") { ++$numWeapons; if($numWeapons > 1) {$type = "E"; $sType = "Off-Hand";} }
    echo("<div style='position:absolute; z-index:100; left:" . GetCharacterLeft($type, $sType) . "; bottom:" . GetCharacterBottom($type, $sType) .";'>");
    echo(Card($myCharacter[$i], "concat", $cardSizeEquipment, $currentPlayer == $playerID && $playable ? "CHAR" : 0, 1, $myCharacter[$i+1] !=2 ? 1 : 0, $border, $counters, strval($i)));
    $effects = ActiveCharacterEffects($playerID, $i);
    if($effects != "") echo("<img title='Buffed by: $effects' style='position:absolute; z-index:100; top:40px; left:23px;' src='./Images/arsenal.png' />");
    if($restriction != "") {
      $restrictionName = CardName($restriction);
      echo("<img title='Restricted by: " . ($restrictionName != "" ? $restrictionName : $restriction) . "' style='position:absolute; z-index:100; top:25px; left:25px;' src='./Images/restricted.png' />");
    }
    if($myCharacter[$i+6] == 1) echo("<img title='On Combat Chain' style='position:absolute; z-index:100; width:" . $cardWidth . "; bottom: 5px; left:5px;' src='./Images/onChain.png' />");
    if($myCharacter[$i+1] == 0) echo("<img title='Equipment Broken' style='position:absolute; z-index:100; width:" . $cardEquipmentWidth . "; bottom: 8px; left:14px;' src='./Images/brokenEquip.png' />");
    if($myCharacter[$i+8] == 1) echo("<img title='Frozen' style='position:absolute; z-index:100; top:5px; left:6px; height:" . $cardHeight . "; width:" . $cardWidth . ";' src='./Images/frozenOverlay.png' />");
    if($type == "C")
    {
      if(CardTalent($myCharacter[0]) == "LIGHT" || count($mySoul) > 0) echo("<div onclick='ShowPopup(\"mySoulPopup\");' style='cursor:pointer; position:absolute; top:-23px; left: 17px; height:20px; font-size:20; font-weight: 600; color: ".$fontColor."; text-shadow: 2px 0 0 ".$borderColor.", 0 -2px 0 ".$borderColor.", 0 2px 0 ".$borderColor.", -2px 0 0 ".$borderColor."; text-align:center;'>Soul: " . count($mySoul) . "</div>");
    }
    echo("</div>");
    echo("</div>");
  }
  echo("</div>");

  //Show deck, discard, pitch, banish
  //Display My Discard
  echo("<div title='Click to view the cards in your Graveyard.' style='cursor:pointer; position:fixed; right:" . GetZoneRight("DISCARD") . "; bottom:" . GetZoneBottom("MYDISCARD") .";' onclick='ShowPopup(\"myDiscardPopup\");'>");
  $card = (count($myDiscard) > 0 ? $myDiscard[count($myDiscard)-1] : $blankZone);
  echo(Card($card, "concat", $cardSizeAura, 0, 0, 0, 0, count($myDiscard)));
  echo("</div>");

  //Display My Deck
  echo("<div style='position:fixed; right:" . GetZoneRight("DECK") . "; bottom:" . GetZoneBottom("MYDECK") .";'>");
  echo(($manualMode ? "<span style='position:absolute; left:13px; bottom:0px; z-index:1000;'>" . CreateButton($playerID, "Draw", 10009, 0, "20px") . "</span>": ""));
  $deckImage = (count($myDeck) > 0 ? "cardBack" : $blankZone);
  echo(Card($deckImage, "concat", $cardSizeAura, 0, 0, 0, 0, count($myDeck)));
  echo("</div>");

  //Display My Banish
  echo("<div style='position:fixed; right:" . GetZoneRight("BANISH") . "; bottom:" . GetZoneBottom("MYBANISH") .";'>");
  $card = (count($myBanish) > 0 ? $myBanish[count($myBanish)-BanishPieces()] : $blankZone);
  echo(Card($card, "concat", $cardSizeAura, 0, 0, 0, 0));
  echo("<span title='Click to see your Banish Zone.' onclick='ShowPopup(\"myBanishPopup\");' style='left:" . $cardIconLeft . "px; top:" . $cardIconTop . "px; cursor:pointer;
  position:absolute; display:inline-block;'><img style='opacity:0.8; height:". $cardIconSize ."; width:". $cardIconSize .";' src='./Images/banish.png'>
  <div style='margin: 0; top: 50%; left: 50%; margin-right: -50%; width: 28px; height: 28px; padding: 3px;
  text-align: center; transform: translate(-50%, -50%);
  position:absolute; z-index: 5; font-size:26px; font-weight: 600; color: #EEE; text-shadow: 3px 0 0 #000, 0 -2px 0 #000, 0 2px 0 #000, -2px 0 0 #000;'>" . count($myBanish)/BanishPieces() . "</div></img></span>");

  if(TalentContains($myCharacter[0], "SHADOW"))
  {
    $myBD = SearchCount(SearchBanish($playerID, "", "", -1, -1, "", "", true));
    $bdImage = IsImmuneToBloodDebt($playerID) ? "bloodDebtImmune2.png" : "bloodDebt2.png";
    echo("<img title='Blood Debt' style='position:absolute; top:18px; left:-40px; width:34px;' src='./Images/" . $bdImage . "'>
    <div style='position:absolute; top:38px; left:-39px; width:34px; font-size:24px; font-weight:500; text-align:center;'>" . $myBD . "</div></img>");
  }

  echo("</div>");

  //Display My Pitch
  echo("<div style='position:fixed; right:" . GetZoneRight("PITCH") . "; bottom:" . GetZoneBottom("MYPITCH") .";'>");
  $card = (count($myPitch) > 0 ? $myPitch[count($myPitch)-PitchPieces()] : $blankZone);
  echo(Card($card, "concat", $cardSizeAura, 0, 0));
  echo("<span title='Click to see your Pitch Zone.' onclick='ShowPopup(\"myPitchPopup\");' style='left:" . $cardIconLeft . "px; top:" . $cardIconTop . "px; cursor:pointer; position:absolute; display:inline-block;'><img style='opacity: 0.8; height:". $cardIconSize ."; width:". $cardIconSize .";' src='./Images/Resource.png'>
  <div style='margin: 0; top: 51%; left: 50%; margin-right: -50%; width: 28px; height: 28px; padding: 3px;
  text-align: center; transform: translate(-50%, -50%);
  position:absolute; z-index: 5; font-size:26px; font-weight: 600; color: #EEE; text-shadow: 3px 0 0 #000, 0 -2px 0 #000, 0 2px 0 #000, -2px 0 0 #000;'>" . $myResources[0] . "</div></img></span>");
  echo(($manualMode ? "<span style='position:absolute; bottom:0; right:3%;'>" . CreateButton($playerID, "-1", 10015, 0, "20px") . CreateButton($playerID, "+1", 10012, 0, "20px") . "</span>": ""));
  echo("</div>");

  echo("</div>");//End play area div

  //Display the log
  echo("<div style='position:fixed; width:200px; top:10px; bottom:10px; right:10px;'>");

  echo("<div style='position:relative; height:50px;'><div style='position:absolute; right:50px;'><table><tr><td>");
  echo("<div title='Click to view stats.' style='cursor:pointer;' onclick='ShowPopup(\"myStatsPopup\");'><img style='width:50px; height:50px;' src='./Images/stats.png' /></div>");
  echo("</td><td></td><td>");
  echo("<div title='Click to view the menu.' style='cursor:pointer;' onclick='ShowPopup(\"menuPopup\");'><img style='width:50px; height:50px;' src='./Images/menu.png' /></div>");
  echo("</td></tr></table></div></div>");

  echo("<div style='text-align:center; width:200px; font-weight:bold; font-size:24;'>Turn #" . $currentTurn . "</div>");
  echo("<div style='text-align:center; width:200px; font-weight:bold; font-size:16;'>Last Played</div>");
  echo("<div style='position:relative; left: 7px; top:0px;'>");
    if(count($lastPlayed) == 0) echo Card("cardBack", "CardImages", 271);
    else
    {
      echo Card($lastPlayed[0], "CardImages", 271);
      if(count($lastPlayed) >= 4)
      {
        if($lastPlayed[3] == "FUSED") echo("<img title='This card was fused.' style='position:absolute; z-index:100; top:125px; left:7px;' src='./Images/fuse2.png' />");
        //else if($lastPlayed[3] == "UNFUSED") echo("<img title='This card was not fused.' style='position:absolute; z-index:100; top:125px; left:7px;' src='./Images/Unfused.png' />");
      }
    }
  echo("</div>");
  echo("<div style='position:relative; z-index:-1; left:3px; top:-6px;'><img style='height:100px; width:200px;' src='./Images/phaseTracker2.png' />");
  $trackerColor = ($playerID == $currentPlayer ? "blue" : "red");
  if($turn[0] == "B") $trackerLeft = "85";
  else if($turn[0] == "A" || $turn[0] == "D") $trackerLeft = "122";
  else if($turn[0] == "PDECK" || $turn[0] == "ARS" || (count($layers) > 0 && $layers[0] == "ENDTURN")) $trackerLeft = "158";
  else if(count($chainLinks) > 0) $trackerLeft = "49";
  else $trackerLeft = "13";
  echo("<div style='position:absolute; z-index:0; top:44px; left:" . $trackerLeft . "px;'><img style='height:29px; width:30px;' src='./Images/" . $trackerColor . "PhaseMarker.png' /></div>");
  echo("</div>");


  echo("<div style='position:fixed; height: calc(100% - 460px); width:193px; bottom:-13px; right:15px;'>");
  echo("<div id='gamelog' style=' border: 3px solid " . $borderColor . "; border-radius: 6px; position:relative; background-color: " . $backgroundColor . "; width:200px; height: calc(100% - 50px); overflow-y: auto;'>");
  EchoLog($gameName, $playerID);
  echo("</div>");
  echo("</div>");

  }

  function PlayableCardBorderColor($cardID)
  {
    if(HasReprise($cardID) && RepriseActive()) return 3;
    return 0;
  }

  function ChoosePopup($zone, $options, $mode, $caption="", $zoneSize=1)
  {
    global $cardSize;
    $content = "";
    $options = explode(",", $options);
    for($i=0; $i<count($options); ++$i)
    {
      $content .= Card($zone[$options[$i]], "concat", $cardSize, $mode, 1, 0, 0, 0, strval($options[$i]));
    }
    echo CreatePopup("CHOOSEZONE", [], 0, 1, $caption, 1, $content);
  }

  function GetCharacterLeft($cardType, $cardSubType)
  {
    global $cardWidth;
    switch($cardType)
    {
      case "C": return "calc(50% - " . ($cardWidth/2 + 5) . "px)";
      //case "W": return "calc(50% " . ($cardSubType == "" ? "- " : "+ ") . ($cardWidth/2 + $cardWidth + 10) . "px)";//TODO: Second weapon
      case "W": return "calc(50% - " . ($cardWidth/2 + $cardWidth + 25) . "px)";//TODO: Second weapon
      default: break;
    }
    switch($cardSubType)
    {
      case "Head": return "95px";
      case "Chest": return "95px";
      case "Arms": return ($cardWidth + 115) . "px";
      case "Legs": return "95px";
      case "Off-Hand": return "calc(50% + " . ($cardWidth/2 + 15) . "px)";
    }
  }

  function GetCharacterBottom($cardType, $cardSubType)
  {
    global $cardSize;
    switch($cardType)
    {
      case "C": return ($cardSize * 2 - 3) . "px";
      case "W": return ($cardSize * 2 - 3) . "px";//TODO: Second weapon
      default: break;
    }
    switch($cardSubType)
    {
      case "Head": return ($cardSize * 2 - 25) . "px";
      case "Chest": return ($cardSize - 10) . "px";
      case "Arms": return ($cardSize - 10) . "px";
      case "Legs": return "5px";
      case "Off-Hand": return ($cardSize * 2 - 3) . "px";
    }
  }

  function GetCharacterTop($cardType, $cardSubType)
  {
    global $cardSize;
    switch($cardType)
    {
      case "C": return "52px";
      case "W": return "52px";//TODO: Second weapon
      //case "C": return ($cardSize + 20) . "px";
      //case "W": return ($cardSize + 20) . "px";//TODO: Second weapon
      default: break;
    }
    switch($cardSubType)
    {
      case "Head": return "5px";
      case "Chest": return ($cardSize - 10) . "px";
      case "Arms": return ($cardSize - 10) . "px";
      case "Legs": return ($cardSize * 2 - 25) . "px";
      case "Off-Hand": return "52px";
    }
  }

  function GetZoneRight($zone)
  {
    global $cardWidth;
    switch($zone)
    {
      case "DISCARD": return "210px";
      case "DECK": return "210px";
      case "BANISH": return "210px";
      case "PITCH": return (230 + $cardWidth) . "px";
    }
  }

  function GetZoneBottom($zone)
  {
    global $cardSize;
    switch($zone)
    {
      case "MYDISCARD": return ($cardSize * 2 - 25) . "px";
      case "MYDECK": return ($cardSize - 10) . "px";
      case "MYBANISH": return (5) . "px";
      case "MYPITCH": return ($cardSize - 10) . "px";
    }
  }

  function GetZoneTop($zone)
  {
    global $cardSize;
    switch($zone)
    {
      case "THEIRDISCARD": return ($cardSize * 2 - 25) . "px";
      case "THEIRDECK": return ($cardSize - 10) . "px";
      case "THEIRBANISH": return (5) . "px";
      case "THEIRPITCH": return ($cardSize - 10) . "px";
    }
  }

  function IsTileable($cardID)
  {
    switch($cardID)
    {
      case "WTR075": return true;
      case "ARC112": return true;
      case "CRU197": return true;
      case "MON186": return true;
      default: return false;
    }
  }

  function DisplayTiles($player)
  {
    global $cardSize, $cardSizeAura;
    $auras = GetAuras($player);

    $count = 0;
    for($i = 0; $i < count($auras); $i += AuraPieces())
    {
      if($auras[$i] == "WTR075") ++$count;
    }
    if($count > 0) echo(Card("WTR075", "concat", $cardSizeAura, 0, 1, 0, 0, ($count > 1 ? $count : 0)) . "&nbsp");

    $runechantCount = 0;
    for($i = 0; $i < count($auras); $i += AuraPieces())
    {
      if($auras[$i] == "ARC112") ++$runechantCount;
    }
    if($runechantCount > 0) echo(Card("ARC112", "concat", $cardSizeAura, 0, 1, 0, 0, ($runechantCount > 1 ? $runechantCount : 0)) . "&nbsp");

    $soulShackleCount = 0;
    for($i = 0; $i < count($auras); $i += AuraPieces())
    {
      if($auras[$i] == "MON186") ++$soulShackleCount;
    }
    if($soulShackleCount > 0) echo(Card("MON186", "concat", $cardSizeAura, 0, 1, 0, 0, ($soulShackleCount > 1 ? $soulShackleCount : 0)) . "&nbsp");

    $items = GetItems($player);
    $copperCount = 0;
    for($i = 0; $i < count($items); $i += ItemPieces())
    {
      if($items[$i] == "CRU197") ++$copperCount;
    }
    if($copperCount > 0) echo(Card("CRU197", "concat", $cardSizeAura, 0, 1, 0, 0, ($copperCount > 1 ? $copperCount : 0)) . "&nbsp");
  }

  function GetPhaseHelptext()
  {
    global $turn;
    $defaultText = "Choose " . TypeToPlay($turn[0]);
    return (GetDQHelpText() != "-" ? implode(" ", explode("_", GetDQHelpText())) : $defaultText);
  }

?>
