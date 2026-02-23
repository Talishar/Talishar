<?php

require_once __DIR__ . '/../Assets/patreon-php-master/src/PatreonLibraries.php';

use SendGrid\Mail\Mail;

// Check for empty input signup
function emptyInputSignup($username, $email, $pwd, $pwdRepeat)
{
	$result = (empty($username) || empty($email) || empty($pwd) || empty($pwdRepeat)) ? true : false;
	return $result;
}

// Check invalid username
function invalidUid($username)
{
	$result = (!ctype_alnum($username)) ? true : false;
	return $result;
}

// Check invalid email
function invalidEmail($email)
{
	$result = (!filter_var($email, FILTER_VALIDATE_EMAIL)) ? true : false;
	return $result;
}

// Check if passwords matches
function pwdMatch($pwd, $pwdrepeat)
{
	$result = ($pwd !== $pwdrepeat) ? true : false;
	return $result;
}

// Check if username is in database, if so then return data
function uidExists($conn, $username)
{
	$conn = GetDBConnection();
	if (!$conn) {
		header("location: ../Signup.php?error=db_unavailable");
		exit();
	}
	$sql = "SELECT * FROM users WHERE usersUid = ?;";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt, $sql)) {
		header("location: ../Signup.php?error=stmtfailed");
		exit();
	}

	mysqli_stmt_bind_param($stmt, "s", $username);
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
}

// Check if email is in database, if so then return data
function emailExists($conn, $email)
{
	$conn = GetDBConnection();
	if (!$conn) {
		header("location: ../Signup.php?error=db_unavailable");
		exit();
	}
	$sql = "SELECT * FROM users WHERE usersEmail = ?;";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt, $sql)) {
		header("location: ../Signup.php?error=stmtfailed");
		exit();
	}

	mysqli_stmt_bind_param($stmt, "s", $email);
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
}

// Insert new user into database
function createUser($conn, $username, $email, $pwd, $reportingServer = false)
{
	$conn = $reportingServer ? GetReportingDBConnection() : GetDBConnection();
	if (!$conn) {
		header("location: ../Signup.php?error=db_unavailable");
		exit();
	}
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

function CreateUserAPI($conn, $username, $email, $pwd)
{
	$conn = GetDBConnection();
	if (!$conn) {
		return false;
	}
	$sql = "INSERT INTO users (usersUid, usersEmail, usersPwd) VALUES (?, ?, ?);";

	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt, $sql)) {
		return false;
	}

	$hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

	mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashedPwd);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);
	mysqli_close($conn);
}

function loginFromCookie()
{
    if (isset($_COOKIE["rememberMeToken"])) {
        $token = $_COOKIE["rememberMeToken"];
        $conn = GetDBConnection();
        if (!$conn) {
            return; // Silently fail if database unavailable
        }
        $sql = "SELECT usersId, usersUid, usersEmail, patreonAccessToken, patreonRefreshToken, patreonEnum, isBanned, lastGameName, lastPlayerId, lastAuthKey FROM users WHERE rememberMeToken=?";
        $stmt = mysqli_stmt_init($conn);
        
        if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $token);
            mysqli_stmt_execute($stmt);
            $data = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_array($data, MYSQLI_NUM);
            mysqli_stmt_close($stmt);

            if (session_status() !== PHP_SESSION_ACTIVE) {
                session_start();
            }

            if ($row != null && count($row) > 0) {
                $_SESSION["userid"] = $row[0];
                $_SESSION["useruid"] = $row[1];
                $_SESSION["useremail"] = $row[2];
                $patreonAccessToken = $row[3];
                $patreonRefreshToken = $row[4];
                $_SESSION["patreonEnum"] = $row[5];
                $_SESSION["isBanned"] = $row[6];
                $_SESSION["lastGameName"] = $row[7];
                $_SESSION["lastPlayerId"] = $row[8];
                $_SESSION["lastAuthKey"] = $row[9];
                
                try {
                    PatreonLogin($patreonAccessToken);
                } catch (\Throwable $e) {
                    // Handle exception (if any)
                }
            } else {
                // Unset session variables if token doesn't match
                unset($_SESSION["userid"]);
                unset($_SESSION["useruid"]);
                unset($_SESSION["useremail"]);
                unset($_SESSION["patreonEnum"]);
                unset($_SESSION["isBanned"]);
                unset($_SESSION["lastGameName"]);
                unset($_SESSION["lastPlayerId"]);
                unset($_SESSION["lastAuthKey"]);
            }
            session_write_close();
        }
        mysqli_close($conn);
    } else {
        // Handle the case when rememberMeToken doesn't exist in cookies
        // For example, you might redirect the user or show a message
        echo "Remember me token not found.";
    }
}

function storeFabraryId($uid, $fabraryId)
{
	$conn = GetDBConnection();
	$sql = "UPDATE users SET fabraryId=? WHERE usersId=?";
	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $sql)) {
		mysqli_stmt_bind_param($stmt, "ss", $fabraryId, $uid);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	}
}

function storeFabDBId($uid, $fabdbId)
{
	$conn = GetDBConnection();
	$sql = "UPDATE users SET fabdbId=? WHERE usersId=?";
	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $sql)) {
		mysqli_stmt_bind_param($stmt, "ss", $fabdbId, $uid);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	}
}

function StoreLastGameInfo($uid, $gameName, $playerID, $authKey)
{
	$conn = GetDBConnection();
	$sql = "UPDATE users SET lastGameName=?, lastPlayerId=?, lastAuthKey=? WHERE usersId=?";
	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $sql)) {
		mysqli_stmt_bind_param($stmt, "ssss", $gameName, $playerID, $authKey, $uid);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	}
	mysqli_close($conn);

	if(session_status() !== PHP_SESSION_ACTIVE) session_start();
	$_SESSION["lastGameName"] = $gameName;
	$_SESSION["lastPlayerId"] = $playerID;
	$_SESSION["lastAuthKey"] = $authKey;
	session_write_close();
}

function GetDeckBuilderId($uid, $decklink)
{
	$conn = GetDBConnection();
	$sql = "SELECT fabraryId,fabdbId FROM users WHERE usersId=?";
	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $sql)) {
		mysqli_stmt_bind_param($stmt, "s", $uid);
		mysqli_stmt_execute($stmt);
		$data = mysqli_stmt_get_result($stmt);
		$row = mysqli_fetch_array($data, MYSQLI_NUM);
		mysqli_stmt_close($stmt);
	}
	mysqli_close($conn);
	$dbId = "";
	if (count($row) == 0) return "";
	if (str_contains($decklink, "fabrary")) $dbId = $row[0];
	else if (str_contains($decklink, "fabdb")) $dbId = $row[1];
	if ($dbId == "NULL") $dbId = "";
	return $dbId;
}

function addFavoriteDeck($userID, $decklink, $deckName, $heroID, $format = "")
{
	$conn = GetDBConnection();
	$deckName = implode("", explode("\"", $deckName));
	$deckName = implode("", explode("'", $deckName));
	$values = "'" . $decklink . "'," . $userID . ",'" . $deckName . "','" . $heroID . "','" . $format . "'";
	$sql = "INSERT IGNORE INTO favoritedeck (decklink, usersId, name, hero, format) VALUES (?, ?, ?, ?, ?);";
	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $sql)) {
		mysqli_stmt_bind_param($stmt, "sssss", $decklink, $userID, $deckName, $heroID, $format);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	}
	mysqli_close($conn);
}

