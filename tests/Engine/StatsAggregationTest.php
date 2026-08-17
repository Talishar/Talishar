<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

/**
 * End-of-game stat aggregation.
 *
 * These tests drive the real logging functions from Libraries/StatFunctions.php
 * and the real aggregation in includes/functions.inc.php through a scripted
 * game where every number is known in advance, then check the reported figures
 * against that ground truth.
 *
 * The invariants that must hold for every seat and every ending:
 *   - totals equal what the player actually did (nothing is ever dropped)
 *   - the per-turn table sums to the totals
 *   - "per turn" divides by the populated turn rows that contribute to it
 *   - each seat uses the same populated-row accounting
 *
 * A failure here means the stats no longer add up, not that a threshold moved.
 */

// Engine accessors the stat functions need. CardGetters.php is not loaded in
// the test bootstrap, so provide the minimum they touch.
if (!function_exists('GetTurnStats')) {
    function &GetTurnStats($player) {
        if ($player == 1) return $GLOBALS['p1TurnStats'];
        return $GLOBALS['p2TurnStats'];
    }
}
if (!function_exists('GetCardStats')) {
    function &GetCardStats($player) {
        if ($player == 1) return $GLOBALS['p1CardStats'];
        return $GLOBALS['p2CardStats'];
    }
}
if (!function_exists('GetCardTurnLog')) {
    function &GetCardTurnLog($player) {
        if ($player == 1) return $GLOBALS['p1CardTurnLog'];
        return $GLOBALS['p2CardTurnLog'];
    }
}
if (!function_exists('GetResources')) {
    function &GetResources($player) {
        if ($player == 1) return $GLOBALS['p1Resources'];
        return $GLOBALS['p2Resources'];
    }
}
if (!function_exists('GetHand')) {
    function &GetHand($player) {
        if ($player == 1) return $GLOBALS['p1Hand'];
        return $GLOBALS['p2Hand'];
    }
}
if (!function_exists('GetHealth')) {
    function GetHealth($player) { return $GLOBALS['p' . $player . 'Health']; }
}
if (!function_exists('WriteLog')) {
    function WriteLog(...$args) { }
}

class StatsAggregationTest extends TestCase
{
    private const THREAT = 10;   // damage threatened on each attacking turn
    private const BLOCK  = 5;    // damage blocked on each defending turn

    /** @var array<int,int> attacking turns actually played, by player */
    private array $attacks = [1 => 0, 2 => 0];
    /** @var array<int,int> defensive phases actually played, by player */
    private array $defences = [1 => 0, 2 => 0];

    // ---------------------------------------------------------------- engine model

    /**
     * Mirrors the turn flow the stat functions depend on. If this drifts from
     * the engine these tests stop meaning anything, so testTurnFlowModelMatchesEngine()
     * guards the two facts it relies on.
     */
    private function startGame(int $firstPlayer): void
    {
        foreach ([1, 2] as $p) {
            $GLOBALS["p{$p}TurnStats"] = [];
            $GLOBALS["p{$p}CardStats"] = [];
            $GLOBALS["p{$p}CardTurnLog"] = [];
            $GLOBALS["p{$p}Resources"] = [0, 0];
            $GLOBALS["p{$p}Hand"] = [];
            $GLOBALS["p{$p}Health"] = 40;
            $GLOBALS["p{$p}LifeHistory"] = [];
            $GLOBALS["p{$p}ArcaneDamageDealt"] = [];
            $GLOBALS["p{$p}TotalTime"] = 0;
        }
        $GLOBALS['currentTurn'] = 0;
        $GLOBALS['firstPlayer'] = $firstPlayer;
        $GLOBALS['mainPlayer'] = $firstPlayer;
        $GLOBALS['defPlayer'] = 3 - $firstPlayer;
        $GLOBALS['turn'] = ['M'];
        $this->attacks = [1 => 0, 2 => 0];
        $this->defences = [1 => 0, 2 => 0];

        StatsStartTurn(); // StartEffects.php does this once at game start
    }

    /** One attacking turn: attacker threatens THREAT, defender blocks BLOCK. */
    private function playTurn(): void
    {
        $main = $GLOBALS['mainPlayer'];
        $def  = $GLOBALS['defPlayer'];

        LogPlayCardStats($main, 'attack_card', 'HAND', 'A');
        LogResourcesUsedStats($main, 1);
        LogPlayCardStats($def, 'block_card', 'HAND', 'B');
        LogCombatResolutionStats(self::THREAT, self::BLOCK);
        if (self::THREAT > self::BLOCK) {
            LogDamageStats($def, self::THREAT - self::BLOCK, self::THREAT - self::BLOCK);
        }

        $this->attacks[$main]++;
        $this->defences[$def]++;
    }

