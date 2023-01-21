<?php

include_once 'Header.php';
include "HostFiles/Redirector.php";
include_once "Libraries/PlayerSettings.php";
include_once 'Assets/patreon-php-master/src/PatreonDictionary.php';
include_once "APIKeys/APIKeys.php";


if (isset($_SESSION["useruid"])) {
  $useruid = $_SESSION["useruid"];
  $banfileHandler = fopen("./HostFiles/bannedPlayers.txt", "r");
  while (!feof($banfileHandler)) {
    $bannedPlayer = trim(fgets($banfileHandler), "\r\n");
    if ($useruid == $bannedPlayer) {
      fclose($banfileHandler);
      exit;
    }
  }
  fclose($banfileHandler);
}

if (isset($_SESSION["userid"])) {
  $uidExists = getUInfo($conn, $_SESSION['useruid']);
  $_SESSION["userKarma"] = $uidExists["usersKarma"];
  $_SESSION["greenThumb"] = $uidExists["greenThumbs"];
  $_SESSION["redThumb"] = $uidExists["redThumbs"];
}

if (!empty($_SESSION['error'])) {
  $error = $_SESSION['error'];
  unset($_SESSION['error']);
  echo "<script>alert('" . $error . "')</script>";
}

$language = TryGet("language", 1);
$settingArray = [];
$defaultFormat = 0;
$defaultVisibility = (isset($_SESSION["useruid"]) ? 1 : 0);
if (isset($_SESSION["userid"])) {
  $savedSettings = LoadSavedSettings($_SESSION["userid"]);
  for ($i = 0; $i < count($savedSettings); $i += 2) {
    $settingArray[$savedSettings[intval($i)]] = $savedSettings[intval($i) + 1];
  }
  if (isset($_GET['language'])) {
    ChangeSetting("", $SET_Language, $language, $_SESSION["userid"]);
  } else if (isset($settingArray[$SET_Language])) $language = $settingArray[$SET_Language];
  if (isset($settingArray[$SET_Format])) $defaultFormat = $settingArray[$SET_Format];
  if (isset($settingArray[$SET_GameVisibility])) $defaultVisibility = $settingArray[$SET_GameVisibility];
}
$_SESSION['language'] = $language;
if (isset($_SESSION["isPatron"])) $isPatron = $_SESSION["isPatron"];
else $isPatron = false;

$createGameText = ($language == 1 ? "Create Game" : "ゲームを作る");
$languageText = ($language == 1 ? "Language" : "言語");
$createNewGameText = ($language == 1 ? "Create New Game" : "新しいゲームを作成する");
$starterDecksText = ($language == 1 ? "Starter Decks" : "おすすめデッキ");

$canSeeNormalQueue = isset($_SESSION["useruid"]);
$canSeeComp = isset($_SESSION["useruid"]) && isset($_SESSION["userKarma"]) && $_SESSION["userKarma"] >= 80;

?>

<style>
  body {
    background-image: url('Images/background_DYN.jpg');
    background-position: top center;
    background-repeat: no-repeat;
    background-size: cover;
    overflow: hidden;
  }
</style>

<div class="FabLogo" style="background-image: url('Images/TalisharLogo.webp');"></div>

<div class="ServerChecker">
  <?php
  try {
    include "ServerChecker.php";
  } catch (\Exception $e) {

  }
  ?>
</div>

