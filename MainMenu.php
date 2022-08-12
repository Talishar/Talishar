<?php

include_once 'Header.php';
include "HostFiles/Redirector.php";

if (!empty($_SESSION['error'])) {
  echo "<script>alert('" . $_SESSION['error'] . "')</script>";
  unset($_SESSION['error']);
}
?>

<style>
  body {
    background-image: url('Images/background.jpg');
    background-position: top center;
    background-repeat: no-repeat;
    background-size: cover;
    overflow: hidden;
  }
</style>

<div class="FabLogo" style="background-image: url('Images/fab_logo.png');">
</div>

<div class="ServerChecker">
  <?php
  include "ServerChecker.php";
  ?>
</div>

<div class="CreateGame_Menu">
  <h1 style="margin-top: 3px;">Create New Game</h1>

  <?php
  echo ("<form style='width:100%;display:inline-block;' action='" . $redirectPath . "/CreateGame.php'>");

  $favoriteDecks = [];
  if (isset($_SESSION["userid"])) {
    $favoriteDecks = LoadFavoriteDecks($_SESSION["userid"]);
    if (count($favoriteDecks) > 0) {
      echo ("<div class='FavoriteDeckMainMenu'>Favorite Decks: ");
      echo ("<select name='favoriteDecks' id='favoriteDecks'>");
      for ($i = 0; $i < count($favoriteDecks); $i += 3) {
        echo ("<option value='" . $favoriteDecks[$i] . "'>" . $favoriteDecks[$i + 1] . "</option>");
      }
      echo ("</select></div>");
    }
  }
  if (count($favoriteDecks) == 0) {
    echo ("<div class='FavoriteDeckMainMenu'>CC Starter Decks: ");
    echo ("<select name='decksToTry' id='decksToTry'>");
    echo ("<option value='1'>Bravo Starter Deck</option>");
    echo ("<option value='2'>Rhinar Starter Deck</option>");
    echo ("<option value='3'>Katsu Starter Deck</option>");
    echo ("<option value='4'>Dorinthea Starter Deck</option>");
    echo ("<option value='5'>Dash Starter Deck</option>");
    echo ("<option value='6'>Viserai Starter Deck</option>");
    echo ("<option value='7'>Kano Starter Deck</option>");
    echo ("<option value='8'>Azalea Starter Deck</option>");
    echo ("<option value='9'>Prism Starter Deck</option>");
    echo ("<option value='10'>Levia Starter Deck</option>");
    echo ("<option value='11'>Boltyn Starter Deck</option>");
    echo ("<option value='12'>Chane Starter Deck</option>");
    echo ("<option value='13'>Oldhim Starter Deck</option>");
    echo ("<option value='14'>Briar Starter Deck</option>");
    echo ("<option value='15'>Lexi Starter Deck</option>");
    echo ("<option value='16'>Fai Starter Deck</option>");
    echo ("<option value='17'>Dromai Starter Deck</option>");
    echo ("</select></div>");
  }
  echo ("<br>");

  ?>
    <label for="fabdb" style='font-weight:bolder; margin-left:10px;'>Deck Link:</label>
    <input type="text" id="fabdb" name="fabdb">&nbsp;
  <?php
  if (isset($_SESSION["userid"])) {
    echo ("<span style='display:inline;'>");
    echo ("<input class='inputFavoriteDeck' type='checkbox' id='favoriteDeck' name='favoriteDeck' />");
    echo ("<label title='Save deck to Favorites' for='favoriteDeck'>&nbsp;</label>");
    echo ("</span>");
  }
  echo ("<br>");
  ?>
  <br>
    <label for="gameDescription" style='font-weight:bolder; margin-left:10px;'>Game Name:</label>
    <input type="text" id="gameDescription" name="gameDescription" placeholder="Game #"><br><br>

    <label for="gameKarmaRestriction" style='font-weight:bolder; margin-left:10px;'>Restrict by Reputation:</label>
  <select class="karmaRestriction-Select" name='gameKarmaRestriction' id='gameKarmaRestriction'>
    <option value="0">No Restriction</option>
    <option value="50">☯ ≥ 50% - Exclude players with a karma below 50% (Bad reputation).</option>
    <option value="75">☯ ≥ 75% - Only players with a good reputation.</option>
    <option value="85">☯ ≥ 85% - Only players with a very good reputation, while excluding new players.</option>
  </select><br><br>



  <span style='display:inline-block; margin-left:5px;'>
      <input type="radio" id="blitz" name="format" value="blitz" checked="checked">
      <label style='margin-left:-10px;' for="blitz">Blitz</label>
  </span>

  <span style='display:inline-block;'>
      <input type="radio" id="cc" name="format" value="cc">
      <label style='margin-left:-12px;' for="cc">Classic Constructed</label>
  </span>

    <input type="radio" id="commoner" name="format" value="commoner">
    <label style='margin-left:-12px;' for="commoner">Commoner</label><br><br>

  <span style='display:inline-block; margin-left:5px;'>
      <input type="radio" id="public" name="visibility" value="public" checked="checked">
      <label style='margin-left:-12px;' for="public">Public</label>
  </span>

    <input type="radio" id="private" name="visibility" value="private">
    <label style='margin-left:-12px;' for="private">Private</label><br><br>

  <input style="margin-left: 20px;" type="checkbox" id="deckTestMode" name="deckTestMode" value="deckTestMode">
  <label for="deckTestMode">Single Player Mode</label><br><br>
  <div style="text-align:center;">

    <label>
      <input class="CreateGame_Button" type="submit" value="Create Game">
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

<div class="NewsMenu">

  <h1>Open Beta Test</h1>
  <h2>All cards supported!</h2>

  <div style='padding-top:10%;'>
    <h2>FaBrary Deck Integration!</h2>
  </div>
  <div style='vertical-align:middle;'><a href='https://fabrary.net/decks/' target='_blank'><img style='padding-left:25%; width:50%;' src="./Images/fabraryLogo.png" /></a></div>
  <h2 style='width:90%; padding-left:5%;'>Click above to explore tournament winning decks!</h2>

  <!--
  <div style='position:absolute; bottom:10px; left:10px;'>
    <h1 style="width:100%; text-align:center; color:rgb(220, 220, 220);">Learn to Play Videos</h1>
    <a title='Italian' href='https://youtu.be/xj5vg1BsNPk' target='_blank'><img style='height:40px;' src='./Images/flags/italy.png' /></a>
    <br><br>
    <i style="font-size: small;">If you make a video in another language, let us know on Discord!</i>
  </div>
-->

</div>

<?php
include_once 'Footer.php';
?>