<head>
<style>
body {
  font-family: Garamond, serif;
  margin:0px;
  color:rgb(240, 240, 240);
}

h1 {
  text-align:center;
  width:100%;
}

h2 {
  text-align:center;
  width:100%;
}
</style>
</head>


<?php

  include "HostFiles/Redirector.php";
  include "Libraries/HTTPLibraries.php";

  $gameName=$_GET["gameName"];
  if(!IsGameNameValid($gameName)) { echo("Invalid game name."); exit; }
  $playerID=$_GET["playerID"];

?>

<div style="width:100%; height:100%; background-image: url('Images/lord-of-wind.jpg'); background-size:cover; z-index=0;">

<div style="position:absolute; z-index:1; top:25%; left:2%; width:25%; height:50%; background-color:rgba(59, 59, 38, 0.7);">
<h1>Game Lobby</h1>
<?php
  echo("<form action='" . $redirectPath . "/JoinGameInput.php'>");
  echo("<input type='hidden' id='gameName' name='gameName' value='$gameName'>");
  echo("<input type='hidden' id='playerID' name='playerID' value='$playerID'>");
?>
  <input type="radio" id="oot" name="deck" value="oot">
  <label for="oot">Oot's Guardian Deck</label><br>
  <input type="radio" id="shane" name="deck" value="shane">
  <label for="shane">Shane's Brute Deck</label><br>
  <input type="radio" id="shawn" name="deck" value="shawn">
  <label for="shawn">Shawn's TAD Dash Deck</label><br>
  <input type="radio" id="dori" name="deck" value="dori">
  <label for="dori"><a target="_blank" rel="noopener noreferrer" href="https://fabtcg.com/decklists/dan-groseclose-dorinthea-deck---sapphire-city-skirmish-190621/">Dorinthea Deck - Sapphire City Skirmish 19.06.21</a></label><br>
  <input type="radio" id="katsu" name="deck" value="katsu">
  <label for="katsu">Katsu Deck</label><br>
  <input type="text" id="fabdb" name="fabdb">
  <label for="fabdb">FaB DB Link</label><br><br>
  <input type="submit" value="Submit">
</form>

  <h2>Instructions</h2>
  <ul>
  <li>Once you choose a deck and submit, you will be taken to the game lobby.</li>
  <li>Once in the game lobby, the first player will be able to start the game.</li>
  </ul>

</div>
</div>

<div style="height:20px; bottom:30px; left:5%; width: 90%; position:absolute; color:white;">FaB Online is in no way affiliated with Legend Story Studios. Legend Story Studios®, Flesh and Blood™, and set names are trademarks of Legend Story Studios. Flesh and Blood characters, cards, logos, and art are property of Legend Story Studios.</div>