function LoadFavoriteDecks($userID)
{
	if ($userID == "") return [];
	$conn = GetDBConnection();
	if (!$conn) return [];
	$sql = "SELECT decklink, name, hero, format from favoritedeck where usersId=?";
	$stmt = mysqli_stmt_init($conn);
	$output = [];
	if (mysqli_stmt_prepare($stmt, $sql)) {
		mysqli_stmt_bind_param($stmt, "s", $userID);
		mysqli_stmt_execute($stmt);
		$data = mysqli_stmt_get_result($stmt);
		while ($row = mysqli_fetch_array($data, MYSQLI_NUM)) {
			for ($i = 0; $i < 4; ++$i) array_push($output, $row[$i]);
		}
		mysqli_stmt_close($stmt);
	}
	mysqli_close($conn);
	return $output;
}

function ConvertDeck($deck) {
	$ret = "";
	foreach(explode("\n", $deck) as $line) {
		foreach (explode(" ", $line) as $card) {
			$ret .= SetID($card) . " ";
		}
        $ret = substr($ret, 0, -1);
		$ret .= "\n";
	}
    $ret = substr($ret, 0, -1);
	return $ret;
}

//Challenge ID 1 = sigil of solace blue
//Challenge ID 2 = Talishar no dash
//Challenge ID 3 = Moon Wish
function logCompletedGameStats($conceded = false)
{
	global $winner, $currentTurn, $gameName; //gameName is assumed by ParseGamefile.php
	global $p1id, $p2id, $p1IsChallengeActive, $p2IsChallengeActive, $p1DeckLink, $p2DeckLink, $firstPlayer;
	global $p1deckbuilderID, $p2deckbuilderID, $gameGUID;
	$loser = ($winner == 1 ? 2 : 1);
	$columns = "WinningHero, LosingHero, NumTurns, WinnerDeck, LoserDeck, WinnerHealth, FirstPlayer";
	$values = "?, ?, ?, ?, ?, ?, ?";
	$winnerDeckFile = "./Games/" . $gameName . "/p" . $winner . "Deck.txt";
	$loserDeckFile = "./Games/" . $gameName . "/p" . $loser . "Deck.txt";
	if (!file_exists($winnerDeckFile) || !file_exists($loserDeckFile)) return;
	$winnerDeck = file_get_contents($winnerDeckFile);
	$loserDeck = file_get_contents($loserDeckFile);
	$winHero = &GetPlayerCharacter($winner);
	$loseHero = &GetPlayerCharacter($loser);

	$winHeroID = SetID($winHero[0]);
	$loseHeroID = SetID($loseHero[0]);
	$winIDDeck = ConvertDeck($winnerDeck);
	$loseIDDeck = ConvertDeck($loserDeck);

	$countWinnerDeck = count(GetDeck($winner));
	$countLoserDeck = count(GetDeck($loser));

	$conn = GetDBConnection();

	// Build parameterized query safely
	$params = [$winHeroID, $loseHeroID, $currentTurn, $winIDDeck, $loseIDDeck, GetHealth($winner), $firstPlayer];
	$paramTypes = "sssssss";
	
	if ($p1id != "" && $p1id != "-") {
		$columns .= ", " . ($winner == 1 ? "WinningPID" : "LosingPID");
		$values .= ", ?";
		$params[] = $p1id;
		$paramTypes .= "s";
	}
	if ($p2id != "" && $p2id != "-") {
		$columns .= ", " . ($winner == 2 ? "WinningPID" : "LosingPID");
		$values .= ", ?";
		$params[] = $p2id;
		$paramTypes .= "s";
	}

	$sql = "INSERT INTO completedgame (" . $columns . ") VALUES (" . $values . ");";
	$stmt = mysqli_stmt_init($conn);
	
	$gameResultID = 0;
	if (mysqli_stmt_prepare($stmt, $sql)) {
		mysqli_stmt_bind_param($stmt, $paramTypes, ...$params);
		mysqli_stmt_execute($stmt);
		$gameResultID = mysqli_insert_id($conn);
		mysqli_stmt_close($stmt);
	}

	if ($p1IsChallengeActive == "1" && $p1id != "-") LogChallengeResult($conn, $gameResultID, $p1id, $winner == 1 ? 1 : 0);
	if ($p2IsChallengeActive == "1" && $p2id != "-") LogChallengeResult($conn, $gameResultID, $p2id, $winner == 2 ? 1 : 0);

	$p1Deck = ($winner == 1 ? $winnerDeck : $loserDeck);
	$p2Deck = ($winner == 2 ? $winnerDeck : $loserDeck);
	$p1Hero = ($winner == 1 ? $winHero[0] : $loseHero[0]);
	$p2Hero = ($winner == 2 ? $winHero[0] : $loseHero[0]);

	if (!AreStatsDisabled(1) && !AreStatsDisabled(2)) {
		WriteLog("📊Sending game result to <b>Fabrary</b>", highlight:true, highlightColor:"green");
		SendFullFabraryResults($gameResultID, $p1DeckLink, $p1Deck, $p1Hero, $p1deckbuilderID, $p2DeckLink, $p2Deck, $p2Hero, $p2deckbuilderID, $gameGUID, $conceded);
	}
	elseif (AreStatsDisabled(1) && !AreStatsDisabled(2)) {
		WriteLog("📊Sending game result to <b>Fabrary</b> for only Player 2", highlight:true, highlightColor:"green");
		SendFullFabraryResults($gameResultID, "-", $p1Deck, $p1Hero, $p1deckbuilderID, $p2DeckLink, $p2Deck, $p2Hero, $p2deckbuilderID, $gameGUID, $conceded);
	}
	elseif (!AreStatsDisabled(1) && AreStatsDisabled(2)) {
		WriteLog("📊Sending game result to <b>Fabrary</b> for only Player 1", highlight:true, highlightColor:"green");
		SendFullFabraryResults($gameResultID, $p1DeckLink, $p1Deck, $p1Hero, $p1deckbuilderID, "-", $p2Deck, $p2Hero, $p2deckbuilderID, $gameGUID, $conceded);
	}
	else WriteLog("No results sent to <b>Fabrary</b> as both players disabled stats", highlight:true);
	// Sends data to FabInsights DB
	$p1StatsDisabled = AreStatsDisabled(1) || AreGlobalStatsDisabled(1);
	$p2StatsDisabled = AreStatsDisabled(2) || AreGlobalStatsDisabled(2);
	if (!$p1StatsDisabled && !$p2StatsDisabled) {
		WriteLog("📊Sending game stats to <b>FaBInsights</b>", highlight:true, highlightColor:"green");
	}
	elseif ($p1StatsDisabled && !$p2StatsDisabled) {
		WriteLog("📊Sending game stats to <b>FaBInsights</b> for only Player 2", highlight:true, highlightColor:"green");
	}
	elseif (!$p1StatsDisabled && $p2StatsDisabled) {
		WriteLog("📊Sending game stats to <b>FaBInsights</b> for only Player 1", highlight:true, highlightColor:"green");
	}
	else WriteLog("No game stats sent to <b>FaBInsights</b> as both players disabled stats", highlight:true);
	SendFaBInsightsResults($gameResultID, $p1DeckLink, $p1Deck, $p1Hero, $p1deckbuilderID, $p2DeckLink, $p2Deck, $p2Hero, $p2deckbuilderID, $p1StatsDisabled, $p2StatsDisabled, $gameGUID, $conceded, $countWinnerDeck, $countLoserDeck);
	mysqli_close($conn);
}

