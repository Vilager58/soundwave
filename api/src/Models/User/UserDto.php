<?php
namespace App\Models\User;

require_once __DIR__ . '/../../../vendor/autoload.php';

Class UserDto{
    public readonly string $firstName;
    public readonly string $lastName;
    public readonly string $patronymic;
    public readonly string $email;
    public readonly string $phone;
    public readonly string $password;

    public function __construct(string $firstName, string $lastName, string $patronymic, string $email, string $phone, string $password){
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->patronymic = $patronymic;
        $this->email = $email;
        $this->phone = $phone;
        $this->password = $password;
    }
}

