<?php

use PHPUnit\Framework\TestCase;

require_once ROOT_PATH . '/Libraries/GameAuthLibraries.php';

class GameAuthLibrariesTest extends TestCase
{
    private string $gamesDirectory;

    protected function setUp(): void
    {
        $this->gamesDirectory = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'talishar-auth-' . bin2hex(random_bytes(8));
        mkdir($this->gamesDirectory . DIRECTORY_SEPARATOR . '123', 0777, true);
    }

    protected function tearDown(): void
    {
        $gameFile = $this->gamesDirectory . DIRECTORY_SEPARATOR . '123' . DIRECTORY_SEPARATOR . 'GameFile.txt';
        if (is_file($gameFile)) unlink($gameFile);
        $gameDirectory = $this->gamesDirectory . DIRECTORY_SEPARATOR . '123';
        if (is_dir($gameDirectory)) rmdir($gameDirectory);
        if (is_dir($this->gamesDirectory)) rmdir($this->gamesDirectory);
    }

    public function testPresentedCookieMustMatchTheRequestedSeat(): void
    {
        $this->assertSame(
            [1, 'p1-secret'],
            ResolvePresentedGameAuth(1, 'p1-secret', 'p1-secret', 'p2-secret')
        );
        $this->assertNull(ResolvePresentedGameAuth(2, 'p1-secret', 'p1-secret', 'p2-secret'));
        $this->assertSame(
            [2, 'p2-secret'],
            ResolvePresentedGameAuth(0, 'p2-secret', 'p1-secret', 'p2-secret')
        );
        $this->assertNull(ResolvePresentedGameAuth(1, '', 'p1-secret', 'p2-secret'));
    }

    public function testResolvesOnlyTheAccountsSavedGameSeatAndKey(): void
    {
        $this->assertSame(
            [2, 'p2-secret'],
            ResolveStoredAccountGameAuth('123', 2, 123, 2, 'p2-secret', 'p1-secret', 'p2-secret')
        );
        $this->assertSame(
            [1, 'p1-secret'],
            ResolveStoredAccountGameAuth('123', 0, 123, 1, 'p1-secret', 'p1-secret', 'p2-secret')
        );
    }

    /** @dataProvider mismatchedAccountFallbackProvider */
    public function testRejectsStaleOrMismatchedAccountFallback(array $arguments): void
    {
        $this->assertNull(ResolveStoredAccountGameAuth(...$arguments));
    }

    public static function mismatchedAccountFallbackProvider(): array
    {
        return [
            'different game' => [['123', 1, 124, 1, 'p1-secret', 'p1-secret', 'p2-secret']],
            'different requested seat' => [['123', 2, 123, 1, 'p1-secret', 'p1-secret', 'p2-secret']],
            'invalid account seat' => [['123', 0, 123, 3, 'p1-secret', 'p1-secret', 'p2-secret']],
            'stale account key' => [['123', 1, 123, 1, 'old-key', 'p1-secret', 'p2-secret']],
            'empty account key' => [['123', 1, 123, 1, '', 'p1-secret', 'p2-secret']],
        ];
    }

    public function testReadsOnlySeatAuthenticationHeader(): void
    {
        $lines = ['p1 data', 'p2 data', '5', 'cc', 'public', '1', '1', 'p1-secret', 'p2-secret', 'Alice', 'Bob', 'remaining data'];
        file_put_contents(
            $this->gamesDirectory . DIRECTORY_SEPARATOR . '123' . DIRECTORY_SEPARATOR . 'GameFile.txt',
            implode("\r\n", $lines)
        );

        $this->assertSame(
            ['p1-secret', 'p2-secret'],
            ReadGameFileSeatAuth('123', $this->gamesDirectory)
        );
    }

    public function testRejectsInvalidGameNamesAndIncompleteFiles(): void
    {
        $this->assertNull(ReadGameFileSeatAuth('../123', $this->gamesDirectory));
        $this->assertNull(ReadGameFileSeatAuth('0', $this->gamesDirectory));

        file_put_contents(
            $this->gamesDirectory . DIRECTORY_SEPARATOR . '123' . DIRECTORY_SEPARATOR . 'GameFile.txt',
            "too\nshort\n"
        );
        $this->assertNull(ReadGameFileSeatAuth('123', $this->gamesDirectory));
    }
}