function LogChallengeResult($conn, $gameResultID, $playerID, $result)
{
	WriteLog("Writing challenge result for player " . $playerID);
	$challengeId = 3;
	$sql = "INSERT INTO challengeresult (gameId, challengeId, playerId, result) VALUES (?, ?, ?, ?);";
	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $sql)) {
		mysqli_stmt_bind_param($stmt, "ssss", $gameResultID, $challengeId, $playerID, $result); //Challenge ID 1 = sigil of solace blue
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	}
}


function SendFabDBResults($player, $decklink, $deck, $gameID, $opposingHero)
{
	global $fabDBToken, $fabDBSecret, $gameName, $p1deckbuilderID, $p2deckbuilderID;
	if($decklink == null || !str_contains($decklink, "fabdb.net")) return;

	$linkArr = explode("/", $decklink);
	$slug = array_pop($linkArr);

	$url = "https://api.fabdb.net/game/results/" . $slug;
	$ch = curl_init($url);
	$payload = SerializeGameResult($player, $decklink, $deck, $gameID, $opposingHero, $gameName);
	$payloadArr = json_decode($payload, true);
	$payloadArr["time"] = microtime();
	$payloadArr["hash"] = hash("sha512", $fabDBSecret . $payloadArr["time"]);
	$payloadArr["player"] = $player;
	$payloadArr["user"] = ($player == 1 ? $p1deckbuilderID : $p2deckbuilderID);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payloadArr));
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);

	$headers = [
		"Authorization: Bearer " . $fabDBToken,
	];

	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLINFO_HEADER_OUT, true);
	$result = curl_exec($ch);
	$information = curl_getinfo($ch);
	curl_close($ch);
}

function SendFullFabraryResults($gameID, $p1Decklink, $p1Deck, $p1Hero, $p1deckbuilderID, $p2Decklink, $p2Deck, $p2Hero, $p2deckbuilderID, $gameGUID = "", $conceded = false)
{
	global $FaBraryKey, $gameName, $p2IsAI;
	if($p2IsAI == "1") return;//Don't file results for AI games
	$url = "https://atofkpq0x8.execute-api.us-east-2.amazonaws.com/prod/v1/results";
	$ch = curl_init($url);
	$payloadArr = [];
	$payloadArr['gameID'] = $gameID;
	$payloadArr['gameName'] = $gameName;
	$payloadArr['deck1'] = json_decode(SerializeGameResult(1, $p1Decklink, $p1Deck, $gameID, $p2Hero, $gameName, $p1deckbuilderID));
	$payloadArr['deck2'] = json_decode(SerializeGameResult(2, $p2Decklink, $p2Deck, $gameID, $p1Hero, $gameName, $p2deckbuilderID));
	$payloadArr["format"] = GetCachePiece(intval($gameName), 13);
	$payloadArr['gameGUID'] = $gameGUID;
	$payloadArr['conceded'] = $conceded;

	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payloadArr));
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
	$headers = [
		"x-api-key: " . $FaBraryKey,
		"Content-Type: application/json",
	];
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$result = curl_exec($ch);
	curl_close($ch);
}

function HashPlayerName($name, $salt) {
    if(empty($name)) return "";
    if(empty($salt)) {
        error_log("WARNING: Player name hash salt not configured - player names will not be sent");
        return "";
    }
    // Use HMAC with SHA-256 for consistent but secure hashing
    return hash_hmac('sha256', $name, $salt);
}

function SendFaBInsightsResults($gameID, $p1DeckLink, $p1Deck, $p1Hero, $p1deckbuilderID, $p2DeckLink, $p2Deck, $p2Hero, $p2deckbuilderID, $p1StatsDisabled = false, $p2StatsDisabled = false, $gameGUID = "", $conceded = false, $countWinnerDeck = 0, $countLoserDeck = 0)
{
	global $FaBInsightsKey, $gameName, $p2IsAI, $p1uid, $p2uid, $playerHashSalt, $deckHashSalt;
    // Skip AI games
    if ($p2IsAI == "1") return;

    // Hash player names
    $hashedP1Name = HashPlayerName($p1uid, $playerHashSalt);
    $hashedP2Name = HashPlayerName($p2uid, $playerHashSalt);
	$hashedP1Deck = HashPlayerName($p1DeckLink, $deckHashSalt);
	$hashedP2Deck = HashPlayerName($p2DeckLink, $deckHashSalt);

    // Your Azure Function endpoint URL
    $url = "https://fab-insights.azurewebsites.net/api/send_results";

    // Prepare the data for the POST request
    $payloadArr = [];
    $payloadArr['gameID'] = $gameID;
    $payloadArr['gameName'] = $gameName;
    $payloadArr['player1Name'] = $hashedP1Name;
    $payloadArr['player2Name'] = $hashedP2Name;
    $payloadArr['deck1'] = json_decode(SerializeDetailedGameResult(1, $hashedP1Deck, $p1Deck, $gameID, $p2Hero, $gameName, $p1deckbuilderID, $p1Hero, $p1StatsDisabled));
    $payloadArr['deck2'] = json_decode(SerializeDetailedGameResult(2, $hashedP2Deck, $p2Deck, $gameID, $p1Hero, $gameName, $p2deckbuilderID, $p2Hero, $p2StatsDisabled));
    $payloadArr["format"] = GetCachePiece(intval($gameName), 13);
	$payloadArr['gameGUID'] = $gameGUID;
	$payloadArr['conceded'] = $conceded;
	$payloadArr['countWinnerDeck'] = $countWinnerDeck;
	$payloadArr['countLoserDeck'] = $countLoserDeck;

    // Initialize cURL
    $ch = curl_init($url);

    // Set cURL options
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payloadArr));  // Send JSON data
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                 // Get the response back
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",  // Specify the content type
		"x-functions-key: " . $FaBInsightsKey // Add x-functions-key header
    ]);

    $response = curl_exec($ch);
    curl_close($ch);
}

