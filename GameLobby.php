
  <head>
    <meta charset="utf-8">
    <title>Flesh and Blood Online</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/style.css">
  </head>

<?php
  ob_start();
  include "WriteLog.php";
  include "CardDictionary.php";
  include "HostFiles/Redirector.php";
  include "Libraries/UILibraries2.php";
  ob_end_clean();

  $gameName=$_GET["gameName"];
  $playerID=$_GET["playerID"];

  if(!file_exists("./Games/" . $gameName . "/GameFile.txt"))
  {
    header("Location: " . $redirectPath . "/MainMenu.php");//If the game file happened to get deleted from inactivity, redirect back to the main menu instead of erroring out
    exit;
  }

  ob_start();
  include "MenuFiles/ParseGamefile.php";
  ob_end_clean();

  $yourName = ($playerID == 1 ? $p1uid : $p2uid);
  $theirName = ($playerID == 1 ? $p2uid : $p1uid);

  if($gameStatus == $MGS_GameStarted)
  {
    $authKey = ($playerID == 1 ? $p1Key : $p2Key);
    header("Location: " . $redirectPath . "/NextTurn3.php?gameName=$gameName&playerID=$playerID&authKey=$authKey");
    exit;
  }

  $icon = "ready.png";

  if($gameStatus == $MGS_ChooseFirstPlayer) $icon = $playerID == $firstPlayerChooser ? "ready.png" : "notReady.png";
  else if($playerID == 1 && $gameStatus < $MGS_ReadyToStart) $icon = "notReady.png";
  else if($playerID == 2 && $gameStatus >= $MGS_ReadyToStart) $icon = "notReady.png";
  //if($gameStatus == "") $MGS_GameStarted;

  echo '<title>Game Lobby</title> <meta http-equiv="content-type" content="text/html; charset=utf-8" > <meta name="viewport" content="width=device-width, initial-scale=1.0">';
  echo '<link id="icon" rel="shortcut icon" type="image/png" href="./HostFiles/' . $icon . '"/>';
?>

<script>
function copyText() {
  gameLink = document.getElementById("gameLink");
  gameLink.select();
  gameLink.setSelectionRange(0, 99999);

  // Copy it to clipboard
  document.execCommand("copy");
}
</script>

<style>
body {
  margin:0px;
  color:rgb(240, 240, 240);
  overflow-y:hidden;
}

h1 {
  margin-top: 6px;
  text-align:center;
  width:100%;
}

h2 {
  text-align:center;
  width:100%;
}

</style>
<script src="./jsInclude.js"></script>
</head>

<body onload='OnLoadCallback(<?php echo(filemtime("./Games/" . $gameName . "/gamelog.txt")); ?>)'>

<audio id="playerJoinedAudio">
  <source src="./Assets/playerJoinedSound.mp3" type="audio/mpeg">
</audio>

<div id="cardDetail" style="display:none; position:absolute;"></div>

<div style="height: 1000px;
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;
  background-color: #111111;
  overflow: hidden;background-image: url('Images/rout.jpg');">

<div style="position:absolute; z-index:1; top:20px; left:20px; width:290px; height:46%;
background-color:rgba(74, 74, 74, 0.8);
border: 2px solid black;
border-radius: 3px;">
<?php
  $theirDisplayName = ($theirName != "-" ? $theirName . "'s" : "Opponent's ");
  echo("<h1>$theirDisplayName Hero</h1>");

  $otherHero = "cardBack";
  echo("<div id='oppHero' style='padding-left:5%;'>");
  echo(Card($otherHero, "CardImages", 350, 0, 0));
  echo("</div>");

?>
</div>

