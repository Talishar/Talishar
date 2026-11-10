<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class HeroPreferenceTest extends TestCase
{
    private static array $results = [];

    public static function setUpBeforeClass(): void
    {
        $script = __DIR__ . '/fixtures/HeroPreferenceScenarios.php';
        $output = shell_exec(escapeshellarg(PHP_BINARY) . ' -d memory_limit=1G ' . escapeshellarg($script) . ' 2>&1');
        $decoded = json_decode((string)$output, true);
        if (!is_array($decoded)) {
            self::fail("Scenario script did not return JSON:\n" . $output);
        }
        self::$results = $decoded;
    }

    public function testEveryListedHeroIdIsARealHero(): void
    {
        $this->assertSame([], self::$results['unknownHeroIds']);
    }

    public function testEveryHeroCanJoinALobbyAskingForIt(): void
    {
        $this->assertSame([], self::$results['blockedFromOwnLobby']);
    }

    public function testEveryHeroIsKeptOutOfALobbyAvoidingIt(): void
    {
        $this->assertSame([], self::$results['allowedIntoAvoidingLobby']);
    }

    public function testEveryHeroMatchesItsRealCardName(): void
    {
        $this->assertSame([], self::$results['blockedByRealName']);
    }

    public function testDifferentHeroNamesNeverCompareEqual(): void
    {
        $this->assertSame([], self::$results['namesThatCollide']);
    }

    /** @dataProvider preferenceCases */
    public function testPreferenceCase(string $case): void
    {
        $this->assertTrue(self::$results['cases'][$case]);
    }

    public static function preferenceCases(): array
    {
        return [
            ['otherHeroBlockedFromJarlLobby'],
            ['otherHeroAllowedWhenJarlAvoided'],
            ['jarlAllowedInMultiHeroLobby'],
            ['commaHeroAllowedInMultiHeroLobby'],
            ['classLobbyAllowsMatchingClass'],
            ['classLobbyBlocksOtherClass'],
            ['placeholderHasNoRestriction'],
            ['unrelatedDescriptionHasNoRestriction'],
        ];
    }

    public function testNameKeysIgnoreCasePunctuationAndAccents(): void
    {
        $keys = self::$results['keys'];
        $this->assertSame('jarlvetreidi', $keys['Jarl Vetreiði']);
        $this->assertSame('jarlvetreidi', $keys['JARL VETREIÐI']);
        $this->assertSame('jarlvetreidi', $keys['Jarl Vetreiđi']);
        $this->assertSame('maxxthehypenitro', $keys['Maxx &#039;The Hype&#039; Nitro']);
        $this->assertSame('minhalfgenius', $keys['Min, Half-Genius']);
        $this->assertSame('thordisaero', $keys['Þórdís Ærø']);
        $this->assertSame('α01firstcogwarden', $keys['α-01, First Cogwarden']);
    }
}
