<?php

namespace App\Controller;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;


class CategoryController
{
    private ProductRepository $productRepository;
    private CategoryRepository $categoryRepository;
    public function __construct(ProductRepository $productRepository, CategoryRepository $categoryRepository)
    {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
    }
    public function category(int $categoryId): array
    {
        session_start();
        if (isset($_SESSION['id'])) {
            $products = $this->productRepository->getAllData();
            $category = $this->categoryRepository->getDataById($categoryId);
            return [
                "../views/category.phtml",
                [
                    'products' => $products,
                    'category' => $category
                ],
                true
            ];
        }
        header('Location: /signin');
        return [];
    }
}