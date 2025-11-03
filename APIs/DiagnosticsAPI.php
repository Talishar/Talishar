<?php
/**
 * DiagnosticsAPI.php
 * Provides diagnostic information about the database connection and environment setup
 * Only accessible to admin users or localhost
 */

include_once '../Libraries/HTTPLibraries.php';
include_once '../includes/dbh.inc.php';

SetHeaders();

// Restrict access - only allow from localhost or admin users
$clientIP = $_SERVER['REMOTE_ADDR'] ?? '';
$isLocalhost = in_array($clientIP, ['127.0.0.1', '::1', 'localhost']) || strpos($clientIP, '172.') === 0; // Docker networks

if (!$isLocalhost) {
    http_response_code(403);
    echo json_encode(["error" => "Access denied. Only accessible from localhost or local networks."]);
    exit;
}

header('Content-Type: application/json');

$diagnostics = new stdClass();
$diagnostics->timestamp = date('Y-m-d H:i:s');
$diagnostics->clientIP = $clientIP;

// Check PHP environment
$diagnostics->php = new stdClass();
$diagnostics->php->version = phpversion();
$diagnostics->php->extensions = [
    'mysqli' => extension_loaded('mysqli'),
    'pdo' => extension_loaded('pdo'),
    'pdo_mysql' => extension_loaded('pdo_mysql'),
];

// Check environment variables
$diagnostics->environment = new stdClass();
$diagnostics->environment->MYSQL_SERVER_NAME = getenv("MYSQL_SERVER_NAME") ?: "NOT SET (will use 'localhost')";
$diagnostics->environment->MYSQL_SERVER_USER_NAME = getenv("MYSQL_SERVER_USER_NAME") ?: "NOT SET (will use 'root')";
$diagnostics->environment->MYSQL_ROOT_PASSWORD = getenv("MYSQL_ROOT_PASSWORD") ? "SET (value hidden)" : "NOT SET (will use empty string)";
$diagnostics->environment->MYSQL_DATABASE = "fabonline (hardcoded)";

// Try to connect
$diagnostics->connection = new stdClass();
$diagnostics->connection->attempts = [];

for ($i = 1; $i <= 1; $i++) {
    $attempt = new stdClass();
    $attempt->number = $i;
    $attempt->timestamp = microtime(true);
    
    $servername = getenv("MYSQL_SERVER_NAME") ?: "localhost";
    $username = getenv("MYSQL_SERVER_USER_NAME") ?: "root";
    $password = getenv("MYSQL_ROOT_PASSWORD") ?: "";
    $database = "fabonline";
    
    $conn = @mysqli_connect($servername, $username, $password, $database);
    
    if ($conn) {
        $attempt->status = "SUCCESS";
        $attempt->error = null;
        
        // Get server info
        $attempt->server_info = mysqli_get_server_info($conn);
        $attempt->server_version = mysqli_get_server_version($conn);
        $attempt->client_version = mysqli_get_client_version();
        
        // Test a simple query
        $result = mysqli_query($conn, "SELECT 1");
        $attempt->query_test = $result ? "SUCCESS" : "FAILED: " . mysqli_error($conn);
        
        // Check fabonline database
        $result = mysqli_query($conn, "SELECT DATABASE()");
        $row = mysqli_fetch_row($result);
        $attempt->current_database = $row[0];
        
        // List tables
        $result = mysqli_query($conn, "SHOW TABLES");
        $attempt->table_count = mysqli_num_rows($result);
        
        mysqli_close($conn);
    } else {
        $attempt->status = "FAILED";
        $attempt->error = mysqli_connect_error();
    }
    
    $attempt->duration_ms = (microtime(true) - $attempt->timestamp) * 1000;
    $diagnostics->connection->attempts[] = $attempt;
}

// Check error log access
$diagnostics->error_log = new stdClass();
$errorLogPath = ini_get('error_log');
$diagnostics->error_log->path = $errorLogPath;
if (file_exists($errorLogPath)) {
    $diagnostics->error_log->exists = true;
    $diagnostics->error_log->readable = is_readable($errorLogPath);
    $diagnostics->error_log->writable = is_writable($errorLogPath);
    $diagnostics->error_log->size_bytes = filesize($errorLogPath);
    $diagnostics->error_log->last_modified = date('Y-m-d H:i:s', filemtime($errorLogPath));
} else {
    $diagnostics->error_log->exists = false;
    $diagnostics->error_log->readable = false;
    $diagnostics->error_log->writable = false;
}

// Recommendations
$diagnostics->recommendations = [];

$mysqlServerName = getenv("MYSQL_SERVER_NAME");
if (!$mysqlServerName) {
    $diagnostics->recommendations[] = "WARNING: MYSQL_SERVER_NAME not set. Make sure it's defined in Docker environment or server configuration.";
}

$mysqlPassword = getenv("MYSQL_ROOT_PASSWORD");
if (!$mysqlPassword) {
    $diagnostics->recommendations[] = "WARNING: MYSQL_ROOT_PASSWORD not set. Check if credentials are properly configured.";
}

if (isset($diagnostics->connection->attempts[0]) && $diagnostics->connection->attempts[0]->status === "FAILED") {
    $diagnostics->recommendations[] = "CRITICAL: Database connection failed. Check MySQL service status and network connectivity.";
}

echo json_encode($diagnostics, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
?>
