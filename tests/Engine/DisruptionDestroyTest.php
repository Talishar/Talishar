<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../MZLogic.php';

if (!function_exists('DestroyAura')) {
    function DestroyAura($player, $index, $uniqueID = '', $location = 'AURAS', $skipTrigger = false, $skipClose = false, $mainPhase = true, $destroyedBy = -1) {
        return $GLOBALS['destroyAuraResult'];
    }
}
if (!function_exists('TypeContains')) {
    function TypeContains($cardID, $type, $player = '', $partial = false, $from = '-', $index = -1) { return false; }
}

class DisruptionDestroyTest extends TestCase
{
    protected function setUp(): void
    {
        $GLOBALS['firstPlayer'] = 1;
        $GLOBALS['mainPlayer'] = 1;
        $GLOBALS['p1TurnCount'] = 1;
        $GLOBALS['p2TurnCount'] = 0;
        $GLOBALS['p1CardsDisrupted'] = [];
        $GLOBALS['p2CardsDisrupted'] = [];
        $GLOBALS['dqState'] = [];
    }

    public function testProtectedAuraDoesNotCountAsDisrupted(): void
    {
        $GLOBALS['destroyAuraResult'] = '';

        MZDestroy(1, 'THEIRAURAS-0', 1);

        $this->assertSame([], $GLOBALS['p1CardsDisrupted']);
    }

    public function testDestroyedAuraCountsAsDisrupted(): void
    {
        $GLOBALS['destroyAuraResult'] = 'fealty';

        MZDestroy(1, 'THEIRAURAS-0', 1);

        $this->assertSame([0 => 1], $GLOBALS['p1CardsDisrupted']);
    }
}
