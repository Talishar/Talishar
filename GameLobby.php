
<head>

<?php
  include "CardDictionary.php";
  include "HostFiles/Redirector.php";
  include "Libraries/UILibraries.php";

  $gameName=$_GET["gameName"];
  $playerID=$_GET["playerID"];

  if(!file_exists("./Games/" . $gameName . "/GameFile.txt"))
  {
    header("Location: " . $redirectPath . "/MainMenu.php");//If the game file happened to get deleted from inactivity, redirect back to the main menu instead of erroring out
  }

  include "MenuFiles/ParseGamefile.php";

  if($gameStatus == 6)
  {
    header("Location: " . $redirectPath . "/NextTurn2.php?gameName=$gameName&playerID=$playerID");
  }

  $gameStarted = 0;
  $icon = "ready.png";

  if($playerID == 1 && $gameStatus < $MGS_ReadyToStart) $icon = "notReady.png";
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
  font-family: Garamond, serif;
  margin:0px;
  color:rgb(240, 240, 240);
}

h1 {
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

<body onload='OnLoadCallback()'>
<div id="cardDetail" style="display:none; position:absolute;"></div>

<div style="width:100%; height:100%; background-image: url('Images/rout.jpg'); background-size:cover; z-index=0;">

<div style="position:absolute; z-index:1; top:20px; left:20px; width:290px; height:46%; background-color:rgba(59, 59, 38, 0.7);">
<h1>Opponent's Hero</h1>
<?php

  $otherPlayer = $playerID == 1 ? 2 : 1;
  $deckFile = "./Games/" . $gameName . "/p" . $otherPlayer . "Deck.txt";
  $otherHero = "cardBack";
  if(file_exists($deckFile))
  {
    $handler = fopen($deckFile, "r");
    $otherCharacter = GetArray($handler);
    $otherHero = $otherCharacter[0];
    fclose($handler);
  }
  echo("<div style='padding-left:20px;'>");
  echo(Card($otherHero, "CardImages", 350, 0, 0));
  echo("</div>");

?>
</div>

<div style="position:absolute; z-index:1; top:20px; left:330px; width:290px; height:46%; background-color:rgba(59, 59, 38, 0.7);">
<h1>Your Hero</h1>
<?php

  $deckFile = "./Games/" . $gameName . "/p" . $playerID . "Deck.txt";
  $handler = fopen($deckFile, "r");
  $character = GetArray($handler);

  echo("<div style='padding-left:20px;'>");
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

<div id="equipTab" style="position:absolute; z-index:1; cursor:pointer; top:20px; left:640px; width:290px; height:75px; background-color:rgba(59, 59, 38, 0.7);" onclick="TabClick('EQUIP');">
<h1>Your Equipment</h1>
</div>
<div id="equipDisplay" style="position:absolute; z-index:1; top:95px; left:640px; right:20px; bottom:10%; background-color:rgba(59, 59, 38, 0.7);">
<table>
<?php

  DisplayEquipRow($head, $headSB, "HEAD");
  DisplayEquipRow($chest, $chestSB, "CHEST");
  DisplayEquipRow($arms, $armsSB, "ARMS");
  DisplayEquipRow($legs, $legsSB, "LEGS");

?>
</table>
  <div id="weaponDisplay" style="position:absolute; z-index:2; top:0px; left:50%; right:20px;">
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

<div id="deckTab" style="position:absolute; z-index:1; cursor:pointer; top:20px; left:930px; width:290px; height:75px; background-color:rgba(159, 159, 138, 0.7);" onclick="TabClick('DECK');">
<?php
echo("<h1>Your Deck (<span id='mbCount'>" . count($deck) . "</span>/<span>" . (count($deck) + count($deckSB)) . "</span>)</h1>");
?>
</div>
<div id="deckDisplay" style="display:none; position:absolute; z-index:1; top:95px; left:640px; right:20px; bottom:10%; background-color:rgba(59, 59, 38, 0.7); overflow-y:scroll;">

<?php
  $count = 0;
  for($i=0; $i<count($deck); ++$i)
  {
    $id = "DECK-" . $count;
    echo("<span style='cursor:pointer;' onclick='CardClick(\"" . $id . "\")'>" . Card($deck[$i], "CardImages", 150, 0, 1, 0, 0, 0, "", $id) . "</span>");
    ++$count;
  }
  for($i=0; $i<count($deckSB); ++$i)
  {
    $id = "DECK-" . $count;
    echo("<span style='cursor:pointer;' onclick='CardClick(\"" . $id . "\")'>" . Card($deckSB[$i], "CardImages", 150, 0, 1, 1, 0, 0, "", $id) . "</span>");
    ++$count;
  }
?>

</div>

<div style="position:absolute; z-index:1; top:50%; left:20px; width:600px; height:40%; background-color:rgba(59, 59, 38, 0.7);">
<h1>Game Lobby</h1>
<?php
  echo("<div style='text-align:center;'>");

  if($playerID == 1 && $gameStatus < $MGS_Player2Joined)
  {
    echo("<div><input type='text' id='gameLink' value='" . $redirectPath . "/JoinGame.php?gameName=$gameName&playerID=2'><button onclick='copyText()'>Copy Link to Join</button></div>");
  }
      echo("<div id='submitForm' style='display:" . ($playerID == 1 ? ($gameStatus == $MGS_ReadyToStart ? "block" : "none") : ($gameStatus >= $MGS_ReadyToStart ? "none" : "block")) . ";'>");
      echo("<form action='./SubmitSideboard.php'>");
        echo("<input type='hidden' id='gameName' name='gameName' value='$gameName'>");
        echo("<input type='hidden' id='playerID' name='playerID' value='$playerID'>");
        echo("<input type='hidden' id='playerCharacter' name='playerCharacter' value=''>");
        echo("<input type='hidden' id='playerDeck' name='playerDeck' value=''>");
        echo("<input type='submit' value='" . ($playerID == 1 ? "Start" : "Ready") . "'>");
      echo("</form>");
      echo("</div>");


  echo("</div>");

  echo("<h2>Instructions</h2>");
  echo("<ul>");
  echo("<li>Copy link and send to your opponent, or open it yourself in another browser tab.</li>");
  echo("<li>The browser tab icon will turn green when player 2 clicks ready.</li>");
  echo("<li>Use the interface at the right to sideboard cards.</li>");
  echo("<li>Player 1 starts the game when both players are ready.</li>");
  echo("</ul>");


  echo("<script>");
  echo("var prevGameState = " . $gameStatus . ";");
  echo("function reload() { setInterval(function(){loadGamestate();}, 500); }");

  echo("</script>");

?>
</div>

<script>

function OnLoadCallback()
{
  UpdateFormInputs();
  reload();
}

function UpdateFormInputs()
{
  document.getElementById("playerCharacter").value = GetCharacterCards();
  document.getElementById("playerDeck").value = GetDeckCards();
}

function TabClick(tab)
{
  var equipTab = document.getElementById("equipTab");
  var equipDisplay = document.getElementById("equipDisplay");
  var deckTab = document.getElementById("deckTab");
  var deckDisplay = document.getElementById("deckDisplay");
  equipDisplay.style.display = tab == "EQUIP" ? "block" : "none";
  deckDisplay.style.display = tab == "DECK" ? "block" : "none";
  equipTab.style.backgroundColor = tab == "EQUIP" ? "rgba(59, 59, 38, 0.7)" : "rgba(159, 159, 138, 0.7)";
  deckTab.style.backgroundColor = tab == "DECK" ? "rgba(59, 59, 38, 0.7)" : "rgba(159, 159, 138, 0.7)";
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
      overlay.style.visibility = (count != idArr[1] ? "visible" : "hidden");
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
      rv += imageSrc.substring(imageSrc.length-10).split(".")[0];
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
      returnValue += imageSrc.substring(imageSrc.length-10).split(".")[0];
    }
    ++count;
    var overlay = document.getElementById("DECK-" + count + "-ovr");
  }
  return returnValue;
}


