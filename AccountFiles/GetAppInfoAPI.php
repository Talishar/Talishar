<?php
/**
 * Get OAuth App Info API
 * Validates app_id and redirect_uri, returns app details for the consent screen
 *
 * GET Parameters:
 *   - app_id: The application identifier
 *   - redirect_uri: The URI to redirect to after auth
 *
 * Returns JSON:
 *   Success: { app_id, name, description }
 *   Error: { error: "message" }
 */

include_once '../Libraries/HTTPLibraries.php';
include_once './OAuthApps.php';

SetHeaders();
header('Content-Type: application/json');

$appId = $_GET['app_id'] ?? null;
$redirectUri = $_GET['redirect_uri'] ?? null;

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

if (!$redirectUri) {
    http_response_code(400);
    echo json_encode(['error' => 'redirect_uri is required']);
    exit;
}

if (!IsValidRedirectUri($appId, $redirectUri)) {
    http_response_code(400);
    echo json_encode(['error' => 'redirect_uri not allowed for this application']);
    exit;
}

echo json_encode([
    'app_id' => $appId,
    'name' => $appConfig['name'],
    'description' => $appConfig['description']
]);
