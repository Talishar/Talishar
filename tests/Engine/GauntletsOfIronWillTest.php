<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class GauntletsOfIronWillTest extends TestCase
{
    private const INACTIVE = 'gauntlets_of_iron_will-0';
    private const ACTIVE = 'gauntlets_of_iron_will-ACTIVE';

    private static array $results = [];

    public static function setUpBeforeClass(): void
    {
        $script = __DIR__ . '/fixtures/GauntletsOfIronWillScenarios.php';
        $output = shell_exec(escapeshellarg(PHP_BINARY) . ' -d memory_limit=1G ' . escapeshellarg($script) . ' 2>&1');
        $decoded = json_decode((string)$output, true);
        if (!is_array($decoded)) {
            self::fail("Scenario script did not return JSON:\n" . $output);
        }
        self::$results = $decoded;
    }

    public function testBuffWithoutSwapActivatesTheEffect(): void
    {
        $this->assertSame(['power' => 5, 'effects' => [self::ACTIVE]], self::$results['buffWithoutSwap']);
    }

    public function testBuffFromTriggerAfterSwapActivatesTheEffect(): void
    {
        $this->assertSame(['power' => 6, 'effects' => [self::INACTIVE]], self::$results['flipTheScriptAfterSwap']);
        $this->assertSame(['power' => 6, 'effects' => [self::ACTIVE, 'flip_the_script_blue']], self::$results['flipTheScriptAfterTrigger']);
    }

    public function testBuffFromAbilityAfterSwapActivatesTheEffect(): void
    {
        $this->assertSame(['power' => 6, 'effects' => [self::ACTIVE]], self::$results['buffAfterSwap']);
    }

    public function testSwapResetsBaselineWhenAnotherEffectIsAheadOfGauntlets(): void
    {
        $this->assertSame(['power' => 5, 'effects' => ['marked', self::INACTIVE]], self::$results['staleBaselineAfterSwap']);
        $this->assertSame(['power' => 5, 'effects' => ['marked', self::ACTIVE]], self::$results['staleBaselineAfterBuff']);
    }

    public function testSwapRemovesActiveEffectAndItDoesNotRearm(): void
    {
        $this->assertSame(['power' => 7, 'effects' => []], self::$results['activeSwappedOut']);
    }

    public function testNegativeBaselineActivatesOnFirstIncrease(): void
    {
        $this->assertSame(['power' => 3, 'effects' => [self::ACTIVE]], self::$results['negativeBaseline']);
    }

    public function testBuffDuringDefenderPriorityActivatesTheEffect(): void
    {
        $this->assertSame(['power' => 5, 'effects' => [self::ACTIVE]], self::$results['buffDuringDefenderPriority']);
    }

    public function testLinkResolvingRemovesOnlyGauntlets(): void
    {
        $this->assertSame(['marked', 'marked'], self::$results['linkResolvesWhileActive']['effects']);
    }
}
