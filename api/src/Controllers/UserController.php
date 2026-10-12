<?php
namespace App\Controllers;
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Models\Auth;
use App\Models\Orders\OrdersRepository;
use App\Models\User;
use App\Models\User\UserRepository;
use App\Models\Database;
use Exception;
use Throwable;

class UserController{
    private UserRepository $userRepository;
    private Auth $auth;

    private OrdersRepository $ordersRepository;


    /**
     * @throws Exception
     * @throws Throwable
     */
    public function __construct(Database $db){
        $this->userRepository = new UserRepository($db);
        $this->auth = new Auth($this->userRepository);
        if(!$this->auth->isLoggedIn()){
            throw new Exception('You are not logged in');
        }
        $this->ordersRepository = new OrdersRepository($db);

    }

    /**
     * @throws Throwable
     */
    public function getOrders() : array{
        return $this->ordersRepository->getOrdersById($this->auth->getUser()->getId());
    }

    public function getCountHandledItems(?int $id = null): array
    {
        $userId = $id ?? $this->auth->getUser()->getId();
        $counts = $this->userRepository->getCountHandledItems($userId);
        return [
            'status' => 'success',
            'message' => $counts
        ];
    }

    public function updateUser(): array
    {
        $user = $this->auth->getUser();

        if (isset($_POST['firstname'])) $user->setFirstName(filter_var($_POST['firstname'], FILTER_SANITIZE_SPECIAL_CHARS));
        if (isset($_POST['lastname'])) $user->setLastName(filter_var($_POST['lastname'], FILTER_SANITIZE_SPECIAL_CHARS));
        if (isset($_POST['patronymic'])) $user->setPatronymic(filter_var($_POST['patronymic'], FILTER_SANITIZE_SPECIAL_CHARS));
        if (isset($_POST['phone'])) $user->setPhone(filter_var($_POST['phone'], FILTER_SANITIZE_SPECIAL_CHARS));
        if (isset($_POST['birthdate'])) $user->setBirthdate(!empty($_POST['birthdate']) ? filter_var($_POST['birthdate'], FILTER_SANITIZE_SPECIAL_CHARS) : null);
        if (isset($_POST['instrument'])) $user->setInstrument(filter_var($_POST['instrument'], FILTER_SANITIZE_SPECIAL_CHARS));
        if (isset($_POST['city'])) $user->setCity(filter_var($_POST['city'], FILTER_SANITIZE_SPECIAL_CHARS));
        if (isset($_POST['adress']) || isset($_POST['address'])) {
            $addr = $_POST['adress'] ?? $_POST['address'];
            $user->setAddress(filter_var($addr, FILTER_SANITIZE_SPECIAL_CHARS));
        }

        $this->userRepository->updateUser($user);

        return [
            'status' => 'success',
            'message' => 'Данные обновлены!'
        ];
    }

    public function getUser(): array
    {
        return [
            'role_id' => $this->auth->getUser()->getRoleId(),
            'firstname' => $this->auth->getUser()->getFirstname(),
            'lastname' => $this->auth->getUser()->getLastname(),
            'patronymic' => $this->auth->getUser()->getPatronymic(),
            'email' => $this->auth->getUser()->getEmail(),
            'phone' => $this->auth->getUser()->getPhone(),
            'birthdate' => $this->auth->getUser()->getBirthdate(),
            'bonus_balance' => $this->auth->getUser()->getBonusBalance(),
            'instrument' => $this->auth->getUser()->getInstrument(),
            'city' => $this->auth->getUser()->getCity(),
        ];
    }



}