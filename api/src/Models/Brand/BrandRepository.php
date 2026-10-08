<?php
namespace App\Models\Brand;

require_once __DIR__ . '/../../../vendor/autoload.php';

use Exception;
use App\Models\Database;
use App\Models\Brand;
use PDO;

class BrandRepository
{
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    /**
     * Получить все бренды из базы данных
     *
     * @return Brand[]  Brand[] : Массив брендов
     * @throws \Throwable
     */
    public function getAll(?string $query = null): array
    {
        return $this->db->transaction(function (PDO $pdo) use ($query) {
            $params = [];

            $brandQuery = ' SELECT 
                        b.id, 
                        b.title, 
                        b.slug, 
                        b.country, 
                        b.logo_url, 
                        b.description, 
                        b.is_active, 
                        COUNT(p.id) as amount 
                    FROM brands b 
                    LEFT JOIN products p ON b.id = p.brand_id 
                    GROUP BY b.id
                    HAVING 1=1';

            if ($query != null) {
                $brandQuery .= ' AND (b.title LIKE :query OR b.slug LIKE :query)';
                $params['query'] = '%' . $_POST['query'] . '%';
            }

            $query = $pdo->prepare($brandQuery);
            $query->execute($params);
            $brands = $query->fetchAll(PDO::FETCH_ASSOC);

            $result = [];
            foreach ($brands as $brand) {
                $result[] = new Brand(
                    id: $brand['id'],
                    title: $brand['title'],
                    slug: $brand['slug'],
                    country: $brand['country'],
                    logoUrl: $brand['logo_url'],
                    description: $brand['description'],
                    active: $brand['is_active'] == '1',
                    amount: $brand['amount'],
                );
            };

            return $result;
        });
    }

    /**
     * Получить бренд из базы данных
     *
     * @param int $id BrandId : id бренда в бд
     * @return Brand|null Brand : Сущность бренд
     * @throws \Throwable
     */
    public function getById(int $id): ?Brand
    {
        return $this->db->transaction(function (PDO $pdo) use ($id) {
            $brandQuery = ' SELECT 
                        b.id, 
                        b.title, 
                        b.slug, 
                        b.country, 
                        b.logo_url, 
                        b.description, 
                        b.is_active, 
                        COUNT(p.id) as amount 
                    FROM brands b 
                    LEFT JOIN products p ON b.id = p.brand_id 
                    GROUP BY b.id
                    HAVING 1=1 AND b.id = ?';

            $query = $pdo->prepare($brandQuery);
            $query->execute([$id]);
            $brand = $query->fetch(PDO::FETCH_ASSOC);

            if(!$brand) throw new Exception("brand not found");

            return new Brand(
                id: $brand['id'],
                title: $brand['title'],
                slug: $brand['slug'],
                country: $brand['country'],
                logoUrl: $brand['logo_url'],
                description: $brand['description'],
                active: $brand['is_active'] == '1',
                amount: $brand['amount'],
            );
        });
    }

    /**
     * Обновить бренд в базе данных
     *
     * @param Brand $brand
     * @return array|null ArrayMessage : результат
     * @throws \Throwable
     */
    public function updateBrand(Brand $brand): ?array
    {
        return $this->db->transaction(function (PDO $pdo) use ($brand) {
            $brandQuery = "UPDATE brands b SET
                        b.title = ?, 
                        b.slug = ?, 
                        b.country = ?, 
                        b.logo_url = ?, 
                        b.description = ?, 
                        b.is_active = ?

                        WHERE b.id = ?
                    ";

            $query = $pdo->prepare($brandQuery);
            $query->execute([
                $brand->getTitle(),
                $brand->getSlug(),
                $brand->getCountry(),
                $brand->getLogoUrl(),
                $brand->getDescription(),
                $brand->isActive() ? 1 : 0,
                $brand->getId()
            ]);
            return [
                'status' => 'success',
                'message' => "Успешное обновление бренда"
            ];
        });
    }

    /**
     * Удалить бренд из базы данных
     *
     * @param int $id BrandId : id бренда в бд
     * @return array|null ArrayMessage : результат
     * @throws \Throwable
     */
    public function removeBrandById(int $id): ?array
    {

        return $this->db->transaction(function (PDO $pdo) use ($id) {
            $productCount = "SELECT COUNT(p.brand_id) AS amount FROM products p WHERE p.brand_id = ?";
            $query = $pdo->prepare($productCount);
            $query->execute([$id]);
            $amount = $query->fetchColumn();
            if($amount != 0) throw new Exception("Cant remove brand with products");

            $brandQuery = "DELETE FROM brands WHERE id = ?";

            $query = $pdo->prepare($brandQuery);
            $query->execute([$id]);
            return [
                'status' => 'success',
                'message' => "Успешное удаление бренда"
            ];
        });
    }
}
