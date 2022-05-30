<?php
   include_once 'Header.php';
?>

<?php
   include "HostFiles/Redirector.php";
?>

<!-- <div style="position:absolute; top: 50px; left: 40%; width: 30%; height: 30%;
  background-image: url('Images/fab_logo.png'); background-size: 85% auto; z-index=1; background-repeat:no-repeat;">
</div> -->

<div style="
  position: absolute; width: 20%; height: 20%; z-index: 15; top: 50px; left: 50%; margin: 0 0 0 -10%; /* -13% = half of width/height */
  background-image: url('Images/fab_logo.png'); background-size: 100% auto; background-repeat: no-repeat;">
</div>

<div style="position:absolute; top:50px; left:10px; width:25%; bottom: 50px; background-color:rgba(74, 74, 74, 0.6);">
  <?php
    include "ServerChecker.php";
   ?>
</div>

<div style="position:absolute; top:28%; left:50%; width:30%; bottom: 50px; margin: 0 0 0 -15%; /* -13% = half of width/height */
  background-color:rgba(74, 74, 74, 0.6);">

<h1>Create a new Game</h1>

<?php
  echo("<form style='width:100%;display:inline-block;' action='" . $redirectPath . "/CreateGame.php'>");
?>

<div style="margin-left: 8px; font-size:19px;">Decks to Try:
  <select name="decksToTry" id="decksToTry">
    <option value="1">Dori Axes CC</option>
    <option value="2">Bravo CC</option>
    <option value="3">Mountain Briar CC</option>
    <option value="4">Stubby Katsu CC</option>
  </select>
</div>

<a title='FaBDB Deckbuilder' href='https://fabdb.net/decks' target='_blank'><img style='height:80px; position:absolute;
  right:20px; top:60px;' src='./Images/fabdb-symbol.png' /></a><br>

  <label for="fabdb">FaB DB Deck Link:</label>
  <input type="text" id="fabdb" name="fabdb"><br><br>

  <span style='display:inline-block;'>
    <input type="radio" id="blitz" name="format" value="blitz" checked="checked">
    <label for="blitz">Blitz</label>
  </span>

  <span style='display:inline-block;'>
    <input type="radio" id="cc" name="format" value="cc">
    <label for="cc">Classic Constructed</label><br>
  </span>

  <input type="radio" id="commoner" name="format" value="commoner">
  <label for="commoner">Commoner</label><br><br>

  <span style='display:inline-block;'>
    <input type="radio" id="public" name="visibility" value="public" checked="checked">
    <label for="public">Public</label>
  </span>

  <input type="radio" id="private" name="visibility" value="private">
  <label for="private">Private</label><br><br>

  <input style="margin-left: 10px;" type="checkbox" id="deckTestMode" name="deckTestMode" value="deckTestMode">
  <label for="deckTestMode">Single player deck test mode</label><br><br>
  <div style="text-align:center;"><input type="submit" style="font-size:20px;" value="Create Game"></div>
</form>


<?php
  //echo("<form style='width:100%;display:inline-block;' action='" . $redirectPath . "/PVE/PVEMenu.php'>");
?>
<!---<div style="text-align:center;"><input type="submit" style="font-size:20px;" value="PVE Menu"></div>
</form>
--->
</div>
</div>

<div style="position:absolute; top:50px; right:10px; width:25%; bottom: 50px; background-color:rgba(74, 74, 74, 0.6);">
<h1 style="width:100%; text-align:center; color:rgb(220, 220, 220);">Open Beta Test</h1>
<h3 style="width:100%; text-align:center; color:rgb(220, 220, 220);">ALL cards supported!</h3>
<?php
  echo("<form style='width:100%;display:inline-block;' action='" . $redirectPath . "/DraftFiles/CreateGame.php'>");
?>
  <input type="hidden" id="numPlayers" name="numPlayers" value="8" />
  <div style="text-align:center;"><input type="submit" style="font-size:20px;" value="Tales of Aria Solo Draft Practice"></div>
</form>


<?php
  echo("<form style='width:100%;display:inline-block;' action='" . $redirectPath . "/WTRDraftFiles/CreateGame.php'>");
?>
  <input type="hidden" id="numPlayers" name="numPlayers" value="8" />
  <div style="text-align:center;"><input type="submit" style="font-size:20px;" value="WTR Solo Draft Practice"></div>
</form>
  <div style='position:absolute; bottom:10px; left:10px;'>
    <h1 style="width:100%; text-align:center; color:rgb(220, 220, 220);">Upcoming Events</h1>
    <a title='Team GG' href='https://www.azcardtrading.it/collections/events-tournaments' style='color:rgb(220, 220, 220); display: block; text-align: center;' target='_blank'>Online Armory from Team GG every Tuesday!</a>
    <h1 style="width:100%; text-align:center; color:rgb(220, 220, 220);">Learn to Play Videos</h1>
    <a title='Italian' href='https://youtu.be/xj5vg1BsNPk' target='_blank'><img style='height:40px;' src='./Images/flags/italy.png' /></a>
    <br><br>
    <i style="font-size: small;">If you make a video in another language, let us know on Discord!</i>
  </div>

</div>

<?php
  include_once 'Footer.php'
?>
