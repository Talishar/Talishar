<?php

require_once __DIR__ . '/../Assets/patreon-php-master/src/PatreonLibraries.php';

use SendGrid\Mail\Mail;

if (!function_exists('IsDevEnvironment')) {
  function IsDevEnvironment() {
    $domain = getenv("DOMAIN");
    if ($domain === "localhost") return true;
    if ($_SERVER['SERVER_NAME'] === 'localhost' || $_SERVER['SERVER_NAME'] === '127.0.0.1') return true;
    return false;
  }
}

// Check for empty input signup
function emptyInputSignup($username, $email, $pwd, $pwdRepeat)
{
	return empty($username) || empty($email) || empty($pwd) || empty($pwdRepeat);
}

// Check invalid username
function invalidUid($username)
{
	return !ctype_alnum($username);
}

// Check invalid email
function invalidEmail($email)
{
	return !filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Check if passwords matches
function pwdMatch($pwd, $pwdrepeat)
{
	return $pwd !== $pwdrepeat;
}

// Check if username is in database, if so then return data
// Also matches display names so a new signup can't take a name another player displays as
function uidExists($conn, $username)
{
	$conn = GetDBConnection(DBL_UID_EXISTS);
	if (!$conn) {
		header("location: ../Signup.php?error=db_unavailable");
		exit();
	}
	$sql = "SELECT * FROM users WHERE usersUid = ? OR displayName = ?;";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt, $sql)) {
		header("location: ../Signup.php?error=stmtfailed");
		exit();
	}

	mysqli_stmt_bind_param($stmt, "ss", $username, $username);
	mysqli_stmt_execute($stmt);

	// "Get result" returns the results from a prepared statement
	$resultData = mysqli_stmt_get_result($stmt);

	if ($row = mysqli_fetch_assoc($resultData)) {
		mysqli_free_result($resultData);
		return $row;
	}
	else {
		mysqli_free_result($resultData);
		$result = false;
		return $result;
	}
}

// Check if email is in database, if so then return data
function emailExists($conn, $email)
{
	$conn = GetDBConnection(DBL_EMAIL_EXISTS);
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
		mysqli_free_result($resultData);
		return $row;
	}
	else {
		mysqli_free_result($resultData);
		$result = false;
		return $result;
	}
}

function CreateUserAPI($conn, $username, $email, $pwd)
{
	$conn = GetDBConnection(DBL_CREATE_USER_API);
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
	$newUserId = mysqli_insert_id($conn);
	mysqli_stmt_close($stmt);
	mysqli_close($conn);
	return $newUserId > 0 ? $newUserId : false;
}

function loginFromCookie()
{
    if (isset($_COOKIE["rememberMeToken"])) {
        $token = $_COOKIE["rememberMeToken"];
        $conn = GetDBConnection(DBL_LOGIN_FROM_COOKIE);
        if (!$conn) {
            return; // Silently fail if database unavailable
        }
        $sql = "SELECT usersId, usersUid, usersEmail, patreonAccessToken, patreonRefreshToken, patreonEnum, isBanned, lastGameName, lastPlayerId, lastAuthKey, metafyID, rust_counters, displayName FROM users WHERE rememberMeToken=?";
        $stmt = mysqli_stmt_init($conn);
        
        if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $token);
            mysqli_stmt_execute($stmt);
            $data = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_array($data, MYSQLI_NUM);
            mysqli_free_result($data);  // FREE RESULT BEFORE CLOSING STATEMENT
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
                $_SESSION["metafyID"] = $row[10] ?? "";
                $_SESSION["rust_counters"] = intval($row[11] ?? 0);
                $_SESSION["displayName"] = $row[12] ?? "";
                LogIPHistory($row[0]);
                try {
                    PatreonLogin($patreonAccessToken);
                } catch (\Throwable $e) {
                    error_log("functions.inc.php: PatreonLogin threw: " . $e->getMessage());
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
                unset($_SESSION["metafyID"]);
                unset($_SESSION["rust_counters"]);
                unset($_SESSION["displayName"]);
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
	$conn = GetDBConnection(DBL_STORE_FABRARY_ID);
	$sql = "UPDATE users SET fabraryId=? WHERE usersId=?";
	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $sql)) {
		mysqli_stmt_bind_param($stmt, "ss", $fabraryId, $uid);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	}
}

function StoreLastGameInfo($uid, $gameName, $playerID, $authKey)
{
	$conn = GetDBConnection(DBL_STORE_LAST_GAME_INFO);
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

function GetLastGameInfo($uid)
{
	$conn = GetDBConnection(DBL_GET_LAST_GAME_INFO);
	if (!$conn) {
		return null;
	}
	$result = null;
	$sql = "SELECT lastGameName, lastPlayerId, lastAuthKey FROM users WHERE usersId=?";
	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $sql)) {
		mysqli_stmt_bind_param($stmt, "s", $uid);
		mysqli_stmt_execute($stmt);
		$data = mysqli_stmt_get_result($stmt);
		$row = mysqli_fetch_assoc($data);
		mysqli_free_result($data);
		mysqli_stmt_close($stmt);
		if ($row != null && intval($row["lastGameName"]) > 0 && !empty($row["lastAuthKey"])) {
			$result = $row;
		}
	}
	mysqli_close($conn);
	return $result;
}

function ShouldSkipRustCountersForSupporterGame($p1IsPatron, $p2IsPatron)
{
	return $p1IsPatron === "1" || $p2IsPatron === "1";
}

function ShouldSkipRustCountersForContributors()
{
	global $p1uid, $p2uid;
	include_once __DIR__ . '/ModeratorList.inc.php';
	return IsUserContributor($p1uid) || IsUserContributor($p2uid);
}

