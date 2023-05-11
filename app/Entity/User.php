<?php
namespace App\Entity;
class User
{
    private int $id;
    private string $firstname;
    private string $lastname;
    private string $patronymic;
    private string $email;
    private string $phoneNumber;
    private string $password;
    public function __construct(
    string $lastname,
    string $firstname,
    string $patronymic,
    string $email,
    string $phoneNumber,
    string $password
    ){
        $this->lastname = $lastname;
        $this->firstname = $firstname;
        $this->patronymic = $patronymic;
        $this->email = $email;
        $this->phoneNumber = $phoneNumber;
        $this->password = $password;
    }
    public function setId(int $id){
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFirstname(): string
    {
        return $this->firstname;
    }

    /**
     * @return string
     */
    public function getLastname(): string
    {
        return $this->lastname;
    }

    /**
     * @return string
     */
    public function getPatronymic(): string
    {
        return $this->patronymic;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }
}