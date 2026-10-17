<?php

namespace App\Models\Product;

require_once __DIR__ . '/../../../vendor/autoload.php';

use App\Models\Database;
use Exception;
use PDO;
use Throwable;

class ProductRepository
{
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    /**
     * Получить каталог товаров с фильтрацией
     *
     * @param array $filters
     * @return array
     * @throws Throwable
     */
    public function getCatalog(array $filters = []): array
    {
        return $this->db->transaction(function (PDO $pdo) use ($filters) {
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

            $params = [];

            $searchQuery = $filters['query'] ?? $filters['q'] ?? null;
            if (!empty($searchQuery) && $searchQuery !== 'null') {
                $q .= ' AND p.title LIKE :query';
                $params['query'] = '%' . $searchQuery . '%';
            }

            $selectedCats = $filters['selectedCats'] ?? '';
            if (!empty($selectedCats)) {
                $cats = is_array($selectedCats) ? $selectedCats : explode(',', $selectedCats);
                $placeholders = [];
                foreach ($cats as $index => $catId) {
                    $catId = (int) trim($catId);
                    if ($catId > 0) {
                        $pName = "cat_" . $index;
                        $placeholders[] = ":" . $pName;
                        $params[$pName] = $catId;
                    }
                }
                if (!empty($placeholders)) {
                    $q .= ' AND c.id IN (' . implode(', ', $placeholders) . ')';
                }
            }

            $selectedBrands = $filters['selectedBrands'] ?? '';
            if (!empty($selectedBrands)) {
                $brands = is_array($selectedBrands) ? $selectedBrands : explode(',', $selectedBrands);
                $placeholders = [];
                foreach ($brands as $index => $brandId) {
                    $brandId = (int) trim($brandId);
                    if ($brandId > 0) {
                        $pName = "brand_" . $index;
                        $placeholders[] = ":" . $pName;
                        $params[$pName] = $brandId;
                    }
                }
                if (!empty($placeholders)) {
                    $q .= ' AND b.id IN (' . implode(', ', $placeholders) . ')';
                }
            }

            if (!empty($filters['minP'])) {
                $q .= ' AND pv.price >= :minP';
                $params['minP'] = (float) $filters['minP'];
            }

            if (!empty($filters['maxP'])) {
                $q .= ' AND pv.price <= :maxP';
                $params['maxP'] = (float) $filters['maxP'];
            }

            $query = $pdo->prepare($q);
            $query->execute($params);
            $rows = $query->fetchAll(PDO::FETCH_ASSOC);

            $products = [];
            foreach ($rows as $row) {
                $productId = $row['product_id'];

                if (!isset($products[$productId])) {
                    $products[$productId] = (object) [
                        'id'               => (int) $productId,
                        'productTitle'     => $row['title'],
                        'shortDescription' => $row['short_description'],
                        'description'      => $row['description'],
                        'brand'            => $row['brand'],
                        'brandId'          => (int) $row['brand_id'],
                        'category'         => $row['category'],
                        'categoryId'       => (int) $row['category_id'],
                        'reviewsCount'     => (int) $row['reviews_count'],
                        'rating'           => (float) $row['rating'],
                        'isFeatured'       => (bool) $row['is_featured'],
                        'isActive'         => (bool) $row['is_active'],
                        'modelCode'        => $row['model_code'],
                        'slug'             => $row['slug'],
                        'variants'         => [],
                    ];
                }
                $products[$productId]->variants[] = (object) [
                    'variantId' => (int) $row['variant_id'],
                    'variant'   => $row['variant'],
                    'article'   => $row['article'],
                    'barcode'   => $row['barcode'],
                    'price'     => (float) $row['price'],
                    'oldPrice'  => !empty($row['old_price']) ? (float) $row['old_price'] : null,
                    'amount'    => (int) $row['amount'],
                    'image'     => $row['image_url'],
                ];
            }
            return array_values($products);
        });
    }

