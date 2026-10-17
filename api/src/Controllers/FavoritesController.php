<?php

namespace App\Controllers;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Models\Favorites\FavoritesRepository;
use App\Models\Database;
use Exception;
use Throwable;

class FavoritesController
{
    private FavoritesRepository $favRepository;

    public function __construct(Database $db)
    {
        if (empty($_SESSION['uid'])) {
            http_response_code(401);
            throw new Exception("Ошибка авторизации");
        }
        $this->favRepository = new FavoritesRepository($db);
    }

    /**
     * Получить товары в избранных
     *
     * @return array
     * @throws Throwable
     */
    public function getFavorites(): array
    {
        return $this->favRepository->getFavoritesByUserId((int) $_SESSION['uid']);
    }


    /**
     * Удалить товар из избранных
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

        $this->favRepository->removeItem((int) $_SESSION['uid'], $variantId);
        return [
            'status' => 'success',
            'message' => 'Товар удален из избранных'
        ];
    }

    /**
     * Добавить товар в избранные
     *
     * @return array
     * @throws Throwable
     */
    public function addItem(): array
    {
        $variantId = (int)($_POST['variantId'] ?? 0);
        if ($variantId <= 0) {
            throw new Exception("Не передан variantId");
        }

        $this->favRepository->addItem((int)$_SESSION['uid'], $variantId);
        return [
            'status' => 'success',
            'message' => 'Товар добавлен в избранные'
        ];
    }
}
