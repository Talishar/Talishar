<?php

include "../Libraries/HTTPLibraries.php";
include_once '../includes/dbh.inc.php';
include_once "CardEditorDatabase.php";

session_start();

if (!isset($_SESSION["useruid"])) {
  echo ("Please login to view this page.");
  exit;
}
$useruid = $_SESSION["useruid"];
if ($useruid != "OotTheMonk" && $useruid != "Launch" && $useruid != "LaustinSpayce" && $useruid != "Star_Seraph" && $useruid != "Tower") {
  echo ("You must log in to use this page.");
  exit;
}
$setToEdit = TryGET("setToEdit", "");

echo("<h1>Editing Set $setToEdit</h1>");
echo("<table style='width:100%;'><tr>");

echo("<td style='width:50%;'>");
echo("<h2>Card List</h2>");
$cards = LoadCardsForSet($setToEdit);
foreach($cards as $card) {
    echo $card->cardID . ", " . ($card->hasGoAgain ? "has go again" : "no go again") . "<br>";
}
echo("</td>");


echo("<td style='width:50%;'>");
echo("<h2>Add/Edit Card</h2>");
echo("test");
echo("</td>");

echo("</tr></table>");
?>
