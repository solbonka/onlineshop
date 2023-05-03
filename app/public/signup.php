<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $connection = new PDO("pgsql:host=db;dbname=dbname", 'dbuser', 'dbpwd');
    $errors_inputs = [];
    $errors_inputs = validate_inputs($lastname, $firstname, $patronymic, $email, $phonenumber, $password);
    if (!$errors_inputs) {
        $lastname = $_POST['lastname'];
        $firstname = $_POST['firstname'];
        $patronymic = $_POST['patronymic'];
        $email = $_POST['email'];
        $phonenumber = $_POST['phonenumber'];
        $password = $_POST['password'];

        $password = password_hash($password, PASSWORD_DEFAULT);

        $sth = $connection->prepare("
             INSERT INTO users (lastname, firstname, patronymic, email, phonenumber, password)
             VALUES (:lastname, :firstname, :patronymic, :email, :phonenumber, :password)");
        $sth->execute(['lastname' => $lastname, 'firstname' => $firstname, 'patronymic' => $patronymic,
            'email' => $email, 'phonenumber' => $phonenumber, 'password' => $password]);
    }

}

function validate_inputs($data_lastname,$data_firstname,$data_patronymic,$data_email,$data_phonenumber,$data_password):array
{
    $errors = [];
    if(validate_lastname($data_lastname) !== null) {
        $errors['lastname'] = validate_lastname($data_lastname);
    }
    if(validate_firstname($data_firstname) !== null) {
        $errors['firstname'] = validate_firstname($data_firstname);
    }
    if(validate_patronymic($data_patronymic) !== null) {
        $errors['patronymic'] = validate_patronymic($data_patronymic);
    }
    if(validate_email($data_email) !== null) {
        $errors['email'] = validate_email($data_email);
    }
    if(validate_phonenumber($data_phonenumber) !== null) {
        $errors['phonenumber'] = validate_phonenumber($data_phonenumber);
    }
    if(validate_password($data_password) !== null) {
        $errors['password'] = validate_password($data_password);
    }
    return $errors;
}
function validate_lastname($data) {
    $err="";
    if(strlen($data)<2 || strlen($data)>30)
        $err = "Длина имени должна быть от 2 до 30 символов";
    if (!preg_match("/^([A-Z][a-z']{1,29})|([А-ЯЁ][а-яё']{1,29})$/u", $data)) {
        $err = "Введите корректную фамилию";
    }
    if(!empty($err))
        return($err);
    else
        return null;
}
function validate_firstname($data) {
    $err="";
    if(strlen($data)<2 || strlen($data)>30)
        $err = "Длина имени должна быть от 2 до 30 символов";
    if (!preg_match("/^([A-Z][a-z']{1,29})|([А-ЯЁ][а-яё']{1,29})$/u", $data)) {
        $err = "Введите корректное отчество";
    }
    if(!empty($err))
        return($err);
    else
        return null;
}
function validate_patronymic($data) {
    $err="";
    if(strlen($data)<2 || strlen($data)>30)
        $err = "Длина имени должна быть от 2 до 30 символов";
    if (!preg_match("/^([A-Z][a-z']{1,29})|([А-ЯЁ][а-яё']{1,29})$/u", $data)) {
        $err = "Введите корректное имя";
    }
    if(!empty($err))
        return($err);
    else
        return null;
}
function validate_email($data) {
    $err="";
    if (!filter_var($data, FILTER_VALIDATE_EMAIL)) {
        $err = "Введите корректный Email";
    }
    if(!empty($err))
        return ($err);
    else
        return null;
}
function validate_phonenumber($data) {
    $err="";
    if (!preg_match("/^(\s*)?(\+)?([- _():=+]?\d[- _():=+]?){10,14}(\s*)?$/", $data)) {
        $err = "Введите корректно номер телефона";
    }
    if(!empty($err))
        return ($err);
    else
        return null;
}
function validate_password($data) {
    $err="";
    if(strlen($data)<3 || strlen($data)>30)
        $err = "Длина пароля должна быть от 3 до 30 символов";

    if(!empty($err))
        return($err);
    else
        return null;
}

?>