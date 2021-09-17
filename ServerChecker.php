<?php

include "HostFiles/Redirector.php";

define('ROOTPATH', __DIR__);

$path = ROOTPATH . "/Games";

$currentlyActiveGames = "";
$spectateLinks = "";

echo("<h1 style='width:100%; text-align:center; color:rgb(240, 240, 240);'>Open Games</h1>");
if ($handle = opendir($path)) {
    while (false !== ($folder = readdir($handle))) {
        if ('.' === $folder) continue;
        if ('..' === $folder) continue;
        $gameToken = $folder;
        $folder = $path . "/" . $folder . "/";
        $gs = $folder . "gamestate.txt";
        if(file_exists($gs))
        {
          $lastGamestateUpdate = filemtime($gs);
          if(time() - $lastGamestateUpdate < 60)
          {
            $currentlyActiveGames .= "Game in Progress - Last Update " . date("h:i", $lastGamestateUpdate) . "<BR>";
            //echo($folder . " Watch?<br>");

       $spectateLinks .= "<form action='" . $redirectPath . "/NextTurn.php'>";
         $spectateLinks .= "<label for='joinGame'> In progress game - Last Update " . date("h:i", $lastGamestateUpdate) . " </label>";
         $spectateLinks .= "<input type='submit' id='joinGame' value='Spectate' />";
         $spectateLinks .= "<input type='hidden' name='gameName' value='$gameToken' />";
         $spectateLinks .= "<input type='hidden' name='playerID' value='3' />";
       $spectateLinks .= "</form>";
          }
          continue;
        }

        $gf = $folder . "GameFile.txt";
        $lineCount = 0;
        if(file_exists($gf))
        {
          $lastRefresh = filemtime($gf);
          if(time() - $lastRefresh < 3)
          {
            $gameFile = fopen($gf, "r");
            while (($buffer = fgets($gameFile, 4096)) !== false) {
              ++$lineCount;
              if($lineCount >= 2) break;
            }
          }
          else if(time() - $lastRefresh > 60)
          {
            unlink($gf);
            unlink($folder . "p1Deck.txt");
            rmdir($folder);
          }
        }

      if($lineCount == 1)
      {
       echo("<form action='" . $redirectPath . "/JoinGame.php'>");
         echo("<label for='joinGame'>Open Game </label>");
         echo("<input type='submit' id='joinGame' value='Join Game' />");
         echo("<input type='hidden' name='gameName' value='$gameToken' />");
         echo("<input type='hidden' name='playerID' value='2' />");
       echo ("</form>");
      }
       
    }
    closedir($handle);
}
  echo("<h1 style='width:100%; text-align:center; color:rgb(240, 240, 240);'>In Progress Games</h1>");
  echo($spectateLinks);

?>