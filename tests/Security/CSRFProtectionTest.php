<?php

namespace Talishar\Tests\Security;

use PHPUnit\Framework\TestCase;

/**
 * CSRF Protection Tests
 * Tests the CSRF token generation and validation functionality
 */
class CSRFProtectionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        // Clear any existing session data
        $_SESSION = [];
        $_POST = [];
        $_GET = [];
        $_SERVER = [];
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        
        // Clean up after each test
        $_SESSION = [];
        $_POST = [];
        $_GET = [];
        $_SERVER = [];
    }

    /**
     * Test CSRF token generation
     */
    public function testCSRFTokenGeneration()
    {
        // Start session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Generate token
        $token1 = generateCSRFToken();
        $token2 = generateCSRFToken();
        
        // Tokens should be the same for the same session
        $this->assertEquals($token1, $token2, "Same session should generate same token");
        
        // Token should be stored in session
        $this->assertArrayHasKey('csrf_token', $_SESSION, "CSRF token should be stored in session");
        $this->assertEquals($token1, $_SESSION['csrf_token'], "Generated token should match session token");
        
        // Token should be a valid hex string
        $this->assertMatchesRegularExpression('/^[a-f0-9]{64}$/', $token1, "CSRF token should be 64-character hex string");
    }

    /**
     * Test CSRF token validation with valid token
     */
    public function testCSRFTokenValidationValid()
    {
        // Start session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Generate token
        $token = generateCSRFToken();
        
        // Validate token
        $this->assertTrue(validateCSRFToken($token), "Valid token should pass validation");
    }

    /**
     * Test CSRF token validation with invalid token
     */
    public function testCSRFTokenValidationInvalid()
    {
        // Start session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Generate token
        generateCSRFToken();
        
        // Test with invalid tokens
        $invalidTokens = [
            'invalid_token',
            '1234567890abcdef',
            '',
            null,
            'a' . str_repeat('0', 63), // Wrong length
            str_repeat('g', 64), // Invalid hex characters
            'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA' // All same character
        ];
        
        foreach ($invalidTokens as $invalidToken) {
            $this->assertFalse(
                validateCSRFToken($invalidToken), 
                "Invalid token should fail validation: " . var_export($invalidToken, true)
            );
        }
    }

    /**
     * Test CSRF token validation without session token
     */
    public function testCSRFTokenValidationNoSessionToken()
    {
        // Start session but don't generate token
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Try to validate without generating token
        $this->assertFalse(validateCSRFToken('any_token'), "Validation should fail when no session token exists");
    }

    /**
     * Test CSRF token field generation
     */
    public function testCSRFTokenFieldGeneration()
    {
        // Start session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Generate token field
        $field = getCSRFTokenField();
        
        // Field should contain hidden input
        $this->assertStringContainsString('<input type="hidden"', $field, "Field should contain hidden input");
        $this->assertStringContainsString('name="csrf_token"', $field, "Field should have correct name");
        $this->assertStringContainsString('value="', $field, "Field should have value attribute");
        
        // Extract token from field
        preg_match('/value="([^"]+)"/', $field, $matches);
        $this->assertCount(2, $matches, "Should extract token value from field");
        
        $extractedToken = $matches[1];
        $this->assertMatchesRegularExpression('/^[a-f0-9]{64}$/', $extractedToken, "Extracted token should be valid");
        
        // Token should be HTML escaped
        $this->assertStringNotContainsString('<', $extractedToken, "Token should not contain unescaped HTML");
        $this->assertStringNotContainsString('>', $extractedToken, "Token should not contain unescaped HTML");
        $this->assertStringNotContainsString('"', $extractedToken, "Token should not contain unescaped quotes");
    }

    /**
     * Test CSRF token requirement for POST requests
     */
    public function testCSRFTokenRequirement()
    {
        // Start session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Set up POST request
        $_SERVER['REQUEST_METHOD'] = 'POST';
        
        // Test without token
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('CSRF token validation failed');
        requireCSRFToken();
    }

    /**
     * Test CSRF token requirement with valid token
     */
    public function testCSRFTokenRequirementValid()
    {
        // Start session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Set up POST request with valid token
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $token = generateCSRFToken();
        $_POST['csrf_token'] = $token;
        
        // Should not throw exception
        $this->expectNotToPerformAssertions();
        requireCSRFToken();
    }

    /**
     * Test CSRF token requirement with invalid token
     */
    public function testCSRFTokenRequirementInvalid()
    {
        // Start session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Set up POST request with invalid token
        $_SERVER['REQUEST_METHOD'] = 'POST';
        generateCSRFToken(); // Generate valid token
        $_POST['csrf_token'] = 'invalid_token';
        
        // Should throw exception
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('CSRF token validation failed');
        requireCSRFToken();
    }

    /**
     * Test CSRF token requirement for GET requests
     */
    public function testCSRFTokenRequirementGetRequest()
    {
        // Start session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Set up GET request
        $_SERVER['REQUEST_METHOD'] = 'GET';
        
        // Should not throw exception for GET requests
        $this->expectNotToPerformAssertions();
        requireCSRFToken();
    }

    /**
     * Test token uniqueness across sessions
     */
    public function testCSRFTokenUniqueness()
    {
        // Generate tokens in different "sessions"
        $tokens = [];
        
        for ($i = 0; $i < 10; $i++) {
            // Simulate different sessions by clearing session
            $_SESSION = [];
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            $tokens[] = generateCSRFToken();
        }
        
        // All tokens should be unique
        $uniqueTokens = array_unique($tokens);
        $this->assertCount(count($tokens), $uniqueTokens, "All generated tokens should be unique");
    }

    /**
     * Test token security properties
     */
    public function testCSRFTokenSecurityProperties()
    {
        // Start session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $token = generateCSRFToken();
        
        // Token should be cryptographically secure
        $this->assertEquals(64, strlen($token), "Token should be 64 characters long");
        $this->assertMatchesRegularExpression('/^[a-f0-9]{64}$/', $token, "Token should be valid hex");
        
        // Token should not be predictable
        $tokens = [];
        for ($i = 0; $i < 100; $i++) {
            $_SESSION = [];
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $tokens[] = generateCSRFToken();
        }
        
        // Check for patterns (basic randomness test)
        $firstChars = array_map(function($token) { return $token[0]; }, $tokens);
        $uniqueFirstChars = array_unique($firstChars);
        $this->assertGreaterThan(5, count($uniqueFirstChars), "Tokens should have varied first characters");
    }

    /**
     * Test timing attack resistance
     */
    public function testCSRFTokenTimingAttackResistance()
    {
        // Start session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $validToken = generateCSRFToken();
        
        // Test that validation time is consistent regardless of token similarity
        $invalidTokens = [
            'a' . substr($validToken, 1), // One character different at start
            substr($validToken, 0, -1) . 'a', // One character different at end
            'invalid_token_completely_different',
            ''
        ];
        
        $times = [];
        foreach ($invalidTokens as $invalidToken) {
            $start = microtime(true);
            validateCSRFToken($invalidToken);
            $times[] = microtime(true) - $start;
        }
        
        // Times should be relatively similar (within 10ms)
        $maxTime = max($times);
        $minTime = min($times);
        $this->assertLessThan(0.01, $maxTime - $minTime, "Validation times should be consistent to prevent timing attacks");
    }
}