function PopulateTurnStatsAndAggregates(&$deck, &$turnStats, &$otherPlayerTurnStats, $player, $useIntval = false)
{
	global $currentTurn, $TurnStats_DamageThreatened, $TurnStats_DamageDealt, $TurnStats_CardsPlayedOffense;
	global $TurnStats_CardsPlayedDefense, $TurnStats_CardsPitched, $TurnStats_CardsBlocked, $TurnStats_DamageBlocked;
	global $TurnStats_ResourcesUsed, $TurnStats_CardsLeft, $TurnStats_ResourcesLeft, $TurnStats_LifeGained;
	global $TurnStats_LifeLost, $TurnStats_DamagePrevented, $TurnStats_CardsDiscarded, $p1TotalTime, $p2TotalTime;

	$countTurnStats = count($turnStats);

	// Populate turn results - only include turns that have actually occurred
	for($i = 0; $i < $countTurnStats && intval($i / TurnStatPieces()) <= $currentTurn; $i += TurnStatPieces()) {
		$turnNo = intval($i / TurnStatPieces());
		$turnKey = "turn_" . $turnNo;
		
		$cardsUsed = $turnStats[$i + $TurnStats_CardsPlayedOffense] + $turnStats[$i + $TurnStats_CardsPlayedDefense];
		$cardsBlocked = $turnStats[$i + $TurnStats_CardsBlocked];
		$cardsPitched = $turnStats[$i + $TurnStats_CardsPitched];
		$cardsDiscarded = $turnStats[$i + $TurnStats_CardsDiscarded];
		$resourcesUsed = $turnStats[$i + $TurnStats_ResourcesUsed];
		$resourcesLeft = $turnStats[$i + $TurnStats_ResourcesLeft];
		$cardsLeft = $turnStats[$i + $TurnStats_CardsLeft];
		$damageThreatened = $turnStats[$i + $TurnStats_DamageThreatened];
		$damageDealt = $turnStats[$i + $TurnStats_DamageDealt];
		$damageBlocked = $turnStats[$i + $TurnStats_DamageBlocked];
		$damagePrevented = $turnStats[$i + $TurnStats_DamagePrevented];
		$damageTaken = $otherPlayerTurnStats[$i + $TurnStats_DamageDealt];
		$lifeGained = $turnStats[$i + $TurnStats_LifeGained];
		$lifeLost = $turnStats[$i + $TurnStats_LifeLost];

		if($useIntval) {
			$cardsUsed = intval($cardsUsed);
			$cardsBlocked = intval($cardsBlocked);
			$cardsPitched = intval($cardsPitched);
			$cardsDiscarded = intval($cardsDiscarded);
			$resourcesUsed = intval($resourcesUsed);
			$resourcesLeft = intval($resourcesLeft);
			$cardsLeft = intval($cardsLeft);
			$damageThreatened = intval($damageThreatened);
			$damageDealt = intval($damageDealt);
			$damageBlocked = intval($damageBlocked);
			$damagePrevented = intval($damagePrevented);
			$damageTaken = intval($damageTaken);
			$lifeGained = intval($lifeGained);
			$lifeLost = intval($lifeLost);
		}

		$deck["turnResults"][$turnKey]["cardsUsed"] = $cardsUsed;
		$deck["turnResults"][$turnKey]["cardsBlocked"] = $cardsBlocked;
		$deck["turnResults"][$turnKey]["cardsPitched"] = $cardsPitched;
		$deck["turnResults"][$turnKey]["cardsDiscarded"] = $cardsDiscarded;
		$deck["turnResults"][$turnKey]["resourcesUsed"] = $resourcesUsed;
		$deck["turnResults"][$turnKey]["resourcesLeft"] = $resourcesLeft;
		$deck["turnResults"][$turnKey]["cardsLeft"] = $cardsLeft;
		$deck["turnResults"][$turnKey]["damageThreatened"] = $damageThreatened;
		$deck["turnResults"][$turnKey]["damageDealt"] = $damageDealt;
		$deck["turnResults"][$turnKey]["damageBlocked"] = $damageBlocked;
		$deck["turnResults"][$turnKey]["damagePrevented"] = $damagePrevented;
		$deck["turnResults"][$turnKey]["damageTaken"] = $damageTaken;
		$deck["turnResults"][$turnKey]["lifeGained"] = $lifeGained;
		$deck["turnResults"][$turnKey]["lifeLost"] = $lifeLost;

		// SerializeGameResult has turnNo, SerializeDetailedGameResult doesn't - only add if not using intval
		if(!$useIntval) {
			$deck["turnResults"][$turnKey]["turnNo"] = $turnNo;
		}
	}

	// Set time information
	$time = ($player == 1 ? $p1TotalTime : $p2TotalTime);
	$totalTime = $p1TotalTime + $p2TotalTime;
	$deck["yourTime"] = $time;
	$deck["totalTime"] = $totalTime;
}
function PopulateAggregateStats(&$deck, &$turnStats)
{
	global $TurnStats_DamageThreatened, $TurnStats_DamageDealt, $TurnStats_CardsPlayedDefense, $TurnStats_CardsBlocked, $TurnStats_DamageBlocked;
	global $TurnStats_ResourcesUsed, $TurnStats_CardsLeft, $TurnStats_LifeGained, $TurnStats_LifeLost, $TurnStats_DamagePrevented;

	$totalDamageThreatened = 0;
	$totalDamageDealt = 0;
	$totalResourcesUsed = 0;
	$totalCardsLeft = 0;
	$totalDefensiveCards = 0;
	$totalDamageBlocked = 0;
	$totalLifeGained = 0;
	$totalLifeLost = 0;
	$totalDamagePrevented = 0;
	$numTurnsCount = 0;

	// First, accumulate turn 0 for total values (non-average)
	$totalDamageThreatened += $turnStats[$TurnStats_DamageThreatened];
	$totalDamageDealt += $turnStats[$TurnStats_DamageDealt];
	$totalResourcesUsed += $turnStats[$TurnStats_ResourcesUsed];
	$totalCardsLeft += $turnStats[$TurnStats_CardsLeft];
	$totalDefensiveCards += $turnStats[$TurnStats_CardsPlayedDefense] + $turnStats[$TurnStats_CardsBlocked];
	$totalDamageBlocked += $turnStats[$TurnStats_DamageBlocked];
	$totalLifeGained += $turnStats[$TurnStats_LifeGained];
	$totalDamagePrevented += $turnStats[$TurnStats_DamagePrevented];
	$totalLifeLost += $turnStats[$TurnStats_LifeLost];

	// Skip turn 0 for average calculations
	$start = TurnStatPieces();
	$endIndex = count($turnStats) - TurnStatPieces();
	if($endIndex < $start) $endIndex = $start;

	for($i = $start; $i < $endIndex; $i += TurnStatPieces()) {
		$totalDamageThreatened += $turnStats[$i + $TurnStats_DamageThreatened];
		$totalDamageDealt += $turnStats[$i + $TurnStats_DamageDealt];
		$totalResourcesUsed += $turnStats[$i + $TurnStats_ResourcesUsed];
		$totalCardsLeft += $turnStats[$i + $TurnStats_CardsLeft];
		$totalDefensiveCards += $turnStats[$i + $TurnStats_CardsPlayedDefense] + $turnStats[$i + $TurnStats_CardsBlocked];
		$totalDamageBlocked += $turnStats[$i + $TurnStats_DamageBlocked];
		$totalLifeGained += $turnStats[$i + $TurnStats_LifeGained];
		$totalDamagePrevented += $turnStats[$i + $TurnStats_DamagePrevented];
		$totalLifeLost += $turnStats[$i + $TurnStats_LifeLost];
		$numTurnsCount++;
	}

	if($numTurnsCount == 0) $numTurnsCount = 1;
	$totalOffensiveCards = 4 * $numTurnsCount - $totalDefensiveCards;
	if($totalOffensiveCards == 0) $totalOffensiveCards = 1;

	$deck["totalDamageThreatened"] = $totalDamageThreatened;
	$deck["totalDamageDealt"] = $totalDamageDealt;
	$deck["totalLifeGained"] = $totalLifeGained;
	$deck["totalDamageBlocked"] = $totalDamageBlocked;
	$deck["totalDamagePrevented"] = $totalDamagePrevented;
	$deck["totalLifeLost"] = $totalLifeLost;
	$deck["averageDamageThreatenedPerTurn"] = round(($totalDamageThreatened - $turnStats[$TurnStats_DamageThreatened]) / $numTurnsCount, 2);
	$deck["averageDamageDealtPerTurn"] = round(($totalDamageDealt - $turnStats[$TurnStats_DamageDealt]) / $numTurnsCount, 2);
	$deck["averageDamageThreatenedPerCard"] = round(($totalDamageThreatened - $turnStats[$TurnStats_DamageThreatened]) / $totalOffensiveCards, 2);
	$deck["averageResourcesUsedPerTurn"] = round(($totalResourcesUsed - $turnStats[$TurnStats_ResourcesUsed]) / $numTurnsCount, 2);
	$deck["averageCardsLeftOverPerTurn"] = round(($totalCardsLeft - $turnStats[$TurnStats_CardsLeft]) / $numTurnsCount, 2);
	$deck["averageCombatValuePerTurn"] = round(($totalDamageThreatened - $turnStats[$TurnStats_DamageThreatened] + $totalDamageBlocked - $turnStats[$TurnStats_DamageBlocked]) / $numTurnsCount, 2);
	$deck["averageValuePerTurn"] = round(($totalDamageThreatened - $turnStats[$TurnStats_DamageThreatened] + $totalDamageBlocked - $turnStats[$TurnStats_DamageBlocked] + $totalLifeGained - $turnStats[$TurnStats_LifeGained] + $totalLifeLost - $turnStats[$TurnStats_LifeLost] + $totalDamagePrevented - $turnStats[$TurnStats_DamagePrevented]) / $numTurnsCount, 2);

	// Calculate stats excluding last turn
	$totalDamageThreatened_NoLast = $totalDamageThreatened;
	$totalDamageDealt_NoLast = $totalDamageDealt;
	$totalResourcesUsed_NoLast = $totalResourcesUsed;
	$totalCardsLeft_NoLast = $totalCardsLeft;
	$totalDefensiveCards_NoLast = $totalDefensiveCards;
	$totalDamageBlocked_NoLast = $totalDamageBlocked;
	$totalLifeGained_NoLast = $totalLifeGained;
	$totalDamagePrevented_NoLast = $totalDamagePrevented;
	$totalLifeLost_NoLast = $totalLifeLost;
	$numTurns_NoLast = $numTurnsCount - 1; // Exclude last turn

	// Subtract the last turn from NoLast values
	if($endIndex - TurnStatPieces() >= $start) {
		$lastTurnIndex = $endIndex - TurnStatPieces();
		$totalDamageThreatened_NoLast -= $turnStats[$lastTurnIndex + $TurnStats_DamageThreatened];
		$totalDamageDealt_NoLast -= $turnStats[$lastTurnIndex + $TurnStats_DamageDealt];
		$totalResourcesUsed_NoLast -= $turnStats[$lastTurnIndex + $TurnStats_ResourcesUsed];
		$totalCardsLeft_NoLast -= $turnStats[$lastTurnIndex + $TurnStats_CardsLeft];
		$totalDefensiveCards_NoLast -= $turnStats[$lastTurnIndex + $TurnStats_CardsPlayedDefense] + $turnStats[$lastTurnIndex + $TurnStats_CardsBlocked];
		$totalDamageBlocked_NoLast -= $turnStats[$lastTurnIndex + $TurnStats_DamageBlocked];
		$totalLifeGained_NoLast -= $turnStats[$lastTurnIndex + $TurnStats_LifeGained];
		$totalDamagePrevented_NoLast -= $turnStats[$lastTurnIndex + $TurnStats_DamagePrevented];
		$totalLifeLost_NoLast -= $turnStats[$lastTurnIndex + $TurnStats_LifeLost];
	}

	if($numTurns_NoLast < 1) $numTurns_NoLast = 1;
	$totalOffensiveCards_NoLast = 4 * $numTurns_NoLast - $totalDefensiveCards_NoLast;
	if($totalOffensiveCards_NoLast == 0) $totalOffensiveCards_NoLast = 1;

	$deck["totalDamageThreatened_NoLast"] = $totalDamageThreatened_NoLast;
	$deck["totalDamageDealt_NoLast"] = $totalDamageDealt_NoLast;
	$deck["totalLifeGained_NoLast"] = $totalLifeGained_NoLast;
	$deck["totalDamageBlocked_NoLast"] = $totalDamageBlocked_NoLast;
	$deck["totalDamagePrevented_NoLast"] = $totalDamagePrevented_NoLast;
	$deck["totalLifeLost_NoLast"] = $totalLifeLost_NoLast;
	$deck["averageDamageThreatenedPerTurn_NoLast"] = round(($totalDamageThreatened_NoLast - $turnStats[$TurnStats_DamageThreatened]) / $numTurns_NoLast, 2);
	$deck["averageDamageDealtPerTurn_NoLast"] = round(($totalDamageDealt_NoLast - $turnStats[$TurnStats_DamageDealt]) / $numTurns_NoLast, 2);
	$deck["averageDamageThreatenedPerCard_NoLast"] = round(($totalDamageThreatened_NoLast - $turnStats[$TurnStats_DamageThreatened]) / $totalOffensiveCards_NoLast, 2);
	$deck["averageResourcesUsedPerTurn_NoLast"] = round(($totalResourcesUsed_NoLast - $turnStats[$TurnStats_ResourcesUsed]) / $numTurns_NoLast, 2);
	$deck["averageCardsLeftOverPerTurn_NoLast"] = round(($totalCardsLeft_NoLast - $turnStats[$TurnStats_CardsLeft]) / $numTurns_NoLast, 2);
	$deck["averageCombatValuePerTurn_NoLast"] = round(($totalDamageThreatened_NoLast - $turnStats[$TurnStats_DamageThreatened] + $totalDamageBlocked_NoLast - $turnStats[$TurnStats_DamageBlocked]) / $numTurns_NoLast, 2);
	$deck["averageValuePerTurn_NoLast"] = round(($totalDamageThreatened_NoLast - $turnStats[$TurnStats_DamageThreatened] + $totalDamageBlocked_NoLast - $turnStats[$TurnStats_DamageBlocked] + $totalLifeGained_NoLast - $turnStats[$TurnStats_LifeGained] + $totalLifeLost_NoLast - $turnStats[$TurnStats_LifeLost] + $totalDamagePrevented_NoLast - $turnStats[$TurnStats_DamagePrevented]) / $numTurns_NoLast, 2);
}

