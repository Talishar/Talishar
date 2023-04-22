<?php

include_once '../includes/dbh.inc.php';
include_once "CardEditorDatabase.php";

$set = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $cardId = $_POST['cardId'];
  $set = substr($cardId, 0, 3);
  $hasGoAgain = $_POST['hasGoAgain'];
  $playAbility = $_POST['playAbility'];
  $hasGoAgain = ($hasGoAgain == "false" ? 0 : 1);
  if($cardId != "") CreateEditCard($cardId, $hasGoAgain);

  // Do something with the retrieved form data here
  // For example, you could insert it into a database or send it in an email
}

$params = $set != "" ? "?setToEdit=" . $set : "";
header("location: ./CardEditor.php" . $params);

?>
