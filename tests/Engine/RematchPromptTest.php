<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;

#[RunTestsInSeparateProcesses]
#[PreserveGlobalState(false)]
class RematchPromptTest extends TestCase
{
    protected function setUp(): void
    {
        require_once ROOT_PATH . '/Libraries/CoreLibraries.php';
        require_once ROOT_PATH . '/GameLogic.php';
        require_once ROOT_PATH . '/Libraries/RematchLibraries.php';

        $hero = array_fill(0, CharacterPieces(), 0);
        $hero[0] = 'kayo_berserker_runt';
        $hero[1] = 2;
        $trackers = array_fill(0, CharacterPieces(), 0);
        $trackers[0] = 'beaten_trackers';
        $trackers[1] = 2;
        $trackers[9] = 1;

        $GLOBALS['mainPlayerGamestateStillBuilt'] = true;
        $GLOBALS['mainPlayer'] = 1;
        $GLOBALS['defPlayer'] = 2;
        $GLOBALS['currentPlayer'] = 1;
        $GLOBALS['mainCharacter'] = $hero;
        $GLOBALS['defCharacter'] = array_merge($hero, $trackers);
        $GLOBALS['turn'] = ['OVER', 1, '-'];
        $GLOBALS['decisionQueue'] = [];
        $GLOBALS['dqState'] = array_fill(0, 10, '0');
        $GLOBALS['dqVars'] = [];
        $GLOBALS['layers'] = [];
        $GLOBALS['EffectContext'] = 'beaten_trackers';
    }

    public function testStaleEffectContextOffersSnoozeOnPlainYesNo(): void
    {
        AddDecisionQueue('YESNO', 2, 'if you want a <b>Rematch</b>?');
        ProcessDecisionQueue();

        $this->assertSame('YESNO', $GLOBALS['turn'][0]);
        $this->assertSame(CharacterPieces(), PromptSnoozeSourceIndex(2));
    }

    public function testRematchPromptDoesNotInheritTheLastCardContext(): void
    {
        QueueRematchPrompt(2, 'if you want a <b>Rematch</b>?');
        ProcessDecisionQueue();

        $this->assertSame('YESNO', $GLOBALS['turn'][0]);
        $this->assertSame(2, (int)$GLOBALS['turn'][1]);
        $this->assertSame('-', $GLOBALS['EffectContext']);
        $this->assertSame(-1, PromptSnoozeSourceIndex(2));
    }

    public function testEveryRematchOfferUsesTheRematchPrompt(): void
    {
        $source = (string)file_get_contents(ROOT_PATH . '/Libraries/NetworkingLibraries.php');

        $this->assertSame(3, substr_count($source, 'QueueRematchPrompt('));
        $this->assertDoesNotMatchRegularExpression('/AddDecisionQueue\("YESNO",[^;]*[Rr]ematch/', $source);
    }
}
