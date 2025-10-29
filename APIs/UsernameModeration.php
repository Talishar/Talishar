<?php

ob_start();
include "../HostFiles/Redirector.php";
include "../Libraries/HTTPLibraries.php";
ob_end_clean();
SetHeaders();

// Handle CORS preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

include_once '../includes/functions.inc.php';
include_once "../includes/dbh.inc.php";

if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}

header('Content-Type: application/json');

if (!isset($_SESSION["useruid"])) {
  http_response_code(401);
  echo json_encode(["error" => "Not logged in"]);
  exit;
}

$useruid = $_SESSION["useruid"];
include_once '../includes/ModeratorList.inc.php';
if (!IsUserModerator($useruid)) {
  http_response_code(403);
  echo json_encode(["error" => "Not authorized"]);
  exit;
}

// Handle both form-encoded and JSON POST data
$postData = $_POST;
if (empty($_POST) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    if (strpos($contentType, 'application/json') !== false) {
        $jsonData = json_decode(file_get_contents('php://input'), true);
        $postData = $jsonData ?? [];
    }
}

function TryPOSTData($key, $default = "", $data = []) {
    return isset($data[$key]) ? $data[$key] : $default;
}

$action = TryPOSTData("action", "getOffensiveUsernames", $postData);

// List of offensive terms/patterns (case-insensitive)
$offensivePatterns = [
    'n-word', 'nword', 'n word', 'racial slur', 
    'b-tch', 'btch', 'whore', 'slut', 'skank',
    'f-ggot', 'fag', 'homo', 'gay slur',
    'f-ck', 'fck', 'f word', 'ass hole', 'asshole',
    'nazi', 'kkk', 'antifa slur', 'penis', 'vagina', 'cunt',
    'n-gger', 'n-gga', 'dick', 'dickhead', 'pussy',
    'slave', 'nazi', 'jew', 'jewish', 'h-tler', 'cum', 'cock', 
    'arab', 'israel', 'koon', 'kum', 'slayer', 'killer'

    // Add more as needed - moderate these yourself
];

// Create whitelist table if it doesn't exist
function EnsureWhitelistTable($conn) {
    $sql = "CREATE TABLE IF NOT EXISTS username_whitelist (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(255) NOT NULL UNIQUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        whitelisted_by VARCHAR(255),
        reason VARCHAR(500)
    )";
    
    if (!mysqli_query($conn, $sql)) {
        error_log("Failed to create whitelist table: " . mysqli_error($conn));
    }
}

// Check if username is whitelisted
function IsUsernameWhitelisted($conn, $username) {
    EnsureWhitelistTable($conn);
    $sql = "SELECT 1 FROM username_whitelist WHERE username = ?";
    $stmt = mysqli_stmt_init($conn);
    
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        return false;
    }
    
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $exists = mysqli_num_rows($result) > 0;
    mysqli_stmt_close($stmt);
    
    return $exists;
}

if ($action === 'getOffensiveUsernames') {
    $conn = GetDBConnection();
    if (!$conn) {
        http_response_code(500);
        echo json_encode(["error" => "Database connection failed"]);
        exit;
    }

    EnsureWhitelistTable($conn);

    // Get all active (not banned) usernames
    $sql = "SELECT usersId, usersUid FROM users WHERE isBanned = 0 ORDER BY usersId DESC";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        http_response_code(500);
        echo json_encode(["error" => "Database query failed: " . mysqli_error($conn)]);
        mysqli_close($conn);
        exit;
    }

    $offensiveUsers = [];
    $cleanPatterns = array_map(function($pattern) {
        return strtolower(str_replace(['-', '1', '0', '3', '5', '7', '!', '@'], '', $pattern));
    }, $offensivePatterns);

    while ($row = mysqli_fetch_assoc($result)) {
        $username = strtolower($row['usersUid']);
        $cleanUsername = str_replace(['-', '_', '1', '0', '3', '5', '7', '!', '@'], '', $username);

        // Skip whitelisted usernames
        if (IsUsernameWhitelisted($conn, $row['usersUid'])) {
            continue;
        }

        // Check against offensive patterns
        foreach ($cleanPatterns as $pattern) {
            if (strpos($cleanUsername, $pattern) !== false) {
                $offensiveUsers[] = [
                    'usersId' => $row['usersId'],
                    'username' => $row['usersUid'],
                    'matchedPattern' => $pattern
                ];
                break; // Don't add same user multiple times
            }
        }
    }

    mysqli_close($conn);

    echo json_encode([
        "status" => "success",
        "offensiveUsers" => $offensiveUsers,
        "totalFound" => count($offensiveUsers)
    ]);
    exit;
}

// Ban offensive usernames
if ($action === 'banOffensiveUsername') {
    $username = TryPOSTData("username", "", $postData);
    
    if (empty($username)) {
        http_response_code(400);
        echo json_encode(["error" => "Username is required"]);
        exit;
    }

    $conn = GetDBConnection();
    if (!$conn) {
        http_response_code(500);
        echo json_encode(["error" => "Database connection failed"]);
        exit;
    }

    // Ban the user in database
    $sql = "UPDATE users SET isBanned = 1 WHERE usersUid = ?";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        http_response_code(500);
        echo json_encode(["error" => "Database prepare failed"]);
        mysqli_close($conn);
        exit;
    }

    mysqli_stmt_bind_param($stmt, "s", $username);

    if (!mysqli_stmt_execute($stmt)) {
        http_response_code(500);
        echo json_encode(["error" => "Failed to ban user"]);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        exit;
    }

    $affectedRows = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    if ($affectedRows > 0) {
        // Also add to bannedPlayers.txt for consistency
        file_put_contents('../HostFiles/bannedPlayers.txt', $username . "\r\n", FILE_APPEND | LOCK_EX);
        
        echo json_encode([
            "status" => "success",
            "message" => "User $username has been banned"
        ]);
    } else {
        http_response_code(404);
        echo json_encode(["error" => "User not found"]);
    }
    exit;
}

// Whitelist offensive usernames (approve them as acceptable)
if ($action === 'whitelistOffensiveUsername') {
    $username = TryPOSTData("username", "", $postData);
    
    if (empty($username)) {
        http_response_code(400);
        echo json_encode(["error" => "Username is required"]);
        exit;
    }

    $conn = GetDBConnection();
    if (!$conn) {
        http_response_code(500);
        echo json_encode(["error" => "Database connection failed"]);
        exit;
    }

    EnsureWhitelistTable($conn);

    // Add to whitelist
    $sql = "INSERT IGNORE INTO username_whitelist (username, whitelisted_by) VALUES (?, ?)";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        http_response_code(500);
        echo json_encode(["error" => "Database prepare failed"]);
        mysqli_close($conn);
        exit;
    }

    mysqli_stmt_bind_param($stmt, "ss", $username, $useruid);

    if (!mysqli_stmt_execute($stmt)) {
        http_response_code(500);
        echo json_encode(["error" => "Failed to whitelist user"]);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        exit;
    }

    $affectedRows = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    if ($affectedRows > 0) {
        echo json_encode([
            "status" => "success",
            "message" => "User $username has been whitelisted"
        ]);
    } else {
        echo json_encode([
            "status" => "success",
            "message" => "User $username was already whitelisted"
        ]);
    }
    exit;
}

http_response_code(400);
echo json_encode(["error" => "Unknown action"]);
?>
