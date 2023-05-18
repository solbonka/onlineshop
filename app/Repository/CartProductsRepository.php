<?php

namespace App\Repository;

use App\Entity\Cart;
use App\Entity\CartProducts;
use App\Entity\Product;

class CartProductsRepository extends Repository
{
    protected string $table = 'cartProducts';
    protected string $entityName = CartProducts::class;

    public function getCartProducts(int $id){

       $result = $this->connection->prepare("select *
                    from cartproduct c_p
                        inner join products p on p.id = c_p.product_id
                        inner join cart c on c.id = c_p.cart_id
                    where user_id = ?");
       $result->execute([$id]);
       $productsInCart = $result->fetchAll();

       $cartProducts = null;
       if ($productsInCart) {
           foreach ($productsInCart as $value) {
               $product = new Product;
               $product->setId($value['id']);
               $product->setName($value['name']);
               $product->setPrice($value['price']);
               $product->setWeight($value['weight']);
               $product->setImage($value['image']);
               $product->setCategoryId($value['category_id']);
               $cart = new Cart;
               $cart->setId($value['cart_id']);
               $cart->setUserId($value['user_id']);
               $cartProduct = new CartProducts;
               $cartProduct->setProduct($product);
               $cartProduct->setCart($cart);
               $cartProduct->setQuantity($value['quantity']);
               $cartProducts[] = $cartProduct;
           }
       }
       return $cartProducts;
    }
}