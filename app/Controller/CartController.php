<?php

namespace App\Controller;



use App\Repository\CartProductsRepository;

class CartController
{
    private CartProductsRepository $cartProductsRepository;
    public function __construct(CartProductsRepository $cartProductRepository){
        $this->cartProductsRepository = $cartProductRepository;
    }
    public function cart()
    {
        session_start();
        if (isset($_SESSION['id'])) {

            $cartProducts = $this->cartProductsRepository->getCartProducts($_SESSION['id']);
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
}