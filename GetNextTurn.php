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

  $count = 0;
  $cacheVal = ReadCache($gameName);
  while($lastUpdate != 0 && $cacheVal < $lastUpdate)
  {
    usleep(50000);//50 milliseconds
    $cacheVal = ReadCache($gameName);
    ++$count;
    if($count == 500) break;
  }

  if($lastUpdate != 0 && $cacheVal < $lastUpdate) { echo "0"; exit; }
  else {
  echo(strval(round(microtime(true) * 1000)) . "ENDTIMESTAMP");
  //First we need to parse the game state from the file
  include "WriteLog.php";
  include "ParseGamestate.php";
  include "GameTerms.php";
  include "GameLogic.php";
  include "Libraries/UILibraries2.php";
  include "Libraries/StatFunctions.php";
  include "Libraries/PlayerSettings.php";

  $targetAuth = ($playerID == 1 ? $p1Key : $p2Key);
  if($playerID != 3 && $authKey != $targetAuth) exit;

  if($currentPlayer == $playerID) { $icon = "ready.png"; $readyText = "You are the player with priority."; }
  else { $icon = "notReady.png"; $readyText = "The other player has priority."; }

  if(count($turn) == 0)
  {
    RevertGamestate();
    WriteCache($gameName, strval(round(microtime(true) * 1000)));
    exit();
  }

  echo("<div id='iconHolder' style='display:none;'>" . $icon . "</div>");

  $cardSize = 120;
  $cardWidth = intval($cardSize * 0.71);
  $cardIconSize = 50;
  $cardIconLeft = intval($cardWidth/2) - intval($cardIconSize/2) + 5;
  $cardIconTop = intval($cardSize/2) - intval($cardIconSize/2) + 5;
  $bigCardSize = 200;
  $permLeft = intval(GetCharacterLeft("E", "Arms")) + $cardWidth + 30;
  $permWidth = "calc(50% - " . ($cardWidth/2 + $cardWidth + 20 + $permLeft) . "px)";
  $permHeight = $cardSize * 2 + 20;

  $darkMode = IsDarkMode($playerID);

  if($darkMode) $backgroundColor = "rgba(20,20,20,0.70)";
  else $backgroundColor = "rgba(255,255,255,0.70)";

  $blankZone = ($darkMode ? "blankZoneDark" : "blankZone");
  $borderColor = ($darkMode ? "white" : "black");

  //Display background
  if(IsDarkMode($playerID))
    echo("<div style='position:absolute; z-index:-100; left:0px; top:0px; width:100%; height:100%;'><img style='object-fit: cover; height:100%; width:100%;' src='./Images/flicflak.jpg' /></div>");
  else
    echo("<div style='position:absolute; z-index:-100; left:0px; top:0px; width:100%; height:100%;'><img style='height:100%; width:100%;' src='./Images/findCenterBackground.jpg' /></div>");

  echo("<div style='position:fixed; right:315px; top:calc(50% - 125px); height:200px;'><span style='position:absolute; font-size: 24px; top:149px; left:30px;'>$myHealth</span><span style='position:absolute; font-size: 24px; top:23px; left:30px;'>$theirHealth</span>");
  if(IsDarkMode($playerID)) echo("<img style='height:200px;' src='./Images/DuoLifeDark.png' />");
  else echo("<img style='height:200px;' src='./Images/DuoLife.png' />");
  echo("<div style='position:absolute; top:37px; left:-130px; z-index:-5;'><img style='height:125px; width:150px;' src='./Images/passBG.png' /></div>");
  if(CanPassPhase($turn[0]) && $currentPlayer == $playerID) echo("<div title='Space is the shortcut to pass.' " . ProcessInputLink($playerID, 99, 0) . " class='passButton' style='position:absolute; top:62px; left:-200px; z-index:-1; cursor:pointer; height:75px; width:225px;'><span style='position:absolute; left:100px; top:15px; color:white; font-family:serif; font-size:36px; user-select: none;'>Pass</span></div>");
  else echo("<div title='Space is the shortcut to pass.' class='passInactive' style='position:absolute; top:62px; left:-200px; z-index:-1; height:75px; width:225px;'><span style='position:absolute; left:100px; top:15px; color:gray; font-family:serif; font-size:36px; user-select: none;'>Pass</span></div>");
  echo("<div style='position:absolute; top:117px; left:-150px; z-index:-4;'><img style='height:60px; width:170px;' src='./Images/p1APTracker.png' /><span style='position:absolute; left:85; top:20; z-index:10; font-size:30px;'>" . $actionPoints . "AP</span></div>");
  echo("</div>");

  //Now display the screen for this turn
  echo("<span style='position:fixed; width:100%; bottom:0px; left:0px; display:inline-block; font-size:30px; text-align:center;'>");

  //Tell the player what to pick
  if($turn[0] != "OVER")
  {
    if($currentPlayer != $playerID)
    {
      echo("<span style='display:inline-block; background-color: " . $backgroundColor . "; position:relative; font-size:30px;'><img height='25px;' title='" . $readyText . "' src='./HostFiles/" . $icon . "'/><b>Waiting for other player to choose " . TypeToPlay($turn[0]) . ".</b></span>");
    }
    else
    {
      echo("<span style='display:inline-block; background-color: " . $backgroundColor . "; font-size:30px;'><img height='25px;' title='" . $readyText . "' src='./HostFiles/" . $icon . "'/><b>Please choose " . TypeToPlay($turn[0]));
      if($turn[0] == "P" || $turn[0] == "CHOOSEHANDCANCEL" || $turn[0] == "CHOOSEDISCARDCANCEL") echo(" (" . ($turn[0] == "P" ? $myResources[0] . " of " . $myResources[1] . " " : "") . "or " . CreateButton($playerID, "Cancel", 10000, 0, "24px") . ")");
      if(CanPassPhase($turn[0]))
      {
        //echo(" (or " . CreateButton($playerID, "Pass", 99, 0, "24px", "", "Hotkey: Space"));
        if($turn[0] == "B") echo("(or " . CreateButton($playerID, "Undo Block", 10001, 0, "24px") . ")");
        //echo(")");
      }
      //if(($turn[0] == "B" || $turn[0] == "D") && IsDominateActive()) echo("[Dominate is active]");
      echo("</b></span>");
    }
  }
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
    $effect .= "<div title='" . htmlentities(CardName($cardID), ENT_QUOTES) . "'style='width:86px; height:66px; margin:2px; border:" . $border . ";'>";
    $effect .= "<img style='object-fit: cover; height:100%; width:100%;' src='./crops/" . $cardID . "_cropped.png' />";
    $effect .= "</div>";
    if($playerID == $currentTurnEffects[$i+1]) $friendlyEffects .= $effect;
    else $opponentEffects .= $effect;
  }
  //echo("<div style='position:fixed; height:100%; width:100px; left:0px; top:0px; background-size:cover; background-image: url(\"./Images/effectSidebar.png\");'>");
  echo("<div>Their Effects</div>");
  echo($opponentEffects);
  echo("<div style='bottom:0px; position:absolute;'>");
  echo($friendlyEffects);
  echo("<div>Your Effects</div>");
  echo("</div>");
  //echo("</div>");

  $displayCombatChain = count($combatChain) > 0;

  if($displayCombatChain)
  {
    $totalAttack = 0;
    $totalDefense = 0;
    $chainAttackModifiers = [];
    EvaluateCombatChain($totalAttack, $totalDefense, $chainAttackModifiers);
    echo(CreatePopup("attackModifierPopup", [], 1, 0, "AttackModifiers", 1, AttackModifiers($chainAttackModifiers)));
  }

  echo("<div style='position:fixed; left:290px; top:150px;'>");

  //Display the combat chain
    echo("<table><tr>");
  if($displayCombatChain)
  {
    echo("<td style='font-size:30px; font-weight:bold;'>$totalAttack</td>");
    echo("<td><img onclick='(function(){ document.getElementById(\"attackModifierPopup\").style.display = \"inline\";})();' style='cursor:pointer; height:30px; width:30px; display:inline-block;' src='./Images/Attack.png' /></td>");
    echo("<td><img style='height:30px; width:30px; display:inline-block;' src='./Images/Defense.png' /></td>");
    echo("<td style='font-size:30px; font-weight:bold;'>$totalDefense</td>");
    if(IsDominateActive()) echo("<td style='font-size:24px; font-weight:bold;'><img style='height:40px; display:inline-block;' src='./Images/dominate.png' /></td>");
    if(DoesAttackHaveGoAgain()) echo("<td><img title='This attack has Go Again.' style='height:30px; width:30px; display:inline-block;' src='./Images/goAgain.png' /></td>");
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
      echo(Card($combatChain[$i], "CardImages", $bigCardSize, $action, 1, $actionDisabled, $combatChain[$i+1] == $playerID ? 1 : 2, 0, strval($i)));
    }
  }

  echo("</div>");//Combat chain div


  if($turn[0] == "INSTANT" && ($playerID == $turn[1] || count($layers) > 0))
  {
    $content = "";
    $content .= "<div style='font-size:24px;'><b>Layers</b>&nbsp;<i style='font-size:16px;'>(You can adjust priority settings in the menu.)</i></div>";
    $content .= "<div display:inline;'>";
    for($i=count($layers)-LayerPieces(); $i>=0; $i-=LayerPieces())
    {
      $content .= Card($layers[$i], "CardImages", $bigCardSize, 0, 0, 0, $layers[$i+1] == $playerID ? 1 : 2);
    }
    $content .= "</div>";
    echo CreatePopup("INSTANT", [], 0, 1, "", 1, $content, "./", false, true);
  }

  if($turn[0] == "OVER")
  {
    $content = CreateButton($playerID, "Main Menu", 100001, 0, "24px", "", "", false, true);
    if($playerID == 1) $content .= "&nbsp;" . CreateButton($playerID, "Rematch", 100000, 0, "24px");
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
      $content .= CreateButton($playerID, $options[$i], 7, $options[$i], "30px");
    }
    $content .= "</div>";
    echo CreatePopup("DYNPITCH", [], 0, 1, "Please choose " . TypeToPlay($turn[0]), 1, $content);
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
      $content .= CreateButton($playerID, str_replace("_", " ", $options[$i]), 17, strval($options[$i]), "30px");
    }
    $content .= "</div>";
    echo CreatePopup("BUTTONINPUT", [], 0, 1, "Please choose " . TypeToPlay($turn[0]), 1, $content);
  }

  if($turn[0] == "YESNO" && $turn[1] == $playerID)
  {
    $content = "<div display:inline;'>";
    $content .= CreateButton($playerID, "Yes", 20, "YES");
    $content .= CreateButton($playerID, "No", 20, "NO");
    $content .= "</div>";
    echo CreatePopup("YESNO", [], 0, 1, "Please choose " . TypeToPlay($turn[0]), 1, $content);
  }

  if(($turn[0] == "OPT" || $turn[0] == "CHOOSETOP" || $turn[0] == "CHOOSEBOTTOM" || $turn[0] == "CHOOSECARD") && $turn[1] == $playerID)
  {
    $content = "<table><tr>";
    $options = explode(",", $turn[2]);
    for($i=0; $i<count($options); ++$i)
    {
      $content .= "<td>";
      $content .= "<table><tr><td>";
      $content .= Card($options[$i], "CardImages", $bigCardSize);
      $content .= "</td></tr><tr><td>";
      if($turn[0] == "CHOOSETOP" || $turn[0] == "OPT") $content .= CreateButton($playerID, "Top", 8, $options[$i]);
      if($turn[0] == "CHOOSEBOTTOM" || $turn[0] == "OPT") $content .= CreateButton($playerID, "Bottom", 9, $options[$i]);
      if($turn[0] == "CHOOSECARD") $content .= CreateButton($playerID, "Choose", 23, $options[$i]);
      $content .= "</td></tr>";
      $content .= "</table>";
      $content .= "</td>";
    }
    $content .= "</tr></table>";
    echo CreatePopup("OPT", [], 0, 1, "Please choose " . TypeToPlay($turn[0]), 1, $content);
  }

  if($turn[0] == "HANDTOPBOTTOM" && $turn[1] == $playerID)
  {
    $content = "<table><tr>";
    for($i=0; $i<count($myHand); ++$i)
    {
      $content .= "<td>";
      $content .= Card($myHand[$i], "CardImages", $bigCardSize);
      $content .= "</td>";
    }
    $content .= "</tr><tr>";
    for($i=0; $i<count($myHand); ++$i)
    {
      $content .= "<td><span>";
      $content .= CreateButton($playerID, "Top", 12, $i);
      $content .= "</span><span>";
      $content .= CreateButton($playerID, "Bottom", 13, $i);
      $content .= "</span>";
      $content .= "</td>";
    }
    $content .= "</tr></table>";
    echo CreatePopup("HANDTOPBOTTOM", [], 0, 1, "Please choose " . TypeToPlay($turn[0]), 1, $content);
  }

  if(($turn[0] == "MAYCHOOSEMULTIZONE" || $turn[0] == "CHOOSEMULTIZONE") && $turn[1] == $playerID)
  {
    $content = "<div display:inline;'>";
    $options = explode(",", $turn[2]);
    $otherPlayer = $playerID == 2 ? 1 : 2;
    $theirAllies = &GetAllies($otherPlayer);
    for($i=0; $i<count($options); ++$i)
    {
      $option = explode("-", $options[$i]);
      if($option[0] == "MYAURAS") $source = $myAuras;
      else if($option[0] == "THEIRAURAS") $source = $theirAuras;
      else if($option[0] == "THEIRALLY") $source = $theirAllies;
      else if($option[0] == "MYCHAR") $source = $myCharacter;
      else if($option[0] == "THEIRCHAR") $source = $theirCharacter;
      else if($option[0] == "MYITEMS") $source = $myItems;
      else if($option[0] == "LAYER") $source = $layers;
      else if($option[0] == "MYHAND") $source = $myHand;
      $content .= Card($source[intval($option[1])], "CardImages", $bigCardSize, 16, 0, 0, 0, 0, $options[$i]);
    }
    $content .= "</div>";
    echo CreatePopup("CHOOSEMULTIZONE", [], 0, 1, "Please choose " . TypeToPlay($turn[0]), 1, $content);
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

  if(($turn[0] == "CHOOSETHEIRHAND") && $turn[1] == $playerID)
  {
    ChoosePopup($theirHand, $turn[2], 16, "Choose a card from their hand");
  }

  if(($turn[0] == "CHOOSETHEIRAURA") && $turn[1] == $playerID)
  {
    ChoosePopup($theirAuras, $turn[2], 16, "Choose one of their auras");
  }

  if(($turn[0] == "CHOOSEDISCARD" || $turn[0] == "MAYCHOOSEDISCARD" || $turn[0] == "CHOOSEDISCARDCANCEL") && $turn[1] == $playerID)
  {
    ChoosePopup($myDiscard, $turn[2], 16, "Choose a card from your discard");
  }

  if(($turn[0] == "MAYCHOOSETHEIRDISCARD") && $turn[1] == $playerID)
  {
    ChoosePopup($theirDiscard, $turn[2], 16, "Choose a card from their discard");
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
    ChoosePopup($theirCharacter, $turn[2], 16, "Choose a card from their character/equipment", CharacterPieces());
  }

  if($turn[0] == "CHOOSETHEIRITEM" && $turn[1] == $playerID)
  {
    ChoosePopup($theirItems, $turn[2], 16, "Choose one of their items", ItemPieces());
  }

  if($turn[0] == "PDECK" && $currentPlayer == $playerID)
  {
    $content = "";
    for($i=0; $i<count($myPitch); $i+=1) {
      $content .= Card($myPitch[$i], "CardImages", 200, 6, 0);
    }
    echo CreatePopup("PITCH", [], 0, 1, "Choose a card from your pitch zone to add to the bottom of your deck", 1, $content);
  }

  if(($turn[0] == "MULTICHOOSETHEIRDISCARD" || $turn[0] == "MULTICHOOSEDISCARD" || $turn[0] == "MULTICHOOSEHAND" || $turn[0] == "MULTICHOOSEDECK" || $turn[0] == "MULTICHOOSETEXT" || $turn[0] == "MULTICHOOSETHEIRDECK") && $currentPlayer == $playerID)
  {
    $content = "";
    echo("<div display:inline;'>");
    $params = explode("-", $turn[2]);
    $options = explode(",", $params[1]);
    $caption = "<h3>Choose up to " . $params[0] . " card" . ($params[0] > 1 ? "s." : ".") . "<h3>";
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

  echo(CreatePopup("theirPitchPopup", $theirPitch, 1, 0, "Their Pitch"));
  echo(CreatePopup("theirDiscardPopup", $theirDiscard, 1, 0, "Their Discard"));
  echo(CreatePopup("theirBanishPopup", $theirBanish, 1, 0, "Their Banish", BanishPieces()));
  if(count($theirSoul) > 0) echo(CreatePopup("theirSoulPopup", $theirSoul, 1, 0, "Their Soul"));

  //Opponent hand
  echo("<div style='position: fixed; top: 0px; left: calc(50% + 170px); height: 50px; border:1px solid " . $borderColor . "; display:inline;'><span style='height:100%; vertical-align:middle; display:inline-block'>Their hand:&nbsp;</span>");
  for($i=0; $i<count($theirHand); ++$i) {
    echo(Card("cardBack", "CardImages", 50, 0, 0, 0, -1));
  }
  if(count($theirSoul) > 0) echo("<div title='Click to view the cards in their Soul.' style='padding-left:10px; cursor:pointer; position:relative; display:inline-block; height:50px; font-size:20; text-align:center;' onclick='(function(){ document.getElementById(\"theirSoulPopup\").style.display = \"inline\";})();'><img style='height:50px; width:50px;' src='./Images/soulIcon.png'></img> <div style='position:relative; top:-20px; display:inline-block;'>" . count($theirSoul) . " cards</div></div>");
  echo("</div>");


  //Show deck, discard, pitch, banish
  //Display Their Discard
  echo("<div title='Click to view the cards in their Graveyard.' style='cursor:pointer; position:fixed; right:" . GetZoneRight("DISCARD") . "; top:" . GetZoneTop("THEIRDISCARD") .";' onclick='(function(){ document.getElementById(\"theirDiscardPopup\").style.display = \"inline\";})();'>");
  $card = (count($theirDiscard) > 0 ? $theirDiscard[count($theirDiscard)-1] : $blankZone);
  $folder = (count($theirDiscard) > 0 ? "CardImages" : "Images");
  echo(Card($card, $folder, $cardSize, 0, 0, 0, 0, count($theirDiscard)));
  echo("</div>");

  //Display Their Deck
  echo("<div style='position:fixed; right:" . GetZoneRight("DECK") . "; top:" . GetZoneTop("THEIRDECK") .";'>");
  $deckImage = (count($theirDeck) > 0 ? "cardBack" : $blankZone);
  echo(Card($deckImage, "CardImages", $cardSize, 0, 0, 0, 0, count($theirDeck)));
  echo("</div>");

  //Display Their Banish
  echo("<div style='position:fixed; right:" . GetZoneRight("BANISH") . "; top:" . GetZoneTop("THEIRBANISH") .";'>");
  $card = (count($theirBanish) > 0 ? ($theirBanish[count($theirBanish)-BanishPieces()+1] == "INT" ? "cardBack": $theirBanish[count($theirBanish)-BanishPieces()]) : $blankZone);
  $folder = (count($theirBanish) > 0 ? "CardImages" : "Images");
  echo(Card($card, $folder, $cardSize, 0, 0, 0, 0));

  echo("<span title='Click to see their banish zone.' onclick='(function(){ document.getElementById(\"theirBanishPopup\").style.display = \"inline\";})();' style='left:" . $cardIconLeft . "px; top:" . $cardIconTop . "px; cursor:pointer; position:absolute; display:inline-block;'><img style='height:50px; width:50px;' src='./Images/banish.png'><div style='position:absolute; top:10px; width:50px; font-size:30; color:white; text-align:center;'>" . count($theirBanish)/BanishPieces() . "</div></img></span>");
  echo("</div>");

  //Display Their Pitch
  echo("<div style='position:fixed; right:" . GetZoneRight("PITCH") . "; top:" . GetZoneTop("THEIRPITCH") .";'>");
  $card = (count($theirPitch) > 0 ? $theirPitch[count($theirPitch)-PitchPieces()] : $blankZone);
  $folder = (count($theirPitch) > 0 ? "CardImages" : "Images");
  echo(Card($card, $folder, $cardSize, 0, 0));
  echo("<span title='Click to see their pitch zone.' onclick='(function(){ document.getElementById(\"theirPitchPopup\").style.display = \"inline\";})();' style='left:" . $cardIconLeft . "px; top:" . $cardIconTop . "px; cursor:pointer; position:absolute; display:inline-block;'><img style='height:50px; width:50px;' src='./Images/Resource.png'><div style='position:absolute; top:10px; width:50px; font-size:30; color:white; text-align:center;'>" . $theirResources[0] . "</div></img></span>");
  echo("</div>");



  //Now display their Auras and Items
  if(count($landmarks) > 0)
  {
    echo("<div style='position: fixed; top:250px; left: calc(50% + 200px); display:inline;'>");
    echo("<h3>Landmark:</h3>");
    for($i=0; $i<count($landmarks); $i+=LandmarkPieces())
    {
      $playable = IsPlayable($landmarks[$i], $turn[0], "PLAY", $i, $restriction);
      $action = ($playable && $currentPlayer == $playerID ? 25 : 0) ;
      $border = CardBorderColor($landmarks[$i], "PLAY", $playable);
      $counters = 0;
      echo(Card($landmarks[$i], "CardImages", $cardSize, $action, 1, 0, $border, $counters, strval($i), "", true));//TODO: Show sideways
    }
    echo("</div>");
  }

  $permTop = 10;
  $theirPermHeight = $cardSize + 10;
  echo("<div style='overflow-y:auto; position: fixed; top:" . $permTop . "px; left:" . $permLeft . "px; height:200px; width:" . $permWidth . "; height:" . $theirPermHeight . "px;'>");
  DisplayTiles(($playerID == 1 ? 2 : 1));
  if(count($theirAuras) > 0)
  {
    echo("<div style='display:inline-block;'>");
    for($i=0; $i<count($theirAuras); $i+=AuraPieces())
    {
      if(IsTileable($theirAuras[$i])) continue;
      $counters = $theirAuras[$i+2] > 0 ? $theirAuras[$i+2] : $theirAuras[$i+3];//TODO: Show both
      echo(Card($theirAuras[$i], "CardImages", $cardSize, 0, 1, $theirAuras[$i+1] != 2 ? 1 : 0, 0, $counters));
    }
    echo("</div>");
  }
  if(count($theirItems) > 0)
  {
    echo("<div style='display:inline-block;'>");
    for($i=0; $i<count($theirItems); $i+=ItemPieces())
    {
      if(IsTileable($theirItems[$i])) continue;
      echo(Card($theirItems[$i], "CardImages", $cardSize, 0, 1, $theirItems[$i+2] !=2 ? 1 : 0, 0, $theirItems[$i+1]));
    }
    echo("</div>");
  }
  $otherPlayer = $playerID == 2 ? 1 : 2;
  $theirAllies = GetAllies($otherPlayer);
  if(count($theirAllies) > 0)
  {
    echo("<div style='display:inline-block;'>");
    for($i=0; $i<count($theirAllies); $i+=AllyPieces())
    {
      echo(Card($theirAllies[$i], "CardImages", $cardSize, 0, 1, $theirAllies[$i+1] !=2 ? 1 : 0, 0, $theirAllies[$i+2]));
    }
    echo("</div>");
  }
    echo("</div>");

  //Now display character and equipment
  $numWeapons = 0;
  for($i=0; $i<count($theirCharacter); $i+=CharacterPieces())
  {
    $type = CardType($theirCharacter[$i]);//NOTE: This is not reliable type
    $sType = CardSubType($theirCharacter[$i]);
    if($type == "W") { ++$numWeapons; if($numWeapons > 1) {$type = "E"; $sType = "Off-Hand";} }
    $counters = CardType($theirCharacter[$i]) == "W" ? $theirCharacter[$i+3] : $theirCharacter[$i+4];
    if($theirCharacter[$i+2] > 0) $counters = $theirCharacter[$i+2];//TODO: display both kinds of counters?
    echo("<div style='z-index:5; position:fixed; left:" . GetCharacterLeft($type, $sType) . "; top:" . GetCharacterTop($type, $sType) .";'>");
    echo(Card($theirCharacter[$i], "CardImages", $cardSize, 0, 1, $theirCharacter[$i+1] !=2 ? 1 : 0, 0, $counters));
    if($theirCharacter[$i+6] == 1) echo("<img title='On Combat Chain' style='position:absolute; z-index:100; top:5px; left:5px; width:" . $cardWidth . "' src='./Images/onChain.png' />");
    if($theirCharacter[$i+1] == 0) echo("<img title='Equipment Broken' style='position:absolute; z-index:100; width:" . $cardWidth . "; bottom: 5px; left:5px;' src='./Images/brokenEquip.png' />");
    echo("</div>");
  }
  echo("</div>");
  //Now display arsenal
  if($theirArsenal != "")
  {
    echo("<div title='Their Arsenal' style='position: fixed; top:0px; left:" . GetCharacterLeft("C", "") . "; display:inline;'>");
    for($i=0; $i<count($theirArsenal); $i+=ArsenalPieces())
    {
      if($theirArsenal[$i+1] == "UP") echo(Card($theirArsenal[$i], "CardImages", $cardSize, 0, 1, $theirArsenal[$i+2] == 0 ? 1 : 0, 0, $theirArsenal[$i+3]));
      else echo(Card("cardBack", "CardImages", $cardSize, 0, 0));
    }
    echo("</div>");
  }


  echo(CreatePopup("myPitchPopup", $myPitch, 1, 0, "Your Pitch"));
  echo(CreatePopup("myDiscardPopup", $myDiscard, 1, 0, "Your Discard"));
  echo(CreatePopup("myBanishPopup", [], 1, 0, "Your Banish", 1, BanishUI()));
  echo(CreatePopup("myStatsPopup", [], 1, 0, "Your Game Stats", 1, CardStats($playerID), "./", true));
  echo(CreatePopup("menuPopup", [], 1, 0, "Menu", 1, CreateButton($playerID, "Undo", 10000, 0, "24px") . "<BR>" . CreateButton($playerID, "+1 Action Point", 10002, 0, "24px") . "<BR>" . CreateButton($playerID, "Concede", 100002, 0, "24px") . "<BR>" . CreateButton($playerID, "Report Bug", 100003, 0, "24px") . "<BR>" . GetSettingsUI($playerID), "./", true));
  if(count($mySoul) > 0) echo(CreatePopup("mySoulPopup", $mySoul, 1, 0, "My Soul"));

  $restriction = "";
  $actionType = $turn[0] == "ARS" ? 4 : 2;
  if(strpos($turn[0], "CHOOSEHAND") !== false && $turn[0] != "MULTICHOOSEHAND") $actionType = 16;
  $handLeft = "calc(50% - " . ((count($myHand) * ($cardWidth + 10) - 10)/2) . "px)";
  echo("<div style='position:fixed; left:" . $handLeft . "; bottom:32px;'>");//Hand div
  for($i=0; $i<count($myHand); ++$i) {
    if($playerID == 3)
    {
      echo(Card("cardBack", "CardImages", $cardSize, 0, 0, 0, -1));
    }
    else
    {
      $playable = $turn[0] == "ARS" || IsPlayable($myHand[$i], $turn[0], "HAND", -1, $restriction) || ($actionType == 16 && strpos("," . $turn[2] . ",", "," . $i . ",") !== false);
      $border = CardBorderColor($myHand[$i], "HAND", $playable);
      $actionData = $actionType == 16 ? strval($i) : "";
      echo("<span style='position:relative;'>");
      echo(Card($myHand[$i], "CardImages", $cardSize, $currentPlayer == $playerID && $playable ? $actionType : 0, 1 , 0, $border, 0, $actionData));
      if($restriction != "") echo("<img title='Restricted by " . CardName($restriction) . "' style='position:absolute; z-index:100; top:-100px; left:45px;' src='./Images/restricted.png' />");
      echo("</span>");
    }
  }
  echo(BanishUI("HAND"));
  echo("</div>");//End hand div

  //Now display arsenal
  if(count($myArsenal) > 0)
  {
    $arsenalLeft = (count($myArsenal) == ArsenalPieces() ? "calc(50% - " . (intval($cardWidth/2)+10) . "px)" : "calc(50% - " . (intval($cardWidth)+10) . "px)");
    echo("<div style='position:fixed; left:" . $arsenalLeft . "; bottom:" . (intval(GetCharacterBottom("C", "")) - $cardSize - 10) . "px;'>");//arsenal div
    for($i=0; $i<count($myArsenal); $i+=ArsenalPieces())
    {
      if($playerID == 3)
      {
        echo(Card("cardBack", "CardImages", $cardSize, 0, 0, 0, -1));
      }
      else
      {
        $playable = $turn[0] != "P" && IsPlayable($myArsenal[$i], $turn[0], "ARS", -1, $restriction);
        $border = CardBorderColor($myArsenal[$i], "ARS", $playable);
        $counters = $myArsenal[$i+3];
        echo("<div style='display:inline-block; position:relative; left:10px;'>");
        echo(Card($myArsenal[$i], "CardImages", $cardSize, $currentPlayer == $playerID && $playable ? 5 : 0, 1, $myArsenal[$i+2] > 0 ? 0 : 1, $border, $counters, strval($i)));
        $iconHeight = $cardSize / 2 - 5;
        $iconLeft = $cardWidth/2 - intval($iconHeight*.71/2) + 5;
        if($myArsenal[$i+1] == "UP") echo("<img style='position:absolute; left:" . $iconLeft . "px; bottom:3px; height:" . $iconHeight . "px; ' src='./Images/faceUp.png' title='This arsenal card is face up.'></img>");
        else echo("<img style='position:absolute; left:" . $iconLeft . "px; bottom:3px; height:" . $iconHeight . "px; ' src='./Images/faceDown.png' title='This arsenal card is face down.'></img>");
        echo("</div>");
      }
    }
    echo("</div>");//End arsenal div
  }

  //Now display Auras and items
  $permTop = intval(GetCharacterBottom("C", "")) + $cardSize;
  $permHeight = $cardSize * 2 + 30;
  echo("<div style='overflow-y:auto; position: fixed; top:" . $permTop . "px; left:" . $permLeft . "px; width:" . $permWidth . "; max-height:" . $permHeight . "px;'>");
  DisplayTiles($playerID);
  if(count($myAuras) > 0)
  {
    echo("<div style='display:inline-block;'>");
    for($i=0; $i<count($myAuras); $i+=AuraPieces())
    {
      if(IsTileable($myAuras[$i])) continue;
      $playable = $myAuras[$i+1] == 2 && IsPlayable($myAuras[$i], $turn[0], "PLAY", $i, $restriction);
      $border = CardBorderColor($myAuras[$i], "PLAY", $playable);
      $counters = $myAuras[$i+2] > 0 ? $myAuras[$i+2] : $myAuras[$i+3];//TODO: Show both
      echo(Card($myAuras[$i], "CardImages", $cardSize, $currentPlayer == $playerID && $turn[0] != "P" && $playable ? 22 : 0, 1, $myAuras[$i+1] != 2 ? 1 : 0, $border, $counters, strval($i)));
    }
    echo("</div>");
  }
  if(count($myItems) > 0)
  {
    echo("<div style='display:inline-block;'>");
    for($i=0; $i<count($myItems); $i+=ItemPieces())
    {
      if(IsTileable($myItems[$i])) continue;
      $playable = IsPlayable($myItems[$i], $turn[0], "PLAY", $i, $restriction);
      $border = CardBorderColor($myItems[$i], "PLAY", $playable);
      echo(Card($myItems[$i], "CardImages", $cardSize, $currentPlayer == $playerID && $turn[0] != "P" && $playable ? 10 : 0, 1, $myItems[$i+2] !=2 ? 1 : 0, $border, $myItems[$i+1], strval($i)));
    }
    echo("</div>");
  }
  $myAllies = GetAllies($playerID);
  if(count($myAllies) > 0)
  {
    echo("<div style='display:inline-block;'>");
    for($i=0; $i<count($myAllies); $i+=AllyPieces())
    {
      $playable = IsPlayable($myAllies[$i], $turn[0], "PLAY", $i, $restriction) && $myAllies[$i+1] == 2;
      $border = CardBorderColor($myAllies[$i], "PLAY", $playable);
      echo(Card($myAllies[$i], "CardImages", $cardSize, $currentPlayer == $playerID && $turn[0] != "P" && $playable ? 24 : 0, 1, $myAllies[$i+1] !=2 ? 1 : 0, $border, $myAllies[$i+2], strval($i)));
    }
    echo("</div>");
  }
  echo("</div>");

  //Now display character and equipment
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
    echo("<div style='position:fixed; left:" . GetCharacterLeft($type, $sType) . "; bottom:" . GetCharacterBottom($type, $sType) .";'>");
    echo(Card($myCharacter[$i], "CardImages", $cardSize, $currentPlayer == $playerID && $playable ? 3 : 0, 1, $myCharacter[$i+1] !=2 ? 1 : 0, $border, $counters, strval($i)));
    $effects = ActiveCharacterEffects($playerID, $i);
    if($effects != "") echo("<img title='Buffed by: $effects' style='position:absolute; z-index:100; top:40px; left:23px;' src='./Images/arsenal.png' />");
    if($restriction != "") {
      $restrictionName = CardName($restriction);
      echo("<img title='Restricted by: " . ($restrictionName != "" ? $restrictionName : $restriction) . "' style='position:absolute; z-index:100; top:40px; left:23px;' src='./Images/restricted.png' />");
    }
    if($myCharacter[$i+6] == 1) echo("<div title='On Combat Chain' style='position:absolute; z-index:100; width:" . $cardWidth . "; bottom: 0px; left:0px;'>" . Card("onChain", "Images", $cardSize, $currentPlayer == $playerID && $playable ? 3 : 0, 0, $myCharacter[$i+1] !=2 ? 1 : 0, 0, 0, strval($i)) . "</div>");
    //echo("<img title='On Combat Chain' style='position:absolute; z-index:100; width:" . $cardWidth . "; bottom: 5px; left:5px;' src='./Images/onChain.png' />");
    if($myCharacter[$i+1] == 0) echo("<img title='Equipment Broken' style='position:absolute; z-index:100; width:" . $cardWidth . "; bottom: 5px; left:5px;' src='./Images/brokenEquip.png' />");
    if($type == "C")
    {
      if(CardTalent($myCharacter[0]) == "LIGHT" || count($mySoul) > 0) echo("<div onclick='(function(){ document.getElementById(\"mySoulPopup\").style.display = \"inline\";})();' style='cursor:pointer; position:absolute; top:-23px; height:20px; font-size:20; text-align:center;'>Soul: " . count($mySoul) . " cards</div>");
    }
    echo("</div>");
    echo("</div>");
  }
  echo("</div>");

  //Show deck, discard, pitch, banish
  //Display My Discard
  echo("<div title='Click to view the cards in your Graveyard.' style='cursor:pointer; position:fixed; right:" . GetZoneRight("DISCARD") . "; bottom:" . GetZoneBottom("MYDISCARD") .";' onclick='(function(){ document.getElementById(\"myDiscardPopup\").style.display = \"inline\";})();'>");
  $card = (count($myDiscard) > 0 ? $myDiscard[count($myDiscard)-1] : $blankZone);
  $folder = (count($myDiscard) > 0 ? "CardImages" : "Images");
  echo(Card($card, $folder, $cardSize, 0, 0, 0, 0, count($myDiscard)));
  echo("</div>");

  //Display My Deck
  echo("<div style='position:fixed; right:" . GetZoneRight("DECK") . "; bottom:" . GetZoneBottom("MYDECK") .";'>");
  $deckImage = (count($myDeck) > 0 ? "cardBack" : $blankZone);
  echo(Card($deckImage, "CardImages", $cardSize, 0, 0, 0, 0, count($myDeck)));
  echo("</div>");

  //Display My Banish
  echo("<div style='position:fixed; right:" . GetZoneRight("BANISH") . "; bottom:" . GetZoneBottom("MYBANISH") .";'>");
  $card = (count($myBanish) > 0 ? $myBanish[count($myBanish)-BanishPieces()] : $blankZone);
  $folder = (count($myBanish) > 0 ? "CardImages" : "Images");
  echo(Card($card, $folder, $cardSize, 0, 0, 0, 0));
  echo("<span title='Click to see your banish zone.' onclick='(function(){ document.getElementById(\"myBanishPopup\").style.display = \"inline\";})();' style='left:" . $cardIconLeft . "px; top:" . $cardIconTop . "px; cursor:pointer; position:absolute; display:inline-block;'><img style='height:50px; width:50px;' src='./Images/banish.png'><div style='position:absolute; top:10px; width:50px; font-size:30; color:white; text-align:center;'>" . count($myBanish)/BanishPieces() . "</div></img></span>");
  echo("</div>");

  //Display My Pitch
  echo("<div style='position:fixed; right:" . GetZoneRight("PITCH") . "; bottom:" . GetZoneBottom("MYPITCH") .";'>");
  $card = (count($myPitch) > 0 ? $myPitch[count($myPitch)-PitchPieces()] : $blankZone);
  $folder = (count($myPitch) > 0 ? "CardImages" : "Images");
  echo(Card($card, $folder, $cardSize, 0, 0));
  echo("<span title='Click to see your pitch zone.' onclick='(function(){ document.getElementById(\"myPitchPopup\").style.display = \"inline\";})();' style='left:" . $cardIconLeft . "px; top:" . $cardIconTop . "px; cursor:pointer; position:absolute; display:inline-block;'><img style='height:50px; width:50px;' src='./Images/Resource.png'><div style='position:absolute; top:10px; width:50px; font-size:30; color:white; text-align:center;'>" . $myResources[0] . "</div></img></span>");
  echo("</div>");

  echo("</div>");//End play area div

  //Display the log
  echo("<div style='position:fixed; width:200px; top:10px; bottom:10px; right:10px;'>");

  echo("<div style='position:relative; height:50px;'><div style='position:absolute; right:50px;'><table><tr><td>");
  echo("<div title='Click to view stats.' style='cursor:pointer;' onclick='(function(){ document.getElementById(\"myStatsPopup\").style.display = \"inline\";})();'><img style='width:50px; height:50px;' src='./Images/stats.png' /></div>");
  echo("</td><td></td><td>");
  echo("<div title='Click to view the menu.' style='cursor:pointer;' onclick='(function(){ document.getElementById(\"menuPopup\").style.display = \"inline\";})();'><img style='width:50px; height:50px;' src='./Images/menu.png' /></div>");
  echo("</td></tr></table></div></div>");

  echo("<div style='text-align:center; width:200px; font-size:24;'>Turn #" . $currentTurn . "</div>");
  echo("<div style='text-align:center; width:200px; font-size:16;'>Last Played</div>");
  echo("<div style='position:relative; top:0px;'>");
    if(count($lastPlayed) == 0) echo Card("cardBack", "CardImages", 271);
    else
    {
      echo Card($lastPlayed[0], "CardImages", 271);
      if(count($lastPlayed) > 2 && $lastPlayed[2] == "FUSED") echo("<img title='This card was fused.' style='position:absolute; z-index:100; top:125px; left:7px;' src='./Images/fuse.png' />");
    }
  echo("</div>");
  echo("<div style='position:relative; z-index:-1; left:0px; top:0px;'><img style='height:100px; width:200px;' src='./Images/phaseTracker2.png' />");
  $trackerColor = ($playerID == $currentPlayer ? "blue" : "red");
  if($turn[0] == "B") $trackerLeft = "85";
  else if($turn[0] == "A" || $turn[0] == "D") $trackerLeft = "122";
  else if($turn[0] == "PDECK" || $turn[0] == "ARS" || (count($layers) > 0 && $layers[0] == "ENDTURN")) $trackerLeft = "158";
  else if(count($chainLinks) > 0) $trackerLeft = "49";
  else $trackerLeft = "13";
  echo("<div style='position:absolute; z-index:0; top:44px; left:" . $trackerLeft . "px;'><img style='height:29px; width:30px;' src='./Images/" . $trackerColor . "PhaseMarker.png' /></div>");
  echo("</div>");

  }

  function PlayableCardBorderColor($cardID)
  {
    if(HasReprise($cardID) && RepriseActive()) return 3;
    return 0;
  }

  function ChoosePopup($zone, $options, $mode, $caption="", $zoneSize=1)
  {
    $content = "";
    $options = explode(",", $options);
    for($i=0; $i<count($options); ++$i)
    {
      $content .= Card($zone[$options[$i]], "CardImages", 200, $mode, 0, 0, 0, 0, strval($options[$i]));
    }
    echo CreatePopup("CHOOSEZONE", [], 0, 1, $caption, 1, $content);
  }


  function GetCharacterLeft($cardType, $cardSubType)
  {
    global $cardWidth;
    switch($cardType)
    {
      case "C": return "calc(50% - " . ($cardWidth/2) . "px)";
      //case "W": return "calc(50% " . ($cardSubType == "" ? "- " : "+ ") . ($cardWidth/2 + $cardWidth + 10) . "px)";//TODO: Second weapon
      case "W": return "calc(50% - " . ($cardWidth/2 + $cardWidth + 10) . "px)";//TODO: Second weapon
      default: break;
    }
    switch($cardSubType)
    {
      case "Head": return "90px";
      case "Chest": return "90px";
      case "Arms": return ($cardWidth + 100) . "px";
      case "Legs": return "90px";
      case "Off-Hand": return "calc(50% + " . ($cardWidth/2 + 10) . "px)";
    }
  }

  function GetCharacterBottom($cardType, $cardSubType)
  {
    global $cardSize;
    switch($cardType)
    {
      case "C": return ($cardSize * 2 + 50) . "px";
      case "W": return ($cardSize * 2 + 50) . "px";//TODO: Second weapon
      default: break;
    }
    switch($cardSubType)
    {
      case "Head": return ($cardSize * 2 + 70) . "px";
      case "Chest": return ($cardSize + 60) . "px";
      case "Arms": return ($cardSize + 60) . "px";
      case "Legs": return "50px";
      case "Off-Hand": return ($cardSize * 2 + 50) . "px";
    }
  }

  function GetCharacterTop($cardType, $cardSubType)
  {
    global $cardSize;
    switch($cardType)
    {
      case "C": return "20px";
      case "W": return "20px";//TODO: Second weapon
      //case "C": return ($cardSize + 20) . "px";
      //case "W": return ($cardSize + 20) . "px";//TODO: Second weapon
      default: break;
    }
    switch($cardSubType)
    {
      case "Head": return "10px";
      case "Chest": return (20 + $cardSize) . "px";
      case "Arms": return (20 + $cardSize) . "px";
      case "Legs": return (30 + $cardSize*2) . "px";
      case "Off-Hand": return "20px";
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
      case "PITCH": return (220 + $cardWidth) . "px";
    }
  }

  function GetZoneBottom($zone)
  {
    global $cardSize;
    switch($zone)
    {
      case "MYDISCARD": return ($cardSize * 2 + 70) . "px";
      case "MYDECK": return ($cardSize + 60) . "px";
      case "MYBANISH": return (50) . "px";
      case "MYPITCH": return ($cardSize + 60) . "px";
    }
  }

  function GetZoneTop($zone)
  {
    global $cardSize;
    switch($zone)
    {
      case "THEIRDISCARD": return ($cardSize * 2 + 30) . "px";
      case "THEIRDECK": return ($cardSize + 20) . "px";
      case "THEIRBANISH": return (10) . "px";
      case "THEIRPITCH": return ($cardSize + 20) . "px";
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
    global $cardSize;
    $auras = GetAuras($player);

    $count = 0;
    for($i = 0; $i < count($auras); $i += AuraPieces())
    {
      if($auras[$i] == "WTR075") ++$count;
    }
    if($count > 0) echo(Card("WTR075", "CardImages", $cardSize, 0, 1, 0, 0, ($count > 1 ? $count : 0)));

    $runechantCount = 0;
    for($i = 0; $i < count($auras); $i += AuraPieces())
    {
      if($auras[$i] == "ARC112") ++$runechantCount;
    }
    if($runechantCount > 0) echo(Card("ARC112", "CardImages", $cardSize, 0, 1, 0, 0, ($runechantCount > 1 ? $runechantCount : 0)));

    $soulShackleCount = 0;
    for($i = 0; $i < count($auras); $i += AuraPieces())
    {
      if($auras[$i] == "MON186") ++$soulShackleCount;
    }
    if($soulShackleCount > 0) echo(Card("MON186", "CardImages", $cardSize, 0, 1, 0, 0, ($soulShackleCount > 1 ? $soulShackleCount : 0)));

    $items = GetItems($player);
    $copperCount = 0;
    for($i = 0; $i < count($items); $i += ItemPieces())
    {
      if($items[$i] == "CRU197") ++$copperCount;
    }
    if($copperCount > 0) echo(Card("CRU197", "CardImages", $cardSize, 0, 1, 0, 0, ($copperCount > 1 ? $copperCount : 0)));
  }

?>
