<?php
/**
 * OAuth Applications Configuration
 * Defines allowed third-party applications for authentication
 */

require_once __DIR__ . "/../APIKeys/APIKeys.php";

$allowedOAuthApps = [
    "fablazing" => [
        "name" => "Fablazing",
        "description" => "Fablazing application",
        "secret" => $fablazingSecret,
        "allowed_redirect_uris" => [
            "http://localhost:3000/auth/talishar/callback",
            "https://fablazing.com/auth/talishar/callback",
            "https://www.fablazing.com/auth/talishar/callback"

        ]
    ]
];

/**
 * Get app configuration by ID
 * @param string $appId
 * @return array|null
 */
function GetOAuthApp($appId) {
    global $allowedOAuthApps;
    return $allowedOAuthApps[$appId] ?? null;
}

/**
 * Validate redirect URI for an app
 * @param string $appId
 * @param string $redirectUri
 * @return bool
 */
function IsValidRedirectUri($appId, $redirectUri) {
    $app = GetOAuthApp($appId);
    if (!$app) return false;
    return in_array($redirectUri, $app["allowed_redirect_uris"], true);
}

/**
 * Base64url encode (for JWT)
 * @param string $data
 * @return string
 */
function Base64UrlEncode($data) {
    return rtrim(strtr(base64_encode($data), "+/", "-_"), "=");
}

/**
 * Generate a signed JWT token
 * @param array $payload
 * @param string $secret
 * @return string
 */
function GenerateJWT($payload, $secret) {
    $header = Base64UrlEncode(json_encode(["alg" => "HS256", "typ" => "JWT"]));
    $payloadEncoded = Base64UrlEncode(json_encode($payload));
    $signature = Base64UrlEncode(
        hash_hmac("sha256", "$header.$payloadEncoded", $secret, true)
    );
    return "$header.$payloadEncoded.$signature";
}
