<?php
require('db.php');

$selected_brands = !empty($selected_brands) ? explode(',', $_POST['selectedBrands']) : [];
$selected_categories = !empty($selected_categories) ? explode(',', $_POST['selectedCats']) : [];

$response = [
    'availableBrands' => [],
    'availableCats' => [],
    'availableMax' => [],
    'availableMin' => []
];


$qprice = "SELECT MAX(pv.price) as maxP, MIN(pv.price) as minP
           FROM products_variants pv
           JOIN products p ON pv.product_id = p.id
           WHERE 1=1";


$qcat = "SELECT DISTINCT p.category_id as cat_id, c.title as title
         FROM products p
         JOIN categories c ON p.category_id = c.id
         WHERE c.is_active = 1";

$params_cat = [];
$placeholders_brands = [];
$placeholders_categories = [];

if (!empty($selected_brands)) {
    foreach ($selected_brands as $i => $id) {
        $placeholders_brands[] = ":b_$i";
        $params_cat["b_$i"] = (int)$id;
    }
    $qcat .= "AND p.brand_id IN (" . implode(',', $placeholders_brands) . ")";
    $qprice .= "AND p.brand_id IN (" . implode(',', $placeholders_brands) . ")";
}

$query = $pdo->prepare($qcat);
$query->execute($params_cat);
$rows = $query->fetchAll(PDO::FETCH_ASSOC);

foreach($rows as $row){
    $response['availableCategories'][$row['cat_id']] = $row['title'];
};

$qbr = "SELECT DISTINCT p.brand_id as brand_id, b.title as title
         FROM products p
         JOIN brands b ON p.brand_id = b.id
         WHERE b.is_active = 1";

$params_brand = [];

if (!empty($selected_categories)) {
    foreach ($selected_categories as $i => $id) {
        $placeholders_categories[] = ":c_$i";
        $params_brand["c_$i"] = (int)$id;
    }
    $qbr .= "AND p.category_id IN (" . implode(',', $placeholders_categories) . ")";
    $qprice .= "AND p.category_id IN (" . implode(',', $placeholders_categories) . ")";
}

$query = $pdo->prepare($qbr);
$query->execute($params_brand);
$rows = $query->fetchAll(PDO::FETCH_ASSOC);

foreach($rows as $row){
    $response['availableBrands'][$row['brand_id']] = $row['title'];
};

$query = $pdo->prepare($qprice);
$query->execute($params_cat + $params_brand);
$rows = $query->fetchAll(PDO::FETCH_ASSOC);

$response['availableMax'] = $rows['0']['maxP'];
$response['availableMin'] = $rows['0']['minP'];

echo json_encode($response);
