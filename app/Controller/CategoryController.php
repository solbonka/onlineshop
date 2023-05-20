<?php

namespace App\Controller;
use App\Repository\CartProductRepository;
use App\Repository\CartRepository;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;


class CategoryController
{
    private ProductRepository $productRepository;
    private CategoryRepository $categoryRepository;
    public function __construct(ProductRepository $productRepository, CategoryRepository $categoryRepository, CartProductRepository $cartProductRepository,
                                CartRepository $cartRepository,)
    {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->cartProductRepository = $cartProductRepository;
        $this->cartRepository = $cartRepository;
    }
    public function category(int $categoryId): array
    {
        session_start();
        if (isset($_SESSION['id'])) {
            $products = $this->productRepository->getAllData();
            $category = $this->categoryRepository->getDataById($categoryId);
            $cart = $this->cartRepository->getCartByUserId($_SESSION['id']);
            $quantityInCart = $this->cartProductRepository->getQuantityInCart($cart);
            return [
                "../views/category.phtml",
                [
                    'products' => $products,
                    'category' => $category,
                    'quantityInCart' => $quantityInCart
                ],
                true
            ];
        }
        header('Location: /signin');
        return [];
    }
}