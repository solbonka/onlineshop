<?php

namespace App;
use App\Exception\ContainerException;

class Container
{
    private array $services;

    public function __construct(array $data)
    {
        $this->services = $data;
    }
    public function set(string $name, mixed $value): void
    {
        $this->services[$name] = $value;
    }


    /**
     * @throws ContainerException
     */
    public function get(string $name): mixed
    {
        if (!isset($this->services[$name])){
            if (class_exists($name)){
                return new $name();
            }
            throw new ContainerException('class WITH name $name not FOUND');
        }
        if (is_callable($this->services[$name])){
            $callback = $this->services[$name];
            return $callback($this);
        }
        return $this->services[$name];
    }
}