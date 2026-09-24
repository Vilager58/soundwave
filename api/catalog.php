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

WHERE 1=1
';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $params = [];

    if ($_POST['query'] != 'null') {
        $q .= 'AND p.title LIKE :query';
        $params['query'] = '%' . $_POST['query'] . '%';
    };

    $placeholders = [];
    if ($_POST['selectedCats'] != '') {
        $cats = explode(',', $_POST['selectedCats']);
        foreach ($cats as $index => $cat_id) {
            $placeholder_name = "cat_" . $index;
            $placeholders[] = ":" . $placeholder_name;
            $params[$placeholder_name] = (int) $cat_id;
        }
        $q .= 'AND c.id IN (' . implode(', ', $placeholders) . ')';
    };

    $placeholders = [];
    if ($_POST['selectedBrands'] != '') {
        $brands = explode(',', $_POST['selectedBrands']);
        foreach ($brands as $index => $brand_id) {
            $placeholder_name = "brand_" . $index;
            $placeholders[] = ":" . $placeholder_name;
            $params[$placeholder_name] = (int) $brand_id;
        }
        $q .= 'AND b.id IN (' . implode(', ', $placeholders) . ')';
    };

    if ($_POST['minP'] != '') {
        $q .= ' AND pv.price >= ' . $_POST['minP'];
    };

    if ($_POST['maxP'] != '') {
        $q .= ' AND pv.price <= ' . $_POST['maxP'];
    };

    $query = $pdo->prepare($q);
    $query->execute($params);
    $rows = $query->fetchAll(PDO::FETCH_ASSOC);

} else {
    $query = $pdo->prepare($q);
    $query->execute([]);
    $rows = $query->fetchAll(PDO::FETCH_ASSOC);
}

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