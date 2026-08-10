<?php

namespace Talishar\Tests\BusinessLogic;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class GameResultSerializationTest extends TestCase
{
    public static function characterLines(): array
    {
        return [
            'untransformed hero' => ['bravo_showstopper tunic_of_fyendal', 'bravo_showstopper'],
            'transformed Arakni game' => ['arakni_marionette arakni_orb_weaver', 'arakni_marionette'],
            'transformed Teklovossen game' => ['teklovossen_esteemed_magnate singularity_r', 'teklovossen_esteemed_magnate'],
            'mixed whitespace' => [" \tviserai_the_forsaken   face_purgatory\n", 'viserai_the_forsaken'],
            'missing character line' => ['', ''],
            'whitespace-only character line' => [" \t\r\n", ''],
        ];
    }

    #[DataProvider('characterLines')]
    public function testExtractStartingHeroFromFirstSubmittedCharacter(string $line, string $expected): void
    {
        $this->assertSame($expected, ExtractStartingHeroFromCharacterLine($line));
    }

    public function testStartingHeroSurvivesPrivateTelemetryExclusion(): void
    {
        $deck = [
            'character' => [['cardId' => 'levia_shadowborn_abomination']],
            'cardResults' => [['cardId' => 'blood_debt_card']],
            'deckbuilderID' => 'private-deck',
        ];

        AddStartingHeroToDetailedResult($deck, 'levia_shadowborn_abomination blasmophet_levia_consumed');
        ExcludePrivateDetailedGameResultFields($deck);

        $this->assertSame('levia_shadowborn_abomination', $deck['startingHero']);
        $this->assertArrayNotHasKey('character', $deck);
        $this->assertArrayNotHasKey('cardResults', $deck);
        $this->assertArrayNotHasKey('deckbuilderID', $deck);
    }

    public function testMissingCharacterDoesNotEmitStartingHero(): void
    {
        $deck = [];
        AddStartingHeroToDetailedResult($deck, " \t ");
        $this->assertArrayNotHasKey('startingHero', $deck);
    }
}
