
<?php
   include "HostFiles/Redirector.php";
?>
<head>
  <link rel="shortcut icon" type="image/png" href="./Images/favicon3.png"/>
<style>
body {
  font-family: Garamond, serif;
  margin:0px;
  color:rgb(240, 240, 240);
}
</style>
</head>
<body>
<div style="width:100%; height:100%; background-image: url('Images/talesofariamainmenu.jpg'); background-size:cover; z-index=0;">
<div style="position:absolute; left: 35%; width:30%; height:30%; background-image: url('Images/fab_logo.png'); background-size:100% auto; z-index=1; background-repeat:no-repeat;"></div>

<div style='position:absolute; top:10px; right:10px;'>
<a target="_blank" href='https://discord.gg/JykuRkdd5S'>
<img src='Images/discord.png' width='64px' height='64px' />
</a>
</div>

<div style='position:absolute; top:23px; right:84px;'><a href="https://www.patreon.com/bePatron?u=36985868" data-patreon-widget-type="become-patron-button">Become a Patron!</a></div>
<script async src="https://c6.patreon.com/becomePatronButton.bundle.js"></script>


<div style="position:absolute; top:30px; left:30px; width:20%; height:90%; background-color:rgba(59, 59, 38, 0.7);">

<?php
   include "ServerChecker.php";
?>
</div>

<div style="position:absolute; top:32%; left:35%; width:30%; height:60%; background-color:rgba(59, 59, 38, 0.7);">
<h1 style="width:100%; text-align:center; color:rgb(240, 240, 240);">Create a new Game</h1>

<?php
  echo("<form style='width:100%;display:inline-block;' action='" . $redirectPath . "/CreateGame.php'>");
?>
  <input type="radio" id="oot" name="deck" value="oot" checked="checked">
  <label for="oot">Oot's Guardian Deck</label><br>
  <input type="radio" id="shane" name="deck" value="shane">
  <label for="shane">Shane's Brute Deck</label><br>
  <input type="radio" id="shawn" name="deck" value="shawn">
  <label for="shawn">Shawn's TAD Dash Deck</label><br>
  <input type="radio" id="dori" name="deck" value="dori">
  <label for="dori">Dorinthea Deck - Skirmish 19.06.21<a target="_blank" rel="noopener noreferrer" href="https://fabtcg.com/decklists/dan-groseclose-dorinthea-deck---sapphire-city-skirmish-190621/">(Link)</a></label><br>
  <input type="radio" id="katsu" name="deck" value="katsu">
  <label for="katsu">Katsu Deck</label><br><br>
  <input type="text" id="fabdb" name="fabdb">
  <label for="fabdb">FaB DB Link</label><br><br>

  <span style='display:inline-block;'>
  <input type="radio" id="blitz" name="format" value="blitz" checked="checked">
  <label for="blitz">Blitz</label>
  </span>
  <input type="radio" id="cc" name="format" value="cc">
  <label for="cc">Classic Constructed</label><br>
  <BR>

  <span style='display:inline-block;'>
  <input type="radio" id="public" name="visibility" value="public" checked="checked">
  <label for="public">Public</label>
  </span>
  <input type="radio" id="private" name="visibility" value="private">
  <label for="private">Private</label><br>
  <BR>

  <input type="checkbox" id="deckTestMode" name="deckTestMode" value="deckTestMode">
  <label for="deckTestMode">Single player deck test mode</label><br><br>
  <div style="text-align:center;"><input type="submit" style="font-size:20px;" value="Create Game"></div>
</form>
<br>

<!---
<?php
  echo("<form style='width:100%;display:inline-block;' action='" . $redirectPath . "/PVE/PVEMenu.php'>");
?>
  <div style="text-align:center;"><input type="submit" style="font-size:20px;" value="PVE Menu"></div>
</form>
--->
</div>
</div>

<div style="position:absolute; top:100px; right:30px; width:30%; height:80%; background-color:rgba(59, 59, 38, 0.7);">
<h1 style="width:100%; text-align:center; color:rgb(220, 220, 220);">Open Beta Test</h3>
<h2 style="width:100%; text-align:center; color:rgb(220, 220, 220);">New UI Update! 12/30/21</h2>
<h3 style="width:100%; text-align:center; color:rgb(220, 220, 220);">All cards supported!</h3>

<?php
  echo("<form style='width:100%;display:inline-block;' action='" . $redirectPath . "/DraftFiles/CreateGame.php'>");
?>
  <input type="hidden" id="numPlayers" name="numPlayers" value="8" />
  <div style="text-align:center;"><input type="submit" style="font-size:20px;" value="Solo Draft Practice"></div>
</form>
</div>

<div style="height:20px; bottom:30px; left:5%; width: 90%; position:absolute; color:white;">FaB Online is in no way affiliated with Legend Story Studios. Legend Story Studios®, Flesh and Blood™, and set names are trademarks of Legend Story Studios. Flesh and Blood characters, cards, logos, and art are property of Legend Story Studios.  Card Images © Legend Story Studios</div>
</div>
</body>