<?php

function LoadUserData($username) {
	try {
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
	}
	catch (\Exception $e) { }

  return $row;
}


function PasswordLogin($username, $password, $rememberMe) {
	$conn = GetLocalMySQLConnection();
	try {
		$userData = LoadUserData($username);
	}
	catch (\Exception $e) { }

  if($userData == NULL) return false;

  try {
  	$passwordValid = password_verify($password, $userData["usersPwd"]);
  }
  catch (\Exception $e) { }

  if($passwordValid)
  {
    session_start();
    session_regenerate_id(true); // Regenerate session ID on login
		$_SESSION["userid"] = $userData["usersId"];
		$_SESSION["useruid"] = $userData["usersUid"];
		$_SESSION["useremail"] = $userData["usersEmail"];
		// Remove password from session for security
		$patreonAccessToken = $userData["patreonAccessToken"];
		$_SESSION["patreonEnum"] = $userData["patreonEnum"];
		$_SESSION["isBanned"] = $userData["isBanned"];
		$_SESSION["metafyID"] = $userData["metafyID"] ?? "";

		try {
			PatreonLogin($patreonAccessToken);
		} catch (\Exception $e) { }

		if($rememberMe)
		{
			ApplyRememberMeCookie($userData["usersId"]);
		}
		session_write_close();

		return true;
  }
  return false;
}

function IsBanned($username)
{
	$userData = LoadUserData($username);
	if (!isset($userData)) return false;
	$_SESSION["isBanned"] = $userData["isBanned"];
	return (intval($userData["isBanned"]) == 1 ? true : false);
}

function ApplyRememberMeCookie($usersId)
{
  $conn = GetLocalMySQLConnection();
  $sql = "SELECT rememberMeToken FROM users WHERE usersId=?";
  $stmt = mysqli_stmt_init($conn);
  $cookie = null;
  if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_bind_param($stmt, "s", $usersId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    $cookie = $row["rememberMeToken"] ?? null;
  }

  if (empty($cookie)) {
    $cookie = bin2hex(random_bytes(32));
    $sql2 = "UPDATE users SET rememberMeToken=? WHERE usersId=?";
    $stmt2 = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt2, $sql2)) {
      mysqli_stmt_bind_param($stmt2, "ss", $cookie, $usersId);
      mysqli_stmt_execute($stmt2);
      mysqli_stmt_close($stmt2);
    }
  }

  mysqli_close($conn);
  setcookie("rememberMeToken", $cookie, time() + (86400 * 90), "/", "", true, true); // Secure and HttpOnly
}

 ?>
