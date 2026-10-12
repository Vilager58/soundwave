<?php

namespace App\Models\Attribute;

use Exception;
use App\Models\Database;
use App\Models\Attribute;
use PDO;

class AttributeRepository
{
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    /**
     * Получить все характеристики
     *
     * @return Attribute[]
     * @throws \Throwable
     */
    public function getAll(?string $query = null): array
    {
        return $this->db->transaction(function (PDO $pdo) use ($query) {
            $params = [];
            $sql = "SELECT id, code, title, unit, data_type, is_filterable FROM attributes WHERE 1=1";

            if (!empty($query) && $query !== 'null') {
                $sql .= " AND (title LIKE :query OR code LIKE :query)";
                $params['query'] = '%' . $query . '%';
            }

            $sql .= " ORDER BY title ASC";

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $result = [];
            foreach ($rows as $row) {
                $result[] = new Attribute(
                    id: (int) $row['id'],
                    code: (string) $row['code'],
                    title: (string) $row['title'],
                    unit: $row['unit'] ?? null,
                    dataType: (string) ($row['data_type'] ?? 'string'),
                    isFilterable: (bool) ($row['is_filterable'] == '1')
                );
            }

            return $result;
        });
    }

    /**
     * Получить характеристику по ID
     *
     * @throws \Throwable
     */
    public function getById(int $id): ?Attribute
    {
        return $this->db->transaction(function (PDO $pdo) use ($id) {
            $stmt = $pdo->prepare("SELECT id, code, title, unit, data_type, is_filterable FROM attributes WHERE id = ?");
            $stmt->execute([$id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$row) {
                throw new Exception("Характеристика не найдена");
            }

            return new Attribute(
                id: (int) $row['id'],
                code: (string) $row['code'],
                title: (string) $row['title'],
                unit: $row['unit'] ?? null,
                dataType: (string) ($row['data_type'] ?? 'string'),
                isFilterable: (bool) ($row['is_filterable'] == '1')
            );
        });
    }

    /**
     * Создать характеристику
     *
     * @throws \Throwable
     */
    public function create(array $data): int
    {
        return $this->db->transaction(function (PDO $pdo) use ($data) {
            $code = (string) ($data['code'] ?? '');
            $title = (string) ($data['title'] ?? '');
            $unit = !empty($data['unit']) ? (string) $data['unit'] : null;
            $dataType = in_array($data['data_type'] ?? '', ['string', 'number', 'boolean']) ? $data['data_type'] : 'string';
            $isFilterable = isset($data['is_filterable']) && $data['is_filterable'] == '1' ? 1 : (isset($data['is_filterable']) && $data['is_filterable'] == '0' ? 0 : 1);

            $stmt = $pdo->prepare("
                INSERT INTO attributes (code, title, unit, data_type, is_filterable)
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([$code, $title, $unit, $dataType, $isFilterable]);

            return (int) $pdo->lastInsertId();
        });
    }

    /**
     * Обновить характеристику
     *
     * @throws \Throwable
     */
    public function update(int $id, array $data): bool
    {
        return $this->db->transaction(function (PDO $pdo) use ($id, $data) {
            $code = (string) ($data['code'] ?? '');
            $title = (string) ($data['title'] ?? '');
            $unit = !empty($data['unit']) ? (string) $data['unit'] : null;
            $dataType = in_array($data['data_type'] ?? '', ['string', 'number', 'boolean']) ? $data['data_type'] : 'string';
            $isFilterable = isset($data['is_filterable']) && $data['is_filterable'] == '1' ? 1 : (isset($data['is_filterable']) && $data['is_filterable'] == '0' ? 0 : 1);

            $stmt = $pdo->prepare("
                UPDATE attributes
                SET code = ?, title = ?, unit = ?, data_type = ?, is_filterable = ?
                WHERE id = ?
            ");
            return $stmt->execute([$code, $title, $unit, $dataType, $isFilterable, $id]);
        });
    }

    /**
     * Удалить характеристику
     *
     * @throws \Throwable
     */
    public function delete(int $id): bool
    {
        return $this->db->transaction(function (PDO $pdo) use ($id) {
            $stmt = $pdo->prepare("DELETE FROM attributes WHERE id = ?");
            return $stmt->execute([$id]);
        });
    }
}
