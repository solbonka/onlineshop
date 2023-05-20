<?php

namespace App\Repository;

use App\Entity\Cart;
use App\Entity\CartProduct;
use App\Entity\Product;

class CartProductRepository extends Repository
{
    protected string $table = 'cartProducts';
    protected string $entityName = CartProduct::class;

    public function getCartProducts(int $id): ?array
    {
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
               $product->setId($value['product_id']);
               $product->setName($value['name']);
               $product->setPrice($value['price']);
               $product->setWeight($value['weight']);
               $product->setImage($value['image']);
               $product->setCategoryId($value['category_id']);
               $cart = new Cart($value['user_id']);
               $cart->setId($value['cart_id']);
               $cartProduct = new CartProduct;
               $cartProduct->setProduct($product);
               $cartProduct->setCart($cart);
               $cartProduct->setQuantity($value['quantity']);
               $cartProducts[] = $cartProduct;
           }
       }
       return $cartProducts;
    }
    public function createCartProduct(Cart $cart, Product $product):void
    {
        $sth = $this->connection->prepare("
             insert into cartproduct (product_id , cart_id)  
             values (:product_id, :cart_id) on conflict (product_id, cart_id) 
             do update set quantity = cartproduct.quantity+1");
        $sth->execute(['product_id' => $product->getId(),
                       'cart_id' => $cart->getId()]);
    }
    public function getCartProductByProductAndCart(Product $product, Cart $cart): ?CartProduct
    {
        $result = $this->connection->prepare("SELECT * FROM cartproduct WHERE product_id = ? and cart_id = ?");
        $result->execute([$product->getId(), $cart->getId()]);
        $cartProductData = $result->fetch(\PDO::FETCH_ASSOC);
        $cartProduct = null;
        if ($cartProductData){
            $cartProduct = new CartProduct;
            $cartProduct->setProduct($product);
            $cartProduct->setCart($cart);
            $cartProduct->setQuantity($cartProductData['quantity']);
        }
        return $cartProduct;
    }
    public function getQuantityInCart(Cart $cart):?int
    {
        $sth = $this->connection->prepare("
             select quantity 
             from cartproduct 
             where cart_id = ?
             ");
        $sth->execute([$cart->getId()]);
        $quantityData = $sth->fetchAll(\PDO::FETCH_ASSOC);
        $quantityArr = [];
        foreach ($quantityData as $arr){
            foreach ($arr as $value){
                $quantityArr[] = $value;
            }
        }
        return array_sum($quantityArr);
    }

}