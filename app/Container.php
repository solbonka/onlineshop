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
        return $this->services[$name]($this);
    }
}