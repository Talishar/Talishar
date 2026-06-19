<?php

include "../AccountFiles/AccountSessionAPI.php";
include_once '../includes/functions.inc.php';
include_once "../includes/dbh.inc.php";
include_once "../Libraries/PlayerSettings.php";
include_once "../Libraries/HTTPLibraries.php";
require_once '../Assets/patreon-php-master/src/PatreonLibraries.php';
include_once '../Assets/patreon-php-master/src/API.php';
include_once '../Assets/patreon-php-master/src/PatreonDictionary.php';

SetHeaders();

if (!IsUserLoggedIn()) {
  if (isset($_COOKIE["rememberMeToken"])) {
    loginFromCookie();
  }
}

$response = new stdClass();
$response->favoriteDecks = [];
if (IsUserLoggedIn()) {
  $savedSettings = LoadSavedSettings(LoggedInUser());
  $settingArray = [];
  $settingCount = count($savedSettings);
  for ($i = 0; $i < $settingCount; $i += 2) {
    $settingArray[$savedSettings[$i]] = $savedSettings[$i + 1];
  }

  $favoriteDecks = LoadFavoriteDecks(LoggedInUser());
  $favCount = count($favoriteDecks);
  if ($favCount > 0) {
    $selIndex = -1;
    if (isset($settingArray[$SET_FavoriteDeckIndex])) $selIndex = $settingArray[$SET_FavoriteDeckIndex];
    $response->lastUsedDeckIndex = $selIndex;
    for ($i = 0; $i < $favCount; $i += 4) {
      $deck = new stdClass();
      $deck->index = $i;
      $deck->key = $i . "<fav>" . $favoriteDecks[$i];
      $deck->name = $favoriteDecks[$i + 1];
      $deck->hero = $favoriteDecks[$i + 2];
      $deck->format = $favoriteDecks[$i + 3];
      $deck->cardBack = "DEFAULT";
      $deck->playmat = "DEFAULT";
      $deck->link = $favoriteDecks[$i];
      $response->favoriteDecks[] = $deck;
    }
  }

  //Load other settings
  if (isset($settingArray[$SET_Format])) $response->lastFormat = FormatName($settingArray[$SET_Format]);
  if (isset($settingArray[$SET_GameVisibility])) $response->lastVisibility = $settingArray[$SET_GameVisibility];
}
echo json_encode($response);
