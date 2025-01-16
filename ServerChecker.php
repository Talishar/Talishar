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

$reactFE = "https://fe.talishar.net/game/play";

echo ("<div class='SpectatorContainer'>");
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
