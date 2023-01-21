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

$canSeeQueue = isset($_SESSION["useruid"]);

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

<div style='position:absolute; top:100px; width:100%;'>
<center>
  <div style="width:400px; height:180px; background-size: contain; background-image: url('Images/TalisharLogo.webp');"></div>
</center>
</div>

<div class="ContentWindow" style='width:25%; height:90%; left:20px; top:60px; overflow-y:auto;'>
  <?php
  try {
    include "ServerChecker.php";
  } catch (\Exception $e) {

  }
  ?>
</div>

<?php

  if(IsMobile()) echo("<div class='ContentWindow' style='width:65%;'>");
  else echo("<div class='ContentWindow' style='top:40%; left:35%; width:30%;'>");

 ?>

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
    echo ("<option value='1'>Ira Welcome Deck</option>");
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
  echo ("<label for='format' style='font-weight:bolder; margin-left:20px;'>Format: </label>");
  echo ("<select name='format' id='format'>");
  if($canSeeQueue)
  {
    echo ("<option value='blitz' " . ($defaultFormat == 2 ? " selected" : "") . ">Blitz</option>");
    echo ("<option value='compblitz' " . ($defaultFormat == 3 ? " selected" : "") . ">Competitive Blitz</option>");
    echo ("<option value='cc' " . ($defaultFormat == 0 ? " selected" : "") . ">Classic Constructed</option>");
    echo ("<option value='compcc'" . ($defaultFormat == 1 ? " selected" : "") . ">Competitive CC</option>");
    echo ("<option value='commoner'" . ($defaultFormat == 5 ? " selected" : "") . ">Commoner</option>");
    echo ("<option value='clash'" . ($defaultFormat == 6 ? " selected" : "") . ">Clash</option>");
  }
  echo ("<option value='livinglegendscc'" . ($defaultFormat == 4 ? " selected" : "") . ">Open Format</option>");
  echo ("</select>");
  ?>
  <BR>
  <BR>

    <?php
      if($canSeeQueue)
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
      <input type="submit" value="<?php echo ($createGameText); ?>">
    </label>

  </div>
  </form>

</div>
</div>

<div class="ContentWindow" style='right:20px; top:60px; height:90%; width:25%; <?php if(IsMobile()) echo("display:none; "); ?>'>

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


                <div class='LanguageSelector'><?php echo ($languageText); ?>:
                  <select id='languageSelect' onchange='changeLanguage()' name='decksToTry' id='decksToTry'>
                    <option value='1' <?php if ($language == 1) echo (" selected"); ?>>English</option>
                    <option value='2' <?php if ($language == 2) echo (" selected"); ?>>Japanese (日本語)</option>
                  </select>
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
