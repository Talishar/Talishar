<?php
  include "HostFiles/Redirector.php";
  include_once 'Header.php';
?>

<?php
  echo("<form style='position:absolute; top:15%; width:100%;display:inline-block;' action='" . $redirectPath . "/DraftFiles/CreateGame.php'>");
?>
  <input type="hidden" id="numPlayers" name="numPlayers" value="8" />
  <div style="text-align:center;"><input type="submit" style="font-size:20px;" value="Tales of Aria Solo Draft Practice"></div>
</form>
<?php
  echo("<form style='position:absolute; top:30%; width:100%;display:inline-block;' action='" . $redirectPath . "/WTRDraftFiles/CreateGame.php'>");
?>
  <input type="hidden" id="numPlayers" name="numPlayers" value="8" />
  <div style="text-align:center;"><input type="submit" style="font-size:20px;" value="WTR Solo Draft Practice"></div>
</form>

<?php
  include_once 'Footer.php'
?>
