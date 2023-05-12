<?php

namespace App;

class FileLogger implements LoggerInterface
{
    public function error(string $message, array $context): void
    {
        $log = json_encode($context);
        $log = "$message: \n" . $log;
        file_put_contents(__DIR__ . '/Log/errors.php', $log . PHP_EOL, FILE_APPEND);
    }

    public function debug(string $message, array $context)
    {
        $log = json_encode($context);
        $log = "$message: \n" . $log;
        file_put_contents(__DIR__ . '/Log/debug.php', $log . PHP_EOL, FILE_APPEND);
    }

    public function warning(string $message, array $context)
    {
        $log = json_encode($context);
        $log = "$message: \n" . $log;
        file_put_contents(__DIR__ . '/Log/warning.php', $log . PHP_EOL, FILE_APPEND);
    }
}