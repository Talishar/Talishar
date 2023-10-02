<?php
ob_start();
include "WriteLog.php";
include "CardDictionary.php";
include "HostFiles/Redirector.php";
include "Libraries/UILibraries2.php";
include "Libraries/SHMOPLibraries.php";
include_once "Libraries/PlayerSettings.php";
include_once "Libraries/HTTPLibraries.php";
include_once "Assets/patreon-php-master/src/PatreonDictionary.php";
ob_end_clean();

session_start();

// This is required to be able to create games on legacy client
if (!function_exists("DelimStringContains")) {
  function DelimStringContains($str, $find, $partial=false)
  {
    $arr = explode(",", $str);
    for($i=0; $i<count($arr); ++$i)
    {
      if($partial && str_contains($arr[$i], $find)) return true;
      else if($arr[$i] == $find) return true;
    }
    return false;
  }
}

if (!function_exists("SubtypeContains")) {
  function SubtypeContains($cardID, $subtype, $player="")
  {
    $cardSubtype = CardSubtype($cardID);
    return DelimStringContains($cardSubtype, $subtype);
  }
}

$authKey = "";
$gameName = $_GET["gameName"];
$playerID = $_GET["playerID"];
if ($playerID == 1 && isset($_SESSION["p1AuthKey"])) $authKey = $_SESSION["p1AuthKey"];
else if ($playerID == 2 && isset($_SESSION["p2AuthKey"])) $authKey = $_SESSION["p2AuthKey"];
else if (isset($_GET["authKey"])) $authKey = $_GET["authKey"];

session_write_close();

if (($playerID == 1 || $playerID == 2) && $authKey == "") {
  if (isset($_COOKIE["lastAuthKey"])) $authKey = $_COOKIE["lastAuthKey"];
}

if (!file_exists("./Games/" . $gameName . "/GameFile.txt")) {
  header("Location: " . $redirectPath . "/MainMenu.php"); //If the game file happened to get deleted from inactivity, redirect back to the main menu instead of erroring out
  exit;
}

ob_start();
include "MenuFiles/ParseGamefile.php";
ob_end_clean();

$targetAuth = ($playerID == 1 ? $p1Key : $p2Key);
if (!isset($authKey) || $authKey != $targetAuth) {
  echo ("Invalid Auth Key");
  exit;
}

$yourName = ($playerID == 1 ? $p1uid : $p2uid);
$theirName = ($playerID == 1 ? $p2uid : $p1uid);

if ($gameStatus == $MGS_GameStarted) {
  $authKey = ($playerID == 1 ? $p1Key : $p2Key);
  if($roguelikeGameID >= 0 && isset($roguelikeUIPath)) header("Location: " . $roguelikeUIPath . "?gameName=$gameName&playerID=$playerID");
  else if(isset($gameUIPath)) header("Location: " . $gameUIPath . "?gameName=$gameName&playerID=$playerID");
  else header("Location: " . $redirectPath . "/NextTurn4.php?gameName=$gameName&playerID=$playerID");
  exit;
}

$icon = "ready.png";

if ($gameStatus == $MGS_ChooseFirstPlayer) $icon = $playerID == $firstPlayerChooser ? "ready.png" : "notReady.png";
else if ($playerID == 1 && $gameStatus < $MGS_ReadyToStart) $icon = "notReady.png";
else if ($playerID == 2 && $gameStatus >= $MGS_ReadyToStart) $icon = "notReady.png";

echo '<title>Game Lobby</title> <meta http-equiv="content-type" content="text/html; charset=utf-8" > <meta name="viewport" content="width=device-width, initial-scale=1.0">';
echo '<link id="icon" rel="shortcut icon" type="image/png" href="./Images/' . $icon . '"/>';

$isMobile = IsMobile();

?>

<head>
  <meta charset="utf-8">
  <title>Talishar</title>
  <link rel="stylesheet" href="./css/menuStyles2.css">
</head>

<script>
  function copyText() {
    var gameLink = document.getElementById("gameLink");
    gameLink.select();
    gameLink.setSelectionRange(0, 99999);

    // Copy it to clipboard
    document.execCommand("copy");
  }
</script>

