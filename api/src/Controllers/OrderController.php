<?php

namespace App\Controllers;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Models\Database;
use App\Models\Orders\OrdersRepository;
use Exception;
use Throwable;

class OrderController
{
    private OrdersRepository $ordersRepository;

    public function __construct(Database $db)
    {
        if (empty($_SESSION['uid'])) {
            http_response_code(401);
            throw new Exception("Ошибка авторизации");
        }
        $this->ordersRepository = new OrdersRepository($db);
    }

    /**
     * Получить заказы текущего пользователя
     *
     * @return array
     * @throws Throwable
     */
    public function getOrders(): array
    {
        return array_values($this->ordersRepository->getOrdersById((int) $_SESSION['uid']));
    }

    /**
     * Создать новый заказ
     *
     * @return array
     * @throws Throwable
     */
    public function createOrder(): array
    {
        $data = $_POST;
        if (isset($data['items']) && is_string($data['items'])) {
            $data['items'] = json_decode($data['items'], true);
        }
        return $this->ordersRepository->createOrder($data, (int) $_SESSION['uid']);
    }
}
