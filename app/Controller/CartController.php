<?php

namespace App\Controller;

use App\Repository\CartProductRepository;
use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use App\Service\CartService;
use App\ViewRenderer;
use Throwable;

class CartController
{
    private CartProductRepository $cartProductRepository;
    private CartRepository $cartRepository;
    private ProductRepository $productRepository;
    private ViewRenderer $renderer;
    private CartService $cartService;

    public function __construct(CartProductRepository $cartProductRepository,
                                CartRepository $cartRepository,
                                ProductRepository $productRepository,
                                ViewRenderer $renderer,
                                CartService $cartService)
    {
        $this->cartProductRepository = $cartProductRepository;
        $this->cartRepository = $cartRepository;
        $this->productRepository = $productRepository;
        $this->renderer = $renderer;
        $this->cartService = $cartService;
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


    /**
     * @throws Throwable
     */
    public function add(): string
    {
        session_start();
        if (isset($_SESSION['id'])) {
          //  json_decode();
                $productId = $_POST['productId'];
                $errorMessage = $this->validate($productId);
                if (empty($errorMessage)) {
                    $product = $this->productRepository->getProductById($_POST['productId']);
                    $this->cartService->addProduct($_SESSION['id'], $product);
                    $quantityInCart = $this->cartProductRepository->getQuantityInCart($this->cartService->getCart($_SESSION['id']));
                    //header('Location: /main'); die;
                }
        }
        return $quantityInCart ?? '';
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