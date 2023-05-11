<?php

namespace App;

class Container
{
    private array $services;

    public function set(string $name, callable $callable): void
    {
        $this->services[$name] = $callable;
    }

    public function get(string $name)
    {
        if (!isset($this->services[$name])){
            return new $name();
        }
        return call_user_func($this->services[$name]);
    }
}