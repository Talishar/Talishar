<?php

/**
 * Input Validation Library
 * Provides functions for validating and sanitizing user input
 */

function validateUsername($username) {
    // Username should be alphanumeric and between 3-20 characters
    if (empty($username) || !is_string($username)) {
        return false;
    }
    
    if (strlen($username) < 3 || strlen($username) > 20) {
        return false;
    }
    
    if (!ctype_alnum($username)) {
        return false;
    }
    
    return true;
}

function validateEmail($email) {
    if (empty($email) || !is_string($email)) {
        return false;
    }
    
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function validatePassword($password) {
    if (empty($password) || !is_string($password)) {
        return false;
    }
    
    // Password should be at least 8 characters
    if (strlen($password) < 8) {
        return false;
    }
    
    return true;
}

function validateGameName($gameName) {
    if (empty($gameName) || !is_string($gameName)) {
        return false;
    }
    
    // Game name should be alphanumeric and between 3-50 characters
    if (strlen($gameName) < 3 || strlen($gameName) > 50) {
        return false;
    }
    
    if (!ctype_alnum($gameName)) {
        return false;
    }
    
    return true;
}

function validatePlayerID($playerID) {
    $playerID = intval($playerID);
    return ($playerID >= 1 && $playerID <= 3);
}

function validateCardID($cardID) {
    if (empty($cardID) || !is_string($cardID)) {
        return false;
    }
    
    // Card ID should be alphanumeric and reasonable length
    if (strlen($cardID) < 1 || strlen($cardID) > 100) {
        return false;
    }
    
    return true;
}

function sanitizeString($input) {
    if (!is_string($input)) {
        return '';
    }
    
    // Remove null bytes and trim whitespace
    $input = str_replace("\0", '', $input);
    $input = trim($input);
    
    return $input;
}

function sanitizeHTML($input) {
    if (!is_string($input)) {
        return '';
    }
    
    return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
}

function validateInteger($input, $min = null, $max = null) {
    $value = intval($input);
    
    if ($min !== null && $value < $min) {
        return false;
    }
    
    if ($max !== null && $value > $max) {
        return false;
    }
    
    return true;
}

function validateBoolean($input) {
    return in_array($input, [true, false, 1, 0, '1', '0', 'true', 'false'], true);
}

function validateJSON($input) {
    if (!is_string($input)) {
        return false;
    }
    
    json_decode($input);
    return json_last_error() === JSON_ERROR_NONE;
}

function validateURL($url) {
    if (empty($url) || !is_string($url)) {
        return false;
    }
    
    return filter_var($url, FILTER_VALIDATE_URL) !== false;
}

function validateIP($ip) {
    if (empty($ip) || !is_string($ip)) {
        return false;
    }
    
    return filter_var($ip, FILTER_VALIDATE_IP) !== false;
}

function validateFileExtension($filename, $allowedExtensions) {
    if (empty($filename) || !is_string($filename)) {
        return false;
    }
    
    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    return in_array($extension, $allowedExtensions, true);
}

function validateFileSize($fileSize, $maxSize) {
    return $fileSize <= $maxSize;
}

function validateMimeType($filePath, $allowedMimeTypes) {
    if (!file_exists($filePath)) {
        return false;
    }
    
    $mimeType = mime_content_type($filePath);
    return in_array($mimeType, $allowedMimeTypes, true);
}

?>
