<?php

namespace App\Repositories;

use App\Core\BaseRepository;
use App\Models\Enum\UserRole;
use App\Models\User;

class UserRepository extends BaseRepository implements IUserRepository
{
    private const TABLE = 'user';
    private const PK = 'user_id';

    public function __construct()
    {
        parent::__construct();
    }

    public function findUserByLogin(string $login): ?User
    {
        try {
            $sql = "SELECT *
                    FROM " . self::TABLE . "
                    WHERE email = :value OR username = :value
                    LIMIT 1";

            $stmt = $this->getConnection()->prepare($sql);
            $stmt->execute([':value' => $login]);
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);

            return is_array($row) ? $this->hydrateUser($row) : null;
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to retrieve user. ' . $e->getMessage());
        }
    }

    public function findUserByEmail(string $email): ?User
    {
        try {
            $sql = "SELECT *
                    FROM " . self::TABLE . "
                    WHERE email = :email
                    LIMIT 1";

            $stmt = $this->getConnection()->prepare($sql);
            $stmt->execute([':email' => $email]);
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);

            return is_array($row) ? $this->hydrateUser($row) : null;
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

            return is_array($row) ? $this->hydrateUser($row) : null;
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to retrieve user. ' . $e->getMessage());
        }
    }

    public function createUser(User $user): void
    {
        try {
            $sql = "INSERT INTO " . self::TABLE . "
                    (email, username, password_hash, first_name, last_name, phone, role, created_at, updated_at)
                    VALUES
                    (:email, :username, :password_hash, :first_name, :last_name, :phone, :role, :created_at, :updated_at)";

            $stmt = $this->getConnection()->prepare($sql);
            $stmt->execute([
                'email' => $user->email,
                'username' => $user->username,
                'password_hash' => $user->password_hash,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'phone' => $user->phone,
                'role' => $user->role instanceof UserRole ? $user->role->value : (string) $user->role,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            $user->user_id = (int) $this->getConnection()->lastInsertId();
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to create user. ' . $e->getMessage());
        }
    }

    public function updateUser(User $user): void
    {
        try {
            $id = (int) ($user->user_id ?? 0);
            if ($id <= 0) {
                throw new \InvalidArgumentException('User id is required for update.');
            }

            $setPassword = ($user->password_hash !== '');

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
                ':email' => $user->email,
                ':username' => $user->username,
                ':first_name' => $user->first_name,
                ':last_name' => $user->last_name,
                ':role' => $user->role instanceof UserRole ? $user->role->value : (string) $user->role,
                ':id' => $id,
            ];

            if ($setPassword) {
                $params[':password_hash'] = $user->password_hash;
            }

            $stmt = $this->getConnection()->prepare($sql);
            $stmt->execute($params);
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to update user. ' . $e->getMessage());
        }
    }

    public function deleteUser(int $id): void
    {
        try {
            $sql = "DELETE FROM " . self::TABLE . " WHERE " . self::PK . " = :id";
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->execute([':id' => $id]);
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

            return array_map(fn(array $row): User => $this->hydrateUser($row), $rows);
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

            return array_map(fn(array $row): User => $this->hydrateUser($row), $rows);
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

            return array_map(fn(array $row): User => $this->hydrateUser($row), $rows);
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
            ':username' => $username,
        ]);

        return (bool) $stmt->fetchColumn();
    }

    public function findFiltered(string $role, string $search, string $sort): array
    {
        [$where, $params] = $this->buildFilter($role, $search);
        $sql = 'SELECT * FROM ' . self::TABLE . $where . ' ORDER BY ' . $this->resolveOrder($sort);
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return array_map(fn(array $row): User => $this->hydrateUser($row), $rows);
    }

    private function buildFilter(string $role, string $search): array
    {
        $clauses = [];
        $params = [];

        if ($role !== '') {
            $clauses[] = 'role = ?';
            $params[] = $role;
        }

        if ($search !== '') {
            $clauses[] = '(first_name LIKE ? OR last_name LIKE ? OR email LIKE ?)';
            $params = array_merge($params, ["%$search%", "%$search%", "%$search%"]);
        }

        return [$clauses ? ' WHERE ' . implode(' AND ', $clauses) : '', $params];
    }

    private function resolveOrder(string $sort): string
    {
        return match ($sort) {
            'name_asc' => 'first_name ASC, last_name ASC',
            'name_desc' => 'first_name DESC, last_name DESC',
            'date_asc' => 'created_at ASC',
            default => 'created_at DESC',
        };
    }

    private function hydrateUser(array $row): User
    {
        $role = UserRole::tryFrom((string) ($row['role'] ?? '')) ?? UserRole::customer;

        $user = new User(
            (string) ($row['username'] ?? ''),
            (string) ($row['email'] ?? ''),
            (string) ($row['password_hash'] ?? ''),
            (string) ($row['first_name'] ?? ''),
            (string) ($row['last_name'] ?? ''),
            isset($row['phone']) ? (string) $row['phone'] : null,
            $role
        );

        $user->user_id = isset($row['user_id']) ? (int) $row['user_id'] : null;
        $user->profile_image_id = isset($row['profile_image_id']) ? (int) $row['profile_image_id'] : null;
        $user->created_at = isset($row['created_at']) ? (string) $row['created_at'] : null;
        $user->updated_at = isset($row['updated_at']) ? (string) $row['updated_at'] : null;

        return $user;
    }
}
