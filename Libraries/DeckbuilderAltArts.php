<?php

// A FaBrary deck can pin cards to specific printings. Its deck API returns each
// pin as `imageOverride`, a bare image name such as "MST095-MV", on the pinned
// card (the hero included) and on `tokenImageOverrides` entries for tokens.
// A pin becomes an alt art when the player has unlocked the alt art for that
// card's set number, so pins pick from art the player already has.

// Returns cardId => image name for every pin in a deckbuilder deck response.
function GetDeckbuilderPins($deckObj)
{
  $pins = [];
  $entries = array_merge(
    is_array($deckObj->cards ?? null) ? $deckObj->cards : [],
    is_array($deckObj->tokenImageOverrides ?? null) ? $deckObj->tokenImageOverrides : []
  );
  foreach ($entries as $entry) {
    $identifier = $entry->identifier ?? "";
    $imageOverride = $entry->imageOverride ?? "";
    if (!is_string($identifier) || !is_string($imageOverride)) continue;
    if ($identifier === "" || $imageOverride === "") continue;
    $pins[str_replace("-", "_", $identifier)] = $imageOverride;
  }
  return $pins;
}

// Alt art paths name a set number with a "-T" suffix ("MST095-T"), so a pinned
// image name maps to one by its set number ("MST095-MV" to "MST095-T").
function DeckbuilderPinAltPath($imageName)
{
  if (!preg_match('/^([A-Z]+[0-9]+)/', $imageName, $matches)) return null;
  return $matches[1] . "-T";
}

// Returns cardId => altPath for the pins the player is entitled to. A pin with
// no matching alt art is dropped, and the card keeps the art it would show
// without the pin. $entitlements is GetUserAltArtEntitlements' cardId => [altPath, ...].
function GetEntitledDeckbuilderAltArts($pins, $entitlements)
{
  $altArts = [];
  foreach ($pins as $cardId => $imageName) {
    $altPath = DeckbuilderPinAltPath($imageName);
    if ($altPath === null) continue;
    if (!in_array($altPath, $entitlements[$cardId] ?? [], true)) continue;
    $altArts[$cardId] = $altPath;
  }
  return $altArts;
}

// The alt art entries the pins add, in the shape BuildGameState and the lobby
// send to the frontend. They go after every other entry: the frontend keeps the
// last entry for a card, so a pinned card shows its pin and the rest are unchanged.
function DeckbuilderAltArtEntries($deckAltArts)
{
  $entries = [];
  foreach ((array)$deckAltArts as $cardId => $altPath) {
    $entries[] = ['name' => 'Deckbuilder', 'cardId' => $cardId, 'altPath' => $altPath];
  }
  return $entries;
}
