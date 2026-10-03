<?php
require('db.php');


print_r($productModel->getById(1));

$params = [];
exit;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['type'])) {
        switch ($_POST['type']) {
            case 'get':
                $product = 'SELECT 
                        p.id as product_id,
                        p.title, 
                        p.short_description,
                        p.is_active, 
                        p.slug,
                        p.model_code, 
                        p.is_featured,
                        p.description,
                        b.title as brand, 
                        b.id as brand_id,
                        b.logo_url,
                        c.title as category,
                        c.id as category_id
                    FROM products p
                    JOIN categories c ON p.category_id = c.id
                    JOIN brands b ON p.brand_id = b.id
                    WHERE p.id = ?';

                $variants = 'SELECT
                    pv.id,
                    pv.title,
                    pv.article,
                    pv.barcode,
                    pv.price,
                    pv.old_price,
                    pv.amount,
                    pv.is_active
                    FROM products_variants pv
                    JOIN products p ON pv.product_id = p.id
                    WHERE pv.product_id = ?
                ';

                $attributes = 'SELECT 
                    pv.id as variant_id,
                    pa.attribute_id,
                    a.title,
                    a.unit,
                    a.data_type,
                    COALESCE(pa.value_string, pa.value_number, pa.value_bool) as val
                    FROM products_variants pv
                    LEFT JOIN product_attributes pa ON pv.id = pa.variant_id
                    LEFT JOIN attributes a ON pa.attribute_id = a.id
                    JOIN products p ON pv.product_id = p.id

                    WHERE pv.product_id = ?
                ';

                $imgs = 'SELECT 
                    pi.id as image_id,
                    pi.variant_id,
                    pi.image_url,
                    pi.is_main,
                    pi.sort_order
                    FROM product_images pi
                    WHERE pi.product_id = ?
                ';

                $id = $_POST['id'];

                $query = $pdo->prepare($product);
                $query->execute([$id]);
                $product = $query->fetch(PDO::FETCH_ASSOC);

                $query = $pdo->prepare($variants);
                $query->execute([$id]);
                $variants = $query->fetchAll(PDO::FETCH_ASSOC);

                $query = $pdo->prepare($attributes);
                $query->execute([$id]);
                $attributes = $query->fetchAll(PDO::FETCH_ASSOC);

                $query = $pdo->prepare($imgs);
                $query->execute([$id]);
                $imgs = $query->fetchAll(PDO::FETCH_ASSOC);

                $response = (object) [
                    'id' => $product['product_id'],
                    'productTitle' => $product['title'],
                    'shortDescription' => $product['short_description'],
                    'description' => $product['description'],
                    'brand' => $product['brand'],
                    'brandId' => $product['brand_id'],
                    'category' => $product['category'],
                    'categoryId' => $product['category_id'],
                    'isFeatured' => $product['is_featured'],
                    'isActive' => $product['is_active'],
                    'modelCode' => $product['model_code'],
                    'slug' => $product['slug'],
                    'images' => [],
                    'variants' => [],
                ];

                foreach ($variants as $variant) {
                    $response->variants[$variant['id']] = (object) [
                        'variantId' => $variant['id'],
                        'variant' => $variant['title'],
                        'article' => $variant['article'],
                        'barcode' => $variant['barcode'],
                        'price' => $variant['price'],
                        'oldPrice' => $variant['old_price'],
                        'amount' => $variant['amount'],
                        'isActive' => $variant['is_active'],
                        'attributes' => [],
                    ];
                }
                ;

                foreach ($attributes as $attr) {
                    $response->variants[$attr['variant_id']]->attributes[$attr['attribute_id']] = (object) [
                        'attributeId' => $attr['attribute_id'],
                        'title' => $attr['title'],
                        'type' => $attr['data_type'],
                        'unit' => $attr['unit'],
                        'value' => $attr['val'],
                    ];
                }

                foreach ($imgs as $img) {
                    $response->images[$img['image_id']] = (object) [
                        'id' => $img['image_id'],
                        'variantId' => $img['variant_id'],
                        'imageUrl' => $img['image_url'],
                        'isMain' => $img['is_main'],
                        'sortOrder' => $img['sort_order'],
                    ];
                }
                break;

            case 'update':
                try {
                    $pdo->beginTransaction();
                    $product = "UPDATE products p SET
                        p.category_id = ?,
                        p.brand_id = ?,
                        p.title = ?,
                        p.slug = ?,
                        p.model_code = ?,
                        p.short_Description = ?,
                        p.description = ?,
                        p.is_active = ?,
                        p.is_featured = ?,
                        p.updated_at = NOW()

                        WHERE p.id = ?
                    ";
                    $query = $pdo->prepare($product);
                    $query->execute([
                        $_POST['categoryId'],
                        $_POST['brandId'],
                        $_POST['title'],
                        $_POST['slug'],
                        $_POST['modelCode'],
                        $_POST['shortDescription'],
                        $_POST['description'],
                        $_POST['isActive'],
                        $_POST['isFeatured'],
                        $_POST['id']
                    ]);

                    $variants = json_decode($_POST['variants'], true);


                    foreach ($variants as $variant) {
                        switch ($variant['action']) {
                            case 'update':
                                $variantQuery = 'UPDATE products_variants pv SET
                                                pv.title = ?,
                                                pv.article = ?,
                                                pv.barcode = ?,
                                                pv.price = ?,
                                                pv.old_price = ?,
                                                pv.amount = ?,
                                                pv.is_active = ?

                                                WHERE
                                                pv.id = ?
                                                ';
                                $query = $pdo->prepare($variantQuery);
                                $query->execute([
                                    $variant['variant'],
                                    $variant['article'],
                                    $variant['barcode'],
                                    $variant['price'],
                                    $variant['oldPrice'],
                                    $variant['amount'],
                                    $variant['isActive'],
                                    $variant['variantId'],
                                ]);
                                foreach ($variant['attributes'] as $attribute) {
                                    switch ($attribute['action']) {
                                        case 'update':
                                            $attributeQuery = 'UPDATE product_attributes pa SET
                                                pa.value_string = ?,
                                                pa.value_number = ?,
                                                pa.value_bool = ?

                                                WHERE
                                                pa.variant_id = ? AND pa.attribute_id = ?
                                                ';

                                            $query = $pdo->prepare($attributeQuery);
                                            $query->execute([
                                                $attribute['type'] == 'string' ? $attribute['value'] : null,
                                                $attribute['type'] == 'number' ? $attribute['value'] : null,
                                                $attribute['type'] == 'boolean' ? $attribute['value'] : null,
                                                $variant['variantId'],
                                                $attribute['attributeId']
                                            ]);
                                            break;
                                    }
                                    ;
                                }
                                ;
                                break;
                        }
                        ;
                    }
                    ;

                    $images = json_decode($_POST['images'], true);
                    foreach ($images as $image) {
                        switch ($image['action']) {
                            case 'update':
                                $imageQuery = 'UPDATE product_images pi SET
                                    pi.variant_id = ?,
                                    pi.image_url = ?,
                                    pi.is_main = ?,
                                    pi.sort_order = ?
                                    
                                    WHERE
                                    pi.id = ?
                                    ';

                                $query = $pdo->prepare($imageQuery);
                                $query->execute([
                                    $image['variantId'],
                                    $image['imageUrl'],
                                    $image['isMain'],
                                    $image['sortOrder'],
                                    $image['id']
                                ]);
                                break;
                        }
                    }

                    $pdo->commit();

                    $response = [
                        'status' => 'success',
                        'message' => "Успешное обновление товара"
                    ];

                } catch (Exception $e) {
                    if (isset($pdo) && $pdo->inTransaction()) {
                        $pdo->rollBack();
                    }
                    http_response_code(503);
                    $response = [
                        'status' => 'error',
                        'message' => $e->getMessage(),
                    ];
                    break;
                }
                break;
        }
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }
}
?>