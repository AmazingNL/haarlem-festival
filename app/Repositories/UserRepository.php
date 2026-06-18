<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\BaseRepository;
use App\Models\Enum\UserRole;
use App\Models\User;

final class UserRepository extends BaseRepository implements IUserRepository
{
    private const TABLE = 'user';

    public function findUserByLogin(string $login): ?User
    {
        return $this->findOne(
            'email = :login OR username = :login',
            [':login' => trim($login)]
        );
    }

    public function findUserByEmail(string $email): ?User
    {
        return $this->findOne('email = :email', [':email' => trim($email)]);
    }

    public function findUserById(int $id): ?User
    {
        if ($id <= 0) {
            return null;
        }

        return $this->findOne('user_id = :id', [':id' => $id]);
    }

    public function createUser(User $user): void
    {
        $now = date('Y-m-d H:i:s');

        $this->execute(
            <<<SQL
                INSERT INTO `user` (
                    email, username, password_hash,
                    first_name, last_name, phone, role,
                    created_at, updated_at
                ) VALUES (
                    :email, :username, :password_hash,
                    :first_name, :last_name, :phone, :role,
                    :created_at, :updated_at
                )
            SQL,
            [
                ':email' => $user->email,
                ':username' => $user->username,
                ':password_hash' => $user->password_hash,
                ':first_name' => $user->first_name,
                ':last_name' => $user->last_name,
                ':phone' => $user->phone,
                ':role' => $this->roleValue($user->role),
                ':created_at' => $now,
                ':updated_at' => $now,
            ]
        );

        $user->user_id = (int) $this->getConnection()->lastInsertId();
    }

    public function existsByEmailOrUsername(string $email, string $username): bool
    {
        return $this->exists(
            'email = :email OR username = :username',
            [
                ':email' => trim($email),
                ':username' => trim($username),
            ]
        );
    }

    public function updateUser(User $user): void
    {
        $id = (int) ($user->user_id ?? 0);
        if ($id <= 0) {
            throw new \InvalidArgumentException('User id is required for update.');
        }

        $params = [
            ':email' => $user->email,
            ':username' => $user->username,
            ':first_name' => $user->first_name,
            ':last_name' => $user->last_name,
            ':role' => $this->roleValue($user->role),
            ':id' => $id,
        ];

        $passwordSql = '';
        if ($user->password_hash !== '') {
            $passwordSql = ', password_hash = :password_hash';
            $params[':password_hash'] = $user->password_hash;
        }

        $this->execute(
            <<<SQL
                UPDATE `user`
                SET email = :email,
                    username = :username,
                    first_name = :first_name,
                    last_name = :last_name,
                    role = :role
                    {$passwordSql},
                    updated_at = NOW()
                WHERE user_id = :id
            SQL,
            $params
        );
    }

    public function deleteUser(int $id): void
    {
        if ($id <= 0) {
            return;
        }

        $this->execute('DELETE FROM `user` WHERE user_id = :id', [':id' => $id]);
    }

    public function findAllUsers(): array
    {
        return $this->findMany('SELECT * FROM `user` ORDER BY created_at DESC');
    }

    public function findFiltered(string $role, string $search, string $sort): array
    {
        [$whereSql, $params] = $this->buildFilter($role, $search);

        return $this->findMany(
            'SELECT * FROM `user`' . $whereSql . ' ORDER BY ' . $this->resolveOrder($sort),
            $params
        );
    }

    private function findOne(string $where, array $params): ?User
    {
        $sql = 'SELECT * FROM `' . self::TABLE . '` WHERE ' . $where . ' LIMIT 1';
        $statement = $this->getConnection()->prepare($sql);
        $statement->execute($params);
        $row = $statement->fetch(\PDO::FETCH_ASSOC);

        return is_array($row) ? $this->hydrateUser($row) : null;
    }

    /** @return list<User> */
    private function findMany(string $sql, array $params = []): array
    {
        $statement = $this->getConnection()->prepare($sql);
        $statement->execute($params);
        $rows = $statement->fetchAll(\PDO::FETCH_ASSOC);

        return array_map(fn(array $row): User => $this->hydrateUser($row), $rows);
    }

    private function exists(string $where, array $params): bool
    {
        $sql = 'SELECT 1 FROM `' . self::TABLE . '` WHERE ' . $where . ' LIMIT 1';
        $statement = $this->getConnection()->prepare($sql);
        $statement->execute($params);

        return (bool) $statement->fetchColumn();
    }

    private function execute(string $sql, array $params = []): void
    {
        $statement = $this->getConnection()->prepare($sql);
        $statement->execute($params);
    }

    private function roleValue(UserRole|string $role): string
    {
        return $role instanceof UserRole ? $role->value : strtolower((string) $role);
    }

    /** @return array{0: string, 1: array<int|string, mixed>} */
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
            $like = '%' . $search . '%';
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
        }

        return [$clauses === [] ? '' : ' WHERE ' . implode(' AND ', $clauses), $params];
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