function AddRustCountersForGameStart($p1id, $p1IsPatron, $p1IsAI, $p2id, $p2IsPatron, $p2IsAI)
{
	if (IsDevEnvironment()) return false;
	if (ShouldSkipRustCountersForContributors()) return true;
	if (ShouldSkipRustCountersForSupporterGame($p1IsPatron, $p2IsPatron)) return true;
	$conn = GetDBConnection(DBL_ADD_RUST_COUNTERS_GAME_START);
	if (!$conn) {
		return false;
	}

	$sql = "UPDATE users SET rust_counters = COALESCE(rust_counters, 0) + 1 WHERE usersId=?";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt, $sql)) {
		mysqli_close($conn);
		return false;
	}

	$players = [
		[
			"userId" => intval($p1id),
			"isPatron" => ($p1IsPatron === "1"),
			"isAI" => ($p1IsAI === "1"),
		],
		[
			"userId" => intval($p2id),
			"isPatron" => ($p2IsPatron === "1"),
			"isAI" => ($p2IsAI === "1"),
		],
	];

	foreach ($players as $player) {
		if ($player["isAI"] || $player["isPatron"] || $player["userId"] <= 0 || $player["userId"] == 465 || $player["userId"] == 9474 || $player["userId"] == 203) {
			continue;
		}

		mysqli_stmt_bind_param($stmt, "i", $player["userId"]);
		mysqli_stmt_execute($stmt);
	}

	mysqli_stmt_close($stmt);
	mysqli_close($conn);
	return true;
}

function GetDeckBuilderId($uid, $decklink)
{
	$conn = GetDBConnection(DBL_GET_DECK_BUILDER_ID);
	if (!$conn) {
        return "";
	}
	$sql = "SELECT fabraryId,fabdbId FROM users WHERE usersId=?";
	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $sql)) {
		mysqli_stmt_bind_param($stmt, "s", $uid);
		mysqli_stmt_execute($stmt);
		$data = mysqli_stmt_get_result($stmt);
		$row = mysqli_fetch_array($data, MYSQLI_NUM);
		mysqli_free_result($data);  // FREE RESULT BEFORE CLOSING STATEMENT
		mysqli_stmt_close($stmt);
	}
	mysqli_close($conn);
	$dbId = "";
	if (count($row) == 0) return "";
	if (str_contains($decklink, "fabrary")) $dbId = $row[0];
	else if (str_contains($decklink, "fabdb")) $dbId = $row[1];
	else if (str_contains($decklink, "fabbazaar")) {
		if (preg_match('/fabbazaar[^\/]*\/decks\/([a-zA-Z0-9_-]+)/', $decklink, $matches)) {
			$dbId = $matches[1];
		}
	}
	if ($dbId == "NULL") $dbId = "";
	return $dbId;
}

function addFavoriteDeck($userID, $decklink, $deckName, $heroID, $format = "")
{
	$conn = GetDBConnection(DBL_ADD_FAVORITE_DECK);
	$deckName = str_replace(['"', "'"], '', $deckName);
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
	$conn = GetDBConnection(DBL_LOAD_FAVORITE_DECKS);
	if (!$conn) return [];
	$sql = "SELECT decklink, name, hero, format, cardBack, playmat, altArtsCustomized from favoritedeck where usersId=?";
	$stmt = mysqli_stmt_init($conn);
	$output = [];
	if (mysqli_stmt_prepare($stmt, $sql)) {
		mysqli_stmt_bind_param($stmt, "s", $userID);
		mysqli_stmt_execute($stmt);
		$data = mysqli_stmt_get_result($stmt);
		while ($row = mysqli_fetch_array($data, MYSQLI_NUM)) {
			for ($i = 0; $i < 7; ++$i) $output[] = $row[$i];
		}
		mysqli_free_result($data);  // FREE RESULT BEFORE CLOSING STATEMENT
		mysqli_stmt_close($stmt);
	}
	mysqli_close($conn);
	return $output;
}

function FetchDeckFromDeckbuilder($decklink)
{
	global $FaBraryKey;

	$curl = curl_init();
	$isFaBDB = str_contains($decklink, "fabdb");
	$isFaBMeta = str_contains($decklink, "fabmeta") && !str_contains($decklink, "fabtcgmeta");

	if ($isFaBDB) {
		$decklinkArr = explode("/", $decklink);
		$slug = $decklinkArr[count($decklinkArr) - 1];
		$apiLink = "https://api.fabdb.net/decks/" . $slug;
	} else if (str_contains($decklink, "fabrary")) {
		$headers = [
			"x-api-key: " . $FaBraryKey,
			"Content-Type: application/json",
		];
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		// Extract slug: https://fabrary.net/decks/SLUG or https://fabrary.net/decks/SLUG?matchupId=...
		$urlWithoutQuery = explode("?", $decklink)[0];
		$decklinkArr = explode("/", $urlWithoutQuery);
		$slug = $decklinkArr[count($decklinkArr) - 1];
		$apiLink = "https://atofkpq0x8.execute-api.us-east-2.amazonaws.com/prod/v1/decks/" . $slug;
	} else {
		$decklinkArr = explode("/", $decklink);
		$slug = $decklinkArr[count($decklinkArr) - 1];
		$apiLink = "https://api.fabmeta.net/deck/" . $slug;
	}

	curl_setopt($curl, CURLOPT_URL, $apiLink);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$apiDeck = curl_exec($curl);
	$apiInfo = curl_getinfo($curl);
	curl_close($curl);

	if ($apiDeck === FALSE) return null;

	$result = new stdClass();
	$result->deckObj = json_decode($apiDeck);
	$result->isFaBDB = $isFaBDB;
	$result->isFaBMeta = $isFaBMeta;
	$result->httpCode = $apiInfo['http_code'];
	return $result;
}

function ResolveDeckCardIds($deckObj, $isFaBDB, $isFaBMeta)
{
	$cardIds = [];
	$cards = isset($deckObj->{'cards'}) ? $deckObj->{'cards'} : [];

	if (!is_array($cards)) return $cardIds;

	foreach ($cards as $card) {
		$cardID = "";
		if ($isFaBDB) {
			if (isset($card->{'printings'}[0]->{'sku'}->{'sku'})) {
				$setID = explode("-", $card->{'printings'}[0]->{'sku'}->{'sku'})[0];
				$internalID = GeneratedSetIDtoCardID($setID);
				$cardID = !empty($internalID) ? $internalID : $setID;
			}
		} else if ($isFaBMeta) {
			$cardID = $card->{'identifier'} ?? "";
		} else if (isset($card->{'identifier'})) {
			$cardID = str_replace("-", "_", $card->{'identifier'});
		} else if (isset($card->{'cardIdentifier'})) {
			$cardID = $card->{'cardIdentifier'};
		}

		if (empty($cardID)) continue;
		if (!in_array($cardID, $cardIds)) $cardIds[] = $cardID;
	}

	return $cardIds;
}

