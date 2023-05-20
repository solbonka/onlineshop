<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Repository\CartProductRepository;
use App\Repository\CartRepository;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;


class MainController
{
    private ProductRepository $productRepository;
    private CategoryRepository $categoryRepository;
    private CartProductRepository $cartProductRepository;
    private CartRepository $cartRepository;

    public function __construct(ProductRepository $productRepository,
                                CategoryRepository $categoryRepository,
                                CartProductRepository $cartProductRepository,
                                CartRepository $cartRepository)
    {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->cartProductRepository = $cartProductRepository;
        $this->cartRepository = $cartRepository;
    }
    public function main(): array
    {
        session_start();
        if (isset($_SESSION['id'])) {
            $products = $this->productRepository->getAllData();
            $categories = $this->categoryRepository->getAllData();
            $cart = $this->cartRepository->getCartByUserId($_SESSION['id']);
            $quantityInCart = $this->cartProductRepository->getQuantityInCart($cart);
            return [
                "../views/main.phtml",
                [
                    'products' => $products,
                    'categories' => $categories,
                    'quantityInCart' => $quantityInCart
                ],
                true
            ];
        }
        header('Location: /signin');
        return [];
    }


}