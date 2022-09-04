<?php

include_once 'Header.php';
include "HostFiles/Redirector.php";

if (!empty($_SESSION['error'])) {
  echo "<script>alert('" . $_SESSION['error'] . "')</script>";
  unset($_SESSION['error']);
}

if (isset($_SESSION["userid"])) {
  $uidExists = getUInfo($conn, $_SESSION['useruid']);
  $_SESSION["userKarma"] = $uidExists["usersKarma"];
  $_SESSION["greenThumb"] = $uidExists["greenThumbs"];
  $_SESSION["redThumb"] = $uidExists["redThumbs"];
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

<!--<div class="FabLogo" style="background-image: url('Images/fab_logo.png');">-->
<div class="FabLogo">
  <h1 style='font-size:44px; text-align: center;'>Flesh and Blood Online</h1>
  <h2 style='font-size:30px; text-align: center;'>Unofficial Online Client</h2>
</div>
<!--</div>-->

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

    <input type="radio" id="blitz" name="format" value="blitz">
    <label style='margin-left:-10px;' for="blitz">Blitz</label>

  <input style='margin-left: 10px;' type="radio" id="cc" name="format" value="cc" checked="checked">
  <label for="cc">CC</label>
  <br class="BRMobile">

  <input style='margin-left: 10px;' type="radio" id="compcc" name="format" value="compcc">
  <label for="compcc">Competitive CC</label>
  <br class="BRMobile">

    <input style='margin-left: 0px;' type="radio" id="commoner" name="format" value="commoner">
    <label style='margin-left:-12px;' for="commoner">Commoner</label>
  <br><br>

    <input type="radio" id="public" name="visibility" value="public" checked="checked">
    <label style='margin-left:-12px;' for="public">Public</label>

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
  <p style='margin:10px; font-size:13px;'><b>Disclaimer: </b>FaB Online is a fan-made project that is still under active development. There are still many bugs, although we try to improve it a little bit each day.</p>

  <h3 style='text-align:center;'>________</h3>

  <div style="position: relative;">
    <div style='vertical-align:middle; text-align:center;'>
      <h2>Commotion #2 has concluded!</h2>
    <img style="width:95%; border-radius:2.5%;" src="./Images/challenges/challenge2.webp" /><br>
    <h3 style="margin-left:15px; margin-right:15px;">Results</h3>
    <table style='margin-left: 20px; width:100%; text-align:left;'>
      <tr>
        <td>Player</td><td>Wins</td>
      </tr>
      <tr>
        <td>Doomsayer</td><td>67</td>
      </tr>
      <tr>
        <td>Tubby Watkins</td><td>44</td>
      </tr>
      <tr>
        <td>Taliksanis</td><td>42</td>
      </tr>
      <tr>
        <td>TheOrknight</td><td>23</td>
      </tr>
      <tr>
        <td>TheTablePitTY</td><td>18</td>
      </tr>
    </table>
  </div>

  <!--
  <div style=" padding-top:10%; vertical-align:middle; position: relative;">
      <div style="vertical-align:middle; position: relative;">
        <h2>Coax a Commotion!</h2>
        <h4>Win as many games as you can <br>with Sigil of Solace (3)</h4><br>
        <img style="margin-left:30%; width:40%; height:150px; border-radius:5%;" src="./concat/WTR175.webp" /><br><br>
        <p style="width:90%; padding-left:5%; font-size:small;">Must be logged in with max copies of Sigil of Solace (3) in your deck <i>after sideboarding</i> for the challenge to work. Check back soon for results!</p>
      </div>
      -->

  <h3 style='text-align:center;'>________</h3>

  <div style='vertical-align:middle; text-align:center;'>
    <h2 style="width:100%; text-align:center; color:rgb(220, 220, 220); font-size:20px;">Learn to Play FaB Online</h2>
    <a title='English' href='https://youtu.be/zxQStzZPVGI' target=' _blank'><img style='height:30px;' src='./Images/flags/uk.png' /></a>
    <a title='Italian' href='https://youtu.be/xj5vg1BsNPk' target='_blank'><img style='height:30px;' src='./Images/flags/italy.png' /></a>
    <br>
    <p style="text-align: center; font-size:small; width:90%; padding-left:5%;">If you make a video in another language, let us know on Discord!</p>
  </div>
</div>

<?php
include_once 'Footer.php';
?>
