<?php

// Check for empty input signup
function emptyInputSignup($username, $email, $pwd, $pwdRepeat) {
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
	$conn = GetDBConnection();
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
	mysqli_close($conn);
}

// Check if username is in database, if so then return data
function getUInfo($conn, $username) {
	$conn = GetDBConnection();
  $sql = "SELECT * FROM users WHERE usersUid = ? OR usersEmail = ?;";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt, $sql)) {
		mysqli_close($conn);
	 	header("location: ../Signup.php?error=stmtfailed");
		exit();
	}

	mysqli_stmt_bind_param($stmt, "ss", $username, $email);
	mysqli_stmt_execute($stmt);

	// "Get result" returns the results from a prepared statement
	$resultData = mysqli_stmt_get_result($stmt);

	if ($row = mysqli_fetch_assoc($resultData)) {
		mysqli_close($conn);
		return $row;
	}
	else {
		$result = false;
		mysqli_close($conn);
		return $result;
	}
	mysqli_stmt_close($stmt);
	mysqli_close($conn);
}

// Insert new user into database
function createUser($conn, $username, $email, $pwd) {
	$conn = GetDBConnection();
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
	if (empty($username) || empty($pwd)) {
		$result = true;
	}
	else {
		$result = false;
	}
	return $result;
}

// Log user into website
function loginUser($username, $pwd, $rememberMe) {
	$conn = GetDBConnection();
	$uidExists = uidExists($conn, $username);

	if ($uidExists === false) {
		mysqli_close($conn);
		header("location: ../Login.php?error=wronglogin");
		exit();
	}

	$pwdHashed = $uidExists["usersPwd"];
	$checkPwd = password_verify($pwd, $pwdHashed);

	if ($checkPwd === false) {
		mysqli_close($conn);
		header("location: ../Login.php?error=wronglogin");
		exit();
	}
	elseif ($checkPwd === true) {
		if(session_status() !== PHP_SESSION_ACTIVE) session_start();
		$_SESSION["userid"] = $uidExists["usersID"];
		$_SESSION["useruid"] = $uidExists["usersUid"];
		$_SESSION["useremail"] = $uidExists["usersEmail"];
		$_SESSION["userspwd"] = $uidExists["usersPwd"];
		$patreonAccessToken = $uidExists["patreonAccessToken"];
		$_SESSION["userKarma"] = $uidExists["usersKarma"];
		$_SESSION["greenThumb"] = $uidExists["greenThumbs"];
		$_SESSION["redThumb"] = $uidExists["redThumbs"];

		PatreonLogin($patreonAccessToken);

		if($rememberMe)
		{
			$cookie = hash("sha256", rand() . $_SESSION["userspwd"] . rand());
			setcookie("rememberMeToken", $cookie, time() + (86400 * 90), "/");
			storeRememberMeCookie($conn, $_SESSION["useruid"], $cookie);
		}

		mysqli_close($conn);
		header("location: ../MainMenu.php?error=none");
		exit();
	}
}

function loginFromCookie()
{
	$token = $_COOKIE["rememberMeToken"];
	$conn = GetDBConnection();
	$sql = "SELECT usersID, usersUid, usersEmail, patreonAccessToken, patreonRefreshToken, usersKarma FROM users WHERE rememberMeToken='$token'";
	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $sql)) {
		mysqli_stmt_execute($stmt);
		$data = mysqli_stmt_get_result($stmt);
		$row = mysqli_fetch_array($data, MYSQLI_NUM);
		mysqli_stmt_close($stmt);
		if($row != null && count($row) > 0)
		{
			$_SESSION["userid"] = $row[0];
			$_SESSION["useruid"] = $row[1];
			$_SESSION["useremail"] = $row[2];
			$patreonAccessToken = $row[3];
			$patreonRefreshToken = $row[4];
			$_SESSION["userKarma"] = $row[5];
			PatreonLogin($patreonAccessToken);
		}
		else {
			unset($_SESSION["userid"]);
			unset($_SESSION["useruid"]);
			unset($_SESSION["useremail"]);
			unset($_SESSION["userKarma"]);
		}
	}
	mysqli_close($conn);
}

function storeRememberMeCookie($conn, $uuid, $cookie)
{
  $sql = "UPDATE users SET rememberMeToken='$cookie' WHERE usersUid='$uuid'";
	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $sql)) {
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	}
}

function addFavoriteDeck($userID, $decklink, $deckName, $heroID)
{
	$conn = GetDBConnection();
	$values = "'" . $decklink . "'," . $userID . ",'" . $deckName . "','" . $heroID . "'";
	$sql = "INSERT IGNORE INTO favoritedeck (decklink, usersId, name, hero) VALUES (" . $values. ");";
	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $sql)) {
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	}
	mysqli_close($conn);
}

function LoadFavoriteDecks($userID)
{
	$conn = GetDBConnection();
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
	}
	mysqli_close($conn);
	return $output;
}