<div style="position:absolute; z-index:1; top:20px; left:330px; width:290px; height:46%; background-color:rgba(74, 74, 74, 0.8);
border: 2px solid black;
border-radius: 3px;">
<?php
  $displayName = ($yourName != "-" ? $yourName . "'s" : "Your ");
  echo("<h1>$displayName Hero</h1>");

  $deckFile = "./Games/" . $gameName . "/p" . $playerID . "Deck.txt";
  $handler = fopen($deckFile, "r");
  $character = GetArray($handler);

  echo("<div style='padding-left:5%;'>");
  echo(Card($character[0], "CardImages", 350, 0, 0));
  echo("</div>");

  $weapons = ""; $head = ""; $chest = ""; $arms = ""; $legs = ""; $offhand = "";
  for($i=1; $i<count($character); ++$i)
  {
    switch(CardSubtype($character[$i]))
    {
      case "Head": $head = $character[$i]; break;
      case "Chest": $chest = $character[$i]; break;
      case "Arms": $arms = $character[$i]; break;
      case "Legs": $legs = $character[$i]; break;
      case "Off-Hand": $offhand = $character[$i]; break;
      default:
        if($weapons != "") $weapons .= ",";
        $weapons .= $character[$i];
        break;
    }
  }

  $deck = GetArray($handler);
  $headSB = GetArray($handler);
  $chestSB = GetArray($handler);
  $armsSB = GetArray($handler);
  $legsSB = GetArray($handler);
  $offhandSB = GetArray($handler);
  $weaponSB = GetArray($handler);
  $deckSB = GetArray($handler);

  fclose($handler);

?>
</div>

<div id="equipTab" style="position:absolute; z-index:1; cursor:pointer; top:20px; left:640px; width:290px; height:73px;
background-color:rgba(74, 74, 74, 0.8);
border: 2px solid black;
border-radius: 3px;" onclick="TabClick('EQUIP');">

<h1>Your Equipment</h1>
</div>

<div id="equipDisplay" style="position:absolute; z-index:1; top:95px; left:640px; right:20px; bottom:2.5%;
background-color:rgba(74, 74, 74, 0.8);
border: 2px solid black;
border-radius: 3px;">

<div style='margin:10px; margin-left: 20px; width:100%; text-align: left; font-family:Roboto; font-weight: bold; font-style: italic; font-size:20px;'>Click Cards to Select/Unselect</div>

<table>
<?php

    DisplayEquipRow($head, $headSB, "HEAD");
    DisplayEquipRow($chest, $chestSB, "CHEST");
    DisplayEquipRow($arms, $armsSB, "ARMS");
    DisplayEquipRow($legs, $legsSB, "LEGS");

?>
</table>
  <div id="weaponDisplay" style="position:absolute; z-index:2; top:40px; left:54%; right:20px;">
    <table>
    <?php

        $weaponArray = explode(",", $weapons);
        $weapon1 = (count($weaponArray) > 0 ? $weaponArray[0] : "");
        $weapon2 = (count($weaponArray) > 1 ? $weaponArray[1] : "");
        DisplayWeaponRow($weapon1, $weapon2, $weaponSB, "WEAPONS");
        DisplayEquipRow($offhand, $offhandSB, "OFFHAND");

    ?>
    </table>
  </div>
</div>

<div id="deckTab" style="position:absolute; z-index:1; cursor:pointer; top:20px; left:933px; width:290px; height:73px;
background-color:rgba(175, 175, 175, 0.8);
border: 2px solid black;
border-radius: 3px;" onclick="TabClick('DECK');">

<?php
echo("<h1>Your Deck (<span id='mbCount'>" . count($deck) . "</span>/<span>" . (count($deck) + count($deckSB)) . "</span>)</h1>");
?>
</div>

<div id="deckDisplay" style="display:none; position:absolute; z-index:1; top:95px; left:640px; right:20px; bottom:2.5%;
background-color:rgba(74, 74, 74, 0.8);
border: 2px solid black;
border-radius: 3px; overflow-y:scroll; overflow-x:hidden;">

<div style='margin:10px; margin-left: 20px; width:100%; text-align: left; font-family:Roboto, sans-serif; font-weight: bold; font-style: italic; font-size:20px;'>Click Cards to Select/Unselect</div>

