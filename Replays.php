<?php
include "HostFiles/Redirector.php";
include_once 'MenuBar.php';
include_once 'APIKeys/APIKeys.php';
include_once 'includes/MetafyHelper.php';
include_once 'Libraries/NetworkingLibraries.php';

$userId = "";
if(isset($_SESSION["userid"])) $userId = $_SESSION["userid"];
if($userId == "")
{
  echo("You must be logged in to use this feature.");
  exit;
}

if (isset($_SESSION["isPatron"])) $isPatron = $_SESSION["isPatron"];
else $isPatron = false;

if(!$isPatron && $_SESSION["useruid"] != "OotTheMonk")
{
  echo("Replay functionality is only available to patrons.");
  exit;
}

$metafyTiers = GetMetafyTiersFromDatabase($_SESSION["useruid"] ?? "");
$maxReplaySlots = GetMaxReplaySlotsForTiers($metafyTiers);

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
  <p>You can save up to <?php echo $maxReplaySlots; ?> replays. Higher Metafy support tiers unlock more save slots &mdash; check the <a href="/premium">Premium</a> page for details.</p>
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
