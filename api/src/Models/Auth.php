<?php

namespace App\Models;
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Models\User\UserRepository;
use Exception;

class Auth{

    private UserRepository $userRepository;
    private array $permissions;
    private bool $isLoggedIn;

    public function __construct(UserRepository $userRepository){
        $this->userRepository = $userRepository;
        if(isset($_SESSION['uid'])){
            $this->isLoggedIn = true;
            $this->permissions = $this->userRepository->getPermissions($_SESSION['uid']);
        } else {
            $this->isLoggedIn = false;
            $this->permissions = [];
        }
    }

    public function login($email, $password){
        try{
            $user = $this->userRepository->getUserByEmail($email);
            $passwordHash = $this->userRepository->getPasswordHash($user->getId());
            if(!password_verify($password, $passwordHash)) {
                throw new Exception("Неверный пароль");
            };

            session_regenerate_id(true);
            $_SESSION['uid'] = $user->getId();
            $_SESSION['role'] = $user->getRoleId();
            $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
            $_SESSION['last_activity'] = time();

            return $user;
        }
        catch (\Exception $e){
            return 'Неверный логин или пароль';
        }
    }

    public function logout(){
        $_SESSION = array();
        session_destroy();
        return 'успешная деаутентификация';
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
}
