<?php

namespace App\Controllers;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Models\Attribute;
use App\Models\Attribute\AttributeRepository;
use App\Models\Database;
use Exception;
use Throwable;

class AttributeController
{
    private AttributeRepository $attributeRepository;

    public function __construct(Database $db)
    {
        $this->attributeRepository = new AttributeRepository($db);
    }

    /**
     * Получить список характеристик
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

        $attributes = $this->attributeRepository->getAll($query);
        $result = [];
        foreach ($attributes as $attr) {
            $result[] = (object) [
                'id' => $attr->getId(),
                'code' => $attr->getCode(),
                'title' => $attr->getTitle(),
                'unit' => $attr->getUnit(),
                'dataType' => $attr->getDataType(),
                'isFilterable' => $attr->isFilterable() ? 1 : 0,
            ];
        }
        return $result;
    }

    /**
     * Получить характеристику по ID
     *
     * @return object
     * @throws Throwable
     */
    public function getById(): object
    {
        $id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);
        if ($id <= 0) {
            throw new Exception("Не передан id характеристики");
        }

        $attr = $this->attributeRepository->getById($id);
        return (object) [
            'id' => $attr->getId(),
            'code' => $attr->getCode(),
            'title' => $attr->getTitle(),
            'unit' => $attr->getUnit(),
            'dataType' => $attr->getDataType(),
            'isFilterable' => $attr->isFilterable() ? 1 : 0,
        ];
    }

    /**
     * Создать новую характеристику
     *
     * @return array
     * @throws Throwable
     */
    public function create(): array
    {
        $id = $this->attributeRepository->create($_POST);
        return [
            'status' => 'success',
            'message' => 'Успешное создание характеристики',
            'id' => $id
        ];
    }

    /**
     * Обновить характеристику
     *
     * @return array
     * @throws Throwable
     */
    public function update(): array
    {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0) {
            throw new Exception("Не передан id характеристики");
        }

        $this->attributeRepository->update($id, $_POST);
        return [
            'status' => 'success',
            'message' => 'Успешное обновление характеристики'
        ];
    }

    /**
     * Удалить характеристику
     *
     * @return array
     * @throws Throwable
     */
    public function delete(): array
    {
        $id = (int) ($_POST['id'] ?? $_GET['id'] ?? 0);
        if ($id <= 0) {
            throw new Exception("Не передан id характеристики");
        }

        $this->attributeRepository->delete($id);
        return [
            'status' => 'success',
            'message' => 'Успешное удаление характеристики'
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
