<?php

namespace App\Models\Cart;

require_once __DIR__ . '/../../../vendor/autoload.php';

use App\Models\Database;
use PDO;
use Throwable;

class CartRepository
{
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    /**
     * Получить товары в корзине пользователя
     *
     * @param int $userId
     * @return array
     * @throws Throwable
     */
    public function getCartByUserId(int $userId): array
    {
        return $this->db->transaction(function (PDO $pdo) use ($userId) {
            $q = '
            SELECT
                p.id as product_id,
                pc.variant_id,
                pc.amount,
                p.title as product_title,
                pv.title as variant_title,
                pv.article,
                c.title as category_title,
                pv.price,
                pi.image_url
            FROM products_cart pc
            JOIN products_variants pv ON pc.variant_id = pv.id
            JOIN products p           ON pv.product_id = p.id
            LEFT JOIN product_images pi ON pv.id = pi.variant_id AND pi.is_main = 1
            JOIN categories c         ON p.category_id = c.id
            WHERE pc.user_id = ?';

            $stmt = $pdo->prepare($q);
            $stmt->execute([$userId]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $cartMap = [];
            foreach ($rows as $row) {
                $varId = $row['variant_id'];
                if (!isset($cartMap[$varId])) {
                    $cartMap[$varId] = (object) [
                        'productId'    => (int) $row['product_id'],
                        'variantId'    => (int) $row['variant_id'],
                        'productTitle' => $row['product_title'],
                        'price'         => (float) $row['price'],
                        'image'         => $row['image_url'],
                        'article'       => $row['article'],
                        'variantTitle' => $row['variant_title'],
                        'amount'        => (int) $row['amount'],
                        'category'      => $row['category_title'],
                    ];
                }
            }

            return array_values($cartMap);
        });
    }

    /**
     * Обновить количество товара в корзине
     *
     * @param int $userId
     * @param int $variantId
     * @param int $delta
     * @return bool
     * @throws Throwable
     */
    public function updateQuantity(int $userId, int $variantId, int $delta): bool
    {
        return $this->db->transaction(function (PDO $pdo) use ($userId, $variantId, $delta) {
            $q = 'UPDATE products_cart SET amount = amount + ? WHERE user_id = ? AND variant_id = ?';
            $stmt = $pdo->prepare($q);
            $stmt->execute([$delta, $userId, $variantId]);

            // Если количество стало <= 0, удаляем из корзины
            $clean = $pdo->prepare('DELETE FROM products_cart WHERE user_id = ? AND variant_id = ? AND amount <= 0');
            $clean->execute([$userId, $variantId]);

            return true;
        });
    }

    /**
     * Удалить товар из корзины
     *
     * @param int $userId
     * @param int $variantId
     * @return bool
     * @throws Throwable
     */
    public function removeItem(int $userId, int $variantId): bool
    {
        return $this->db->transaction(function (PDO $pdo) use ($userId, $variantId) {
            $stmt = $pdo->prepare('DELETE FROM products_cart WHERE user_id = ? AND variant_id = ?');
            return $stmt->execute([$userId, $variantId]);
        });
    }

    /**
     * Добавить товар в корзину
     *
     * @param int $userId
     * @param int $variantId
     * @param int $amount
     * @return bool
     * @throws Throwable
     */
    public function addItem(int $userId, int $variantId, int $amount = 1): bool
    {
        return $this->db->transaction(function (PDO $pdo) use ($userId, $variantId, $amount) {
            $q = 'INSERT INTO products_cart (user_id, variant_id, amount) VALUES (?, ?, ?)
                  ON DUPLICATE KEY UPDATE amount = amount + VALUES(amount)';
            $stmt = $pdo->prepare($q);
            return $stmt->execute([$userId, $variantId, $amount]);
        });
    }
}