function logCompletedGameStats() {
	global $winner, $currentTurn, $gameName;//gameName is assumed by ParseGamefile.php
	global $p1id, $p2id;
	$loser = ($winner == 1 ? 2 : 1);
	$columns = "WinningHero, LosingHero, NumTurns, WinnerDeck, LoserDeck";
	$values = "?, ?, ?, ?, ?";
	$winnerDeck = file_get_contents("./Games/" . $gameName . "/p" . $winner . "Deck.txt");
	$loserDeck = file_get_contents("./Games/" . $gameName . "/p" . $loser . "Deck.txt");
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

	$conn = GetDBConnection();

  $sql = "INSERT INTO completedgame (" . $columns . ") VALUES (" . $values . ");";
	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $sql)) {
		$winHero = &GetPlayerCharacter($winner);
		$loseHero = &GetPlayerCharacter($loser);
		mysqli_stmt_bind_param($stmt, "sssss", $winHero[0], $loseHero[0], $currentTurn, $winnerDeck, $loserDeck);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	}
	mysqli_close($conn);

}

function UpdateKarma($p1value=0, $p2value=0) 
{
	global $p1id, $p2id, $p1Karma, $p2Karma;
	
	$p1NewKarma = $p1Karma + $p1value;
	$p2NewKarma = $p2Karma + $p2value;

	$conn = GetDBConnection();
	$stmt = "";
	if($p1id != "" && $p1id != "-")
	{
		$sql = "UPDATE users SET usersKarma='$p1NewKarma' WHERE usersid='$p1id'";
		$stmt = mysqli_stmt_init($conn);
		if (mysqli_stmt_prepare($stmt, $sql)) {
			mysqli_stmt_execute($stmt);
		}
	}
	if($p2id != "" && $p2id != "-")
	{
		$sql = "UPDATE users SET usersKarma='$p2NewKarma' WHERE usersid='$p2id'";
		$stmt = mysqli_stmt_init($conn);
		if (mysqli_stmt_prepare($stmt, $sql)) {
			mysqli_stmt_execute($stmt);
		}
	}
	if($stmt != ""){
		mysqli_stmt_close($stmt);
	}
	mysqli_close($conn);
}

function AddGreenRating($p1value=0, $p2value=0)
{
	global $p1id, $p2id, $p1GreenRating, $p2GreenRating;
	
	$p1GreenerThumb = $p1GreenRating;
	$p2GreenerThumb = $p2GreenRating;

	if($p1value == 1) $p1GreenerThumb = $p1GreenRating + 1;
	if($p2value == 1) $p2GreenerThumb = $p2GreenRating + 1;

	WriteLog("P1 Greener: " . $p1GreenerThumb . " ID: " . $p1id . " Rating: " . $p1GreenRating);
	WriteLog("P2 Greener: " . $p2GreenerThumb . " ID: " . $p2id . " Rating: " . $p2GreenRating);

	$conn = GetDBConnection();
	$stmt = "";
	if($p1id != "" && $p1id != "-")
	{
		$sql = "UPDATE users SET greenThumbs='$p1GreenerThumb' WHERE usersid='$p1id'";
		$stmt = mysqli_stmt_init($conn);
		if (mysqli_stmt_prepare($stmt, $sql)) {
			mysqli_stmt_execute($stmt);
		}
	}
	if($p2id != "" && $p2id != "-")
	{
		$sql = "UPDATE users SET greenThumbs='$p2GreenerThumb' WHERE usersid='$p2id'";
		$stmt = mysqli_stmt_init($conn);
		if (mysqli_stmt_prepare($stmt, $sql)) {
			mysqli_stmt_execute($stmt);
		}
	}
	if($stmt != ""){
		mysqli_stmt_close($stmt);
	}
	mysqli_close($conn);
}

function AddRedRating($p1value=0, $p2value=0)
{
	global $p1id, $p2id, $p1RedRating, $p2RedRating;

	$p1RedderThumb = $p1RedRating;
	$p2RedderThumb = $p2RedRating;
	
	// TODO: Add a mathematical equasion if the player has too many red compared to green. Maybe like for ech extra 10 you have you lose an extra one. e.g. someone with 1 green and 12 red would get -2 karma.
	if($p1value == 2) $p1RedderThumb = $p1RedRating + 1;
	if($p2value == 2) $p2RedderThumb = $p2RedRating + 1;

	$conn = GetDBConnection();
	$stmt = "";
	if($p1id != "" && $p1id != "-")
	{
	$sql = "UPDATE users SET redThumbs='$p1RedderThumb' WHERE usersid='$p1id'";
	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $sql)) {
		mysqli_stmt_execute($stmt);
	}
	}
	if($p2id != "" && $p2id != "-")
	{
		$sql = "UPDATE users SET redThumbs='$p2RedderThumb' WHERE usersid='$p2id'";
		$stmt = mysqli_stmt_init($conn);
		if (mysqli_stmt_prepare($stmt, $sql)) {
			mysqli_stmt_execute($stmt);
		}
	}
	if($stmt != ""){
		mysqli_stmt_close($stmt);
	}
	mysqli_close($conn);
	}

function SavePatreonTokens($accessToken, $refreshToken)
{
	if(!isset($_SESSION["userid"])) return;
	$userID = $_SESSION["userid"];
	$conn = GetDBConnection();
	$sql = "UPDATE users SET patreonAccessToken='$accessToken', patreonRefreshToken='$refreshToken' WHERE usersid='$userID'";
	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $sql)) {
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	}
	mysqli_close($conn);
}