<div class="CreateGame_Menu" <?php if(IsMobile()) echo(" style='width:65%;' "); ?>>
  <h1 style="margin-top: 3px;"><?php echo ($createNewGameText); ?></h1>

  <?php
  echo ("<form style='width:100%;display:inline-block;' action='" . $redirectPath . "/CreateGame.php'>");

  $favoriteDecks = [];
  if (isset($_SESSION["userid"])) {
    $favoriteDecks = LoadFavoriteDecks($_SESSION["userid"]);
    if (count($favoriteDecks) > 0) {
      $selIndex = -1;
      if (isset($settingArray[$SET_FavoriteDeckIndex])) $selIndex = $settingArray[$SET_FavoriteDeckIndex];
      echo ("<div class='FavoriteDeckMainMenu'>Favorite Decks: ");
      echo ("<select style='height:26px; width:60%;' name='favoriteDecks' id='favoriteDecks'>");
      for ($i = 0; $i < count($favoriteDecks); $i += 3) {
        echo ("<option value='" . $i . "<fav>" . $favoriteDecks[$i] . "'" . ($i == $selIndex ? " selected " : "") . ">" . $favoriteDecks[$i + 1] . "</option>");
      }
      echo ("</select></div>");
    }
  }
  if (count($favoriteDecks) == 0) {
    echo ("<div class='FavoriteDeckMainMenu'>" . $starterDecksText . ": ");
    echo ("<select name='decksToTry' id='decksToTry'>");
    echo ("<option value='1'>Bravo CC Starter Deck</option>");
    echo ("<option value='2'>Rhinar CC Starter Deck</option>");
    echo ("<option value='3'>Katsu CC Starter Deck</option>");
    echo ("<option value='4'>Dorinthea CC Starter Deck</option>");
    echo ("<option value='5'>Dash CC Starter Deck</option>");
    echo ("<option value='6'>Viserai CC Starter Deck</option>");
    echo ("<option value='7'>Kano CC Starter Deck</option>");
    echo ("<option value='8'>Azalea CC Starter Deck</option>");
    echo ("<option value='9'>Prism Blitz Starter Deck</option>");
    echo ("<option value='10'>Levia Blitz Starter Deck</option>");
    echo ("<option value='11'>Boltyn Blitz Starter Deck</option>");
    echo ("<option value='12'>Chane Blitz Starter Deck</option>");
    echo ("<option value='13'>Oldhim BlitzStarter Deck</option>");
    echo ("<option value='14'>Briar Blitz Starter Deck</option>");
    echo ("<option value='15'>Lexi Blitz Starter Deck</option>");
    echo ("<option value='16'>Fai Blitz Starter Deck</option>");
    echo ("<option value='17'>Dromai Blitz Starter Deck</option>");
    echo ("</select></div>");
  }
  echo ("<br>");

  ?>
    <label for="fabdb" style='font-weight:bolder; margin-left:10px;'>Deck Link:</label>
    <input type="text" id="fabdb" name="fabdb">
  <?php
  if (isset($_SESSION["userid"])) {
    echo ("<span style='display:inline;'>");
    echo ("<input class='inputFavoriteDeck' type='checkbox' id='favoriteDeck' name='favoriteDeck' />");
    echo ("<label title='Save deck to Favorites' for='favoriteDeck' style='margin-left:10px;'></label>");
    echo ("</span>");
  }
  echo ("<br>");
  ?>
  <br>
    <label for="gameDescription" style='font-weight:bolder; margin-left:10px;'>Game Name:</label>
    <input type="text" id="gameDescription" name="gameDescription" placeholder="Game #"><br><br>

  <?php
  if (isset($_SESSION["userid"])) {
    echo ("<label for='gameKarmaRestriction' style='font-weight:bolder; margin-left:20px;'>Restrict by Reputation:</label>");
    echo ("<select class='karmaRestriction-Select' style='margin-left:10px;' name='gameKarmaRestriction' id='gameKarmaRestriction'>");
    echo ("<option value='0'>No Restriction</option>");
    if ($_SESSION["userKarma"] >= 50) echo ("<option value='50'>☯ ≥ 50% - Exclude players with a karma below 50% (Bad reputation).</option>");
    if ($_SESSION["userKarma"] >= 75) echo ("<option value='75'>☯ ≥ 75% - Only players with a good reputation. Exclude players without accounts.</option>");
    if ($_SESSION["userKarma"] >= 85) echo ("<option value='85'>☯ ≥ 85% - Only players with a very good reputation, while excluding new players.</option>");
    echo ("</select><br><br>");
  }
  ?>

  <?php
  echo ("<label for='format' style='font-weight:bolder; margin-left:20px;'>Format: </label>");
  echo ("<select name='format' id='format'>");
  if($canSeeNormalQueue) echo ("<option value='blitz' " . ($defaultFormat == 2 ? " selected" : "") . ">Blitz</option>");
  if($canSeeComp) echo ("<option value='compblitz' " . ($defaultFormat == 3 ? " selected" : "") . ">Competitive Blitz</option>");
  if($canSeeNormalQueue) echo ("<option value='cc' " . ($defaultFormat == 0 ? " selected" : "") . ">Classic Constructed</option>");
  if($canSeeComp) echo ("<option value='compcc'" . ($defaultFormat == 1 ? " selected" : "") . ">Competitive CC</option>");
  if($canSeeNormalQueue) echo ("<option value='commoner'" . ($defaultFormat == 5 ? " selected" : "") . ">Commoner</option>");
  if($canSeeNormalQueue) echo ("<option value='clash'" . ($defaultFormat == 6 ? " selected" : "") . ">Clash</option>");
  echo ("<option value='livinglegendscc'" . ($defaultFormat == 4 ? " selected" : "") . ">Open Format</option>");
  echo ("</select>");
  ?>
  <BR>
  <BR>

    <?php
      if($canSeeNormalQueue)
      {
        echo '<input style="margin-left:20px;" type="radio" id="public" name="visibility" value="public" ' . ($defaultVisibility == 1 ? 'checked="checked"' : "") . '>';
        echo('<label style="margin-left:2px;" for="public">Public</label>');
      }
     ?>

    <input type="radio" id="private" name="visibility" value="private" <?php if ($defaultVisibility == 0) echo 'checked="checked"'; ?>>
    <label style='margin-left:-12px;' for="private">Private</label><br><br>

  <input style="margin-left: 20px;" type="checkbox" id="deckTestMode" name="deckTestMode" value="deckTestMode">
  <label for="deckTestMode">Single Player</label><br><br>
  <div style="text-align:center;">

    <label>
      <input class="CreateGame_Button" type="submit" value="<?php echo ($createGameText); ?>">
    </label>

  </div>
  </form>


  <?php
  //echo("<form style='width:100%;display:inline-block;' action='" . $redirectPath . "/PVE/PVEMenu.php'>");
  ?>
  <!---<div style="text-align:center;"><input type="submit" style="font-size:20px;" value="PVE Menu"></div>
