<?php
function TryGET($key, $default = "")
{
  return isset($_GET[$key]) ? $_GET[$key] : $default;
}

function TryPOST($key, $default = "")
{
  return isset($_POST[$key]) ? $_POST[$key] : $default;
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


function GetGameCounter($path = "./")
{
  global $redirectPath;
  $gameIDCounterFile = $path . "HostFiles/GameIDCounter.txt";

  if (!is_file($gameIDCounterFile)) { // if the game ID counter does not exist, make it.
    $contents = '101';
    file_put_contents($gameIDCounterFile, $contents);
  }

  $gcFile = fopen($gameIDCounterFile, "r+");

  $attemptCount = 0;
  while (!flock($gcFile, LOCK_EX) && $attemptCount < 30) {  // acquire an exclusive lock
    sleep(1);
    ++$attemptCount;
  }
  if ($attemptCount == 30) {
    header("Location: " . $redirectPath . "MainMenu.php"); //We never actually got the lock
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
  // array holding allowed Origin domains (with fixed regex patterns)
  $allowedOrigins = array(
    "~^https?://[0-9a-z\-]*\.talishar\.net$~i",
    "~^https?://talishar\.net$~i",
    "~^https?://[0-9a-z\-]*\.talishar-fe\.pages\.dev$~i",
    "~^https?://talishar\.surge\.sh$~i",
    "~^https?://localhost(:[0-9]+)?$~i",
    "~^https?://127\.0\.0\.1(:[0-9]+)?$~i"
  );

  $originSet = false;
  
  if (isset($_SERVER['HTTP_ORIGIN']) && $_SERVER['HTTP_ORIGIN'] != '') {
    foreach ($allowedOrigins as $allowedOrigin) {
      if (preg_match($allowedOrigin, $_SERVER['HTTP_ORIGIN'])) {
        header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
        $originSet = true;
        break;
      }
    }
  }
  
  // Always set CORS headers to fix Brave browser issues
  // If origin didn't match, use wildcard for requests without proper Origin headers
  if (!$originSet && (!isset($_SERVER['HTTP_ORIGIN']) || $_SERVER['HTTP_ORIGIN'] == '')) {
    header('Access-Control-Allow-Origin: *');
  }
  
  header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
  header('Access-Control-Max-Age: 1000');
  header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
  header('Access-Control-Allow-Credentials: true');
  header('X-Accel-Buffering: no');
}

function isMobile()
{
  $tablet_browser = 0;
  $mobile_browser = 0;

  if (isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
    $tablet_browser++;
  }

  if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
    $mobile_browser++;
  }

  if ((isset($_SERVER['HTTP_ACCEPT']) && strpos(strtolower($_SERVER['HTTP_ACCEPT']), 'application/vnd.wap.xhtml+xml') > 0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
    $mobile_browser++;
  }

  if (isset($_SERVER['HTTP_USER_AGENT'])) {
    $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
    $mobile_agents = array(
      'w3c ', 'acs-', 'alav', 'alca', 'amoi', 'audi', 'avan', 'benq', 'bird', 'blac',
      'blaz', 'brew', 'cell', 'cldc', 'cmd-', 'dang', 'doco', 'eric', 'hipt', 'inno',
      'ipaq', 'java', 'jigs', 'kddi', 'keji', 'leno', 'lg-c', 'lg-d', 'lg-g', 'lge-',
      'maui', 'maxo', 'midp', 'mits', 'mmef', 'mobi', 'mot-', 'moto', 'mwbp', 'nec-',
      'newt', 'noki', 'palm', 'pana', 'pant', 'phil', 'play', 'port', 'prox',
      'qwap', 'sage', 'sams', 'sany', 'sch-', 'sec-', 'send', 'seri', 'sgh-', 'shar',
      'sie-', 'siem', 'smal', 'smar', 'sony', 'sph-', 'symb', 't-mo', 'teli', 'tim-',
      'tosh', 'tsm-', 'upg1', 'upsi', 'vk-v', 'voda', 'wap-', 'wapa', 'wapi', 'wapp',
      'wapr', 'webc', 'winw', 'winw', 'xda ', 'xda-'
    );

    if (in_array($mobile_ua, $mobile_agents)) {
      $mobile_browser++;
    }

    if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'opera mini') > 0) {
      $mobile_browser++;
      //Check for tablets on opera mini alternative headers
      $stock_ua = strtolower(isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA']) ? $_SERVER['HTTP_X_OPERAMINI_PHONE_UA'] : (isset($_SERVER['HTTP_DEVICE_STOCK_UA']) ? $_SERVER['HTTP_DEVICE_STOCK_UA'] : ''));
      if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $stock_ua)) {
        $tablet_browser++;
      }
    }
  }

  if ($tablet_browser > 0) return false;
  return $mobile_browser > 0;
}
