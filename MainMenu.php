
<?php
   $redirectPath="https://localhost/fabonlinelocal";
?>

<style>
body {
  font-family: Garamond, serif;
  margin:0px;
  color:rgb(240, 240, 240);
}
</style>
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
  <div style="text-align:center;"><input type="submit" value="Create Game"></div>
</form>
<br>
<h3 style="width:100%; text-align:center; color:rgb(220, 220, 220);">UI Polish Update! 9/10/21</h3>
<div style="width:100%; text-align:left; color:rgb(220, 220, 220);">- Highlight cards that are playable</div>
<div style="width:100%; text-align:left; color:rgb(220, 220, 220);">- Change combat chain card color</div>
<div style="width:100%; text-align:left; color:rgb(220, 220, 220);">- Fix card highlight from going off the screen</div>
<div style="width:100%; text-align:left; color:rgb(220, 220, 220);">- Add player color coded highlight to played cards in game log</div>
<div style="width:100%; text-align:left; color:rgb(220, 220, 220);">- Add hover to discover functionality to played cards in game log</div>
<h3 style="width:100%; text-align:center; color:rgb(220, 220, 220);">MON Light/CRU Mech 9/10/21 Supported cards:</h3>
<div style="width:100%; text-align:left; color:rgb(220, 220, 220);">- ALL of WTR</div>
<div style="width:100%; text-align:left; color:rgb(220, 220, 220);">- ALL Mechanologist except Data Doll and Meganetic Shockwave</div>
<div style="width:100%; text-align:left; color:rgb(220, 220, 220);">- ALL Warrior except Kassai</div>
<div style="width:100%; text-align:left; color:rgb(220, 220, 220);">- ALL Guardian except Righteous Cleansing</div>
<div style="width:100%; text-align:left; color:rgb(220, 220, 220);">- ALL Ranger except Silver the Tip</div>
<div style="width:100%; text-align:left; color:rgb(220, 220, 220);">- All ARC Wizard except Index and Sonic Boom (partial)</div>
<div style="width:100%; text-align:left; color:rgb(220, 220, 220);">- All ARC Generic except Chains of Eminence</div>
<div style="width:100%; text-align:left; color:rgb(220, 220, 220);">- All CRU Brute except Kayo, Beast Within, Massacre, and Argh... Smash!</div>
<div style="width:100%; text-align:left; color:rgb(220, 220, 220);">- All CRU Generic except Gambler's Gloves</div>
<div style="width:100%; text-align:left; color:rgb(220, 220, 220);">- All MON Generic except Exude Confidence, Rouse the Ancients, Rally the Rearguard</div>
<div style="width:100%; text-align:left; color:rgb(220, 220, 220);">- All MON Light except Glisten, Blinding Beam, Ray of Hope</div>

</div>
</div>

<div style="height:20px; bottom:30px; left:5%; width: 90%; position:absolute; color:white;">FaB Online is in no way affiliated with Legend Story Studios. Legend Story Studios®, Flesh and Blood™, and set names are trademarks of Legend Story Studios. Flesh and Blood characters, cards, logos, and art are property of Legend Story Studios.</div>
</div>
</body>