</form>
--->
</div>
</div>

<div class="NewsMenu" <?php if(IsMobile()) echo(" style='display:none;' "); ?>>

  <h1>News</h1>
  <div style="position: relative;">
    <div style='vertical-align:middle; text-align:center;'>

      <div style="vertical-align:middle; position: relative;">
        <h3>Big changes to matchmaking!</h3>
        <h4 style="margin-left:5%; margin-right:5%;">Login is now required for matchmaking</h4>
        <BR>
        If logged out, you can still make private games to play with friends, against yourself in multiple tabs, or against the bot! We've also added Clash as a supported format.

        <br>
      </div>

      <BR>

      <h5 style='text-align:center;'>________</h5><BR>


      <div style='vertical-align:middle; text-align:center;'>
        <h2 style="width:100%; text-align:center; color:rgb(220, 220, 220); font-size:20px;">Learn to Play on Talishar</h2>
        <a title='English' href='https://youtu.be/zxQStzZPVGI' target=' _blank'><img style='height:30px;' src='./Images/flags/uk.png' /></a>
        <a title='Spanish' href='https://youtu.be/Rr-TV3kRslk' target=' _blank'><img style='height:30px;' src='./Images/flags/spain.png' /></a>
        <a title='Polish' href='https://youtu.be/BuMTY3K8eso' target=' _blank'><img style='height:30px;' src='./Images/flags/polish.png' /></a>
        <a title='French' href='https://youtu.be/-hdLB2xusFg' target=' _blank'><img style='height:30px;' src='./Images/flags/french.png' /></a>
        <a title='Brazil' href='https://youtu.be/dC9Ck9GDySo' target=' _blank'><img style='height:30px;' src='./Images/flags/brazil.png' /></a>

        <div class='LanguageSelector'><?php echo ($languageText); ?>:
          <select id='languageSelect' onchange='changeLanguage()' name='decksToTry' id='decksToTry'>
            <option value='1' <?php if ($language == 1) echo (" selected"); ?>>English</option>
            <option value='2' <?php if ($language == 2) echo (" selected"); ?>>Japanese (日本語)</option>
          </select>
        </div>
      </div>

    </div>
  </div>


  <?php
  /*
  if (!$isPatron) {
    echo '<div>
      <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-8442966023291783"
          crossorigin="anonymous"></script>
      <!-- MainMenu -->
      <ins class="adsbygoogle"
          style="display:block"
          data-ad-client="ca-pub-8442966023291783"
          data-ad-slot="5060625180"
          data-ad-format="auto"
          data-full-width-responsive="true"></ins>
      <script>
          (adsbygoogle = window.adsbygoogle || []).push({});
      </script>
    </div>';
  }
  */
  ?>
  <BR>

</div>

<script>
  function changeLanguage() {
    window.location.search = '?language=' + document.getElementById('languageSelect').value;
  }
</script>
<?php
include_once 'Footer.php';
?>
