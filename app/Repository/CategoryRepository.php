<?php

namespace App\Repository;

use App\Entity\Category;
use PDO;

class CategoryRepository extends Repository
{
    protected string $table = 'categories';
    protected string $entityName = Category::class;
    public function getDataById(int $id): ?Category
    {
        $result = $this->connection->prepare("SELECT * FROM categories where id = ?");
        $result->execute([$id]);
        $categoryData = $result->fetch(PDO::FETCH_ASSOC);
        $category = new $this->entityName();
        if ($categoryData) {
            foreach ($categoryData as $key => $field) {
                $key = (str_replace(' ', '', ucwords(str_replace('_', ' ', $key))));
                $method = "set$key";
                $category->$method($field);
            }
        }
        return $category;
    }
}