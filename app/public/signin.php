<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    header("Location: /main");
    $connection = new PDO("pgsql:host=db;dbname=dbname", 'dbuser', 'dbpwd');
    $errorInputs = [];
    $errorInputs = validateInputs($_POST, $connection);
    echo 'тут есть кто?';
    print_r($errorInputs);
    if (!$errorInputs){
        $email = $_POST['email'] ?? null;
        $password = $_POST['password'] ?? null;
        $password = password_hash($password, PASSWORD_DEFAULT);
        header("Location: /main");
    }
}

function validateInputs(array $data, PDO $connection):array
{
    $errors = [];
    $emailError = validateEmail($data, $connection);
    if($emailError !== null) {
        $errors['email'] = $emailError;
    }
    $passwordError = validatePassword($data, $connection);
    if($passwordError !== null) {
        $errors['password'] = $passwordError;
    }
    return $errors;
}
function validateEmail(array $data, PDO $connection): ?string
{
    $email = $data['email'] ?? null;

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Введите корректный Email";
    }

    $result = $connection->prepare("SELECT email FROM users WHERE email = :email");
    $result->execute(['email' => $email]);
    $exists = $result->fetch();

    if (!$exists) {
        return "Нет пользователя с таким Email";
    }

    return null;
}
function validatePassword(array $data, PDO $connection): ?string
{
    $password = $data['password'] ?? null;

    if(strlen($password)<3 || strlen($password)>30) {
        return "Длина пароля должна быть от 3 до 30 символов";
    }

    $password = password_hash($password, PASSWORD_DEFAULT);
    $result = $connection->prepare("SELECT password FROM users WHERE password = :password");
    $result->execute(['password' => $password]);
    $exists = $result->fetch(PDO::FETCH_COLUMN);

    if (!$exists) {
        return "Неверный пароль";
    }

    return null;
}