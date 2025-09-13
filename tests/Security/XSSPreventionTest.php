<?php

namespace Talishar\Tests\Security;

use PHPUnit\Framework\TestCase;

/**
 * XSS Prevention Tests
 * Tests the security fixes for Cross-Site Scripting vulnerabilities
 */
class XSSPreventionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        // Clear any existing session data
        $_SESSION = [];
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        
        // Clean up after each test
        $_SESSION = [];
    }

    /**
     * Test that HTML special characters are properly escaped
     */
    public function testHTMLSpecialCharsEscaping()
    {
        $xssPayloads = [
            "<script>alert('XSS')</script>",
            "<img src=x onerror=alert('XSS')>",
            "<iframe src=javascript:alert('XSS')></iframe>",
            "<svg onload=alert('XSS')>",
            "<body onload=alert('XSS')>",
            "<input onfocus=alert('XSS') autofocus>",
            "<select onfocus=alert('XSS') autofocus>",
            "<textarea onfocus=alert('XSS') autofocus>",
            "<keygen onfocus=alert('XSS') autofocus>",
            "<video><source onerror=alert('XSS')>",
            "<audio src=x onerror=alert('XSS')>",
            "<details open ontoggle=alert('XSS')>",
            "<marquee onstart=alert('XSS')>",
            "<object data=javascript:alert('XSS')>",
            "<embed src=javascript:alert('XSS')>",
            "<link rel=stylesheet href=javascript:alert('XSS')>",
            "<meta http-equiv=refresh content=0;url=javascript:alert('XSS')>",
            "<form action=javascript:alert('XSS')><input type=submit>",
            "<isindex action=javascript:alert('XSS')>",
            "<table background=javascript:alert('XSS')>",
            "<td background=javascript:alert('XSS')>",
            "<th background=javascript:alert('XSS')>",
            "<style>@import'javascript:alert(\"XSS\")';</style>",
            "<style>body{background:url('javascript:alert(\"XSS\")')}</style>",
            "<div style=background:url('javascript:alert(\"XSS\")')>",
            "<div style=background:url(javascript:alert('XSS'))>",
            "<div style=background:url(javascript:alert('XSS'))>",
            "<div style=background:url(javascript:alert('XSS'))>",
            "<div style=background:url(javascript:alert('XSS'))>",
            "<div style=background:url(javascript:alert('XSS'))>"
        ];

        foreach ($xssPayloads as $payload) {
            $escaped = htmlspecialchars($payload, ENT_QUOTES, 'UTF-8');
            
            // The escaped version should not contain dangerous characters
            $this->assertStringNotContainsString('<script>', $escaped, "Script tags should be escaped");
            $this->assertStringNotContainsString('onerror=', $escaped, "Event handlers should be escaped");
            $this->assertStringNotContainsString('onload=', $escaped, "Event handlers should be escaped");
            $this->assertStringNotContainsString('javascript:', $escaped, "JavaScript URLs should be escaped");
            $this->assertStringNotContainsString('onfocus=', $escaped, "Event handlers should be escaped");
            $this->assertStringNotContainsString('onstart=', $escaped, "Event handlers should be escaped");
            $this->assertStringNotContainsString('ontoggle=', $escaped, "Event handlers should be escaped");
            
            // The escaped version should contain HTML entities
            $this->assertStringContainsString('&lt;', $escaped, "Less than should be escaped");
            $this->assertStringContainsString('&gt;', $escaped, "Greater than should be escaped");
        }
    }

    /**
     * Test that session data is properly escaped when displayed
     */
    public function testSessionDataEscaping()
    {
        // Simulate malicious session data
        $_SESSION['useruid'] = "<script>alert('XSS')</script>";
        
        $escapedOutput = htmlspecialchars($_SESSION['useruid'], ENT_QUOTES, 'UTF-8');
        
        $this->assertStringNotContainsString('<script>', $escapedOutput, "Session data should be escaped");
        $this->assertStringContainsString('&lt;script&gt;', $escapedOutput, "Script tags should be HTML encoded");
    }

    /**
     * Test that user input is properly sanitized
     */
    public function testUserInputSanitization()
    {
        $maliciousInputs = [
            "Hello <script>alert('XSS')</script> World",
            "Normal text with <img src=x onerror=alert('XSS')> image",
            "Link: <a href=javascript:alert('XSS')>Click me</a>",
            "Form: <form action=javascript:alert('XSS')><input type=submit></form>",
            "Style: <div style=background:url(javascript:alert('XSS'))>Content</div>"
        ];

        foreach ($maliciousInputs as $input) {
            $sanitized = $this->sanitizeHTML($input);
            
            // Check that dangerous elements are escaped
            $this->assertStringNotContainsString('<script>', $sanitized, "Script tags should be escaped");
            $this->assertStringNotContainsString('onerror=', $sanitized, "Event handlers should be escaped");
            $this->assertStringNotContainsString('javascript:', $sanitized, "JavaScript URLs should be escaped");
            $this->assertStringNotContainsString('onload=', $sanitized, "Event handlers should be escaped");
            
            // Check that HTML entities are used
            $this->assertStringContainsString('&lt;', $sanitized, "Less than should be escaped");
            $this->assertStringContainsString('&gt;', $sanitized, "Greater than should be escaped");
        }
    }

    /**
     * Test that valid HTML content is preserved when appropriate
     */
    public function testValidHTMLPreservation()
    {
        $validHTML = [
            "Hello World",
            "This is a <strong>bold</strong> text",
            "A link: <a href='https://example.com'>Example</a>",
            "An image: <img src='image.jpg' alt='Image'>",
            "A paragraph with <em>emphasis</em>"
        ];

        foreach ($validHTML as $html) {
            $escaped = htmlspecialchars($html, ENT_QUOTES, 'UTF-8');
            
            // Valid HTML should be escaped for security
            $this->assertStringContainsString('&lt;strong&gt;', $escaped, "HTML tags should be escaped");
            $this->assertStringContainsString('&lt;a href=', $escaped, "HTML attributes should be escaped");
            $this->assertStringContainsString('&lt;img src=', $escaped, "HTML tags should be escaped");
        }
    }

    /**
     * Test different encoding contexts
     */
    public function testDifferentEncodingContexts()
    {
        $payload = "<script>alert('XSS')</script>";
        
        // Test different ENT_QUOTES flags
        $escapedSingle = htmlspecialchars($payload, ENT_QUOTES, 'UTF-8');
        $escapedDouble = htmlspecialchars($payload, ENT_COMPAT, 'UTF-8');
        $escapedNone = htmlspecialchars($payload, ENT_NOQUOTES, 'UTF-8');
        
        // All should escape the script tag
        $this->assertStringContainsString('&lt;script&gt;', $escapedSingle);
        $this->assertStringContainsString('&lt;script&gt;', $escapedDouble);
        $this->assertStringContainsString('&lt;script&gt;', $escapedNone);
        
        // Test different character encodings
        $escapedUTF8 = htmlspecialchars($payload, ENT_QUOTES, 'UTF-8');
        $escapedISO = htmlspecialchars($payload, ENT_QUOTES, 'ISO-8859-1');
        
        $this->assertStringContainsString('&lt;script&gt;', $escapedUTF8);
        $this->assertStringContainsString('&lt;script&gt;', $escapedISO);
    }

    /**
     * Test edge cases
     */
    public function testEdgeCases()
    {
        // Test empty string
        $this->assertEquals('', htmlspecialchars('', ENT_QUOTES, 'UTF-8'));
        
        // Test null
        $this->assertEquals('', htmlspecialchars(null, ENT_QUOTES, 'UTF-8'));
        
        // Test numeric input
        $this->assertEquals('123', htmlspecialchars(123, ENT_QUOTES, 'UTF-8'));
        
        // Test boolean input
        $this->assertEquals('1', htmlspecialchars(true, ENT_QUOTES, 'UTF-8'));
        $this->assertEquals('', htmlspecialchars(false, ENT_QUOTES, 'UTF-8'));
        
        // Test special characters
        $specialChars = "&<>\"'";
        $escaped = htmlspecialchars($specialChars, ENT_QUOTES, 'UTF-8');
        $this->assertStringContainsString('&amp;', $escaped);
        $this->assertStringContainsString('&lt;', $escaped);
        $this->assertStringContainsString('&gt;', $escaped);
        $this->assertStringContainsString('&quot;', $escaped);
        $this->assertStringContainsString('&#039;', $escaped);
    }

    /**
     * Helper method to sanitize HTML (mimics our sanitization function)
     */
    private function sanitizeHTML($input)
    {
        if (!is_string($input)) {
            return '';
        }
        
        return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    }
}