<?php

    $count = 0;
    for($i=0; $i<count($deck); ++$i)
    {
      $id = "DECK-" . $count;
      echo("<span style='cursor:pointer; padding-bottom:5px; padding-left:3px;' onclick='CardClick(\"" . $id . "\")'>" . Card($deck[$i], "concat", 130, 0, 1, 0, 0, 0, "", $id) . "</span>");
      ++$count;
    }
    for($i=0; $i<count($deckSB); ++$i)
    {
      $id = "DECK-" . $count;
      echo("<span style='cursor:pointer; padding-bottom:5px; padding-left:3px;' onclick='CardClick(\"" . $id . "\")'>" . Card($deckSB[$i], "concat", 130, 0, 1, 1, 0, 0, "", $id) . "</span>");
      ++$count;
    }
?>
</div>
  <div style="position:absolute; z-index:1; top:50%; left:20px; width:600px; height:47%; font-weight:500; font-size:18px;
    background-color:rgba(74, 74, 74, 0.8);
    border: 2px solid black;
    border-radius: 3px;">
  <h1>Game Lobby</h1>
<?php

  echo("<div id='submitForm' style='display:none; width:100%; text-align: center;'>");
  echo("<form action='./SubmitSideboard.php'>");
    echo("<input type='hidden' id='gameName' name='gameName' value='$gameName'>");
    echo("<input type='hidden' id='playerID' name='playerID' value='$playerID'>");
    echo("<input type='hidden' id='playerCharacter' name='playerCharacter' value=''>");
    echo("<input type='hidden' id='playerDeck' name='playerDeck' value=''>");
    echo("<input type='submit' value='" . ($playerID == 1 ? "Start" : "Ready") . "'>");
  echo("</form>");
  echo("</div>");

  echo("<div id='mainPanel' style='text-align:center;'>");

  echo("</div>");
  echo("<div id='chatbox' style='position:relative; top:2px; left:2%; height: 45px;'>");
  echo("<input style='width:85%; display:inline;' type='text' id='chatText' name='chatText' value='' autocomplete='off' onkeypress='ChatKey(event)'>");
  echo("<button style='display:inline; width:10%; margin-left:5px;' onclick='SubmitChat()'>Chat</button>");
  echo("<input type='hidden' id='gameName' value='" . $gameName . "'>");
  echo("<input type='hidden' id='playerID' value='" . $playerID . "'>");
  echo("</div>");

  echo("<script>");
  echo("var prevGameState = " . $gameStatus . ";");
  echo("function reload() { setInterval(function(){loadGamestate();}, 500); }");

  echo("</script>");

?>
</div>

<script>

function OnLoadCallback(lastUpdate)
{
  <?php
  if($playerID == "1" && $gameStatus == $MGS_ChooseFirstPlayer)
  {
    echo("var audio = document.getElementById('playerJoinedAudio');");
    echo("audio.play();");
  }
   ?>
  UpdateFormInputs();
  var log = document.getElementById('gamelog');
  if(log !== null) log.scrollTop = log.scrollHeight;
  CheckReloadNeeded(0);
}

function UpdateFormInputs()
{
  var playerCharacter = document.getElementById("playerCharacter");
  if(!!playerCharacter) playerCharacter.value = GetCharacterCards();
  var playerDeck = document.getElementById("playerDeck");
  if(!!playerDeck) playerDeck.value = GetDeckCards();
}

function TabClick(tab)
{
  var equipTab = document.getElementById("equipTab");
  var equipDisplay = document.getElementById("equipDisplay");
  var deckTab = document.getElementById("deckTab");
  var deckDisplay = document.getElementById("deckDisplay");
  equipDisplay.style.display = tab == "EQUIP" ? "block" : "none";
  deckDisplay.style.display = tab == "DECK" ? "block" : "none";
  equipTab.style.backgroundColor = tab == "EQUIP" ? "rgba(74, 74, 74, 0.8)" : "rgba(196, 196, 196, 0.7)";
  deckTab.style.backgroundColor = tab == "DECK" ? "rgba(74, 74, 74, 0.8)" : "rgba(196, 196, 196, 0.7)";
}

