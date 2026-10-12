<?php
namespace App\Models\User;
require_once __DIR__ . '/../../../vendor/autoload.php';

use Exception;
use App\Models\Database;
use App\Models\User;


use PDO;
use Throwable;

class UserRepository{
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    /**
     * @param PDO $pdo
     * @param string $sql
     * @param string $id
     * @return User
     * @throws Exception
     */
    public function getUser(PDO $pdo, string $sql, string $id): User
    {
        $query = $pdo->prepare($sql);
        $query->execute([$id]);

        if ($query->rowCount() == 0) {
            throw new Exception("Пользователь не найден");
        }

        $query = $query->fetch(PDO::FETCH_ASSOC);
        return new User(
            $query['id'],
            $query['firstname'],
            $query['lastname'],
            $query['patronymic'],
            $query['phone'],
            $query['birthdate'],
            $query['is_active'] == '1',
            $query['email'],
            $query['role_id'],
            $query['bonus_balance'],
            $query['password_hash'],
            $query['instrument'],
            $query['city'],
        );
    }

    /**
     * @throws Throwable
     */
    public function getUserByEmail(string $email) : User {
        return $this->db->transaction(function (PDO $pdo) use ($email) {
            $emailQuery = ' SELECT * FROM users WHERE email = ?';
            return $this->getUser($pdo, $emailQuery, $email);
        });

    }

    /**
     * @throws Throwable
     */
    public function getUserByPhone(string $phone) {
        return $this->db->transaction(function (PDO $pdo) use ($phone) {
            $phoneQuery = ' SELECT * FROM users WHERE phone LIKE ?';
            return $this->getUser($pdo, $phoneQuery, $phone);
        });
    }

    /**
     * @throws Throwable
     */
    public function checkUserByEmailAndPhone(string $email, string $phone) : bool
    {
        return $this->db->transaction(function (PDO $pdo) use ($email, $phone) {
            $emailQuery = ' SELECT * FROM users WHERE email = ? or phone = ?';
            $query = $pdo->prepare($emailQuery);
            $query->execute([$email, $phone]);

            if ($query->rowCount() == 0) {
                return false;
            }
            return true;
        });
    }

    /**
     * @throws Throwable
     */
    public function getUserById(string $id) : User {
        return $this->db->transaction(function (PDO $pdo) use ($id) {
            $idQuery = ' SELECT * FROM users WHERE id = ?';
            return $this->getUser($pdo, $idQuery, $id);
        });
    }
    public function updateUser(User $user): bool {
        return $this->db->transaction(function (PDO $pdo) use ($user) {
            $userQuery = 'UPDATE users SET 
                firstname = ?, 
                lastname = ?, 
                patronymic = ?, 
                phone = ?, 
                birthdate = ?, 
                instrument = ?, 
                city = ?,
                adress = ?
                WHERE id = ?';
            $query = $pdo->prepare($userQuery);
            return $query->execute([
                $user->getFirstName(),
                $user->getLastName(),
                $user->getPatronymic(),
                $user->getPhone(),
                $user->getBirthdate() ?: null,
                $user->getInstrument(),
                $user->getCity(),
                $user->getAddress(),
                $user->getId()
            ]);
        });
    }

    /**
     * @throws Throwable
     */
    public function createUser(User $user): bool {
        return $this->db->transaction(function (PDO $pdo) use ($user) {
            $userQuery = 'INSERT INTO users(firstname, lastname, patronymic, email, phone, password_hash) VALUES(?, ?, ?, ?, ?, ?)';
            $query = $pdo -> prepare($userQuery);
            $query -> execute([
                $user->getFirstName(),
                $user->getLastName(),
                $user->getPatronymic(),
                $user->getEmail(),
                $user->getPhone(),
                $user->getPasswordHash()]);
            return true;
        });
    }

    public function deleteUser(User $user): bool {
        return $this->db->transaction(function (PDO $pdo) use ($user) {
            $query = $pdo->prepare('DELETE FROM users WHERE id = ?');
            return $query->execute([$user->getId()]);
        });
    }

    public function updateRole(int $userId, int $roleId): bool {
        return $this->db->transaction(function (PDO $pdo) use ($userId, $roleId) {
            $query = $pdo->prepare('UPDATE users SET role_id = ? WHERE id = ?');
            return $query->execute([$roleId, $userId]);
        });
    }

    public function changePassword(User $user, string $newPassword = ''): bool {
        return $this->db->transaction(function (PDO $pdo) use ($user, $newPassword) {
            $hash = !empty($newPassword) ? password_hash($newPassword, PASSWORD_DEFAULT) : $user->getPasswordHash();
            $query = $pdo->prepare('UPDATE users SET password_hash = ? WHERE id = ?');
            return $query->execute([$hash, $user->getId()]);
        });
    }

    public function activate(User $user): bool {
        return $this->db->transaction(function (PDO $pdo) use ($user) {
            $query = $pdo->prepare('UPDATE users SET is_active = 1 WHERE id = ?');
            return $query->execute([$user->getId()]);
        });
    }

    public function deactivate(User $user): bool {
        return $this->db->transaction(function (PDO $pdo) use ($user) {
            $query = $pdo->prepare('UPDATE users SET is_active = 0 WHERE id = ?');
            return $query->execute([$user->getId()]);
        });
    }

    public function getCountHandledItems(int $id): array {
        return $this->db->transaction(function (PDO $pdo) use ($id) {
            $q1 = $pdo->prepare('SELECT COALESCE(SUM(amount), 0) AS cart_count FROM products_cart WHERE user_id = ?');
            $q1->execute([$id]);
            $cartCount = (int) $q1->fetchColumn();

            $q2 = $pdo->prepare('SELECT COUNT(*) AS fav_count FROM products_favorites WHERE user_id = ?');
            $q2->execute([$id]);
            $favCount = (int) $q2->fetchColumn();

            return [
                'cartCount' => $cartCount,
                'favCount' => $favCount
            ];
        });
    }

    public function getPermissions(string $id) : array
    {
        return $this->db->transaction(function (PDO $pdo) use ($id) {
            $permsQuery = ' SELECT 
                        p.code
                        FROM
                        permissions p
                        JOIN role_permissions rp ON p.id = rp.permission_id
                        JOIN users u ON rp.role_id = u.role_id
                        
                        WHERE u.id = ?';
            $query = $pdo->prepare($permsQuery);
            $query->execute([$id]);
            $perms = $query->fetchAll(PDO::FETCH_ASSOC);
            $pa = [];
            foreach ($perms as $perm) {
                $pa[] = $perm['code'];
            }
            return $pa;
        });




    }
}
