
<?php
   include "../HostFiles/Redirector.php";
?>
<head>
  <link rel="shortcut icon" type="image/png" href="./PVEImages/OE_icon.png"/>
<style>
body {
  font-family: Garamond, serif;
  margin:0px;
  color:rgb(240, 240, 240);
}
</style>
</head>
<body>
<div style="width:100%; height:100%; background-image: url('PVEImages/menuBackground.png'); background-size:cover; z-index=0;">

<div style="position:absolute; top:10%; left:10%; width:90%; height:90%;">

<div style='color:white; font-family:sans-serif; font-size:62px;'>Official Bosses</div>
<div style='color:white; font-family:sans-serif; font-size:16px; line-height:24px;'>Select one of the bosses below to proceed. For new players, we recommend playing against the <b>Red Dragon</b> first.</div>

<BR>
<table>
<tr><td>
<div>Red Dragon<br><img src="PVEImages/Bosses/dragonPortrait.png" alt="Red Dragon"></div>
</td><!--<td>
<div>Kima, the Floodbringer<br><img src="PVEImages/Bosses/kimaPortrait.png" alt="Kima, the Floodbringer"></div>
</td><td>
<div>Ozvag, the Miser<br><img src="PVEImages/Bosses/ozvagPortrait.png" alt="Ozvag, the Miser"></div>
</td><td>
<div>Howling Woodsman<br><img src="PVEImages/Bosses/woodsmanPortrait.png" alt="Howling Woodsman"></div>
</td><td>
<div>Rog Kehz, Swarm Lord<br><img src="PVEImages/Bosses/swarmLordPortrait.png" alt="Rog Kehz, Swarm Blue"></div>-->
</td></tr></table>

<BR>

<form action="./CreateGame.php">
  <span style='font-family:sans-serif;'>Number of Players:</span>
  <input type="hidden" name="boss" value="1" />
  <label class="">Two
    <input type="radio" checked="checked" name="players" value="2">
    <span class=""></span>
  </label>
  <label class="">Three
    <input type="radio" checked="checked" name="players" value="3">
    <span class=""></span>
  </label>
  <label class="">Four
    <input type="radio" checked="checked" name="players" value="4">
    <span class=""></span>
  </label>
  <br>
  <input type="image" src="PVEImages/playBeta.png" alt="Play Beta" />
</form>

<!--<a href="./CreateGame.php"><img src="PVEImages/playBeta.png" alt="Play Beta"></a>-->

</div>

</div>

<div style="height:20px; bottom:30px; left:5%; width: 90%; position:absolute; color:white;">FaB Online is in no way affiliated with Legend Story Studios. Legend Story Studios®, Flesh and Blood™, and set names are trademarks of Legend Story Studios. Flesh and Blood characters, cards, logos, and art are property of Legend Story Studios.  Card Images © Legend Story Studios</div>
</div>
</body>