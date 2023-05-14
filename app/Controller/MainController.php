<?php

namespace App\Controller;

use App\Repository\ProductRepository;

class MainController
{
    private ProductRepository $productRepository;
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }
    public function main(): array
    {
        session_start();
        if (isset($_SESSION['id'])) {
            $products = $this->productRepository->getProducts();
            return [
                "../views/main.phtml",
                [
                    'products' => $products
                ],
                true
            ];
        }
        header('Location: /signin');
        return [];
    }
}