<?php

use App\Brand\BrandRepository;
require('db.php');

$params = [];

require_once('./src/Brand/BrandRepository.php');
require_once('./src/Database.php');
use App\Database;
// use App\Brand\BrandRepository;

$db = new Database('mysql:host=localhost;dbname=soundwave;port=3306', 'root', '');

$brandRepository = new BrandRepository($db);

print_r($brandRepository->getById(1));
exit;

function loadFile($type)
{
    switch ($type) {
        case 'brand':
            $destPath = '';
            if (isset($_POST['logoUrl']) && $_POST['logoUrl'] != '') {
                $destPath = $_POST['logoUrl'];
            }
            if (isset($_FILES['logo_input']) && $_FILES['logo_input']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['logo_input']['tmp_name'];
                $fileName = basename($_FILES['logo_input']['name']);
                $uploadFolder = '../uploads/brands/';
                if (!is_dir($uploadFolder)) {
                    mkdir($uploadFolder, 0755, true);
                }
                ;
                $destPath = $uploadFolder . $fileName;
                $move = move_uploaded_file($fileTmpPath, $destPath);
                $destPath = substr($destPath, 2);
            }
            return $destPath;
            break;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['type'])) {
        switch ($_POST['type']) {
            case 'get':
                $q = ' SELECT 
                        b.id, 
                        b.title, 
                        b.slug, 
                        b.country, 
                        b.logo_url, 
                        b.description, 
                        b.is_active, 
                        COUNT(p.id) as amount 
                    FROM brands b 
                    LEFT JOIN products p ON b.id = p.brand_id 
                    GROUP BY b.id
                    HAVING 1=1';

                if ($_POST['query'] != 'null') {
                    $q .= ' AND (b.title LIKE :query OR b.slug LIKE :query)';
                    $params['query'] = '%' . $_POST['query'] . '%';
                }
                ;
                $query = $pdo->prepare($q);
                $query->execute($params);
                $rows = $query->fetchAll(PDO::FETCH_ASSOC);
                $brands = [];

                foreach ($rows as $row) {
                    $brand_id = $row['id'];
                    if (!isset($brands[$brand_id])) {
                        $brands[$brand_id] = (object) [
                            'id' => $brand_id,
                            'title' => $row['title'],
                            'slug' => $row['slug'],
                            'country' => $row['country'],
                            'logoUrl' => $row['logo_url'],
                            'description' => $row['description'],
                            'amount' => $row['amount'],
                            'isActive' => $row['is_active'],
                        ];
                    }
                }

                $response = array_values($brands);
                break;

            case 'update':
                $destPath = loadFile('brand');
                $id = filter_var($_POST['id'], FILTER_SANITIZE_SPECIAL_CHARS);
                $title = filter_var($_POST['title'], FILTER_SANITIZE_SPECIAL_CHARS);
                $slug = filter_var($_POST['slug'], FILTER_SANITIZE_SPECIAL_CHARS);
                $country = filter_var($_POST['country'], FILTER_SANITIZE_SPECIAL_CHARS);
                $description = filter_var($_POST['description'], FILTER_SANITIZE_SPECIAL_CHARS);
                $is_active = isset($_POST['is_active']) ? '1' : '0';
                $q = ' UPDATE brands b SET 
                        b.title = ?, 
                        b.slug = ?, 
                        b.country = ?, 
                        b.logo_url = ?, 
                        b.description = ?, 
                        b.is_active = ?

                        WHERE b.id = ?
                        ';

                $query = $pdo->prepare($q);
                $query->execute([$title, $slug, $country, $destPath, $description, $is_active, $id]);
                $response = [
                    'status' => 'success',
                    'message' => "Успешное обновление бренда"
                ];
                break;

            case 'add':
                $destPath = loadFile('brand');
                $title = filter_var($_POST['title'], FILTER_SANITIZE_SPECIAL_CHARS);
                $slug = filter_var($_POST['slug'], FILTER_SANITIZE_SPECIAL_CHARS);
                $country = filter_var($_POST['country'], FILTER_SANITIZE_SPECIAL_CHARS);
                $description = filter_var($_POST['description'], FILTER_SANITIZE_SPECIAL_CHARS);
                $is_active = isset($_POST['is_active']) ? '1' : '0';
                $q = ' INSERT INTO brands(
                           title, slug, country, logo_url, description, is_active)
                           VALUES(?, ?, ?, ?, ?, ?)';
                $query = $pdo->prepare($q);
                $query->execute([$title, $slug, $country, $destPath, $description, $is_active]);
                $response = [
                    'status' => 'success',
                    'message' => "Успешное добавление бренда"
                ];
                break;

            case 'remove':
                $id = filter_var($_POST['id'], FILTER_SANITIZE_SPECIAL_CHARS);
                $q = ' DELETE FROM brands WHERE id = ?';
                $query = $pdo->prepare($q);
                $query->execute([$id]);
                $response = [
                    'status' => 'success',
                    'message' => "Успешное удаление бренда"
                ];
                break;
        }
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }
}
