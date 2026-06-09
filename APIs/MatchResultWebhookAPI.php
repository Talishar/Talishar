<?php

include "../HostFiles/Redirector.php";
include "../Libraries/HTTPLibraries.php";
include_once "../AccountFiles/AccountSessionAPI.php";
include_once "../includes/dbh.inc.php";

SetHeaders();

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$_POST = json_decode(file_get_contents('php://input'), true);

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
        $response->success = false;
        $response->message = $validationError;
        echo json_encode($response);
        exit;
    }
}

$conn = GetDBConnection(DBL_MATCH_RESULT_WEBHOOK_API);
if (!$conn) {
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
        $response->success = false;
        $response->message = "Failed to save webhook.";
    }
    mysqli_stmt_close($stmt);
} else {
    $response->success = false;
    $response->message = "Database query failed.";
}

mysqli_close($conn);
session_write_close();

header('Content-Type: application/json');
echo json_encode($response);
exit;

function ValidateWebhookUrl(string $url): ?string
{
    if (strlen($url) > 2048) {
        return "Webhook URL is too long (max 2048 characters).";
    }

    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        return "Invalid webhook URL format.";
    }

    $scheme = parse_url($url, PHP_URL_SCHEME);
    if (!in_array($scheme, ['http', 'https'], true)) {
        return "Webhook URL must use http or https.";
    }

    $host = parse_url($url, PHP_URL_HOST);
    if (empty($host)) {
        return "Webhook URL has no host.";
    }

    // Reject bare IP addresses — must use a resolvable hostname
    if (filter_var($host, FILTER_VALIDATE_IP)) {
        return "Webhook URL must use a hostname, not a bare IP address.";
    }

    // Resolve the hostname and reject private/reserved ranges (SSRF protection)
    $ip = gethostbyname($host);
    if ($ip === $host) {
        return "Webhook URL hostname could not be resolved. Please check the URL and try again.";
    }

    if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
        return "Webhook URL must point to a public host.";
    }

    return null;
}
