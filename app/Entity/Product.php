<?php

namespace App\Entity;

class Product
{
    private int $id;
    private string $name;
    private float $price;
    private int $weight;
    private string $image;
    public function __construct(
        string $name,
        float $price,
        int $weight,
        string $image
    ){
        $this->name = $name;
        $this->price = $price;
        $this->weight = $weight;
        $this->image = $image;
    }
    public function setId(int $id): void
    {
        $this->id = $id;
    }
    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
    public function getPrice(): float
    {
        return $this->price;
    }
    public function getWeight(): int
    {
        return $this->weight;
    }
    public function getImage(): string
    {
        return $this->image;
    }
}