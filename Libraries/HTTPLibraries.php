<?php

function TryGET($key, $default = "")
{
  return isset($_GET[$key]) ? $_GET[$key] : $default;
}

function IsGameNameValid($gameName)
{
  return is_numeric($gameName);
}

function IsDeckLinkValid($deckLink)
{
  return true;
  // Valid format: https://fabdb.net/decks/vyDDnODb
  /*
  if(filter_var($deckLink, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED) && stristr($deckLink, "fabdb.net/decks/") != false){
    return true;
  }
  return false;
  */
}


function GetGameCounter()
{
  $gcFile = fopen("HostFiles/GameIDCounter.txt", "r+");
  $attemptCount = 0;
  while (!flock($gcFile, LOCK_EX) && $attemptCount < 30) {  // acquire an exclusive lock
    sleep(1);
    ++$attemptCount;
  }
  if ($attemptCount == 30) {
    header("Location: " . $redirectorPath . "MainMenu.php"); //We never actually got the lock
  }
  $counter = intval(fgets($gcFile));
  //$gameName = hash("sha256", $counter);
  $gameName = $counter;
  ftruncate($gcFile, 0);
  rewind($gcFile);
  fwrite($gcFile, $counter + 1);
  flock($gcFile, LOCK_UN);    // release the lock
  fclose($gcFile);
  return $gameName;
}

function IsReplay()
{
  global $gameName;
  return (GetCachePiece($gameName, 10) == "1" ? true : false);
}

function SetHeaders()
{
  // array holding allowed Origin domains
  $allowedOrigins = array(
    "[0-9a-z]*\.talishar\.net",
  );

  if (isset($_SERVER['HTTP_ORIGIN']) && $_SERVER['HTTP_ORIGIN'] != '') {
    foreach ($allowedOrigins as $allowedOrigin) {
      if (preg_match('#' . $allowedOrigin . '#', $_SERVER['HTTP_ORIGIN'])) {
        header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
        header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
        header('Access-Control-Max-Age: 1000');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
        break;
      }
    }
  }
}

function isMobile() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}
