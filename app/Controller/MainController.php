<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;


class MainController
{
    private ProductRepository $productRepository;
    private CategoryRepository $categoryRepository;
    public function __construct(ProductRepository $productRepository, CategoryRepository $categoryRepository)
    {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
    }
    public function main(): array
    {
        session_start();
        if (isset($_SESSION['id'])) {
            $products = $this->productRepository->getAllData();
            $categories = $this->categoryRepository->getAllData();
            return [
                "../views/main.phtml",
                [
                    'products' => $products,
                    'categories' => $categories
                ],
                true
            ];
        }
        header('Location: /signin');
        return [];
    }


}