<?php

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lastname = $_POST['lastname'];
    if (!preg_match("/^([A-Z][a-z']{1,29})|([А-ЯЁ][а-яё']{1,29})$/u", $lastname)) {
        $errors['lastname'] = "Введите корректную фамилию";
    }
    $firstname = $_POST['firstname'];
    if (!preg_match("/^([A-Z][a-z']{1,29})|([А-ЯЁ][а-яё']{1,29})$/u", $firstname)) {
        $errors['firstname'] = "Введите корректное имя";
    }
    $patronymic = $_POST['patronymic'];
    if (!preg_match("/^([A-Z][a-z']{1,29})|([А-ЯЁ][а-яё']{1,29})$/u", $patronymic)) {
        $errors['patronymic'] = "Введите корректное отчество";
    }
    $email = $_POST['email'];
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Введите корректный Email";
    }
    $phonenumber = $_POST['phonenumber'];
    if (!preg_match("/^(\s*)?(\+)?([- _():=+]?\d[- _():=+]?){10,14}(\s*)?$/", $phonenumber)) {
        $errors['phonenumber'] = "Введите корректно номер телефона";
    }
    $password = $_POST['password'];
    if (strlen($password) < 3) {
        $errors['password'] = "Пароль не может состоять менее чем из 3 символов";
    }
    if (!$errors) {
        $connection = new PDO("pgsql:host=db;dbname=dbname", 'dbuser', 'dbpwd');
        $sth = $connection->prepare("
             INSERT INTO users (lastname, firstname, patronymic, email, phonenumber, password)
             VALUES (:lastname, :firstname, :patronymic, :email, :phonenumber, :password)");
        $sth->execute(['lastname' => $lastname, 'firstname' => $firstname, 'patronymic' => $patronymic,
            'email' => $email, 'phonenumber' => $phonenumber, 'password' => $password]);
    }

}
?>