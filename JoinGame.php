<?php
include_once 'Header.php';
?>

<?php

include "HostFiles/Redirector.php";
include "Libraries/HTTPLibraries.php";

$gameName = $_GET["gameName"];
if (!IsGameNameValid($gameName)) {
  echo ("Invalid game name.");
  exit;
}
$playerID = $_GET["playerID"];
if($playerID == "1")
{
  echo("Player 1 should not use JoinGame.php");
  exit;
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

<div class='JoinGameDiv'>
  <h1>Game Lobby</h1></br>

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
      echo ("<div class='DeckToTry'>Favorite Decks: ");
      echo ("<select style='height:26px;' name='favoriteDecks' id='favoriteDecks'>");
      for ($i = 0; $i < count($favoriteDecks); $i += 3) {
        echo ("<option value='" . $favoriteDecks[$i] . "'>" . $favoriteDecks[$i + 1] . "</option>");
      }
      echo ("</select></div><br>");
    }
  }
  if (count($favoriteDecks) == 0) {
    echo ("<div class='DeckToTry'>CC Starter Decks: ");
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
    echo ("</select></div><br>");
  }

  ?>
    <label for="fabdb" style='font-weight:bolder; margin-left:71px;'>Deck Link:</label>
    <input type="text" id="fabdb" name="fabdb">
  &nbsp;
  <?php
  if (isset($_SESSION["userid"])) {
    echo ("<span style='display:inline;'>");
    echo ("<input title='Save deck to Favorites' class='inputFavoriteDeck' type='checkbox' id='favoriteDeck' name='favoriteDeck' />");
    echo ("<label for='favoriteDeck'>&nbsp;</label>");
    echo ("</span>");
  }
  echo ("<BR>");
  echo ("<BR>");
  ?>

  <div style='text-align:center;'><input class="JoinGame_Button" type="submit" value="Submit"></div>
  </form><br>

  <h3 style="text-align:center;">_____________________________</h3>
  <h2>Instructions</h2>

  <p style="text-align:center; padding:10px;">Choose a deck and click submit. You will be taken to the game lobby.</p><br>
  <p style="text-align:center; padding:10px;">Once in the game lobby, the player who win the dice roll choose if the go first. Then the host can start the game.</p><br>
  <p style="text-align:center; font-size: 20px; padding:5px;">Have Fun!</p>
</div>
</div>

<?php
include_once 'Footer.php'
?>