    /** FinalizeTurn: end-of-turn logging, turn counter, seat swap, next block. */
    private function endTurn(): void
    {
        LogEndTurnStats($GLOBALS['mainPlayer']);
        LogEndLifeStats();
        if ($GLOBALS['mainPlayer'] == $GLOBALS['firstPlayer']) $GLOBALS['currentTurn'] += 1;
        $GLOBALS['defPlayer'] = $GLOBALS['mainPlayer'];
        $GLOBALS['mainPlayer'] = 3 - $GLOBALS['mainPlayer'];
        if ($GLOBALS['mainPlayer'] == 1) StatsStartTurn();
    }

    /**
     * Plays until both players have had $attacksEach turns, then stops on
     * $lethalBy's turn without finalizing it, which is what happens when a
     * player is killed mid-turn.
     */
    private function playGame(int $firstPlayer, int $attacksEach, int $lethalBy): void
    {
        $this->startGame($firstPlayer);
        $guard = 0;
        while (true) {
            $this->playTurn();
            if ($this->attacks[1] >= $attacksEach && $this->attacks[2] >= $attacksEach
                && $GLOBALS['mainPlayer'] == $lethalBy) {
                break;
            }
            $this->endTurn();
            $this->assertLessThan(100, ++$guard, 'simulated game did not terminate');
        }
    }

    private function aggregatesFor(int $player): array
    {
        $deck = [];
        $turnStats = &GetTurnStats($player);
        $otherTurnStats = &GetTurnStats(3 - $player);
        PopulateTurnStatsAndAggregates($deck, $turnStats, $otherTurnStats, $player, true);
        PopulateAggregateStats($deck, $turnStats, $player);
        return $deck;
    }

    /** @return array<array{0:int,1:int}> firstPlayer / lethalBy combinations */
    public static function seatProvider(): array
    {
        return [
            'P1 on the play, P1 wins'  => [1, 1],
            'P1 on the play, P2 wins'  => [1, 2],
            'P2 on the play, P1 wins'  => [2, 1],
            'P2 on the play, P2 wins'  => [2, 2],
        ];
    }

    // ---------------------------------------------------------------- totals

    /**
     * @dataProvider seatProvider
     */
    public function testTotalsCountEveryTurnPlayed(int $firstPlayer, int $lethalBy): void
    {
        $this->playGame($firstPlayer, 6, $lethalBy);

        foreach ([1, 2] as $player) {
            $stats = $this->aggregatesFor($player);
            $this->assertSame(
                $this->attacks[$player] * self::THREAT,
                (int)$stats['totalDamageThreatened'],
                "P$player lost an attacking turn from totalDamageThreatened"
            );
            $this->assertSame(
                $this->defences[$player] * self::BLOCK,
                (int)$stats['totalDamageBlocked'],
                "P$player lost a defending turn from totalDamageBlocked"
            );
        }
    }

    /**
     * The end game screen shows both a per-turn table and headline totals. If
     * they disagree, one of them is dropping turns.
     *
     * @dataProvider seatProvider
     */
    public function testPerTurnTableSumsToTheTotals(int $firstPlayer, int $lethalBy): void
    {
        $this->playGame($firstPlayer, 6, $lethalBy);

        foreach ([1, 2] as $player) {
            $stats = $this->aggregatesFor($player);
            $tableThreatened = 0;
            $tableBlocked = 0;
            foreach ($stats['turnResults'] as $turn) {
                $tableThreatened += $turn['damageThreatened'];
                $tableBlocked += $turn['damageBlocked'];
            }
            $this->assertSame((int)$stats['totalDamageThreatened'], $tableThreatened,
                "P$player: per-turn table does not sum to totalDamageThreatened");
            $this->assertSame((int)$stats['totalDamageBlocked'], $tableBlocked,
                "P$player: per-turn table does not sum to totalDamageBlocked");
        }
    }

    // ---------------------------------------------------------------- averages

