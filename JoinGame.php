<?php
include_once 'MenuBar.php';
include "HostFiles/Redirector.php";
include_once "Libraries/PlayerSettings.php";
include_once 'Assets/patreon-php-master/src/PatreonDictionary.php';

$gameName = $_GET["gameName"];
if (!IsGameNameValid($gameName)) {
  echo ("Invalid game name.");
  exit;
}
$playerID = $_GET["playerID"];
if ($playerID == "1") {
  echo ("Player 1 should not use JoinGame.php");
  exit;
}

$settingArray = [];
if (isset($_SESSION["userid"])) {
  $savedSettings = LoadSavedSettings($_SESSION["userid"]);
  for ($i = 0; $i < count($savedSettings); $i += 2) {
    $settingArray[$savedSettings[intval($i)]] = $savedSettings[intval($i) + 1];
  }
}

?>
<style>
  body {
    margin: 0px;
    color: rgb(240, 240, 240);
    color: #EDEDED;
    background-image: url('Images/lord-of-wind.jpg');
    background-position: top center;
    background-repeat: no-repeat;
    background-size: cover;
    overflow: hidden;
  }
</style>

<div class='ContentWindow' style='width:30%; left:35%; top:15%; height:70%'>
  <br>
  <h1>Join Game</h1>
  <?php
  echo ("<form action='" . $redirectPath . "/JoinGameInput.php'>");
  echo ("<input type='hidden' id='gameName' name='gameName' value='$gameName'>");
  echo ("<input type='hidden' id='playerID' name='playerID' value='$playerID'>");
  ?>

  <?php
  echo ("<form style='display:inline-block;' action='" . $redirectPath . "/CreateGame.php'>");

  $favoriteDecks = [];
  if (isset($_SESSION["userid"])) {
    $favoriteDecks = LoadFavoriteDecks($_SESSION["userid"]);
    if (count($favoriteDecks) > 0) {
      $selIndex = -1;
      if (isset($settingArray[$SET_FavoriteDeckIndex])) $selIndex = $settingArray[$SET_FavoriteDeckIndex];
      echo ("<label for='favoriteDecks'>Favorite Decks");
      echo ("<select style='height:34px; width:60%;' name='favoriteDecks' id='favoriteDecks'>");
      for ($i = 0; $i < count($favoriteDecks); $i += 4) {
        echo ("<option value='" . $favoriteDecks[$i] . "'" . ($i == $selIndex ? " selected " : "") . ">" . $favoriteDecks[$i + 1] . "</option>");
      }
      echo ("</select></label>");
    }
  }
  if (count($favoriteDecks) == 0) {
    echo ("<label for='decksToTry'>Starter Decks");
    echo ("<option value='1'>Ira Welcome Deck</option>");
    echo ("</select></label><br>");
  }

  ?>
  <label for="fabdb">Deck Link</label>
  <input type="text" id="fabdb" name="fabdb">
  <br>
  <?php
  if (isset($_SESSION["userid"])) {
    echo ("<span style='display:inline;'>");
    echo ("<label for='favoriteDeck'><input title='Save deck to Favorites' class='inputFavoriteDeck' type='checkbox' id='favoriteDeck' name='favoriteDeck' />");
    echo ("Save Deck to Favorites</label>");
    echo ("</span>");
  }
  ?>
  <br>
  <div style='text-align:center;'><input class="JoinGame_Button" type="submit" value="Submit"></div>
  </form>

  <h3 style="text-align:center;">_____________________________</h3>
  <div>
    <h2>Instructions</h2>
    <p style="text-align:center; padding:10px;">Choose a deck and click submit. You will be taken to the game lobby.</p>
    <p style="text-align:center; padding:10px;">Once in the game lobby, the player who win the dice roll choose if the go first. Then the host can start the game.</p>
    <p style="text-align:center; font-size: 20px; padding:5px;">Have Fun!</p>
  </div>
</div>
</div>

<?php
include_once 'Disclaimer.php'
?>
