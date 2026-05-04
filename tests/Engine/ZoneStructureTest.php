<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../Constants.php';

class ZoneStructureTest extends TestCase
{
    public function testHandPiecesIsOne(): void
    {
        $this->assertSame(1, HandPieces());
    }

    public function testDeckPiecesIsOne(): void
    {
        $this->assertSame(1, DeckPieces());
    }

    public function testPitchPiecesIsOne(): void
    {
        $this->assertSame(1, PitchPieces());
    }

    public function testDiscardPiecesIsThree(): void
    {
        $this->assertSame(3, DiscardPieces());
    }

    public function testBanishPiecesIsThree(): void
    {
        $this->assertSame(3, BanishPieces());
    }

    public function testArsenalPiecesIsSeven(): void
    {
        $this->assertSame(7, ArsenalPieces());
    }

    public function testCharacterPiecesIsFifteen(): void
    {
        $this->assertSame(15, CharacterPieces());
    }

    public function testCombatChainPiecesIsTwelve(): void
    {
        $this->assertSame(12, CombatChainPieces());
    }

    public function testAuraPiecesIsFourteen(): void
    {
        $this->assertSame(14, AuraPieces());
    }

    public function testItemPiecesIsFourteen(): void
    {
        $this->assertSame(14, ItemPieces());
    }

    public function testAllyPiecesIsFifteen(): void
    {
        $this->assertSame(15, AllyPieces());
    }

    public function testPermanentPiecesIsFour(): void
    {
        $this->assertSame(4, PermanentPieces());
    }

    public function testLayerPiecesIsSeven(): void
    {
        $this->assertSame(7, LayerPieces());
    }

    public function testChainLinksPiecesIsTen(): void
    {
        $this->assertSame(10, ChainLinksPieces());
    }

    public function testChainLinkSummaryPiecesIsNine(): void
    {
        $this->assertSame(9, ChainLinkSummaryPieces());
    }

    public function testCurrentTurnEffectsPiecesIsFour(): void
    {
        $this->assertSame(4, CurrentTurnEffectsPieces());
    }

    public function testNextTurnEffectsPiecesIsFive(): void
    {
        $this->assertSame(5, NextTurnEffectsPieces());
    }

    public function testSoulPiecesIsOne(): void
    {
        $this->assertSame(1, SoulPieces());
    }

    public function testEventPiecesIsTwo(): void
    {
        $this->assertSame(2, EventPieces());
    }

    public function testPublicZoneListHasTwelveZones(): void
    {
        $publicZones = [
            'arms', 'banished', 'chest', 'combat_chain',
            'graveyard', 'head', 'hero', 'legs',
            'permanent', 'pitch', 'stack', 'weapon',
        ];
        $this->assertCount(12, $publicZones);
    }

    public function testPrivateZoneListHasThreeZones(): void
    {
        $privateZones = ['arsenal', 'deck', 'hand'];
        $this->assertCount(3, $privateZones);
    }

    public function testArenaZoneListHasEightZones(): void
    {
        $arenaZones = [
            'arms', 'chest', 'combat_chain', 'head',
            'hero', 'legs', 'permanent', 'weapon',
        ];
        $this->assertCount(8, $arenaZones);
    }

    public function testNonArenaZonesAreCorrect(): void
    {
        $nonArenaZones = [
            'arsenal', 'banished', 'deck', 'graveyard',
            'hand', 'pitch', 'stack',
        ];
        $this->assertCount(7, $nonArenaZones);
    }

    public function testTotalZoneTypeCountIsCorrect(): void
    {
        $allZoneTypes = [
            'arms', 'arsenal', 'banished', 'chest', 'combat_chain',
            'deck', 'graveyard', 'hand', 'head', 'hero',
            'legs', 'permanent', 'pitch', 'stack', 'weapon',
        ];
        $this->assertCount(15, $allZoneTypes);
    }

    public function testCombatChainCardIDIsAtIndexZero(): void
    {
        $cardIdIndex = 0;
        $this->assertLessThan(CombatChainPieces(), $cardIdIndex);
    }

    public function testCombatChainPlayerIsAtIndexOne(): void
    {
        $playerIndex = 1;
        $this->assertLessThan(CombatChainPieces(), $playerIndex);
    }

    public function testCombatChainFromIsAtIndexTwo(): void
    {
        $fromIndex = 2;
        $this->assertLessThan(CombatChainPieces(), $fromIndex);
    }

    public function testCombatChainUniqueIDIsAtIndexSeven(): void
    {
        $uidIndex = 7;
        $this->assertLessThan(CombatChainPieces(), $uidIndex);
    }

    public function testCombatChainOriginUniqueIDIsAtIndexEight(): void
    {
        $originUidIndex = 8;
        $this->assertLessThan(CombatChainPieces(), $originUidIndex);
    }
}