function GetDemiHeroForms($cardID)
{
	$arakniAgents = [
		"arakni_black_widow",
		"arakni_funnel_web",
		"arakni_orb_weaver",
		"arakni_redback",
		"arakni_tarantula",
		"arakni_trap_door"
	];
	return match ($cardID) {
		"arakni_marionette", "arakni_web_of_deceit" => $arakniAgents,
		default => []
	};
}

function ConvertDeck($deck) {
	$lines = explode("\n", $deck);
	$convertedLines = [];
	foreach ($lines as $line) {
		$cards = explode(" ", $line);
		$convertedCards = [];
		foreach ($cards as $card) {
			$convertedCards[] = SetID($card);
		}
		$convertedLines[] = implode(" ", $convertedCards);
	}
	return implode("\n", $convertedLines);
}

//Challenge ID 1 = sigil of solace blue
//Challenge ID 2 = Talishar no dash
//Challenge ID 3 = Moon Wish
function logCompletedGameStats($conceded = false)
{
	global $winner, $currentTurn, $gameName; //gameName is assumed by ParseGamefile.php
	global $p1id, $p2id, $p1IsChallengeActive, $p2IsChallengeActive, $p1DeckLink, $p2DeckLink, $firstPlayer;
	global $p1deckbuilderID, $p2deckbuilderID, $gameGUID;
	global $p2IsAI;
	if ($winner == 0) return;
	if ($p2IsAI == "1") return; // Don't file results for AI games

	$loser = ($winner == 1 ? 2 : 1);
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

	$p1Deck = ($winner == 1 ? $winnerDeck : $loserDeck);
	$p2Deck = ($winner == 2 ? $winnerDeck : $loserDeck);
	$p1Hero = ($winner == 1 ? $winHero[0] : $loseHero[0]);
	$p2Hero = ($winner == 2 ? $winHero[0] : $loseHero[0]);
	$p1FabraryDisabled = AreStatsDisabled(1);
	$p2FabraryDisabled = AreStatsDisabled(2);
	$p1StatsDisabled = AreStatsDisabled(1) || AreGlobalStatsDisabled(1);
	$p2StatsDisabled = AreStatsDisabled(2) || AreGlobalStatsDisabled(2);

	$gameResultID = $gameName;

	// Pre-compute shared data to avoid redundant work
	$gameCacheArr = ReadCacheArray(intval($gameName));
	$format = $gameCacheArr[12] ?? "";
	if ($format == 7 || $format == 6) return; // Don't send stats for draft (7) or sealed (6) formats
	$isPublic = (($gameCacheArr[8] ?? "") === "1"); 
	$hashedP1Deck = "-";
	$hashedP2Deck = "-";
	$detailedResult1Json = SerializeDetailedGameResult(1, $hashedP1Deck, $p1Deck, $gameResultID, $p2Hero, $gameName, $p1deckbuilderID, $p1Hero, $p1StatsDisabled);
	$detailedResult2Json = SerializeDetailedGameResult(2, $hashedP2Deck, $p2Deck, $gameResultID, $p1Hero, $gameName, $p2deckbuilderID, $p2Hero, $p2StatsDisabled);

	// Collect curl handles for parallel execution
	$curlHandles = [];

	if (!$p1FabraryDisabled || !$p2FabraryDisabled) {
		$ch = PrepareFabraryRequest(
			$gameResultID,
			$p1FabraryDisabled ? "-" : $p1DeckLink,
			$p1Deck, $p1Hero, $p1deckbuilderID,
			$p2FabraryDisabled ? "-" : $p2DeckLink,
			$p2Deck, $p2Hero, $p2deckbuilderID,
			$format, $gameGUID, $conceded, $isPublic
		);
		if ($ch) $curlHandles[] = $ch;
	}

	$ch = PrepareFaBInsightsRequest($gameResultID, $detailedResult1Json, $detailedResult2Json, $format, $gameGUID, $conceded, $countWinnerDeck, $countLoserDeck, $isPublic);
	if ($ch) $curlHandles[] = $ch;

	$bazaarHandle = PrepareFaBBazaarRequest($gameResultID, $p1DeckLink, $p2DeckLink, $p1deckbuilderID, $p2deckbuilderID, $detailedResult1Json, $detailedResult2Json, $format, $gameGUID, $conceded, $countWinnerDeck, $countLoserDeck, $isPublic);
	$wasFaBBazaarResultsSent = ($bazaarHandle !== null);
	if ($bazaarHandle) $curlHandles[] = $bazaarHandle;

	// Execute all API requests in parallel instead of sequentially
	if (!empty($curlHandles)) {
		executeParallelCurlRequests($curlHandles);
	}

	if (!$p1FabraryDisabled && !$p2FabraryDisabled)  $fabraryDesc = "<b>Fabrary</b>";
	elseif (!$p1FabraryDisabled)                     $fabraryDesc = "<b>Fabrary</b> (Player 1 only)";
	elseif (!$p2FabraryDisabled)                     $fabraryDesc = "<b>Fabrary</b> (Player 2 only)";
	else                                             $fabraryDesc = null;

	$otherSites = "<b>FaB Insights</b>";
	if ($wasFaBBazaarResultsSent) $otherSites .= " and <b>FaBBazaar</b>";
	if (!$p1StatsDisabled && !$p2StatsDisabled)      $otherSites .= "";
	elseif (!$p1StatsDisabled)                       $otherSites .= " (Player 1 only)";
	elseif (!$p2StatsDisabled)                       $otherSites .= " (Player 2 only)";
	else                                             $otherSites = null;

	if ($fabraryDesc !== null && $otherSites !== null)
		WriteLog("📊 Sending game result to $fabraryDesc and $otherSites", highlight:true, highlightColor:"green");
	elseif ($fabraryDesc !== null)
		WriteLog("📊 Sending game result to $fabraryDesc", highlight:true, highlightColor:"green");
	elseif ($otherSites !== null)
		WriteLog("📊 Sending game stats to $otherSites", highlight:true, highlightColor:"green");
	else
		WriteLog("No game stats sent as both players have disabled stats", highlight:true);
}

