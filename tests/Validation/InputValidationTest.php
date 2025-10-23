<?php

namespace Talishar\Tests\Validation;

use PHPUnit\Framework\TestCase;

/**
 * Input Validation Tests
 * Tests the input validation and sanitization functions
 */
class InputValidationTest extends TestCase
{
    /**
     * Test username validation
     */
    public function testUsernameValidation()
    {
        // Valid usernames
        $validUsernames = [
            'testuser',
            'player123',
            'admin',
            'TestUser',
            'user_123',
            'player1',
            'abc',
            'a' . str_repeat('b', 19) // 20 characters
        ];

        foreach ($validUsernames as $username) {
            $this->assertTrue(
                validateUsername($username),
                "Valid username should pass: " . $username
            );
        }

        // Invalid usernames
        $invalidUsernames = [
            '', // Empty
            'ab', // Too short
            'a' . str_repeat('b', 20), // Too long (21 characters)
            'user@domain', // Contains special characters
            'user name', // Contains space
            'user-name', // Contains hyphen
            'user.name', // Contains dot
            'user_name', // Contains underscore
            '123', // Only numbers
            'test<script>', // Contains HTML
            'test; DROP TABLE users; --', // SQL injection attempt
            null, // Null value
            123, // Non-string
            [], // Array
            new stdClass() // Object
        ];

        foreach ($invalidUsernames as $username) {
            $this->assertFalse(
                validateUsername($username),
                "Invalid username should fail: " . var_export($username, true)
            );
        }
    }

    /**
     * Test email validation
     */
    public function testEmailValidation()
    {
        // Valid emails
        $validEmails = [
            'test@example.com',
            'user.name@domain.co.uk',
            'user+tag@example.org',
            'user123@test-domain.com',
            'a@b.co',
            'test@sub.domain.com'
        ];

        foreach ($validEmails as $email) {
            $this->assertTrue(
                validateEmail($email),
                "Valid email should pass: " . $email
            );
        }

        // Invalid emails
        $invalidEmails = [
            '', // Empty
            'invalid-email', // No @ symbol
            '@domain.com', // No local part
            'user@', // No domain
            'user@domain', // No TLD
            'user..name@domain.com', // Double dots
            'user@domain..com', // Double dots in domain
            'user@domain.com.', // Trailing dot
            '.user@domain.com', // Leading dot
            'user@domain.c', // Too short TLD
            'user name@domain.com', // Space in local part
            'user@domain name.com', // Space in domain
            'user@domain.com<script>', // HTML injection
            'user@domain.com; DROP TABLE users; --', // SQL injection
            null, // Null value
            123, // Non-string
            [], // Array
            new stdClass() // Object
        ];

        foreach ($invalidEmails as $email) {
            $this->assertFalse(
                validateEmail($email),
                "Invalid email should fail: " . var_export($email, true)
            );
        }
    }

    /**
     * Test password validation
     */
    public function testPasswordValidation()
    {
        // Valid passwords
        $validPasswords = [
            'password123',
            'MySecurePassword!',
            '12345678',
            'a' . str_repeat('b', 99), // 100 characters
            'P@ssw0rd',
            'simplepassword'
        ];

        foreach ($validPasswords as $password) {
            $this->assertTrue(
                validatePassword($password),
                "Valid password should pass: " . $password
            );
        }

        // Invalid passwords
        $invalidPasswords = [
            '', // Empty
            '1234567', // Too short (7 characters)
            'a' . str_repeat('b', 100), // Too long (101 characters)
            null, // Null value
            12345678, // Non-string
            [], // Array
            new stdClass() // Object
        ];

        foreach ($invalidPasswords as $password) {
            $this->assertFalse(
                validatePassword($password),
                "Invalid password should fail: " . var_export($password, true)
            );
        }
    }

    /**
     * Test game name validation
     */
    public function testGameNameValidation()
    {
        // Valid game names
        $validGameNames = [
            'game123',
            'TestGame',
            'mygame',
            'abc',
            'a' . str_repeat('b', 49) // 50 characters
        ];

        foreach ($validGameNames as $gameName) {
            $this->assertTrue(
                validateGameName($gameName),
                "Valid game name should pass: " . $gameName
            );
        }

        // Invalid game names
        $invalidGameNames = [
            '', // Empty
            'ab', // Too short
            'a' . str_repeat('b', 50), // Too long (51 characters)
            'game name', // Contains space
            'game-name', // Contains hyphen
            'game.name', // Contains dot
            'game_name', // Contains underscore
            'game<script>', // Contains HTML
            'game; DROP TABLE games; --', // SQL injection attempt
            null, // Null value
            123, // Non-string
            [], // Array
            new stdClass() // Object
        ];

        foreach ($invalidGameNames as $gameName) {
            $this->assertFalse(
                validateGameName($gameName),
                "Invalid game name should fail: " . var_export($gameName, true)
            );
        }
    }

