<?php
function TryGET($key, $default = "")
{
  return $_GET[$key] ?? $default;
}

function TryPOST($key, $default = "")
{
  return $_POST[$key] ?? $default;
}

// Decodes a JSON request body without imposing method or Content-Type rules.
// This matches the legacy API endpoints that read php://input directly.
function ReadJsonBody()
{
  return json_decode(file_get_contents('php://input'), true);
}

function WriteJsonResponse($payload, $statusCode = null)
{
  if ($statusCode !== null) http_response_code($statusCode);
  echo json_encode($payload);
}

function ExitJsonResponse($payload, $statusCode = null)
{
  WriteJsonResponse($payload, $statusCode);
  exit;
}

function SetJsonError($response, $message, $statusCode = 400)
{
  http_response_code($statusCode);
  $response->error = $message;
}

// Reads the request body for endpoints that accept either a form-encoded or a JSON POST.
function ReadPostData()
{
  if (!empty($_POST) || $_SERVER['REQUEST_METHOD'] !== 'POST') return $_POST;
  $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
  if (strpos($contentType, 'application/json') === false) return $_POST;
  return ReadJsonBody() ?? [];
}

function TryPOSTData($key, $default = "", $data = [])
{
  return $data[$key] ?? $default;
}

// Copies a library action's {success, message} result onto a JSON response object,
// answering 400 with the message on failure. Returns whether the action succeeded.
function ApplyActionResult($response, $result)
{
  if ($result['success']) {
    $response->success = true;
    $response->message = $result['message'];
    return true;
  }
  http_response_code(400);
  $response->error = $result['message'];
  return false;
}

// Shared setup for authenticated JSON APIs after their dependencies are loaded.
function InitializeAuthenticatedJsonApi($databaseLogKey)
{
  if (session_status() !== PHP_SESSION_ACTIVE) session_start();
  header('Content-Type: application/json');

  if (!IsUserLoggedIn()) {
    ExitJsonResponse(["error" => "Not logged in"], 401);
  }

  $postData = ReadJsonBody();
  if (!$postData) $postData = [];

  $userId = LoggedInUser();
  $connection = GetDBConnection($databaseLogKey);
  if (
    !$connection ||
    (is_object($connection) && isset($connection->connect_error) && $connection->connect_error)
  ) {
    ExitJsonResponse(["error" => "Database connection failed"], 500);
  }

  return [$postData, $userId, $connection];
}

function IsGameNameValid($gameName)
{
  return is_numeric($gameName);
}

function GetGameCounter($path = "./")
{
  global $redirectPath;
  $gameIDCounterFile = $path . "HostFiles/GameIDCounter.txt";

  if (!is_file($gameIDCounterFile)) { // if the game ID counter does not exist, make it.
    $contents = '101';
    file_put_contents($gameIDCounterFile, $contents);
  }

  $gcFile = fopen($gameIDCounterFile, "r+");

  $attemptCount = 0;
  while (!flock($gcFile, LOCK_EX) && $attemptCount < 30) {  // acquire an exclusive lock
    sleep(1);
    ++$attemptCount;
  }
  if ($attemptCount == 30) {
    fclose($gcFile);
    error_log("GetGameCounter: could not lock " . $gameIDCounterFile . " after 30s");
    http_response_code(503);
    exit;
  }
  $counter = intval(fgets($gcFile));
  //$gameName = hash("sha256", $counter);
  $gameName = $counter;
  ftruncate($gcFile, 0);
  rewind($gcFile);
  fwrite($gcFile, $counter + 1);
  flock($gcFile, LOCK_UN);    // release the lock
  fclose($gcFile);
  return $gameName;
}

function IsReplay()
{
  global $gameName;
  static $cache = [];
  if (isset($cache[$gameName])) return $cache[$gameName];
  $val = GetCachePiece($gameName, 10);
  return $cache[$gameName] = ($val == "1");
}

function SetHeaders()
{
  // array holding allowed Origin domains (with fixed regex patterns)
  $allowedOrigins = [
    "~^https?://[0-9a-z\-]*\.talishar\.net$~i",
    "~^https?://talishar\.net$~i",
    "~^https?://www.talishar\.net$~i",
    "~^https?://[0-9a-z\-]*\.talishar-fe\.pages\.dev$~i",
    "~^https?://[0-9a-z\-]*\.talishar-fe-temp\.pages\.dev$~i",
    "~^https?://talishar\.surge\.sh$~i",
    "~^https?://localhost(:[0-9]+)?$~i",
    "~^https?://127\.0\.0\.1(:[0-9]+)?$~i"
  ];

  $originSet = false;

  if (isset($_SERVER['HTTP_ORIGIN']) && $_SERVER['HTTP_ORIGIN'] != '') {
    foreach ($allowedOrigins as $allowedOrigin) {
      if (preg_match($allowedOrigin, $_SERVER['HTTP_ORIGIN'])) {
        header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
        $originSet = true;
        break;
      }
    }
  }

  // Always set CORS headers to fix Brave browser issues
  // If origin didn't match, use wildcard for requests without proper Origin headers
  if (!$originSet && (!isset($_SERVER['HTTP_ORIGIN']) || $_SERVER['HTTP_ORIGIN'] == '')) {
    header('Access-Control-Allow-Origin: *');
  }

  header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
  header('Access-Control-Max-Age: 1000');
  header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
  header('Access-Control-Allow-Credentials: true');
  header('X-Accel-Buffering: no');
}

