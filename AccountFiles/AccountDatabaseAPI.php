<?php

function LoadUserData($username) {
	$conn = GetLocalMySQLConnection();
  $sql = "SELECT * FROM users WHERE usersUid = ?";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt, $sql)) {
	 	return NULL;
	}
	mysqli_stmt_bind_param($stmt, "s", $username);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
  $row = mysqli_fetch_assoc($result);
	mysqli_stmt_close($stmt);
	mysqli_close($conn);

  return $row;
}


function PasswordLogin($username, $password, $rememberMe) {
	$conn = GetLocalMySQLConnection();
	$userData = LoadUserData($username);

  if($userData == NULL) return false;

  try {
  	$passwordValid = password_verify($password, $userData["usersPwd"]);
  }
  catch (\Exception $e) { }

  if($passwordValid)
  {
    session_start();
		$_SESSION["userid"] = $userData["usersID"];
		$_SESSION["useruid"] = $userData["usersUid"];
		$_SESSION["useremail"] = $userData["usersEmail"];
		$_SESSION["userspwd"] = $userData["usersPwd"];
		$patreonAccessToken = $userData["patreonAccessToken"];
		$_SESSION["patreonEnum"] = $userData["patreonEnum"];

		try {
			PatreonLogin($patreonAccessToken);
		} catch (\Exception $e) { }

		if($rememberMe)
		{
			$cookie = hash("sha256", rand() . $_SESSION["userspwd"] . rand());
			setcookie("rememberMeToken", $cookie, time() + (86400 * 90), "/");
			storeRememberMeCookie($conn, $_SESSION["useruid"], $cookie);
		}
		session_write_close();

		return true;
  }
  return false;
}

function AttemptPasswordLogin($username, $password, $rememberMe) {
	$conn = GetLocalMySQLConnection();
	$userData = LoadUserData($username);

  if($userData != NULL)
  {

  }
  else {
		header("location: ../LoginPage.php");
		exit();
  }


  try {
  	$passwordValid = password_verify($password, $userData["usersPwd"]);
  }
  catch (\Exception $e) { }

  if($passwordValid)
  {
    session_start();
		$_SESSION["userid"] = $userData["usersId"];
		$_SESSION["useruid"] = $userData["usersUid"];
		$_SESSION["useremail"] = $userData["usersEmail"];
		$_SESSION["userspwd"] = $userData["usersPwd"];
		$patreonAccessToken = $userData["patreonAccessToken"];
		$_SESSION["patreonEnum"] = $userData["patreonEnum"];

		try {
			PatreonLogin($patreonAccessToken);
		} catch (\Exception $e) { }

		if($rememberMe)
		{
			$cookie = hash("sha256", rand() . $_SESSION["userspwd"] . rand());
			setcookie("rememberMeToken", $cookie, time() + (86400 * 90), "/");
			storeRememberMeCookie($conn, $_SESSION["useruid"], $cookie);
		}
		session_write_close();

		header("location: ../MainMenu.php");
		exit();
  }
  else {
    header("location: ../LoginPage.php");
    exit();
  }
}

function storeRememberMeCookie($conn, $uuid, $cookie)
{
  $sql = "UPDATE users SET rememberMeToken=? WHERE usersUid=?";
	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $sql)) {
		mysqli_stmt_bind_param($stmt, "ss", $cookie, $uuid);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	}
}

 ?>
