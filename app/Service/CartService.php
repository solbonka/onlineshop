<?php
namespace App\Service;
use App\Entity\Cart;
use App\Entity\Product;
use App\Repository\CartProductRepository;
use App\Repository\CartRepository;
use PDO;

class CartService
{
    private PDO $connection;
    private CartRepository $cartRepository;
    private CartProductRepository $cartProductRepository;

    public function __construct(CartRepository $cartRepository, PDO $connection, CartProductRepository $cartProductRepository)
    {
        $this->cartRepository = $cartRepository;
        $this->connection = $connection;
        $this->cartProductRepository = $cartProductRepository;
    }
    public function getCart(int $userId):Cart
    {
        $cart = $this->cartRepository->getCartByUserId($userId);
        if (empty($cart))
        {
            $this->cartRepository->create($cart);
            $newCart = new Cart($cart->getUserId());
            $newCart->setId($cart->getId());
            return $newCart;
        }
        return $cart;
    }

    /**
     * @throws \Throwable
     */
    public function addProduct(Cart $cart, Product $product):void
    {
        $this->connection->beginTransaction();
        try{
            $this->cartRepository->create($cart);
            $this->cartProductRepository->createCartProduct($cart, $product);
        }
        catch (\Throwable $exception){
            $this->connection->rollBack();
            throw $exception;
        }
        $this->connection->commit();
    }
}