function SerializeGameResult($player, $DeckLink, $deckAfterSB, $gameID = "", $opposingHero = "", $gameName = "", $deckbuilderID = "", $includeFullLog=false)
{
	global $winner, $currentTurn, $CardStats_TimesPlayed, $CardStats_TimesBlocked, $CardStats_TimesPitched, $CardStats_TimesHit, $CardStats_TimesCharged, $firstPlayer;
	global $CardStats_TimesKatsuDiscard, $CardStats_TimesDiscarded;
	if($DeckLink != "") {
		$DeckLink = explode("/", $DeckLink);
		$DeckLink = $DeckLink[count($DeckLink) - 1];
	}
	$deckAfterSB = explode("\r\n", $deckAfterSB);
	if(count($deckAfterSB) == 1) return "";
	$character = $deckAfterSB[0];
	$deckAfterSB = $deckAfterSB[1];
	$deck = [];
	if($gameID != "") $deck["gameId"] = $gameID;
	if($gameName != "") $deck["gameName"] = $gameName;
	$deck["deckId"] = $DeckLink;
	$deck["turns"] = intval($currentTurn);
	$deck["result"] = ($player == $winner ? 1 : 0);
	if($winner == "1" || $winner == "2") {
		$deck["winner"] = intval($winner);
	}
	$deck["firstPlayer"] = ($player == $firstPlayer ? 1 : 0);
	if($opposingHero != "") $deck["opposingHero"] = $opposingHero;
	if($deckbuilderID != "") $deck["deckbuilderID"] = $deckbuilderID;
	
	// Add hero information for display
	$characterCards = explode(" ", $character);
	if(count($characterCards) > 0) {
		$yourHeroCardID = $characterCards[0];
		$deck["yourHero"] = $yourHeroCardID;
	}
	
	// Add opponent's hero if provided
	if($opposingHero != "") {
		$deck["opponentHero"] = $opposingHero;
	}
	
	$deck["cardResults"] = [];
	$deck["character"] = [];

	$character = explode(" ", $character);
	$deduplicatedCharacter = [];
	for($i = 0; $i < count($character); ++$i) {
		$card = $character[$i];
		if (array_key_exists($card, $deduplicatedCharacter)) {
			$deduplicatedCharacter[$card]++;
		} else {
			$deduplicatedCharacter[$card] = 1;
		}
	}

	foreach ($deduplicatedCharacter as $card => $numCopies) {
		$cardResult = [
			"cardId" => $card,
			"cardName" => CardName($card),
			"numCopies" => $numCopies,
		];
		array_push($deck["character"], $cardResult);
	}

	$deckAfterSB = explode(" ", $deckAfterSB);
	$deduplicatedDeck = [];
	for($i = 0; $i < count($deckAfterSB); ++$i) {
		$card = $deckAfterSB[$i];
		if (array_key_exists($card, $deduplicatedDeck)) {
			$deduplicatedDeck[$card]++;
		} else {
			$deduplicatedDeck[$card] = 1;
		}
	}

	foreach ($deduplicatedDeck as $card => $numCopies) {
		$cardResult = [
			"cardId" => $card,
			"played" => 0,
			"blocked" => 0,
			"pitched" => 0,
			"hits" => 0,
			"discarded" => 0,
			"charged" => 0,
			"cardName" => CardName($card),
			"pitchValue" => PitchValue($card),
			"numCopies" => $numCopies,
		];
		array_push($deck["cardResults"], $cardResult);
	}

	$cardStats = &GetCardStats($player);
	$deck["tokenResults"] = [];
	$deck["arenaCardResults"] = [];
	for($i = 0; $i < count($cardStats); $i += CardStatPieces()) {
		$found = false;
		for($j = 0; $j < count($deck["cardResults"]); ++$j) {
			if($deck["cardResults"][$j]["cardId"] == $cardStats[$i]) {
				$deck["cardResults"][$j]["played"] = $cardStats[$i + $CardStats_TimesPlayed];
				$deck["cardResults"][$j]["blocked"] = $cardStats[$i + $CardStats_TimesBlocked];
				$deck["cardResults"][$j]["pitched"] = $cardStats[$i + $CardStats_TimesPitched];
				$deck["cardResults"][$j]["hits"] = $cardStats[$i + $CardStats_TimesHit];
				$deck["cardResults"][$j]["charged"] = $cardStats[$i + $CardStats_TimesCharged];
				$deck["cardResults"][$j]["charged"] = $cardStats[$i + $CardStats_TimesKatsuDiscard];
				$deck["cardResults"][$j]["discarded"] = $cardStats[$i + $CardStats_TimesDiscarded];
				$found = true;
				break;
			}
		}
		// If card has stats but wasn't in the decklist, route to arenaCardResults if equipment/weapon, otherwise tokenResults
		if (!$found) {
			$cardType = CardType($cardStats[$i]);
			$cardResult = [
				"cardId" => $cardStats[$i],
				"played" => $cardStats[$i + $CardStats_TimesPlayed],
				"blocked" => $cardStats[$i + $CardStats_TimesBlocked],
				"pitched" => $cardStats[$i + $CardStats_TimesPitched],
				"hits" => $cardStats[$i + $CardStats_TimesHit],
				"discarded" => $cardStats[$i + $CardStats_TimesDiscarded],
				"charged" => $cardStats[$i + $CardStats_TimesCharged],
				"cardName" => CardName($cardStats[$i]),
				"pitchValue" => PitchValue($cardStats[$i]),
				"katsuDiscard" => $cardStats[$i + $CardStats_TimesKatsuDiscard],
			];
			if (DelimStringContains($cardType, "C") || DelimStringContains($cardType, "E") || DelimStringContains($cardType, "W") || DelimStringContains($cardType, "Companion")) {
				array_push($deck["arenaCardResults"], $cardResult);
			} else {
				array_push($deck["tokenResults"], $cardResult);
			}
		}
	}

	$turnStats = &GetTurnStats($player);
	$otherPlayerTurnStats = &GetTurnStats($player == 1 ? 2 : 1);

	// Use helper function to populate turn stats and aggregates (useIntval=false for SerializeGameResult)
	PopulateTurnStatsAndAggregates($deck, $turnStats, $otherPlayerTurnStats, $player, false);
	PopulateAggregateStats($deck, $turnStats);

	if($includeFullLog) { $deck["fullLog"] = IsPatron($player) ? implode("<BR>", explode("\r\n", @file_get_contents("./Games/" . $gameID . "/fullGamelog.txt"))) : ""; }
	
	return json_encode($deck);
}

