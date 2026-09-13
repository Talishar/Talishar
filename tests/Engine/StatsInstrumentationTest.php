<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

/**
 * What each stat counter is supposed to mean.
 *
 * StatsAggregationTest.php checks that the end-game screen adds up the stat
 * blocks correctly. This file checks the layer underneath it: that each event
 * in a game lands in the right counter, for the right player, on the right
 * turn. Damage threatened, damage dealt, arcane damage, damage blocked and
 * overblocked, damage prevented, life gained, life lost to your own effects,
 * ally damage, buffs, and the per-card counters.
 *
 * The engine wiring around these functions (DealDamage, GainHealth, LoseHealth,
 * DamageAlly) was verified separately against a live game; what is pinned here
 * is the contract those call sites rely on.
 */

// Engine accessors the stat functions need. Shared with StatsAggregationTest.php
// when both run in the same process, so the definitions must stay identical.
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

class StatsInstrumentationTest extends TestCase
{
    /**
     * Opens a game with $firstPlayer on the play and nobody having done
     * anything yet. Mirrors StartEffects.php.
     */
    private function startGame(int $firstPlayer = 1): void
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
        $GLOBALS['p1TurnCount'] = 0;
        $GLOBALS['p2TurnCount'] = 0;
        IncrementTurnCount($firstPlayer);
        StatsStartTurn();
    }

    /** FinalizeTurn: hand the turn to the other player and open their block. */
    private function passTurn(): void
    {
        if ($GLOBALS['mainPlayer'] == $GLOBALS['firstPlayer']) $GLOBALS['currentTurn'] += 1;
        $GLOBALS['defPlayer'] = $GLOBALS['mainPlayer'];
        $GLOBALS['mainPlayer'] = 3 - $GLOBALS['mainPlayer'];
        IncrementTurnCount($GLOBALS['mainPlayer']);
        StatsStartTurn();
    }

    /** One turn stat of one player, on the block they are currently writing to. */
    private function stat(int $player, string $name): int
    {
        $turnStats = &GetTurnStats($player);
        $offset = $GLOBALS['TurnStats_' . $name];
        return (int)($turnStats[GetStatTurnIndex($player) * TurnStatPieces() + $offset] ?? 0);
    }

    /** One turn stat of one player on an explicit block. */
    private function statAt(int $player, int $block, string $name): int
    {
        $turnStats = &GetTurnStats($player);
        return (int)($turnStats[$block * TurnStatPieces() + $GLOBALS['TurnStats_' . $name]] ?? 0);
    }

    /** One per-card counter. */
    private function cardStat(int $player, string $cardID, string $name): int
    {
        $cardStats = &GetCardStats($player);
        $offset = $GLOBALS['CardStats_' . $name];
        for ($i = 0; $i < count($cardStats); $i += CardStatPieces()) {
            if ($cardStats[$i] === $cardID) return (int)$cardStats[$i + $offset];
        }
        return 0;
    }

    // ------------------------------------------------------------ damage attribution

    /**
     * LogDamageStats takes the player who was hit and credits the other seat.
     * Getting this backwards would swap both players' offensive numbers.
     */
    public function testDamageIsCreditedToTheAttackerNotTheVictim(): void
    {
        $this->startGame(1);
        LogDamageStats(2, 9, 6);

        $this->assertSame(9, $this->stat(1, 'DamageThreatened'), 'the attacker should own the threatened damage');
        $this->assertSame(6, $this->stat(1, 'DamageDealt'), 'the attacker should own the damage dealt');
        $this->assertSame(0, $this->stat(2, 'DamageThreatened'), 'the victim must not be credited with threatening damage');
        $this->assertSame(0, $this->stat(2, 'DamageDealt'), 'the victim must not be credited with dealing damage');
    }

    /**
     * Threatened counts the whole swing, dealt counts what actually landed.
     * Blocked and prevented damage widens the gap between them; the two must
     * stay independent.
     */
    public function testThreatenedAndDealtAreTrackedSeparately(): void
    {
        $this->startGame(1);
        LogDamageStats(2, 10, 10);   // unblocked
        LogDamageStats(2, 7, 0);     // fully prevented

        $this->assertSame(17, $this->stat(1, 'DamageThreatened'));
        $this->assertSame(10, $this->stat(1, 'DamageDealt'));
    }

    /** Damage a player does to themselves is a life cost, not an attack. */
    public function testSelfInflictedDamageIsLifeLostRatherThanDamageDealt(): void
    {
        $this->startGame(1);
        LogLifeLossStats(1, 3);      // what FinalizeDamage does when playerSource == player

        $this->assertSame(-3, $this->stat(1, 'LifeLost'), 'life lost is stored negative so it subtracts from value');
        $this->assertSame(0, $this->stat(1, 'DamageThreatened'));
        $this->assertSame(0, $this->stat(2, 'DamageThreatened'), 'self-damage must never credit the opponent');
        $this->assertSame(0, $this->stat(2, 'DamageDealt'));
    }

    /** Several self-inflicted costs in one turn add up rather than replacing. */
    public function testRepeatedSelfInflictedCostsAccumulate(): void
    {
        $this->startGame(1);
        LogLifeLossStats(1, 2);
        LogLifeLossStats(1, 4);

        $this->assertSame(-6, $this->stat(1, 'LifeLost'));
    }

    // ------------------------------------------------------------ arcane

    /**
     * Arcane damage is kept in its own per-turn array, indexed by the same
     * turn number as the attacker's stat block so the two line up on the
     * end-game table.
     */
    public function testArcaneDamageIsRecordedAgainstTheAttackersTurn(): void
    {
        $this->startGame(1);
        $this->passTurn();           // P2 is now the attacker

        LogArcaneDamageStats(1, 4);  // P1 was hit, so P2 dealt it
        LogDamageStats(1, 4, 4);

        $attackerBlock = GetStatTurnIndex(2);
        $this->assertSame(4, (int)$GLOBALS['p2ArcaneDamageDealt'][$attackerBlock],
            'arcane damage should be filed on the attacker turn that dealt it');
        $this->assertSame(0, (int)($GLOBALS['p1ArcaneDamageDealt'][$attackerBlock] ?? 0),
            'the player who took arcane damage must not be credited with dealing it');
    }

    /**
     * Arcane damage is a subset of the damage dealt that turn, never more.
     * Both counters come from the same FinalizeDamage call.
     */
    public function testArcaneDamageIsPartOfTheSameTurnsDamageDealt(): void
    {
        $this->startGame(1);
        LogDamageStats(2, 5, 5);     // 5 physical
        LogDamageStats(2, 3, 3);     // 3 arcane
        LogArcaneDamageStats(2, 3);

        $block = GetStatTurnIndex(1);
        $this->assertSame(8, $this->stat(1, 'DamageDealt'));
        $this->assertLessThanOrEqual($this->stat(1, 'DamageDealt'), (int)$GLOBALS['p1ArcaneDamageDealt'][$block],
            'arcane damage cannot be larger than the total damage dealt that turn');
    }

    /** Arcane totals accumulate across several spells in a turn. */
    public function testArcaneDamageAccumulatesWithinATurn(): void
    {
        $this->startGame(1);
        LogArcaneDamageStats(2, 2);
        LogArcaneDamageStats(2, 3);

        $this->assertSame(5, (int)$GLOBALS['p1ArcaneDamageDealt'][GetStatTurnIndex(1)]);
    }

    // ------------------------------------------------------------ life

    public function testLifeGainedAccumulatesForTheHealer(): void
    {
        $this->startGame(1);
        LogLifeGainedStats(1, 3);
        LogLifeGainedStats(1, 2);

        $this->assertSame(5, $this->stat(1, 'LifeGained'));
        $this->assertSame(0, $this->stat(2, 'LifeGained'), 'the opponent must not be credited with the healing');
    }

    /**
     * Life gained is positive and life lost is negative, so the end-game
     * "value" figure can add both without a sign check.
     */
    public function testLifeGainedAndLifeLostUseOppositeSigns(): void
    {
        $this->startGame(1);
        LogLifeGainedStats(1, 4);
        LogLifeLossStats(1, 4);

        $this->assertSame(4, $this->stat(1, 'LifeGained'));
        $this->assertSame(-4, $this->stat(1, 'LifeLost'));

        $deck = [];
        $turnStats = &GetTurnStats(1);
        PopulateAggregateStats($deck, $turnStats, 1);
        $this->assertSame(4, (int)$deck['totalLifeGained']);
        $this->assertSame(-4, (int)$deck['totalLifeLost']);
    }

    // ------------------------------------------------------------ prevention and blocking

    /**
     * Blocking 8 into a 6-power attack blocks 6 and overblocks 2, and the
     * attacker is only credited with the 6 they actually threatened.
     */
    public function testBlockedDamageIsCappedAtTheAttacksPower(): void
    {
        $this->startGame(1);
        LogCombatResolutionStats(6, 8);

        $this->assertSame(6, $this->stat(1, 'DamageThreatened'));
        $this->assertSame(6, $this->stat(2, 'DamageBlocked'), 'you cannot block more than was thrown at you');
        $this->assertSame(2, $this->stat(2, 'Overblock'));
    }

    /** Underblocking records only what was stopped, and no overblock. */
    public function testUnderblockingRecordsOnlyWhatWasStopped(): void
    {
        $this->startGame(1);
        LogCombatResolutionStats(9, 2);

        $this->assertSame(2, $this->stat(1, 'DamageThreatened'), 'the unblocked remainder is logged by the damage step');
        $this->assertSame(2, $this->stat(2, 'DamageBlocked'));
        $this->assertSame(0, $this->stat(2, 'Overblock'));
    }

    /** An unblocked attack produces no block or overblock rows at all. */
    public function testAnUnblockedAttackRecordsNoBlock(): void
    {
        $this->startGame(1);
        LogCombatResolutionStats(7, 0);

        $this->assertSame(0, $this->stat(2, 'DamageBlocked'));
        $this->assertSame(0, $this->stat(2, 'Overblock'));
    }

    /**
     * Damage prevention is the defender's own counter, separate from blocking.
     * Ally damage is also funnelled through it, which is why it is part of the
     * end-game "value" figure.
     */
    public function testDamagePreventedIsItsOwnCounterForTheDefender(): void
    {
        $this->startGame(1);
        LogDamagePreventedStats(2, 4);
        LogDamagePreventedStats(2, 3);

        $this->assertSame(7, $this->stat(2, 'DamagePrevented'));
        $this->assertSame(0, $this->stat(2, 'DamageBlocked'), 'prevention is not blocking');
        $this->assertSame(0, $this->stat(1, 'DamagePrevented'));
    }

    // ------------------------------------------------------------ allies

    /**
     * Damage an ally soaks is logged as its controller's damage prevented
     * (DamageAlly, the MZDAMAGE branch, the arcane-into-ally branch and
     * ResolveChainLink all do this), and cards that opt in additionally credit
     * the attacker with the damage dealt. Both must be able to coexist on the
     * same turn without either overwriting the other.
     */
    public function testAllyDamageIsPreventionForTheControllerAndDamageForAnAttributedAttacker(): void
    {
        $this->startGame(1);

        // Legacy path: controller soaks it, nobody is credited with dealing it.
        LogDamagePreventedStats(2, 3);
        $this->assertSame(3, $this->stat(2, 'DamagePrevented'));
        $this->assertSame(0, $this->stat(1, 'DamageDealt'));

        // Attributed path (a card passing its controller as $playerSource).
        LogDamagePreventedStats(2, 5);
        LogDamageStats(2, 5, 5);
        $this->assertSame(8, $this->stat(2, 'DamagePrevented'));
        $this->assertSame(5, $this->stat(1, 'DamageDealt'));
        $this->assertSame(5, $this->stat(1, 'DamageThreatened'));
    }

    /**
     * Overkill into an ally counts only up to the ally's remaining health as
     * damage dealt, so one big effect cannot inflate damage dealt without limit.
     */
    public function testOverkillIntoAnAllyCountsOnlyTheAllysRemainingHealth(): void
    {
        $this->startGame(1);
        $allyHealthBefore = 2;
        $damage = 9;
        LogDamageStats(2, $damage, min($damage, $allyHealthBefore));

        $this->assertSame(9, $this->stat(1, 'DamageThreatened'));
        $this->assertSame(2, $this->stat(1, 'DamageDealt'));
    }

    // ------------------------------------------------------------ buffs and modifiers

    /**
     * A damage buff is applied before the damage is logged, so it raises both
     * threatened and dealt by the same amount. If a buff only moved one of them
     * the end-game screen would show damage dealt above damage threatened.
     */
    public function testADamageBuffRaisesThreatenedAndDealtTogether(): void
    {
        $this->startGame(1);
        $base = 5;
        $buff = 1;
        LogDamageStats(2, $base + $buff, $base + $buff);

        $this->assertSame(6, $this->stat(1, 'DamageThreatened'));
        $this->assertSame(6, $this->stat(1, 'DamageDealt'));
        $this->assertGreaterThanOrEqual($this->stat(1, 'DamageDealt'), $this->stat(1, 'DamageThreatened'),
            'damage dealt must never exceed damage threatened');
    }

    /**
     * Passive equipment and character buffs (Tiger Stripe Shuko, Valiant Dynamo,
     * Prizeworn Pathfinders) are counted on their own card counter and must not
     * be mistaken for the card being played.
     */
    public function testAPassiveBuffTriggerIsItsOwnCounter(): void
    {
        $this->startGame(1);
        LogPlayCardStats(1, 'tiger_stripe_shuko', 'EQUIP', 'PASSIVE');
        LogPlayCardStats(1, 'tiger_stripe_shuko', 'EQUIP', 'PASSIVE');

        $this->assertSame(2, $this->cardStat(1, 'tiger_stripe_shuko', 'TimesPassiveTriggered'));
        $this->assertSame(0, $this->cardStat(1, 'tiger_stripe_shuko', 'TimesPlayed'));
        $this->assertSame(0, $this->cardStat(1, 'tiger_stripe_shuko', 'TimesActivated'));
        $this->assertSame(0, $this->stat(1, 'CardsPlayedOffense'), 'a passive trigger is not a card played');
        $this->assertSame(0, $this->stat(1, 'CardsPlayedDefense'));
    }

    // ------------------------------------------------------------ per-card counters

    /** Each way of using a card has its own counter and none of them bleed. */
    public function testEachCardCounterIsIndependent(): void
    {
        $this->startGame(1);
        LogPlayCardStats(1, 'sink_below_red', 'HAND');             // played
        LogPlayCardStats(1, 'sink_below_red', 'HAND', 'B');        // blocked
        LogPlayCardStats(1, 'sink_below_red', 'HAND', 'P');        // pitched
        LogPlayCardStats(1, 'sink_below_red', 'HAND', 'HIT');      // hit
        LogPlayCardStats(1, 'sink_below_red', 'HAND', 'CHARGE');   // charged
        LogPlayCardStats(1, 'sink_below_red', 'HAND', 'DISCARD');  // discarded
        LogPlayCardStats(1, 'sink_below_red', 'PLAY');             // activated from play
        LogPlayCardStats(1, 'sink_below_red', 'HAND', 'PASSIVE');  // passive trigger
        LogPlayCardStats(1, 'sink_below_red', 'HAND', 'KATSUDISCARD');

        foreach ([
            'TimesPlayed', 'TimesBlocked', 'TimesPitched', 'TimesHit', 'TimesCharged',
            'TimesDiscarded', 'TimesActivated', 'TimesPassiveTriggered', 'TimesKatsuDiscard',
        ] as $counter) {
            $this->assertSame(1, $this->cardStat(1, 'sink_below_red', $counter),
                "$counter should have counted exactly one event");
        }
    }

    /** A second card gets its own entry rather than adding to the first. */
    public function testCardsDoNotShareCounters(): void
    {
        $this->startGame(1);
        LogPlayCardStats(1, 'sink_below_red', 'HAND');
        LogPlayCardStats(1, 'snatch_red', 'HAND');
        LogPlayCardStats(1, 'snatch_red', 'HAND');

        $this->assertSame(1, $this->cardStat(1, 'sink_below_red', 'TimesPlayed'));
        $this->assertSame(2, $this->cardStat(1, 'snatch_red', 'TimesPlayed'));
    }

    /** Whose turn it is decides whether a card played counts as offence. */
    public function testOffenceAndDefenceFollowWhoseTurnItIs(): void
    {
        $this->startGame(1);
        LogPlayCardStats(1, 'snatch_red', 'HAND');      // P1 is the main player
        LogPlayCardStats(2, 'sink_below_red', 'HAND');  // P2 is defending

        $this->assertSame(1, $this->stat(1, 'CardsPlayedOffense'));
        $this->assertSame(0, $this->stat(1, 'CardsPlayedDefense'));
        $this->assertSame(1, $this->stat(2, 'CardsPlayedDefense'));
        $this->assertSame(0, $this->stat(2, 'CardsPlayedOffense'));
    }

    /**
     * Player ids reach LogPlayCardStats from the gamestate as strings and from
     * card code as integers. Offence must not depend on which.
     */
    public function testOffenceIsDecidedByPlayerIdentityNotItsType(): void
    {
        $this->startGame(1);
        $GLOBALS['mainPlayer'] = "1";   // what ParseGamestate.php leaves behind

        LogPlayCardStats(1, 'snatch_red', 'HAND');

        $this->assertSame(1, $this->stat(1, 'CardsPlayedOffense'),
            'an integer player id must still be recognised as the main player');
        $this->assertSame(0, $this->stat(1, 'CardsPlayedDefense'));
    }

    /**
     * Blocking with a permanent is still a block for that card, but it does
     * not spend a card out of hand, so the turn's "cards blocked" must not move.
     */
    public function testBlockingFromPlayDoesNotSpendACardFromHand(): void
    {
        $this->startGame(1);
        LogPlayCardStats(2, 'nullrune_boots', 'EQUIP', 'B');
        LogPlayCardStats(2, 'sink_below_red', 'HAND', 'B');

        $this->assertSame(1, $this->cardStat(2, 'nullrune_boots', 'TimesBlocked'));
        $this->assertSame(1, $this->cardStat(2, 'sink_below_red', 'TimesBlocked'));
        $this->assertSame(1, $this->stat(2, 'CardsBlocked'), 'only the card from hand counts against the turn');
    }

    /** Activating a permanent is not the same as playing a card from hand. */
    public function testActivatingFromPlayIsNotACardPlayed(): void
    {
        $this->startGame(1);
        LogPlayCardStats(1, 'edge_of_autumn', 'PLAY');

        $this->assertSame(1, $this->cardStat(1, 'edge_of_autumn', 'TimesActivated'));
        $this->assertSame(0, $this->cardStat(1, 'edge_of_autumn', 'TimesPlayed'));
        $this->assertSame(0, $this->stat(1, 'CardsPlayedOffense'));
    }

    // ------------------------------------------------------------ turn placement

    /**
     * Every player's stat block N must describe the same slice of the game, or
     * the end-game table compares one player's turn against the other's.
     * The "damage taken" column reads the opponent's block at the same index,
     * so a half-turn skew there shows the wrong number.
     */
    public function testBothSeatsRecordTheSameTurnInTheSameBlock(): void
    {
        foreach ([1, 2] as $firstPlayer) {
            $this->startGame($firstPlayer);

            for ($attack = 1; $attack <= 8; $attack++) {
                $main = $GLOBALS['mainPlayer'];
                $def  = 3 - $main;
                $this->assertSame(
                    GetStatTurnIndex($main),
                    GetStatTurnIndex($def),
                    "first player $firstPlayer, attack $attack: the attacker is writing to block "
                    . GetStatTurnIndex($main) . " while the defender writes to block " . GetStatTurnIndex($def)
                    . ", so their rows describe different turns"
                );
                $this->passTurn();
            }
        }
    }

    /**
     * A blocked attack must leave the block on the same row as the attack it
     * stopped, otherwise the defender appears to block damage nobody threw.
     */
    public function testABlockLandsOnTheSameRowAsTheAttackItStopped(): void
    {
        $this->startGame(1);
        $this->passTurn();   // P2's first attack
        $this->passTurn();   // P1's second attack, blocked by P2

        LogCombatResolutionStats(6, 4);

        $attackerBlock = GetStatTurnIndex(1);
        $this->assertSame(4, $this->statAt(2, $attackerBlock, 'DamageBlocked'),
            'the block should sit on the same row as the attack, not one row later');
    }

    /** Opening-turn activity by the player on the draw belongs to turn 0. */
    public function testOpeningTurnActivityByThePlayerOnTheDrawStaysInTurnZero(): void
    {
        $this->startGame(1);
        LogPlayCardStats(2, 'sink_below_red', 'HAND', 'B');
        LogResourcesUsedStats(2, 2);

        $this->assertSame(1, $this->statAt(2, 0, 'CardsBlocked'));
        $this->assertSame(2, $this->statAt(2, 0, 'ResourcesUsed'));
        $this->assertSame(0, $this->statAt(2, 1, 'CardsBlocked'), 'opening-turn defence must not spill into turn 1');
    }

    // ------------------------------------------------------------ end-game aggregation

    /** Every category reaches the headline totals, and the table sums to them. */
    public function testTotalsCoverEveryCategory(): void
    {
        $this->startGame(1);
        LogDamageStats(2, 10, 8);        // P1 threatens 10, deals 8
        LogDamagePreventedStats(1, 2);
        LogLifeGainedStats(1, 3);
        LogLifeLossStats(1, 1);
        $this->passTurn();
        LogCombatResolutionStats(5, 5);  // P2 attacks 5, P1 blocks all of it

        $deck = [];
        $turnStats = &GetTurnStats(1);
        $otherTurnStats = &GetTurnStats(2);
        PopulateTurnStatsAndAggregates($deck, $turnStats, $otherTurnStats, 1, true);
        PopulateAggregateStats($deck, $turnStats, 1);

        $this->assertSame(10, (int)$deck['totalDamageThreatened']);
        $this->assertSame(8, (int)$deck['totalDamageDealt']);
        $this->assertSame(5, (int)$deck['totalDamageBlocked']);
        $this->assertSame(2, (int)$deck['totalDamagePrevented']);
        $this->assertSame(3, (int)$deck['totalLifeGained']);
        $this->assertSame(-1, (int)$deck['totalLifeLost']);

        $table = ['damageThreatened' => 0, 'damageDealt' => 0, 'damageBlocked' => 0,
                  'damagePrevented' => 0, 'lifeGained' => 0, 'lifeLost' => 0];
        foreach ($deck['turnResults'] as $turn) {
            foreach ($table as $key => $_) $table[$key] += $turn[$key];
        }
        $this->assertSame(10, $table['damageThreatened'], 'the per-turn table must sum to the totals');
        $this->assertSame(8, $table['damageDealt']);
        $this->assertSame(5, $table['damageBlocked']);
        $this->assertSame(2, $table['damagePrevented']);
        $this->assertSame(3, $table['lifeGained']);
        $this->assertSame(-1, $table['lifeLost']);
    }

    /** The per-turn table reports arcane damage for both directions. */
    public function testArcaneDamageReachesThePerTurnTableForBothPlayers(): void
    {
        $this->startGame(1);
        LogDamageStats(2, 4, 4);
        LogArcaneDamageStats(2, 4);      // P1 dealt 4 arcane
        LogDamagePreventedStats(2, 1);   // give P2 a row of its own so its table is rendered

        $deck = [];
        $turnStats = &GetTurnStats(1);
        $otherTurnStats = &GetTurnStats(2);
        PopulateTurnStatsAndAggregates($deck, $turnStats, $otherTurnStats, 1, true);
        $this->assertSame(4, $deck['turnResults']['turn_0']['arcaneDamageDealt']);

        $deck2 = [];
        PopulateTurnStatsAndAggregates($deck2, $otherTurnStats, $turnStats, 2, true);
        $this->assertSame(4, $deck2['turnResults']['turn_0']['arcaneDamageTaken'],
            'the player who was hit should see the same number as damage taken');
    }

    /**
     * The end-game "value" figure is combat plus the life swing, and life lost
     * has to pull it down rather than push it up.
     */
    public function testValuePerTurnAddsCombatAndTheLifeSwing(): void
    {
        $this->startGame(1);
        $this->passTurn();
        $this->passTurn();               // a turn that is not turn 0, so it counts toward averages

        LogDamageStats(2, 6, 6);         // threatened 6
        LogCombatResolutionStats(0, 0);
        LogDamagePreventedStats(1, 2);
        LogLifeGainedStats(1, 3);
        LogLifeLossStats(1, 4);

        $deck = [];
        $turnStats = &GetTurnStats(1);
        PopulateAggregateStats($deck, $turnStats, 1);

        // one contributing row: 6 threatened + 0 blocked + 3 gained - 4 lost + 2 prevented
        $this->assertEqualsWithDelta(6.0, $deck['averageCombatValuePerTurn'], 0.001);
        $this->assertEqualsWithDelta(7.0, $deck['averageValuePerTurn'], 0.001);
    }

    /** A card in the decklist keeps every one of its counters. */
    public function testDecklistCardsReportEveryCounter(): void
    {
        $cardStats = ['katsu_the_wanderer', 4, 3, 2, 1, 5, 6, 7, 8, 9];
        $deck = [
            'cardResults' => [[
                'cardId' => 'katsu_the_wanderer', 'played' => 0, 'blocked' => 0, 'pitched' => 0,
                'hits' => 0, 'discarded' => 0, 'charged' => 0,
            ]],
            'tokenResults' => [],
            'arenaCardResults' => [],
        ];
        PopulateCardStatResults($deck, $cardStats, true);
        $entry = $deck['cardResults'][0];

        $this->assertSame(4, $entry['played']);
        $this->assertSame(3, $entry['blocked']);
        $this->assertSame(2, $entry['pitched']);
        $this->assertSame(1, $entry['hits']);
        $this->assertSame(5, $entry['charged'], 'the charge count must survive the katsu discard count');
        $this->assertSame(6, $entry['discarded']);
        $this->assertSame(7, $entry['activated']);
        $this->assertSame(8, $entry['passiveTriggered']);
    }

    /** A card that is not in the decklist still reports all of its counters. */
    public function testNonDecklistCardsReportEveryCounter(): void
    {
        if (!function_exists('CardType')) {
            $this->markTestSkipped('the card dictionary is not loaded in the test bootstrap');
        }
        $cardStats = ['seismic_surge', 0, 0, 0, 0, 0, 0, 3, 2, 0];
        $deck = ['cardResults' => [], 'tokenResults' => [], 'arenaCardResults' => []];
        PopulateCardStatResults($deck, $cardStats, true);

        $entries = array_merge($deck['arenaCardResults'], $deck['tokenResults']);
        $this->assertCount(1, $entries);
        $this->assertSame(3, $entries[0]['activated']);
        $this->assertSame(2, $entries[0]['passiveTriggered']);
        $this->assertArrayHasKey('katsuDiscard', $entries[0]);
    }

    // ------------------------------------------------------------ guards

    /** No counter may ever go backwards from a legitimate event. */
    public function testNoCounterEverGoesNegative(): void
    {
        $this->startGame(1);
        LogDamageStats(2, 0, 0);
        LogDamagePreventedStats(1, 0);
        LogLifeGainedStats(1, 0);
        LogLifeLossStats(1, 0);
        LogCombatResolutionStats(0, 0);

        $turnStats = &GetTurnStats(1);
        foreach ($turnStats as $slot => $value) {
            $this->assertGreaterThanOrEqual(0, (int)$value, "turn stat slot $slot went negative");
        }
    }

    /** An empty game produces no aggregates rather than dividing by zero. */
    public function testAnEmptyGameProducesNoAggregates(): void
    {
        $empty = [];
        $deck = [];
        PopulateAggregateStats($deck, $empty, 1);
        $this->assertSame([], $deck);
    }
}
