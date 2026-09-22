<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;

#[RunTestsInSeparateProcesses]
#[PreserveGlobalState(false)]
class KorshemTest extends TestCase
{
    protected function setUp(): void
    {
        require_once ROOT_PATH . '/Libraries/CoreLibraries.php';
        require_once ROOT_PATH . '/GameLogic.php';

        global $CS_KorshemConditionMet;
        $GLOBALS['mainPlayerGamestateStillBuilt'] = true;
        $GLOBALS['mainPlayer'] = 1;
        $GLOBALS['defPlayer'] = 2;
        $GLOBALS['currentPlayer'] = 1;
        $GLOBALS['mainClassState'] = array_fill(0, $CS_KorshemConditionMet + 1, 0);
        $GLOBALS['defClassState'] = array_fill(0, $CS_KorshemConditionMet + 1, 0);
        $GLOBALS['mainResources'] = [0, 0];
        $GLOBALS['defResources'] = [0, 0];
        $GLOBALS['mainItems'] = [];
        $GLOBALS['defItems'] = [];
        $GLOBALS['mainAuras'] = [];
        $GLOBALS['defAuras'] = [];
        $GLOBALS['p1Permanents'] = [];
        $GLOBALS['p2Permanents'] = [];
        $GLOBALS['mainCharacter'] = array_fill(0, CharacterPieces(), 0);
        $GLOBALS['mainCharacter'][0] = 'bravo';
        $GLOBALS['mainCharacter'][1] = 2;
        $GLOBALS['defCharacter'] = array_fill(0, CharacterPieces(), 0);
        $GLOBALS['defCharacter'][0] = 'bravo';
        $GLOBALS['defCharacter'][1] = 2;
        $GLOBALS['mainHealth'] = 20;
        $GLOBALS['defHealth'] = 20;
        $GLOBALS['mainTurnStats'] = [];
        $GLOBALS['defTurnStats'] = [];
        $GLOBALS['p1TurnCount'] = 0;
        $GLOBALS['p2TurnCount'] = 0;
        $GLOBALS['firstPlayer'] = 1;
        $GLOBALS['currentTurnEffects'] = [];
        $GLOBALS['landmarks'] = [];
        $GLOBALS['layers'] = [];
        $GLOBALS['turn'] = ['M', 1, '-'];
        $GLOBALS['dqState'] = ['0'];
        $GLOBALS['permanentUniqueIDCounter'] = 0;
    }

    public function testResourceGainFromCardEffectKeepsKorshem(): void
    {
        GainResources(1, 2);

        $this->assertSame(1, $GLOBALS['defResources'][0]);
        $this->assertTrue(KorshemTurnConditionMet());
    }

    public function testPitchResourceGainDoesNotKeepKorshem(): void
    {
        GainResources(3, 1, false);

        $this->assertSame(3, $GLOBALS['mainResources'][0]);
        $this->assertFalse(KorshemTurnConditionMet());
    }

    public function testHealthGainFromCardEffectKeepsKorshem(): void
    {
        GainHealth(1, 2, true);

        $this->assertSame(21, $GLOBALS['defHealth']);
        $this->assertTrue(KorshemTurnConditionMet());
    }

    public function testNonCardHealthAdjustmentDoesNotKeepKorshem(): void
    {
        GainHealth(3, 2, true, false, false);

        $this->assertSame(23, $GLOBALS['defHealth']);
        $this->assertFalse(KorshemTurnConditionMet());
    }

    public function testPositivePropertyModifierKeepsKorshemButNonPositiveDoesNot(): void
    {
        PropertyModifierApplied('attack-uid', 'POWER', -1, 'test', 1, 'A');
        PropertyModifierApplied('block-uid', 'DEFENSE', 0, 'test', 2, 'B');
        $this->assertFalse(KorshemTurnConditionMet());

        PropertyModifierApplied('attack-uid', 'POWER', 1, 'test', 1, 'A');
        $this->assertTrue(KorshemTurnConditionMet());
    }

    public function testEndPhaseTriggerIsQueuedOnlyWhenConditionWasNotMet(): void
    {
        $GLOBALS['landmarks'] = ['korshem_crossroad_of_elements', 2, 'PLAY', 0];

        LandmarkBeginEndPhaseTriggers();

        $this->assertSame('PRETRIGGER', $GLOBALS['layers'][0]);
        $this->assertSame(2, $GLOBALS['layers'][1]);
        $this->assertSame('korshem_crossroad_of_elements', $GLOBALS['layers'][2]);
        $this->assertSame('KORSHEM_END_PHASE', $GLOBALS['layers'][4]);

        $GLOBALS['layers'] = [];
        MarkKorshemTurnCondition(1);
        LandmarkBeginEndPhaseTriggers();
        $this->assertSame([], $GLOBALS['layers']);
    }

    public function testDefenseModeAppliesOnlyToTheNextActionCard(): void
    {
        $attack = ['wounded_bull_red', 1, 'HAND', 0, 0, 0, 0, 'attack-uid', 'attack-origin', 'wounded_bull_red', '-', 0];
        $firstBlock = ['wounded_bull_red', 2, 'HAND', 0, 0, 0, 0, 'block-1', 'block-origin-1', 'wounded_bull_red', '-', 0];
        $secondBlock = ['wounded_bull_red', 2, 'HAND', 0, 0, 0, 0, 'block-2', 'block-origin-2', 'wounded_bull_red', '-', 0];
        $GLOBALS['combatChain'] = array_merge($attack, $firstBlock, $secondBlock);
        $GLOBALS['CombatChain'] = new CombatChain();
        $GLOBALS['currentPlayer'] = 2;
        $GLOBALS['currentTurnEffects'] = ['korshem_crossroad_of_elements-2', 2, -1, 1];

        OnBlockEffects(CombatChainPieces(), 'HAND');
        OnBlockEffects(CombatChainPieces() * 2, 'HAND');

        $this->assertSame(1, $GLOBALS['combatChain'][CombatChainPieces() + 6]);
        $this->assertSame(0, $GLOBALS['combatChain'][CombatChainPieces() * 2 + 6]);
        $this->assertSame([], $GLOBALS['currentTurnEffects']);
        $this->assertTrue(KorshemTurnConditionMet());
    }

    public function testKorshemNoLongerExposesManualInstantAbility(): void
    {
        $card = GetClass('korshem_crossroad_of_elements', 1);

        $this->assertInstanceOf(korshem_crossroad_of_elements::class, $card);
        $this->assertSame('', GetAbilityType('korshem_crossroad_of_elements', from: 'PLAY', player: 1));
        $this->assertSame(1, $card->EffectPowerModifier('1'));
        $this->assertTrue($card->CombatEffectActive('2'));
    }
}
