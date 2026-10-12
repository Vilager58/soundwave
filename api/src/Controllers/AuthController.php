<?php
namespace App\Controllers;
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Models\Database;
use App\Models\Auth;
use App\Models\User\UserRepository;
use Exception;
use App\Models\User\UserDto;
use Throwable;

class AuthController{
    private Auth $auth;

    public function __construct(Database $db){
        $userRepository = new UserRepository($db);
        $this->auth = new Auth($userRepository);
    }

    /**
     * @throws Throwable
     */
    public function login(): array{
        if(!isset($_POST['login-login']) || !isset($_POST['login-password'])){
            throw new Exception("Заполните все поля.");
        }
        try{
            $this->auth->login($_POST['login-login'], $_POST['login-password']);
            return [
                'status' => 'success',
                'message' => "Успешная авторизация!"
            ];
        } catch (Exception){
            http_response_code(403);
            return [
                'status' => 'error',
                'message' => "Неверные данные."
            ];
        }
    }

    public function logout(): array{
        $this->auth->logout();
        return [
            'status' => 'success',
            'message' => "Успешный выход!"
        ];
    }

    /**
     * @throws Throwable
     */
    public function register(): array
    {
        if (!isset($_POST['reg-name']) ||
            !isset($_POST['reg-lastname']) ||
            !isset($_POST['reg-patronymic']) ||
            !isset($_POST['reg-email']) ||
            !isset($_POST['reg-phone']) ||
            !isset($_POST['reg-password'])
        ) {
            throw new Exception("Заполните все поля.");
        }

        $userDto = new UserDto(
            $_POST['reg-name'],
            $_POST['reg-lastname'],
            $_POST['reg-patronymic'],
            $_POST['reg-email'],
            $_POST['reg-phone'],
            $_POST['reg-password']
        );
        $this->auth->register($userDto);
        return [
            'status' => 'success',
            'message' => "Успешная регистрация!"
        ];
    }
}