function CardClick(id)
{
  var idArr = id.split("-");
  if(IsEquipType(idArr[0]))
  {
    var count = 0;
    var overlay = document.getElementById(idArr[0] + "-" + count + "-ovr");
    while(!!overlay)
    {
      if(count != idArr[1]) overlay.style.visibility = "visible";
      else overlay.style.visibility = (overlay.style.visibility == "visible" ? "hidden" : "visible");
      //overlay.style.visibility = (count != idArr[1] ? "visible" : "hidden");
      ++count;
      var overlay = document.getElementById(idArr[0] + "-" + count + "-ovr");
    }
  }
  else if(idArr[0] == "DECK")
  {
    var overlay = document.getElementById(id + "-ovr");
    overlay.style.visibility = (overlay.style.visibility == "hidden" ? "visible" : "hidden");
    var mbCount = document.getElementById("mbCount");
    mbCount.innerText = parseInt(mbCount.innerText) + (overlay.style.visibility == "hidden" ? 1 : -1);
  }
  else if(idArr[0] == "WEAPONS")
  {
    var overlay = document.getElementById(id + "-ovr");
    overlay.style.visibility = (overlay.style.visibility == "hidden" ? "visible" : "hidden");
  }
  UpdateFormInputs();
}

function IsEquipType(type)
{
  switch(type)
  {
    case "HEAD": return true;
    case "CHEST": return true;
    case "ARMS": return true;
    case "LEGS": return true;
    case "OFFHAND": return true;
    default: return false;
  }
}

function GetCharacterCards()
{
  var types = ["WEAPONS", "OFFHAND", "HEAD", "CHEST", "ARMS", "LEGS"];
  var returnValue = "<?php echo($character[0]); ?>";
  //returnValue += "<?php echo(($weapons!="" ? "," . $weapons : "")); ?>";
  for(var i=0; i<types.length; ++i)
  {
    var selected = GetSelectedEquipType(types[i]);
    if(selected != "") returnValue += "," + selected;
  }
  return returnValue;
}

function GetSelectedEquipType(type)
{
  var count = 0;
  var overlay = document.getElementById(type + "-" + count + "-ovr");
  var rv = "";
  while(!!overlay)
  {
    if(overlay.style.visibility == "hidden")
    {
      var imageSrc = document.getElementById(type + "-" + count + "-img").src;
      if(rv != "") rv += ",";
      rv += imageSrc.substring(imageSrc.length-11).split(".")[0];
    }
    ++count;
    var overlay = document.getElementById(type + "-" + count + "-ovr");
  }
  return rv;
}

function GetDeckCards()
{
  var count = 0;
  var returnValue = "";
  var overlay = document.getElementById("DECK-" + count + "-ovr");
  while(!!overlay)
  {
    if(overlay.style.visibility == "hidden")
    {
      var imageSrc = document.getElementById("DECK-" + count + "-img").src;
      if(returnValue != "") returnValue += ",";
      returnValue += imageSrc.substring(imageSrc.length-11).split(".")[0];
    }
    ++count;
    var overlay = document.getElementById("DECK-" + count + "-ovr");
  }
  return returnValue;
}

  function CheckReloadNeeded(lastUpdate) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        if(parseInt(this.responseText) != 0)
        {
          if(parseInt(this.responseText) == 1) location.reload();
          else {
            var responseArr = this.responseText.split("ENDTIMESTAMP");
            document.getElementById("mainPanel").innerHTML = responseArr[1];
            CheckReloadNeeded(parseInt(responseArr[0]));
            var playAudio = document.getElementById("playAudio");
            if(!!playAudio && playAudio.innerText == 1)
            {
              var audio = document.getElementById('playerJoinedAudio');
              audio.play();
            }
            var otherHero = document.getElementById("otherHero");
            if(!!otherHero) document.getElementById("oppHero").innerHTML = otherHero.innerHTML;
            document.getElementById("icon").href = "./HostFiles/" + document.getElementById("iconHolder").innerText;
            var log = document.getElementById('gamelog');
            if(log !== null) log.scrollTop = log.scrollHeight;
            document.getElementById("submitForm").style.display = document.getElementById("submitDisplay").innerHTML;
          }
        }
        else { CheckReloadNeeded(lastUpdate); }
      }
    };
    xmlhttp.open("GET", "GetLobbyRefresh.php?gameName=<?php echo($gameName);?>&playerID=<?php echo($playerID);?>&lastUpdate=" + lastUpdate, true);
    xmlhttp.send();
  }

  function SubmitFirstPlayer(action)
  {
    if(action == 1) action = "Go First";
    else action = "Go Second";
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
      }
    }
    var ajaxLink = "ChooseFirstPlayer.php?gameName=" + <?php echo($gameName); ?>;
    ajaxLink += "&playerID=" + <?php echo($playerID); ?>;
    ajaxLink += "&action=" + action;
    xmlhttp.open("GET", ajaxLink, true);
    xmlhttp.send();
  }

