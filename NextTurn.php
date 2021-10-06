<head>

<?php

  include 'Libraries/HTTPLibraries.php';

  //We should always have a player ID as a URL parameter
  $gameName=$_GET["gameName"];
  $playerID=TryGet("playerID", 3);

  //First we need to parse the game state from the file
  include "WriteLog.php";
  include "ParseGamestate.php";
  include "GameTerms.php";
  include "GameLogic.php";
  include "HostFiles/Redirector.php";
  include "Libraries/UILibraries.php";
  include "Libraries/StatFunctions.php";

  if($currentPlayer == $playerID) $icon = "ready.png";
  else $icon = "notReady.png";
  echo '<link rel="shortcut icon" type="image/png" href="./HostFiles/' . $icon . '"/>';

  if(count($turn) == 0)
  {
    echo("The game seems not to have loaded properly. Please try restarting the game.");
    exit();
  }

?>

<style>
  td {
    text-align:center;
  }
</style>

</head>

<body onload='OnLoadCallback(<?php echo(filemtime("./Games/" . $gameName . "/gamelog.txt")); ?>)'>

<?php


  //Include js files
  echo("<script src=\"./jsInclude.js\"></script>");

  echo("<script>");
  echo("function reload() { document.getElementById('gamelog').scrollTop = document.getElementById('gamelog').scrollHeight;" . ($playerID != $currentPlayer ? "CheckReloadNeeded();" : "") . "}");

  echo 'function CheckReloadNeeded() {';
    echo 'var xmlhttp = new XMLHttpRequest();';
    echo 'xmlhttp.onreadystatechange = function() {';
      echo 'if (this.readyState == 4 && this.status == 200) {';
        echo 'if(parseInt(this.responseText) == 1) { location.reload(); }';
        echo 'else { CheckReloadNeeded(); }';
      echo '}';
    echo '};';
    echo 'xmlhttp.open("GET", "IsReloadNeeded.php?gameName=' . $gameName . '&playerID=' . ($playerID == 3 ? ($currentPlayer == 1 ? 2 : 1) : $playerID) . '", true);';
    echo 'xmlhttp.send();';
  echo '}';
  echo("</script>");

  //Display hidden elements
  echo("<div id=\"cardDetail\" style=\"z-index:1000; display:none; position:absolute;\"></div>");

  //Display background
  echo("<div style='position:absolute; z-index:-100; left:0px; top:0px; width:100%; height:100%;'><img style='height:100%; width:100%;' src='./CardImages/findCenterBackground.jpg' /></div>");

  //Now display the screen for this turn
  echo("<span style='display:inline-block; background-color: rgba(255,255,255,0.70); position:relative; font-size:30px;'><b>Turn #" . $currentTurn . " (" . ($playerID == $currentPlayer ? "Your" : "Their") . " " . PhaseName($turn[0]) . " Phase - $actionPoints Action Points");
  if(DoesAttackHaveGoAgain()) echo("<img title='This attack has Go Again.' style='height:24px; width:24px; display:inline-block;' src='./Images/goAgain.png' />");
  echo(")</b></span>");//Turn number

  //Tell the player what to pick
  echo("<div style='z-index:500;'>");
  if($turn[0] == "OVER")
  {
    echo("<h2>Player " . $winner . " Won!</h2>");
    echo(CardStatsUI($playerID));
  }
  else if($currentPlayer != $playerID)
  {
    echo("<span style='display:inline-block; background-color: rgba(255,255,255,0.70); position:relative; font-size:24px;'><b>Waiting for other player to choose " . TypeToPlay($turn[0]) . ".</b></span>");
  }
  else
  {
    echo("<span style='display:inline-block; background-color: rgba(255,255,255,0.70); font-size:24px;'><b>Please choose " . TypeToPlay($turn[0]));
    if($turn[0] == "P" || $turn[0] == "CHOOSEHANDCANCEL" || $turn[0] == "CHOOSEDISCARDCANCEL") echo(" (" . ($turn[0] == "P" ? $myResources[0] . " of " . $myResources[1] . " " : "") . "or " . CreateButton($playerID, "Cancel", 10000, 0, "24px") . ")");
    if(CanPassPhase($turn[0]))
    {
      echo(" (or " . CreateButton($playerID, "Pass", 99, 0, "24px"));
      if($turn[0] == "B") echo(" or " . CreateButton($playerID, "Undo Block", 10001, 0, "24px"));
      echo(")");
    }
    if(($turn[0] == "B" || $turn[0] == "D") && IsDominateActive()) echo("[Dominate is active]");
    echo(":</b></span>");
  }
  echo("</div>");
  //Display the combat chain
  if($turn[0] == "A" || $turn[0] == "B" || $turn[0] == "D" || ($turn[0] == "P" && ($turn[2] == "A" || $turn[2] == "B" || $turn[2] == "D")))
  {
    $totalAttack = 0;
    $totalDefense = 0;
    EvaluateCombatChain($totalAttack, $totalDefense);
    echo("<table><tr>");
    echo("<td><span style='font-size:64;'>$totalAttack</span></td>");
    echo("<td><img style='height:64px; width:64px; display:inline-block;' src='./Images/Attack.png' /></td>");
    echo("<td><img style='height:64px; width:64px; display:inline-block;' src='./Images/Defense.png' /></td>");
    echo("<td><span style='font-size:64;'>$totalDefense</span></td>");
    echo("</tr></table>");
    echo("<div display:inline;'><h3>Combat Chain</h3>");
    for($i=0; $i<count($combatChain); $i+=CombatChainPieces()) {
      //echo(Card($combatChain[$i], "CardImages", 400, 0, 0, 0, $combatChain[$i+1] == $playerID ? 1 : 2));
      $action = $currentPlayer == $playerID && $turn[0] != "P" && $currentPlayer == $combatChain[$i+1] && IsPlayable($combatChain[$i], $turn[0], "PLAY", $i) ? 21 : 0;
      $actionDisabled = 0;
      echo(Card($combatChain[$i], "CardImages", 400, $action, 1, $actionDisabled, $combatChain[$i+1] == $playerID ? 1 : 2, 0, strval($i)));
    }
    echo("</div>");
  }

  if($turn[0] == "DYNPITCH")
  {
    echo("<div display:inline;'>");
    $options = explode(",", $turn[2]);
    for($i=0; $i<count($options); ++$i)
    {
      echo(CreateButton($playerID, $options[$i], 7, $options[$i]));
    }
    echo("</div>");
  }

  if(($turn[0] == "BUTTONINPUT" || $turn[0] == "CHOOSEARCANE") && $turn[1] == $playerID)
  {
    echo("<div display:inline;'>");
    $options = explode(",", $turn[2]);
    for($i=0; $i<count($options); ++$i)
    {
      echo(CreateButton($playerID, str_replace("_", " ", $options[$i]), 17, strval($options[$i])));
    }
    echo("</div>");
  }

  if($turn[0] == "YESNO" && $turn[1] == $playerID)
  {
    echo("<div display:inline;'>");
    echo(CreateButton($playerID, "Yes", 20, "YES"));
    echo(CreateButton($playerID, "No", 20, "NO"));
    echo("</div>");
  }

  if($turn[0] == "OPT" && $turn[1] == $playerID)
  {
    echo("<table><tr>");
    $options = explode(",", $turn[2]);
    for($i=0; $i<count($options); ++$i)
    {
      echo("<td>");
      echo("<table><tr><td>");
      echo(Card($options[$i], "CardImages", 400));
      echo("</td></tr><tr><td>");
      echo(CreateButton($playerID, "Top", 8, $options[$i]));
      echo(CreateButton($playerID, "Bottom", 9, $options[$i]));
      echo("</td></tr>");
      echo("</table>");
      echo("</td>");
    }
    echo("</tr></table>");
  }

  if($turn[0] == "CHOOSEDECK" && $turn[1] == $playerID)
  {
    echo("<div display:inline;'>");
    $options = explode(",", $turn[2]);
    for($i=0; $i<count($options); ++$i)
    {
      echo(Card($myDeck[$options[$i]], "CardImages", 400, 11, 0, 0, 0, 0, strval($options[$i])));
    }
    echo("</div>");
  }

  if(($turn[0] == "MAYCHOOSEARSENAL" || $turn[0] == "CHOOSEARSENAL" || $turn[0] == "CHOOSEARSENALCANCEL") && $turn[1] == $playerID)
  {
    echo("<div display:inline;'>");
    $options = explode(",", $turn[2]);
    for($i=0; $i<count($options); ++$i)
    {
      echo(Card($myArsenal[$options[$i]], "CardImages", 400, 18, 0, 0, 0, 0, strval($options[$i])));
    }
    echo("</div>");
  }

  if(($turn[0] == "CHOOSETHEIRHAND") && $turn[1] == $playerID)
  {
    echo("<div display:inline;'>");
    $options = explode(",", $turn[2]);
    for($i=0; $i<count($options); ++$i)
    {
      echo(Card($theirHand[$options[$i]], "CardImages", 400, 18, 0, 0, 0, 0, strval($options[$i])));
    }
    echo("</div>");
  }

  if(($turn[0] == "CHOOSEDISCARD" || $turn[0] == "MAYCHOOSEDISCARD" || $turn[0] == "CHOOSEDISCARDCANCEL") && $turn[1] == $playerID)
  {
    echo("<div display:inline;'>");
    $options = explode(",", $turn[2]);
    for($i=0; $i<count($options); ++$i)
    {
      echo(Card($myDiscard[$options[$i]], "CardImages", 400, 18, 0, 0, 0, 0, strval($options[$i])));
    }
    echo("</div>");
  }

  if($turn[0] == "HANDTOPBOTTOM" && $turn[1] == $playerID)
  {
    echo("<table><tr>");
    for($i=0; $i<count($myHand); ++$i)
    {
      echo("<td>");
      echo(Card($myHand[$i], "CardImages", 300));
      echo("</td>");
    }
    echo("</tr><tr>");
    for($i=0; $i<count($myHand); ++$i)
    {
      echo("<td>");
      echo(CreateButton($playerID, "Top", 12, $i));
      echo("</span><span>");
      echo(CreateButton($playerID, "Bottom", 13, $i));
      echo("</span></div>");
      echo("</td>");
    }
    echo("</tr><table>");
  }

  if($turn[0] == "CHOOSECOMBATCHAIN" && $turn[1] == $playerID)
  {
    echo("<div display:inline;'>");
    $options = explode(",", $turn[2]);
    for($i=0; $i<count($options); ++$i)
    {
      echo(Card($combatChain[$options[$i]], "CardImages", 400, 15, 0, 0, 0, 0, strval($options[$i])));
    }
    echo("</div>");
  }

  if($turn[0] == "CHOOSECHARACTER" && $turn[1] == $playerID)
  {
    echo("<div display:inline;'>");
    $options = explode(",", $turn[2]);
    for($i=0; $i<count($options); ++$i)
    {
      echo(Card($myCharacter[$options[$i]], "CardImages", 400, 15, 0, 0, 0, 0, strval($options[$i])));
    }
    echo("</div>");
  }

  if($turn[0] == "CHOOSETHEIRCHARACTER" && $turn[1] == $playerID)
  {
    echo("<div display:inline;'>");
    $options = explode(",", $turn[2]);
    for($i=0; $i<count($options); ++$i)
    {
      echo(Card($theirCharacter[$options[$i]], "CardImages", 400, 15, 0, 0, 0, 0, strval($options[$i])));
    }
    echo("</div>");
  }

  if($turn[0] == "PDECK")
  {
    echo("<div display:inline;'>");
    for($i=0; $i<count($myPitch); $i+=1) {
      echo(Card($myPitch[$i], "CardImages", 400, 6, 0));
    }
    echo("</div>");
  }

  if(($turn[0] == "MULTICHOOSETHEIRDISCARD" || $turn[0] == "MULTICHOOSEDISCARD" || $turn[0] == "MULTICHOOSEHAND" || $turn[0] == "MULTICHOOSEDECK" || $turn[0] == "MULTICHOOSETEXT") && $turn[1] == $playerID)
  {
    echo("<div display:inline;'>");
    $params = explode("-", $turn[2]);
    $options = explode(",", $params[1]);
    echo("<h3>Choose up to " . $params[0] . " card" . ($params[0] > 1 ? "s." : ".") . "<h3>");
    echo(CreateForm($playerID, "Submit", 19, count($options)));
    echo("<table><tr>");
    for($i=0; $i<count($options); ++$i)
    {
      echo("<td>");
      if($turn[0] == "MULTICHOOSEDISCARD") echo(Card($myDiscard[$options[$i]], "CardImages", 250));
      else if($turn[0] == "MULTICHOOSETHEIRDISCARD") echo(Card($theirDiscard[$options[$i]], "CardImages", 250));
      else if($turn[0] == "MULTICHOOSEHAND") echo(Card($myHand[$options[$i]], "CardImages", 250));
      else if($turn[0] == "MULTICHOOSEDECK") echo(Card($myDeck[$options[$i]], "CardImages", 250));
      else if($turn[0] == "MULTICHOOSETEXT") echo(str_replace("_", " ", $options[$i]));
      echo("</td>");
    }
    echo("</tr><tr>");
    for($i=0; $i<count($options); ++$i)
    {
      echo("<td>");
      echo(CreateCheckbox($i, strval($options[$i])));
      echo("</td>");
    }
    echo("</tr></table>");
    echo("</form>");
    echo("</div>");
  }

  //Display opponent's stuff
  //Opponent hand
  echo("<div style='position: fixed; top: 0px; left: 50%; height: 50px; width:580px; border:1px solid black; display:inline;'><span style='height:100%; vertical-align:middle; display:inline-block'>Their hand:&nbsp;</span>");
  for($i=0; $i<count($theirHand); ++$i) {
    echo(Card("cardBack", "CardImages", 50, 0, 0, 0, -1));
  }
  echo("<span style='position:relative;'><img style='padding-left:20px; height:50px; width:50px;' src='./Images/Resource.png'><div style='position:absolute; top:-30px; left:20px; width:50px; font-size:30; color:white; text-align:center;'>" . $theirResources[0] . "</div></img></span>");
  echo("<div style='display:inline-block; padding-left:20px; height:50px; width:100px;'><div style='position:relative;heigh:100%;width:100%;'><span style='position:absolute; font-size: 24px; top:15px; left:20px;'>$theirHealth</span></div><img style='display:inline-block; height:100%; width:100%;' src='./CardImages/healthSymbol.png' /></div>");
  echo("<div style='position:relative; display:inline-block; top:-20px; height:50px; font-size:20; text-align:center; padding-left:20px;'>Deck: " . count($theirDeck) . " cards</div>");
  echo("</div>");

  //Now display their Auras and Items
  echo("<div style='position: fixed; top:250px; left:50%; display:inline;'>");
  if(count($theirAuras) > 0)
  {
    echo("<div style='display:inline-block;'>");
    echo("<h3>Their Auras:</h3>");
    for($i=0; $i<count($theirAuras); $i+=2)
    {
      echo(Card($theirAuras[$i], "CardImages", 180, 0, 1, 0, 0, $theirAuras[$i+1]));
    }
    echo("</div>");
  }
  if(count($theirItems) > 0)
  {
    echo("<div style='display:inline-block;'>");
    echo("<h3>Their Items:</h3>");
    for($i=0; $i<count($theirItems); $i+=ItemPieces())
    {
      echo(Card($theirItems[$i], "CardImages", 180, 0, 1, $theirItems[$i+2] !=2 ? 1 : 0, 0, $theirItems[$i+1]));
    }
    echo("</div>");
  }
    echo("</div>");

  //Now display character and equipment
  echo("<div style='position: fixed; top:10px; left:40%; display:inline;'><h3>Their Equipment:</h3>");
  for($i=0; $i<count($theirCharacter); $i+=CharacterPieces())
  {
    $counters = CardType($theirCharacter[$i]) == "W" ? $theirCharacter[$i+3] : $theirCharacter[$i+4];
    if($theirCharacter[$i+2] > 0) $counters = $theirCharacter[$i+2];//TODO: display both kinds of counters?
    echo(Card($theirCharacter[$i], "CardImages", 180, 0, 1, $theirCharacter[$i+1] !=2 ? 1 : 0, 0, $counters));
  }
  echo("</div>");
  //Now display arsenal
  if($theirArsenal != "")
  {
    echo("<div style='position: fixed; top:10px; left:83%; display:inline;'><h3 style='width:130px; background-color: rgba(255,255,255,0.70);'>Their Arsenal:</h3>");
    for($i=0; $i<count($theirArsenal); $i+=ArsenalPieces())
    {
      if($theirArsenal[$i+1] == "UP") echo(Card($theirArsenal[$i], "CardImages", 180, 0, 0));
      else echo(Card("cardBack", "CardImages", 180, 0, 0));
    }
    echo("</div>");
  }


  echo(CreatePopup("myDiscardPopup", $myDiscard, 1, 0, "Your Discard"));
  echo(CreatePopup("myBanishPopup", [], 1, 0, "Your Banish", 1, BanishUI()));
  echo(CreatePopup("myStatsPopup", [], 1, 0, "Your Game Stats", 1, CardStats($playerID) . "<BR>" . CreateButton($playerID, "Revert Gamestate", 10000, 0, "24px")));

  //Display the player's hand at the bottom of the screen
  echo("<div style='position: fixed; left:10px; bottom:10px; width:40%; display:inline;'>");
  //echo("<h3 style='background-color: rgba(255,255,255,0.70); width:100px;'>Your hand:</h3>");
  echo("<div style='height:60px;'><div style='background-color: rgba(255,255,255,0.70); position: absolute; top:0px; left: 5px; height: 50px; width:700px; border:1px solid green; display:inline;'>");
  echo("<span style='position:relative; display:inline-block;'><img style='padding-left:20px; height:50px; width:50px;' src='./Images/Resource.png'><div style='position:absolute; top:10px; left:20px; width:50px; font-size:30; color:white; text-align:center;'>" . $myResources[0] . "</div></img></span>");
  echo("<div style='position:relative; display:inline-block; padding-left:20px; height:50px; width:100px;'><div style='position:relative;heigh:100%;width:100%;'><span style='position:absolute; font-size: 24px; top:15px; left:20px;'>$myHealth</span></div><img style='display:inline-block; height:100%; width:100%;' src='./CardImages/healthSymbol.png' /></div>");

  echo("<div title='The number of cards remaining in your deck.' style='cursor:default; position:relative; display:inline-block; height:50px; padding-left:10px;'><img style='display:inline-block; padding-left:10px; height:50px; width:50px;' src='./Images/deckIcon.png'></img> <div style='font-size:20; position:relative; display:inline-block; height:50px;top:-20px;'>" . count($myDeck) . " cards</div></div>");

  echo("<div title='Click to view the cards in your Graveyard.' style='cursor:pointer; position:relative; display:inline-block; height:50px; font-size:20; text-align:center; padding-left:10px;' onclick='(function(){ document.getElementById(\"myDiscardPopup\").style.display = \"inline\";})();'><img style='padding-left:10px; height:50px; width:50px;' src='./Images/graveyardIcon.png'></img> <div style='position:relative; top:-20px; display:inline-block;'>" . count($myDiscard) . " cards</div></div>");

  if(count($myBanish) > 0) echo("<div title='Click to view the cards in your Banish zone.' style='cursor:pointer; position:relative; display:inline-block; height:50px; font-size:20; text-align:center; padding-left:10px;' onclick='(function(){ document.getElementById(\"myBanishPopup\").style.display = \"inline\";})();'><img style='padding-left:10px; height:50px; width:50px;' src='./Images/banishIcon.png'></img> <div style='position:relative; top:-20px; display:inline-block;'>" . (count($myBanish)/BanishPieces()) . " cards</div></div>");

  if(CardTalent($myCharacter[0]) == "LIGHT" || count($mySoul) > 0) echo("<div style='position:relative; display:inline-block; top:-20px; height:50px; font-size:20; text-align:center; padding-left:20px;'>Soul: " . count($mySoul) . " cards</div>");

