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
          $lastGamestateUpdate = intval(GetCachePiece($gameToken, 2));
          if($currentTime - $lastGamestateUpdate < 12000)
          {
            $spectateLinks .= "<form action='" . $redirectPath . "/NextTurn3.php'>";
            $spectateLinks .= "<label for='joinGame'>Last Update " . ($currentTime - $lastGamestateUpdate) . " seconds ago </label>";
            $spectateLinks .= "<input type='submit' style='font-size:20px;' id='joinGame' value='Spectate' />";
            $spectateLinks .= "<input type='hidden' name='gameName' value='$gameToken' />";
            $spectateLinks .= "<input type='hidden' name='playerID' value='3' />";
            $spectateLinks .= "</form>";
          }
          else if(time() - $lastGamestateUpdate > 360000)//1 hour
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
          $lastRefresh = intval(GetCachePiece($gameName, 2));//filemtime($gf);
          if($lastRefresh != "" && $currentTime - $lastRefresh < 500)
          {
            //$status = (GetCachePiece($gameName, 5) == "-1" ? 0 : 1);
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
         else if($format == "commoner")
         {
           $commonerLinks .= "<form action='" . $redirectPath . "/JoinGame.php'>";
           $commonerLinks .= "<label for='joinGame'>Open Game </label>";
           $commonerLinks .= "<input type='submit' style='font-size:20px;' id='joinGame' value='Join Game' />";
           $commonerLinks .= "<input type='hidden' name='gameName' value='$gameToken' />";
           $commonerLinks .= "<input type='hidden' name='playerID' value='2' />";
           $commonerLinks .= "</form>";
         }
      }

    }
    closedir($handle);
}
  echo("<h2 style='width:100%; text-align:center; color:rgb(240, 240, 240);'>Blitz</h2>");
  echo($blitzLinks);
  echo("<h2 style='width:100%; text-align:center; color:rgb(240, 240, 240);'>Classic Constructed</h2>");
  echo($ccLinks);
  echo("<h2 style='width:100%; text-align:center; color:rgb(240, 240, 240);'>Commoner</h2>");
  echo($commonerLinks);
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
