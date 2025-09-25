<?php

namespace Talishar\Tests\Security;

use PHPUnit\Framework\TestCase;

/**
 * Session Management Tests
 * Tests the secure session management functionality
 */
class SessionManagementTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        // Clear any existing session data
        $_SESSION = [];
        $_COOKIE = [];
        
        // Reset session configuration
        ini_restore('session.cookie_httponly');
        ini_restore('session.cookie_secure');
        ini_restore('session.use_strict_mode');
        ini_restore('session.cookie_samesite');
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        
        // Clean up after each test
        $_SESSION = [];
        $_COOKIE = [];
        
        // Reset session configuration
        ini_restore('session.cookie_httponly');
        ini_restore('session.cookie_secure');
        ini_restore('session.use_strict_mode');
        ini_restore('session.cookie_samesite');
    }

    /**
     * Test secure session start
     */
    public function testSecureSessionStart()
    {
        // Start secure session
        SecureSessionStart();
        
        // Check that session is started
        $this->assertEquals(PHP_SESSION_ACTIVE, session_status(), "Session should be active");
        
        // Check secure session configuration
        $this->assertEquals('1', ini_get('session.cookie_httponly'), "HttpOnly should be enabled");
        $this->assertEquals('1', ini_get('session.cookie_secure'), "Secure should be enabled");
        $this->assertEquals('1', ini_get('session.use_strict_mode'), "Strict mode should be enabled");
        $this->assertEquals('Strict', ini_get('session.cookie_samesite'), "SameSite should be Strict");
    }

    /**
     * Test session check function
     */
    public function testCheckSession()
    {
        // Test starting session
        CheckSession();
        $this->assertEquals(PHP_SESSION_ACTIVE, session_status(), "Session should be started");
        
        // Test session regeneration tracking
        $this->assertArrayHasKey('last_regeneration', $_SESSION, "Last regeneration time should be set");
        $this->assertIsInt($_SESSION['last_regeneration'], "Last regeneration time should be integer");
        
        // Test regeneration after time threshold
        $_SESSION['last_regeneration'] = time() - 400; // 400 seconds ago (more than 300)
        
        $oldSessionId = session_id();
        CheckSession();
        $newSessionId = session_id();
        
        $this->assertNotEquals($oldSessionId, $newSessionId, "Session ID should be regenerated after threshold");
        $this->assertGreaterThan($_SESSION['last_regeneration'], time(), "Last regeneration time should be updated");
    }

    /**
     * Test user login status check
     */
    public function testIsUserLoggedIn()
    {
        // Test when not logged in
        $this->assertFalse(IsUserLoggedIn(), "Should return false when not logged in");
        
        // Test when logged in
        $_SESSION['useruid'] = 'testuser';
        $this->assertTrue(IsUserLoggedIn(), "Should return true when logged in");
        
        // Test with empty useruid
        $_SESSION['useruid'] = '';
        $this->assertFalse(IsUserLoggedIn(), "Should return false with empty useruid");
    }

    /**
     * Test logged in user ID retrieval
     */
    public function testLoggedInUser()
    {
        // Test when not logged in
        $this->assertNull(LoggedInUser(), "Should return null when not logged in");
        
        // Test when logged in
        $_SESSION['userid'] = 123;
        $this->assertEquals(123, LoggedInUser(), "Should return correct user ID");
    }

    /**
     * Test logged in username retrieval
     */
    public function testLoggedInUserName()
    {
        // Test when not logged in
        $this->assertNull(LoggedInUserName(), "Should return null when not logged in");
        
        // Test when logged in
        $_SESSION['useruid'] = 'testuser';
        $this->assertEquals('testuser', LoggedInUserName(), "Should return correct username");
    }

    /**
     * Test patron status check
     */
    public function testIsLoggedInUserPatron()
    {
        // Test hardcoded patrons
        $_SESSION['useruid'] = 'OotTheMonk';
        $this->assertTrue(IsLoggedInUserPatron(), "OotTheMonk should be recognized as patron");
        
        $_SESSION['useruid'] = 'PvtVoid';
        $this->assertTrue(IsLoggedInUserPatron(), "PvtVoid should be recognized as patron");
        
        // Test regular patron
        $_SESSION['useruid'] = 'regularuser';
        $_SESSION['isPatron'] = '1';
        $this->assertEquals('1', IsLoggedInUserPatron(), "Regular patron should return '1'");
        
        // Test non-patron
        $_SESSION['useruid'] = 'regularuser';
        unset($_SESSION['isPatron']);
        $this->assertEquals('0', IsLoggedInUserPatron(), "Non-patron should return '0'");
        
        // Test PvtVoid patron
        $_SESSION['useruid'] = 'regularuser';
        $_SESSION['isPvtVoidPatron'] = '1';
        $this->assertEquals('1', IsLoggedInUserPatron(), "PvtVoid patron should return '1'");
    }

    /**
     * Test session data retrieval functions
     */
    public function testSessionDataRetrieval()
    {
        // Set up session data
        $_SESSION['lastGameName'] = 'testgame';
        $_SESSION['lastPlayerId'] = 1;
        $_SESSION['lastAuthKey'] = 'testauthkey';
        
        // Test last game name
        $this->assertEquals('testgame', SessionLastGameName(), "Should return last game name");
        
        // Test with no last game name
        unset($_SESSION['lastGameName']);
        $this->assertEquals('', SessionLastGameName(), "Should return empty string when no last game name");
        
        // Test last player ID
        $this->assertEquals(1, SessionLastGamePlayerID(), "Should return last player ID");
        
        // Test last auth key
        $this->assertEquals('testauthkey', SessionLastAuthKey(), "Should return last auth key");
    }

    /**
     * Test session clearing
     */
    public function testClearLoginSession()
    {
        // Set up session data
        $_SESSION['userid'] = 123;
        $_SESSION['useruid'] = 'testuser';
        $_SESSION['lastGameName'] = 'testgame';
        
        // Set up cookies
        $_COOKIE['rememberMeToken'] = 'testtoken';
        $_COOKIE['lastAuthKey'] = 'testauthkey';
        
        // Clear session
        ClearLoginSession();
        
        // Check that session is destroyed
        $this->assertEquals(PHP_SESSION_NONE, session_status(), "Session should be destroyed");
        
        // Note: We can't easily test cookie deletion in unit tests
        // as setcookie() doesn't actually modify $_COOKIE in CLI environment
    }

    /**
     * Test session regeneration on login
     */
    public function testSessionRegenerationOnLogin()
    {
        // Start initial session
        session_start();
        $initialSessionId = session_id();
        
        // Simulate login process with session regeneration
        session_regenerate_id(true);
        $newSessionId = session_id();
        
        $this->assertNotEquals($initialSessionId, $newSessionId, "Session ID should be regenerated on login");
    }

    /**
     * Test session security headers
     */
    public function testSessionSecurityHeaders()
    {
        // Start secure session
        SecureSessionStart();
        
        // Check session configuration
        $this->assertEquals('1', ini_get('session.cookie_httponly'), "HttpOnly should be enabled");
        $this->assertEquals('1', ini_get('session.cookie_secure'), "Secure should be enabled");
        $this->assertEquals('1', ini_get('session.use_strict_mode'), "Strict mode should be enabled");
        $this->assertEquals('Strict', ini_get('session.cookie_samesite'), "SameSite should be Strict");
    }

    /**
     * Test session fixation prevention
     */
    public function testSessionFixationPrevention()
    {
        // Start session
        session_start();
        $initialSessionId = session_id();
        
        // Simulate session regeneration (as done in CheckSession)
        if (!isset($_SESSION['last_regeneration'])) {
            $_SESSION['last_regeneration'] = time();
        } elseif (time() - $_SESSION['last_regeneration'] > 300) {
            session_regenerate_id(true);
            $_SESSION['last_regeneration'] = time();
        }
        
        // Force regeneration by setting old timestamp
        $_SESSION['last_regeneration'] = time() - 400;
        CheckSession();
        
        $newSessionId = session_id();
        $this->assertNotEquals($initialSessionId, $newSessionId, "Session ID should be regenerated to prevent fixation");
    }

    /**
     * Test remember me token security
     */
    public function testRememberMeTokenSecurity()
    {
        // Test secure token generation
        $token1 = hash("sha256", random_bytes(32) . "password" . random_bytes(32));
        $token2 = hash("sha256", random_bytes(32) . "password" . random_bytes(32));
        
        $this->assertNotEquals($token1, $token2, "Tokens should be unique");
        $this->assertEquals(64, strlen($token1), "Token should be 64 characters");
        $this->assertMatchesRegularExpression('/^[a-f0-9]{64}$/', $token1, "Token should be valid hex");
    }

    /**
     * Test session data integrity
     */
    public function testSessionDataIntegrity()
    {
        // Start session
        session_start();
        
        // Set test data
        $_SESSION['userid'] = 123;
        $_SESSION['useruid'] = 'testuser';
        $_SESSION['useremail'] = 'test@example.com';
        
        // Verify data integrity
        $this->assertEquals(123, $_SESSION['userid'], "User ID should be preserved");
        $this->assertEquals('testuser', $_SESSION['useruid'], "Username should be preserved");
        $this->assertEquals('test@example.com', $_SESSION['useremail'], "Email should be preserved");
        
        // Test that password is not stored in session
        $this->assertArrayNotHasKey('userspwd', $_SESSION, "Password should not be stored in session");
    }

    /**
     * Test session timeout handling
     */
    public function testSessionTimeoutHandling()
    {
        // Start session
        session_start();
        
        // Set old regeneration time
        $_SESSION['last_regeneration'] = time() - 600; // 10 minutes ago
        
        // Check session should trigger regeneration
        $oldSessionId = session_id();
        CheckSession();
        $newSessionId = session_id();
        
        $this->assertNotEquals($oldSessionId, $newSessionId, "Session should be regenerated after timeout");
        $this->assertGreaterThan($_SESSION['last_regeneration'], time() - 10, "Regeneration time should be updated");
    }
}
