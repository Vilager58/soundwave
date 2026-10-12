<?php

namespace App\Controllers;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Models\Brand;
use App\Models\Brand\BrandData;
use App\Models\Brand\BrandRepository;
use App\Models\Database;
use Exception;
use Throwable;

class BrandController
{
    private BrandRepository $brandRepository;

    public function __construct(Database $db)
    {
        $this->brandRepository = new BrandRepository($db);
    }

    /**
     * Получить список брендов
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

        $brands = $this->brandRepository->getAll($query);
        $result = [];
        foreach ($brands as $brand) {
            $result[] = (object) [
                'id' => $brand->getId(),
                'title' => $brand->getTitle(),
                'slug' => $brand->getSlug(),
                'country' => $brand->getCountry(),
                'logoUrl' => $brand->getLogoUrl(),
                'description' => $brand->getDescription(),
                'amount' => $brand->getAmount(),
                'isActive' => $brand->isActive() ? 1 : 0,
            ];
        }
        return $result;
    }

    /**
     * Получить бренд по ID
     *
     * @return object
     * @throws Throwable
     */
    public function getById(): object
    {
        $id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);
        if ($id <= 0) {
            throw new Exception("Не передан id бренда");
        }

        $brand = $this->brandRepository->getById($id);
        return (object) [
            'id' => $brand->getId(),
            'title' => $brand->getTitle(),
            'slug' => $brand->getSlug(),
            'country' => $brand->getCountry(),
            'logoUrl' => $brand->getLogoUrl(),
            'description' => $brand->getDescription(),
            'amount' => $brand->getAmount(),
            'isActive' => $brand->isActive() ? 1 : 0,
        ];
    }

    /**
     * Создать новый бренд
     *
     * @return array
     * @throws Throwable
     */
    public function create(): array
    {
        $title = filter_var($_POST['title'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
        $slug = filter_var($_POST['slug'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
        $country = filter_var($_POST['country'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
        $logoUrl = filter_var($_POST['logoUrl'] ?? $_POST['logo_url'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
        $description = filter_var($_POST['description'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
        $isActive = isset($_POST['is_active']) ? ($_POST['is_active'] == '1') : true;

        $brandData = new BrandData($title, $slug, $country, $logoUrl, $description, $isActive);
        return $this->brandRepository->createBrand($brandData);
    }

    /**
     * Обновить бренд
     *
     * @return array
     * @throws Throwable
     */
    public function update(): array
    {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0) {
            throw new Exception("Не передан id бренда");
        }

        $brand = $this->brandRepository->getById($id);
        $title = filter_var($_POST['title'] ?? $brand->getTitle(), FILTER_SANITIZE_SPECIAL_CHARS);
        $slug = filter_var($_POST['slug'] ?? $brand->getSlug(), FILTER_SANITIZE_SPECIAL_CHARS);
        $country = filter_var($_POST['country'] ?? $brand->getCountry(), FILTER_SANITIZE_SPECIAL_CHARS);
        $logoUrl = filter_var($_POST['logoUrl'] ?? $_POST['logo_url'] ?? $brand->getLogoUrl(), FILTER_SANITIZE_SPECIAL_CHARS);
        $description = filter_var($_POST['description'] ?? $brand->getDescription(), FILTER_SANITIZE_SPECIAL_CHARS);
        $isActive = isset($_POST['is_active']) ? ($_POST['is_active'] == '1') : $brand->isActive();

        $brand->update(new BrandData($title, $slug, $country, $logoUrl, $description, $isActive));
        return $this->brandRepository->updateBrand($brand);
    }

    /**
     * Удалить бренд
     *
     * @return array
     * @throws Throwable
     */
    public function delete(): array
    {
        $id = (int) ($_POST['id'] ?? $_GET['id'] ?? 0);
        if ($id <= 0) {
            throw new Exception("Не передан id бренда");
        }
        return $this->brandRepository->removeBrandById($id);
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