function PrepareFabraryRequest($gameID, $p1Decklink, $p1Deck, $p1Hero, $p1deckbuilderID, $p2Decklink, $p2Deck, $p2Hero, $p2deckbuilderID, $format, $gameGUID = "", $conceded = false, $isPublic = true)
{
	global $FaBraryKey, $gameName;
	$url = "https://atofkpq0x8.execute-api.us-east-2.amazonaws.com/prod/v1/results";
	$payloadArr = [];
	$payloadArr['gameID'] = $gameID;
	$payloadArr['gameName'] = $gameName;
	$payloadArr['deck1'] = json_decode(SerializeGameResult(1, $p1Decklink, $p1Deck, $gameID, $p2Hero, $gameName, $p1deckbuilderID));
	$payloadArr['deck2'] = json_decode(SerializeGameResult(2, $p2Decklink, $p2Deck, $gameID, $p1Hero, $gameName, $p2deckbuilderID));
	$payloadArr["format"] = $format;
	$payloadArr['gameGUID'] = $gameGUID;
	$payloadArr['conceded'] = $conceded;
	$payloadArr['isPublic'] = $isPublic;

	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payloadArr));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
	curl_setopt($ch, CURLOPT_TIMEOUT, 5);
	curl_setopt($ch, CURLOPT_HTTPHEADER, [
		"x-api-key: " . $FaBraryKey,
		"Content-Type: application/json",
	]);
	return $ch;
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

function PrepareFaBInsightsRequest($gameID, $detailedResult1Json, $detailedResult2Json, $format, $gameGUID = "", $conceded = false, $countWinnerDeck = 0, $countLoserDeck = 0, $isPublic = true)
{
	global $FaBInsightsKey, $gameName, $p1uid, $p2uid, $playerHashSalt;

	$hashedP1Name = HashPlayerName($p1uid, $playerHashSalt);
	$hashedP2Name = HashPlayerName($p2uid, $playerHashSalt);

	$url = "https://fab-insights.azurewebsites.net/api/send_results";

	$payloadArr = [];
	$payloadArr['gameID'] = $gameID;
	$payloadArr['gameName'] = $gameName;
	$payloadArr['player1Name'] = $hashedP1Name;
	$payloadArr['player2Name'] = $hashedP2Name;
	$payloadArr['deck1'] = json_decode($detailedResult1Json);
	$payloadArr['deck2'] = json_decode($detailedResult2Json);
	$payloadArr["format"] = $format;
	$payloadArr['gameGUID'] = $gameGUID;
	$payloadArr['conceded'] = $conceded;
	$payloadArr['countWinnerDeck'] = $countWinnerDeck;
	$payloadArr['countLoserDeck'] = $countLoserDeck;
	$payloadArr['isPublic'] = $isPublic;

	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payloadArr));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
	curl_setopt($ch, CURLOPT_TIMEOUT, 5);
	curl_setopt($ch, CURLOPT_HTTPHEADER, [
		"Content-Type: application/json",
		"x-functions-key: " . $FaBInsightsKey
	]);
	return $ch;
}

function PrepareFaBBazaarRequest($gameID, $p1DeckLink, $p2DeckLink, $p1deckbuilderID, $p2deckbuilderID, $detailedResult1Json, $detailedResult2Json, $format, $gameGUID = "", $conceded = false, $countWinnerDeck = 0, $countLoserDeck = 0, $isPublic = true)
{
	global $gameName, $FaBBazaarKey;

	$deckId = (str_contains($p1DeckLink, "fabbazaar") ? $p1deckbuilderID : null)
		?: (str_contains($p2DeckLink, "fabbazaar") ? $p2deckbuilderID : null);
	if (empty($deckId)) return null; // Only send if a FaBBazaar deck is used

	$p1TurnLog = &GetCardTurnLog(1);
	$p2TurnLog = &GetCardTurnLog(2);

	$payloadArr = [];
	$payloadArr['gameID'] = $gameID;
	$payloadArr['gameName'] = $gameName;
	$payloadArr['deck1'] = json_decode($detailedResult1Json);
	$payloadArr['deck1']->turnLog = $p1TurnLog;
	$payloadArr['deck2'] = json_decode($detailedResult2Json);
	$payloadArr['deck2']->turnLog = $p2TurnLog;
	$payloadArr['format'] = $format;
	$payloadArr['gameGUID'] = $gameGUID;
	$payloadArr['conceded'] = $conceded;
	$payloadArr['countWinnerDeck'] = $countWinnerDeck;
	$payloadArr['countLoserDeck'] = $countLoserDeck;
	$payloadArr['isPublic'] = $isPublic;

	$url = "https://fabbazaar.app/api/decks/" . urlencode($deckId) . "/talishar";

	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payloadArr));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
	curl_setopt($ch, CURLOPT_TIMEOUT, 5);
	curl_setopt($ch, CURLOPT_HTTPHEADER, [
		"Content-Type: application/json",
		"x-api-key: " . $FaBBazaarKey,
		"User-Agent: Talishar",
	]);
	return $ch;
}

// Execute multiple curl handles in parallel using curl_multi
function executeParallelCurlRequests($handles)
{
	if (empty($handles)) return;

	$mh = curl_multi_init();
	foreach ($handles as $ch) {
		curl_multi_add_handle($mh, $ch);
	}

	$running = null;
	do {
		$status = curl_multi_exec($mh, $running);
		if ($running > 0) {
			curl_multi_select($mh, 1.0);
		}
	} while ($running > 0 && $status === CURLM_OK);

	foreach ($handles as $ch) {
		curl_multi_remove_handle($mh, $ch);
		curl_close($ch);
	}
	curl_multi_close($mh);
}

