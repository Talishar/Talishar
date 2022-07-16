<?php

include "Libraries/SHMOPLibraries.php";
include "HostFiles/Redirector.php";

define('ROOTPATH', __DIR__);

$path = ROOTPATH . "/Games";

$currentlyActiveGames = "";
$spectateLinks = "";
$blitzLinks = "";
$ccLinks = "";
$commonerLinks = "";

echo("<h1 style='width:100%; text-align:center; color:rgb(240, 240, 240);'>Public Games</h1>");
if ($handle = opendir($path)) {
    while (false !== ($folder = readdir($handle))) {
        if ('.' === $folder) continue;
        if ('..' === $folder) continue;
        $gameToken = $folder;
        $folder = $path . "/" . $folder . "/";
        $gs = $folder . "gamestate.txt";
        $currentTime = round(microtime(true) * 1000);
        if(file_exists($gs))
        {
          $lastGamestateUpdate = intval(GetCachePiece($gameToken, 6));
          if($currentTime - $lastGamestateUpdate < 30000)
          {
            $spectateLinks .= "<form style='text-align:center;' action='" . $redirectPath . "/NextTurn3.php'>";
            $spectateLinks .= "<label for='joinGame' style='font-weight:500;'>Last Update " . intval(($currentTime - $lastGamestateUpdate)/1000) . " seconds ago </label>";
            $spectateLinks .= "<input type='submit' style='font-size:16px;' id='joinGame' value='Spectate' />";
            $spectateLinks .= "<input type='hidden' name='gameName' value='$gameToken' />";
            $spectateLinks .= "<input type='hidden' name='playerID' value='3' />";
            $spectateLinks .= "</form>";
          }
          else if($currentTime - $lastGamestateUpdate > 7200000)//10 hours
          {
            if($autoDeleteGames)
            {
              deleteDirectory($folder);
              DeleteCache($gameToken);
            }
          }
          continue;
        }

        $gf = $folder . "GameFile.txt";
        $gameName = $gameToken;
        $lineCount = 0;
        $status = -1;
        if(file_exists($gf))
        {
          $lastRefresh = intval(GetCachePiece($gameName, 2));//Player 1 last connection time
          if($lastRefresh != "" && $currentTime - $lastRefresh < 500)
          {
            include 'MenuFiles/ParseGamefile.php';
            $status = $gameStatus;
            UnlockGamefile();
          }
          else if($lastRefresh == "" || $currentTime - $lastRefresh > 1080000)//3 hours
          {
            deleteDirectory($folder);
            DeleteCache($gameToken);
          }
        }

      if($status == 0 && $visibility == "public")
      {
        if($format == "blitz")
        {
          $blitzLinks .= "<form style='text-align:center;' action='" . $redirectPath . "/JoinGame.php'>";
          $blitzLinks .= "<label for='joinGame' style='font-weight:500;'>Open Game </label>";
          $blitzLinks .= "<input type='submit' style='font-size:16px;' id='joinGame' value='Join Game' />";
          $blitzLinks .= "<input type='hidden' name='gameName' value='$gameToken' />";
          $blitzLinks .= "<input type='hidden' name='playerID' value='2' />";
          $blitzLinks .= "</form>";
        }
        else if($format == "cc")
        {
          $ccLinks .= "<form style='text-align:center;' action='" . $redirectPath . "/JoinGame.php'>";
          $ccLinks .= "<label for='joinGame' style='font-weight:500;'>Open Game </label>";
          $ccLinks .= "<input type='submit' style='font-size:16px;' id='joinGame' value='Join Game' />";
          $ccLinks .= "<input type='hidden' name='gameName' value='$gameToken' />";
          $ccLinks .= "<input type='hidden' name='playerID' value='2' />";
          $ccLinks .= "</form>";
         }
         else if($format == "commoner")
         {
           $commonerLinks .= "<form style='text-align:center;' action='" . $redirectPath . "/JoinGame.php'>";
           $commonerLinks .= "<label for='joinGame' style='font-weight:500;'>Open Game </label>";
           $commonerLinks .= "<input type='submit' style='font-size:16px;' id='joinGame' value='Join Game' />";
           $commonerLinks .= "<input type='hidden' name='gameName' value='$gameToken' />";
           $commonerLinks .= "<input type='hidden' name='playerID' value='2' />";
           $commonerLinks .= "</form>";
         }
      }
    }
    closedir($handle);
}
  echo("<h2 style='width:100%; text-align:center; color:RGB(240,240,240);'>Blitz</h2>");
  echo($blitzLinks);
  echo("<h3 style='text-align:center;'>________</h3>");
  echo("<h2 style='width:100%; text-align:center; color:RGB(240,240,240);'>Classic Constructed</h2>");
  echo($ccLinks);
  echo("<h3 style='text-align:center;'>________</h3>");
  echo("<h2 style='width:100%; text-align:center; color:RGB(240,240,240);'>Commoner</h2>");
  echo($commonerLinks);
  echo("<h3 style='text-align:center;'>________</h3>");
  echo("<h2 style='width:100%; text-align:center; color:RGB(240,240,240);'>Games In Progress</h2>");
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
