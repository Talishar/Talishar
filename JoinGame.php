<?php
   include_once 'Header.php';
?>

<?php

  include "HostFiles/Redirector.php";
  include "Libraries/HTTPLibraries.php";

  $gameName=$_GET["gameName"];
  if(!IsGameNameValid($gameName)) { echo("Invalid game name."); exit; }
  $playerID=$_GET["playerID"];
?>
<style>
body {
  margin:0px;
  color:rgb(240, 240, 240);
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
  echo("<form action='" . $redirectPath . "/JoinGameInput.php'>");
  echo("<input type='hidden' id='gameName' name='gameName' value='$gameName'>");
  echo("<input type='hidden' id='playerID' name='playerID' value='$playerID'>");
?>

<?php
  echo("<form style='width:100%;display:inline-block;' action='" . $redirectPath . "/CreateGame.php'>");

  $favoriteDecks = [];
  if(isset($_SESSION["userid"]))
  {
    $favoriteDecks = LoadFavoriteDecks($_SESSION["userid"]);
    if(count($favoriteDecks) > 0)
    {
      echo("<div class='DeckToTry'>Favorite Decks: ");
      echo("<select style='height:26px;' name='favoriteDecks' id='favoriteDecks'>");
      for($i=0; $i<count($favoriteDecks); $i+=3)
      {
        echo("<option style='background-image: url(crop/". $favoriteDecks[$i+2] .")''; value='" . $favoriteDecks[$i] . "'>" . $favoriteDecks[$i+1] . "</option>");
      }
      echo("</select></div><br>");
    }
  }
  if(count($favoriteDecks) == 0)
  {
    echo("<div class='DeckToTry'>CC Starter Decks: ");
    echo("<select name='decksToTry' id='decksToTry'>");

      echo("<option value='1'>Bravo Starter Deck</option>");
      echo("<option value='2'>Rhinar Starter Deck</option>");
      echo("<option value='3'>Katsu Starter Deck</option>");
      echo("<option value='4'>Dorinthea Starter Deck</option>");
      echo("<option value='5'>Dash Starter Deck</option>");
      echo("<option value='6'>Viserai Starter Deck</option>");
      echo("<option value='7'>Kano Starter Deck</option>");
      echo("<option value='8'>Azalea Starter Deck</option>");
    echo("</select></div><br>");
  }

?>
<div class='containerFavoriteDeck'>
  <label for="fabdb" style='font-weight:bolder; margin-left:10px;'>Deck Link:</label>
  <input type="text" id="fabdb" name="fabdb">
&nbsp;
<?php
  //if(isset($_SESSION["userid"])) echo("<div style='display:inline; cursor:pointer;'><img style='margin-bottom:-10px; height:32px;' src='./Images/favoriteUnfilled.png' /></div>");
  if(isset($_SESSION["userid"]))
  {
    echo("<input class='inputFavoriteDeck' type='checkbox' id='favoriteDeck' name='favoriteDeck' />");
    echo("<label for='favoriteDeck'>Save as favorite?</label>");
    echo("</div><br>");
  }
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
