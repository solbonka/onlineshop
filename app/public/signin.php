<?php
session_start();
$errorInputs = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errorInputs = validateInputs($_POST);
    if (!$errorInputs) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $connection = new PDO("pgsql:host=db;dbname=dbname", 'dbuser', 'dbpwd');
        $stmt = $connection->prepare('SELECT * FROM users WHERE email=?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['id'] = $user['id'];
            header("Location: /main");
        }
        else {
            $errorInputs['email'] = 'Неверный Email или пароль';
        }
    }
}
function validateInputs(array $data):array
{
    $errors = [];
    $emailError = validateEmail($data);
    if($emailError !== null) {
        $errors['email'] = $emailError;
    }
    $passwordError = validatePassword($data);
    if($passwordError !== null) {
        $errors['password'] = $passwordError;
    }
    return $errors;
}
function validateEmail(array $data): ?string
{
    $email = $data['email'] ?? null;

    if(empty($email)){
        return "Введите Email";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Введите корректный Email";
    }

    return null;
}
function validatePassword(array $data): ?string
{
    $password = $data['password'] ?? null;

    if(empty($password)){
        return "Введите пароль";
    }

    if(strlen($password)<3 || strlen($password)>30) {
        return "Длина пароля должна быть от 3 до 30 символов";
    }

    return null;
}

return [
    "./forms/signin.phtml",
    [
        'errors' => $errorInputs
    ]
];