<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../Libraries/DeckbuilderAltArts.php';

class DeckbuilderAltArtsTest extends TestCase
{
    private function deck(): object
    {
        return json_decode(json_encode([
            'cards' => [
                ['identifier' => 'a-drop-in-the-ocean-blue', 'imageOverride' => 'MST095-MV', 'total' => 3],
                ['identifier' => 'aurora-shooting-star', 'imageOverride' => 'ROS007-MV', 'total' => 1],
                ['identifier' => 'sink-below-red', 'total' => 3],
            ],
            'tokenImageOverrides' => [
                ['identifier' => 'embodiment-of-lightning', 'imageOverride' => 'FAB119-CF'],
            ],
        ]));
    }

    public function testReadsPinsFromCardsAndTokens(): void
    {
        $this->assertSame([
            'a_drop_in_the_ocean_blue' => 'MST095-MV',
            'aurora_shooting_star' => 'ROS007-MV',
            'embodiment_of_lightning' => 'FAB119-CF',
        ], GetDeckbuilderPins($this->deck()));
    }

    public function testADeckWithoutPinsHasNone(): void
    {
        $this->assertSame([], GetDeckbuilderPins(json_decode('{"cards":[{"identifier":"sink-below-red","total":3}]}')));
        $this->assertSame([], GetDeckbuilderPins(json_decode('{"name":"no cards"}')));
    }

    public function testMapsAPrintingToItsSetNumbersAltArt(): void
    {
        $this->assertSame('MST095-T', DeckbuilderPinAltPath('MST095-MV'));
        $this->assertSame('MST095-T', DeckbuilderPinAltPath('MST095'));
        $this->assertNull(DeckbuilderPinAltPath('not-an-image'));
    }

    public function testKeepsOnlyPinsThePlayerHasUnlocked(): void
    {
        $entitlements = [
            'a_drop_in_the_ocean_blue' => ['LGS282-T', 'MST095-T'],
            'aurora_shooting_star' => ['ROS001-T'],
        ];
        $this->assertSame(
            ['a_drop_in_the_ocean_blue' => 'MST095-T'],
            GetEntitledDeckbuilderAltArts(GetDeckbuilderPins($this->deck()), $entitlements)
        );
    }

    public function testAPlayerWithoutAltArtsGetsNoPins(): void
    {
        $this->assertSame([], GetEntitledDeckbuilderAltArts(GetDeckbuilderPins($this->deck()), []));
    }

    public function testEntriesUseTheFrontendAltArtShape(): void
    {
        $this->assertSame(
            [['name' => 'Deckbuilder', 'cardId' => 'a_drop_in_the_ocean_blue', 'altPath' => 'MST095-T']],
            DeckbuilderAltArtEntries(['a_drop_in_the_ocean_blue' => 'MST095-T'])
        );
    }
}
