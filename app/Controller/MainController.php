<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Repository\CartProductRepository;
use App\Repository\CartRepository;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\ViewRenderer;


class MainController
{
    private ProductRepository $productRepository;
    private CategoryRepository $categoryRepository;
    private CartProductRepository $cartProductRepository;
    private CartRepository $cartRepository;
    private ViewRenderer $renderer;

    public function __construct(ProductRepository $productRepository,
                                CategoryRepository $categoryRepository,
                                CartProductRepository $cartProductRepository,
                                CartRepository $cartRepository,
                                ViewRenderer $renderer)
    {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->cartProductRepository = $cartProductRepository;
        $this->cartRepository = $cartRepository;
        $this->renderer = $renderer;
    }
    public function main(): string
    {
        session_start();
        if (isset($_SESSION['id'])) {
            $products = $this->productRepository->getAllData();
            $categories = $this->categoryRepository->getAllData();
            $cart = $this->cartRepository->getCartByUserId($_SESSION['id']);
            $quantityInCart = $this->cartProductRepository->getQuantityInCart($cart);
            return $this->renderer->render("../views/main.phtml",
                [
                'products' => $products,
                'categories' => $categories,
                'quantityInCart' => $quantityInCart
            ], true);

        }
        header('Location: /signin'); die;
    }
}