<?php

namespace App\Models\Category;

require_once __DIR__ . '/../../../vendor/autoload.php';

use App\Models\Category;
use App\Models\Database;
use Exception;
use PDO;

class CategoryRepository
{
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    /**
     * Получить категории
     *
     * @param string|null $query Поисковый запрос
     * @param bool $parentOnly Только корневые категории
     * @return Category[]
     * @throws \Throwable
     */
    public function getAll(?string $query = null, bool $parentOnly = false): array
    {
        return $this->db->transaction(function (PDO $pdo) use ($query, $parentOnly) {
            $params = [];
            $where = 'WHERE 1=1';

            if (!empty($query) && $query !== 'null') {
                $where .= ' AND (child.title LIKE :query OR child.slug LIKE :query)';
                $params['query'] = '%' . $query . '%';
            }

            if ($parentOnly) {
                $where .= ' AND child.parent_id IS NULL';
            }

            $sql = 'SELECT 
                    child.id,
                    child.title AS category_name, 
                    child.slug,
                    (SELECT COUNT(id) FROM products WHERE category_id = child.id) AS amount,
                    parent.title AS parent_category_name,
                    parent.id AS parent_id,
                    child.is_active,
                    child.description
                FROM categories child
                LEFT JOIN categories parent ON child.parent_id = parent.id
                ' . $where . '
                ORDER BY 
                    COALESCE(parent.title, child.title) ASC,
                    CASE WHEN child.parent_id IS NULL THEN 0 ELSE 1 END ASC,
                    child.title ASC';

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $result = [];
            foreach ($rows as $row) {
                $result[] = new Category(
                    id: (int) $row['id'],
                    title: (string) $row['category_name'],
                    slug: (string) $row['slug'],
                    parentId: !empty($row['parent_id']) ? (int) $row['parent_id'] : null,
                    parentTitle: $row['parent_category_name'] ?? null,
                    description: $row['description'] ?? null,
                    isActive: $row['is_active'] == '1',
                    amount: (int) $row['amount']
                );
            }

            return $result;
        });
    }

    /**
     * Получить категорию по ID
     *
     * @throws \Throwable
     */
    public function getById(int $id): ?Category
    {
        return $this->db->transaction(function (PDO $pdo) use ($id) {
            $sql = 'SELECT 
                    child.id,
                    child.title AS category_name, 
                    child.slug,
                    (SELECT COUNT(id) FROM products WHERE category_id = child.id) AS amount,
                    parent.title AS parent_category_name,
                    parent.id AS parent_id,
                    child.is_active,
                    child.description
                FROM categories child
                LEFT JOIN categories parent ON child.parent_id = parent.id
                WHERE child.id = ?';

            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$row) {
                throw new Exception("Категория не найдена");
            }

            return new Category(
                id: (int) $row['id'],
                title: (string) $row['category_name'],
                slug: (string) $row['slug'],
                parentId: !empty($row['parent_id']) ? (int) $row['parent_id'] : null,
                parentTitle: $row['parent_category_name'] ?? null,
                description: $row['description'] ?? null,
                isActive: $row['is_active'] == '1',
                amount: (int) $row['amount']
            );
        });
    }

    /**
     * Создать категорию
     *
     * @throws \Throwable
     */
    public function create(array $data): int
    {
        return $this->db->transaction(function (PDO $pdo) use ($data) {
            $title = (string) ($data['title'] ?? '');
            $slug = (string) ($data['slug'] ?? '');
            $parentId = !empty($data['parentCategoryId']) || !empty($data['parentId']) || !empty($data['id'])
                ? (int) ($data['parentCategoryId'] ?? $data['parentId'] ?? $data['id'])
                : null;
            if ($parentId === 0) {
                $parentId = null;
            }
            $description = $data['description'] ?? null;
            $isActive = isset($data['is_active']) ? ($data['is_active'] == '1' ? 1 : 0) : 1;

            $stmt = $pdo->prepare('INSERT INTO categories (title, slug, parent_id, description, is_active) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$title, $slug, $parentId, $description, $isActive]);

            return (int) $pdo->lastInsertId();
        });
    }

    /**
     * Обновить категорию
     *
     * @throws \Throwable
     */
    public function update(int $id, array $data): bool
    {
        return $this->db->transaction(function (PDO $pdo) use ($id, $data) {
            $title = (string) ($data['title'] ?? '');
            $slug = (string) ($data['slug'] ?? '');
            $parentId = isset($data['parentCategoryId'])
                ? ($data['parentCategoryId'] == 0 ? null : (int) $data['parentCategoryId'])
                : (isset($data['parentId']) ? ($data['parentId'] == 0 ? null : (int) $data['parentId']) : null);
            $description = $data['description'] ?? null;
            $isActive = isset($data['is_active']) ? ($data['is_active'] == '1' ? 1 : 0) : 1;

            $stmt = $pdo->prepare('UPDATE categories SET title = ?, slug = ?, parent_id = ?, description = ?, is_active = ? WHERE id = ?');
            return $stmt->execute([$title, $slug, $parentId, $description, $isActive, $id]);
        });
    }

    /**
     * Удалить категорию
     *
     * @throws \Throwable
     */
    public function delete(int $id): bool
    {
        return $this->db->transaction(function (PDO $pdo) use ($id) {
            $stmt = $pdo->prepare('DELETE FROM categories WHERE id = ?');
            return $stmt->execute([$id]);
        });
    }
}
