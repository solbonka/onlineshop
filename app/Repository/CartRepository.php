<?php

namespace App\Repository;

use App\Entity\Cart;
use App\Service\CartService;
use PDO;

class CartRepository extends Repository
{
    protected string $table = 'carts';
    protected string $entityName = Cart::class;
    protected CartService $cartService;

    public function __construct(PDO $connection, CartService $cartService)
    {
        parent::__construct($connection);
        $this->cartService = $cartService;
    }

    public function getCartByUserId(int $id): Cart
    {
        $result = $this->connection->prepare("SELECT * FROM cart WHERE id = ?");
        $result->execute([$id]);
        $cartData = $result->fetch(PDO::FETCH_ASSOC);
        $cart = $this->cartService->getCartByData($cartData, $id);
        $this->create($cart);
        $cart->setId($id);
        return $cart;
    }
    public function create(Cart $cart): void
    {
        $sth = $this->connection->prepare("
             INSERT INTO cart (user_id)
             VALUES (:user_id) on conflict do nothing ");
        $sth->execute(['user_id' => $cart->getUserId()]);
    }

}