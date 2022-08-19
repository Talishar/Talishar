  <head>

    <?php

    include 'Libraries/HTTPLibraries.php';

    //We should always have a player ID as a URL parameter
    $gameName = $_GET["gameName"];
    if (!IsGameNameValid($gameName)) {
      echo ("Invalid game name.");
      exit;
    }
    $playerID = TryGet("playerID", 3);
    if(!is_numeric($playerID)) {
      echo ("Invalid player ID.");
      exit;
    }
    $authKey = TryGet("authKey", 3);

    session_start();

    //First we need to parse the game state from the file
    include "WriteLog.php";
    include "ParseGamestate.php";
    include "GameTerms.php";
    include "GameLogic.php";
    include "HostFiles/Redirector.php";
    include "Libraries/UILibraries2.php";
    include "Libraries/StatFunctions.php";
    include "Libraries/PlayerSettings.php";

    if ($currentPlayer == $playerID) {
      $icon = "ready.png";
      $readyText = "You are the player with priority.";
    } else {
      $icon = "notReady.png";
      $readyText = "The other player has priority.";
    }
    echo '<link id="icon" rel="shortcut icon" type="image/png" href="./HostFiles/' . $icon . '"/>';

    $darkMode = IsDarkMode($playerID);

    if ($darkMode) $backgroundColor = "rgba(20,20,20,0.70)";
    else $backgroundColor = "rgba(255,255,255,0.70)";

    $borderColor = ($darkMode ? "#DDD" : "#1a1a1a");
    ?>


    <head>
      <meta charset="utf-8">
      <title>Flesh and Blood Online</title>
      <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
      <link rel="stylesheet" href="css/gamestyle.css">
    </head>

    <script>
      function Hotkeys(event) {
        if (event.keyCode === 32) SubmitInput(99, ""); //Space = pass
        if (event.keyCode === 117) SubmitInput(10000, ""); //U = undo
        if (event.keyCode === 104) SubmitInput(3, "&cardID=0"); //H = hero ability
        if (event.keyCode === 109) ShowPopup("menuPopup"); //M = open menu
        <?php
        if (CardType($myCharacter[CharacterPieces()]) == "W") echo ("if(event.keyCode === 108) SubmitInput(3, '&cardID=" . CharacterPieces() . "');"); //L = left weapon
        if (CardType($myCharacter[CharacterPieces() * 2]) == "W") echo ("if(event.keyCode === 114) SubmitInput(3, '&cardID=" . (CharacterPieces() * 2) . "');"); //R = right weapon
        ?>
      }

      function Card(cardNumber, folder, maxHeight, action=0, showHover=0, overlay=0, borderColor=0, counters=0, actionDataOverride="", id="", rotate=false, lifeCounters=0, defCounters=0, atkCounters=0, from="", controller=0)
      {
        if(folder == "crops")
        {
          cardNumber += "_cropped";
        }
        fileExt = ".png";
        folderPath = folder;
        if(cardNumber == "ENDTURN" || cardNumber == "RESUMETURN" || cardNumber == "PHANTASM" || cardNumber == "FINALIZECHAINLINK")
        {
          //folderPath = str_replace("CardImages", "Images", folderPath);
          //folderPath = str_replace("concat", "Images", folderPath);
          showHover = 0;
          borderColor = 0;
        }
        else if(folder == "concat")
        {
          //if(DelimStringContains(CardSubType($cardNumber), "Landmark")) $rotate = true;
          fileExt = ".webp";
        }
        else if(folder == "WebpImages")
        {
          fileExt = ".webp";
        }
        var actionData = actionDataOverride != "" ? actionDataOverride : cardNumber;
        //Enforce 375x523 aspect ratio as exported (.71)
        margin = "margin:0px;";
        border = "";
        if(borderColor != -1) margin = borderColor > 0 ? "margin:0px;" : "margin:1px;";
        if(folder == "crops") margin = "0px;";

        var rv = "<a style='" + margin + " position:relative; display:inline-block;" + (action > 0 ? "cursor:pointer;" : "") + "'" + (showHover > 0 ? " onmouseover='ShowCardDetail(event, this)' onmouseout='HideCardDetail()'" : "") + (action > 0 ? " onclick='SubmitInput(\"" + action + "\", \"&cardID=" + actionData + "\");'" : "") + ">";

        if(borderColor > 0){
          border = "border-radius:8px; border:2.5px solid " + BorderColorMap(borderColor) + ";";
        }else if(folder == "concat"){
          border = "border-radius:8px; border:1.5px solid transparent;";
        } else {
          border = "border: 1px solid transparent;";
        }

        if(folder == "crops") { height = maxHeight; width = (height * 1.29); }
        else if(folder == "concat") { height = maxHeight; width = maxHeight; }
        else if(rotate == false) { height = maxHeight; width = (maxHeight * .71); }
        else { height = (maxHeight * .71); width = maxHeight; }

        //if($controller != 0 && IsPatron($controller) && CardHasAltArt($cardNumber)) $folderPath = "PatreonImages/" . $folderPath;
        <?php
        if(IsPatron(1)) echo("if(controller == 1 && CardHasAltArt(cardNumber)) folderPath = 'PatreonImages/' + folderPath;");
        if(IsPatron(2)) echo("if(controller == 2 && CardHasAltArt(cardNumber)) folderPath = 'PatreonImages/' + folderPath;");
        ?>

        if(rotate == false){
          rv += "<img " + (id != "" ? "id='" + id + "-img' ":"") + "style='" + border + " height:" + height + "; width:" + width + "px; position:relative;' src='./" + folderPath + "/" + cardNumber + fileExt + "' />";
          rv += "<div " + (id != "" ? "id='" + id + "-ovr' ":"") + "style='visibility:" + (overlay == 1 ? "visible" : "hidden") + "; width:100%; height:100%; top:0px; left:0px; border-radius:10px; position:absolute; background: rgba(0, 0, 0, 0.5); z-index: 1;'></div>";
        } else {
          // Landmarks Rotation
          //$rv .= "<img " . ($id != "" ? "id='".$id."-img' ":"") . "style='transform:rotate(-90deg);" . $border . " height:" . $height . "; width:" . $width . "px; position:relative;' src='./" . $folderPath . "/" . $cardNumber . $fileExt . "' />";
          //$rv .= "<div " . ($id != "" ? "id='".$id."-ovr' ":"") . "style='transform:rotate(-90deg); visibility:" . ($overlay == 1 ? "visible" : "hidden") . "; width:100%; height:100%; top:0px; left:0px; border-radius:10px; position:absolute; background: rgba(0, 0, 0, 0.5); z-index: 1;'></div>";
        }

        //TODO: counters

        rv += "</a>";
        return rv;
      }

      function CardHasAltArt(cardID)
      {
        switch(cardID)
        {
          case "ELE146": return true;
          case "MON155": return true;
          case "MON219": return true;
          case "MON220": return true;
          case "UPR042": return true;
          case "UPR043": return true;
          case "WTR002": return true;
          case "WTR162": return true;
          case "WTR224": return true;
          case "UPR406": case "UPR407": case "UPR408":
          case "UPR409": case "UPR410": case "UPR411":
          case "UPR412": case "UPR413": case "UPR414":
          case "UPR415": case "UPR416": case "UPR417":
            return true;
          default: return false;
        }
      }

      function BorderColorMap(code)
      {
        code = parseInt(code);
        switch(code)
        {
          case 1: return "DeepSkyBlue";
          case 2: return "red";
          case 3: return "yellow";
          case 4: return "Gray";
          case 5: return "Tan";
          case 6: return "chartreuse";
          case 7: return "Orchid";
          default: return "Black";
        }
      }

      function PopulateZone(zone, size=96, folder="concat")
      {
        //Card(cardNumber, folder, maxHeight, action=0, showHover=0, overlay=0, borderColor=0, counters=0, actionDataOverride="", id="", rotate=false, lifeCounters=0, defCounters=0, atkCounters=0, from="", controller=0)
        var zoneEl = document.getElementById(zone);
        var zoneData = zoneEl.innerHTML;
        if(zoneData == "") return;
        var zoneArr = zoneData.split("|");
        var newHTML = "";
        for(var i=0; i<zoneArr.length; ++i)
        {
          cardArr = zoneArr[i].split(" ");
          newHTML += "<span style='position:relative; margin:1px;'>";
          newHTML += Card(cardArr[0], folder, size, cardArr[1], 1, 0, cardArr[2], 0, cardArr[3], "", false, 0, 0, 0, "", cardArr[5]);
          newHTML += "</span>";
        }
        zoneEl.innerHTML = newHTML;
        zoneEl.style.display = "inline";
      }
    </script>

    <script src="./jsInclude.js"></script>

    <?php // TODO: find a way to move those styles to a stylesheet. Not sure why it's not working.
    ?>
    <style>
      :root {
        <?php if (IsDarkMode($playerID)) echo ("color-scheme: dark;");
        else echo ("color-scheme: light;");

        ?>
      }

      div,
      span {
        font-family: helvetica;
      }

      td {
        text-align: center;
      }

      .passButton {
        background: url("./Images/passActive.png") no-repeat;
        background-size: contain;
        transition: 150ms ease-in-out;
      }

      .passButton:hover {
        background: url("./Images/passHover.png") no-repeat;
        background-size: contain;
        -webkit-transform: scale(1.1);
        -ms-transform: scale(1.1);
        transform: scale(1.1);
      }

      .passButton:active {
        background: url("./Images/passPress.png") no-repeat;
        background-size: contain;
      }

      .passInactive {
        background: url("./Images/passInactive.png") no-repeat;
        background-size: contain;
      }

      .breakChain {
        background: url("./Images/chainLinkRight.png") no-repeat;
        background-size: contain;
        transition: 150ms ease-in-out;
      }

      .breakChain:hover {
        background: url("./Images/chainLinkBreak.png") no-repeat;
        background-size: contain;
        cursor: pointer;
        -webkit-transform: scale(1.3);
        -ms-transform: scale(1.3);
        transform: scale(1.3);
      }

      .breakChain:focus {
        outline: none;
      }

      .chainSummary {
        cursor: pointer;
        transition: 150ms ease-in-out;
      }

      .chainSummary:hover {
        -webkit-transform: scale(1.4);
        -ms-transform: scale(1.4);
        transform: scale(1.4);
      }

      .chainSummary:focus {
        outline: none;
      }

      .MenuButtons {
        cursor: pointer;
        transition: 150ms ease-in-out;
      }

      .MenuButtons:hover {
        -webkit-transform: scale(1.2);
        -ms-transform: scale(1.2);
        transform: scale(1.2);
      }

      .MenuButtons:focus {
        outline: none;
      }
    </style>

  </head>

  <body onkeypress='Hotkeys(event)' onload='OnLoadCallback(<?php echo (filemtime("./Games/" . $gameName . "/gamelog.txt")); ?>)'>

  <?php if($theirCharacter[0] != "DUMMY") echo(CreatePopup("inactivityWarningPopup", [], 0, 0, "⚠️ Inactivity Warning ⚠️", 1, "", "", true, true, "Interact with the screen in the next 10 seconds or you could be kicked for inactivity.")); ?>
  <?php if($theirCharacter[0] != "DUMMY") echo(CreatePopup("inactivePopup", [], 0, 0, "⚠️ You are Inactive ⚠️", 1, "", "", true, true, "You are inactive. Your opponent is able to claim victory. Interact with the screen to clear this.")); ?>

  <script>
    var IDLE_TIMEOUT = 40; //seconds
    var _idleSecondsCounter = 0;
    var _idleState = 0;//0 = not idle, 1 = idle warning, 2 = idle

    var activityFunction = function () {
        var oldIdleState = _idleState;
        _idleSecondsCounter = 0;
        _idleState = 0;
        var inactivityPopup = document.getElementById('inactivityWarningPopup');
        if(inactivityPopup) inactivityPopup.style.display = "none";
        var inactivePopup = document.getElementById('inactivePopup');
        if(inactivePopup) inactivePopup.style.display = "none";
        if(oldIdleState == 2) SubmitInput("100006", "");
    };

    document.onclick = activityFunction;

    document.onmousemove = activityFunction;

    document.onkeydown = activityFunction;

    window.setInterval(CheckIdleTime, 1000);

    function CheckIdleTime() {
        if(document.getElementById("iconHolder").innerText != "ready.png") return;
        _idleSecondsCounter++;
        if (_idleSecondsCounter >= IDLE_TIMEOUT) {
            if(_idleState == 0)
            {
              _idleState = 1;
              _idleSecondsCounter = 0;
              var inactivityPopup = document.getElementById('inactivityWarningPopup');
              if(inactivityPopup) inactivityPopup.style.display = "inline";
            }
            else if(_idleState == 1)
            {
              _idleState = 2;
              var inactivityPopup = document.getElementById('inactivityWarningPopup');
              if(inactivityPopup) inactivityPopup.style.display = "none";
              var inactivePopup = document.getElementById('inactivePopup');
              if(inactivePopup) inactivePopup.style.display = "inline";
              SubmitInput("100005", "");
            }
        }
    }
  </script>

    <audio id="yourTurnSound" src="./Assets/prioritySound.wav"></audio>

    <script>
      function reload() {
        CheckReloadNeeded(0);
      }

      function CheckReloadNeeded(lastUpdate) {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            if (this.responseText.split("REMATCH")[0] == "1234") {
              location.replace('GameLobby.php?gameName=<?php echo ($gameName); ?>&playerID=<?php echo ($playerID); ?>&authKey=<?php echo ($authKey); ?>');
            } else if (parseInt(this.responseText) != 0) {
              HideCardDetail();
              var responseArr = this.responseText.split("ENDTIMESTAMP");
              document.getElementById("mainDiv").innerHTML = responseArr[1];
              CheckReloadNeeded(parseInt(responseArr[0]));
              var readyIcon = document.getElementById("iconHolder").innerText;
              document.getElementById("icon").href = "./HostFiles/" + readyIcon;
              var log = document.getElementById('gamelog');
              if (log !== null) log.scrollTop = log.scrollHeight;
              if(readyIcon == "ready.png")
              {
                var audio = document.getElementById('yourTurnSound');
                <?php if(!IsMuted($playerID)) echo("audio.play();"); ?>
              }
              //var animations = document.getElementById("animations").innerText;
              PopulateZone("myHand");
              PopulateZone("theirHand", 50, "WebpImages");
              //if(animations != "") alert(animations);
            } else {
              CheckReloadNeeded(lastUpdate);
            }
          }
        };
        var dimensions = "&windowWidth=" + window.innerWidth + "&windowHeight=" + window.innerHeight;
        xmlhttp.open("GET", "GetNextTurn2.php?gameName=<?php echo ($gameName); ?>&playerID=<?php echo ($playerID); ?>&lastUpdate=" + lastUpdate + "&authKey=<?php echo ($authKey); ?>" + dimensions, true);
        xmlhttp.send();
      }

      function chkSubmit(mode, count) {
        var input = "";
        input += "&gameName=" + document.getElementById("gameName").value;
        input += "&playerID=" + document.getElementById("playerID").value;
        input += "&chkCount=" + count;
        for (var i = 0; i < count; ++i) {
          var el = document.getElementById("chk" + i);
          if (el.checked) input += "&chk" + i + "=" + el.value;
        }
        SubmitInput(mode, input);
      }
    </script>

    <?php
    //Display hidden elements
    echo ("<div id='popupContainer'></div>");
    echo ("<div id=\"cardDetail\" style=\"z-index:100000; display:none; position:fixed;\"></div>");
    echo ("<div id='mainDiv' style='left:0px; top:0px; width:100%;height:100%;'></div>");
    if ($playerID != 3) {
      echo ("<div id='chatbox' style='position:fixed; bottom:0px; right:10px; width:200px; height: 32px;'>");
      echo ("<input style='margin-left: 4px; margin-right: 1px; width:140px; display:inline; border: 2px solid " . $borderColor . "; border-radius: 3px; font-weight: 500;' type='text' id='chatText' name='chatText' value='' autocomplete='off' onkeypress='ChatKey(event)'>");
      echo ("<button style='display:inline; border: 2px solid " . $borderColor . "; width:45px; color: #1a1a1a; border:" . $backgroundColor . "; padding: 0; font: inherit; cursor: pointer; outline: inherit; box-shadow: none;' onclick='SubmitChat()'>Chat</button>");
      echo ("</div>");
    }
    echo ("<input type='hidden' id='gameName' value='" . $gameName . "'>");
    echo ("<input type='hidden' id='playerID' value='" . $playerID . "'>");
    echo ("<input type='hidden' id='authKey' value='" . $authKey . "'>");
    ?>

  </body>
