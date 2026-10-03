<?php
namespace App\Brand;
// 1. ПОДКЛЮЧАЕМ ФАЙЛ: Выходим из Brand/ на один уровень вверх в src/ и берем Database.php
require_once __DIR__ . '/../Database.php';
require_once 'Brand.php';

use PDO;
use App\Database;
use App\Brand;

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
            foreach($brands as $brand){
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

    public function getById(int $id): ?array
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
                    HAVING 1=1';
            
            $query = $pdo->prepare($brandQuery);
            $query->execute([$id]);
            $product = $query->fetch(PDO::FETCH_ASSOC);

            $response = [
                'id' => $product['product_id'],
                'productTitle' => $product['title'],
                'shortDescription' => $product['short_description'],
                'description' => $product['description'],
                'brand' => $product['brand'],
                'brandId' => $product['brand_id'],
                'category' => $product['category'],
                'categoryId' => $product['category_id'],
                'isFeatured' => $product['is_featured'],
                'isActive' => $product['is_active'],
                'modelCode' => $product['model_code'],
                'slug' => $product['slug']
            ];
            return $response;
        });
    }
    public function updateById(int $id): ?array
    {
        return $this->db->transaction(function (PDO $pdo) use ($id) {
            $product = "UPDATE products p SET
                        p.category_id = ?,
                        p.brand_id = ?,
                        p.title = ?,
                        p.slug = ?,
                        p.model_code = ?,
                        p.short_Description = ?,
                        p.description = ?,
                        p.is_active = ?,
                        p.is_featured = ?,
                        p.updated_at = NOW()

                        WHERE p.id = ?
                    ";

            $query = $pdo->prepare($product);
            $query->execute([
                $_POST['categoryId'],
                $_POST['brandId'],
                $_POST['title'],
                $_POST['slug'],
                $_POST['modelCode'],
                $_POST['shortDescription'],
                $_POST['description'],
                $_POST['isActive'],
                $_POST['isFeatured'],
                $id
            ]);
            $response = [
                'status' => 'success',
                'message' => "Успешное обновление товара"
            ];
            return $response;
        });
    }

    
    
}
