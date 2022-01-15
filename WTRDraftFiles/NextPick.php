<head>

<?php

  include '../Libraries/HTTPLibraries.php';

  //We should always have a player ID as a URL parameter
  $gameName=$_GET["gameName"];
  $playerID=TryGet("playerID", 3);

  //First we need to parse the game state from the file
  include "ZoneGetters.php";
  include "ParseGamestate.php";
  include "../HostFiles/Redirector.php";
  include "../Libraries/UILibraries.php";
  include "../WriteLog.php";
/*
  if($currentPlayer == $playerID) $icon = "ready.png";
  else $icon = "notReady.png";
  echo '<link rel="shortcut icon" type="image/png" href="./HostFiles/' . $icon . '"/>';
*/

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
  echo("<script src=\"../jsInclude.js\"></script>");

  //Display hidden elements
  echo("<div id=\"cardDetail\" style=\"z-index:1000; display:none; position:absolute;\"></div>");

  //Display background
  echo("<div style='position:absolute; z-index:-100; left:0px; top:0px; width:100%; height:100%;'><img style='height:100%; width:100%;' src='../CardImages/findCenterBackground.jpg' /></div>");


  echo("<h1 style='width:85%; text-align: center'>Pack Locations</h1>");
  echo("<table style='width:85%;'><tr>");
  for($i=1; $i<=$numPlayers; ++$i)
  {
    if($i == 5 || $i == 9) echo("</tr><tr>");
    echo("<td>");
    $numPacks = GetZoneCount($i, "DecisionQueue", "CHOOSECARD");
    if($i == $playerID) echo("<span style='font-size: 40px;'>You ");
    else echo("<span style='font-size: 40px;'>Player $i ");
    for($j=0; $j<$numPacks; ++$j) echo(Card("cardBack", "../CardImages", 50, 0, 0, 0, -1));
    echo("</span>");
    echo("</td>");
  }
  echo("</tr></table>");

  $choices = &GetZone($playerID, "ChosenCards");
  echo(CreatePopup("myPickPopup", $choices, 1, 0, "Your Card Choices", 1, "", "../"));

  echo("<div style='position:fixed; width:100%; top:35%; height:65%;'>");
  $myPackData = &GetZone($playerID, "PackData");
  $myDQ = &GetZone($playerID, "DecisionQueue");
  $header = "<h1>Pack " . $myPackData[0] . " Pick " . $myPackData[1] . (count($myDQ)==0?" (Waiting for other players)":"");
  $draftFinished = ($myPackData[0] == 3 && $myPackData[1] == 16);
  if($draftFinished) $header = "<h1>Draft Finished";
  $header .= "&nbsp;-&nbsp;<span title='Click to see the cards you chose.' style='cursor:pointer;' onclick='(function(){ document.getElementById(\"myPickPopup\").style.display = \"inline\";})();'>Your Chosen Cards</span>";
  $header .= "</h1>";
  echo($header);
  if($draftFinished)
  {
    echo("<h2>Note: If you want to try multiple heros, open this page in another tab</h2>");
    echo("<form style='width:100%;display:inline-block;' action='" . $redirectPath . "/WTRDraftFiles/LimitedPractice.php'>");
?>
    <label for='rhinar'>Rhinar</label>
    <input type='radio' id='rhinar' name='hero' value='rhinar'><br>
    <label for='bravo'>Bravo</label>
    <input type='radio' id='bravo' name='hero' value='bravo'><br>
    <label for='katsu'>Katsu</label>
    <input type='radio' id='katsu' name='hero' value='katsu'><br>
    <label for='dori'>Dorinthea</label>
    <input type='radio' id='dori' name='hero' value='dori'><br>
    <input type='hidden' id='gameName' name='gameName' value='<?php echo($gameName); ?>' />
    <input type='hidden' id='playerID' name='playerID' value='<?php echo($playerID); ?>' />
    <input type='hidden' id='set' name='set' value='WTR' />
    <input type='submit' style='font-size:20px;' value='Test Draft Pool' />

<?php
    echo("</form>");

  }
  if(count($myDQ) > 0 && $myDQ[0] == "CHOOSECARD")
  {
    echo("<div display:inline;'>");
    $options = explode(",", $myDQ[1]);
    for($i=0; $i<count($options); ++$i)
    {
      echo(Card($options[$i], "../CardImages", 200, 1, 1, 0, 0, 0, strval($options[$i])));
    }
    echo("</div>");
  }
  echo("</div>");//End cards div

  echo("</div>");//End play area div

  //Display the log
  echo("<div id='gamelog' style='background-color: rgba(255,255,255,0.70); position:fixed; display:inline; width:12%; height: 40%; top:10px; right:10px; overflow-y: scroll;'>");

  EchoLog($gameName, $playerID);
  echo("</div>");

  echo("<div id='chatbox' style='position:fixed; display:inline; width:200px; height: 50px; top:42%; right:10px;'>");
  //echo("<input style='width:155px; display:inline;' type='text' id='chatText' name='chatText' value='' autocomplete='off' onkeypress='ChatKey(event)'>");
  //echo("<button style='display:inline;' onclick='SubmitChat()'>Chat</button>");
  echo("<input type='hidden' id='gameName' value='" . $gameName . "'>");
  echo("<input type='hidden' id='playerID' value='" . $playerID . "'>");

  function PlayableCardBorderColor($cardID)
  {
    if(HasReprise($cardID) && RepriseActive()) return 3;
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
