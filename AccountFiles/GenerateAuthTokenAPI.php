<?php
/**
 * Generate Auth Token API
 * Creates a signed JWT for authenticated users
 *
 * POST Body (JSON):
 *   - app_id: The application identifier
 *   - redirect_uri: The URI to redirect to (must match allowed URIs)
 *
 * Returns JSON:
 *   Success: { token: "jwt-token" }
 *   Error: { error: "message" }
 */

include_once './AccountSessionAPI.php';
include_once './OAuthApps.php';
include_once '../Libraries/HTTPLibraries.php';
include_once '../APIKeys/APIKeys.php';
include_once '../includes/dbh.inc.php';

SetHeaders();

// Handle CORS preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if (!IsUserLoggedIn()) {
    ExitJsonResponse(['error' => 'User not authenticated'], 401);
}

$input = ReadJsonBody();
$appId = $input['app_id'] ?? null;
$redirectUri = $input['redirect_uri'] ?? null;

if (!$appId) {
    ExitJsonResponse(['error' => 'app_id is required'], 400);
}

$appConfig = GetOAuthApp($appId);
if (!$appConfig) {
    ExitJsonResponse(['error' => 'Invalid application ID'], 400);
}

if (!$redirectUri || !IsValidRedirectUri($appId, $redirectUri)) {
    ExitJsonResponse(['error' => 'Invalid redirect_uri'], 400);
}

$userId = LoggedInUser();
$userName = LoggedInUserName();

$saltedUsername = '';
if (!empty($userName) && !empty($playerHashSalt)) {
    $saltedUsername = hash_hmac('sha256', $userName, $playerHashSalt);
}

$userEmail = '';
$conn = GetDBConnection(DBL_GENERATE_AUTH_TOKEN_API);
if ($conn) {
    $stmt = $conn->prepare("SELECT usersEmail FROM users WHERE usersId = ?");
    if ($stmt) {
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $userEmail = $row['usersEmail'];
        }
        $stmt->close();
    }
}

$payload = [
    'email' => $userEmail,
    'salted_username' => $saltedUsername,
    'app_id' => $appId,
    'iat' => time(),
    'exp' => time() + 300  // 5 minute expiry
];

$token = GenerateJWT($payload, $appConfig['secret']);

WriteJsonResponse(['token' => $token]);
