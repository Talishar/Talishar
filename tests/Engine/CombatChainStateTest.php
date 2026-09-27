<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../Constants.php';

class CombatChainStateTest extends TestCase
{
    public function testCurrentAttackGainedGoAgainIsAtIndexZero(): void
    {
        global $CCS_CurrentAttackGainedGoAgain;
        $this->assertSame(0, $CCS_CurrentAttackGainedGoAgain);
    }

    public function testWeaponIndexIsAtIndexOne(): void
    {
        global $CCS_WeaponIndex;
        $this->assertSame(1, $CCS_WeaponIndex);
    }

    public function testHasAimCounterIsAtIndexTwo(): void
    {
        global $CCS_HasAimCounter;
        $this->assertSame(2, $CCS_HasAimCounter);
    }

    public function testAttackNumChargedIsAtIndexThree(): void
    {
        global $CCS_AttackNumCharged;
        $this->assertSame(3, $CCS_AttackNumCharged);
    }

    public function testDamageDealtIsAtIndexFour(): void
    {
        global $CCS_DamageDealt;
        $this->assertSame(4, $CCS_DamageDealt);
    }

    public function testHitsWithWeaponIsAtIndexSix(): void
    {
        global $CCS_HitsWithWeapon;
        $this->assertSame(6, $CCS_HitsWithWeapon);
    }

    public function testGoesWhereAfterLinkResolvesIsAtIndexSeven(): void
    {
        global $CCS_GoesWhereAfterLinkResolves;
        $this->assertSame(7, $CCS_GoesWhereAfterLinkResolves);
    }

    public function testAttackPlayedFromIsAtIndexEight(): void
    {
        global $CCS_AttackPlayedFrom;
        $this->assertSame(8, $CCS_AttackPlayedFrom);
    }

    public function testAttackTargetIsAtIndexSixteen(): void
    {
        global $CCS_AttackTarget;
        $this->assertSame(16, $CCS_AttackTarget);
    }

    public function testLinkTotalPowerIsAtIndexSeventeen(): void
    {
        global $CCS_LinkTotalPower;
        $this->assertSame(17, $CCS_LinkTotalPower);
    }

    public function testCachedTotalPowerIsAtIndexTwentyTwo(): void
    {
        global $CCS_CachedTotalPower;
        $this->assertSame(22, $CCS_CachedTotalPower);
    }

    public function testCachedTotalBlockIsAtIndexTwentyThree(): void
    {
        global $CCS_CachedTotalBlock;
        $this->assertSame(23, $CCS_CachedTotalBlock);
    }

    public function testCombatDamageReplacedIsAtIndexTwentyFour(): void
    {
        global $CCS_CombatDamageReplaced;
        $this->assertSame(24, $CCS_CombatDamageReplaced);
    }

    public function testAttackUniqueIDIsAtIndexTwentyFive(): void
    {
        global $CCS_AttackUniqueID;
        $this->assertSame(25, $CCS_AttackUniqueID);
    }

    public function testCachedDominateActiveIsAtIndexTwentySeven(): void
    {
        global $CCS_CachedDominateActive;
        $this->assertSame(27, $CCS_CachedDominateActive);
    }

    public function testIsBoostedIsAtIndexTwentyNine(): void
    {
        global $CCS_IsBoosted;
        $this->assertSame(29, $CCS_IsBoosted);
    }

    public function testCachedOverpowerActiveIsAtIndexThirtyOne(): void
    {
        global $CCS_CachedOverpowerActive;
        $this->assertSame(31, $CCS_CachedOverpowerActive);
    }

    public function testHitThisLinkIsAtIndexThirtyFour(): void
    {
        global $CCS_HitThisLink;
        $this->assertSame(34, $CCS_HitThisLink);
    }

    public function testCachedGoAgainIsAtIndexFortyEight(): void
    {
        global $CCS_CachedGoAgain;
        $this->assertSame(48, $CCS_CachedGoAgain);
    }

    public function testAttackDamageDealtToHeroIsAtIndexFortyNine(): void
    {
        global $CCS_AttackDamageDealtToHero;
        $this->assertSame(49, $CCS_AttackDamageDealtToHero);
    }

    public function testNumActionsPlayedIsAtIndexSeven(): void
    {
        global $CS_NumActionsPlayed, $classStateDefaults;
        $this->assertSame("NumActionsPlayed", $CS_NumActionsPlayed);
        $this->assertSame(7, array_search($CS_NumActionsPlayed, array_keys($classStateDefaults)));
    }

    public function testDamageTakenIsAtIndexSix(): void
    {
        global $CS_DamageTaken, $classStateDefaults;
        $this->assertSame("DamageTaken", $CS_DamageTaken);
        $this->assertSame(6, array_search($CS_DamageTaken, array_keys($classStateDefaults)));
    }

    public function testNumCardsDrawnIsAtIndexTwelve(): void
    {
        global $CS_NumCardsDrawn, $classStateDefaults;
        $this->assertSame("NumCardsDrawn", $CS_NumCardsDrawn);
        $this->assertSame(12, array_search($CS_NumCardsDrawn, array_keys($classStateDefaults)));
    }

    public function testNumAttacksIsAtIndexThirty(): void
    {
        global $CS_NumAttacks, $classStateDefaults;
        $this->assertSame("NumAttacks", $CS_NumAttacks);
        $this->assertSame(30, array_search($CS_NumAttacks, array_keys($classStateDefaults)));
    }

    public function testDamageDealtIsAtIndexFiftyOne(): void
    {
        global $CS_DamageDealt, $classStateDefaults;
        $this->assertSame("DamageDealt", $CS_DamageDealt);
        $this->assertSame(51, array_search($CS_DamageDealt, array_keys($classStateDefaults)));
    }

    public function testDamageDealtToOpponentIsAtIndexNinetyFour(): void
    {
        global $CS_DamageDealtToOpponent, $classStateDefaults;
        $this->assertSame("DamageDealtToOpponent", $CS_DamageDealtToOpponent);
        $this->assertSame(94, array_search($CS_DamageDealtToOpponent, array_keys($classStateDefaults)));
    }

    public function testWeaponIndexSentinelIsNegativeOne(): void
    {
        $noWeaponSentinel = -1;
        $this->assertSame(-1, $noWeaponSentinel);
    }

    public function testAttackTargetDefaultIsNA(): void
    {
        $defaultAttackTarget = "NA";
        $this->assertSame("NA", $defaultAttackTarget);
    }

    public function testGoesWhereAfterLinkDefaultIsGY(): void
    {
        $defaultDestination = "GY";
        $this->assertSame("GY", $defaultDestination);
    }

    public function testShmopCurrentPlayerOffsetIsNine(): void
    {
        global $SHMOP_CURRENTPLAYER;
        $this->assertSame(9, $SHMOP_CURRENTPLAYER);
    }
}
