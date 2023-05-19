<?php

namespace App\Repository;

use App\Entity\Product;
use PDO;

class ProductRepository extends Repository
{
    protected string $table = 'products';
    protected string $entityName = Product::class;

   //public function createProduct(Product $product): void
   //{
   //    $sth = $this->connection->prepare("
   //         INSERT INTO products (name, price, weight, image)
   //         VALUES (:name, :price, :weigt, :image)");
   //    $sth->execute(['name' => $product->getName(),
   //        'price' => $product->getPrice(),
   //        'weight' => $product->getWeight(),
   //        'category' => $product->getImage()]);
   //}
    public function getProductsByCart(int $id)
        {
            $result = $this->connection->prepare("select *
                        from products p
                         inner join cartproduct c_p on c_p.product_id = p.id
                         inner join cart c on c.id = c_p.cart_id
                        where user_id = ?");
            $result->execute([$id]);
            $productsInCart = $result->fetchAll();
            $products = null;
            if ($productsInCart) {
                foreach ($productsInCart as $value) {
                    $product = new Product;
                    $product->setId($value['id']);
                    $product->setName($value['name']);
                    $product->setPrice($value['price']);
                    $product->setWeight($value['weight']);
                    $product->setImage($value['image']);
                    $product->setCategoryId($value['category_id']);
                    $products[] = $product;
                }
            }
            return $products;
        }
   public function getProductById(int $id)
   {
       $result = $this->connection->prepare("SELECT * FROM products WHERE id = ?");
       $result->execute([$id]);
       $productData = $result->fetch(PDO::FETCH_ASSOC);
       $product = null;
       if ($productData) {
           $product = new Product;
           $product->setId($productData['id']);
           $product->setName($productData['name']);
           $product->setPrice($productData['price']);
           $product->setWeight($productData['weight']);
           $product->setImage($productData['image']);
           $product->setCategoryId($productData['category_id']);
       }
       return $product;
    }

}