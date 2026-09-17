<?php
require('checkauth.php');
require('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
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
    JOIN product_images pi    ON pv.id = pi.variant_id AND pi.is_main = 1
    JOIN categories c         ON p.category_id = c.id

    WHERE pc.user_id = ?';

    $query = $pdo->prepare($q);
    $query->execute([$_SESSION['uid']]);
    $rows = $query->fetchAll(PDO::FETCH_ASSOC);

    $cartMap = [];

    foreach ($rows as $row) {
        $varId = $row['variant_id'];
        if (!isset($ordersMap[$varId])) {
            $cartMap[$varId] = (object) [
                'product_id' => $row['product_id'],
                'variant_id' => $row['variant_id'],
                'product_title' => $row['product_title'],
                'price' => $row['price'],
                'image' => $row['image_url'],
                'article' => $row['article'],
                'variant_title' => $row['variant_title'],
                'amount' => $row['amount'],
                'category' => $row['category_title'],
            ];
        }
    }
    $result = array_values($cartMap);
} elseif ($_POST['case'] == 'updateQuanity') {
    $q = '
        UPDATE products_cart SET amount = amount + ? WHERE user_id = ? AND variant_id = ?
    ';
    $query = $pdo->prepare($q);
    $query->execute([$_POST['delta'], $_SESSION['uid'], $_POST['variant_id']]);
    $rows = $query->fetchAll(PDO::FETCH_ASSOC);
    $result = [
        'case' => 'qty',
        ''
    ];
} elseif ($_POST['case'] == 'removeItem') {
    $q = '
        DELETE FROM products_cart WHERE user_id = ? AND variant_id = ?
    ';
    $query = $pdo->prepare($q);
    $query->execute([$_SESSION['uid'], $_POST['variant_id']]);
    $rows = $query->fetchAll(PDO::FETCH_ASSOC);
    $result = [
        'case' => 'rmv',
        ''
    ];
}
;


echo json_encode($result, JSON_UNESCAPED_UNICODE);

?>