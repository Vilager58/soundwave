<?php

namespace App\Controllers;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Models\Category;
use App\Models\Category\CategoryRepository;
use App\Models\Database;
use Exception;
use Throwable;

class CategoryController
{
    private CategoryRepository $categoryRepository;

    public function __construct(Database $db)
    {
        $this->categoryRepository = new CategoryRepository($db);
    }

    /**
     * Получить список категорий
     *
     * @return array
     * @throws Throwable
     */
    public function getAll(): array
    {
        $query = $_GET['query'] ?? $_POST['query'] ?? null;
        if ($query === 'null') {
            $query = null;
        }
        $parentOnly = isset($_GET['parent']) || isset($_POST['parent']);

        $categories = $this->categoryRepository->getAll($query, $parentOnly);
        $result = [];
        foreach ($categories as $category) {
            $result[] = (object) [
                'id' => $category->getId(),
                'title' => $category->getTitle(),
                'slug' => $category->getSlug(),
                'parentTitle' => $category->getParentTitle(),
                'parentId' => $category->getParentId(),
                'description' => $category->getDescription(),
                'amount' => $category->getAmount(),
                'isActive' => $category->isActive() ? 1 : 0,
            ];
        }
        return $result;
    }

    /**
     * Получить категорию по ID
     *
     * @return object
     * @throws Throwable
     */
    public function getById(): object
    {
        $id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);
        if ($id <= 0) {
            throw new Exception("Не передан id категории");
        }

        $category = $this->categoryRepository->getById($id);
        return (object) [
            'id' => $category->getId(),
            'title' => $category->getTitle(),
            'slug' => $category->getSlug(),
            'parentTitle' => $category->getParentTitle(),
            'parentId' => $category->getParentId(),
            'description' => $category->getDescription(),
            'amount' => $category->getAmount(),
            'isActive' => $category->isActive() ? 1 : 0,
        ];
    }

    /**
     * Создать новую категорию
     *
     * @return array
     * @throws Throwable
     */
    public function create(): array
    {
        $id = $this->categoryRepository->create($_POST);
        return [
            'status' => 'success',
            'message' => 'Успешное добавление категории',
            'id' => $id
        ];
    }

    /**
     * Обновить категорию
     *
     * @return array
     * @throws Throwable
     */
    public function update(): array
    {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0) {
            throw new Exception("Не передан id категории");
        }

        $this->categoryRepository->update($id, $_POST);
        return [
            'status' => 'success',
            'message' => 'Успешное обновление категории'
        ];
    }

    /**
     * Удалить категорию
     *
     * @return array
     * @throws Throwable
     */
    public function delete(): array
    {
        $id = (int) ($_POST['id'] ?? $_GET['id'] ?? 0);
        if ($id <= 0) {
            throw new Exception("Не передан id категории");
        }

        $this->categoryRepository->delete($id);
        return [
            'status' => 'success',
            'message' => 'Успешное удаление категории'
        ];
    }

    /**
     * Универсальный обработчик (для совместимости с type=get/add/update/remove)
     *
     * @return mixed
     * @throws Throwable
     */
    public function handle(): mixed
    {
        $type = $_POST['type'] ?? $_GET['type'] ?? 'get';
        return match ($type) {
            'get' => $this->getAll(),
            'add', 'create' => $this->create(),
            'update' => $this->update(),
            'remove', 'delete' => $this->delete(),
            default => throw new Exception("Неизвестный тип действия: $type")
        };
    }
}