function loadGamestate() {
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function() {
      var resp = "";
      for(var i=0; i<this.responseText.length; ++i) resp += this.responseText[i];
      if(parseInt(resp) != prevGameState && parseInt(resp) != 5) { location.reload(); }
      <?php if($playerID == 1) echo 'if(parseInt(resp) == 5) {document.getElementById("icon").href = "./HostFiles/ready.png"; document.getElementById("submitForm").style.display = "block";}'; ?>
      prevGameState = parseInt(resp);
    };
    xhttp.open("GET", "GameFileLength.php?gameName=<?php echo($gameName); ?>", true);
    xhttp.send();
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
    echo("<span style='cursor:pointer;'>" . Card($equip, "CardImages", 150, 0, 1, 0, 0, 0, "", $id) . "</span>");
    echo("</div>");
    echo("</td>");
    ++$count;
  }
  for($i=0; $i<count($equipSB); ++$i)
  {
    $id = $name . "-" . $count;
    echo("<td>");
    echo("<div onclick='CardClick(\"" . $id . "\")'>");
    echo("<span style='cursor:pointer;'>" . Card($equipSB[$i], "CardImages", 150, 0, 1, 1, 0, 0, "", $id) . "</span>");
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
    echo("<span style='cursor:pointer;'>" . Card($weapon1, "CardImages", 150, 0, 1, 0, 0, 0, "", $id) . "</span>");
    echo("</div>");
    echo("</td>");
    ++$count;
  }
  if($weapon2 != "")
  {
    $id = $name . "-" . $count;
    echo("<td>");
    echo("<div onclick='CardClick(\"" . $id . "\")'>");
    echo("<span style='cursor:pointer;'>" . Card($weapon2, "CardImages", 150, 0, 1, 0, 0, 0, "", $id) . "</span>");
    echo("</div>");
    echo("</td>");
    ++$count;
  }
  for($i=0; $i<count($weaponSB); ++$i)
  {
    $id = $name . "-" . $count;
    echo("<td>");
    echo("<div onclick='CardClick(\"" . $id . "\")'>");
    echo("<span style='cursor:pointer;'>" . Card($weaponSB[$i], "CardImages", 150, 0, 1, 1, 0, 0, "", $id) . "</span>");
    echo("</div>");
    echo("</td>");
    ++$count;
  }

  if($weapon1 != "" || $weapon2 != "" || count($weaponSB) > 0) echo("</tr>");
}

?>

<div style="height:20px; bottom:30px; left:5%; width: 90%; position:absolute; color:white;background-color:rgba(59, 59, 38, 0.7); text-align:center;">FaB Online is in no way affiliated with Legend Story Studios. Legend Story Studios®, Flesh and Blood™, and set names are trademarks of Legend Story Studios. Flesh and Blood characters, cards, logos, and art are property of Legend Story Studios. Card Images © Legend Story Studios</div>
</body>