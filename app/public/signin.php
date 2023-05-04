<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST'){

    $connection = new PDO("pgsql:host=db;dbname=dbname", 'dbuser', 'dbpwd');
    $stmt = $connection->prepare("SELECT email FROM users WHERE email = ?");
    $errorInputs = [];
    $errorInputs = validateInputs($_POST, $connection);
    if (!$errorInputs){
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

    if(empty($email)){
        return "Введите Email";
    }

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
    $email = $data['email'] ?? null;

    if(empty($password)){
        return "Введите пароль";
    }

    if(strlen($password)<3 || strlen($password)>30) {
        return "Длина пароля должна быть от 3 до 30 символов";
    }

    $result = $connection->prepare("SELECT password FROM users WHERE email = :password");
    $result->execute(['password' => $email]);
    $hash = $result->fetch();

    if (!$hash) {
        return "Неверный пароль";
    }

    if (!password_verify($password, $hash["password"])) {
        return "Неверный пароль";
    }

    return null;
}