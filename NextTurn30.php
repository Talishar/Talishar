<head>

<?php

  include 'Libraries/HTTPLibraries.php';

  //We should always have a player ID as a URL parameter
  $gameName=$_GET["gameName"];
  if(!IsGameNameValid($gameName)) { echo("Invalid game name."); exit; }
  $playerID=TryGet("playerID", 3);
  $authKey=TryGet("authKey", 3);

  //First we need to parse the game state from the file
  include "WriteLog.php";
  include "ParseGamestate.php";
  include "GameTerms.php";
  include "GameLogic.php";
  include "HostFiles/Redirector.php";
  include "Libraries/UILibraries2.php";
  include "Libraries/StatFunctions.php";
  include "Libraries/PlayerSettings.php";

  if($currentPlayer == $playerID) { $icon = "ready.png"; $readyText = "You are the player with priority."; }
  else { $icon = "notReady.png"; $readyText = "The other player has priority."; }
  echo '<link id="icon" rel="shortcut icon" type="image/png" href="./HostFiles/' . $icon . '"/>';

  $darkMode = IsDarkMode($playerID);

  if($darkMode) $backgroundColor = "rgba(20,20,20,0.70)";
  else $backgroundColor = "rgba(255,255,255,0.70)";

  $borderColor = ($darkMode ? "#DDD" : "black");
  ?>


  <head>
    <meta charset="utf-8">
    <title>Flesh and Blood Online</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
  </head>

<script>
  function Hotkeys(event)
  {
    if(event.keyCode === 32) SubmitInput(99, "");//Space = pass
    if(event.keyCode === 117) SubmitInput(10000, "");//U = undo
    if(event.keyCode === 104) SubmitInput(3, "&cardID=0");//H = hero ability
    <?php
      if(CardType($myCharacter[CharacterPieces()]) == "W") echo("if(event.keyCode === 108) SubmitInput(3, '&cardID=" . CharacterPieces() . "');");//L = left weapon
      if(CardType($myCharacter[CharacterPieces()*2]) == "W") echo("if(event.keyCode === 114) SubmitInput(3, '&cardID=" . (CharacterPieces() * 2) . "');");//R = right weapon
    ?>
  }
</script>

  <script src="./jsInclude.js"></script>

<style>
  :root {
    <?php if(IsDarkMode($playerID)) echo("color-scheme: dark;");
    else echo("color-scheme: light;");

     ?>
  }

  div, span { font-family: helvetica; }

  td {
    text-align:center;
  }

  .passButton {
    background: url("./Images/passActive.png") no-repeat;
    background-size:contain;
  }

  .passButton:hover {
    background: url("./Images/passHover.png") no-repeat;
    background-size:contain;
  }

  .passButton:active {
    background: url("./Images/passPress.png") no-repeat;
    background-size:contain;
  }

  .passInactive {
    background: url("./Images/passInactive.png") no-repeat;
    background-size:contain;
  }

  .breakChain {
    background: url("./Images/chainLinkRight.png") no-repeat;
    background-size:contain;
  }

  .breakChain:hover {
    background: url("./Images/chainLinkBreak.png") no-repeat;
    background-size:contain;
    cursor:pointer;
  }
</style>

</head>

<body onkeypress='Hotkeys(event)' onload='OnLoadCallback(<?php echo(filemtime("./Games/" . $gameName . "/gamelog.txt")); ?>)'>

  <script>

  function reload() {
    CheckReloadNeeded(0);
  }

  function CheckReloadNeeded(lastUpdate) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        if(parseInt(this.responseText.split("REMATCH")[0]) == 1234)
        {
          location.replace('GameLobby.php?gameName=<?php echo($gameName);?>&playerID=<?php echo($playerID);?>&authKey=<?php echo($authKey);?>');
        }
        else if(parseInt(this.responseText) != 0)
        {
          HideCardDetail();
          var responseArr = this.responseText.split("ENDTIMESTAMP");
          document.getElementById("mainDiv").innerHTML = responseArr[1];
          CheckReloadNeeded(parseInt(responseArr[0]));
          document.getElementById("icon").href = "./HostFiles/" + document.getElementById("iconHolder").innerText;
          var log = document.getElementById('gamelog');
          if(log !== null) log.scrollTop = log.scrollHeight;
        }
        else { CheckReloadNeeded(lastUpdate); }
      }
    };
    xmlhttp.open("GET", "GetNextTurn.php?gameName=<?php echo($gameName);?>&playerID=<?php echo($playerID);?>&lastUpdate=" + lastUpdate + "&authKey=<?php echo($authKey);?>", true);
    xmlhttp.send();
  }

  function chkSubmit(mode, count)
  {
    var input = "";
    input += "&gameName=" + document.getElementById("gameName").value;
    input += "&playerID=" + document.getElementById("playerID").value;
    input += "&chkCount=" + count;
    for(var i=0; i<count; ++i)
    {
      var el = document.getElementById("chk" + i);
      if(el.checked) input += "&chk" + i + "=" + el.value;
    }
    SubmitInput(mode, input);
  }

  </script>

  <?php
  //Display hidden elements
  echo("<div id=\"cardDetail\" style=\"z-index:100000; display:none; position:fixed;\"></div>");
  echo("<div id='mainDiv' style='left:0px; top:0px; width:100%;height:100%;'></div>");
  echo("<div id='chatbox' style='position:fixed; bottom:0px; right:10px; width:200px; height: 30px;'>");
  echo("<input style='width:150px; display:inline; border: 2px solid " . $borderColor . "; border-radius: 3px; font-weight: 500;' type='text' id='chatText' name='chatText' value='' autocomplete='off' onkeypress='ChatKey(event)'>");
  echo("<button style='display:inline; border: 2px solid " . $borderColor . "; border-radius: 3px; font-weight: 500;' onclick='SubmitChat()'>Chat</button>");
  echo("<input type='hidden' id='gameName' value='" . $gameName . "'>");
  echo("<input type='hidden' id='playerID' value='" . $playerID . "'>");
  echo("<input type='hidden' id='authKey' value='" . $authKey . "'>");
  echo("</div>");
?>
</body>
