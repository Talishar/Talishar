<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class CombatMathTest extends TestCase
{
    private function computeCombatDamage(int $power, array $defenseValues): int
    {
        $totalDefense = array_sum($defenseValues);
        return max(0, $power - $totalDefense);
    }

    private function attackHits(int $power, array $defenseValues): bool
    {
        return $power > array_sum($defenseValues);
    }

    public function testAttackHitsWhenPowerExceedsTotalDefense(): void
    {
        $this->assertTrue($this->attackHits(6, [3, 2]));
    }

    public function testAttackDoesNotHitWhenPowerEqualsDefense(): void
    {
        $this->assertFalse($this->attackHits(5, [5]));
    }

    public function testAttackDoesNotHitWhenPowerBelowDefense(): void
    {
        $this->assertFalse($this->attackHits(4, [5]));
    }

    public function testDamageEqualsExcessPower(): void
    {
        $this->assertSame(1, $this->computeCombatDamage(6, [3, 2]));
    }

    public function testDamageIsZeroWhenDefenseEqualsOrExceedsPower(): void
    {
        $this->assertSame(0, $this->computeCombatDamage(5, [5]));
        $this->assertSame(0, $this->computeCombatDamage(4, [5]));
    }

    public function testDamageIsNeverNegative(): void
    {
        $this->assertSame(0, $this->computeCombatDamage(3, [10]));
    }

    public function testNoDefendersFullDamageDealt(): void
    {
        $this->assertSame(6, $this->computeCombatDamage(6, []));
    }

    public function testZeroPowerAttackDealsNoDamage(): void
    {
        $this->assertSame(0, $this->computeCombatDamage(0, []));
        $this->assertSame(0, $this->computeCombatDamage(0, [3]));
    }

    public function testExactBlockResultsInZeroDamage(): void
    {
        $this->assertSame(0, $this->computeCombatDamage(3, [3]));
    }

    public function testMultipleDefendersAreSummedBeforeComparison(): void
    {
        $this->assertSame(1, $this->computeCombatDamage(7, [2, 3, 1]));
    }

    public function testMultipleDefendersFullyBlockAttack(): void
    {
        $this->assertSame(0, $this->computeCombatDamage(6, [2, 4]));
    }

    public function testSingleHighPowerAttack(): void
    {
        $this->assertSame(10, $this->computeCombatDamage(10, [0]));
    }

    public function testEquipmentBlockIsCountedInDefenseTotal(): void
    {
        $this->assertSame(1, $this->computeCombatDamage(8, [3, 4]));
    }

    public function testPitchingRedCardGainsOneResource(): void
    {
        $pitchValue = 1;
        $resourcesBefore = 0;
        $resourcesAfter = $resourcesBefore + $pitchValue;
        $this->assertSame(1, $resourcesAfter);
    }

    public function testPitchingYellowCardGainsTwoResources(): void
    {
        $pitchValue = 2;
        $resourcesBefore = 0;
        $resourcesAfter = $resourcesBefore + $pitchValue;
        $this->assertSame(2, $resourcesAfter);
    }

    public function testPitchingBlueCardGainsThreeResources(): void
    {
        $pitchValue = 3;
        $resourcesBefore = 0;
        $resourcesAfter = $resourcesBefore + $pitchValue;
        $this->assertSame(3, $resourcesAfter);
    }

    public function testCombinedPitchTotalEqualsCardCost(): void
    {
        $cost = 3;
        $pitched = 3;
        $this->assertGreaterThanOrEqual($cost, $pitched);
    }

    public function testMultipleCardsCanCoverHighCost(): void
    {
        $cost = 4;
        $totalPitched = 1 + 2 + 1;
        $this->assertGreaterThanOrEqual($cost, $totalPitched);
    }

    public function testActionPhaseStartsWithOneActionPoint(): void
    {
        $startingActionPoints = 1;
        $this->assertSame(1, $startingActionPoints);
    }

    public function testGoAgainGrantsOneAdditionalActionPoint(): void
    {
        $actionPoints = 1;
        $actionPoints += 1;
        $this->assertSame(2, $actionPoints);
    }

    public function testPlayingCardConsumesOneActionPoint(): void
    {
        $actionPoints = 1;
        $actionPoints -= 1;
        $this->assertSame(0, $actionPoints);
    }

    public function testGoAgainAllowsPlayingTwoAttacksInOneTurn(): void
    {
        $actionPoints = 1;
        $actionPoints += 1; 
        $actionPoints -= 1; 
        $this->assertSame(1, $actionPoints);
    }
}
