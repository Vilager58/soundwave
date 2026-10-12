<?php

namespace App\Controllers;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Models\Database;
use App\Models\Product\ProductRepository;
use Exception;
use Throwable;

class ProductController
{
    private ProductRepository $productRepository;

    public function __construct(Database $db)
    {
        $this->productRepository = new ProductRepository($db);
    }

    /**
     * Получить каталог товаров с фильтрами
     *
     * @return array
     * @throws Throwable
     */
    public function getCatalog(): array
    {
        $filters = $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : $_GET;
        return $this->productRepository->getCatalog($filters);
    }

    /**
     * Получить фильтры каталога
     *
     * @return array
     * @throws Throwable
     */
    public function getFilters(): array
    {
        $params = $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : $_GET;
        return $this->productRepository->getFilters($params);
    }

    /**
     * Получить детальную информацию о товаре
     *
     * @return object
     * @throws Throwable
     */
    public function getProduct(): object
    {
        $id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);
        if ($id <= 0) {
            throw new Exception("Не передан id товара");
        }

        return $this->productRepository->getById($id);
    }

    /**
     * Обновить товар
     *
     * @return array
     * @throws Throwable
     */
    public function updateProduct(): array
    {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0) {
            throw new Exception("Не передан id товара");
        }

        return $this->productRepository->update($id, $_POST);
    }

    /**
     * Универсальный обработчик для совместимости с product.php
     *
     * @return mixed
     * @throws Throwable
     */
    public function handle(): mixed
    {
        $type = $_POST['type'] ?? $_GET['type'] ?? 'get';
        return match ($type) {
            'get' => $this->getProduct(),
            'update' => $this->updateProduct(),
            default => throw new Exception("Неизвестный тип действия: $type")
        };
    }
}