<style>
  body {
    margin: 0px;
    color: rgb(240, 240, 240);
    overflow-y: hidden;
    color: #DDD;
    background-image: url('Images/rout.jpg');
    background-position: center center;
    background-repeat: no-repeat;
    background-size: cover;
    overflow: hidden;
  }

  h1 {
    margin-top: 10px;
    text-align: center;
    width: 100%;
    text-shadow: 2px 0 0 #1a1a1a, 0 -2px 0 #1a1a1a, 0 2px 0 #1a1a1a, -2px 0 0 #1a1a1a;
  }

  h2 {
    margin-top: 10px;
    text-align: center;
    width: 100%;
    text-shadow: 2px 0 0 #1a1a1a, 0 -2px 0 #1a1a1a, 0 2px 0 #1a1a1a, -2px 0 0 #1a1a1a;
  }
</style>
<script src="./jsInclude.js"></script>
</head>

<body onload='OnLoadCallback(<?php echo (filemtime("./Games/" . $gameName . "/gamelog.txt")); ?>)'>

  <audio id="playerJoinedAudio">
    <source src="./Assets/playerJoinedSound.mp3" type="audio/mpeg">
  </audio>

  <div id="cardDetail" style="display:none; position:absolute;"></div>

  <center>
    <?php
    if ($isMobile) echo '<div id="oppHero" style="position:absolute; z-index:1; top:2%; left:2%; width:50%; height:25%; background-color:rgba(74, 74, 74, 0.9); border: 2px solid #1a1a1a; border-radius: 5px;">';
    else echo '<div id="oppHero" style="position:absolute; z-index:1; top:20px; left:20px; width:290px; height:351px; background-color:rgba(74, 74, 74, 0.9); border: 2px solid #1a1a1a; border-radius: 5px;">';
    $theirDisplayName = ($theirName != "-" ? $theirName : "Player " . ($playerID == 1 ? 2 : 1));
    if ($isMobile) echo ("<h3>$theirDisplayName</h3>");
    else echo ("<h2>$theirDisplayName</h2>");

    $otherHero = "CardBack";
    echo ("<div>");
    echo (Card($otherHero, "concat", ($isMobile ? 100 : 250), 0, 0));
    echo ("</div>");
    ?>
    </div>
  </center>

  <?php
  if ($isMobile) echo '<div style="position:absolute; z-index:1; top:29%; left:2%; width:50%; height:25%; background-color:rgba(74, 74, 74, 0.9); border: 2px solid #1a1a1a; border-radius: 5px;">';
  else echo '<div style="position:absolute; z-index:1; top:20px; left:330px; width:290px; height:351px; background-color:rgba(74, 74, 74, 0.9); border: 2px solid #1a1a1a; border-radius: 5px;">';
  $contentCreator = ContentCreators::tryFrom(($playerID == 1 ? $p1ContentCreatorID : $p2ContentCreatorID));
  $nameColor = ($contentCreator != null ? $contentCreator->NameColor() : "");
  $displayName = "<span style='color:" . $nameColor . "'>" . ($yourName != "-" ? $yourName : "Player " . $playerID) . "</span>";
  if ($isMobile) echo ("<h3>$displayName</h3>");
  else echo ("<h2>$displayName</h2>");

  $deckFile = "./Games/" . $gameName . "/p" . $playerID . "Deck.txt";
  $handler = fopen($deckFile, "r");
  if ($handler) {
    $character = GetArray($handler);

    echo ("<center>");
    echo ("<div style='position:relative; display: inline-block;'>");
    $overlayURL = ($contentCreator != null ? $contentCreator->HeroOverlayURL($character[0]) : "");
    echo (Card($character[0], "concat", ($isMobile ? 100 : 250), 0, 1));
    if ($overlayURL != "") echo ("<img title='Portrait' style='position:absolute; z-index:1001; top: 27px; left: 0px; cursor:pointer; height:" . ($isMobile ? 100 : 250) . "; width:" . ($isMobile ? 100 : 250) . ";' src='" . $overlayURL . "' />");
    echo ("</div>");
    echo ("</center>");

    echo ("<div style='text-align:center; margin-top: 2px;'>");
    echo ("<a href='MainMenu.php'><button class='GameLobby_Button' style='display:inline; cursor:pointer;'>Leave Lobby</button></a>");
    echo ("</div>");

    $weapons = "";
    $head = "";
    $chest = "";
    $arms = "";
    $legs = "";
    $offhand = "";
    $quiver = "";
    for ($i = 1; $i < count($character); ++$i) {
      $cardId = $character[$i];
      if (SubtypeContains($cardId, "Head")) $head = $cardId;
      else if (SubtypeContains($cardId, "Chest")) $chest = $cardId;
      else if (SubtypeContains($cardId, "Arms")) $arms = $cardId;
      else if (SubtypeContains($cardId, "Legs")) $legs = $cardId;
      else if (SubtypeContains($cardId, "Off-Hand")) $offhand = $cardId;
      else if (SubtypeContains($cardId, "Quiver")) $quiver = $cardId;
      else {
        if ($weapons != "") $weapons .= ",";
        $weapons .= $cardId;
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
    $quiverSB = GetArray($handler);

    fclose($handler);
  }

  ?>
  </div>

  <div id="matchupTab" style="position:absolute; z-index:1; top:2%; right:10px; width:160px; height:8%; background-color:rgba(74, 74, 74, 0.9); border: 2px solid #1a1a1a; border-radius: 5px;">
    <h1>Matchups</h1>
  </div>
  <div id="matchups" style="position:absolute; text-align: center; z-index:1; top:10%; margin-top:3px; right:10px; bottom:3%; width:160px; <?php if ($isMobile) echo ('height:43.5%; '); ?> background-color:rgba(74, 74, 74, 0.9); border: 2px solid #1a1a1a; border-radius: 5px; overflow-y: auto;">

    <?php

    function sortMatchupsAlphabetically($a, $b)
    {
      if ($a->name == $b->name) {
        return 0;
      }

      return $a->name < $b->name ? -1 : 1;
    }

    $decklink = ($playerID == 1 ? $p1DeckLink : $p2DeckLink);
    $matchups = ($playerID == 1 ? $p1Matchups : $p2Matchups);
    if ($matchups != NULL) {
      usort($matchups, "sortMatchupsAlphabetically");
      for ($i = 0; $i < count($matchups); ++$i) {
        echo ("<div style='cursor:pointer; padding:5px; font-size:24px;'>");
        $matchuplink = $redirectPath . "/JoinGameInput.php?gameName=" . $gameName . "&playerID=" . $playerID . "&fabdb=" . $decklink . "&matchup=" . $matchups[$i]->{"matchupId"};
        echo ("<a href='" . $matchuplink . "'>");
        echo ("<input type='button' value='" . $matchups[$i]->{"name"} . "' />");
        echo ("</a>");
        echo ("</div>");
      }
      if ($isMobile && count($matchups) == 0) {
        echo ("Sideboarding is limited on mobile; we recommend defining matchups in your decklist for mobile sideboarding.");
      }
    } else {
      echo ("<BR>The following deckbuilder sites support matchups:<BR>");
      echo ("<a href='https://fabrary.net' target='_blank'>Fabrary</a>");
    }

    ?>

  </div>

  <div<?php if ($isMobile) echo (" style='display:none;'"); ?>>
    <div id="equipTab" style="position:absolute; z-index:1; cursor:pointer; top:20px; left:640px; width:280px; height:73px; background-color:rgba(175, 175, 175, 0.8); border: 2px solid #1a1a1a; border-radius: 5px;" onclick="TabClick('EQUIP');">

      <h1>Your Equipment</h1>
    </div>

    <div id="equipDisplay" style="position:absolute; z-index:1; top:95px; left:640px; right:180px; bottom:3%; background-color:rgba(74, 74, 74, 0.9); border: 2px solid #1a1a1a; border-radius: 5px;">

      <div style='margin:3px; margin-top: 10px; margin-left: 10px; width:100%; text-align: left; font-family:Roboto; font-style: italic; font-weight: bold; font-size:18px; text-shadow: 2px 0 0 #1a1a1a, 0 -2px 0 #1a1a1a, 0 2px 0 #1a1a1a, -2px 0 0 #1a1a1a;'>Click Cards to Select/Unselect</div>

      <table>
        <?php

        if (isset($head) && isset($headSB)) DisplayEquipRow($head, $headSB, "HEAD");
        if (isset($chest) && isset($chestSB)) DisplayEquipRow($chest, $chestSB, "CHEST");
        if (isset($arms) && isset($armsSB)) DisplayEquipRow($arms, $armsSB, "ARMS");
        if (isset($legs) && isset($legsSB)) DisplayEquipRow($legs, $legsSB, "LEGS");

        ?>
      </table>
      <div id="weaponDisplay" style="position:absolute; z-index:2; top:30px; left:50%; right:20px;">
        <table>
          <?php

          if (isset($weapons)) {
            $weaponArray = explode(",", $weapons);
            $weapon1 = (count($weaponArray) > 0 ? $weaponArray[0] : "");
            $weapon2 = (count($weaponArray) > 1 ? $weaponArray[1] : "");
            if (isset($weapon1) && isset($weapon2) && isset($weaponSB)) DisplayWeaponRow($weapon1, $weapon2, $weaponSB, "WEAPONS");
          }
          if (isset($offhand) && isset($offhandSB)) DisplayEquipRow($offhand, $offhandSB, "OFFHAND");
          if (isset($quiver) && isset($quiverSB)) DisplayEquipRow($quiver, $quiverSB, "QUIVER");

          ?>
        </table>
      </div>
    </div>

    <div id="deckTab" style="position:absolute; z-index:1; cursor:pointer; top:20px; left:922px; width:280px; height:73px; background-color:rgba(74, 74, 74, 0.9); border: 2px solid #1a1a1a; border-radius: 5px;" onclick="TabClick('DECK');">

      <?php
      if (isset($deck)) echo ("<h1>Your Deck (<span id='mbCount'>" . count($deck) . "</span>/<span>" . (count($deck) + count($deckSB)) . "</span>)</h1>");
      ?>
    </div>

    <div id="deckDisplay" style="display:none; position:absolute; z-index:1; top:95px; left:640px; right:180px; bottom:3%; background-color:rgba(74, 74, 74, 0.9); border: 2px solid #1a1a1a; border-radius: 5px; overflow-y:scroll; overflow-x:hidden;">

      <div style='margin:3px; margin-top: 10px; margin-left: 10px; width:100%; text-align: left; font-family:Roboto; font-style: italic; font-weight: bold; font-size:18px; text-shadow: 2px 0 0 #1a1a1a, 0 -2px 0 #1a1a1a, 0 2px 0 #1a1a1a, -2px 0 0 #1a1a1a;'>Click Cards to Select/Unselect</div>

      <?php
      if (isset($deck)) {
        $cardSize = 110;
        $count = 0;
        sort($deck);
        for ($i = 0; $i < count($deck); ++$i) {
          $id = "DECK-" . $count;
          if (!($roguelikeGameID >= 0)) echo ("<span style='cursor:pointer; padding-bottom:5px; padding-left:3px;' onclick='CardClick(\"" . $id . "\")'>" . Card($deck[$i], "concat", $cardSize, 0, 1, 0, 0, 0, "", $id) . "</span>");
          else {
            echo (Card($deck[$i], "concat", $cardSize, 0, 1, 0, 0, 0, "", $id));
          }
          ++$count;
        }
        for ($i = 0; $i < count($deckSB); ++$i) {
          $id = "DECK-" . $count;
          echo ("<span style='cursor:pointer; padding-bottom:5px; padding-left:3px;' onclick='CardClick(\"" . $id . "\")'>" . Card($deckSB[$i], "concat", $cardSize, 0, 1, 1, 0, 0, "", $id) . "</span>");
          ++$count;
        }
      }
      ?>
    </div>
    </div>
    <?php
    if ($isMobile) echo '<div style="position:absolute; z-index:1; top:56%; left:2%; width:600px; max-width: 96%; bottom:3%; font-weight:500; font-size:18px; background-color:rgba(74, 74, 74, 0.9); border: 2px solid #1a1a1a;border-radius: 5px;">';
    else echo '<div style="position:absolute; z-index:1; top:390px; left:20px; width:600px; bottom:3%; font-weight:500; font-size:18px; background-color:rgba(74, 74, 74, 0.9); border: 2px solid #1a1a1a;border-radius: 5px;">';
    ?>

    <h1>Game Lobby</h1>
    <?php

    echo ("<div id='submitForm' style='display:none; width:100%; text-align: center;'>");
    echo ("<form action='./SubmitSideboard.php'>");
    echo ("<input type='hidden' id='gameName' name='gameName' value='$gameName'>");
    echo ("<input type='hidden' id='playerID' name='playerID' value='$playerID'>");
    echo ("<input type='hidden' id='playerCharacter' name='playerCharacter' value=''>");
    echo ("<input type='hidden' id='playerDeck' name='playerDeck' value=''>");
    echo ("<input type='hidden' id='authKey' name='authKey' value='$authKey'>");
    echo ("<input class='GameLobby_Button' type='submit' value='" . ($playerID == 1 ? "Start" : "Ready") . "'>");
    echo ("</form>");
    echo ("</div>");

    echo ("<div id='mainPanel' style='text-align:center;'>");

    echo ("</div>");

    echo ("<div id='chatbox' style='position:absolute; bottom:3%; left:3%; width:97%;'>");
    //echo ("<div id='chatbox' style='position:relative; left:3%; width:97%; margin-top:4px;'>");
    echo ("<input id='chatText' style='background0color: grey; color: black; margin-left: 4px; margin-right: 1px; display:inline; border: 2px solid white; border-radius: 3px; font-weight: 500;' type='text' name='chatText' disabled value='chat disabled in legacy' autocomplete='off' >");
    echo ("<input type='hidden' id='gameName' value='" . $gameName . "'>");
    echo ("<input type='hidden' id='playerID' value='" . $playerID . "'>");
    echo ("</div>");

    echo ("<script>");
    echo ("var prevGameState = " . $gameStatus . ";");
    echo ("function reload() { setInterval(function(){loadGamestate();}, 500); }");

    echo ("</script>");

    ?>
    </div>

    <script>
      function OnLoadCallback(lastUpdate) {
        <?php
        if ($playerID == "1" && $gameStatus == $MGS_ChooseFirstPlayer) {
          echo ("var audio = document.getElementById('playerJoinedAudio');");
          echo ("audio.play();");
        }
        ?>
        UpdateFormInputs();
        var log = document.getElementById('gamelog');
        if (log !== null) log.scrollTop = log.scrollHeight;
        CheckReloadNeeded(0);
      }

      function UpdateFormInputs() {
        var playerCharacter = document.getElementById("playerCharacter");
        if (!!playerCharacter) playerCharacter.value = GetCharacterCards();
        var playerDeck = document.getElementById("playerDeck");
        if (!!playerDeck) playerDeck.value = GetDeckCards();
      }

      function TabClick(tab) {
        var equipTab = document.getElementById("equipTab");
        var equipDisplay = document.getElementById("equipDisplay");
        var deckTab = document.getElementById("deckTab");
        var deckDisplay = document.getElementById("deckDisplay");
        equipDisplay.style.display = tab == "EQUIP" ? "block" : "none";
        deckDisplay.style.display = tab == "DECK" ? "block" : "none";
        equipTab.style.backgroundColor = tab == "EQUIP" ? "rgba(175, 175, 175, 0.8)" : "rgba(74, 74, 74, 0.8)";
        deckTab.style.backgroundColor = tab == "DECK" ? "rgba(175, 175, 175, 0.8)" : "rgba(74, 74, 74, 0.8)";
      }

      function CardClick(id) {
        var idArr = id.split("-");
        if (IsEquipType(idArr[0])) {
          var count = 0;
          var overlay = document.getElementById(idArr[0] + "-" + count + "-ovr");
          while (!!overlay) {
            if (count != idArr[1]) overlay.style.visibility = "visible";
            else overlay.style.visibility = (overlay.style.visibility == "visible" ? "hidden" : "visible");
            //overlay.style.visibility = (count != idArr[1] ? "visible" : "hidden");
            ++count;
            var overlay = document.getElementById(idArr[0] + "-" + count + "-ovr");
          }
        } else if (idArr[0] == "DECK") {
          var overlay = document.getElementById(id + "-ovr");
          overlay.style.visibility = (overlay.style.visibility == "hidden" ? "visible" : "hidden");
          var mbCount = document.getElementById("mbCount");
          mbCount.innerText = parseInt(mbCount.innerText) + (overlay.style.visibility == "hidden" ? 1 : -1);
        } else if (idArr[0] == "WEAPONS") {
          var overlay = document.getElementById(id + "-ovr");
          overlay.style.visibility = (overlay.style.visibility == "hidden" ? "visible" : "hidden");
        }
        UpdateFormInputs();
      }

      function IsEquipType(type) {
        switch (type) {
          case "HEAD":
            return true;
          case "CHEST":
            return true;
          case "ARMS":
            return true;
          case "LEGS":
            return true;
          case "OFFHAND":
            return true;
          case "QUIVER":
            return true;
          default:
            return false;
        }
      }

      function GetCharacterCards() {
        var types = ["WEAPONS", "OFFHAND", "QUIVER", "HEAD", "CHEST", "ARMS", "LEGS"];
        var returnValue = "<?php echo (isset($character) ? $character[0] : ""); ?>";
        for (var i = 0; i < types.length; ++i) {
          var selected = GetSelectedEquipType(types[i]);
          if (selected != "") returnValue += "," + selected;
        }
        return returnValue;
      }

      function GetSelectedEquipType(type) {
        var count = 0;
        var overlay = document.getElementById(type + "-" + count + "-ovr");
        var rv = "";
        while (!!overlay) {
          if (overlay.style.visibility == "hidden") {
            var imageSrc = document.getElementById(type + "-" + count + "-img").src;
            if (rv != "") rv += ",";
            rv += imageSrc.substring(imageSrc.length - 11).split(".")[0];
          }
          ++count;
          var overlay = document.getElementById(type + "-" + count + "-ovr");
        }
        return rv;
      }

      function GetDeckCards() {
        var count = 0;
        var returnValue = "";
        var overlay = document.getElementById("DECK-" + count + "-ovr");
        while (!!overlay) {
          if (overlay.style.visibility == "hidden") {
            var imageSrc = document.getElementById("DECK-" + count + "-img").src;
            if (returnValue != "") returnValue += ",";
            var splitArr = imageSrc.split("/");
            returnValue += splitArr[splitArr.length - 1].split(".")[0];
          }
          ++count;
          var overlay = document.getElementById("DECK-" + count + "-ovr");
        }
        return returnValue;
      }

      var audioPlayed = false;

      function CheckReloadNeeded(lastUpdate) {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            if (parseInt(this.responseText) != 0) {
              if (parseInt(this.responseText) == 1) location.reload();
              else {
                var responseArr = this.responseText.split("ENDTIMESTAMP");
                document.getElementById("mainPanel").innerHTML = responseArr[1];
                CheckReloadNeeded(parseInt(responseArr[0]));
                var playAudio = document.getElementById("playAudio");
                if (!!playAudio && playAudio.innerText == 1 && !audioPlayed) {
                  var audio = document.getElementById('playerJoinedAudio');
                  audio.play();
                  audioPlayed = true;
                }
                var otherHero = document.getElementById("otherHero");
                if (!!otherHero) document.getElementById("oppHero").innerHTML = otherHero.innerHTML;
                document.getElementById("icon").href = "./Images/" + document.getElementById("iconHolder").innerText;
                var log = document.getElementById('gamelog');
                if (log !== null) log.scrollTop = log.scrollHeight;
                document.getElementById("submitForm").style.display = document.getElementById("submitDisplay").innerHTML;
              }
            }
          }
        };
        xmlhttp.open("GET", "GetLobbyRefresh.php?gameName=<?php echo ($gameName); ?>&playerID=<?php echo ($playerID); ?>&lastUpdate=" + lastUpdate + "&authKey=<?php echo ($authKey); ?>", true);
        xmlhttp.send();
      }

      function SubmitFirstPlayer(action) {
        if (action == 1) action = "Go First";
        else action = "Go Second";
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {}
        }
        var ajaxLink = "ChooseFirstPlayer.php?gameName=" + <?php echo ($gameName); ?>;
        ajaxLink += "&playerID=" + <?php echo ($playerID); ?>;
        ajaxLink += "&action=" + action;
        ajaxLink += <?php echo ("\"&authKey=" . $authKey . "\""); ?>;
        xmlhttp.open("GET", ajaxLink, true);
        xmlhttp.send();
      }
    </script>

    <?php

    function DisplayEquipRow($equip, $equipSB, $name)
    {
      $cardSize = 110;
      $count = 0;
      if ($equip != "" || count($equipSB) > 0) echo ("<tr><td>");
      if ($equip != "") {
        $id = $name . "-" . $count;
        echo ("<div style='display:inline; width:" . $cardSize . ";' onclick='CardClick(\"" . $id . "\")'>");
        echo ("<span style='cursor:pointer; padding-bottom:5px; padding-left:3px;'>" . Card($equip, "concat", $cardSize, 0, 1, 0, 0, 0, "", $id) . "</span>");
        echo ("</div>");
        ++$count;
      }
      for ($i = 0; $i < count($equipSB); ++$i) {
        $id = $name . "-" . $count;
        echo ("<div style='display:inline; width:" . $cardSize . ";' onclick='CardClick(\"" . $id . "\")'>");
        echo ("<span style='cursor:pointer; padding-bottom:5px; padding-left:3px;'>" . Card($equipSB[$i], "concat", $cardSize, 0, 1, 1, 0, 0, "", $id) . "</span>");
        echo ("</div>");
        ++$count;
      }

      if ($equip != "" || count($equipSB) > 0) echo ("</td></tr>");
    }

    function DisplayWeaponRow($weapon1, $weapon2, $weaponSB, $name)
    {
      $cardSize = 110;
      $count = 0;
      if ($weapon1 != "" || $weapon2 != "" || count($weaponSB) > 0) echo ("<tr><td>");
      if ($weapon1 != "") {
        $id = $name . "-" . $count;
        echo ("<div style='display:inline; width:" . $cardSize . ";' onclick='CardClick(\"" . $id . "\")'>");
        echo ("<span style='cursor:pointer; padding-bottom:5px; padding-left:3px;'>" . Card($weapon1, "concat", $cardSize, 0, 1, 0, 0, 0, "", $id) . "</span>");
        echo ("</div>");
        ++$count;
      }
      if ($weapon2 != "") {
        if (HasReverseArt($weapon1) && $weapon2 == $weapon1) {
          $weapon2 = ReverseArt($weapon1);
        }
        $id = $name . "-" . $count;
        echo ("<div style='display:inline; width:" . $cardSize . ";' onclick='CardClick(\"" . $id . "\")'>");
        echo ("<span style='cursor:pointer; padding-bottom:5px; padding-left:3px;'>" . Card($weapon2, "concat", $cardSize, 0, 1, 0, 0, 0, "", $id) . "</span>");
        echo ("</div>");
        ++$count;
      }
      for ($i = 0; $i < count($weaponSB); ++$i) {
        if (isset($weaponSB[$i + 1])) {
          if (HasReverseArt($weaponSB[$i]) && $weaponSB[$i + 1] == $weaponSB[$i]) {
            $weaponSB[$i + 1] = ReverseArt($weaponSB[$i]);
          }
        }
        $id = $name . "-" . $count;
        echo ("<div style='display:inline; width:" . $cardSize . ";' onclick='CardClick(\"" . $id . "\")'>");
        echo ("<span style='cursor:pointer; padding-bottom:5px; padding-left:3px;'>" . Card($weaponSB[$i], "concat", $cardSize, 0, 1, 1, 0, 0, "", $id) . "</span>");
        echo ("</div>");
        ++$count;
      }

      if ($weapon1 != "" || $weapon2 != "" || count($weaponSB) > 0) echo ("</td></tr>");
    }

    function HasReverseArt($cardID)
    {
      switch ($cardID) {
        case "WTR078":
          return true;
        case "CRU004":
        case "CRU051":
        case "CRU079":
          return true;
        case "DYN069":
        case "DYN115":
          return true;
        case "OUT005":
        case "OUT007":
        case "OUT009":
          return true;
        default:
          return false;
          break;
      }
    }

    function ReverseArt($cardID)
    {
      switch ($cardID) {
        case "WTR078":
          return "CRU049";
        case "CRU004":
          return "CRU005";
        case "CRU051":
          return "CRU052";
        case "CRU079":
          return "CRU080";
        case "DYN069":
          return "DYN070";
        case "DYN115":
          return "DYN116";
        case "OUT005":
          return "OUT006";
        case "OUT007":
          return "OUT008";
        case "OUT009":
          return "OUT010";
        default:
          break;
      }
    }
    ?>

    <?php
    include_once 'Disclaimer.php'
    ?>
