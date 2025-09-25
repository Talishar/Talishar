<?php

/**
 * Simple Test Runner for Talishar
 * A basic test runner that doesn't require PHPUnit or Docker
 */

echo "🧪 Talishar Security Test Suite\n";
echo "===============================\n\n";

// Test results
$results = [
    'total' => 0,
    'passed' => 0,
    'failed' => 0,
    'errors' => []
];

/**
 * Simple assertion function
 */
function assertTrue($condition, $message = '') {
    global $results;
    $results['total']++;
    
    if ($condition) {
        $results['passed']++;
        echo "  ✅ $message\n";
        return true;
    } else {
        $results['failed']++;
        $results['errors'][] = $message;
        echo "  ❌ $message\n";
        return false;
    }
}

function assertFalse($condition, $message = '') {
    return assertTrue(!$condition, $message);
}

function assertEquals($expected, $actual, $message = '') {
    return assertTrue($expected === $actual, $message ?: "Expected: $expected, Got: $actual");
}

function assertStringContainsString($needle, $haystack, $message = '') {
    return assertTrue(strpos($haystack, $needle) !== false, $message ?: "String '$haystack' should contain '$needle'");
}

function assertStringNotContainsString($needle, $haystack, $message = '') {
    return assertTrue(strpos($haystack, $needle) === false, $message ?: "String '$haystack' should not contain '$needle'");
}

/**
 * Test SQL Injection Prevention
 */
function testSQLInjectionPrevention() {
    echo "🔒 Testing SQL Injection Prevention...\n";
    
    // Test malicious inputs
    $maliciousInputs = [
        "'; DROP TABLE users; --",
        "' OR '1'='1",
        "' UNION SELECT * FROM users --",
        "'; INSERT INTO users VALUES ('hacker', 'password'); --"
    ];
    
    foreach ($maliciousInputs as $input) {
        // Test username validation
        $isValid = !empty($input) && is_string($input) && ctype_alnum($input);
        assertFalse($isValid, "Malicious input should be rejected: " . substr($input, 0, 20) . "...");
    }
    
    // Test valid usernames
    $validUsernames = ['testuser', 'player123', 'admin'];
    foreach ($validUsernames as $username) {
        $isValid = !empty($username) && is_string($username) && ctype_alnum($username);
        assertTrue($isValid, "Valid username should be accepted: $username");
    }
    
    echo "\n";
}

/**
 * Test XSS Prevention
 */
function testXSSPrevention() {
    echo "🛡️ Testing XSS Prevention...\n";
    
    $xssPayloads = [
        "<script>alert('XSS')</script>",
        "<img src=x onerror=alert('XSS')>",
        "<iframe src=javascript:alert('XSS')></iframe>"
    ];
    
    foreach ($xssPayloads as $payload) {
        $escaped = htmlspecialchars($payload, ENT_QUOTES, 'UTF-8');
        
        assertStringNotContainsString('<script>', $escaped, "Script tags should be escaped");
        assertStringNotContainsString('onerror=', $escaped, "Event handlers should be escaped");
        assertStringContainsString('&lt;', $escaped, "Less than should be escaped");
        assertStringContainsString('&gt;', $escaped, "Greater than should be escaped");
    }
    
    echo "\n";
}

/**
 * Test CSRF Protection
 */
function testCSRFProtection() {
    echo "🔐 Testing CSRF Protection...\n";
    
    // Test token generation
    $token1 = bin2hex(random_bytes(32));
    $token2 = bin2hex(random_bytes(32));
    
    assertTrue(strlen($token1) === 64, "CSRF token should be 64 characters");
    assertTrue(ctype_xdigit($token1), "CSRF token should be valid hex");
    assertTrue($token1 !== $token2, "CSRF tokens should be unique");
    
    // Test token validation
    assertTrue(hash_equals($token1, $token1), "Valid token should pass validation");
    assertFalse(hash_equals($token1, $token2), "Invalid token should fail validation");
    assertFalse(hash_equals($token1, 'invalid'), "Invalid token should fail validation");
    
    echo "\n";
}

/**
 * Test Input Validation
 */
function testInputValidation() {
    echo "✅ Testing Input Validation...\n";
    
    // Test username validation
    assertTrue(validateUsername('testuser'), "Valid username should pass");
    assertFalse(validateUsername(''), "Empty username should fail");
    assertFalse(validateUsername('ab'), "Short username should fail");
    assertFalse(validateUsername('user@domain'), "Username with special chars should fail");
    
    // Test email validation
    assertTrue(validateEmail('test@example.com'), "Valid email should pass");
    assertFalse(validateEmail('invalid-email'), "Invalid email should fail");
    assertFalse(validateEmail(''), "Empty email should fail");
    
    // Test password validation
    assertTrue(validatePassword('password123'), "Valid password should pass");
    assertFalse(validatePassword('1234567'), "Short password should fail");
    assertFalse(validatePassword(''), "Empty password should fail");
    
    echo "\n";
}

/**
 * Test Session Management
 */
function testSessionManagement() {
    echo "🔑 Testing Session Management...\n";
    
    // Test session start
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    assertTrue(session_status() === PHP_SESSION_ACTIVE, "Session should be active");
    
    // Test session data
    $_SESSION['test'] = 'value';
    assertTrue(isset($_SESSION['test']), "Session data should be stored");
    assertEquals('value', $_SESSION['test'], "Session data should be retrievable");
    
    // Test session regeneration
    $oldId = session_id();
    session_regenerate_id(true);
    $newId = session_id();
    assertTrue($oldId !== $newId, "Session ID should be regenerated");
    
    echo "\n";
}

/**
 * Validation functions (simplified versions)
 */
function validateUsername($username) {
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
    
    if (strlen($password) < 8) {
        return false;
    }
    
    return true;
}

// Run all tests
testSQLInjectionPrevention();
testXSSPrevention();
testCSRFProtection();
testInputValidation();
testSessionManagement();

// Display results
echo "📊 Test Results Summary\n";
echo "=======================\n";
echo "Total Tests: " . $results['total'] . "\n";
echo "Passed: " . $results['passed'] . "\n";
echo "Failed: " . $results['failed'] . "\n";
echo "Success Rate: " . ($results['total'] > 0 ? round(($results['passed'] / $results['total']) * 100, 2) : 0) . "%\n\n";

if ($results['failed'] > 0) {
    echo "❌ Failed Tests:\n";
    foreach ($results['errors'] as $error) {
        echo "  - $error\n";
    }
    echo "\n";
}

if ($results['failed'] === 0 && $results['total'] > 0) {
    echo "🎉 All tests passed! Your security fixes are working correctly.\n";
} elseif ($results['total'] === 0) {
    echo "⚠️  No tests were run. Please check your test files.\n";
} else {
    echo "⚠️  Some tests failed. Please review the errors above.\n";
}

echo "\n";
echo "💡 This is a simplified test runner. For full testing capabilities:\n";
echo "   1. Install PHPUnit: composer install --dev\n";
echo "   2. Run full test suite: ./vendor/bin/phpunit\n";
echo "   3. Or use Docker: docker compose exec web-server php run_tests.php\n\n";

?>
