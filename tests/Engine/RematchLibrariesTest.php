<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../Libraries/RematchLibraries.php';

class RematchLibrariesTest extends TestCase
{
    public function testRematchGetsANewGameGuid(): void
    {
        $previousGameGUID = '4d48e24c-4ea0-4a85-800d-f24ebef95834';
        $rematchGameGUID = ResetGameGUIDForRematch();

        self::assertMatchesRegularExpression(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i',
            $rematchGameGUID
        );
        self::assertNotSame($previousGameGUID, $rematchGameGUID);
    }

    public function testRematchGuidIsResetBeforeTheGameFileIsWritten(): void
    {
        $source = (string)file_get_contents(__DIR__ . '/../../Libraries/GameFinalization.php');
        $resetPosition = strpos($source, '$gameGUID = ResetGameGUIDForRematch();');
        $writePosition = strpos($source, 'WriteGameFile();', $resetPosition ?: 0);

        self::assertNotFalse($resetPosition, 'A rematch must receive a new game GUID.');
        self::assertNotFalse($writePosition, 'The rematch game file must be written after resetting its GUID.');
        self::assertLessThan($writePosition, $resetPosition);
    }
}
