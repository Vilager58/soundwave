<?php
require('db.php');


$params = [];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['type'])) {
        switch ($_POST['type']) {
            case 'get':
                $i = '';
                
                if ($_POST['query'] != 'null') {
                    $i .= ' AND (child.title LIKE :query OR child.slug LIKE :query) ';
                    $params['query'] = '%' . $_POST['query'] . '%';
                }
                if (isset($_POST['parent'])) {
                    $i .= ' AND child.parent_id IS NULL';
                };
                if($i != ''){
                    $i = 'WHERE 1=1' . $i;
                }

                $q = ' SELECT 
                        child.id,
                        child.title AS category_name, 
                        child.slug,
                        (SELECT COUNT(id) from products WHERE category_id = child.id) as amount,
                        parent.title AS parent_category_name,
                        parent.id AS parent_id,
                        child.is_active,
                        child.description
                    FROM categories child
                    LEFT JOIN categories parent ON child.parent_id = parent.id
                    '. $i . 
                    ' ORDER BY 
                        COALESCE(parent.title, child.title) ASC,
                        CASE WHEN child.parent_id IS NULL THEN 0 ELSE 1 END ASC,
                        child.title ASC
                    ';
                $query = $pdo->prepare($q);
                $query->execute($params);
                $rows = $query->fetchAll(PDO::FETCH_ASSOC);
                $categories = [];

                foreach ($rows as $row) {
                    $category_id = $row['id'];
                    if (!isset($categories[$category_id])) {
                        $categories[$category_id] = (object) [
                            'id'          => $category_id,
                            'title'       => $row['category_name'],
                            'slug'        => $row['slug'],
                            'parentTitle' => $row['parent_category_name'],
                            'parentId'    => $row['parent_id'],
                            'description' => $row['description'],
                            'amount'      => $row['amount'],
                            'isActive'    => $row['is_active'],
                        ];
                    }
                }

                $response = array_values($categories);
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