<?php

namespace App\Repositories;

use App\Core\BaseEntity;
use App\Models\User;
use App\Core\BaseRepository;
use App\Repositories\IUserRepository;
class UserRepository extends BaseRepository implements IUserRepository
{
    private const TABLE = 'user';
    private const PK    = 'user_id';

    public function __construct()
    {
        parent::__construct();
    }

    public function findUserByEmail(string $email): ?User
    {
        // Login requirement says "username OR e-mail", so we search both.
        try {
            $sql = "SELECT *
                    FROM " . self::TABLE . "
                    WHERE email = :value OR username = :value
                    LIMIT 1";

            $stmt = $this->getConnection()->prepare($sql);
            $stmt->execute([':value' => $email]);
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            if ($row) {
                return User::fromArray($row);
            }
            return null;
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to retrieve user. ' . $e->getMessage());
        }
    }

    public function findUserById(int $id): ?User
    {
        try {

            $sql = "SELECT *
                FROM " . self::TABLE . "
                WHERE " . self::PK . " = :id
                LIMIT 1";

            $stmt = $this->getConnection()->prepare($sql);
            $stmt->execute([':id' => $id]);
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);

            return $row ? User::fromArray($row) : null;
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to retrieve user. ' . $e->getMessage());
        }
    }

    public function createUser(User $user): User
    {
        try {
        $sql = "INSERT INTO " . self::TABLE . "
                (email, username, password_hash, first_name, last_name, role, created_at, updated_at)
                VALUES
                (:email, :username, :password_hash, :first_name, :last_name, :role, NOW(), NOW())";

        $data = $user->toArray();
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute([
            ':email' => $data['email'],
            ':username' => $data['username'],
            ':password_hash' => $data['password_hash'],
            ':first_name' => $data['first_name'],
            ':last_name' => $data['last_name'],
            ':role' => $data['role'] ?? null
        ]);

        $newId = (int) $this->getConnection()->lastInsertId();

        // Return freshly loaded user
        return $this->findUserById($newId) ?? $user;
        } 
        catch (\Exception $e) {
            throw new \RuntimeException('Failed to create user. ' . $e->getMessage());
        }
    }

    public function updateUser(User $user): User
    {
        try {
        $data = $user->toArray();
        $id = (int) ($data[self::PK] ?? 0);

        if ($id <= 0) {
            throw new \InvalidArgumentException("User id is required for update.");
        }

        // If password_hash is empty, don't overwrite it.
        $setPassword = (!empty($data['password_hash']));

        $sql = "UPDATE " . self::TABLE . "
                SET email = :email,
                    username = :username,
                    first_name = :first_name,
                    last_name = :last_name,
                    role = :role"
            . ($setPassword ? ", password_hash = :password_hash" : "")
            . ",
                    updated_at = NOW()
                WHERE " . self::PK . " = :id";

        $params = [
            ':email' => $data['email'],
            ':username' => $data['username'],
            ':first_name' => $data['first_name'],
            ':last_name' => $data['last_name'],
            ':role' => $data['role'],
            ':id' => $id,
        ];

        if ($setPassword) {
            $params[':password_hash'] = $data['password_hash'];
        }

        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute($params);

        return $this->findUserById($id) ?? $user;
        } 
        catch (\Exception $e) {
            throw new \RuntimeException('Failed to update user. ' . $e->getMessage());
        }
    }

    public function deleteUser(int $id): bool
    {
        try {
        $sql = "DELETE FROM " . self::TABLE . " WHERE " . self::PK . " = :id";
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->rowCount() > 0;
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to delete user. ' . $e->getMessage());
        }
    }

    public function findAllUsers(): array
    {
        try {
        $sql = "SELECT * FROM " . self::TABLE . " ORDER BY created_at DESC";
        $stmt = $this->getConnection()->query($sql);

        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return array_map(fn($row) => User::fromArray($row), $rows);
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to retrieve users. ' . $e->getMessage());
        }
    }

    public function findByRole(string $role): array
    {
        try {
        $sql = "SELECT *
                FROM " . self::TABLE . "
                WHERE role = :role
                ORDER BY created_at DESC";

        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute([':role' => $role]);

        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return array_map(fn($row) => User::fromArray($row), $rows);
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to retrieve users by role. ' . $e->getMessage());
        }
    }

    public function findByName(string $name): array
    {
        try {
        $sql = "SELECT *
                FROM " . self::TABLE . "
                WHERE first_name LIKE :q
                   OR last_name LIKE :q
                   OR CONCAT(first_name, ' ', last_name) LIKE :q
                ORDER BY last_name ASC, first_name ASC";

        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute([':q' => '%' . $name . '%']);

        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return array_map(fn($row) => User::fromArray($row), $rows);
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to retrieve users by name. ' . $e->getMessage());
        }
    }

    public function existsByEmailOrUsername(string $email, string $username): bool
    {
        $sql = "SELECT 1
            FROM " . self::TABLE . "
            WHERE email = :email OR username = :username
            LIMIT 1";

        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute([
            ':email' => $email,
            ':username' => $username
        ]);

        return (bool)$stmt->fetchColumn();
    }

    // ----------------------------
    // Helpers
    // ----------------------------


}
