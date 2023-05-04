<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $connection = new PDO("pgsql:host=db;dbname=dbname", 'dbuser', 'dbpwd');
    $errors_inputs = [];
    $errors_inputs = validateInputs($_POST, $connection);
    if (!$errors_inputs) {
        $lastname = $_POST['lastname'];
        $firstname = $_POST['firstname'];
        $patronymic = $_POST['patronymic'];
        $email = $_POST['email'];
        $phoneNumber = $_POST['phoneNumber'];
        $password = $_POST['password'];

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
    $lastnameError = validateLastname($data['lastname']);
    if($lastnameError !== null) {
        $errors['lastname'] = $lastnameError;
    }
    $firstnameError = validateFirstname($data['firstname']);
    if($firstnameError !== null) {
        $errors['firstname'] = $firstnameError;
    }
    $patronymicError = validatePatronymic($data['patronymic']);
    if($patronymicError !== null) {
        $errors['patronymic'] = $patronymicError;
    }
    $emailError = validateEmail($data['email'], $connection);
    if($emailError !== null) {
        $errors['email'] = $emailError;
    }
    $phoneNumberError = validatePhoneNumber($data['phoneNumber']);
    if($phoneNumberError !== null) {
        $errors['phoneNumber'] = $phoneNumberError;
    }
    $passwordError = validatePassword($data['password']);
    if($passwordError !== null) {
        $errors['password'] = $passwordError;
    }
    return $errors;
}
function validateLastname($data) {
    $lastname=$data['lastname'];
    $err='';
    if(strlen($lastname)<2 || strlen($lastname)>30)
        $err = "Длина имени должна быть от 2 до 30 символов";
    if (!preg_match("/^([A-Z][a-z']{1,29})|([А-ЯЁ][а-яё']{1,29})$/u", $lastname)) {
        $err = "Введите корректную фамилию";
    }
    if(!empty($err)) {
        return ($err);
    }
    return null;
}
function validateFirstname($data) {
    $firstname = $data['firstname'];
    $err="";
    if(strlen($firstname)<2 || strlen($firstname)>30)
        $err = "Длина имени должна быть от 2 до 30 символов";
    if (!preg_match("/^([A-Z][a-z']{1,29})|([А-ЯЁ][а-яё']{1,29})$/u", $firstname)) {
        $err = "Введите корректное отчество";
    }
    if(!empty($err)) {
        return ($err);
    }
    return null;
}
function validatePatronymic($data) {
    $patronymic = $data['patronymic'];
    $err="";
    if(strlen($patronymic)<2 || strlen($patronymic)>30)
        $err = "Длина имени должна быть от 2 до 30 символов";
    if (!preg_match("/^([A-Z][a-z']{1,29})|([А-ЯЁ][а-яё']{1,29})$/u", $patronymic)) {
        $err = "Введите корректное имя";
    }
    if(!empty($err)) {
        return ($err);
    }
    return null;
}
function validateEmail($data, PDO $connection) {
    $email = $data['email'];
    $err="";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $err = "Введите корректный Email";
    }
    $result = $connection->prepare("SELECT email FROM users WHERE email = ?");
    $result->execute([$err]);
    $exists = $result->fetch();
    if ($exists) {
        return "Этот Email уже используется";
    }
    if(!empty($err)) {
        return ($err);
    }
    return null;
}
function validatePhoneNumber($data) {
    $phoneNumber = $data['phoneNumber'];
    $err="";
    if (!preg_match("/^(\s*)?(\+)?([- _():=+]?\d[- _():=+]?){10,14}(\s*)?$/", $phoneNumber)) {
        $err = "Введите корректно номер телефона";
    }
    if(!empty($err)) {
        return ($err);
    }
    return null;
}
function validatePassword($data) {
    $password = $data['password'];
    $err="";
    if(strlen($password)<3 || strlen($password)>30)
        $err = "Длина пароля должна быть от 3 до 30 символов";

    if(!empty($err)) {
        return ($err);
    }
    return null;
}

?>