    /**
     * @dataProvider seatProvider
     */
    public function testAveragesDivideByRecordedTurnRows(int $firstPlayer, int $lethalBy): void
    {
        $this->playGame($firstPlayer, 6, $lethalBy);

        foreach ([1, 2] as $player) {
            $stats = $this->aggregatesFor($player);
            $turnStats = &GetTurnStats($player);
            $rows = count(UsedTurnStatBlocks($turnStats));
            $threatened = (int)$stats['totalDamageThreatened'];
            $blocked = (int)$stats['totalDamageBlocked'];

            $this->assertEqualsWithDelta(round($threatened / $rows, 2),
                $stats['averageDamageThreatenedPerTurn'], 0.001,
                "P$player: averageDamageThreatenedPerTurn is not per recorded turn row");
            $this->assertEqualsWithDelta(round(($threatened + $blocked) / $rows, 2),
                $stats['averageCombatValuePerTurn'], 0.001,
                "P$player: averageCombatValuePerTurn is not per recorded turn row");
            $this->assertEqualsWithDelta(round(($threatened + $blocked) / $rows, 2),
                $stats['averageValuePerTurn'], 0.001,
                "P$player: averageValuePerTurn is not per recorded turn row");
        }
    }

    /**
     * Both seats must use their own populated rows as the denominator. The
     * player who dies while defending can legitimately have one more row, so
     * their result need not equal the other player's result.
     */
    public function testEachSeatUsesItsRecordedRows(): void
    {
        foreach ([1, 2] as $firstPlayer) {
            $this->playGame($firstPlayer, 6, 3 - $firstPlayer);
            foreach ([1, 2] as $player) {
                $stats = $this->aggregatesFor($player);
                $turnStats = &GetTurnStats($player);
                $rows = count(UsedTurnStatBlocks($turnStats));
                $expected = round(
                    ((int)$stats['totalDamageThreatened'] + (int)$stats['totalDamageBlocked']) / $rows,
                    2
                );
                $this->assertEqualsWithDelta($expected, $stats['averageValuePerTurn'], 0.001,
                    "P$player: seat-specific average did not use its recorded rows");
            }
        }
    }

    public function testShortGamesAreNotDiluted(): void
    {
        $this->playGame(1, 2, 2);

        foreach ([1, 2] as $player) {
            $stats = $this->aggregatesFor($player);
            $turnStats = &GetTurnStats($player);
            $rows = count(UsedTurnStatBlocks($turnStats));
            $expected = round(
                ((int)$stats['totalDamageThreatened'] + (int)$stats['totalDamageBlocked']) / $rows,
                2);
            $this->assertEqualsWithDelta($expected, $stats['averageValuePerTurn'], 0.001,
                "P$player: short game average is wrong");
        }
    }

    // ---------------------------------------------------------------- block layout

    /**
     * Every stat block from the first to the last must belong to a turn the
     * player took, so that counting blocks and counting turns agree. The only
     * allowed extra is the final defence of a player who was killed while
     * blocking, which has no turn of its own.
     *
     * @dataProvider seatProvider
     */
    public function testBlockCountMatchesTurnCount(int $firstPlayer, int $lethalBy): void
    {
        $this->playGame($firstPlayer, 6, $lethalBy);

        foreach ([1, 2] as $player) {
            $turnStats = &GetTurnStats($player);
            $used = count(UsedTurnStatBlocks($turnStats));
            $turns = CountAttackingTurns($player);

            $this->assertSame($this->attacks[$player], $turns,
                "P$player: CountAttackingTurns disagrees with the turns actually played");

            $killedWhileBlocking = ($player != $lethalBy);
            $this->assertSame($turns + ($killedWhileBlocking ? 1 : 0), $used,
                "P$player: unexpected number of populated stat blocks");
        }
    }

    /**
     * The player on the draw has no cycle before their first turn, so their
     * block 0 must stay empty rather than absorbing their first block set,
     * which used to hide that blocking from every average.
     */
    public function testSecondPlayerFirstDefenceIsNotStrandedInBlockZero(): void
    {
        $this->playGame(1, 3, 1);

        $secondPlayerStats = &GetTurnStats(2);
        $pieces = TurnStatPieces();
        $blockZero = array_slice($secondPlayerStats, 0, $pieces);
        $this->assertSame(array_fill(0, $pieces, 0), $blockZero,
            'block 0 of the player on the draw should be empty');

        $stats = $this->aggregatesFor(2);
        $this->assertSame($this->defences[2] * self::BLOCK, (int)$stats['totalDamageBlocked'],
            'the first block set of the player on the draw was not counted');
    }

    // ---------------------------------------------------------------- exclude last turn

    public function testExcludeLastTurnDropsExactlyOneTurn(): void
    {
        // Winner: their last stat block is a full turn, so dropping it costs a
        // turn of offence and one from the denominator.
        $this->playGame(1, 5, 1);

        $stats = $this->aggregatesFor(1);
        $turns = $this->attacks[1];
        $threatened = $turns * self::THREAT;

        $this->assertSame($threatened - self::THREAT, (int)$stats['totalDamageThreatened_NoLast'],
            'excluding the last turn should remove exactly one turn of damage');
        $this->assertEqualsWithDelta(
            round(($threatened - self::THREAT) / ($turns - 1), 2),
            $stats['averageDamageThreatenedPerTurn_NoLast'], 0.001,
            'excluding the last turn should also drop one turn from the denominator');
    }

