<?php

use PHPUnit\Framework\TestCase;

require_once ROOT_PATH . '/AI/BotLogic.php';

final class BotTest extends TestCase
{
    public function testValueBreakdownSeparatesBenefitsAndCosts(): void
    {
        $value = BotValueBreakdown([
            'damagePrevented' => 5,
            'futureHandValue' => 3,
            'cardOpportunityCost' => 2,
            'overblockCost' => 1,
        ]);

        $this->assertSame(5.0, $value['total']);
    }

    public function testLifeThresholdsAreAlternativeRatherThanCumulative(): void
    {
        $this->assertSame(4.0, BotLifeThresholdRisk(1));
        $this->assertSame(2.5, BotLifeThresholdRisk(2));
        $this->assertSame(1.0, BotLifeThresholdRisk(4));
        $this->assertSame(0.0, BotLifeThresholdRisk(5));
        $this->assertInfinite(BotLifeThresholdRisk(0));
    }

    public function testCardRolesPreserveDefenseReactionsAndEfficientBlues(): void
    {
        $reaction = BotCardRolesFromStats('DR', 1, 4, 0);
        $blueBlock = BotCardRolesFromStats('AA', 3, 3, 3);
        $redAttack = BotCardRolesFromStats('AA', 1, 2, 7);

        $this->assertContains('defense-reaction', $reaction['tags']);
        $this->assertGreaterThan($reaction['playValue'], $reaction['arsenalValue']);
        $this->assertLessThan($redAttack['blockCost'], $blueBlock['blockCost']);
    }

    public function testOpponentResponsePricesLikelyBlocksWithoutReadingCards(): void
    {
        $response = BotEvaluateOpponentResponse(8, 20, 4, 2, 3);

        $this->assertSame(8.0, $response['rawDamage']);
        $this->assertGreaterThan(0, $response['expectedPrevention']);
        $this->assertLessThan(8, $response['expectedDamage']);
        $this->assertGreaterThan($response['expectedDamage'], BotResponseWeightedDamage($response));
    }

    public function testDefenseScoreProtectsAgainstLethalButDeclinesWastefulEquipment(): void
    {
        $lethalBlock = BotScoreDefenseCandidate(5, 5, 3, 8, false);
        $equipmentOnSmallAttack = BotScoreDefenseCandidate(1, 20, 3, 2, true);

        $this->assertGreaterThan(100, $lethalBlock);
        $this->assertLessThan(0, $equipmentOnSmallAttack);
    }

    public function testIraPracticeDeckMatchesTheSupportedCcShape(): void
    {
        $lines = file(ROOT_PATH . '/Assets/IraCC.txt', FILE_IGNORE_NEW_LINES);
        $character = preg_split('/\s+/', trim($lines[0]));
        $deck = preg_split('/\s+/', trim($lines[1]));

        $this->assertSame('ira_scarlet_revenger', $character[0]);
        $this->assertContains('edge_of_autumn', $character);
        $this->assertCount(6, $character);
        $this->assertCount(60, $deck);
        $this->assertContains('whirling_mist_blossom_yellow', $deck);
        $this->assertContains('vengeance_never_rests_blue', $deck);
    }
}
