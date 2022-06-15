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
  echo("<div style='position:absolute; z-index:-100; left:0px; top:0px; width:100%; height:100%;'><img style='height:100%; width:100%;' src='../Images/findCenterBackground.jpg' /></div>");


  $choices = &GetZone($playerID, "ChosenCards");
  echo(CreatePopup("myPickPopup", $choices, 1, 0, "Your Cards", 1, "", "../"));

  echo("<div style='position:fixed; width:100%; top:35%; height:65%;'>");
  $draftFinished = true;
  if($draftFinished) $header = "<h1>Sealed Pool";
  $header .= "&nbsp;-&nbsp;<span title='Click to see the cards in your sealed pool.' style='cursor:pointer;' onclick='(function(){ document.getElementById(\"myPickPopup\").style.display = \"inline\";})();'>Your Cards</span>";
  $header .= "</h1>";
  echo($header);
  if($draftFinished)
  {
    echo("<h2>Note: If you want to try multiple heros, open this page in another tab</h2>");
    echo("<form style='width:100%;display:inline-block;' action='" . $redirectPath . "/SealedFiles/LimitedPractice.php'>");
?>
    <label for='rhinar'>Dromai</label>
    <input type='radio' id='dromai' name='hero' value='dromai'><br>
    <label for='bravo'>Fai</label>
    <input type='radio' id='fai' name='hero' value='fai'><br>
    <label for='katsu'>Iyslander</label>
    <input type='radio' id='iyslander' name='hero' value='iyslander'><br>
    <input type='hidden' id='gameName' name='gameName' value='<?php echo($gameName); ?>' />
    <input type='hidden' id='playerID' name='playerID' value='<?php echo($playerID); ?>' />
    <input type='hidden' id='set' name='set' value='UPR' />
    <input type='submit' style='font-size:20px;' value='Test Draft Pool' />

<?php
    echo("</form>");

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
