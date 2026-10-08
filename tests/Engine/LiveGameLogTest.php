<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../Libraries/LiveGameLog.php';

class LiveGameLogTest extends TestCase
{
    private string $lockFilename;
    private array $handles = [];

    protected function setUp(): void
    {
        $this->lockFilename = sys_get_temp_dir() . '/talishar-log-lock-' . bin2hex(random_bytes(6));
    }

    protected function tearDown(): void
    {
        foreach ($this->handles as $handle) fclose($handle);
        @unlink($this->lockFilename);
    }

    private function openLock()
    {
        $handle = fopen($this->lockFilename, 'c');
        $this->handles[] = $handle;
        return $handle;
    }

    public function testLockIsAcquiredWhenFree(): void
    {
        self::assertTrue(AcquireLiveGameLogLock($this->openLock()));
    }

    public function testLockWaitIsBoundedWhileAnotherHandleHoldsIt(): void
    {
        self::assertTrue(flock($this->openLock(), LOCK_EX));

        $start = hrtime(true);
        $acquired = AcquireLiveGameLogLock($this->openLock());
        $elapsed = (hrtime(true) - $start) / 1e9;

        self::assertFalse($acquired);
        self::assertGreaterThanOrEqual(LIVE_GAME_LOG_LOCK_WAIT * 0.9, $elapsed);
        self::assertLessThan(LIVE_GAME_LOG_LOCK_WAIT + 1, $elapsed);
    }

    public function testLockIsAcquiredOnceTheHolderReleasesIt(): void
    {
        $holder = $this->openLock();
        self::assertTrue(flock($holder, LOCK_EX));
        self::assertTrue(flock($holder, LOCK_UN));

        self::assertTrue(AcquireLiveGameLogLock($this->openLock()));
    }
}
