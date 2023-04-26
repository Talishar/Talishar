<?php

include "Libraries/SHMOPLibraries.php";
include "HostFiles/Redirector.php";
include "CardDictionary.php";
include_once 'MenuBar.php';
include_once "./AccountFiles/AccountDatabaseAPI.php";
include_once './includes/functions.inc.php';
include_once './includes/dbh.inc.php';

define('ROOTPATH', __DIR__);

$path = ROOTPATH . "/Games";

$currentlyActiveGames = "";
$spectateLinks = "";
$blitzLinks = "";
$compBlitzLinks = "";
$ccLinks = "";
$compCCLinks = "";
$otherFormatsLinks = "";
$livingLegendsCCLinks = "";
// TODO: Have as a global variable.
$reactFE = "https://fe.talishar.net/game/play";

$isShadowBanned = false;
if(isset($_SESSION["isBanned"])) $isShadowBanned = (intval($_SESSION["isBanned"]) == 1 ? true : false);
else if(isset($_SESSION["useruid"])) $isShadowBanned = IsBanned($_SESSION["useruid"]);

$canSeeQueue = isset($_SESSION["useruid"]);

echo ("<div class='SpectatorContainer'>");
echo ("<h1 style='width:100%; text-align:center; color:rgb(240, 240, 240);'>Public Games</h1>");
$gameInProgressCount = 0;
if ($handle = opendir($path)) {
  while (false !== ($folder = readdir($handle))) {
    if ('.' === $folder) continue;
    if ('..' === $folder) continue;
    $gameToken = $folder;
    $folder = $path . "/" . $folder . "/";
    $gs = $folder . "gamestate.txt";
    $currentTime = round(microtime(true) * 1000);
    if (file_exists($gs)) {
      $lastGamestateUpdate = intval(GetCachePiece($gameToken, 6));
      if ($currentTime - $lastGamestateUpdate < 30000) {
        $p1Hero = GetCachePiece($gameToken, 7);
        $p2Hero = GetCachePiece($gameToken, 8);
        if($p2Hero != "") $gameInProgressCount += 1;
        $visibility = GetCachePiece($gameToken, 9);
        if ($p2Hero != "" && $visibility == "1") {
          $spectateLinks .= "<form style='text-align:center;' action='" . $reactFE . "'>";
          $spectateLinks .= "<center><table><tr><td style='vertical-align:middle; padding-left:8px; width:50px; height: 40px;'>";
          if ($p1Hero == "") {
            $spectateLinks .= "<label for='joinGame' style='font-weight:500;'>Last Update " . intval(($currentTime - $lastGamestateUpdate) / 1000) . " seconds ago </label>";
          } else {
            $spectateLinks .= "<img height='40px;' style='max-width:50px;' src='./crops/" . $p1Hero . "_cropped.png' />";
            $spectateLinks .= "</td><td style='vertical-align:middle;'>";
            $spectateLinks .= "vs";
            $spectateLinks .= "</td><td style='width:50px; height: 40px; vertical-align:middle; padding-left:8px;'>";
            $spectateLinks .= "<img height='40px;' style='max-width:50px;' src='./crops/" . $p2Hero . "_cropped.png' />";
            $spectateLinks .= "</td><td style='vertical-align:middle;'>";
          }
          $spectateLinks .= "<input class='ServerChecker_Button' type='submit' style='font-size:16px;' id='joinGame' value='Spectate' />";
          $spectateLinks .= "</td></tr></table></center>";
          $spectateLinks .= "<input type='hidden' name='gameName' value='$gameToken' />";
          $spectateLinks .= "<input type='hidden' name='playerID' value='3' />";
          $spectateLinks .= "</form>";
        }
      } else if ($currentTime - $lastGamestateUpdate > 900000) //~1 hour
      {
        if ($autoDeleteGames) {
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
    if (file_exists($gf)) {
      $lastRefresh = intval(GetCachePiece($gameName, 2)); //Player 1 last connection time
      if ($lastRefresh != "" && $currentTime - $lastRefresh < 500) {
        include 'MenuFiles/ParseGamefile.php';
        $status = $gameStatus;
        UnlockGamefile();
      } else if ($lastRefresh == "" || $currentTime - $lastRefresh > 900000) //1 hour
      {
        deleteDirectory($folder);
        DeleteCache($gameToken);
      }
    }

    if ($status == 0 && $visibility == "public" && intval(GetCachePiece($gameName, 11)) < 3) {
      $p1Hero = GetCachePiece($gameName, 7);
      $formatName = "";
      if ($format == "commoner") $formatName = "Commoner ";
      else if ($format == "livinglegendscc") $formatName = "Open Format ";
      else if ($format == "clash") $formatName = "Clash";

      $link = "<form style='text-align:center;' action='" . $redirectPath . "/JoinGame.php'>";
      $link .= "<center><table style='left:40%;'><tr><td style='vertical-align:middle;'>";
      if ($formatName != "") $link .= $formatName . "&nbsp;</td><td>";
      $link .= "</td><td style='vertical-align:middle;'>";
      $description = ($gameDescription == "" ? "Game #" . $gameName : $gameDescription);
      $link .= "<span style='font-weight:500; pointer:default;'> &nbsp;" . $description . " </span>";
      $link .= "<input class='ServerChecker_Button' type='submit' style='font-size:16px;' id='joinGame' value='Join Game' />";
      $link .= "</td></tr></table></center>";
      $link .= "<input type='hidden' name='gameName' value='$gameToken' />";
      $link .= "<input type='hidden' name='playerID' value='2' />";
      $link .= "</form>";
      if(!$isShadowBanned) {
        switch($format) {
          case "blitz": $blitzLinks .= $link; break;
          case "compblitz": $compBlitzLinks .= $link; break;
          case "cc": $ccLinks .= $link; break;
          case "compcc": $compCCLinks .= $link; break;
          default:
            if($format != "shadowblitz" && $format != "shadowcc") $otherFormatsLinks .= $link;
            break;
        }
      }
      else {
        if($format == "shadowblitz") $blitzLinks .= $link;
        else if($format == "shadowcc") $ccLinks .= $link;
      }
    }
  }
  closedir($handle);
}
if ($canSeeQueue) {
  echo ("<h3 style='width:100%; text-align:center; color:RGB(240,240,240);'>Blitz</h3>");
  echo ($blitzLinks);
  echo ("<h4 style='text-align:center;'>______________________</h4>");
  echo ("<h3 style='width:100%; text-align:center; color:RGB(240,240,240);'>Competitive Blitz</h3>");
  echo ($compBlitzLinks);
  echo ("<h4 style='text-align:center;'>______________________</h4>");
  echo ("<h3 style='width:100%; text-align:center; color:RGB(240,240,240);'>Classic Constructed</h3>");
  echo ($ccLinks);
  echo ("<h4 style='text-align:center;'>______________________</h4>");
  echo ("<h3 title='This game mode is intended for training for high level regional and national events.' style='cursor:default; width:100%; text-align:center;'>Competitive CC</h3>");
  echo ($compCCLinks);
  echo ("<h4 style='text-align:center;'>______________________</h4>");
  echo ("<h3 style='width:100%; text-align:center; color:RGB(240,240,240);'>Other Formats</h3>");
  echo ($otherFormatsLinks);
}
if (!$canSeeQueue) {
  echo ("<BR>");
  echo ("<div><b>&#10071;Log in to use matchmaking and see open matches</b></div><br>");
}
echo ("<h4 style='text-align:center;'>______________________</h4>");
echo ("<h3 style='width:100%; text-align:center; color:RGB(240,240,240);'>Games In Progress ($gameInProgressCount)</h3>");
if (!IsMobile()) {
  echo ($spectateLinks);
}
echo ("</div>");

function deleteDirectory($dir)
{
  if (!file_exists($dir)) {
    return true;
  }

  if (!is_dir($dir)) {
    $handler = fopen($dir, "w");
    fwrite($handler, "");
    fclose($handler);
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
