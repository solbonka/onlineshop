<?php
namespace App\Repository;
use PDO;
class UserRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }
    public function create(string $lastname, string $firstname, string $patronymic, string $email, string $phoneNumber, string $password): void
    {
        $sth = $this->connection->prepare("
             INSERT INTO users (lastname, firstname, patronymic, email, phoneNumber, password)
             VALUES (:lastname, :firstname, :patronymic, :email, :phoneNumber, :password)");
        $sth->execute(['lastname' => $lastname, 'firstname' => $firstname, 'patronymic' => $patronymic,
            'email' => $email, 'phoneNumber' => $phoneNumber, 'password' => $password]);
    }
    public function checkForEmail(string $email)
    {
        $result = $this->connection->prepare("SELECT email FROM users WHERE email = :email");
        $result->execute(['email' => $email]);
        return $result->fetch(PDO::FETCH_COLUMN);
    }
}