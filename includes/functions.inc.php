<?php

// Check for empty input signup
function emptyInputSignup($username, $email, $pwd, $pwdRepeat) {
	$result;
	if (empty($username) || empty($email) || empty($pwd) || empty($pwdRepeat)) {
		$result = true;
	}
	else {
		$result = false;
	}
	return $result;
}

// Check invalid username
function invalidUid($username) {
	$result;
	if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
		$result = true;
	}
	else {
		$result = false;
	}
	return $result;
}

// Check invalid email
function invalidEmail($email) {
	$result;
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$result = true;
	}
	else {
		$result = false;
	}
	return $result;
}

// Check if passwords matches
function pwdMatch($pwd, $pwdrepeat) {
	$result;
	if ($pwd !== $pwdrepeat) {
		$result = true;
	}
	else {
		$result = false;
	}
	return $result;
}

// Check if username is in database, if so then return data
function uidExists($conn, $username) {
  $sql = "SELECT * FROM users WHERE usersUid = ? OR usersEmail = ?;";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt, $sql)) {
	 	header("location: ../Signup.php?error=stmtfailed");
		exit();
	}

	mysqli_stmt_bind_param($stmt, "ss", $username, $email);
	mysqli_stmt_execute($stmt);

	// "Get result" returns the results from a prepared statement
	$resultData = mysqli_stmt_get_result($stmt);

	if ($row = mysqli_fetch_assoc($resultData)) {
		return $row;
	}
	else {
		$result = false;
		return $result;
	}
	mysqli_stmt_close($stmt);
}

// Check if username is in database, if so then return data
function getUInfo($conn, $username) {
  $sql = "SELECT * FROM users WHERE usersUid = ? OR usersEmail = ?;";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt, $sql)) {
	 	header("location: ../Signup.php?error=stmtfailed");
		exit();
	}

	mysqli_stmt_bind_param($stmt, "ss", $username, $email);
	mysqli_stmt_execute($stmt);

	// "Get result" returns the results from a prepared statement
	$resultData = mysqli_stmt_get_result($stmt);

	if ($row = mysqli_fetch_assoc($resultData)) {
		return $row;
	}
	else {
		$result = false;
		return $result;
	}
	mysqli_stmt_close($stmt);
}

// Insert new user into database
function createUser($conn, $username, $email, $pwd) {
  $sql = "INSERT INTO users (usersUid, usersEmail, usersPwd) VALUES (?, ?, ?);";

	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt, $sql)) {
	 	header("location: ../Signup.php?error=stmtfailed");
		exit();
	}

	$hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

	mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashedPwd);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);
	mysqli_close($conn);
	header("location: ../Signup.php?error=none");
	exit();
}

// Check for empty input login
function emptyInputLogin($username, $pwd) {
	$result;
	if (empty($username) || empty($pwd)) {
		$result = true;
	}
	else {
		$result = false;
	}
	return $result;
}

// Log user into website
function loginUser($conn, $username, $pwd, $rememberMe) {
	$uidExists = uidExists($conn, $username);

	if ($uidExists === false) {
		header("location: ../Login.php?error=wronglogin");
		exit();
	}

	$pwdHashed = $uidExists["usersPwd"];
	$checkPwd = password_verify($pwd, $pwdHashed);

	if ($checkPwd === false) {
		header("location: ../Login.php?error=wronglogin");
		exit();
	}
	elseif ($checkPwd === true) {
		session_start();
		$_SESSION["userid"] = $uidExists["usersID"];
		$_SESSION["useruid"] = $uidExists["usersUid"];
		$_SESSION["useremail"] = $uidExists["usersEmail"];
		$_SESSION["userspwd"] = $uidExists["usersPwd"];

		if($rememberMe)
		{
			$cookie = hash("sha256", rand() . $_SESSION["userspwd"] . rand());
			setcookie("rememberMeToken", $cookie, time() + (86400 * 90), "/");
			storeRememberMeCookie($conn, $_SESSION["useruid"], $cookie);
		}

		header("location: ../MainMenu.php?error=none");
		exit();
	}
}

