<?php

include_once '../Libraries/HTTPLibraries.php';
include_once "../AccountFiles/AccountSessionAPI.php";
include_once "../includes/dbh.inc.php";

SetHeaders();

$_POST = json_decode(file_get_contents('php://input'), true);
$decklink = isset($_POST["deckLink"]) ? $_POST["deckLink"] : "";

if (IsUserLoggedIn() && $decklink != "") {
  $sql = "DELETE FROM favoritedeck WHERE decklink=? AND usersId=?";

  $conn = GetDBConnection();
  if (!$conn) {
    http_response_code(500);
    exit('Database connection failed');
  }
  $stmt = mysqli_stmt_init($conn);
  if (mysqli_stmt_prepare($stmt, $sql)) {
    $userID = LoggedInUser();
    mysqli_stmt_bind_param($stmt, "ss", $decklink, $userID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
  }

  mysqli_close($conn);

  $response = new stdClass();
  $response->message = "Deck deleted successfully.";
  echo (json_encode($response));
}

exit;
