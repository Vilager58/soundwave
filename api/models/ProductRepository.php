<?php

require_once('Database.php');
class ProductRepository
{
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function getAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM products");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id): ?array
    {
        return $this->db->transaction(function (PDO $pdo) use ($id) {
            $product = 'SELECT 
                        p.id as product_id,
                        p.title, 
                        p.short_description,
                        p.is_active, 
                        p.slug,
                        p.model_code, 
                        p.is_featured,
                        p.description,
                        b.title as brand, 
                        b.id as brand_id,
                        b.logo_url,
                        c.title as category,
                        c.id as category_id
                    FROM products p
                    JOIN categories c ON p.category_id = c.id
                    JOIN brands b ON p.brand_id = b.id
                    WHERE p.id = ?';

            $query = $pdo->prepare($product);
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
