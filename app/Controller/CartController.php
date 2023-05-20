<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Repository\CartProductRepository;
use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use App\ViewRenderer;
use PDO;

class CartController
{
    private CartProductRepository $cartProductRepository;
    private CartRepository $cartRepository;
    private ProductRepository $productRepository;
    private PDO $connection;
    private ViewRenderer $renderer;

    public function __construct(CartProductRepository $cartProductRepository,
                                CartRepository $cartRepository,
                                ProductRepository $productRepository,
                                PDO $connection,
                                ViewRenderer $renderer)
    {
        $this->cartProductRepository = $cartProductRepository;
        $this->cartRepository = $cartRepository;
        $this->productRepository = $productRepository;
        $this->connection = $connection;
        $this->renderer = $renderer;
    }
    public function cart(): string
    {
        session_start();
        if (isset($_SESSION['id'])) {
            $cartProducts = $this->cartProductRepository->getCartProducts($_SESSION['id']);
            $cart = $this->cartRepository->getCartByUserId($_SESSION['id']);
            $quantityInCart = $this->cartProductRepository->getQuantityInCart($cart);
            return $this->renderer->render(
                "../views/cart.phtml",
                [
                    'cartProducts' => $cartProducts,
                    'quantityInCart' => $quantityInCart
                ],
                true
            );
        }
        header('Location: /signin');die;
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