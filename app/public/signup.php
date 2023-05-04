<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $connection = new PDO("pgsql:host=db;dbname=dbname", 'dbuser', 'dbpwd');
    $errorInputs = [];
    $errorInputs = validateInputs($_POST, $connection);
    if (!$errorInputs) {
        $lastname = $_POST['lastname'] ?? null;
        $firstname = $_POST['firstname'] ?? null;
        $patronymic = $_POST['patronymic'] ?? null;
        $email = $_POST['email'] ?? null;
        $phoneNumber = $_POST['phoneNumber'] ?? null;
        $password = $_POST['password'] ?? null;

        $password = password_hash($password, PASSWORD_DEFAULT);

        $sth = $connection->prepare("
             INSERT INTO users (lastname, firstname, patronymic, email, phoneNumber, password)
             VALUES (:lastname, :firstname, :patronymic, :email, :phoneNumber, :password)");
        $sth->execute(['lastname' => $lastname, 'firstname' => $firstname, 'patronymic' => $patronymic,
            'email' => $email, 'phoneNumber' => $phoneNumber, 'password' => $password]);
    }
}

function validateInputs(array $data, PDO $connection):array
{
    $errors = [];
    $lastnameError = validateLastname($data);
    if($lastnameError !== null) {
        $errors['lastname'] = $lastnameError;
    }
    $firstnameError = validateFirstname($data);
    if($firstnameError !== null) {
        $errors['firstname'] = $firstnameError;
    }
    $patronymicError = validatePatronymic($data);
    if($patronymicError !== null) {
        $errors['patronymic'] = $patronymicError;
    }
    $emailError = validateEmail($data, $connection);
    if($emailError !== null) {
        $errors['email'] = $emailError;
    }
    $phoneNumberError = validatePhoneNumber($data);
    if($phoneNumberError !== null) {
        $errors['phoneNumber'] = $phoneNumberError;
    }
    $passwordError = validatePassword($data);
    if($passwordError !== null) {
        $errors['password'] = $passwordError;
    }
    return $errors;
}
function validateLastname(array $data): ?string
{
    $lastname=$data['lastname'] ?? null;
    $err=null;

    if(strlen($lastname)<2 || strlen($lastname)>30)
        $err = "Длина имени должна быть от 2 до 30 символов";

    if (!preg_match("/^([A-Z][a-z']{1,29})|([А-ЯЁ][а-яё']{1,29})$/u", $lastname)) {
        $err = "Введите корректную фамилию";
    }

    return $err;
}
function validateFirstname(array $data): ?string
{
    $firstname = $data['firstname'] ?? null;
    $err = null;

    if(strlen($firstname)<2 || strlen($firstname)>30)
        $err = "Длина имени должна быть от 2 до 30 символов";

    if (!preg_match("/^([A-Z][a-z']{1,29})|([А-ЯЁ][а-яё']{1,29})$/u", $firstname)) {
        $err = "Введите корректное отчество";
    }

    return $err;
}
function validatePatronymic(array $data): ?string
{
    $patronymic = $data['patronymic'] ?? null;
    $err = null;

    if(strlen($patronymic)<2 || strlen($patronymic)>30)
        $err = "Длина имени должна быть от 2 до 30 символов";

    if (!preg_match("/^([A-Z][a-z']{1,29})|([А-ЯЁ][а-яё']{1,29})$/u", $patronymic)) {
        $err = "Введите корректное имя";
    }

    return $err;
}
function validateEmail(array $data, PDO $connection): ?string
{
    $email = $data['email'] ?? null;
    $err = null;

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Введите корректный Email";
    }

    $result = $connection->prepare("SELECT email FROM users WHERE email = :email");
    $result->execute(['email' => $email]);
    $exists = $result->fetch(PDO::FETCH_COLUMN);

    if ($exists) {
        $err =  "Этот Email уже используется";
    }

    return $err;
}
function validatePhoneNumber(array $data): ?string
{
    $phoneNumber = $data['phoneNumber'] ?? null;
    $err=null;

    if (!preg_match("/^(\s*)?(\+)?([- _():=+]?\d[- _():=+]?){10,14}(\s*)?$/", $phoneNumber)) {
        $err = "Введите корректно номер телефона";
    }

    return $err;
}
function validatePassword(array $data): ?string
{
    $password = $data['password'] ?? null;
    $err = null;
    if(strlen($password)<3 || strlen($password)>30)
        $err = "Длина пароля должна быть от 3 до 30 символов";

    return $err;
}

?>