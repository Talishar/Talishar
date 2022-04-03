<?php

include "Libraries/SHMOPLibraries.php";
include "HostFiles/Redirector.php";

define('ROOTPATH', __DIR__);

$path = ROOTPATH . "/Games";

$currentlyActiveGames = "";
$spectateLinks = "";
$blitzLinks = "";
$ccLinks = "";

echo("<h1 style='width:100%; text-align:center; color:rgb(240, 240, 240);'>Public Games</h1>");
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
          if(time() - $lastGamestateUpdate < 120)
          {
            $spectateLinks .= "<form action='" . $redirectPath . "/NextTurn3.php'>";
            $spectateLinks .= "<label for='joinGame'>Last Update " . time() - $lastGamestateUpdate . " seconds ago </label>";
            $spectateLinks .= "<input type='submit' style='font-size:20px;' id='joinGame' value='Spectate' />";
            $spectateLinks .= "<input type='hidden' name='gameName' value='$gameToken' />";
            $spectateLinks .= "<input type='hidden' name='playerID' value='3' />";
            $spectateLinks .= "</form>";
          }
          else if(time() - $lastGamestateUpdate > 3600)//1 hour
          {
            if($autoDeleteGames) deleteDirectory($folder);
          }
          continue;
        }

        $gf = $folder . "GameFile.txt";
        $gameName = $gameToken;
        $lineCount = 0;
        $status = -1;
        if(file_exists($gf))
        {
          $lastRefresh = filemtime($gf);
          if(time() - $lastRefresh < 5)
          {
            include 'MenuFiles/ParseGamefile.php';
            $status = $gameStatus;
            UnlockGamefile();
          }
          else if(time() - $lastRefresh > 10800)//3 hours
          {
            deleteDirectory($folder);
          }
        }

      if($status == 0 && $visibility == "public")
      {
        if($format == "blitz")
        {
          $blitzLinks .= "<form action='" . $redirectPath . "/JoinGame.php'>";
          $blitzLinks .= "<label for='joinGame'>Open Game </label>";
          $blitzLinks .= "<input type='submit' style='font-size:20px;' id='joinGame' value='Join Game' />";
          $blitzLinks .= "<input type='hidden' name='gameName' value='$gameToken' />";
          $blitzLinks .= "<input type='hidden' name='playerID' value='2' />";
          $blitzLinks .= "</form>";
        }
        else if($format == "cc")
        {
          $ccLinks .= "<form action='" . $redirectPath . "/JoinGame.php'>";
          $ccLinks .= "<label for='joinGame'>Open Game </label>";
          $ccLinks .= "<input type='submit' style='font-size:20px;' id='joinGame' value='Join Game' />";
          $ccLinks .= "<input type='hidden' name='gameName' value='$gameToken' />";
          $ccLinks .= "<input type='hidden' name='playerID' value='2' />";
          $ccLinks .= "</form>";
         }
      }

    }
    closedir($handle);
}
  echo("<h2 style='width:100%; text-align:center; color:rgb(240, 240, 240);'>Blitz</h2>");
  echo($blitzLinks);
  echo("<h2 style='width:100%; text-align:center; color:rgb(240, 240, 240);'>Classic Constructed</h2>");
  echo($ccLinks);
  echo("<h1 style='width:100%; text-align:center; color:rgb(240, 240, 240);'>In Progress Games</h1>");
  echo($spectateLinks);


function deleteDirectory($dir) {
    if (!file_exists($dir)) {
        return true;
    }

    if (!is_dir($dir)) {
        return unlink($dir);
    }

    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }

        if (!deleteDirectory($dir . "/" . $item)) {
            return false;
        }

    }

    return rmdir($dir);
}

?>
