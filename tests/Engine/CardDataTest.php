<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../GeneratedCode/GeneratedCardDictionaries.php';

class CardDataTest extends TestCase
{
    public function testActionsHaveTypeA(): void
    {
        $this->assertSame('A', GeneratedCardType('aether_dart_red'));
        $this->assertSame('A', GeneratedCardType('aether_dart_yellow'));
        $this->assertSame('A', GeneratedCardType('aether_dart_blue'));
    }

    public function testAttackActionsHaveTypeAA(): void
    {
        $this->assertSame('AA', GeneratedCardType('surging_strike_red'));
        $this->assertSame('AA', GeneratedCardType('brutal_assault_red'));
    }

    public function testEquipmentHasTypeE(): void
    {
        $this->assertSame('E', GeneratedCardType('ironrot_legs'));
        $this->assertSame('E', GeneratedCardType('ironrot_helm'));
        $this->assertSame('E', GeneratedCardType('ironrot_plate'));
        $this->assertSame('E', GeneratedCardType('ironrot_gauntlet'));
        $this->assertSame('E', GeneratedCardType('fyendals_spring_tunic'));
    }

    public function testHeroesHaveTypeC(): void
    {
        $this->assertSame('C', GeneratedCardType('katsu_the_wanderer'));
        $this->assertSame('C', GeneratedCardType('bravo'));
        $this->assertSame('C', GeneratedCardType('dorinthea_ironsong'));
    }

    public function testUnknownCardTypeReturnsDefaultAA(): void
    {
        $this->assertSame('AA', GeneratedCardType('totally_unknown_card_xyz'));
        $this->assertSame('AA', GeneratedCardType(''));
    }

    public function testIntegerInputReturnsEmptyStringForType(): void
    {
        $this->assertSame('', GeneratedCardType(0));
        $this->assertSame('', GeneratedCardType(42));
    }

    public function testRedCardsDefaultPitchOne(): void
    {
        $this->assertSame(1, GeneratedPitchValue('aether_dart_red'));
        $this->assertSame(1, GeneratedPitchValue('surging_strike_red'));
        $this->assertSame(1, GeneratedPitchValue('brutal_assault_red'));
    }

    public function testYellowCardsPitchTwo(): void
    {
        $this->assertSame(2, GeneratedPitchValue('aether_dart_yellow'));
        $this->assertSame(2, GeneratedPitchValue('surging_strike_yellow'));
        $this->assertSame(2, GeneratedPitchValue('brutal_assault_yellow'));
    }

    public function testBlueCardsPitchThree(): void
    {
        $this->assertSame(3, GeneratedPitchValue('aether_dart_blue'));
        $this->assertSame(3, GeneratedPitchValue('surging_strike_blue'));
        $this->assertSame(3, GeneratedPitchValue('brutal_assault_blue'));
    }

    public function testEquipmentPitchIsZero(): void
    {
        $this->assertSame(0, GeneratedPitchValue('ironrot_legs'));
        $this->assertSame(0, GeneratedPitchValue('fyendals_spring_tunic'));
    }

    public function testHeroPitchIsZero(): void
    {
        $this->assertSame(0, GeneratedPitchValue('katsu_the_wanderer'));
        $this->assertSame(0, GeneratedPitchValue('bravo'));
    }

    public function testUnknownCardPitchIsDefaultOne(): void
    {
        $this->assertSame(1, GeneratedPitchValue('totally_fake_card_red'));
    }

    public function testIntegerInputReturnsZeroForPitch(): void
    {
        $this->assertSame(0, GeneratedPitchValue(0));
        $this->assertSame(0, GeneratedPitchValue(42));
    }

    public function testSurgingStrikePowerValues(): void
    {
        $this->assertSame(5, GeneratedPowerValue('surging_strike_red'));
        $this->assertSame(4, GeneratedPowerValue('surging_strike_yellow'));
        $this->assertSame(3, GeneratedPowerValue('surging_strike_blue'));
    }

    public function testBrutalAssaultPowerValues(): void
    {
        $this->assertSame(6, GeneratedPowerValue('brutal_assault_red'));
        $this->assertSame(5, GeneratedPowerValue('brutal_assault_yellow'));
        $this->assertSame(4, GeneratedPowerValue('brutal_assault_blue'));
    }

    public function testArcaneDamageCardPowerIsZero(): void
    {
        $this->assertSame(0, GeneratedPowerValue('aether_dart_red'));
        $this->assertSame(0, GeneratedPowerValue('aether_dart_yellow'));
        $this->assertSame(0, GeneratedPowerValue('aether_dart_blue'));
    }