    /**
     * Получить доступные фильтры каталога
     *
     * @param array $params
     * @return array
     * @throws Throwable
     */
    public function getFilters(array $params = []): array
    {
        return $this->db->transaction(function (PDO $pdo) use ($params) {
            $selectedBrands = !empty($params['selectedBrands'])
                ? (is_array($params['selectedBrands']) ? $params['selectedBrands'] : explode(',', $params['selectedBrands']))
                : [];
            $selectedCategories = !empty($params['selectedCats'])
                ? (is_array($params['selectedCats']) ? $params['selectedCats'] : explode(',', $params['selectedCats']))
                : [];

            $response = [
                'availableBrands' => (object) [],
                'availableCategories' => (object) [],
                'availableMax' => null,
                'availableMin' => null
            ];

            $qprice = "SELECT MAX(pv.price) as maxP, MIN(pv.price) as minP
                       FROM products_variants pv
                       JOIN products p ON pv.product_id = p.id
                       WHERE 1=1";

            $qcat = "SELECT DISTINCT p.category_id as cat_id, c.title as title
                     FROM products p
                     JOIN categories c ON p.category_id = c.id
                     WHERE c.is_active = 1";

            $qbr = "SELECT DISTINCT p.brand_id as brand_id, b.title as title
                     FROM products p
                     JOIN brands b ON p.brand_id = b.id
                     WHERE b.is_active = 1";

            $paramsBrand = [];
            $paramsCat = [];
            $paramsPrice = [];
            $placeholdersBrands = [];
            $placeholdersCategories = [];

            if (!empty($selectedBrands)) {
                foreach ($selectedBrands as $i => $id) {
                    $id = (int) trim($id);
                    if ($id > 0) {
                        $placeholdersBrands[] = ":b_$i";
                        $paramsBrand["b_$i"] = $id;
                    }
                }
                if (!empty($placeholdersBrands)) {
                    $inB = implode(',', $placeholdersBrands);
                    $qbr .= " AND p.brand_id IN ($inB)";
                    $qprice .= " AND p.brand_id IN ($inB)";
                    $paramsPrice = $paramsPrice + $paramsBrand;
                }
            }

            $query = $pdo->prepare($qbr);
            $query->execute($paramsBrand);
            $rows = $query->fetchAll(PDO::FETCH_ASSOC);

            $availableBrands = [];
            foreach ($rows as $row) {
                $availableBrands[$row['brand_id']] = $row['title'];
            }
            $response['availableBrands'] = (object) $availableBrands;

            if (!empty($selectedCategories)) {
                foreach ($selectedCategories as $i => $id) {
                    $id = (int) trim($id);
                    if ($id > 0) {
                        $placeholdersCategories[] = ":c_$i";
                        $paramsCat["c_$i"] = $id;
                    }
                }
                if (!empty($placeholdersCategories)) {
                    $inC = implode(',', $placeholdersCategories);
                    $qcat .= " AND p.category_id IN ($inC)";
                    $qprice .= " AND p.category_id IN ($inC)";
                    $paramsPrice = $paramsPrice + $paramsCat;
                }
            }

            $query = $pdo->prepare($qcat);
            $query->execute($paramsCat);
            $rows = $query->fetchAll(PDO::FETCH_ASSOC);

            $availableCategories = [];
            foreach ($rows as $row) {
                $availableCategories[$row['cat_id']] = $row['title'];
            }
            $response['availableCategories'] = (object) $availableCategories;

            $query = $pdo->prepare($qprice);
            $query->execute($paramsPrice);
            $rows = $query->fetchAll(PDO::FETCH_ASSOC);

            $response['availableMax'] = $rows[0]['maxP'] ?? 0;
            $response['availableMin'] = $rows[0]['minP'] ?? 0;

            return $response;
        });
    }

