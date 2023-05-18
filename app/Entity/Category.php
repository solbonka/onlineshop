<?php

namespace App\Entity;


class Category
{
    private int $id;
    private string $name;
    private string $icon;



    public function setId(int $id): void
    {
        $this->id = $id;
    }
    public function setName(string $name): void
    {
        $this->name = $name;
    }
    public function setIcon(string $icon): void
    {
        $this->icon = $icon;
    }
    public function getId(): int
    {
        return $this->id;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function getIcon(): string
    {
        return $this->icon;
    }

}