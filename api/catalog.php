<?php
require('db.php');

$q = '
SELECT 
pv.id as variant_id,
p.title,
p.short_description,
pv.price,
pv.old_price,
pv.title as variant,
b.title as brand,
c.title as category,
pi.image_url,
(SELECT COUNT(*) FROM reviews r WHERE r.product_id = p.id AND r.status = 2) as reviews_count,
(SELECT AVG(r.rating) FROM reviews r WHERE r.product_id = p.id AND r.status = 2) as rating
FROM
     products_variants pv
JOIN products p           ON pv.product_id = p.id
JOIN categories c         ON p.category_id = c.id
JOIN brands b             ON p.brand_id = b.id
JOIN product_images pi    ON pv.id = pi.variant_id AND pi.is_main = 1 

WHERE
    pv.is_active = 1
AND pv.amount > 0
';

$query = $pdo->prepare($q);
$query->execute([]);
$rows = $query->fetchAll(PDO::FETCH_ASSOC);

$products = [];

foreach ($rows as $row) {
    $varId = $row['variant_id'];
    if (!isset($products[$varId])) {
        $products[$varId] = (object) [
            'variantId' => $row['variant_id'],
            'productTitle' => $row['title'],
            'shortDescription' => $row['short_description'],
            'price' => $row['price'],
            'oldPrice' => $row['old_price'],
            'variant' => $row['variant'],
            'brand' => $row['brand'],
            'category' => $row['category'],
            'image' => $row['image_url'],
            'reviewsCount' => $row['reviews_count'],
            'rating' => $row['rating'],
        ];
    }
}

$result = array_values($products);
echo json_encode($result, JSON_UNESCAPED_UNICODE);

?>