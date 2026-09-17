<?php
require('checkauth.php');
require('db.php');

$q = '
SELECT SUM(pc.amount) AS cart_count FROM products_cart pc WHERE user_id = ?;
SELECT COUNT(pf.user_id) AS fav_count FROM products_favorites pf WHERE user_id = ?; 
';
$query = $pdo->prepare($q);
$query->execute([$_SESSION['uid'], $_SESSION['uid']]);
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