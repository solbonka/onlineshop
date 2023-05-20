<?php

namespace App\Repository;

use App\Entity\Cart;
use PDO;

class CartRepository extends Repository
{
    protected string $table = 'carts';
    protected string $entityName = Cart::class;
    public function getCartByUserId(int $id): Cart
    {
        $result = $this->connection->prepare("SELECT * FROM cart WHERE id = ?");
        $result->execute([$id]);
        $cartData = $result->fetch(PDO::FETCH_ASSOC);
        if (!$cartData) {
            $newCart = new Cart($id);
            $this->create($newCart);
            $cartData['user_id'] = $newCart->getUserId();
            $cartData['id'] = $newCart->getId();
        }
        $cart = new Cart($cartData['user_id']);
        $cart->setId($cartData['id']);
        return $cart;
    }
    public function create(Cart $cart): void
    {
        $sth = $this->connection->prepare("
             INSERT INTO cart (user_id)
             VALUES (:user_id)");
        $sth->execute(['user_id' => $cart->getUserId()]);
    }

}