<?php

namespace App\Controllers;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Models\Cart\CartRepository;
use App\Models\Database;
use Exception;
use Throwable;

class CartController
{
    private CartRepository $cartRepository;

    public function __construct(Database $db)
    {
        if (empty($_SESSION['uid'])) {
            http_response_code(401);
            throw new Exception("Ошибка авторизации");
        }
        $this->cartRepository = new CartRepository($db);
    }

    /**
     * Получить товары в корзине
     *
     * @return array
     * @throws Throwable
     */
    public function getCart(): array
    {
        return $this->cartRepository->getCartByUserId((int) $_SESSION['uid']);
    }

    /**
     * Изменить количество товара
     *
     * @return array
     * @throws Throwable
     */
    public function updateQuantity(): array
    {
        $variantId = (int) ($_POST['variant_id'] ?? 0);
        $delta = (int) ($_POST['delta'] ?? 0);

        if ($variantId <= 0) {
            throw new Exception("Не передан variant_id");
        }

        $this->cartRepository->updateQuantity((int) $_SESSION['uid'], $variantId, $delta);
        return [
            'status' => 'success',
            'case' => 'qty',
            'message' => 'Количество обновлено'
        ];
    }

    /**
     * Удалить товар из корзины
     *
     * @return array
     * @throws Throwable
     */
    public function removeItem(): array
    {
        $variantId = (int) ($_POST['variantId'] ?? 0);
        if ($variantId <= 0) {
            throw new Exception("Не передан variantId");
        }

        $this->cartRepository->removeItem((int) $_SESSION['uid'], $variantId);
        return [
            'status' => 'success',
            'case' => 'rmv',
            'message' => 'Товар удален из корзины'
        ];
    }

    /**
     * Добавить товар в корзину
     *
     * @return array
     * @throws Throwable
     */
    public function addItem(): array
    {
        $variantId = (int) ($_POST['variantId'] ?? 0);
        $amount = (int) ($_POST['amount'] ?? 1);
        if ($variantId <= 0) {
            throw new Exception("Не передан variant_id");
        }

        $this->cartRepository->addItem((int) $_SESSION['uid'], $variantId, $amount);
        return [
            'status' => 'success',
            'message' => 'Товар добавлен в корзину'
        ];
    }
}
