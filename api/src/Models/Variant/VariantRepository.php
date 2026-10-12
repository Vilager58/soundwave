<?php

namespace App\Models\Variant;

require_once __DIR__ . '/../../../vendor/autoload.php';

use App\Models\Database;
use App\Models\Variant;
use Exception;
use PDO;

class VariantRepository
{
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    /**
     * Получить варианты товара
     *
     * @param int $productId
     * @return Variant[]
     * @throws \Throwable
     */
    public function getByProductId(int $productId): array
    {
        return $this->db->transaction(function (PDO $pdo) use ($productId) {
            $stmt = $pdo->prepare('SELECT * FROM products_variants WHERE product_id = ?');
            $stmt->execute([$productId]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $result = [];
            foreach ($rows as $row) {
                $result[] = new Variant(
                    id: (int) $row['id'],
                    productId: (int) $row['product_id'],
                    title: (string) $row['title'],
                    article: (string) $row['article'],
                    barcode: $row['barcode'] ?? null,
                    price: (float) $row['price'],
                    oldPrice: !empty($row['old_price']) ? (float) $row['old_price'] : null,
                    amount: (int) $row['amount'],
                    isActive: $row['is_active'] == '1'
                );
            }
            return $result;
        });
    }

    /**
     * Получить вариант по ID
     *
     * @param int $id
     * @return Variant|null
     * @throws \Throwable
     */
    public function getById(int $id): ?Variant
    {
        return $this->db->transaction(function (PDO $pdo) use ($id) {
            $stmt = $pdo->prepare('SELECT * FROM products_variants WHERE id = ?');
            $stmt->execute([$id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$row) {
                throw new Exception("Вариант не найден");
            }

            return new Variant(
                id: (int) $row['id'],
                productId: (int) $row['product_id'],
                title: (string) $row['title'],
                article: (string) $row['article'],
                barcode: $row['barcode'] ?? null,
                price: (float) $row['price'],
                oldPrice: !empty($row['old_price']) ? (float) $row['old_price'] : null,
                amount: (int) $row['amount'],
                isActive: $row['is_active'] == '1'
            );
        });
    }
}
