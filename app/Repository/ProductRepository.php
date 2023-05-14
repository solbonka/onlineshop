<?php

namespace App\Repository;
use PDO;
use App\Entity\Product;
class ProductRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }
    public function createProduct(Product $product): void
    {
        $sth = $this->connection->prepare("
             INSERT INTO products (name, price, weight, image)
             VALUES (:name, :price, :weigt, :image)");
        $sth->execute(['name' => $product->getName(),
            'price' => $product->getPrice(),
            'weight' => $product->getWeight(),
            'category' => $product->getImage()]);
    }
    public function getProducts(): ?array
    {
        $products = null;
        $result = $this->connection->query("SELECT * FROM products");
        $productsData = $result->fetchAll(PDO::FETCH_ASSOC);
        if ($productsData){
            foreach ($productsData as $value){
                $product = new Product($value['name'],$value['price'],$value['weight'],$value['image']);
                $product->setId($value['id']);
                $products[] = $product;
            }
        }
        return $products;
    }

}