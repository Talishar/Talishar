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

  $encounter = &GetZone($playerID, "Encounter");
  echo("<h1 style='width:85%; text-align: center'>Encounter #" . $encounter[0] . "</h1>");

  $health = &GetZone($playerID, "Health");
  echo("<h2 style='width:85%; text-align: center'>Health Remaining: " . $health[0] . "</h2>");


  $deck = &GetZone($playerID, "Deck");
  echo(CreatePopup("myDeckPopup", $deck, 1, 0, "Your Deck (" . count($deck) . " cards)", 1, "", "../"));

  $myDQ = &GetZone($playerID, "DecisionQueue");
  echo("<div style='position:fixed; width:100%; top:35%; height:65%;'>");
  $header = "<h1><span title='Click to view your deck (" . count($deck) . " cards)' style='cursor:pointer;' onclick='(function(){ document.getElementById(\"myDeckPopup\").style.display = \"inline\";})();'>Click to see your deck (" . count($deck) . " cards)</span></h1>";
  echo($header);
  echo("<h2>" . EncounterDescription($encounter[0], $encounter[1]) . "</h2>");
  echo("<BR>");

  if(count($myDQ) > 0)
  {
    if($myDQ[0] == "CHOOSECARD")
    {
      echo("<div display:inline;'>");
      $options = explode(",", $myDQ[1]);
      for($i=0; $i<count($options); ++$i)
      {
        echo(Card($options[$i], "../CardImages", 200, 1, 1, 0, 0, 0, strval($options[$i])));
      }
      echo("</div>");
    }
    else if($myDQ[0] == "BUTTONINPUT")
    {
      $content = "<div display:inline;'>";
      $options = explode(",", $myDQ[1]);
      for ($i = 0; $i < count($options); ++$i) {
        $content .= CreateButton($playerID, str_replace("_", " ", $options[$i]), 2, strval($options[$i]), "24px");
      }
      $content .= "</div>";
      echo CreatePopup("BUTTONINPUT", [], 0, 1, "Choose a button", 1, $content);
    }
    else {
      echo("Bug. This phase not implemented: " . $myDQ[0]);
    }
  }
  else {
    echo("<form style='width:100%;display:inline-block;' action='" . $redirectPath . "/RogueLike/PlayEncounter.php'>");
    echo("<input type='hidden' id='gameName' name='gameName' value='$gameName' />");
    echo("<input type='hidden' id='playerID' name='playerID' value='$playerID' />");
    echo("<input type='submit' style='font-size:20px;' value='Play Encounter' />");
    echo("</form>");
  }

  echo("</div>");//End cards div

  echo("</div>");//End play area div

  //Display the log
  echo("<div id='gamelog' style='background-color: rgba(255,255,255,0.8); position:fixed; display:inline; width:12%; height: 40%; top:10px; right:10px; overflow-y: scroll;'>");

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

  function EncounterDescription($encounter, $subphase)
  {
    switch($encounter)
    {
      case 1:
        if($subphase == "Fight") return "You're attacked by a Woottonhog.";
        else if($subphase == "AfterFight") return "You defeated the Woottonhog.";
      case 2:
        return "You found a campfire. Choose what you want to do.";
      default: return "No encounter text.";
    }
  }
?>
</body>
