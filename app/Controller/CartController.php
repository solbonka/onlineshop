<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Repository\CartProductRepository;
use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use PDO;

class CartController
{
    private CartProductRepository $cartProductRepository;
    private CartRepository $cartRepository;
    private ProductRepository $productRepository;
    private PDO $connection;

    public function __construct(CartProductRepository $cartProductRepository,
                                CartRepository $cartRepository,
                                ProductRepository $productRepository,
                                PDO $connection)
    {
        $this->cartProductRepository = $cartProductRepository;
        $this->cartRepository = $cartRepository;
        $this->productRepository = $productRepository;
        $this->connection = $connection;
    }
    public function cart(): array
    {
        session_start();
        if (isset($_SESSION['id'])) {
            $cartProducts = $this->cartProductRepository->getCartProducts($_SESSION['id']);
            return [
                "../views/cart.phtml",
                [
                    'cartProducts' => $cartProducts
                ],
                true
            ];
        }
        header('Location: /signin');
        return [];
    }
    public function add(): void
    {
        session_start();
        if (isset($_SESSION['id'])) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $productId = $_POST['productId'];
                $errorMessage = $this->validate($productId);
                if (empty($errorMessage)) {
                    $product = $this->productRepository->getProductById($_POST['productId']);
                    $cart = $this->cartRepository->getCartByUserId($_SESSION['id']);
                    $this->connection->beginTransaction();
                    try{
                        if (empty ($cart)) {
                            $cart = new Cart($_SESSION['id']);
                            $this->cartRepository->create($cart);
                        }
                        $this->cartProductRepository->createCartProduct($cart, $product);
                    }
                    catch (\Throwable $exception){
                        $this->connection->rollBack();
                    }
                    $this->connection->commit();
                    header('Location: /main'); die;
                }
            }
        }
    }
    private function validate(int $productId): array
    {
        $errorMessage = [];
        if (empty($productId)) {
            $errorMessage['productId'] = 'Invalid productId';
        }
        return $errorMessage;
    }
}