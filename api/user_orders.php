<?php
require('checkauth.php');
require('db.php');

$q = '
SELECT
  o.id as order_id,
  o.order_number,
  o.delivery_address_snapshot as адрес,
  o.final_total,
  o.tracking_number,
  o.created_at,

  os.id as Статус_заказа,
  os.title as status_title,
  dm.title as delivery_title,
  pm.title as payment_title,

  oi.variant_id,
  oi.amount,
  oi.total_price as item_total_price,
  
  p.title as product_title,
  pv.title as variant_title,
  pv.article

FROM orders o
     JOIN order_statuses os    ON os.id = o.status_id
     JOIN delivery_methods dm  ON o.delivery_method_id = dm.id
     JOIN payment_methods pm   ON o.payment_method_id = pm.id
LEFT JOIN order_items oi       ON o.id = oi.order_id
LEFT JOIN products_variants pv ON oi.variant_id = pv.id
LEFT JOIN products p           ON pv.product_id = p.id

WHERE o.user_id = ?;
';

$query = $pdo->prepare($q);
$query->execute([$_SESSION['uid']]);
$rows = $query->fetchAll(PDO::FETCH_ASSOC);

$ordersMap = [];

$dateFormatter = new IntlDateFormatter(
    'ru_RU',
    IntlDateFormatter::LONG,
    IntlDateFormatter::NONE,
    'UTC'
);

foreach ($rows as $row) {
    $orderId = $row['order_id'];
    if (!isset($ordersMap[$orderId])) {
        $timestamp = strtotime($row['created_at']);
        $formattedDate = $dateFormatter->format($timestamp);
        $formattedDate = str_replace(' г.', '', $formattedDate);

        $ordersMap[$orderId] = (object) [
            'id' => $row['order_number'],
            'date' => $formattedDate,
            'status' => $row['Статус_заказа'],
            'statusLabel' => $row['status_title'],
            'trackNumber' => $row['tracking_number'],
            'deliveryType' => $row['delivery_title'],
            'deliveryAddress' => $row['адрес'],
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
            'image' => "img/products/{$row['variant_id']}.jpg"
        ];
    }
}

$result = array_values($ordersMap);
echo json_encode($result, JSON_UNESCAPED_UNICODE);

?>