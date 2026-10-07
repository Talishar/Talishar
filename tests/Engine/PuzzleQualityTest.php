<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../Libraries/PuzzleAnalysis.php';
require_once __DIR__ . '/../../Libraries/PuzzleVerify.php';

class PuzzleQualityTest extends TestCase
{
    public function testPlainDefenderMatchesSimpleAttacksWithoutWastingBlocks(): void
    {
        $blockers = [['four', 4], ['five', 5]];
        self::assertSame(['four'], PuzzlePlainBlockSelection(4, 4, $blockers));
        self::assertSame(['five'], PuzzlePlainBlockSelection(5, 4, [['five', 5]]));
        self::assertSame(['three', 'four'], PuzzlePlainBlockSelection(7, 3, [['three', 3], ['four', 4], ['five', 5]]));
        self::assertSame(['three'], PuzzlePlainBlockSelection(6, 4, [['two', 2], ['three', 3]]));
        self::assertSame([], PuzzlePlainBlockSelection(6, 9, [['two', 2], ['three', 3]]));
    }

    public function testRoutineSurvivalIsFilteredDespiteBotFailure(): void
    {
        $analysis = $this->analyze([['kind' => 'BLOCK', 'cards' => ['foo'], 'target' => 'bar']]);
        self::assertTrue($analysis['filtered']);
        self::assertSame('easy', $analysis['difficulty']);
        self::assertContains('NO_TACTIC', array_column($analysis['flags'], 'code'));
    }

    public function testTacticalDefenseStillNeedsToBeatPlainBlocks(): void
    {
        $steps = [
            ['kind' => 'BLOCK', 'cards' => ['foo'], 'target' => 'bar'],
            ['kind' => 'PLAY', 'cards' => ['instant']]
        ];
        self::assertFalse($this->analyze($steps)['filtered']);
        $plain = $this->analyze($steps, true);
        self::assertTrue($plain['filtered']);
        self::assertContains('PLAIN_BLOCKS', array_column($plain['flags'], 'code'));
    }

    public function testUnfinishedBenchmarkCannotQualify(): void
    {
        $steps = [['kind' => 'PLAY', 'cards' => ['instant']]];
        $analysis = $this->analyze($steps, false, false);
        self::assertTrue($analysis['filtered']);
        self::assertContains('BASELINE_STALLED', array_column($analysis['flags'], 'code'));
    }

    private function analyze(array $steps, bool $plainWon = false, bool $plainCompleted = true): array
    {
        $lines = array_fill(0, 80, '');
        $lines[0] = '4 20';
        $baseline = [
            'bot' => ['won' => false, 'completed' => true, 'damage' => 4, 'blocked' => [], 'played' => []],
            'plain' => ['won' => $plainWon, 'completed' => $plainCompleted],
            'real' => ['blocked' => ['foo'], 'played' => ['instant']]
        ];
        return AnalyzeSurvivePosition(implode("\r\n", $lines), 1,
            ['threatened' => 9, 'cardsPlayed' => 2],
            ['status' => 'proven', 'life' => 4, 'threatened' => 9],
            $baseline, $steps);
    }
}
