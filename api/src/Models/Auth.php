<?php

namespace App\Models;
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Models\User\UserDto;
use App\Models\User\UserRepository;
use Exception;
use App\Models\User;

class Auth{

    private UserRepository $userRepository;
    private array $permissions;
    private bool $isLoggedIn;

    private User $user;

    /**
     * @throws \Throwable
     */
    public function __construct(UserRepository $userRepository){
        $this->userRepository = $userRepository;
        if(isset($_SESSION['uid'])){
            $this->isLoggedIn = true;
            $this->permissions = $this->userRepository->getPermissions($_SESSION['uid']);
            $this->user = $this->userRepository->getUserById($_SESSION['uid']);
        } else {
            $this->isLoggedIn = false;
            $this->permissions = [];
        }
    }

    /**
     * @throws \Throwable
     */
    public function register(UserDto $userDto): bool
    {
        if($this->userRepository->checkUserByEmailAndPhone($userDto->email, $userDto->phone)){
            throw new Exception("Пользователь с таким email или телефоном уже существует");
        }

        $userData = User::createFromDto($userDto);
        $this->userRepository->createUser($userData);
        $user = $this->userRepository->getUserByEmail($userData->getEmail());

        session_regenerate_id(true);
        $_SESSION['uid'] = $user->getId();
        $_SESSION['role'] = $user->getRoleId();
        $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
        $_SESSION['last_activity'] = time();
        return true;
    }

    public function login($email, $password): bool{
            $user = $this->userRepository->getUserByEmail($email);
            if(!password_verify($password, $user->getPasswordHash())) {
                throw new Exception("Неверный пароль");
            };
            session_regenerate_id(true);
            $_SESSION['uid'] = $user->getId();
            $_SESSION['role'] = $user->getRoleId();
            $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
            $_SESSION['last_activity'] = time();
            return true;
    }

    public function logout(){
        $_SESSION = array();
        session_destroy();
        return false;
    }

    public function isLoggedIn(){
        return $this->isLoggedIn;
    }

    public function checkPermission(string $permission): bool{
        return in_array($permission, $this->permissions);
    }

    public function getPermissions(): array{
        return $this->permissions;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
