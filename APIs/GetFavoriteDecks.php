<?php

include "../AccountFiles/AccountSessionAPI.php";
include_once '../includes/functions.inc.php';
include_once "../includes/dbh.inc.php";
include_once "../Libraries/PlayerSettings.php";
include_once "../Libraries/HTTPLibraries.php";

SetHeaders();

$response = new stdClass();
$response->favoriteDecks = [];
if (IsUserLoggedIn()) {
  $savedSettings = LoadSavedSettings(LoggedInUser());
  $settingArray = [];
  for ($i = 0; $i < count($savedSettings); $i += 2) {
    $settingArray[$savedSettings[intval($i)]] = $savedSettings[intval($i) + 1];
  }

  $favoriteDecks = LoadFavoriteDecks(LoggedInUser());
  if (count($favoriteDecks) > 0) {
    $selIndex = -1;
    if (isset($settingArray[$SET_FavoriteDeckIndex])) $selIndex = $settingArray[$SET_FavoriteDeckIndex];
    $response->lastUsedDeckIndex = $selIndex;
    for ($i = 0; $i < count($favoriteDecks); $i += 3) {
      $deck = new stdClass();
      $deck->index = $i;
      $deck->key = $i . "<fav>" . $favoriteDecks[$i];
      $deck->name = $favoriteDecks[$i + 1];
      array_push($response->favoriteDecks, $deck);
    }
  }
}
echo json_encode($response);