    /**
     * Test player ID validation
     */
    public function testPlayerIDValidation()
    {
        // Valid player IDs
        $validPlayerIDs = [1, 2, 3, '1', '2', '3'];

        foreach ($validPlayerIDs as $playerID) {
            $this->assertTrue(
                validatePlayerID($playerID),
                "Valid player ID should pass: " . var_export($playerID, true)
            );
        }

        // Invalid player IDs
        $invalidPlayerIDs = [
            0, // Too low
            4, // Too high
            -1, // Negative
            '0', // String zero
            '4', // String four
            'invalid', // Non-numeric string
            '', // Empty string
            null, // Null value
            [], // Array
            new stdClass() // Object
        ];

        foreach ($invalidPlayerIDs as $playerID) {
            $this->assertFalse(
                validatePlayerID($playerID),
                "Invalid player ID should fail: " . var_export($playerID, true)
            );
        }
    }

    /**
     * Test card ID validation
     */
    public function testCardIDValidation()
    {
        // Valid card IDs
        $validCardIDs = [
            'DYN001',
            'MON123',
            'WTR456',
            'ARC789',
            'a', // Minimum length
            str_repeat('a', 100) // Maximum length
        ];

        foreach ($validCardIDs as $cardID) {
            $this->assertTrue(
                validateCardID($cardID),
                "Valid card ID should pass: " . $cardID
            );
        }

        // Invalid card IDs
        $invalidCardIDs = [
            '', // Empty
            str_repeat('a', 101), // Too long (101 characters)
            null, // Null value
            123, // Non-string
            [], // Array
            new stdClass() // Object
        ];

        foreach ($invalidCardIDs as $cardID) {
            $this->assertFalse(
                validateCardID($cardID),
                "Invalid card ID should fail: " . var_export($cardID, true)
            );
        }
    }

    /**
     * Test integer validation
     */
    public function testIntegerValidation()
    {
        // Valid integers
        $this->assertTrue(validateInteger('123'));
        $this->assertTrue(validateInteger(123));
        $this->assertTrue(validateInteger('0'));
        $this->assertTrue(validateInteger('-123'));

        // Test with min/max constraints
        $this->assertTrue(validateInteger('5', 1, 10));
        $this->assertTrue(validateInteger('1', 1, 10));
        $this->assertTrue(validateInteger('10', 1, 10));

        // Invalid integers
        $this->assertFalse(validateInteger('abc'));
        $this->assertFalse(validateInteger('12.34'));
        $this->assertFalse(validateInteger(''));
        $this->assertFalse(validateInteger(null));

        // Test constraints
        $this->assertFalse(validateInteger('0', 1, 10)); // Below min
        $this->assertFalse(validateInteger('11', 1, 10)); // Above max
    }

    /**
     * Test boolean validation
     */
    public function testBooleanValidation()
    {
        // Valid booleans
        $validBooleans = [true, false, 1, 0, '1', '0', 'true', 'false'];

        foreach ($validBooleans as $boolean) {
            $this->assertTrue(
                validateBoolean($boolean),
                "Valid boolean should pass: " . var_export($boolean, true)
            );
        }

        // Invalid booleans
        $invalidBooleans = [
            'yes',
            'no',
            'on',
            'off',
            'enabled',
            'disabled',
            '',
            null,
            [],
            new stdClass()
        ];

        foreach ($invalidBooleans as $boolean) {
            $this->assertFalse(
                validateBoolean($boolean),
                "Invalid boolean should fail: " . var_export($boolean, true)
            );
        }
    }

    /**
     * Test JSON validation
     */
    public function testJSONValidation()
    {
        // Valid JSON
        $validJSON = [
            '{"key": "value"}',
            '{"numbers": [1, 2, 3]}',
            '{"nested": {"key": "value"}}',
            '[]',
            '{}',
            '"string"',
            '123',
            'true',
            'false',
            'null'
        ];

        foreach ($validJSON as $json) {
            $this->assertTrue(
                validateJSON($json),
                "Valid JSON should pass: " . $json
            );
        }

        // Invalid JSON
        $invalidJSON = [
            '{key: "value"}', // Missing quotes around key
            '{"key": value}', // Missing quotes around value
            '{"key": "value",}', // Trailing comma
            '{', // Incomplete
            '}', // Incomplete
            '', // Empty
            null, // Null
            123, // Non-string
            [], // Array
            new stdClass() // Object
        ];

        foreach ($invalidJSON as $json) {
            $this->assertFalse(
                validateJSON($json),
                "Invalid JSON should fail: " . var_export($json, true)
            );
        }
    }

