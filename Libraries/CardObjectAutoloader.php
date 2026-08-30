<?php

declare(strict_types=1);

function RegisterCardObjectAutoloader(): void
{
    static $registered = false;
    if ($registered) {
        return;
    }

    $classMap = require __DIR__ . '/../GeneratedCode/CardObjectClassMap.php';
    $cardObjectDirectory = dirname(__DIR__) . '/Classes/CardObjects/';

    spl_autoload_register(
        static function (string $className) use ($classMap, $cardObjectDirectory): void {
            $relativePath = $classMap[strtolower($className)] ?? null;
            if ($relativePath !== null) {
                require_once $cardObjectDirectory . $relativePath;
                return;
            }

            if (str_contains($className, '\\') || !function_exists('CardSet')) {
                return;
            }

            $set = CardSet($className);
            if ($set === '') {
                return;
            }

            $fallbackFile = $cardObjectDirectory . $set . 'Cards.php';
            if (is_file($fallbackFile)) {
                require_once $fallbackFile;
            }
        },
        true,
        true
    );

    $registered = true;
}
