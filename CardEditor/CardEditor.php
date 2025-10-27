<?php

include "../Libraries/HTTPLibraries.php";
include_once '../includes/dbh.inc.php';
include_once '../includes/ModeratorList.inc.php';
include_once "CardEditorDatabase.php";

session_start();

if (!isset($_SESSION["useruid"])) {
  echo ("Please login to view this page.");
  exit;
}
$useruid = $_SESSION["useruid"];
if (!IsCardEditor($useruid)) {
  echo ("You must log in to use this page.");
  exit;
}
$setToEdit = TryGET("setToEdit", "");

echo("<h1>Editing Set $setToEdit</h1>");
echo("<a href='./RecoverDatabase.php' target='_blank'>Initial Database Setup</a>&nbsp;");
echo("<a href='./DatabaseCardCodeGenerator.php' target='_blank'>Generate Code</a>");
echo("<br><br>");
echo("<table style='width:100%;'><tr>");

echo("<td style='width:50%;'>");
echo("<h2>Card List</h2>");
$cards = LoadDatabaseCards($setToEdit);
foreach($cards as $card) {
    echo $card->cardID . ", " . ($card->hasGoAgain ? "has go again" : "no go again") . "<br>";
}
echo("</td>");


echo("<td style='width:50%;'>");
echo("<h2>Add/Edit Card</h2>");
?>

<form action="CardCreateEdit.php" method="post">
  <label for="cardId">Card ID:</label>
  <input type="text" id="cardId" name="cardId"><br><br>

  <label for="hasGoAgain">Has Go Again:</label>
  <select id="hasGoAgain" name="hasGoAgain">
    <option value="true" selected>True</option>
    <option value="false">False</option>
  </select><br><br>

  <label for="playAbility">Play Ability:</label>
  <textarea id="playAbility" name="playAbility" rows="4" cols="50"></textarea><br><br>

  <button type="submit">Submit</button>
</form>

<?php
echo("</td>");

echo("</tr></table>");
?>
