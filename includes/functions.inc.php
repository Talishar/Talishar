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
	global $p1id, $p2id, $p1IsChallengeActive, $p2IsChallengeActive, $p1DeckLink, $p2DeckLink;
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
		$gameResultID = mysqli_insert_id($conn);
		mysqli_stmt_close($stmt);
		$challengeId = 1;
		if($p1IsChallengeActive == "1" && $p1id != "-")
		{
			$sql = "INSERT INTO challengeresult (gameId, challengeId, playerId, result) VALUES (?, ?, ?, ?);";
			$stmt = mysqli_stmt_init($conn);
			if (mysqli_stmt_prepare($stmt, $sql)) {
				$result = ($winner == 1 ? 1 : 0);
				mysqli_stmt_bind_param($stmt, "ssss", $gameResultID, $challengeId, $p1id, $result);//Challenge ID 1 = sigil of solace blue
				mysqli_stmt_execute($stmt);
				mysqli_stmt_close($stmt);
			}
		}
		if($p2IsChallengeActive == "1" && $p2id != "-")
		{
			$sql = "INSERT INTO challengeresult (gameId, challengeId, playerId, result) VALUES (?, ?, ?, ?);";
			$stmt = mysqli_stmt_init($conn);
			if (mysqli_stmt_prepare($stmt, $sql)) {
				$result = ($winner == 2 ? 1 : 0);
				mysqli_stmt_bind_param($stmt, "ssss", $gameResultID, $challengeId, $p2id, $result);//Challenge ID 1 = sigil of solace blue
				mysqli_stmt_execute($stmt);
				mysqli_stmt_close($stmt);
			}
		}
	}
	SendFabraryResults(1, $p1DeckLink, ($winner == 1 ? $winnerDeck : $loserDeck));
	SendFabraryResults(2, $p2DeckLink, ($winner == 2 ? $winnerDeck : $loserDeck));
	mysqli_close($conn);
}

function SendFabraryResults($player, $decklink, $deck)
{
	global $FaBraryKey;
	if(!str_contains($decklink, "fabrary.net")) return;

	$url = "https://5zvy977nw7.execute-api.us-east-2.amazonaws.com/prod/decks/01G7FD2B3YQAMR8NJ4B3M58H96/results";
	$ch = curl_init($url);
	$payload = SerializeGameResult($player, $decklink, $deck);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$headers = array(
		"x-api-key: " . $FaBraryKey,
		"Content-Type: application/json",
	);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	$result = curl_exec($ch);
	curl_close($ch);
}

function SerializeGameResult($player, $DeckLink, $deckAfterSB)
{
	global $winner, $currentTurn, $CardStats_TimesPlayed, $CardStats_TimesBlocked, $CardStats_TimesPitched;
	$DeckLink = explode("/", $DeckLink);
	$DeckLink = $DeckLink[count($DeckLink)-1];
	$deckAfterSB = explode("\r\n", $deckAfterSB);
	if(count($deckAfterSB) == 1) return;
	$deckAfterSB = $deckAfterSB[1];
	$deck = [];
	$deck["deckId"] = $DeckLink;
	$deck["turns"] = intval($currentTurn);
	$deck["result"] = ($player == $winner ? 1 : 0);
	$deck["cardResults"] = [];
	$deckAfterSB = explode(" ", $deckAfterSB);
	foreach($deckAfterSB as $card)
	{
		$deck["cardResults"][$card] = [];
		$deck["cardResults"][$card]["played"] = 0;
		$deck["cardResults"][$card]["blocked"] = 0;
		$deck["cardResults"][$card]["pitched"] = 0;
	}
	$cardStats = &GetCardStats($player);
	for($i=0; $i<count($cardStats); $i+=CardStatPieces())
	{
		if(!isset($deck["cardResults"][$cardStats[$i]])) continue;
		$deck["cardResults"][$cardStats[$i]]["played"] = $cardStats[$i+$CardStats_TimesPlayed];
		$deck["cardResults"][$cardStats[$i]]["blocked"] = $cardStats[$i+$CardStats_TimesBlocked];
		$deck["cardResults"][$cardStats[$i]]["pitched"] = $cardStats[$i+$CardStats_TimesPitched];
	}
	return json_encode($deck);
}

function UpdateKarma($p1value=0, $p2value=0)
{
	global $p1id, $p2id;

	$conn = GetDBConnection();
	$stmt = "";
	if($p1id != "" && $p1id != "-")
	{
		$sql = "UPDATE users SET usersKarma=usersKarma+$p1value WHERE usersid='$p1id'";
		$stmt = mysqli_stmt_init($conn);
		if (mysqli_stmt_prepare($stmt, $sql)) {
			mysqli_stmt_execute($stmt);
		}
	}
	if($p2id != "" && $p2id != "-")
	{
		$sql = "UPDATE users SET usersKarma=usersKarma+$p2value WHERE usersid='$p2id'";
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

function AddRating($player, $rating)
{
	global $p1id, $p2id;//, $p1GreenRating, $p2GreenRating;

	$dbID = ($player == 1 ? $p1id : $p2id);

	if($dbID != "" && $dbID != "-")
	{
		$conn = GetDBConnection();
		$sql = "UPDATE users SET " . $rating . "Thumbs=" . $rating . "Thumbs+1 WHERE usersid='$dbID'";
		$stmt = mysqli_stmt_init($conn);
		if (mysqli_stmt_prepare($stmt, $sql)) {
			mysqli_stmt_execute($stmt);
			mysqli_stmt_close($stmt);
		}
		mysqli_close($conn);
	}
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
