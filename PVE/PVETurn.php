<head>

<?php

  include '../Libraries/HTTPLibraries.php';

  //We should always have a player ID as a URL parameter
  $gameName=$_GET["gameName"];
  $playerID=TryGet("playerID", 3);

  //First we need to parse the game state from the file
  include "Constants.php";
  include "ZoneGetters.php";
  include "ParseGamestate.php";
  include "../HostFiles/Redirector.php";
  include "../Libraries/UILibraries.php";
  include "../WriteLog.php";
  include "../CardDictionary.php";
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

<body>

<?php


  //Include js files
  echo("<script src=\"../jsInclude.js\"></script>");

  //Display hidden elements
  echo("<div id=\"cardDetail\" style=\"z-index:1000; display:none; position:absolute;\"></div>");

  $turn = &GetGlobalZone("Turn");
  //Display background

  if($turn[0] == "0") $rg = "radial-gradient(50% 43.6% at 50% 50%, rgba(23, 85, 143, 0.9) 0%, rgba(26, 32, 38, 0.9) 100%)";
  else if($turn[0] == "1") $rg = "radial-gradient(50% 43.6% at 50% 50%, rgba(76, 23, 143, 0.9) 0%, rgba(26, 32, 38, 0.9) 100%)";
  echo("<div style='position:absolute; z-index:-100; left:0px; top:0px; width:100%; height:100%; background: " . $rg . ", url(PVEImages/circuit.png), #000000;'></div>");

  echo("<div style='display:flex; flex-direction:row;'>");
  echo("<div id='stanceArea' style='flex: 0 0 200px;'>");
  if($turn[0] == "0") echo("<img style='position:absolute; z-index:-99; object-fit: cover; top:0px; left:0px; height:100%; width:200px;' src='./PVEImages/leftPanel.svg' />");
  else if($turn[0] == "1") echo("<img style='position:absolute; z-index:-99; object-fit: cover; top:0px; left:0px; height:100%; width:200px;' src='./PVEImages/leftPanelBoss.svg' />");
?>
<?php
  $stanceDeck = &GetGlobalZone("StanceDeck");
  $stance = &GetGlobalZone("Stance");
  echo("<div style='top:8px; left:8px;'>" . CreateButton($playerID, "Undo", 10000, 0, "20px", "./PVEImages/undo.png") . "</div>");
  echo("<div style='padding-top:100px;'>");
  echo("<div style='width:171px; text-align:right; position:absolute; color:white;'>Deck - " . count($stanceDeck) . "/" . (count($stanceDeck) + count($stance)) . "</div>");
  echo("<div style='position:absolute; top:285px; left:40px;'>" . CreateButton($playerID, "Flip Stance", 5, 0, "20px", "./PVEImages/flipStance.png") . "</div>");
  echo("<img style='height:170px; width:171px;' src='./PVEImages/cardBack.png' /></div>");
  if(count($stance) > 0) echo(Card($stance[0], "../CardImages", 200, 0, 1));
  echo("<div>" . CreateButton($playerID, "Create Barricade", 6, 0, "20px", "./PVEImages/createBarricade.png") . "</div>");
?>
</div>

<div id='bossArea' style='flex-grow:1;'>
  <div style='display:flex; flex-direction:column;'>
    <div style='flex-grow:1;'></div>
      <?php
        $bossStatus = GetGlobalZone("BossStatus");
        if($bossStatus[0] == 1) echo("<h1 style='text-align:center; color:white;'>Boss is Recovering! " . CreateButton($playerID, "Clear Status", 12, "-", "20px") . "</h1>");

        $bossCharacter = GetGlobalZone("BossCharacter");
        $bossHealth = GetGlobalZone("BossHealth");
        echo("<div>");
        echo("<table style='width:100%;'><tr><td width='25%'></td><td>");
        echo("<table><tr><td rowspan=5>");
          echo(Card($bossCharacter[0], "../CardImages", 200, 0, 1));
          echo("</td><td>");
          echo("<div>" . CreateButton($playerID, "+5", 9, 5, "20px") . "</div>");
          echo("</td</tr><tr><td>");
          echo("<div>" . CreateButton($playerID, "+1", 9, 1, "20px") . "</div>");
          echo("</td</tr><tr><td>");
          echo("<div style='font-size:20px; background-color:rgba(255,255,255,0.70);'>" . $bossHealth[0] . "</div>");
          echo("</td</tr><tr><td>");
          echo("<div>" . CreateButton($playerID, "-1", 9, -1, "20px") . "</div>");
          echo("</td</tr><tr><td>");
          echo("<div>" . CreateButton($playerID, "-5", 9, -5, "20px") . "</div>");
          echo("</td</tr></table>");
        echo("</td><td>");
        echo("<table><tr><td rowspan=5>");
          $index = BossCharacterPieces();
          echo(Card($bossCharacter[$index], "../CardImages", 200, 0, 1, 0, 0, $bossCharacter[$index+1]));
          echo("</td><td>");
          echo("<div>" . CreateButton($playerID, "+5", 14, $index . "_5", "20px") . "</div>");
          echo("</td></tr><tr><td>");
          echo("<div>" . CreateButton($playerID, "+1", 14, $index . "_1", "20px") . "</div>");
          echo("</td></tr><tr><td>");
          echo("<div style='font-size:20px; background-color:rgba(255,255,255,0.70);'>Counters</div>");
          echo("</td></tr><tr><td>");
          echo("<div>" . CreateButton($playerID, "-1", 14, $index . "_-1", "20px") . "</div>");
          echo("</td></tr><tr><td>");
          echo("<div>" . CreateButton($playerID, "-5", 14, $index . "_-5", "20px") . "</div>");
          echo("</td></tr></table>");
        echo("</td><td>");
        echo("</td><td></td><td width='25%'></td></tr></table>");
        echo("</div>");

        $barricades = GetGlobalZone("Barricades");
        echo("<div>");
        echo("<table><tr>");
        for($i=0; $i<count($barricades); $i+=2)
        {
          echo("<td>");
          echo("<table><tr><td>");
          echo(Card($barricades[$i], "../CardImages", 200, 0, 1, 0, 0, $barricades[$i+1]));
          echo("</td></tr><tr><td>");
          echo(CreateButton($playerID, "Add to Chain", 7, $i));
          echo(CreateButton($playerID, "Delete", 8, $i));
          echo("</td></tr>");
          echo("</table>");
          echo("</td>");
        }
        echo("</tr></table>");
        echo("</div>");

        echo("<div>");
        echo("<table><tr>");
        $combatChain = GetGlobalZone("CombatChain");
        for($i=0; $i<count($combatChain); $i+=2)
        {
          echo("<td>");
          echo("<table><tr><td colspan='2'>");
          echo(Card($combatChain[$i], "../CardImages", 200, 0, 1, 0, 0, $combatChain[$i+1]));
          echo("</td></tr><tr><td>");
          if(CardType($combatChain[$i]) == "B") echo(CreateButton($playerID, "Remove from chain", 10, $i));
          echo("</td><td>");
          if(CardType($combatChain[$i]) == "B") echo(CreateButton($playerID, "-1 Counter", 11, $i));
          echo("</td></tr></table>");
          echo("</td>");
        }
        echo("</tr></table>");
        echo("</div>");
       ?>
  </div>
