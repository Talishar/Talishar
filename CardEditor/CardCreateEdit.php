<?php

include_once '../includes/dbh.inc.php';
include_once "CardEditorDatabase.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $cardId = $_POST['cardId'];
  $hasGoAgain = $_POST['hasGoAgain'];
  $playAbility = $_POST['playAbility'];
  $hasGoAgain = ($hasGoAgain == "false" ? 0 : 1);
  echo($cardId . " " . $hasGoAgain . " " . $playAbility);
  CreateEditCard($cardId, $hasGoAgain);

  // Do something with the retrieved form data here
  // For example, you could insert it into a database or send it in an email
}
?>
