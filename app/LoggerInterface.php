<?php

namespace App;

interface LoggerInterface
{
    public function error(string $message, array $context);
    public function debug(string $message, array $context);
    public function warning(string $message, array $context);
}