    public function testTrailingDefenceCountsAsOneDisplayedTurn(): void
    {
        $this->startGame(1);

        // P2 takes one complete turn, then is left with a final defensive row
        // when P1 attacks again. Both rows contribute value and must therefore
        // be included in the full-game per-turn denominator.
        $this->playTurn();
        $this->endTurn();
        $this->playTurn();
        $this->endTurn();
        $this->playTurn();

        $stats = $this->aggregatesFor(2);
        $p2Stats = &GetTurnStats(2);
        $this->assertSame(2, count(UsedTurnStatBlocks($p2Stats)));
        $this->assertSame(10, (int)$stats['totalDamageThreatened']);
        $this->assertSame(10, (int)$stats['totalDamageBlocked']);
        $this->assertEqualsWithDelta(10.0, $stats['averageValuePerTurn'], 0.001,
            'the final defensive row must not inflate the average by being omitted from its denominator');
        $this->assertEqualsWithDelta(15.0, $stats['averageValuePerTurn_NoLast'], 0.001,
            'excluding the final defensive row should retain only the complete preceding row');
    }

    // ---------------------------------------------------------------- guards

    /**
     * A player who blocks with everything can spend more cards defending than
     * the hand-size model assumes are available, which must not produce a
     * negative "per card" denominator.
     */
    public function testPerCardAverageIsNeverNegative(): void
    {
        $this->startGame(1);
        for ($i = 0; $i < 3; $i++) {
            $main = $GLOBALS['mainPlayer'];
            $def = $GLOBALS['defPlayer'];
            LogPlayCardStats($main, 'attack_card', 'HAND', 'A');
            for ($c = 0; $c < 9; $c++) LogPlayCardStats($def, "block_$c", 'HAND', 'B');
            LogCombatResolutionStats(self::THREAT, self::BLOCK);
            $this->attacks[$main]++;
            $this->defences[$def]++;
            $this->endTurn();
        }

        foreach ([1, 2] as $player) {
            $stats = $this->aggregatesFor($player);
            $this->assertGreaterThanOrEqual(0, $stats['averageDamageThreatenedPerCard'],
                "P$player: damage threatened per card went negative");
            $this->assertGreaterThanOrEqual(0, $stats['averageDamageThreatenedPerCard_NoLast'],
                "P$player: damage threatened per card (excluding last turn) went negative");
        }
    }

    public function testEmptyStatsDoNotProduceOutput(): void
    {
        $empty = [];
        $deck = [];
        PopulateAggregateStats($deck, $empty, 1);
        $this->assertSame([], $deck, 'no stats should produce no aggregates');
    }

    public function testSingleTurnGame(): void
    {
        $this->startGame(1);
        $this->playTurn();

        $stats = $this->aggregatesFor(1);
        $this->assertSame(self::THREAT, (int)$stats['totalDamageThreatened']);
        $this->assertEqualsWithDelta((float)self::THREAT, $stats['averageDamageThreatenedPerTurn'], 0.001);
    }

    // ---------------------------------------------------------------- model canary

    /**
     * The simulation above encodes two engine facts. If either changes, the
     * numbers these tests assert stop describing real games, so fail loudly and
     * point at what needs updating rather than silently passing.
     */
    public function testTurnFlowModelMatchesEngine(): void
    {
        $networking = file_get_contents(ROOT_PATH . '/Libraries/NetworkingLibraries.php');
        $startEffects = file_get_contents(ROOT_PATH . '/StartEffects.php');

        $this->assertMatchesRegularExpression(
            '/if\s*\(\s*\$mainPlayer\s*==\s*\$firstPlayer\s*&&\s*!\$extraTurn\s*\)\s*\{\s*\$currentTurn\s*\+=\s*1\s*;/',
            $networking,
            'FinalizeTurn no longer advances $currentTurn only after the first player\'s turn. '
            . 'GetStatTurnIndex(), CountAttackingTurns() and the turn model in this test all assume it does.'
        );
        $this->assertMatchesRegularExpression(
            '/if\s*\(\s*\$mainPlayer\s*==\s*1\s*\)\s*StatsStartTurn\(\)\s*;/',
            $networking,
            'FinalizeTurn no longer appends a stat block when player 1 takes the turn. '
            . 'The block layout these tests assert depends on it.'
        );
        $this->assertStringContainsString('StatsStartTurn();', $startEffects,
            'StartEffects.php no longer creates the opening stat block.');
    }
}
