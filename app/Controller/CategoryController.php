<?php

namespace App\Controller;
use App\Repository\CartProductRepository;
use App\Repository\CartRepository;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\ViewRenderer;


class CategoryController
{
    private ProductRepository $productRepository;
    private CategoryRepository $categoryRepository;
    private ViewRenderer $renderer;
    private CartProductRepository $cartProductRepository;
    private CartRepository $cartRepository;

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
    public function category(int $categoryId): string
    {
        session_start();
        if (isset($_SESSION['id'])) {
            $products = $this->productRepository->getAllData();
            $category = $this->categoryRepository->getDataById($categoryId);
            $cart = $this->cartRepository->getCartByUserId($_SESSION['id']);
            $quantityInCart = $this->cartProductRepository->getQuantityInCart($cart);
            return $this->renderer->render(
                "../views/category.phtml",
                [
                    'products' => $products,
                    'category' => $category,
                    'quantityInCart' => $quantityInCart
                ],
                true
            );
        }
        header('Location: /signin');die;
    }
}