    /**
     * Получить товар по ID со всеми вариантами, характеристиками и изображениями
     *
     * @param int $id
     * @return object|null
     * @throws Throwable
     */
    public function getById(int $id): ?object
    {
        return $this->db->transaction(function (PDO $pdo) use ($id) {
            $productQuery = 'SELECT 
                    p.id as product_id,
                    p.title, 
                    p.short_description,
                    p.is_active, 
                    p.slug,
                    p.model_code, 
                    p.is_featured,
                    p.description,
                    c.slug as cat_slug,
                    b.title as brand, 
                    b.id as brand_id,
                    b.logo_url,
                    c.title as category,
                    c.id as category_id
                FROM products p
                JOIN categories c ON p.category_id = c.id
                JOIN brands b ON p.brand_id = b.id
                WHERE p.id = ?';

            $stmt = $pdo->prepare($productQuery);
            $stmt->execute([$id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$product) {
                throw new Exception("Товар не найден");
            }

            $variantsQuery = 'SELECT
                pv.id,
                pv.title,
                pv.article,
                pv.barcode,
                pv.price,
                pv.old_price,
                pv.amount,
                pv.is_active
                FROM products_variants pv
                WHERE pv.product_id = ?';

            $stmt = $pdo->prepare($variantsQuery);
            $stmt->execute([$id]);
            $variants = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $attributesQuery = 'SELECT 
                pv.id as variant_id,
                pa.attribute_id,
                a.title,
                a.unit,
                a.data_type,
                COALESCE(pa.value_string, pa.value_number, pa.value_bool) as val
                FROM products_variants pv
                LEFT JOIN product_attributes pa ON pv.id = pa.variant_id
                LEFT JOIN attributes a ON pa.attribute_id = a.id
                WHERE pv.product_id = ?';

            $stmt = $pdo->prepare($attributesQuery);
            $stmt->execute([$id]);
            $attributes = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $imgsQuery = 'SELECT 
                pi.id as image_id,
                pi.variant_id,
                pi.image_url,
                pi.is_main,
                pi.sort_order
                FROM product_images pi
                WHERE pi.product_id = ?';

            $stmt = $pdo->prepare($imgsQuery);
            $stmt->execute([$id]);
            $imgs = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $response = (object) [
                'id' => (int) $product['product_id'],
                'productTitle' => $product['title'],
                'shortDescription' => $product['short_description'],
                'description' => $product['description'],
                'brand' => $product['brand'],
                'brandId' => (int) $product['brand_id'],
                'category' => $product['category'],
                'categoryId' => (int) $product['category_id'],
                'categorySlug' => $product['cat_slug'],
                'isFeatured' => (bool) $product['is_featured'],
                'isActive' => (bool) $product['is_active'],
                'modelCode' => $product['model_code'],
                'slug' => $product['slug'],
                'images' => (object) [],
                'variants' => (object) [],
            ];

            $variantsObj = [];
            foreach ($variants as $variant) {
                $variantsObj[$variant['id']] = (object) [
                    'variantId' => (int) $variant['id'],
                    'variant' => $variant['title'],
                    'article' => $variant['article'],
                    'barcode' => $variant['barcode'],
                    'price' => (float) $variant['price'],
                    'oldPrice' => !empty($variant['old_price']) ? (float) $variant['old_price'] : null,
                    'amount' => (int) $variant['amount'],
                    'isActive' => (bool) $variant['is_active'],
                    'attributes' => (object) [],
                ];
            }

            foreach ($attributes as $attr) {
                if (isset($variantsObj[$attr['variant_id']]) && !empty($attr['attribute_id'])) {
                    $attrObj = (array) $variantsObj[$attr['variant_id']]->attributes;
                    $attrObj[$attr['attribute_id']] = (object) [
                        'attributeId' => (int) $attr['attribute_id'],
                        'title' => $attr['title'],
                        'type' => $attr['data_type'],
                        'unit' => $attr['unit'],
                        'value' => $attr['val'],
                    ];
                    $variantsObj[$attr['variant_id']]->attributes = (object) $attrObj;
                }
            }
            $response->variants = (object) $variantsObj;

            $imagesObj = [];
            foreach ($imgs as $img) {
                $imagesObj[$img['image_id']] = (object) [
                    'id' => (int) $img['image_id'],
                    'variantId' => !empty($img['variant_id']) ? (int) $img['variant_id'] : null,
                    'imageUrl' => $img['image_url'],
                    'isMain' => (int) $img['is_main'],
                    'sortOrder' => (int) $img['sort_order'],
                ];
            }
            $response->images = (object) $imagesObj;

            return $response;
        });
    }

    /**
     * Обновить товар, варианты, характеристики и изображения
     *
     * @param int $id
     * @param array $data
     * @return array
     * @throws Throwable
     */
    public function update(int $id, array $data): array
    {
        return $this->db->transaction(function (PDO $pdo) use ($id, $data) {
            $productSql = "UPDATE products p SET
                p.category_id = ?,
                p.brand_id = ?,
                p.title = ?,
                p.slug = ?,
                p.model_code = ?,
                p.short_description = ?,
                p.description = ?,
                p.is_active = ?,
                p.is_featured = ?,
                p.updated_at = NOW()
                WHERE p.id = ?";

            $stmt = $pdo->prepare($productSql);
            $stmt->execute([
                $data['categoryId'] ?? 0,
                $data['brandId'] ?? 0,
                $data['title'] ?? '',
                $data['slug'] ?? '',
                $data['modelCode'] ?? null,
                $data['shortDescription'] ?? null,
                $data['description'] ?? '',
                isset($data['isActive']) ? ($data['isActive'] ? 1 : 0) : 1,
                isset($data['isFeatured']) ? ($data['isFeatured'] ? 1 : 0) : 0,
                $id
            ]);

            if (!empty($data['variants'])) {
                $variants = is_string($data['variants']) ? json_decode($data['variants'], true) : $data['variants'];
                if (is_array($variants)) {
                    foreach ($variants as $variant) {
                        $action = $variant['action'] ?? '';
                        if ($action === 'update' && !empty($variant['variantId'])) {
                            $vSql = 'UPDATE products_variants SET
                                title = ?, article = ?, barcode = ?, price = ?, old_price = ?, amount = ?, is_active = ?
                                WHERE id = ?';
                            $vStmt = $pdo->prepare($vSql);
                            $vStmt->execute([
                                $variant['variant'] ?? '',
                                $variant['article'] ?? '',
                                $variant['barcode'] ?? null,
                                $variant['price'] ?? 0,
                                !empty($variant['oldPrice']) ? $variant['oldPrice'] : null,
                                $variant['amount'] ?? 0,
                                isset($variant['isActive']) ? ($variant['isActive'] ? 1 : 0) : 1,
                                $variant['variantId']
                            ]);

                            if (!empty($variant['attributes']) && is_array($variant['attributes'])) {
                                foreach ($variant['attributes'] as $attribute) {
                                    if (($attribute['action'] ?? '') === 'update') {
                                        $aSql = 'UPDATE product_attributes SET
                                            value_string = ?, value_number = ?, value_bool = ?
                                            WHERE variant_id = ? AND attribute_id = ?';
                                        $aStmt = $pdo->prepare($aSql);
                                        $aType = $attribute['type'] ?? 'string';
                                        $aStmt->execute([
                                            $aType === 'string' ? ($attribute['value'] ?? null) : null,
                                            $aType === 'number' ? ($attribute['value'] ?? null) : null,
                                            $aType === 'boolean' ? ($attribute['value'] ?? null) : null,
                                            $variant['variantId'],
                                            $attribute['attributeId']
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }
            }

            if (!empty($data['images'])) {
                $images = is_string($data['images']) ? json_decode($data['images'], true) : $data['images'];
                if (is_array($images)) {
                    foreach ($images as $image) {
                        if (($image['action'] ?? '') === 'update' && !empty($image['id'])) {
                            $imgSql = 'UPDATE product_images SET
                                variant_id = ?, image_url = ?, is_main = ?, sort_order = ?
                                WHERE id = ?';
                            $imgStmt = $pdo->prepare($imgSql);
                            $imgStmt->execute([
                                $image['variantId'] ?? null,
                                $image['imageUrl'] ?? '',
                                !empty($image['isMain']) ? 1 : 0,
                                $image['sortOrder'] ?? 0,
                                $image['id']
                            ]);
                        }
                    }
                }
            }

            return [
                'status' => 'success',
                'message' => 'Успешное обновление товара'
            ];
        });
    }
}