function SerializeDetailedGameResult($player, $DeckLink, $deckAfterSB, $gameID = "", $opposingHero = "", $gameName = "", $deckbuilderID = "", $playerHero = "", $excludePrivateFields = false)
{
	global $winner, $currentTurn, $CardStats_TimesPlayed, $CardStats_TimesBlocked, $CardStats_TimesPitched, $CardStats_TimesHit, $CardStats_TimesCharged, $firstPlayer;
	global $CardStats_TimesKatsuDiscard, $CardStats_TimesDiscarded;
	if($DeckLink != "") {
		$DeckLink = explode("/", $DeckLink);
		$DeckLink = $DeckLink[count($DeckLink) - 1];
	}
	$deckAfterSB = explode("\r\n", $deckAfterSB);
	if(count($deckAfterSB) == 1) return "";
	$character = $deckAfterSB[0];
	$deckAfterSB = $deckAfterSB[1];
	$deck = [];
	if($gameID != "") $deck["gameId"] = $gameID;
	if($gameName != "") $deck["gameName"] = $gameName;
	$deck["deckId"] = $DeckLink;
	$deck["turns"] = intval($currentTurn);
	$deck["result"] = ($player == $winner ? 1 : 0);
	if($winner == "1" || $winner == "2") $deck["winner"] = intval($winner);
	$deck["firstPlayer"] = ($player == $firstPlayer ? 1 : 0);
	if($opposingHero != "") $deck["opposingHero"] = $opposingHero;
	if($playerHero != "") $deck["playerHero"] = $playerHero;
	if($deckbuilderID != "") $deck["deckbuilderID"] = $deckbuilderID;
	$deck["cardResults"] = [];
	$deck["character"] = [];

	$character = explode(" ", $character);
	$deduplicatedCharacter = [];
	for($i = 0; $i < count($character); ++$i) {
		$card = $character[$i];
		if (array_key_exists($card, $deduplicatedCharacter)) {
			$deduplicatedCharacter[$card]++;
		} else {
			$deduplicatedCharacter[$card] = 1;
		}
	}

	foreach ($deduplicatedCharacter as $card => $numCopies) {
		$cardResult = [
			"cardId" => $card,
			"cardName" => CardName($card),
			"numCopies" => $numCopies,
		];
		array_push($deck["character"], $cardResult);
	}

	$deckAfterSB = explode(" ", $deckAfterSB);
	$deduplicatedDeck = [];
	for($i = 0; $i < count($deckAfterSB); ++$i) {
		$card = $deckAfterSB[$i];
		if (array_key_exists($card, $deduplicatedDeck)) {
			$deduplicatedDeck[$card]++;
		} else {
			$deduplicatedDeck[$card] = 1;
		}
	}

	foreach ($deduplicatedDeck as $card => $numCopies) {
		$cardResult = [
			"cardId" => $card,
			"played" => 0,
			"blocked" => 0,
			"pitched" => 0,
			"hits" => 0,
			"discarded" => 0,
			"charged" => 0,
			"cardName" => CardName($card),
			"pitchValue" => PitchValue($card),
			"numCopies" => $numCopies,
		];
		array_push($deck["cardResults"], $cardResult);
	}

	$cardStats = &GetCardStats($player);
	$deck["tokenResults"] = [];
	$deck["arenaCardResults"] = [];
	for($i = 0; $i < count($cardStats); $i += CardStatPieces()) {
		$found = false;
		for($j = 0; $j < count($deck["cardResults"]); ++$j) {
			if($deck["cardResults"][$j]["cardId"] == $cardStats[$i]) {
				$deck["cardResults"][$j]["played"] = intval($cardStats[$i + $CardStats_TimesPlayed]);
				$deck["cardResults"][$j]["blocked"] = intval($cardStats[$i + $CardStats_TimesBlocked]);
				$deck["cardResults"][$j]["pitched"] = intval($cardStats[$i + $CardStats_TimesPitched]);
				$deck["cardResults"][$j]["hits"] = intval($cardStats[$i + $CardStats_TimesHit]);
				$deck["cardResults"][$j]["charged"] = intval($cardStats[$i + $CardStats_TimesCharged]);
				$deck["cardResults"][$j]["charged"] = intval($cardStats[$i + $CardStats_TimesKatsuDiscard]);
				$deck["cardResults"][$j]["discarded"] = intval($cardStats[$i + $CardStats_TimesDiscarded]);
				$found = true;
				break;
			}
		}
		// If card has stats but wasn't in the decklist, route to arenaCardResults if equipment/weapon, otherwise tokenResults
		if (!$found) {
			$cardType = CardType($cardStats[$i]);
			$cardResult = [
				"cardId" => $cardStats[$i],
				"played" => intval($cardStats[$i + $CardStats_TimesPlayed]),
				"blocked" => intval($cardStats[$i + $CardStats_TimesBlocked]),
				"pitched" => intval($cardStats[$i + $CardStats_TimesPitched]),
				"hits" => intval($cardStats[$i + $CardStats_TimesHit]),
				"discarded" => intval($cardStats[$i + $CardStats_TimesDiscarded]),
				"charged" => intval($cardStats[$i + $CardStats_TimesCharged]),
				"cardName" => CardName($cardStats[$i]),
				"pitchValue" => PitchValue($cardStats[$i]),
				"katsuDiscard" => intval($cardStats[$i + $CardStats_TimesKatsuDiscard]),
			];
			if (DelimStringContains($cardType, "C") || DelimStringContains($cardType, "E") || DelimStringContains($cardType, "W") || DelimStringContains($cardType, "Companion")) {
				array_push($deck["arenaCardResults"], $cardResult);
			} else {
				array_push($deck["tokenResults"], $cardResult);
			}
		}
	}
	$turnStats = &GetTurnStats($player);
	$otherPlayerTurnStats = &GetTurnStats($player == 1 ? 2 : 1);

	PopulateTurnStatsAndAggregates($deck, $turnStats, $otherPlayerTurnStats, $player, true);
	PopulateAggregateStats($deck, $turnStats);

	// Exclude private fields if stats are disabled
	if ($excludePrivateFields) {
		unset($deck["deckbuilderID"]);
		unset($deck["cardResults"]);
		unset($deck["character"]);
		unset($deck["yourTime"]);
		unset($deck["turnResults"]);
		unset($deck["totalDamageThreatened"]);
		unset($deck["totalDamageDealt"]);
		unset($deck["totalLifeGained"]);
		unset($deck["totalDamageBlocked"]);
		unset($deck["totalDamagePrevented"]);
		unset($deck["totalLifeLost"]);
		unset($deck["averageDamageThreatenedPerTurn"]);
		unset($deck["averageDamageDealtPerTurn"]);
		unset($deck["averageDamageThreatenedPerCard"]);
		unset($deck["averageResourcesUsedPerTurn"]);
		unset($deck["averageCardsLeftOverPerTurn"]);
		unset($deck["averageCombatValuePerTurn"]);
		unset($deck["averageValuePerTurn"]);
		unset($deck["totalDamageThreatened_NoLast"]);
		unset($deck["totalDamageDealt_NoLast"]);
		unset($deck["totalLifeGained_NoLast"]);
		unset($deck["totalDamageBlocked_NoLast"]);
		unset($deck["totalDamagePrevented_NoLast"]);
		unset($deck["totalLifeLost_NoLast"]);
		unset($deck["averageDamageThreatenedPerTurn_NoLast"]);
		unset($deck["averageDamageDealtPerTurn_NoLast"]);
		unset($deck["averageDamageThreatenedPerCard_NoLast"]);
		unset($deck["averageResourcesUsedPerTurn_NoLast"]);
		unset($deck["averageCardsLeftOverPerTurn_NoLast"]);
		unset($deck["averageCombatValuePerTurn_NoLast"]);
		unset($deck["averageValuePerTurn_NoLast"]);
	}

	return json_encode($deck);
}