echo("<div title='Click to view the game stats.' style='cursor:pointer; position:relative; display:inline-block; top:-20px; height:50px; font-size:20; text-align:center; padding-left:20px;' onclick='(function(){ document.getElementById(\"myStatsPopup\").style.display = \"inline\";})();'>Stats</div>");
  echo("</div></div>");
  $actionType = $turn[0] == "ARS" ? 4 : 2;
  if(strpos($turn[0], "CHOOSEHAND") !== false && $turn[0] != "MULTICHOOSEHAND") $actionType = 16;
  for($i=0; $i<count($myHand); ++$i) {
    $playable = $turn[0] == "ARS" || IsPlayable($myHand[$i], $turn[0], "HAND") || ($actionType == 16 && strpos("," . $turn[2] . ",", "," . $i . ",") !== false);
    $border = CardBorderColor($myHand[$i], "HAND", $playable);
    $actionData = $actionType == 16 ? strval($i) : "";
    echo(Card($myHand[$i], "CardImages", 180, $currentPlayer == $playerID && $playable ? $actionType : 0, 1 , 0, $border, 0, $actionData));
  }
  echo(BanishUI("HAND"));

  //Now display Auras and items
  echo("<div style='background-color: rgba(255,255,255,0.70); position: fixed; bottom:200px; left:50%;'>");
  if(count($myAuras) > 0)
  {
    echo("<div style='display:inline-block;'>");
    echo("<h3>Your Auras:</h3>");
    for($i=0; $i<count($myAuras); $i+=2)
    {
      echo(Card($myAuras[$i], "CardImages", 180, 0, 1, 0, 0, $myAuras[$i+1]));
    }
    echo("</div>");
  }
  if(count($myItems) > 0)
  {
    echo("<div style='display:inline-block;'>");
    echo("<h3>Your Items:</h3>");
    for($i=0; $i<count($myItems); $i+=ItemPieces())
    {
      $playable = IsPlayable($myItems[$i], $turn[0], "PLAY", $i);
      $border = CardBorderColor($myItems[$i], "PLAY", $playable);
      echo(Card($myItems[$i], "CardImages", 180, $currentPlayer == $playerID && $turn[0] != "P" && $playable ? 10 : 0, 1, $myItems[$i+2] !=2 ? 1 : 0, $border, $myItems[$i+1], strval($i)));
    }
    echo("</div>");
  }
  echo("</div>");

  //Now display character and equipment
  echo("<div style='position: fixed; bottom:10px; left:40%; display:inline;'><h3 style='width:140px; background-color: rgba(255,255,255,0.70);'>Your Equipment:</h3>");
  for($i=0; $i<count($myCharacter); $i+=CharacterPieces())
  {
    $counters = CardType($myCharacter[$i]) == "W" ? $myCharacter[$i+3] : $myCharacter[$i+4];
    if($myCharacter[$i+2] > 0) $counters = $myCharacter[$i+2];//TODO: Display both kinds of counters
    $playable = $myCharacter[$i+1] == 2 && IsPlayable($myCharacter[$i], $turn[0], "CHAR", $i);
    $border = CardBorderColor($myCharacter[$i], "CHAR", $playable);
    echo(Card($myCharacter[$i], "CardImages", 180, $currentPlayer == $playerID && $playable ? 3 : 0, 1, $myCharacter[$i+1] !=2 ? 1 : 0, $border, $counters, strval($i)));
  }
  echo("</div>");

  //Now display arsenal
  if(count($myArsenal) > 0)
  {
    echo("<div style='position: fixed; bottom:10px; left:83%; display:inline;'><h3 style='width:130px; background-color: rgba(255,255,255,0.70);'>Your Arsenal:</h3>");
    for($i=0; $i<count($myArsenal); $i+=ArsenalPieces())
    {
      $playable = $turn[0] != "P" && IsPlayable($myArsenal[$i], $turn[0], "ARS");
      $border = CardBorderColor($myArsenal[$i], "ARS", $playable);
      echo("<div>");
      echo(Card($myArsenal[$i], "CardImages", 180, $currentPlayer == $playerID && $playable ? 5 : 0, 1, 0, $border, 0, strval($i)));
      if($myArsenal[$i+1] == "UP") echo("<img style='position:absolute; left:" . (40 + ($playable ? 5 : 0)) . "px; bottom:3px; height:70px; ' src='./Images/faceUp.png' title='This arsenal card is face up.'></img>");
      else echo("<img style='position:absolute; left:" . (40 + ($playable ? 5 : 0)) . "px; bottom:" . (3 + ($playable ? 5 : 0)) . "px; height:70px; ' src='./Images/faceDown.png' title='This arsenal card is face down.'></img>");
      echo("</div>");
    }
    echo("</div>");
  }

  echo("</div>");//End play area div

  //Display the log
  echo("<div id='gamelog' style='background-color: rgba(255,255,255,0.70); position:fixed; display:inline; width:200px; height: 40%; top:20%; right:10px; overflow-y: scroll;'>");

  EchoLog($gameName, $playerID);
  echo("</div>");

  echo("<div id='chatbox' style='position:fixed; display:inline; width:200px; height: 50px; top:62%; right:10px;'>");
  echo("<input style='width:155px; display:inline;' type='text' id='chatText' name='chatText' value='' autocomplete='off' onkeypress='ChatKey(event)'>");
  echo("<button style='display:inline;' onclick='SubmitChat()'>Chat</button>");
  echo("<input type='hidden' id='gameName' value='" . $gameName . "'>");
  echo("<input type='hidden' id='playerID' value='" . $playerID . "'>");

  function PlayableCardBorderColor($cardID)
  {
    if(HasReprise($cardID) && RepriseActive()) return 3;
    return 0;
  }

  function CardBorderColor($cardID, $from, $isPlayable)
  {
    global $playerID, $currentPlayer;
    if($playerID != $currentPlayer) return 0;
    if($from == "BANISH")
    {
      if(HasBloodDebt($cardID)) return 2;
      if($isPlayable && HasReprise($cardID) && RepriseActive()) return 5;
      if($isPlayable && ComboActive($cardID)) return 5;
      return 4;
    }
    if($isPlayable && ComboActive($cardID)) return 3;
    if($isPlayable && HasReprise($cardID) && RepriseActive()) return 3;
    else if($isPlayable) return 6;
    return 0;
  }

  function CanPassPhase($phase)
  {
    switch($phase)
    {
      case "P": return 0;
      case "PDECK": return 0;
      case "CHOOSEDECK": return 0;
      case "HANDTOPBOTTOM": return 0;
      case "CHOOSECOMBATCHAIN": return 0;
      case "CHOOSECHARACTER": return 0;
      case "CHOOSEHAND": return 0;
      case "CHOOSEHANDCANCEL": return 0;
      case "MULTICHOOSEDISCARD": return 0;
      case "CHOOSEDISCARDCANCEL": return 0;
      case "CHOOSEARCANE": return 0;
      case "CHOOSEARSENAL": return 0;
      case "CHOOSEDISCARD": return 0;
      case "MULTICHOOSEHAND": return 0;
      default: return 1;
    }
  }
?>
</body>
