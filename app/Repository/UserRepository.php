<?php
namespace App\Repository;

use App\Entity\User;
use PDO;

class UserRepository extends Repository
{
    protected string $table = 'users';
    protected string $entityName = User::class;

    public function create(User $user): void
    {
        $sth = $this->connection->prepare("
             INSERT INTO users (lastname, firstname, patronymic, email, phoneNumber, password)
             VALUES (:lastname, :firstname, :patronymic, :email, :phoneNumber, :password)");
        $sth->execute(['lastname' => $user->getLastname(),
                       'firstname' => $user->getFirstname(),
                       'patronymic' => $user->getPatronymic(),
                       'email' => $user->getEmail(),
                       'phoneNumber' => $user->getPhoneNumber(),
                       'password' => $user->getPassword()]);
    }

    public function getDataByEmail(string $email): ?User
    {
        $result = $this->connection->prepare("SELECT * FROM users WHERE email = ?");
        $result->execute([$email]);
        $userData = $result->fetch(PDO::FETCH_ASSOC);
        $user = null;
        if ($userData) {
            $user = new User($userData['lastname'], $userData['firstname'], $userData['patronymic'],
            $userData['email'], $userData['phonenumber'], $userData['password']);
            $user->setId($userData['id']);
        }
        return $user;
    }
}