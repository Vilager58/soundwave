<?php
require('db.php');


$params = [];


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
                    pv.id,
                    pa.attribute_id,
                    a.title,
                    a.unit,
                    COALESCE(pa.value_string, pa.value_number, pa.value_bool) as val
                    FROM products_variants pv
                    LEFT JOIN product_attributes pa ON pv.id = pa.variant_id
                    LEFT JOIN attributes a ON pa.attribute_id = a.id
                    JOIN products p ON pv.product_id = p.id

                    WHERE pv.product_id = ?
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
                    'variants' => [],
                    'images' => [],
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
                        'attributes' => [],
                    ];
                };

                foreach ($attributes as $attr) {
                    $response->variants[$attr['id']]->attributes[$attr['attribute_id']] = (object) [
                        'atributeId' => $attr['attribute_id'],
                        'title' => $attr['title'],
                        'unit' => $attr['unit'],
                        'value' => $attr['val'],
                    ];
                }

                // $response = array_values($product);
                // echo($product[0]);
                break;

            case 'update':
                $id = filter_var($_POST['id'], FILTER_SANITIZE_SPECIAL_CHARS);
                $parent_id = filter_var($_POST['parentCategoryId'], FILTER_SANITIZE_SPECIAL_CHARS);
                $parent_id = $parent_id == 0 ? null : $parent_id;
                $title = filter_var($_POST['title'], FILTER_SANITIZE_SPECIAL_CHARS);
                $slug = filter_var($_POST['slug'], FILTER_SANITIZE_SPECIAL_CHARS);
                $description = filter_var($_POST['description'], FILTER_SANITIZE_SPECIAL_CHARS);
                $is_active = isset($_POST['is_active']) ? '1' : '0';
                $q = ' UPDATE categories c SET 
                        c.title = ?, 
                        c.slug = ?,  
                        c.parent_id = ?, 
                        c.description = ?, 
                        c.is_active = ?

                        WHERE c.id = ?
                        ';

                $query = $pdo->prepare($q);
                $query->execute([$title, $slug, $parent_id, $description, $is_active, $id]);
                $response = [
                    'status' => 'success',
                    'message' => "Успешное обновление категории"
                ];
                break;

            case 'add':
                $parent_id = filter_var($_POST['id'], FILTER_SANITIZE_SPECIAL_CHARS);
                $title = filter_var($_POST['title'], FILTER_SANITIZE_SPECIAL_CHARS);
                $slug = filter_var($_POST['slug'], FILTER_SANITIZE_SPECIAL_CHARS);
                $description = filter_var($_POST['description'], FILTER_SANITIZE_SPECIAL_CHARS);
                $is_active = isset($_POST['is_active']) ? '1' : '0';

                $q = ' INSERT INTO categories(
                           title, slug, parent_id, description, is_active)
                           VALUES(?, ?, ?, ?, ?)';
                $query = $pdo->prepare($q);
                $query->execute([$title, $slug, $parent_id, $description, $is_active]);
                $response = [
                    'status' => 'success',
                    'message' => "Успешное добавление категории"
                ];
                break;

            case 'remove':
                $id = filter_var($_POST['id'], FILTER_SANITIZE_SPECIAL_CHARS);
                $q = ' DELETE FROM categories WHERE id = ?';
                $query = $pdo->prepare($q);
                $query->execute([$id]);
                $response = [
                    'status' => 'success',
                    'message' => "Успешное удаление категории"
                ];
                break;
        }
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }
}
?>