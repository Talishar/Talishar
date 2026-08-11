<?php

include "../HostFiles/Redirector.php";
include "../Libraries/HTTPLibraries.php";
SetHeaders();

// Answer the preflight before pulling in anything heavier. Those includes start a
// session and could emit output; any output before SetHeaders() would mean the CORS
// headers never get sent, which surfaces in the browser as a CORS failure.
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

include_once "../AccountFiles/AccountSessionAPI.php";
include_once "../includes/dbh.inc.php";
include_once "../includes/WebhookSecurity.php";

header('Content-Type: application/json');

// A non-JSON body decodes to null, which would break TryPOST's array access.
$_POST = json_decode(file_get_contents('php://input'), true) ?? [];

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!IsUserLoggedIn()) {
    if (isset($_COOKIE["rememberMeToken"])) {
        loginFromCookie();
    }
}

$response = new stdClass();

if (!IsUserLoggedIn()) {
    http_response_code(401);
    $response->success = false;
    $response->message = "You must be logged in.";
    echo json_encode($response);
    exit;
}

$userName = LoggedInUserName();
$webhookUrl = TryPOST("webhookUrl", "");

if (!empty($webhookUrl)) {
    $validationError = ValidateWebhookUrl($webhookUrl);
    if ($validationError !== null) {
        http_response_code(400);
        $response->success = false;
        $response->message = $validationError;
        echo json_encode($response);
        exit;
    }
}

$conn = GetDBConnection(DBL_MATCH_RESULT_WEBHOOK_API);
if (!$conn) {
    http_response_code(500);
    $response->success = false;
    $response->message = "Database connection failed.";
    echo json_encode($response);
    exit;
}

$urlToStore = empty($webhookUrl) ? null : $webhookUrl;
$sql = "UPDATE users SET matchResultWebhookUrl = ? WHERE usersUid = ?";
$stmt = mysqli_stmt_init($conn);

if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_bind_param($stmt, "ss", $urlToStore, $userName);
    if (mysqli_stmt_execute($stmt)) {
        $response->success = true;
        $response->message = empty($webhookUrl) ? "Webhook cleared." : "Webhook saved.";
    } else {
        http_response_code(500);
        $response->success = false;
        $response->message = "Failed to save webhook.";
    }
    mysqli_stmt_close($stmt);
} else {
    http_response_code(500);
    $response->success = false;
    $response->message = "Database query failed.";
}

mysqli_close($conn);
session_write_close();

echo json_encode($response);
exit;
