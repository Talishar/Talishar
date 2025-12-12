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

if (!IsUserLoggedIn()) {
    http_response_code(401);
    echo json_encode(['error' => 'User not authenticated']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$appId = $input['app_id'] ?? null;
$redirectUri = $input['redirect_uri'] ?? null;

if (!$appId) {
    http_response_code(400);
    echo json_encode(['error' => 'app_id is required']);
    exit;
}

$appConfig = GetOAuthApp($appId);
if (!$appConfig) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid application ID']);
    exit;
}

if (!$redirectUri || !IsValidRedirectUri($appId, $redirectUri)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid redirect_uri']);
    exit;
}

$userId = LoggedInUser();
$userName = LoggedInUserName();

$saltedUsername = '';
if (!empty($userName) && !empty($playerHashSalt)) {
    $saltedUsername = hash_hmac('sha256', $userName, $playerHashSalt);
}

$userEmail = '';
$conn = GetDBConnection();
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

echo json_encode(['token' => $token]);
