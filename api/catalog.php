<?php
require('db.php');

$q = '
SELECT 
    p.id as product_id,
    pv.id as variant_id, 
    p.title, 
    p.short_description,
    p.is_active, 
    p.slug,
    p.model_code, 
    p.is_featured,
    p.description,
    pv.price, 
    pv.old_price, 
    pv.amount, 
    pv.article,
    pv.barcode,
    
    pv.title as variant, 
    b.title as brand, 
    b.id as brand_id,
    c.title as category,
    c.id as category_id,
    pi.image_url, 
    COALESCE(r_stats.reviews_count, 0) as reviews_count, 
    COALESCE(r_stats.rating, 0) as rating 
FROM products_variants pv
JOIN products p ON pv.product_id = p.id
JOIN categories c ON p.category_id = c.id
JOIN brands b ON p.brand_id = b.id
LEFT JOIN product_images pi ON pv.id = pi.variant_id AND pi.is_main = 1
LEFT JOIN (
    SELECT 
        product_id, 
        COUNT(*) as reviews_count, 
        AVG(rating) as rating 
    FROM reviews 
    WHERE status = 2 
    GROUP BY product_id
) r_stats ON p.id = r_stats.product_id

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

$products = [];

foreach ($rows as $row) {
    $productId = $row['product_id'];

    // 1. Если такого продукта еще нет в массиве, создаем его (один раз на продукт)
    if (!isset($products[$productId])) {
        $products[$productId] = (object) [
            'id'               => $productId,
            'productTitle'     => $row['title'],
            'shortDescription' => $row['short_description'],
            'description'      => $row['description'],
            'brand'            => $row['brand'],
            'brandId'          => $row['brand_id'],
            'category'         => $row['category'],
            'categoryId'       => $row['category_id'],
            'reviewsCount'     => $row['reviews_count'],
            'rating'           => $row['rating'],
            'isFeatured'       => $row['is_featured'],
            'isActive'         => $row['is_active'],
            'modelCode'        => $row['model_code'],
            'slug'             => $row['slug'],
            'variants'         => []
        ];
    }

    // 2. Добавляем текущий вариант в массив вариантов этого продукта
    $products[$productId]->variants[] = (object) [
        'variantId' => $row['variant_id'],
        'variant'   => $row['variant'],
        'article'   => $row['article'],
        'barcode'   => $row['barcode'],
        'price'     => $row['price'],
        'oldPrice'  => $row['old_price'],
        'amount'    => $row['amount'],
        'image'     => $row['image_url'],
    ];
}

$result = array_values($products);
echo json_encode($result, JSON_UNESCAPED_UNICODE);

?>