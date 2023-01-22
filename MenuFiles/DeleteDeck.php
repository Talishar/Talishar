<?php

  include_once '../Libraries/HTTPLibraries.php';
  include_once '../Libraries/HTTPLibraries.php';
  include_once "../includes/dbh.inc.php";

  $decklink = TryGet("decklink", "");

  if($decklink == "") { header("Location: ../ProfilePage.php"); exit; }
  session_start();
  if(!isset($_SESSION["userid"])) { header("Location: ../ProfilePage.php"); exit; }
  $sql = "DELETE FROM favoritedeck WHERE decklink=? AND usersId=?";

	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_bind_param($stmt, "ss", $decklink, $_SESSION["userid"]);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
  }

  mysqli_close($conn);
  header("Location: ../ProfilePage.php");
  exit;

?>
