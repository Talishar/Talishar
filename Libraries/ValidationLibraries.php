<?php

/**
 * Input Validation Library
 * Provides functions for validating and sanitizing user input
 */

function validateUsername($username) {
    // Username should be alphanumeric (plus underscores), start with a letter,
    // and be between 3-20 characters
    if (empty($username) || !is_string($username)) {
        return false;
    }
    
    if (strlen($username) < 3 || strlen($username) > 20) {
        return false;
    }
    
    if (!preg_match('/^[a-zA-Z][a-zA-Z0-9_]{2,19}$/', $username)) {
        return false;
    }
    
    return true;
}

function validateEmail($email) {
    if (empty($email) || !is_string($email)) {
        return false;
    }
    
    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        return false;
    }
    
    // Additionally require TLD of at least 2 characters
    $parts = explode('@', $email);
    if (count($parts) === 2) {
        $domain = $parts[1];
        $domainParts = explode('.', $domain);
        $tld = end($domainParts);
        if (strlen($tld) < 2) {
            return false;
        }
    }
    
    return true;
}

function validatePassword($password) {
    if (empty($password) || !is_string($password)) {
        return false;
    }
    
    // Password should be between 8 and 100 characters
    if (strlen($password) < 8 || strlen($password) > 100) {
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
    if (!is_numeric($playerID)) {
        return false;
    }
    $playerID = intval($playerID);
    return ($playerID >= 1 && $playerID <= 3);
}

function validateCardID($cardID) {
    if (empty($cardID) || !is_string($cardID)) {
        return false;
    }
    
    // Card ID should be alphanumeric (plus hyphens/underscores) and reasonable length
    if (strlen($cardID) < 1 || strlen($cardID) > 100) {
        return false;
    }
    
    if (!preg_match('/^[a-zA-Z0-9_-]+$/', $cardID)) {
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
    if ($input === null || $input === '') {
        return false;
    }
    
    if (filter_var($input, FILTER_VALIDATE_INT) === false) {
        return false;
    }
    
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
    
    if (filter_var($url, FILTER_VALIDATE_URL) === false) {
        return false;
    }
    
    // Additionally require a proper domain with TLD (dot present in host)
    $parsed = parse_url($url);
    if (!isset($parsed['host']) || strpos($parsed['host'], '.') === false) {
        return false;
    }
    
    return true;
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
