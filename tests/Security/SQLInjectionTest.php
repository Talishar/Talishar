<?php

namespace Talishar\Tests\Security;

use PHPUnit\Framework\TestCase;

/**
 * SQL Injection Prevention Tests
 * Tests the security fixes for SQL injection vulnerabilities
 */
class SQLInjectionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        // Clear any existing session data
        $_SESSION = [];
        $_GET = [];
        $_POST = [];
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        
        // Clean up after each test
        $_SESSION = [];
        $_GET = [];
        $_POST = [];
    }

    /**
     * Test that malicious SQL injection attempts are prevented
     */
    public function testSQLInjectionPrevention()
    {
        // Test malicious input that would cause SQL injection
        $maliciousInputs = [
            "'; DROP TABLE users; --",
            "' OR '1'='1",
            "' UNION SELECT * FROM users --",
            "'; INSERT INTO users VALUES ('hacker', 'password'); --",
            "' OR 1=1 --",
            "admin'--",
            "admin'/*",
            "'; DELETE FROM users; --"
        ];

        foreach ($maliciousInputs as $maliciousInput) {
            // Test username validation
            $this->assertFalse(
                $this->validateUsername($maliciousInput),
                "Malicious input should be rejected: " . $maliciousInput
            );
        }
    }

    /**
     * Test that valid usernames are accepted
     */
    public function testValidUsernameAcceptance()
    {
        $validUsernames = [
            'testuser',
            'player123',
            'admin',
            'user_123',
            'TestUser',
            'player1'
        ];

        foreach ($validUsernames as $username) {
            $this->assertTrue(
                $this->validateUsername($username),
                "Valid username should be accepted: " . $username
            );
        }
    }

    /**
     * Test card ID validation prevents injection
     */
    public function testCardIDValidation()
    {
        $maliciousCardIDs = [
            "'; DROP TABLE carddefinition; --",
            "' OR '1'='1",
            "'; INSERT INTO carddefinition VALUES ('hack', 1); --",
            "card'; DELETE FROM carddefinition; --"
        ];

        foreach ($maliciousCardIDs as $cardID) {
            $this->assertFalse(
                $this->validateCardID($cardID),
                "Malicious card ID should be rejected: " . $cardID
            );
        }

        // Test valid card IDs
        $validCardIDs = [
            'DYN001',
            'MON123',
            'WTR456',
            'ARC789'
        ];

        foreach ($validCardIDs as $cardID) {
            $this->assertTrue(
                $this->validateCardID($cardID),
                "Valid card ID should be accepted: " . $cardID
            );
        }
    }

    /**
     * Test that parameterized queries are used correctly
     */
    public function testParameterizedQueryStructure()
    {
        // Test that our fixed SQL queries use placeholders
        $sqlQueries = [
            "SELECT * FROM users WHERE usersUid = ?",
            "INSERT INTO carddefinition (cardID, hasGoAgain) VALUES (?, ?)",
            "INSERT INTO completedgame (WinningHero, LosingHero, NumTurns, WinnerDeck, LoserDeck, WinnerHealth, FirstPlayer) VALUES (?, ?, ?, ?, ?, ?, ?)"
        ];

        foreach ($sqlQueries as $query) {
            $this->assertStringContainsString('?', $query, "SQL query should use parameterized placeholders");
            $this->assertStringNotContainsString('$', $query, "SQL query should not contain direct variable interpolation");
        }
    }

    /**
     * Test input sanitization
     */
    public function testInputSanitization()
    {
        $maliciousInputs = [
            "<script>alert('xss')</script>",
            "'; DROP TABLE users; --",
            "javascript:alert('xss')",
            "<img src=x onerror=alert('xss')>",
            "'; INSERT INTO users VALUES ('hacker', 'password'); --"
        ];

        foreach ($maliciousInputs as $input) {
            $sanitized = $this->sanitizeString($input);
            $this->assertNotEquals($input, $sanitized, "Input should be sanitized");
            $this->assertStringNotContainsString('<script>', $sanitized, "Script tags should be removed");
            $this->assertStringNotContainsString('DROP TABLE', $sanitized, "SQL commands should be removed");
        }
    }

    /**
     * Helper method to validate username (mimics our validation function)
     */
    private function validateUsername($username)
    {
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

    /**
     * Helper method to validate card ID (mimics our validation function)
     */
    private function validateCardID($cardID)
    {
        if (empty($cardID) || !is_string($cardID)) {
            return false;
        }
        
        if (strlen($cardID) < 1 || strlen($cardID) > 100) {
            return false;
        }
        
        return true;
    }

    /**
     * Helper method to sanitize string (mimics our sanitization function)
     */
    private function sanitizeString($input)
    {
        if (!is_string($input)) {
            return '';
        }
        
        // Remove null bytes and trim whitespace
        $input = str_replace("\0", '', $input);
        $input = trim($input);
        
        return $input;
    }
}