function SavePatreonTokens($accessToken, $refreshToken)
{
	if(!isset($_SESSION["userid"])) return;
	$userID = $_SESSION["userid"];
	$conn = GetDBConnection();
	$sql = "UPDATE users SET patreonAccessToken=?, patreonRefreshToken=? WHERE usersid=?";
	$stmt = mysqli_stmt_init($conn);
	if(mysqli_stmt_prepare($stmt, $sql)) {
		mysqli_stmt_bind_param($stmt, "sss", $accessToken, $refreshToken, $userID);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	}
	mysqli_close($conn);
}

function AwardBadge($userID, $badgeID)
{
	if($userID == "") return "";
	$conn = GetDBConnection();
	$sql = "insert into playerbadge (playerId, badgeId, intVariable) values (?, ?, 1) ON DUPLICATE KEY UPDATE intVariable = intVariable + 1;";
	$stmt = mysqli_stmt_init($conn);
	if(mysqli_stmt_prepare($stmt, $sql)) {
		mysqli_stmt_bind_param($stmt, "ss", $userID, $badgeID);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	}
	mysqli_close($conn);
}

function SaveSetting($playerId, $settingNumber, $value)
{
	if($playerId == "" || $playerId == "-" || !is_numeric($playerId)) return;
	$conn = GetDBConnection();
	if (!$conn) {
		return;
	}
	$sql = "insert into savedsettings (playerId, settingNumber, settingValue) values (?, ?, ?) ON DUPLICATE KEY UPDATE settingValue = VALUES(settingValue);";
	$stmt = mysqli_stmt_init($conn);
	if(mysqli_stmt_prepare($stmt, $sql)) {
		mysqli_stmt_bind_param($stmt, "sss", $playerId, $settingNumber, $value);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	}
	mysqli_close($conn);
}