function PopulateTurnStatsAndAggregates(&$deck, &$turnStats, &$otherPlayerTurnStats, $player, $useIntval = false)
{
	global $currentTurn, $TurnStats_DamageThreatened, $TurnStats_DamageDealt, $TurnStats_CardsPlayedOffense;
	global $TurnStats_CardsPlayedDefense, $TurnStats_CardsPitched, $TurnStats_CardsBlocked, $TurnStats_DamageBlocked;
	global $TurnStats_ResourcesUsed, $TurnStats_CardsLeft, $TurnStats_ResourcesLeft, $TurnStats_LifeGained;
	global $TurnStats_LifeLost, $TurnStats_DamagePrevented, $TurnStats_CardsDiscarded, $p1TotalTime, $p2TotalTime;
	global $p1LifeHistory, $p2LifeHistory;
	$lifeHistory = $player == 1 ? $p1LifeHistory : $p2LifeHistory;
	$opponentLifeHistory = $player == 1 ? $p2LifeHistory : $p1LifeHistory;

	$countTurnStats = count($turnStats);
	$tsp = TurnStatPieces();

	// Populate turn results - only include turns that have actually occurred
	$turnNo = 0;
	for($i = 0; $i < $countTurnStats && $turnNo <= $currentTurn; $i += $tsp, ++$turnNo) {
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
			$cardsUsed = (int)$cardsUsed;
			$cardsBlocked = (int)$cardsBlocked;
			$cardsPitched = (int)$cardsPitched;
			$cardsDiscarded = (int)$cardsDiscarded;
			$resourcesUsed = (int)$resourcesUsed;
			$resourcesLeft = (int)$resourcesLeft;
			$cardsLeft = (int)$cardsLeft;
			$damageThreatened = (int)$damageThreatened;
			$damageDealt = (int)$damageDealt;
			$damageBlocked = (int)$damageBlocked;
			$damagePrevented = (int)$damagePrevented;
			$damageTaken = (int)$damageTaken;
			$lifeGained = (int)$lifeGained;
			$lifeLost = (int)$lifeLost;
		}

		$entry = &$deck["turnResults"][$turnKey];
		$entry["cardsUsed"] = $cardsUsed;
		$entry["cardsBlocked"] = $cardsBlocked;
		$entry["cardsPitched"] = $cardsPitched;
		$entry["cardsDiscarded"] = $cardsDiscarded;
		$entry["resourcesUsed"] = $resourcesUsed;
		$entry["resourcesLeft"] = $resourcesLeft;
		$entry["cardsLeft"] = $cardsLeft;
		$entry["damageThreatened"] = $damageThreatened;
		$entry["damageDealt"] = $damageDealt;
		$entry["damageBlocked"] = $damageBlocked;
		$entry["damagePrevented"] = $damagePrevented;
		$entry["damageTaken"] = $damageTaken;
		$entry["lifeGained"] = $lifeGained;
		$entry["lifeLost"] = $lifeLost;
		$entry["lifeAtTurnEnd"] = isset($lifeHistory[$turnNo]) ? (int)$lifeHistory[$turnNo] : null;
		$entry["opponentLifeAtTurnEnd"] = isset($opponentLifeHistory[$turnNo]) ? (int)$opponentLifeHistory[$turnNo] : null;

		// SerializeGameResult has turnNo, SerializeDetailedGameResult doesn't - only add if not using intval
		if(!$useIntval) {
			$entry["turnNo"] = $turnNo;
		}
		unset($entry);
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

	$tsp = TurnStatPieces();
	$countTurnStats = count($turnStats);
	if (empty($turnStats) || $countTurnStats < $tsp) return;

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
	$start = $tsp;
	$endIndex = $countTurnStats - $tsp;
	if($endIndex < $start) $endIndex = $start;

	for($i = $start; $i < $endIndex; $i += $tsp) {
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
	if($endIndex - $tsp >= $start) {
		$lastTurnIndex = $endIndex - $tsp;
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
	global $CardStats_TimesKatsuDiscard, $CardStats_TimesDiscarded, $CardStats_TimesActivated, $CardStats_TimesPassiveTriggered;
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
		$deck["startingLife"] = CharacterHealth($yourHeroCardID);
	}

	// Add opponent's hero if provided
	if($opposingHero != "") {
		$deck["opponentHero"] = $opposingHero;
		$deck["opponentStartingLife"] = CharacterHealth($opposingHero);
	}

	$deck["cardResults"] = [];
	$deck["character"] = [];

	$character = explode(" ", $character);
	$deduplicatedCharacter = array_count_values($character);

	foreach ($deduplicatedCharacter as $card => $numCopies) {
		$deck["character"][] = [
			"cardId" => $card,
			"cardName" => CardName($card),
			"numCopies" => $numCopies,
		];
	}

	$deckAfterSB = explode(" ", $deckAfterSB);
	$deduplicatedDeck = array_count_values($deckAfterSB);

	foreach ($deduplicatedDeck as $card => $numCopies) {
		$deck["cardResults"][] = [
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
	}

	$cardStats = &GetCardStats($player);
	$deck["tokenResults"] = [];
	$deck["arenaCardResults"] = [];

	// Build a cardId → index map to avoid O(n*m) inner-loop lookups
	$cardResultIndex = [];
	foreach ($deck["cardResults"] as $j => $cr) {
		$cardResultIndex[$cr["cardId"]] = $j;
	}

	$csp = CardStatPieces();
	$countCardStats = count($cardStats);
	for($i = 0; $i < $countCardStats; $i += $csp) {
		$cardId = $cardStats[$i];
		if (isset($cardResultIndex[$cardId])) {
			$j = $cardResultIndex[$cardId];
			$deck["cardResults"][$j]["played"] = $cardStats[$i + $CardStats_TimesPlayed];
			$deck["cardResults"][$j]["blocked"] = $cardStats[$i + $CardStats_TimesBlocked];
			$deck["cardResults"][$j]["pitched"] = $cardStats[$i + $CardStats_TimesPitched];
			$deck["cardResults"][$j]["hits"] = $cardStats[$i + $CardStats_TimesHit];
			$deck["cardResults"][$j]["charged"] = $cardStats[$i + $CardStats_TimesCharged];
			$deck["cardResults"][$j]["charged"] = $cardStats[$i + $CardStats_TimesKatsuDiscard];
			$deck["cardResults"][$j]["discarded"] = $cardStats[$i + $CardStats_TimesDiscarded];
			$deck["cardResults"][$j]["activated"] = $cardStats[$i + $CardStats_TimesActivated];
			$deck["cardResults"][$j]["passiveTriggered"] = $cardStats[$i + $CardStats_TimesPassiveTriggered];
		} else {
			// If card has stats but wasn't in the decklist, route to arenaCardResults if equipment/weapon/character/companion or if activated from play (e.g. ally tokens), otherwise tokenResults
			$cardType = CardType($cardId);
			$cardResult = [
				"cardId" => $cardId,
				"played" => $cardStats[$i + $CardStats_TimesPlayed],
				"blocked" => $cardStats[$i + $CardStats_TimesBlocked],
				"pitched" => $cardStats[$i + $CardStats_TimesPitched],
				"hits" => $cardStats[$i + $CardStats_TimesHit],
				"discarded" => $cardStats[$i + $CardStats_TimesDiscarded],
				"charged" => $cardStats[$i + $CardStats_TimesCharged],
				"cardName" => CardName($cardId),
				"pitchValue" => PitchValue($cardId),
				"katsuDiscard" => $cardStats[$i + $CardStats_TimesKatsuDiscard],
				"activated" => $cardStats[$i + $CardStats_TimesActivated],
				"passiveTriggered" => $cardStats[$i + $CardStats_TimesPassiveTriggered],
			];
			if (DelimStringContains($cardType, "C") || DelimStringContains($cardType, "E") || DelimStringContains($cardType, "W") || DelimStringContains($cardType, "Companion") || $cardStats[$i + $CardStats_TimesActivated] > 0 || $cardStats[$i + $CardStats_TimesPassiveTriggered] > 0 || $cardStats[$i + $CardStats_TimesPitched] > 0) {
				$deck["arenaCardResults"][] = $cardResult;
			} else {
				$deck["tokenResults"][] = $cardResult;
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
	global $CardStats_TimesKatsuDiscard, $CardStats_TimesDiscarded, $CardStats_TimesActivated, $CardStats_TimesPassiveTriggered;
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
	if($opposingHero != "") $deck["opponentStartingLife"] = intval(CharacterHealth($opposingHero));
	if($playerHero != "") $deck["playerHero"] = $playerHero;
	if($playerHero != "") $deck["startingLife"] = intval(CharacterHealth($playerHero));
	if($deckbuilderID != "") $deck["deckbuilderID"] = $deckbuilderID;
	$deck["cardResults"] = [];
	$deck["character"] = [];

	$character = explode(" ", $character);
	$deduplicatedCharacter = array_count_values($character);

	foreach ($deduplicatedCharacter as $card => $numCopies) {
		$deck["character"][] = [
			"cardId" => $card,
			"cardName" => CardName($card),
			"numCopies" => $numCopies,
		];
	}

	$deckAfterSB = explode(" ", $deckAfterSB);
	$deduplicatedDeck = array_count_values($deckAfterSB);

	foreach ($deduplicatedDeck as $card => $numCopies) {
		$deck["cardResults"][] = [
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
	}

	$cardStats = &GetCardStats($player);
	$deck["tokenResults"] = [];
	$deck["arenaCardResults"] = [];

	// Build a cardId → index map to avoid O(n*m) inner-loop lookups
	$cardResultIndex = [];
	foreach ($deck["cardResults"] as $j => $cr) {
		$cardResultIndex[$cr["cardId"]] = $j;
	}

	$csp = CardStatPieces();
	$countCardStats = count($cardStats);
	for($i = 0; $i < $countCardStats; $i += $csp) {
		$cardId = $cardStats[$i];
		if (isset($cardResultIndex[$cardId])) {
			$j = $cardResultIndex[$cardId];
			$deck["cardResults"][$j]["played"] = intval($cardStats[$i + $CardStats_TimesPlayed]);
			$deck["cardResults"][$j]["blocked"] = intval($cardStats[$i + $CardStats_TimesBlocked]);
			$deck["cardResults"][$j]["pitched"] = intval($cardStats[$i + $CardStats_TimesPitched]);
			$deck["cardResults"][$j]["hits"] = intval($cardStats[$i + $CardStats_TimesHit]);
			$deck["cardResults"][$j]["charged"] = intval($cardStats[$i + $CardStats_TimesCharged]);
			$deck["cardResults"][$j]["charged"] = intval($cardStats[$i + $CardStats_TimesKatsuDiscard]);
			$deck["cardResults"][$j]["discarded"] = intval($cardStats[$i + $CardStats_TimesDiscarded]);
			$deck["cardResults"][$j]["activated"] = intval($cardStats[$i + $CardStats_TimesActivated]);
			$deck["cardResults"][$j]["passiveTriggered"] = intval($cardStats[$i + $CardStats_TimesPassiveTriggered]);
		} else {
			// If card has stats but wasn't in the decklist, route to arenaCardResults if equipment/weapon/character/companion or if activated from play (e.g. ally tokens), otherwise tokenResults
			$cardType = CardType($cardId);
			$cardResult = [
				"cardId" => $cardId,
				"played" => intval($cardStats[$i + $CardStats_TimesPlayed]),
				"blocked" => intval($cardStats[$i + $CardStats_TimesBlocked]),
				"pitched" => intval($cardStats[$i + $CardStats_TimesPitched]),
				"hits" => intval($cardStats[$i + $CardStats_TimesHit]),
				"discarded" => intval($cardStats[$i + $CardStats_TimesDiscarded]),
				"charged" => intval($cardStats[$i + $CardStats_TimesCharged]),
				"cardName" => CardName($cardId),
				"pitchValue" => PitchValue($cardId),
				"katsuDiscard" => intval($cardStats[$i + $CardStats_TimesKatsuDiscard]),
				"activated" => intval($cardStats[$i + $CardStats_TimesActivated]),
				"passiveTriggered" => intval($cardStats[$i + $CardStats_TimesPassiveTriggered]),
			];
			if (DelimStringContains($cardType, "C") || DelimStringContains($cardType, "E") || DelimStringContains($cardType, "W") || DelimStringContains($cardType, "Companion") || intval($cardStats[$i + $CardStats_TimesActivated]) > 0 || intval($cardStats[$i + $CardStats_TimesPassiveTriggered]) > 0 || intval($cardStats[$i + $CardStats_TimesPitched]) > 0) {
				$deck["arenaCardResults"][] = $cardResult;
			} else {
				$deck["tokenResults"][] = $cardResult;
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
	$conn = GetDBConnection(DBL_SAVE_PATREON_TOKENS);
	$sql = "UPDATE users SET patreonAccessToken=?, patreonRefreshToken=? WHERE usersid=?";
	$stmt = mysqli_stmt_init($conn);
	if(mysqli_stmt_prepare($stmt, $sql)) {
		mysqli_stmt_bind_param($stmt, "sss", $accessToken, $refreshToken, $userID);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	}
	mysqli_close($conn);
}

function SaveSetting($playerId, $settingNumber, $value)
{
	if($playerId == "" || $playerId == "-" || !is_numeric($playerId)) return;
	$conn = GetDBConnection(DBL_SAVE_SETTING);
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
	$conn = GetDBConnection(DBL_LOAD_SAVED_SETTINGS);
	if (!$conn) return [];
	$sql = "select settingNumber,settingValue from `savedsettings` where playerId=(?)";
	$stmt = mysqli_stmt_init($conn);
	if(mysqli_stmt_prepare($stmt, $sql)) {
		mysqli_stmt_bind_param($stmt, "s", $playerId);
		mysqli_stmt_execute($stmt);
		$data = mysqli_stmt_get_result($stmt);
		while($row = mysqli_fetch_array($data, MYSQLI_NUM)) {
			$output[] = $row[0];
			$output[] = $row[1];
		}
		mysqli_free_result($data);  // FREE RESULT BEFORE CLOSING STATEMENT
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

// Returns the player's display name, falling back to the account handle when unset.
function GetDisplayNameByUid($uid)
{
	if ($uid == "" || $uid == "-") return $uid;
	$conn = GetDBConnection(DBL_GET_DISPLAY_NAME);
	if (!$conn) return $uid;
	$displayName = null;
	$sql = "SELECT displayName FROM users WHERE usersUid = ?";
	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $sql)) {
		mysqli_stmt_bind_param($stmt, "s", $uid);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $displayName);
		mysqli_stmt_fetch($stmt);
		mysqli_stmt_close($stmt);
	}
	return ($displayName !== null && $displayName !== "") ? $displayName : $uid;
}

function ResolveNameToAccount($name)
{
	if ($name == "") return null;
	$conn = GetDBConnection(DBL_RESOLVE_NAME_TO_ACCOUNT);
	if (!$conn) return null;

	$queries = [
		"SELECT usersId, usersUid FROM users WHERE usersUid = ? LIMIT 1",
		"SELECT usersId, usersUid FROM users WHERE displayName = ? LIMIT 1",
		"SELECT u.usersId, u.usersUid FROM name_history h JOIN users u ON u.usersId = h.usersId
		 WHERE h.oldName = ? OR h.newName = ? ORDER BY h.changedAt DESC LIMIT 1"
	];
	foreach ($queries as $i => $sql) {
		$stmt = mysqli_stmt_init($conn);
		if (!mysqli_stmt_prepare($stmt, $sql)) continue;
		if ($i == 2) mysqli_stmt_bind_param($stmt, "ss", $name, $name);
		else mysqli_stmt_bind_param($stmt, "s", $name);
		mysqli_stmt_execute($stmt);
		$usersId = null;
		$usersUid = null;
		mysqli_stmt_bind_result($stmt, $usersId, $usersUid);
		$found = mysqli_stmt_fetch($stmt);
		mysqli_stmt_close($stmt);
		if ($found) return ["usersId" => $usersId, "usersUid" => $usersUid];
	}
	return null;
}

function BanPlayer($uid, $bannedBy = "")
{
	$account = ResolveNameToAccount($uid);
	if ($account !== null) $uid = $account["usersUid"];
	$conn = GetDBConnection(DBL_BAN_PLAYER);
	if (!$conn) return $uid;
	$sql = "UPDATE users SET isBanned = true WHERE usersUid = ?";
	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $sql)) {
		mysqli_stmt_bind_param($stmt, "s", $uid);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	}
	EnsureBannedPlayersTable($conn);
	$sql = "INSERT INTO banned_players (name, bannedBy) VALUES (?, ?) ON DUPLICATE KEY UPDATE name = name";
	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $sql)) {
		mysqli_stmt_bind_param($stmt, "ss", $uid, $bannedBy);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	}
	if (function_exists('apcu_delete')) @apcu_delete('talishar_banned_players');
	return $uid;
}

function EnsureBannedPlayersTable($conn)
{
	$sql = "CREATE TABLE IF NOT EXISTS banned_players (
		name VARCHAR(255) NOT NULL PRIMARY KEY,
		bannedBy VARCHAR(255) DEFAULT NULL,
		createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
	if (!mysqli_query($conn, $sql)) {
		error_log("Failed to create banned_players table: " . mysqli_error($conn));
	}
}

function EnsureBannedIPsTable($conn)
{
	$sql = "CREATE TABLE IF NOT EXISTS banned_ips (
		ip VARCHAR(45) NOT NULL PRIMARY KEY,
		bannedBy VARCHAR(255) DEFAULT NULL,
		createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
	if (!mysqli_query($conn, $sql)) {
		error_log("Failed to create banned_ips table: " . mysqli_error($conn));
	}
}

function BanIP($ip, $bannedBy = "")
{
	$conn = GetDBConnection(DBL_BAN_PLAYER);
	if (!$conn) return false;
	EnsureBannedIPsTable($conn);
	$sql = "INSERT INTO banned_ips (ip, bannedBy) VALUES (?, ?) ON DUPLICATE KEY UPDATE ip = ip";
	$stmt = mysqli_stmt_init($conn);
	$success = false;
	if (mysqli_stmt_prepare($stmt, $sql)) {
		mysqli_stmt_bind_param($stmt, "ss", $ip, $bannedBy);
		$success = mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	}
	return $success;
}

function IsIPInCIDR($ip, $cidr)
{
	if (strpos($cidr, '/') === false) return $ip === $cidr;
	list($subnet, $bits) = explode('/', $cidr);
	$bits = (int)$bits;
	$ipBin = @inet_pton($ip);
	$subnetBin = @inet_pton($subnet);
	if ($ipBin === false || $subnetBin === false || strlen($ipBin) !== strlen($subnetBin)) {
		return false;
	}
	$bytes = intdiv($bits, 8);
	$remainderBits = $bits % 8;
	if ($bytes > 0 && substr($ipBin, 0, $bytes) !== substr($subnetBin, 0, $bytes)) {
		return false;
	}
	if ($remainderBits > 0) {
		$mask = ~(0xFF >> $remainderBits) & 0xFF;
		if ((ord($ipBin[$bytes]) & $mask) !== (ord($subnetBin[$bytes]) & $mask)) {
			return false;
		}
	}
	return true;
}

function IsCloudflareIP($ip)
{
	// Official ranges: https://www.cloudflare.com/ips/
	static $cfRanges = [
		'173.245.48.0/20',
		'103.21.244.0/22',
		'103.22.200.0/22',
		'103.31.4.0/22',
		'141.101.64.0/18',
		'108.162.192.0/18',
		'190.93.240.0/20',
		'188.114.96.0/20',
		'197.234.240.0/22',
		'198.41.128.0/17',
		'162.158.0.0/15',
		'104.16.0.0/13',
		'104.24.0.0/14',
		'172.64.0.0/13',
		'131.0.72.0/22',
		'2400:cb00::/32',
		'2606:4700::/32',
		'2803:f800::/32',
		'2405:b500::/32',
		'2405:8100::/32',
		'2a06:98c0::/29',
		'2c0f:f248::/32',
	];
	foreach ($cfRanges as $range) {
		if (IsIPInCIDR($ip, $range)) return true;
	}
	return false;
}

function GetClientIP()
{
	$remoteAddr = $_SERVER['REMOTE_ADDR'] ?? "";
	if ($remoteAddr !== "" && IsCloudflareIP($remoteAddr) && !empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
		$cfIP = $_SERVER['HTTP_CF_CONNECTING_IP'];
		if (filter_var($cfIP, FILTER_VALIDATE_IP)) {
			return $cfIP;
		}
	}
	return $remoteAddr;
}

function IsIPBanned($ip = null)
{
	if ($ip === null) $ip = GetClientIP();
	if ($ip == "") return false;

	static $cache = [];
	if (isset($cache[$ip])) return $cache[$ip];

	$banned = false;

	$bannedIPsFile = dirname(__DIR__) . "/HostFiles/bannedIPs.txt";
	if (file_exists($bannedIPsFile)) {
		$lines = @file($bannedIPsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		if ($lines && in_array($ip, array_map('trim', $lines))) $banned = true;
	}

	if (!$banned) {
		$conn = GetDBConnection(DBL_IS_IP_BANNED);
		if ($conn) {
			$sql = "SELECT 1 FROM banned_ips WHERE ip = ? LIMIT 1";
			$stmt = mysqli_stmt_init($conn);
			try {
				if (mysqli_stmt_prepare($stmt, $sql)) {
					mysqli_stmt_bind_param($stmt, "s", $ip);
					mysqli_stmt_execute($stmt);
					$result = mysqli_stmt_get_result($stmt);
					$banned = ($result && mysqli_fetch_row($result) != null);
					mysqli_stmt_close($stmt);
				}
			} catch (\Exception $e) {
				error_log("IsIPBanned: query failed: " . $e->getMessage());
			}
		}
	}

	$cache[$ip] = $banned;
	return $banned;
}

function EnsureIPHistoryTable($conn)
{
	$sql = "CREATE TABLE IF NOT EXISTS ip_history (
		usersId INT NOT NULL,
		ip VARCHAR(45) NOT NULL,
		firstSeen TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
		lastSeen TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		timesSeen INT NOT NULL DEFAULT 1,
		PRIMARY KEY (usersId, ip),
		INDEX idx_ip (ip)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
	if (!mysqli_query($conn, $sql)) {
		error_log("Failed to create ip_history table: " . mysqli_error($conn));
	}
}

function LogIPHistory($usersId, $ip = null)
{
	if ($ip === null) $ip = GetClientIP();
	if ($ip == "" || $usersId == null || $usersId === "") return;
	// Never record a Cloudflare edge address as a player's IP -- it's shared
	// by unrelated visitors and would poison ban-evasion IP matching.
	if (IsCloudflareIP($ip)) return;

	static $logged = [];
	$key = $usersId . "|" . $ip;
	if (isset($logged[$key])) return;
	$logged[$key] = true;

	$conn = GetDBConnection(DBL_LOG_IP_HISTORY);
	if (!$conn) return;
	EnsureIPHistoryTable($conn);
	$sql = "INSERT INTO ip_history (usersId, ip) VALUES (?, ?)
		ON DUPLICATE KEY UPDATE lastSeen = CURRENT_TIMESTAMP, timesSeen = timesSeen + 1";
	$stmt = mysqli_stmt_init($conn);
	try {
		if (mysqli_stmt_prepare($stmt, $sql)) {
			mysqli_stmt_bind_param($stmt, "is", $usersId, $ip);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_close($stmt);
		}
	} catch (\Exception $e) {
		error_log("LogIPHistory: query failed: " . $e->getMessage());
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
