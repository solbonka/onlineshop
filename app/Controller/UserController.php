<?php

namespace App\Controller;
use App\Repository\UserRepository;
use App\Entity\User;
use App\ViewRenderer;


class UserController
{
    private UserRepository $userRepository;
    private ViewRenderer $renderer;
    public function __construct(UserRepository $userRepository,
                                ViewRenderer $renderer)
    {
        $this->userRepository = $userRepository;
        $this->renderer = $renderer;
    }
    public function signUp(): string
    {
        $errorInputs = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errorInputs = $this->validateInputsSignUp($_POST);
            if (!$errorInputs) {

                $lastname = $_POST['lastname'] ?? null;
                $firstname = $_POST['firstname'] ?? null;
                $patronymic = $_POST['patronymic'] ?? null;
                $email = $_POST['email'] ?? null;
                $phoneNumber = $_POST['phoneNumber'] ?? null;
                $password = $_POST['password'] ?? null;

                $password = password_hash($password, PASSWORD_DEFAULT);
                $user = new User($firstname, $lastname, $patronymic, $email, $phoneNumber, $password);
                $this->userRepository->create($user);
            }
        }
        return $this->renderer->render(
            "../views/signup.phtml",
            [
                'errorInputs' => $errorInputs
            ],
            false
        );

    }

    private function validateInputsSignUp(array $data):array
    {
        $errors = [];
        $lastnameError = $this->validateLastnameSignUp($data);
        if($lastnameError !== null) {
            $errors['lastname'] = $lastnameError;
        }
        $firstnameError = $this->validateFirstnameSignUp($data);
        if($firstnameError !== null) {
            $errors['firstname'] = $firstnameError;
        }
        $patronymicError = $this->validatePatronymicSignUp($data);
        if($patronymicError !== null) {
            $errors['patronymic'] = $patronymicError;
        }
        $emailError = $this->validateEmailSignUp($data);
        if($emailError !== null) {
            $errors['email'] = $emailError;
        }
        $phoneNumberError = $this->validatePhoneNumberSignUp($data);
        if($phoneNumberError !== null) {
            $errors['phoneNumber'] = $phoneNumberError;
        }
        $passwordError = $this->validatePasswordSignUp($data);
        if($passwordError !== null) {
            $errors['password'] = $passwordError;
        }
        return $errors;
    }
    private function validateLastnameSignUp(array $data): ?string
    {
        $lastname=$data['lastname'] ?? null;
        $err=null;

        if(strlen($lastname)<2 || strlen($lastname)>30) {
            $err = "Длина имени должна быть от 2 до 30 символов";
        }

        if (!preg_match("/^([A-Z][a-z']{1,29})|([А-ЯЁ][а-яё']{1,29})$/u", $lastname)) {
            $err = "Введите корректную фамилию";
        }

        return $err;
    }
    private function validateFirstnameSignUp(array $data): ?string
    {
        $firstname = $data['firstname'] ?? null;
        $err = null;

        if(strlen($firstname)<2 || strlen($firstname)>30) {
            $err = "Длина имени должна быть от 2 до 30 символов";
        }

        if (!preg_match("/^([A-Z][a-z']{1,29})|([А-ЯЁ][а-яё']{1,29})$/u", $firstname)) {
            $err = "Введите корректное отчество";
        }

        return $err;
    }
    private function validatePatronymicSignUp(array $data): ?string
    {
        $patronymic = $data['patronymic'] ?? null;
        $err = null;

        if(strlen($patronymic)<2 || strlen($patronymic)>30) {
            $err = "Длина имени должна быть от 2 до 30 символов";
        }

        if (!preg_match("/^([A-Z][a-z']{1,29})|([А-ЯЁ][а-яё']{1,29})$/u", $patronymic)) {
            $err = "Введите корректное имя";
        }

        return $err;
    }
    private function validateEmailSignUp(array $data): ?string
    {
        $email = $data['email'] ?? null;
        $err = null;

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Введите корректный Email";
        }

        $exists = $this->userRepository->getDataByEmail($email);

        if ($exists) {
            $err =  "Этот Email уже используется";
        }

        return $err;
    }
    private function validatePhoneNumberSignUp(array $data): ?string
    {
        $phoneNumber = $data['phoneNumber'] ?? null;
        $err=null;

        if (!preg_match("/^(\s*)?(\+)?([- _():=+]?\d[- _():=+]?){10,14}(\s*)?$/", $phoneNumber)) {
            $err = "Введите корректно номер телефона";
        }

        return $err;
    }
    private function validatePasswordSignUp(array $data): ?string
    {
        $password = $data['password'] ?? null;
        $err = null;
        if(strlen($password)<3 || strlen($password)>30) {
            $err = "Длина пароля должна быть от 3 до 30 символов";
        }

        return $err;
    }
    public function signIn(): string
    {
        session_start();

        $errorInputs = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errorInputs = $this->validateInputsSignIn($_POST);
            if (!$errorInputs) {
                $email = $_POST['email'];
                $password = $_POST['password'];
                $user = $this->userRepository->getDataByEmail($email);

                if ($user && password_verify($password, $user->getPassword())) {
                    $_SESSION['id'] = $user->getId();
                    header("Location: /main");
                }
                else {
                    $errorInputs['email'] = 'Неверный Email или пароль';
                }
            }
        }


        return $this->renderer->render(
            "../views/signin.phtml",
            [
                'errorInputs' => $errorInputs
            ],
            false
        );
    }
    public function validateInputsSignIn(array $data):array
    {
        $errors = [];
        $emailError = $this->validateEmailSignIn($data);
        if($emailError !== null) {
            $errors['email'] = $emailError;
        }
        $passwordError = $this->validatePasswordSignIn($data);
        if($passwordError !== null) {
            $errors['password'] = $passwordError;
        }
        return $errors;
    }
    private function validateEmailSignIn(array $data): ?string
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
    private function validatePasswordSignIn(array $data): ?string
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
}