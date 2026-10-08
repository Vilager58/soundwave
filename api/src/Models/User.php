<?php

namespace App\Models;

class User{
    private int $id;
    private string $firstName;
    private string $lastName;
    private string $patronymic;
    private string $phone;
    private ?string $birthdate;
    private bool $isActive;
    private string $email;
    private int $roleId;
    private int $bonusBalance;


    public function __construct(int $id,
                                string $firstName,
                                string $lastName,
                                string $patronymic,
                                string $phone,
                                ?string $birthdate,
                                string $isActive,
                                string $email,
                                int $roleId,
                                int $bonusBalance)
    {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->patronymic = $patronymic;
        $this->phone = $phone;
        $this->birthdate = $birthdate;
        $this->isActive = $isActive;
        $this->email = $email;
        $this->roleId = $roleId;
        $this->bonusBalance = $bonusBalance;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    public function getFirstName(): string{
        return $this->firstName;
    }

    public function getLastName(): string{
        return $this->lastName;
    }

    public function getPatronymic(): string{
        return $this->patronymic;
    }

    public function getPhone(): string{
        return $this->phone;
    }

    public function getBirthdate(): string{
        return $this->birthdate;
    }
    public function isActive(): bool{
        return $this->isActive;
    }
    public function getEmail(): string{
        return $this->email;
    }

    /**
     * @return int
     */
    public function getRoleId(): int
    {
        return $this->roleId;
    }
}

class UserData{
    private int $id;
    private string $city;
    private string $adress;
}