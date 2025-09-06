<?php

/**
 * Test Bootstrap File
 * Sets up the testing environment for Talishar
 */

// Set error reporting for tests
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define test constants
define('TEST_MODE', true);
define('ROOT_PATH', dirname(__DIR__));

// Mock session for testing
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include necessary files for testing
require_once ROOT_PATH . '/includes/functions.inc.php';
require_once ROOT_PATH . '/Libraries/ValidationLibraries.php';
require_once ROOT_PATH . '/Libraries/CSRFLibraries.php';
require_once ROOT_PATH . '/AccountFiles/AccountSessionAPI.php';

// Mock database connection for testing
function GetTestDBConnection() {
    // Return a mock connection for testing
    return new stdClass();
}

// Override database functions for testing
if (!function_exists('GetDBConnection')) {
    function GetDBConnection() {
        return GetTestDBConnection();
    }
}

if (!function_exists('GetLocalMySQLConnection')) {
    function GetLocalMySQLConnection() {
        return GetTestDBConnection();
    }
}

// Mock other functions that might be called during tests
if (!function_exists('IsGameNameValid')) {
    function IsGameNameValid($gameName) {
        return !empty($gameName) && ctype_alnum($gameName) && strlen($gameName) >= 3 && strlen($gameName) <= 50;
    }
}

if (!function_exists('TryGET')) {
    function TryGET($key, $default = '') {
        return $_GET[$key] ?? $default;
    }
}

if (!function_exists('TryPOST')) {
    function TryPOST($key, $default = '') {
        return $_POST[$key] ?? $default;
    }
}

if (!function_exists('SetHeaders')) {
    function SetHeaders() {
        // Mock function for testing
    }
}

if (!function_exists('PatreonLogin')) {
    function PatreonLogin($token, $refresh = false, $debug = false) {
        // Mock function for testing
        return true;
    }
}

if (!function_exists('BanPlayer')) {
    function BanPlayer($player) {
        // Mock function for testing
        return true;
    }
}

if (!function_exists('DeleteCache')) {
    function DeleteCache($gameName) {
        // Mock function for testing
        return true;
    }
}

if (!function_exists('deleteDirectory')) {
    function deleteDirectory($dir) {
        // Mock function for testing
        return true;
    }
}

// Set up test environment
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['HTTP_HOST'] = 'localhost';
$_SERVER['REQUEST_URI'] = '/test';

?>
