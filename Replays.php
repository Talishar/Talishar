<?php
include "HostFiles/Redirector.php";
include_once 'MenuBar.php';
include_once 'APIKeys/APIKeys.php';

if (isset($_SESSION["isPatron"])) $isPatron = $_SESSION["isPatron"];
else $isPatron = false;

if(!$isPatron)
{
  echo("Replay functionality is only available to patrons.");
  exit;
}

$userId = "";
if(isset($_SESSION["userid"])) $userId = $_SESSION["userid"];
if($userId == "")
{
  echo("You must be logged in to use this feature.");
  exit;
}

$path = "./Replays/" . $userId . "/";
if(!file_exists($path))
{
  echo("You have no replays. Play some games and save replays to use this feature!");
  exit;
}

?>
<style>
  body {
    background-image: url('Images/Metrix.jpg');
    background-position: top center;
    background-repeat: no-repeat;
    background-size: cover;
    overflow: hidden;
  }
</style>


<section class="draft-form">
  <h2>Replays</h2>
  <?php

  if ($handle = opendir($path)) {
    while (false !== ($folder = readdir($handle))) {
      if ('.' === $folder) continue;
      if ('..' === $folder) continue;
      if ($folder == "counter.txt") continue;
      $gameToken = $folder;
      $folder = $path . $folder . "/";
      //echo("<a href='./CreateReplayGame.php?replayNumber=$gameToken'>Replay #" . $gameToken . "</a><BR>");
      echo('<div class="draft-form-form">');
      //echo("<a href='./CreateReplayGame.php?replayNumber=$gameToken'>Replay #" . $gameToken . "</a><BR>");
      echo('<form action="CreateReplayGame.php" method="get">');
      echo("<input type='hidden' id='replayNumber' name='replayNumber' value='$gameToken'>");
      echo("<input type='submit' style='font-size:20px;' value='Replay #$gameToken'>");
      echo('</form>');
      echo('</div>');
    }
  }
   ?>
</section>

<?php
include_once 'Disclaimer.php'
?>
