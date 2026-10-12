<?php

namespace App\Models\Orders;
require_once __DIR__ . '/../../../vendor/autoload.php';

use App\Models\Database;
use App\Models\Order;
use PDO;
use IntlDateFormatter;
use Throwable;

class OrdersRepository{
    private Database $db;

    public function __construct(Database $db){
        $this->db = $db;
    }
    public function createOrder(array|Order $order, int $userId = 0): array{
        return $this->db->transaction(function (PDO $pdo) use ($order, $userId) {
            $uId = $userId ?: ($_SESSION['uid'] ?? 0);
            $orderNumber = 'SW-' . strtoupper(bin2hex(random_bytes(4)));
            $deliveryAddress = is_array($order) ? ($order['deliveryAddress'] ?? $order['address'] ?? '') : $order->getDeliveryAddress();
            $finalTotal = is_array($order) ? (float) ($order['total'] ?? 0) : $order->getTotal();
            $deliveryMethodId = is_array($order) ? (int) ($order['deliveryMethodId'] ?? 1) : 1;
            $paymentMethodId = is_array($order) ? (int) ($order['paymentMethodId'] ?? 1) : 1;

            $sql = 'INSERT INTO orders (order_number, user_id, status_id, delivery_method_id, delivery_address_snapshot, final_total, payment_method_id) 
                    VALUES (?, ?, 1, ?, ?, ?, ?)';
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$orderNumber, $uId, $deliveryMethodId, $deliveryAddress, $finalTotal, $paymentMethodId]);
            $orderId = (int) $pdo->lastInsertId();

            $items = is_array($order) ? ($order['items'] ?? []) : $order->getItems();
            if (!empty($items)) {
                $itemSql = 'INSERT INTO order_items (order_id, variant_id, amount, total_price) VALUES (?, ?, ?, ?)';
                $itemStmt = $pdo->prepare($itemSql);
                foreach ($items as $item) {
                    $itemStmt->execute([
                        $orderId,
                        $item['variant_id'] ?? $item['id'] ?? 0,
                        $item['quantity'] ?? $item['amount'] ?? 1,
                        ($item['price'] ?? 0) * ($item['quantity'] ?? $item['amount'] ?? 1)
                    ]);
                }
            }

            return [
                'status' => 'success',
                'message' => 'Заказ успешно оформлен',
                'order_id' => $orderId,
                'order_number' => $orderNumber
            ];
        });
    }

    /**
     * @throws Throwable
     */
    public function getOrdersById(int $id) : array{
        return $this->db->transaction(function (PDO $pdo) use ($id) {
            $ordersQuery = '
            SELECT
              o.id as order_id,
              o.order_number,
              o.delivery_address_snapshot as address,
              o.final_total,
              o.tracking_number,
              o.created_at,
            
              os.id as status_id,
              os.title as status_title,
              dm.title as delivery_title,
              pm.title as payment_title,
            
              oi.variant_id,
              oi.amount,
              oi.total_price as item_total_price,
              
              p.title as product_title,
              pv.title as variant_title,
              pv.article,
              
              pi.image_url
              
            FROM orders o
                 JOIN order_statuses os    ON os.id = o.status_id
                 JOIN delivery_methods dm  ON o.delivery_method_id = dm.id
                 JOIN payment_methods pm   ON o.payment_method_id = pm.id
            LEFT JOIN order_items oi       ON o.id = oi.order_id
            LEFT JOIN products_variants pv ON oi.variant_id = pv.id
            LEFT JOIN products p           ON pv.product_id = p.id
            
            LEFT JOIN product_images pi    
              ON oi.variant_id = pi.variant_id 
              AND pi.is_main = 1
            
            WHERE o.user_id = ?;
            ';

            $query = $pdo->prepare($ordersQuery);
            $query->execute([$_SESSION['uid']]);
            $rows = $query->fetchAll(PDO::FETCH_ASSOC);

            $dateFormatter = new IntlDateFormatter(
                'ru_RU',
                IntlDateFormatter::LONG,
                IntlDateFormatter::NONE,
                'UTC'
            );

            $ordersMap = [];

            foreach ($rows as $row) {
                $orderId = $row['order_id'];
                if (!isset($ordersMap[$orderId])) {
                    $timestamp = strtotime($row['created_at']);
                    $formattedDate = $dateFormatter->format($timestamp);
                    $formattedDate = str_replace(' г.', '', $formattedDate);

                    $ordersMap[$orderId] = (object) [
                        'id' => $row['order_number'],
                        'date' => $formattedDate,
                        'status' => $row['status_id'],
                        'statusLabel' => $row['status_title'],
                        'trackNumber' => $row['tracking_number'],
                        'deliveryType' => $row['delivery_title'],
                        'deliveryAddress' => $row['address'],
                        'paymentMethod' => $row['payment_title'],
                        'items' => [],
                        'total' => (float) $row['final_total'],
                        'createdAt' => date('c', $timestamp)
                    ];
                }

                if (!empty($row['variant_id'])) {
                    $fullTitle = $row['product_title'];
                    if (!empty($row['variant_title'])) {
                        $fullTitle .= ' (' . $row['variant_title'] . ')';
                    }
                    $ordersMap[$orderId]->items[] = [
                        'id' => $row['article'] ?? $row['variant_id'],
                        'quantity' => (int) $row['amount'],
                        'price' => (float) ($row['item_total_price'] / $row['amount']),
                        'title' => $fullTitle,
                        'image' => $row['image_url']
                    ];
                }
            }

            return $ordersMap;
        });
    }

    public function updateOrder(Order $order): bool{
        return $this->db->transaction(function (PDO $pdo) use ($order) {
            $stmt = $pdo->prepare('UPDATE orders SET status_id = ?, delivery_address_snapshot = ?, tracking_number = ? WHERE id = ?');
            return $stmt->execute([
                $order->getStatusId(),
                $order->getDeliveryAddress(),
                $order->getTrackNumber(),
                $order->getId()
            ]);
        });
    }

    public function deleteOrder(Order|int $order): bool{
        return $this->db->transaction(function (PDO $pdo) use ($order) {
            $orderId = is_int($order) ? $order : $order->getId();
            $pdo->prepare('DELETE FROM order_items WHERE order_id = ?')->execute([$orderId]);
            $stmt = $pdo->prepare('DELETE FROM orders WHERE id = ?');
            return $stmt->execute([$orderId]);
        });
    }
}