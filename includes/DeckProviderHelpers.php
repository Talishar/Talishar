<?php

function IsFaBBazaarHostLink($deckLink)
{
  if (!is_string($deckLink) || $deckLink === '') return false;

  $favoriteMarker = strpos($deckLink, '<fav>');
  if ($favoriteMarker !== false) {
    $deckLink = substr($deckLink, $favoriteMarker + strlen('<fav>'));
  }

  $host = parse_url(trim($deckLink), PHP_URL_HOST);
  if (!is_string($host)) return false;

  $host = strtolower($host);
  return $host === 'fabbazaar.app' || $host === 'www.fabbazaar.app';
}

function ExtractFaBBazaarDeckId($deckLink)
{
  if (!IsFaBBazaarHostLink($deckLink)) return '';

  $favoriteMarker = strpos($deckLink, '<fav>');
  if ($favoriteMarker !== false) {
    $deckLink = substr($deckLink, $favoriteMarker + strlen('<fav>'));
  }

  $path = parse_url(trim($deckLink), PHP_URL_PATH);
  if (!is_string($path)) return '';

  return preg_match('~^/decks/([a-zA-Z0-9_-]+)/?$~', $path, $matches)
    ? $matches[1]
    : '';
}

function IsFaBBazaarDeckLink($deckLink)
{
  return ExtractFaBBazaarDeckId($deckLink) !== '';
}

?>
