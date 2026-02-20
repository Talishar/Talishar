<?php

include_once 'MenuBar.php';
include "HostFiles/Redirector.php";
include_once "Libraries/PlayerSettings.php";
include_once 'Assets/patreon-php-master/src/PatreonDictionary.php';
include_once "APIKeys/APIKeys.php";

if (!empty($_SESSION['error'])) {
  $error = $_SESSION['error'];
  unset($_SESSION['error']);
  echo "<script>alert('" . $error . "')</script>";
}

$language = TryGet("language", 1);
$settingArray = [];
$defaultFormat = 0;
$defaultVisibility = (isset($_SESSION["useruid"]) ? 1 : 0);
if (isset($_SESSION["userid"])) {
  $savedSettings = LoadSavedSettings($_SESSION["userid"]);
  for ($i = 0; $i < count($savedSettings); $i += 2) {
    $settingArray[$savedSettings[intval($i)]] = $savedSettings[intval($i) + 1];
  }
  if (isset($_GET['language'])) {
    ChangeSetting("", $SET_Language, $language, $_SESSION["userid"]);
  } else if (isset($settingArray[$SET_Language])) $language = $settingArray[$SET_Language];
  if (isset($settingArray[$SET_Format])) $defaultFormat = $settingArray[$SET_Format];
  if (isset($settingArray[$SET_GameVisibility])) $defaultVisibility = $settingArray[$SET_GameVisibility];
}
$_SESSION['language'] = $language;
$isPatron = (isset($_SESSION["isPatron"])) ? $_SESSION["isPatron"] : false;

$canSeeQueue = isset($_SESSION["useruid"]);
?>

<h2 style='width:60%; color: orange; margin: auto;'>
  This is the Legacy UI. It is no longer supported. Please download the <b><a href="https://github.com/Talishar/Talishar-FE">Talishar Front End</a></b> and follow the README. Failure to do so will result in errors.
</h2>