    public function testIntegerInputReturnsZeroForPower(): void
    {
        $this->assertSame(0, GeneratedPowerValue(0));
    }

    public function testSurgingStrikeBlockValues(): void
    {
        $this->assertSame(2, GeneratedBlockValue('surging_strike_red'));
        $this->assertSame(2, GeneratedBlockValue('surging_strike_yellow'));
        $this->assertSame(2, GeneratedBlockValue('surging_strike_blue'));
    }

    public function testDefaultBlockValueIsThree(): void
    {
        $this->assertSame(3, GeneratedBlockValue('aether_dart_red'));
        $this->assertSame(3, GeneratedBlockValue('totally_unknown_card'));
    }

    public function testIntegerInputReturnsZeroForBlock(): void
    {
        $this->assertSame(0, GeneratedBlockValue(0));
    }

    public function testEquipmentCostIsMinusOne(): void
    {
        $this->assertSame(-1, GeneratedCardCost('ironrot_legs'));
        $this->assertSame(-1, GeneratedCardCost('ironrot_helm'));
        $this->assertSame(-1, GeneratedCardCost('fyendals_spring_tunic'));
    }

    public function testBrutalAssaultCostIsTwo(): void
    {
        $this->assertSame(2, GeneratedCardCost('brutal_assault_red'));
        $this->assertSame(2, GeneratedCardCost('brutal_assault_yellow'));
        $this->assertSame(2, GeneratedCardCost('brutal_assault_blue'));
    }

    public function testSurgingStrikeCostIsTwo(): void
    {
        $this->assertSame(2, GeneratedCardCost('surging_strike_red'));
        $this->assertSame(2, GeneratedCardCost('surging_strike_yellow'));
        $this->assertSame(2, GeneratedCardCost('surging_strike_blue'));
    }

    public function testUnlistedCardCostIsZero(): void
    {
        $this->assertSame(0, GeneratedCardCost('totally_fake_card_xyz'));
    }

    public function testIntegerInputReturnsZeroForCost(): void
    {
        $this->assertSame(0, GeneratedCardCost(0));
    }

    public function testSurgingStrikeHasGoAgain(): void
    {
        $this->assertTrue(GeneratedGoAgain('surging_strike_red'));
        $this->assertTrue(GeneratedGoAgain('surging_strike_yellow'));
        $this->assertTrue(GeneratedGoAgain('surging_strike_blue'));
    }

    public function testArcaneCussingHasGoAgain(): void
    {
        $this->assertTrue(GeneratedGoAgain('arcane_cussing_red'));
        $this->assertTrue(GeneratedGoAgain('arcane_cussing_yellow'));
        $this->assertTrue(GeneratedGoAgain('arcane_cussing_blue'));
    }

    public function testAetherIronweaveHasGoAgain(): void
    {
        $this->assertTrue(GeneratedGoAgain('aether_ironweave'));
    }

    public function testCardsWithoutGoAgainReturnFalse(): void
    {
        $this->assertFalse(GeneratedGoAgain('aether_dart_red'));
        $this->assertFalse(GeneratedGoAgain('brutal_assault_red'));
        $this->assertFalse(GeneratedGoAgain('ironrot_legs'));
    }

    public function testIntegerInputReturnsZeroForGoAgain(): void
    {
        $this->assertSame(0, GeneratedGoAgain(0));
    }

    public function testPitchColourInvariantSurgingStrike(): void
    {
        $red    = GeneratedPitchValue('surging_strike_red');
        $yellow = GeneratedPitchValue('surging_strike_yellow');
        $blue   = GeneratedPitchValue('surging_strike_blue');

        $this->assertLessThan($yellow, $red,  'Red pitch must be less than yellow');
        $this->assertLessThan($blue,   $yellow, 'Yellow pitch must be less than blue');
    }

    public function testPowerPitchInvariantSurgingStrike(): void
    {
        $redPower    = GeneratedPowerValue('surging_strike_red');
        $yellowPower = GeneratedPowerValue('surging_strike_yellow');
        $bluePower   = GeneratedPowerValue('surging_strike_blue');

        $this->assertGreaterThan($yellowPower, $redPower,    'Red power must exceed yellow');
        $this->assertGreaterThan($bluePower,   $yellowPower, 'Yellow power must exceed blue');
    }

    public function testEquipmentPitchAndCostInvariant(): void
    {
        $equipment = ['ironrot_legs', 'ironrot_helm', 'ironrot_plate', 'ironrot_gauntlet'];
        foreach ($equipment as $card) {
            $this->assertSame(0,  GeneratedPitchValue($card), "$card should have pitch 0");
            $this->assertSame(-1, GeneratedCardCost($card),   "$card should have cost -1");
        }
    }
}
