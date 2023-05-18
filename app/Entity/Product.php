<?php

namespace App\Entity;

class Product
{
    private int $id;
    private string $name;
    private float $price;
    private int $weight;
    private string $image;
    private int $categoryId;

    public function setId(int $id): void
    {
        $this->id = $id;
    }
    public function setName(string $name): void
    {
        $this->name = $name;
    }
    public function setPrice(float $price): void
    {
        $this->price = $price;
    }
    public function setWeight(int $weight): void
    {
        $this->weight = $weight;
    }
    public function setImage(string $image): void
    {
        $this->image = $image;
    }
    public function setCategoryId(int $categoryId): void
    {
        $this->categoryId = $categoryId;
    }
    public function getId(): int
    {
        return $this->id;
    }

    public function getCategoryId(): int
    {
        return $this->categoryId;
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