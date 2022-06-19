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

<div style="position:absolute; top:50px; left:10px; width:25%; bottom: 30px;
  background-color:rgba(74, 74, 74, 0.8);
  border: 2px solid black;
  border-radius: 3px;">
  <?php
    include "ServerChecker.php";
   ?>
</div>

<div style="position:absolute; top:28%; left:50%; width:40%; bottom: 30px; margin: 0 0 0 -20%; /* -13% = half of width/height */
  background-color:rgba(74, 74, 74, 0.8);
  border: 2px solid black;
  border-radius: 3px;">

<h1 style="margin-top: 3px;">Create New Game</h1>

<?php
  echo("<form style='width:100%;display:inline-block;' action='" . $redirectPath . "/CreateGame.php'>");
?>

<div style="margin-left: 8px; font-size:19px; font-weight:bolder;">Decks to Try:
  <select name="decksToTry" id="decksToTry">
    <option value="1">Dori Axes CC</option>
    <option value="2">Bravo CC</option>
    <option value="3">Mountain Briar CC</option>
    <option value="4">Stubby Katsu CC</option>
  </select>
</div>

<a title='FaBDB Deckbuilder' href='https://fabdb.net/decks' target='_blank'><img style='height:80px; position:absolute;
  right:20px; top:60px;' src='./Images/fabdb-symbol.png' /></a><br>

  <label for="fabdb" style='font-weight:bolder; margin-right:-10px;'>FaB DB Deck Link:</label>
  <input type="text" id="fabdb" name="fabdb"><br><br>

  <span style='display:inline-block;'>
    <input type="radio" id="blitz" name="format" value="blitz" checked="checked">
    <label style='margin-left:-12px;' for="blitz">Blitz</label>
  </span>

  <span style='display:inline-block;'>
    <input type="radio" id="cc" name="format" value="cc">
    <label style='margin-left:-12px;' for="cc">Classic Constructed</label>
  </span>

  <input type="radio" id="commoner" name="format" value="commoner">
  <label style='margin-left:-12px;' for="commoner">Commoner</label><br><br>

  <span style='display:inline-block;'>
    <input type="radio" id="public" name="visibility" value="public" checked="checked">
    <label style='margin-left:-12px;' for="public">Public</label>
  </span>

  <input type="radio" id="private" name="visibility" value="private">
  <label style='margin-left:-12px;' for="private">Private</label><br><br>

  <input style="margin-left: 10px;" type="checkbox" id="deckTestMode" name="deckTestMode" value="deckTestMode">
  <label for="deckTestMode">Single Player Mode</label><br><br>
  <div style="text-align:center;">

    <label>
      <input style='display:none;' type="submit">
      <svg style='cursor:pointer;' version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
      	 viewBox="0 0 300 64" style="enable-background:new 0 0 300 64;" xml:space="preserve">
      <style type="text/css">
      	.st0{fill:#FFFFFF;}
      </style>
      <g>
      	<path class="st0" d="M72.02,48.91c-5.8,0-10.52-4.72-10.52-10.52V25.6c0-5.8,4.72-10.52,10.52-10.52h155.97
      		c5.8,0,10.52,4.72,10.52,10.52V38.4c0,5.8-4.72,10.52-10.52,10.52H72.02z"/>
      	<path d="M227.98,17.59c4.42,0,8.02,3.6,8.02,8.02V38.4c0,4.42-3.6,8.02-8.02,8.02H72.02c-4.42,0-8.02-3.6-8.02-8.02V25.6
      		c0-4.42,3.6-8.02,8.02-8.02H227.98 M227.98,12.59H72.02C64.83,12.59,59,18.41,59,25.6V38.4c0,7.19,5.83,13.02,13.02,13.02h155.97
      		c7.19,0,13.02-5.83,13.02-13.02V25.6C241,18.41,235.17,12.59,227.98,12.59L227.98,12.59z"/>
      </g>
      <g>
      	<path d="M89.18,35.74c-1.2,2.01-3.43,3.49-6.27,3.49c-4.2,0-7.26-3.08-7.26-7.04c0-3.73,3.06-6.84,7.26-6.84
      		c2.86,0,5.11,1.34,6.31,3.35l-2.74,1.6c-0.83-1.22-1.87-2.03-3.57-2.03c-2.35,0-3.98,1.72-3.98,3.92c0,2.13,1.64,4,3.98,4
      		c1.66,0,2.88-0.85,3.53-2.03L89.18,35.74z"/>
      	<path d="M102.6,30.82c0,1.93-0.95,3.57-2.39,4.34l2.76,4.04h-4.02l-2.01-3.33h-2.37V39h-3.23V25.59h6.49
      		C100.55,25.59,102.6,27.7,102.6,30.82z M94.57,28.58v4.08h2.78c1.1,0,1.99-0.93,1.99-2.05c0-1.1-0.89-2.03-1.97-2.03H94.57z"/>
      	<path d="M115.55,25.59v3h-7.04v2.31h5.88v2.8h-5.88V36h7.04v3h-10.27V25.59H115.55z"/>
      	<path d="M128.17,36.91h-6.47L120.81,39h-3.43l5.74-13.41h3.61L132.48,39h-3.45L128.17,36.91z M127.04,34.26l-2.09-5.01l-2.13,5.01
      		H127.04z"/>
      	<path d="M144.09,28.58h-4.75V39h-3.27V28.58h-4.75v-3h12.78V28.58z"/>
      	<path d="M156.57,25.59v3h-7.04v2.31h5.88v2.8h-5.88V36h7.04v3H146.3V25.59H156.57z"/>
      	<path d="M176.5,28.29l-2.8,1.42c-0.87-0.85-1.72-1.32-3.25-1.32c-2.35,0-3.98,1.64-3.98,3.86c0,2.13,1.68,3.96,4.02,3.96
      		c1.44,0,2.58-0.45,3.29-1.12v-1.3h-4.08v-2.48h6.94v4.97c-0.1,0.2-2.21,2.96-6.13,2.96c-4.44,0-7.3-3.33-7.3-6.94
      		c0-3.73,3.06-6.94,7.28-6.94C173.07,25.35,175.16,26.51,176.5,28.29z"/>
      	<path d="M188.29,36.91h-6.47L180.93,39h-3.43l5.74-13.41h3.61L192.61,39h-3.45L188.29,36.91z M187.17,34.26l-2.09-5.01l-2.13,5.01
      		H187.17z"/>
      	<path d="M211.32,39h-3.25v-6.84L204.85,39h-3.61l-3.21-6.84V39h-3.23V25.59h3.59l4.67,9.84l4.65-9.84h3.61V39z"/>
      	<path d="M224.56,25.59v3h-7.04v2.31h5.88v2.8h-5.88V36h7.04v3h-10.27V25.59H224.56z"/>
      </g>
      </svg>
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

<div style="position:absolute; top:50px; right:10px; width:25%; bottom: 30px;
background-color:rgba(74, 74, 74, 0.8);
border: 2px solid black;
border-radius: 3px;">

<h1 style="width:100%; text-align:center; color:rgb(220, 220, 220);">Open Beta Test</h1>
<h3 style="width:100%; text-align:center; color:rgb(220, 220, 220);">All cards supported except:</h3>
  Brand with Cinderclaw<br>
  Thaw<br>
  Succumb to Winter<br>
  Conduit of Frostburn<br>
  Channel the Bleak Expanse<br>
  Rewind<br>
  Erase Face<br>
  Quell Mechanic<br>
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
