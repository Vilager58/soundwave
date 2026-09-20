<?php
require('db.php');

$q = '
SELECT c.title, c.slug FROM categories c WHERE ISNULL(c.parent_id) AND c.is_active = 1;
';
$query = $pdo->prepare($q);
$query->execute([]);
$rows = $query->fetch(PDO::FETCH_ASSOC);
$cartCount = $rows['cart_count'] ?? 0;

$query->nextRowset();

$rows = $query->fetch(PDO::FETCH_ASSOC);
$favCount = $rows['fav_count'] ?? 0;

$response = [
    'status' => 'success',
    'message' => [
        'cartCount' => $cartCount,
        'favCount' => $favCount
    ]
];

echo json_encode($response);
?>