function LoadSavedSettings($playerId)
{
	if($playerId == "") {
		return [];
	}
	$output = [];
	$conn = GetDBConnection();
	if (!$conn) return [];
	$sql = "select settingNumber,settingValue from `savedsettings` where playerId=(?)";
	$stmt = mysqli_stmt_init($conn);
	if(mysqli_stmt_prepare($stmt, $sql)) {
		mysqli_stmt_bind_param($stmt, "s", $playerId);
		mysqli_stmt_execute($stmt);
		$data = mysqli_stmt_get_result($stmt);
		while($row = mysqli_fetch_array($data, MYSQLI_NUM)) {
			array_push($output, $row[0]);
			array_push($output, $row[1]);
		}
		mysqli_stmt_close($stmt);
	}
	mysqli_close($conn);
	return $output;
}

function SendEmail($userEmail, $url)
{
	include "../APIKeys/APIKeys.php";
	require '../vendor/autoload.php';

	$email = new Mail();
	$email->setFrom("noreply@sendgrid.net", "Talishar");
	$email->addTo($userEmail);
	$email->addContent(
		"text/html",
		"
        <p>
          We recieved a password reset request. The link to reset your password is below.
          If you did not make this request, you can ignore this email
        </p>
        <p>
          Here is your password reset link: </br>
          <a href=$url>Password Reset</a>
        </p>
      "
	);
	$sendgrid = new \SendGrid($sendgridKey);
	try {
		$response = $sendgrid->send($email);
		print $response->statusCode() . "\n";
		print_r($response->headers());
		print $response->body() . "\n";
	} catch (Exception $e) {
		echo 'Caught exception: ' . $e->getMessage() . "\n";
	}
}

function SendEmailAPI($userEmail, $url)
{
	include "../APIKeys/APIKeys.php";
	
	// Check if SendGrid API key is configured
	if (empty($sendgridKey)) {
		return;
	}
	
	// Check if vendor/autoload.php exists (SendGrid installed)
	if (!file_exists('../vendor/autoload.php')) {
		error_log("SendGrid dependencies not installed. Reset link logged to file for testing.");
		return;
	}
	
	require '../vendor/autoload.php';

	// Use verified SendGrid domain (em5232.talishar.net is verified in your SendGrid account)
	$fromEmail = "noreply@em5232.talishar.net";
	$fromName = "Talishar";

	$email = new Mail();
	$email->setFrom($fromEmail, $fromName);
	$email->setSubject("Talishar Password Reset Link");
	$email->addTo($userEmail);
	$email->addContent(
		"text/html",
		"<html><body style='font-family: Arial, sans-serif;'>
        <h2>Password Reset Request</h2>
        <p>We received a password reset request for your Talishar account.</p>
        <p>If you did not make this request, you can safely ignore this email.</p>
        <p style='margin: 20px 0;'><a href='" . htmlspecialchars($url) . "' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; display: inline-block;'>Reset Password</a></p>
        <p>This link expires in 30 minutes.</p>
        </body></html>"
	);
	
	try {
		$sendgrid = new \SendGrid($sendgridKey);
		
		// Attempt 1: Try standard SendGrid send
		$response = $sendgrid->send($email);
		$statusCode = $response->statusCode();
		
		if ($statusCode === 202) {
			error_log("SendGrid: Password reset email sent to $userEmail (Status: 202)");
			return;
		} else {
			error_log("SendGrid warning: Status $statusCode for $userEmail");
			error_log("SendGrid response: " . $response->body());
		}
	} catch (Exception $e) {
		// Attempt 2: Try with direct cURL call and SSL verification disabled
		error_log("SendGrid initial attempt failed: " . $e->getMessage());
		SendEmailAPICurlFallback($userEmail, $url, $email, $sendgridKey);
	}
}

/**
 * Fallback email sending using direct cURL with SSL verification disabled
 * Used when SendGrid PHP SDK fails due to SSL certificate issues (common in Docker/Windows)
 */
function SendEmailAPICurlFallback($userEmail, $url, $email, $sendgridKey)
{
	try {
		error_log("Attempting SendGrid email with cURL fallback for $userEmail");
		
		// Build raw JSON for SendGrid API manually
		$emailData = [
			"personalizations" => [
				[
					"to" => [
						["email" => $userEmail]
					]
				]
			],
			"from" => [
				"email" => "noreply@em5232.talishar.net",
				"name" => "Talishar"
			],
			"subject" => "Talishar Password Reset Link",
			"content" => [
				[
					"type" => "text/html",
					"value" => "<html><body style='font-family: Arial, sans-serif;'>
					<h2>Password Reset Request</h2>
					<p>We received a password reset request for your Talishar account.</p>
					<p>If you did not make this request, you can safely ignore this email.</p>
					<p style='margin: 20px 0;'><a href='" . htmlspecialchars($url) . "' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; display: inline-block;'>Reset Password</a></p>
					<p>This link expires in 30 minutes.</p>
					</body></html>"
				]
			]
		];
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://api.sendgrid.com/v3/mail/send');
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($emailData));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		
		// CRITICAL FIX: Disable SSL verification for problematic environments
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'Authorization: Bearer ' . $sendgridKey,
			'Content-Type: application/json'
		]);
		
		$response = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$curlError = curl_error($ch);
		curl_close($ch);
		
		if ($httpCode === 202) {
			error_log("SendGrid cURL fallback: Email accepted for $userEmail (Status: 202)");
		} elseif ($curlError) {
			error_log("SendGrid cURL fallback error: $curlError (HTTP $httpCode) for $userEmail");
		} else {
			error_log("SendGrid cURL fallback response: HTTP $httpCode - $response for $userEmail");
		}
		
	} catch (Exception $e) {
		error_log("SendGrid cURL fallback exception: " . $e->getMessage() . " for $userEmail");
	}
}

function BanPlayer($uid)
{
	$conn = GetDBConnection();
	$sql = "UPDATE users SET isBanned = true WHERE usersUid = ?";
	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $sql)) {
		mysqli_stmt_bind_param($stmt, "s", $uid);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	}
}

if (!function_exists('GenerateGameGUID')) {
	function GenerateGameGUID()
	{
		$data = random_bytes(16);
		$data[6] = chr(ord($data[6]) & 0x0f | 0x40);
		$data[8] = chr(ord($data[8]) & 0x3f | 0x80);
		return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
	}
}
