<?php

namespace App\Models;
require_once __DIR__ . '/../../vendor/autoload.php';
use App\Models\User\UserDto;

class User{
    private ?int $id;
    private string $firstName;
    private string $lastName;
    private string $patronymic;
    private string $phone;
    private ?string $birthdate;
    private bool $isActive;
    private string $email;
    private int $roleId;
    private int $bonusBalance;
    private string $passwordHash;
    private ?string $instrument;

    private string $city;
    private string $address = '';
    public function __construct(int $id,
                                string $firstName,
                                string $lastName,
                                string $patronymic,
                                string $phone,
                                ?string $birthdate,
                                string $isActive,
                                string $email,
                                int $roleId,
                                int $bonusBalance,
                                string $passwordHash,
                                string $instrument = '',
                                string $city = '')
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
        $this->passwordHash = $passwordHash;
        $this->instrument = $instrument;
        $this->city = $city;
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

    public function getPasswordHash(): string{
        return $this->passwordHash;
    }

    public function getRoleId(): int{
        return $this->roleId;
    }

    public function getBonusBalance(): int{
        return $this->bonusBalance;
    }

    public function getInstrument(): string{
        return $this->instrument;
    }

    public function getCity(): string{
        return $this->city;
    }

    public static function createFromDto(UserDto $dto): self
    {
        $name = filter_var($dto->firstName, FILTER_SANITIZE_SPECIAL_CHARS);
        $lastname = filter_var($dto->lastName, FILTER_SANITIZE_SPECIAL_CHARS);
        $patronymic = filter_var($dto->patronymic, FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_var($dto->email, FILTER_SANITIZE_SPECIAL_CHARS);
        $phone = filter_var($dto->phone, FILTER_SANITIZE_SPECIAL_CHARS);
        $hashedPassword = password_hash($dto->password, PASSWORD_DEFAULT);

        return new self(
            id: -1,
            firstName: $name,
            lastName: $lastname,
            patronymic: $patronymic,
            phone: $phone,
            birthdate: null,
            isActive: true,
            email: $email,
            roleId: 6,
            bonusBalance: 0,
            passwordHash: $hashedPassword,
        );
    }

    public function getAddress(): string
    {
        return $this->address ?? '';
    }

    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function setPatronymic(string $patronymic): void
    {
        $this->patronymic = $patronymic;
    }

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    public function setBirthdate(?string $birthdate): void
    {
        $this->birthdate = $birthdate;
    }

    public function setInstrument(?string $instrument): void
    {
        $this->instrument = $instrument;
    }

    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'role_id' => $this->roleId,
            'firstname' => $this->firstName,
            'lastname' => $this->lastName,
            'patronymic' => $this->patronymic,
            'email' => $this->email,
            'phone' => $this->phone,
            'birthdate' => $this->birthdate,
            'bonus_balance' => $this->bonusBalance,
            'instrument' => $this->instrument,
            'city' => $this->city,
            'address' => $this->address,
        ];
    }
}

