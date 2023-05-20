<?php
namespace App\Service;
use App\Entity\Cart;

class CartService
{
    public function getCartByData(array $cartData, int $userId):Cart
    {
        if ($cartData) {
            return new Cart($cartData['user_id']);
        }
        return new Cart($userId);
    }
}