</script>

<?php

function DisplayEquipRow($equip, $equipSB, $name)
{
  $count = 0;
  if($equip != "" || count($equipSB) > 0) echo("<tr>");
  if($equip != "")
  {
    $id = $name . "-" . $count;
    echo("<td>");
    echo("<div onclick='CardClick(\"" . $id . "\")'>");
    echo("<span style='cursor:pointer; padding-bottom:5px; padding-left:3px;'>" . Card($equip, "concat", 130, 0, 1, 0, 0, 0, "", $id) . "</span>");
    echo("</div>");
    echo("</td>");
    ++$count;
  }
  for($i=0; $i<count($equipSB); ++$i)
  {
    $id = $name . "-" . $count;
    echo("<td>");
    echo("<div onclick='CardClick(\"" . $id . "\")'>");
    echo("<span style='cursor:pointer; padding-bottom:5px; padding-left:3px;'>" . Card($equipSB[$i], "concat", 130, 0, 1, 1, 0, 0, "", $id) . "</span>");
    echo("</div>");
    echo("</td>");
    ++$count;
  }

  if($equip != "" || count($equipSB) > 0) echo("</tr>");
}

function DisplayWeaponRow($weapon1, $weapon2, $weaponSB, $name)
{
  $count = 0;
  if($weapon1 != "" || $weapon2 != "" || count($weaponSB) > 0) echo("<tr>");
  if($weapon1 != "")
  {
    $id = $name . "-" . $count;
    echo("<td>");
    echo("<div onclick='CardClick(\"" . $id . "\")'>");
    echo("<span style='cursor:pointer; padding-bottom:5px; padding-left:3px;'>" . Card($weapon1, "concat", 130, 0, 1, 0, 0, 0, "", $id) . "</span>");
    echo("</div>");
    echo("</td>");
    ++$count;
  }
  if($weapon2 != "")
  {
    $id = $name . "-" . $count;
    echo("<td>");
    echo("<div onclick='CardClick(\"" . $id . "\")'>");
    echo("<span style='cursor:pointer; padding-bottom:5px; padding-left:3px;'>" . Card($weapon2, "concat", 130, 0, 1, 0, 0, 0, "", $id) . "</span>");
    echo("</div>");
    echo("</td>");
    ++$count;
  }
  for($i=0; $i<count($weaponSB); ++$i)
  {
    $id = $name . "-" . $count;
    echo("<td>");
    echo("<div onclick='CardClick(\"" . $id . "\")'>");
    echo("<span style='cursor:pointer; padding-bottom:5px; padding-left:3px;'>" . Card($weaponSB[$i], "concat", 130, 0, 1, 1, 0, 0, "", $id) . "</span>");
    echo("</div>");
    echo("</td>");
    ++$count;
  }

  if($weapon1 != "" || $weapon2 != "" || count($weaponSB) > 0) echo("</tr>");
}

?>

<?php
  include_once 'Footer.php'
?>