function loginFromCookie()
{
	$token = $_COOKIE["rememberMeToken"];
	require_once "dbh.inc.php";
	if(!isset($conn)) global $conn;
	$sql = "SELECT usersID, usersUid, usersEmail FROM users WHERE rememberMeToken='$token'";
	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $sql)) {
		mysqli_stmt_execute($stmt);
		$data = mysqli_stmt_get_result($stmt);
		$row = mysqli_fetch_array($data, MYSQLI_NUM);
		mysqli_stmt_close($stmt);
		mysqli_close($conn);
		if($row != null && count($row) > 0)
		{
			$_SESSION["userid"] = $row[0];
			$_SESSION["useruid"] = $row[1];
			$_SESSION["useremail"] = $row[2];
		}
		else {
			unset($_SESSION["userid"]);
			unset($_SESSION["useruid"]);
			unset($_SESSION["useremail"]);
		}
	}
}

function storeRememberMeCookie($conn, $uuid, $cookie)
{
	//global $conn;
  $sql = "UPDATE users SET rememberMeToken='$cookie' WHERE usersUid='$uuid'";
	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $sql)) {
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
		mysqli_close($conn);
	}
}

function addFavoriteDeck($userID, $decklink, $deckName, $heroID)
{
	require_once "dbh.inc.php";
	$values = "'" . $decklink . "'," . $userID . ",'" . $deckName . "','" . $heroID . "'";
	$sql = "INSERT IGNORE INTO favoritedeck (decklink, usersId, name, hero) VALUES (" . $values. ");";
	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $sql)) {
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
		mysqli_close($conn);
	}
}

function LoadFavoriteDecks($userID)
{
	require_once "dbh.inc.php";
	global $servername, $dBUsername, $dBPassword, $dBName;
	$conn = mysqli_connect($servername, $dBUsername, $dBPassword, $dBName);
	$sql = "SELECT decklink, name, hero from favoritedeck where usersId=$userID";
	$stmt = mysqli_stmt_init($conn);
	$output = [];
	if (mysqli_stmt_prepare($stmt, $sql)) {
		mysqli_stmt_execute($stmt);
		$data = mysqli_stmt_get_result($stmt);
	  while($row = mysqli_fetch_array($data, MYSQLI_NUM)) {
			for($i=0;$i<3;++$i) array_push($output, $row[$i]);
		}
		mysqli_stmt_close($stmt);
		mysqli_close($conn);
	}
	return $output;
}

function logCompletedGameStats() {
	global $winner, $currentTurn, $gameName;//gameName is assumed by ParseGamefile.php
	$loser = ($winner == 1 ? 2 : 1);
	$columns = "WinningHero, LosingHero, NumTurns, WinnerDeck, LoserDeck";
	$values = "?, ?, ?, ?, ?";
	$winnerDeck = file_get_contents("./Games/" . $gameName . "/p" . $winner . "Deck.txt");
	$loserDeck = file_get_contents("./Games/" . $gameName . "/p" . $loser . "Deck.txt");
	require_once "./MenuFiles/ParseGamefile.php";
	if($p1id != "" && $p1id != "-")
	{
		$columns .= ", " . ($winner == 1 ? "WinningPID" : "LosingPID");
		$values .= ", " . $p1id;
	}
	if($p2id != "" && $p2id != "-")
	{
		$columns .= ", " . ($winner == 2 ? "WinningPID" : "LosingPID");
		$values .= ", " . $p2id;
	}

	require_once "dbh.inc.php";
	//global $conn;
  $sql = "INSERT INTO completedgame (" . $columns . ") VALUES (" . $values . ");";
	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $sql)) {
		$winHero = &GetPlayerCharacter($winner);
		$loseHero = &GetPlayerCharacter($loser);
		mysqli_stmt_bind_param($stmt, "sssss", $winHero[0], $loseHero[0], $currentTurn, $winnerDeck, $loserDeck);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
		mysqli_close($conn);
	}

}
