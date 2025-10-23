<?php

namespace Talishar\Tests\BusinessLogic;

use PHPUnit\Framework\TestCase;

/**
 * Game Logic Tests
 * Tests core game logic functions
 */
class GameLogicTest extends TestCase
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
            'GameWithNumbers123'
        ];

        foreach ($validGameNames as $gameName) {
            $this->assertTrue(
                IsGameNameValid($gameName),
                "Valid game name should pass: " . $gameName
            );
        }

        // Invalid game names
        $invalidGameNames = [
            '', // Empty
            'ab', // Too short
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
                IsGameNameValid($gameName),
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
                $this->validatePlayerID($playerID),
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
                $this->validatePlayerID($playerID),
                "Invalid player ID should fail: " . var_export($playerID, true)
            );
        }
    }

    /**
     * Test mode validation
     */
    public function testModeValidation()
    {
        // Valid modes
        $validModes = [1, 2, 99, 100, 100015, 999999];

        foreach ($validModes as $mode) {
            $this->assertTrue(
                $this->validateMode($mode),
                "Valid mode should pass: " . $mode
            );
        }

        // Invalid modes
        $invalidModes = [
            0, // Too low
            1000000, // Too high
            -1, // Negative
            'invalid', // Non-numeric string
            '', // Empty string
            null, // Null value
            [], // Array
            new stdClass() // Object
        ];

        foreach ($invalidModes as $mode) {
            $this->assertFalse(
                $this->validateMode($mode),
                "Invalid mode should fail: " . var_export($mode, true)
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
                $this->validateCardID($cardID),
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
                $this->validateCardID($cardID),
                "Invalid card ID should fail: " . var_export($cardID, true)
            );
        }
    }

    /**
     * Test check count validation
     */
    public function testCheckCountValidation()
    {
        // Valid check counts
        $validCheckCounts = [0, 1, 5, 10, 50, 100];

        foreach ($validCheckCounts as $count) {
            $this->assertTrue(
                $this->validateCheckCount($count),
                "Valid check count should pass: " . $count
            );
        }

        // Invalid check counts
        $invalidCheckCounts = [
            -1, // Negative
            101, // Too high
            'invalid', // Non-numeric string
            '', // Empty string
            null, // Null value
            [], // Array
            new stdClass() // Object
        ];

        foreach ($invalidCheckCounts as $count) {
            $this->assertFalse(
                $this->validateCheckCount($count),
                "Invalid check count should fail: " . var_export($count, true)
            );
        }
    }

    /**
     * Test input sanitization
     */
    public function testInputSanitization()
    {
        $inputs = [
            'normal input',
            'input with null\0byte',
            '  input with spaces  ',
            'input with\ttab',
            'input with\nnewline',
            'input with\rcarriage return',
            'input with<script>alert("xss")</script>',
            'input with; DROP TABLE users; --'
        ];

        foreach ($inputs as $input) {
            $sanitized = $this->sanitizeString($input);
            
            // Should remove null bytes
            $this->assertStringNotContainsString("\0", $sanitized, "Null bytes should be removed");
            
            // Should trim whitespace
            if ($input === '  input with spaces  ') {
                $this->assertEquals('input with spaces', $sanitized, "Leading/trailing spaces should be trimmed");
            }
            
            // Should preserve other content
            $this->assertStringContainsString('input', $sanitized, "Valid content should be preserved");
        }
    }

    /**
     * Test authentication key validation
     */
    public function testAuthKeyValidation()
    {
        // Valid auth keys
        $validAuthKeys = [
            'valid_auth_key_123',
            'another_valid_key',
            'key_with_numbers_456',
            str_repeat('a', 50) // Long key
        ];

        foreach ($validAuthKeys as $authKey) {
            $this->assertTrue(
                $this->validateAuthKey($authKey),
                "Valid auth key should pass: " . $authKey
            );
        }

        // Invalid auth keys
        $invalidAuthKeys = [
            '', // Empty
            str_repeat('a', 201), // Too long (201 characters)
            null, // Null value
            123, // Non-string
            [], // Array
            new stdClass() // Object
        ];

        foreach ($invalidAuthKeys as $authKey) {
            $this->assertFalse(
                $this->validateAuthKey($authKey),
                "Invalid auth key should fail: " . var_export($authKey, true)
            );
        }
    }

    /**
     * Test game state validation
     */
    public function testGameStateValidation()
    {
        // Test valid game states
        $validGameStates = [
            'waiting',
            'active',
            'completed',
            'paused'
        ];

        foreach ($validGameStates as $state) {
            $this->assertTrue(
                $this->validateGameState($state),
                "Valid game state should pass: " . $state
            );
        }

        // Test invalid game states
        $invalidGameStates = [
            '', // Empty
            'invalid_state',
            'state with spaces',
            'state<script>',
            null, // Null value
            123, // Non-string
            [], // Array
            new stdClass() // Object
        ];

        foreach ($invalidGameStates as $state) {
            $this->assertFalse(
                $this->validateGameState($state),
                "Invalid game state should fail: " . var_export($state, true)
            );
        }
    }

    /**
     * Test deck validation
     */
    public function testDeckValidation()
    {
        // Test valid deck formats
        $validDecks = [
            'DYN001,MON123,WTR456',
            'ARC789',
            'DYN001,DYN002,DYN003',
            '' // Empty deck might be valid in some contexts
        ];

        foreach ($validDecks as $deck) {
            $this->assertTrue(
                $this->validateDeck($deck),
                "Valid deck should pass: " . $deck
            );
        }

        // Test invalid decks
        $invalidDecks = [
            'invalid_card_id',
            'DYN001; DROP TABLE cards; --',
            'DYN001<script>alert("xss")</script>',
            null, // Null value
            123, // Non-string
            [], // Array
            new stdClass() // Object
        ];

        foreach ($invalidDecks as $deck) {
            $this->assertFalse(
                $this->validateDeck($deck),
                "Invalid deck should fail: " . var_export($deck, true)
            );
        }
    }

    /**
     * Helper method to validate player ID
     */
    private function validatePlayerID($playerID)
    {
        $playerID = intval($playerID);
        return ($playerID >= 1 && $playerID <= 3);
    }

    /**
     * Helper method to validate mode
     */
    private function validateMode($mode)
    {
        if (!is_numeric($mode)) {
            return false;
        }
        
        $mode = intval($mode);
        return ($mode >= 1 && $mode <= 999999);
    }

    /**
     * Helper method to validate card ID
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
     * Helper method to validate check count
     */
    private function validateCheckCount($count)
    {
        if (!is_numeric($count)) {
            return false;
        }
        
        $count = intval($count);
        return ($count >= 0 && $count <= 100);
    }

    /**
     * Helper method to sanitize string
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

    /**
     * Helper method to validate auth key
     */
    private function validateAuthKey($authKey)
    {
        if (empty($authKey) || !is_string($authKey)) {
            return false;
        }
        
        if (strlen($authKey) > 200) {
            return false;
        }
        
        return true;
    }

    /**
     * Helper method to validate game state
     */
    private function validateGameState($state)
    {
        if (empty($state) || !is_string($state)) {
            return false;
        }
        
        $validStates = ['waiting', 'active', 'completed', 'paused'];
        return in_array($state, $validStates, true);
    }

    /**
     * Helper method to validate deck
     */
    private function validateDeck($deck)
    {
        if (!is_string($deck)) {
            return false;
        }
        
        // If empty, it might be valid
        if (empty($deck)) {
            return true;
        }
        
        // Split by comma and validate each card ID
        $cards = explode(',', $deck);
        foreach ($cards as $card) {
            $card = trim($card);
            if (!empty($card) && !$this->validateCardID($card)) {
                return false;
            }
        }
        
        return true;
    }
}
