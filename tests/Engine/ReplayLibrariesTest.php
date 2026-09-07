<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../Libraries/ReplayLibraries.php';

class ReplayLibrariesTest extends TestCase
{
    private string $gameDirectory;
    private string $replayDirectory;

    protected function setUp(): void
    {
        $root = sys_get_temp_dir() . '/talishar-replay-' . bin2hex(random_bytes(6));
        $this->gameDirectory = $root . '/game';
        $this->replayDirectory = $root . '/replay';
        mkdir($this->gameDirectory, 0700, true);
        mkdir($this->replayDirectory, 0700, true);
    }

    protected function tearDown(): void
    {
        foreach ([$this->gameDirectory, $this->replayDirectory] as $directory) {
            foreach (glob($directory . '/*') ?: [] as $filename) unlink($filename);
            rmdir($directory);
        }
        rmdir(dirname($this->gameDirectory));
    }

    public function testSnapshotPointerMatchesCommandFileAfterPointerHeaderIsAdded(): void
    {
        file_put_contents($this->gameDirectory . '/commandfile.txt', "1 StartTurn 1 0\r\n1 27 card 0 0\r\n");
        file_put_contents($this->gameDirectory . '/gamestate.txt', "state after playing the card\r\n");

        $pointer = SaveReplayStateSnapshot($this->gameDirectory);

        self::assertSame(2, $pointer);
        self::assertSame(
            "state after playing the card\r\n",
            gzdecode((string)file_get_contents(ReplayStateFilename($this->gameDirectory, 2)))
        );
    }

    public function testCommandOnlyReplayIsRecognizedAsLegacy(): void
    {
        file_put_contents($this->replayDirectory . '/commandfile.txt', "1 99 0 0 0\r\n");
        file_put_contents($this->replayDirectory . '/origGamestate.txt', "legacy initial state");

        self::assertNull(ReadReplayFormat($this->replayDirectory));
        self::assertFalse(ValidateReplayStateFiles($this->replayDirectory));
    }

    public function testFormatPackagesAndVerifiesExactStates(): void
    {
        $commands = "1 StartTurn 1 0\r\n1 99 0 0 0\r\n";
        $initialState = "initial state\r\n";
        $resultState = "exact resulting state\r\n";
        file_put_contents($this->gameDirectory . '/commandfile.txt', $commands);
        file_put_contents($this->gameDirectory . '/gamestate.txt', $resultState);
        file_put_contents($this->gameDirectory . '/turn_1-1_Gamestate.txt', "turn one state\r\n");
        self::assertSame(2, SaveReplayStateSnapshot($this->gameDirectory));
        file_put_contents($this->replayDirectory . '/commandfile.txt', $commands);
        file_put_contents($this->replayDirectory . '/origGamestate.txt', $initialState);
        file_put_contents($this->replayDirectory . '/replayStartGamestate.txt', $initialState);

        self::assertTrue(WriteReplayFormat($this->gameDirectory, $this->replayDirectory));
        self::assertTrue(ValidateReplayStateFiles($this->replayDirectory));
        self::assertSame(2, NextReplayStatePointer($this->replayDirectory, 0));
        self::assertNull(NextReplayStatePointer($this->replayDirectory, 2));
        self::assertSame($resultState, ReadReplayStateSnapshot($this->replayDirectory, 2));
        self::assertSame("turn one state\r\n", ReadReplayTurnSnapshot($this->replayDirectory, 1, 1));
        self::assertSame($initialState, ReadReplayInitialStateSnapshot($this->replayDirectory));
    }

    public function testCorruptStateIsRejectedInsteadOfSilentlyPlayed(): void
    {
        $commands = "1 99 0 0 0\r\n";
        file_put_contents($this->gameDirectory . '/commandfile.txt', $commands);
        file_put_contents($this->gameDirectory . '/gamestate.txt', "valid state");
        SaveReplayStateSnapshot($this->gameDirectory);
        file_put_contents($this->replayDirectory . '/commandfile.txt', $commands);
        file_put_contents($this->replayDirectory . '/origGamestate.txt', "initial state");
        self::assertTrue(WriteReplayFormat($this->gameDirectory, $this->replayDirectory));

        file_put_contents(ReplayStateFilename($this->replayDirectory, 1), gzencode("different state"));

        self::assertFalse(ValidateReplayStateFiles($this->replayDirectory));
        self::assertNull(ReadReplayStateSnapshot($this->replayDirectory, 1));
    }
}
