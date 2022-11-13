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
