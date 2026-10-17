<?php

namespace App\Models\Favorites;

require_once __DIR__ . '/../../../vendor/autoload.php';

use App\Models\Database;
use PDO;
use Throwable;

class FavoritesRepository
{
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    /**
     * Получить товары из избранного
     *
     * @param int $userId
     * @return array
     * @throws Throwable
     */
    public function getFavoritesByUserId(int $userId): array
    {
        return $this->db->transaction(function (PDO $pdo) use ($userId) {
            $q = '
            SELECT
                p.id as product_id,
                pf.variant_id,
                p.title as product_title,
                pv.title as variant_title,
                pv.article,
                c.title as category_title,
                pv.price,
                pi.image_url,
                COALESCE(r_stats.reviews_count, 0) as reviews_count, 
                COALESCE(r_stats.rating, 0) as rating 
            
            FROM products_favorites pf
            JOIN products_variants pv ON pf.variant_id = pv.id
            JOIN products p           ON pv.product_id = p.id
            LEFT JOIN product_images pi ON pv.id = pi.variant_id AND pi.is_main = 1
            JOIN categories c         ON p.category_id = c.id
            LEFT JOIN (
                SELECT 
                    product_id, 
                    COUNT(*) as reviews_count, 
                    AVG(rating) as rating 
                FROM reviews 
                WHERE status = 2 
                GROUP BY product_id
            ) r_stats ON p.id = r_stats.product_id
            
            WHERE pf.user_id = ?';

            $stmt = $pdo->prepare($q);
            $stmt->execute([$userId]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $favMap = [];
            foreach ($rows as $row) {
                $varId = $row['variant_id'];
                if (!isset( $favMap[$varId])) {
                    $favMap[$varId] = (object) [
                        'productId'    => (int) $row['product_id'],
                        'variantId'    => (int) $row['variant_id'],
                        'productTitle' => $row['product_title'],
                        'price'         => (float) $row['price'],
                        'image'         => $row['image_url'],
                        'article'       => $row['article'],
                        'rating'        => $row['rating'],
                        'variantTitle' => $row['variant_title'],
                        'category'      => $row['category_title'],
                    ];
                }
            }

            return array_values($favMap);
        });
    }

    /**
     * Удалить товар из избранного
     *
     * @param int $userId
     * @param int $variantId
     * @return bool
     * @throws Throwable
     */
    public function removeItem(int $userId, int $variantId): bool
    {
        return $this->db->transaction(function (PDO $pdo) use ($userId, $variantId) {
            $stmt = $pdo->prepare('DELETE FROM products_favorites WHERE user_id = ? AND variant_id = ?');
            return $stmt->execute([$userId, $variantId]);
        });
    }

    /**
     * Добавить товар в избранные
     *
     * @param int $userId
     * @param int $variantId
     * @return bool
     * @throws Throwable
     */
    public function addItem(int $userId, int $variantId): bool
    {
        return $this->db->transaction(function (PDO $pdo) use ($userId, $variantId) {
            $q = 'INSERT INTO products_favorites (user_id, variant_id) VALUES (?, ?)';
            $stmt = $pdo->prepare($q);
            return $stmt->execute([$userId, $variantId]);
        });
    }
}
