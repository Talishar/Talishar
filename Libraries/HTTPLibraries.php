<?php

function TryGET($key, $default="")
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

?>
