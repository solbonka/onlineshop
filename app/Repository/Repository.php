<?php

namespace App\Repository;
use PDO;
class Repository
{
    protected PDO $connection;
    protected string $table;
    protected string $entityName;
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }
    public function getAllData(): ?array
    {
        $entity = null;
        $result = $this->connection->query("SELECT * FROM $this->table");
        $data = $result->fetchAll(PDO::FETCH_ASSOC);
        if ($data) {
            foreach ($data as $arr) {
                $obj = new $this->entityName();
                foreach ($arr as $field => $value) {
                    $field = (str_replace(' ', '', ucwords(str_replace('_', ' ', $field))));
                    $method = "set$field";
                    $obj->$method($value);
                }
                $entity[]=$obj;
            }
        }
        return $entity;
    }
}