    /**
     * Test URL validation
     */
    public function testURLValidation()
    {
        // Valid URLs
        $validURLs = [
            'https://example.com',
            'http://example.com',
            'https://www.example.com',
            'https://example.com/path',
            'https://example.com/path?query=value',
            'https://example.com/path#fragment',
            'https://subdomain.example.com',
            'https://example.com:8080',
            'ftp://example.com',
            'https://example.co.uk'
        ];

        foreach ($validURLs as $url) {
            $this->assertTrue(
                validateURL($url),
                "Valid URL should pass: " . $url
            );
        }

        // Invalid URLs
        $invalidURLs = [
            '', // Empty
            'not-a-url', // No protocol
            'https://', // No domain
            'https://example', // No TLD
            'javascript:alert("xss")', // JavaScript protocol
            'data:text/html,<script>alert("xss")</script>', // Data protocol
            'file:///etc/passwd', // File protocol
            null, // Null value
            123, // Non-string
            [], // Array
            new stdClass() // Object
        ];

        foreach ($invalidURLs as $url) {
            $this->assertFalse(
                validateURL($url),
                "Invalid URL should fail: " . var_export($url, true)
            );
        }
    }

    /**
     * Test IP validation
     */
    public function testIPValidation()
    {
        // Valid IPs
        $validIPs = [
            '192.168.1.1',
            '127.0.0.1',
            '8.8.8.8',
            '255.255.255.255',
            '0.0.0.0',
            '2001:0db8:85a3:0000:0000:8a2e:0370:7334', // IPv6
            '::1', // IPv6 localhost
            '2001:db8::1' // IPv6 shortened
        ];

        foreach ($validIPs as $ip) {
            $this->assertTrue(
                validateIP($ip),
                "Valid IP should pass: " . $ip
            );
        }

        // Invalid IPs
        $invalidIPs = [
            '', // Empty
            '256.256.256.256', // Out of range
            '192.168.1', // Incomplete
            '192.168.1.1.1', // Too many octets
            'not-an-ip', // Not an IP
            '192.168.1.1:8080', // Port included
            null, // Null value
            123, // Non-string
            [], // Array
            new stdClass() // Object
        ];

        foreach ($invalidIPs as $ip) {
            $this->assertFalse(
                validateIP($ip),
                "Invalid IP should fail: " . var_export($ip, true)
            );
        }
    }

    /**
     * Test string sanitization
     */
    public function testStringSanitization()
    {
        $inputs = [
            'normal string',
            'string with null\0byte',
            '  string with spaces  ',
            'string with\ttab',
            'string with\nnewline',
            'string with\rcarriage return'
        ];

        foreach ($inputs as $input) {
            $sanitized = sanitizeString($input);
            
            // Should remove null bytes
            $this->assertStringNotContainsString("\0", $sanitized, "Null bytes should be removed");
            
            // Should trim whitespace
            if ($input === '  string with spaces  ') {
                $this->assertEquals('string with spaces', $sanitized, "Leading/trailing spaces should be trimmed");
            }
            
            // Should preserve other content
            $this->assertStringContainsString('string', $sanitized, "Valid content should be preserved");
        }
    }

    /**
     * Test HTML sanitization
     */
    public function testHTMLSanitization()
    {
        $inputs = [
            'normal text',
            '<script>alert("xss")</script>',
            '<img src=x onerror=alert("xss")>',
            'text with <strong>bold</strong> tags',
            'text with "quotes" and \'apostrophes\'',
            'text with < and > symbols'
        ];

        foreach ($inputs as $input) {
            $sanitized = sanitizeHTML($input);
            
            // Should escape HTML entities
            $this->assertStringContainsString('&lt;', $sanitized, "Less than should be escaped");
            $this->assertStringContainsString('&gt;', $sanitized, "Greater than should be escaped");
            $this->assertStringContainsString('&quot;', $sanitized, "Double quotes should be escaped");
            $this->assertStringContainsString('&#039;', $sanitized, "Single quotes should be escaped");
            
            // Should not contain unescaped HTML
            $this->assertStringNotContainsString('<script>', $sanitized, "Script tags should be escaped");
            $this->assertStringNotContainsString('<img', $sanitized, "Img tags should be escaped");
            $this->assertStringNotContainsString('<strong>', $sanitized, "Strong tags should be escaped");
        }
    }
}
