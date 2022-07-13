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
h1 {
  margin-top: 6px;
  text-align:center;
  width:100%;
  color: #EDEDED;
  text-shadow: 2px 0 0 #1a1a1a, 0 -2px 0 #1a1a1a, 0 2px 0 #1a1a1a, -2px 0 0 #1a1a1a;
}

h2 {
  text-align:center;
  width:100%;
  text-shadow: 2px 0 0 #1a1a1a, 0 -2px 0 #1a1a1a, 0 2px 0 #1a1a1a, -2px 0 0 #1a1a1a;
}

p, div, a{
  text-shadow: 2px 0 0 #1a1a1a, 0 -2px 0 #1a1a1a, 0 2px 0 #1a1a1a, -2px 0 0 #1a1a1a;
}
select, input{
  font-weight: bold;
}
</style>

<div style="position:absolute; z-index:1; top:15%; left:2%; width:25%; height:70%;
background-color:rgba(74, 74, 74, 0.9);
border: 2px solid #1a1a1a;
border-radius: 3px;">
<h2>Game Lobby</h2>
<?php
  echo("<form action='" . $redirectPath . "/JoinGameInput.php'>");
  echo("<input type='hidden' id='gameName' name='gameName' value='$gameName'>");
  echo("<input type='hidden' id='playerID' name='playerID' value='$playerID'>");
?>

<div style="margin-left: 10px;">Decks to Try:
  <select name="decksToTry" id="decksToTry">
    <option value="1">Dori Axes CC</option>
    <option value="2">Bravo CC</option>
    <option value="3">Mountain Briar CC</option>
    <option value="4">Stubby Katsu CC</option>
  </select>
</div><br>

<a title='FaBDB Deckbuilder' href='https://fabdb.net/decks' target='_blank'><img style='height:75px; position:absolute;
  right:15px; top:10px;' src='./Images/fabdb-symbol.png'/></a>

  <label for="fabdb" style="margin-right: -10px;">FaB DB Link:</label>
  <input type="text" id="fabdb" name="fabdb"><br><br>

  <div style='width:100%; text-align:center;'><input type="submit" value="Submit"></div>
</form><br>

  <h2 style="border-top: 5px solid;">Instructions</h2>

  <p style="margin-left: 8px;">Choose a deck and click submit. You will be taken to the game lobby.</p><br>
  <p style="margin-left: 8px;">Once in the game lobby, the player who win the dice roll choose if the go first. Then the host can start the game.</p>

</div>
</div>

<?php
  include_once 'Footer.php'
?>
