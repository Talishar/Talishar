<?php

  include_once "../AccountFiles/AccountSessionAPI.php";
  include_once "../includes/dbh.inc.php";

  $decklink = TryGet("decklink", "");

  if(IsUserLoggedIn())
  {
    $sql = "DELETE FROM favoritedeck WHERE decklink=? AND usersId=?";

    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $sql)) {
      mysqli_stmt_bind_param($stmt, "ss", $decklink, LoggedInUser());
      mysqli_stmt_execute($stmt);
      mysqli_stmt_close($stmt);
    }

    mysqli_close($conn);
  }

  $response = new stdClass();
  $response->message = "Deck deleted successfully.";
  echo(json_encode($response));
  
  exit;

?>
