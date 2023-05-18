<?php

namespace App\Repository;

use App\Entity\Cart;

class CartRepository extends Repository
{
    protected string $table = 'carts';
    protected string $entityName = Cart::class;
}