</div>

<div id='actionArea' style='flex: 0 0 300px;'>
<?php
  $bossDeck = &GetGlobalZone("BossDeck");
  $bossPitch = &GetGlobalZone("BossPitch");
  $bossResources = &GetGlobalZone("BossResources");
  if($turn[0] == "0") echo("<img style='position:absolute; z-index:-99; object-fit: cover; top:0px; right:0px; height:100%; width:200px;' src='./PVEImages/rightPanelPlayer.svg' />");
  else if($turn[0] == "1") echo("<img style='position:absolute; z-index:-99; object-fit: cover; top:0px; right:0px; height:100%; width:200px;' src='./PVEImages/rightPanelBoss.svg' />");
  echo("<div style='color:white;'>Boss Deck Count: " . count($bossDeck) . "</div>");
  //echo("<div style='color:white;'>Boss Pitch Count: " . count($bossPitch) . "</div>");
  if($turn[0] == "0") echo("<div style='color:white;'>Cards used this turn: " . $turn[2] . "/" . $turn[1] . "</div>");
  if($turn[0] == "1") echo("<div style='color:white;'>Resources Float/Required: " . $bossResources[0] . "/" . $bossResources[1] . "</div>");
  echo("<table style='width:100%; height:200px;'><tr><td style='width:50%;'>");
  if(count($bossPitch) > 0) echo(Card($bossPitch[count($bossPitch)-1], "../CardImages", 180, 0, 1));
  else echo("<span style='display:inline-block; min-width:120px;'></span>");
  echo("</td><td style='width:50%;>");
  echo(Card("cardBack", "../CardImages", 180));
  echo("</td></tr></table>");

  echo("<table style='width:100%;'><tr><td>");
  if($turn[0] == "0") echo("<div style='float:right;'>" . CreateButton($playerID, "Switch Turn", 13, 0, "20px", "./PVEImages/playerToggle.png") . "</div>");
  if($turn[0] == "1") echo("<div style='float:right;'>" . CreateButton($playerID, "Switch Turn", 13, 0, "20px", "./PVEImages/bossToggle.png") . "</div>");
  echo("</td></tr><tr><td>");
  echo("<div style='float:right;'>" . CreateButton($playerID, "Clear Pitch", 4, 0, "20px", "./PVEImages/clearPitch.png") . "</div>");
  echo("</td></tr><tr><td>");
  if($turn[0] == "1")
  {
    echo("<div style='float:right;'>" . CreateButton($playerID, "Attack", 0, 0, "20px", "./PVEImages/attackButton.png") . "</div>");
  }
  else if($turn[0] == "0")
  {
    echo("<div style='float:right;'>" . CreateButton($playerID, "Defend", 2, 0, "20px", "./PVEImages/defendButton.png"). "</div>");
  }
  echo("</td></tr><tr><td>");
  echo("<div style='float:right;'>" . CreateButton($playerID, "Pitch", 1, 0, "20px", "./PVEImages/pitchButton.png"). "</div>");
  echo("</td></tr><tr><td>");
  echo("<div style='float:right;'>" . CreateButton($playerID, "Close Chain", 3, 0, "20px", "./PVEImages/closeChainButton.png"). "</div>");
echo("</td></tr></table>");
?>
</div>
</div>
<?php
/*
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

*/


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
