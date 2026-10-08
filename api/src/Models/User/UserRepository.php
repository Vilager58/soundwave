<?php
namespace App\Models\User;
require_once __DIR__ . '/../../../vendor/autoload.php';

use Exception;
use App\Models\Database;
use App\Models\User;
use PDO;
class UserRepository{
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }
    public function getUserByEmail(string $email) : User {
        return $this->db->transaction(function (PDO $pdo) use ($email) {
            $emailQuery = ' SELECT * FROM users WHERE email = ?';
            $query = $pdo->prepare($emailQuery);
            $query->execute([$email]);

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
            );

        });

    }
    public function getUserByPhone(string $phone) {}
    public function getUserById(string $id) : User {
        return $this->db->transaction(function (PDO $pdo) use ($id) {
            $idQuery = ' SELECT * FROM users WHERE id = ?';
            $query = $pdo->prepare($idQuery);
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
            );

        });

    }
    public function getPasswordHash(string $id) : string {
        return $this->db->transaction(function (PDO $pdo) use ($id) {
            $passwordQuery = ' SELECT password_hash FROM users WHERE id = ?';
            $query = $pdo->prepare($passwordQuery);
            $query->execute([$id]);

            if ($query->rowCount() == 0) {
                throw new Exception("Пользователь не найден");
            }

            $query = $query->fetch(PDO::FETCH_ASSOC);
            return $query['password_hash'];

        });
    }
    public function updateUser(User $user) {}
    public function createUser(User $user) {}
    public function deleteUser(User $user) {}
    public function updateRole(){}
    public function changePassword(User $user){}
    public function activate(User $user){}
    public function deactivate(User $user){}

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
