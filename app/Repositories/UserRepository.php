<?php

namespace App\Repositories;

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
        $sql = "SELECT *
                FROM " . self::TABLE . "
                WHERE email = :value OR username = :value
                LIMIT 1";

        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute([':value' => $email]);
        $row = $stmt->fetch();
        return $row ? $this->rowToUser($row[0]) : null;
    }

    public function findUserById(int $id): ?User
    {
        $sql = "SELECT *
                FROM " . self::TABLE . "
                WHERE " . self::PK . " = :id
                LIMIT 1";

        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();

        return $row ? $this->rowToUser($row[0] ) : null;
    }

    public function createUser(User $user): User
    {
        // Expect password already hashed BEFORE calling this (service layer).
        $sql = "INSERT INTO " . self::TABLE . "
                (email, username, password_hash, first_name, last_name, role, created_at, updated_at)
                VALUES
                (:email, :username, :password_hash, :first_name, :last_name, :role, NOW(), NOW())";

        $data = $this->userToDbArray($user);

        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute([
            ':email'         => $data['email'],
            ':username'      => $data['username'],
            ':password_hash' => $data['password_hash'],
            ':first_name'    => $data['first_name'],
            ':last_name'     => $data['last_name'],
            ':role'          => $data['role'],
        ]);

        $newId = (int)$this->getConnection()->lastInsertId();

        // Return freshly loaded user
        return $this->findUserById($newId) ?? $user;
    }

    public function updateUser(User $user): User
    {
        $data = $this->userToDbArray($user);
        $id = (int)($data[self::PK] ?? 0);

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
            ':email'      => $data['email'],
            ':username'   => $data['username'],
            ':first_name' => $data['first_name'],
            ':last_name'  => $data['last_name'],
            ':role'       => $data['role'],
            ':id'         => $id,
        ];

        if ($setPassword) {
            $params[':password_hash'] = $data['password_hash'];
        }

        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute($params);

        return $this->findUserById($id) ?? $user;
    }

    public function deleteUser(int $id): bool
    {
        $sql = "DELETE FROM " . self::TABLE . " WHERE " . self::PK . " = :id";
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->rowCount() > 0;
    }

    public function findAllUsers(): array
    {
        $sql = "SELECT * FROM " . self::TABLE . " ORDER BY created_at DESC";
        $stmt = $this->getConnection()->query($sql);

        $rows = $stmt->fetch();
        return array_map([$this, 'rowToUser'], $rows);
    }

    public function findByRole(string $role): array
    {
        $sql = "SELECT *
                FROM " . self::TABLE . "
                WHERE role = :role
                ORDER BY created_at DESC";

        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute([':role' => $role]);

        $rows = $stmt->fetch();
        return array_map([$this, 'rowToUser'], $rows);
    }

    public function findByName(string $name): array
    {
        // Simple search across first+last; adjust if your model differs.
        $sql = "SELECT *
                FROM " . self::TABLE . "
                WHERE first_name LIKE :q
                   OR last_name LIKE :q
                   OR CONCAT(first_name, ' ', last_name) LIKE :q
                ORDER BY last_name ASC, first_name ASC";

        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute([':q' => '%' . $name . '%']);

        $rows = $stmt->fetch();
        return array_map([$this, 'rowToUser'], $rows);
    }

    // ----------------------------
    // Helpers
    // ----------------------------

    /** @param array<string,mixed> $row */
    private function rowToUser(array $row): User
    {
        // If User extends BaseEntity, this exists and is perfect.
        if (method_exists(User::class, 'fromArray')) {
            /** @var User $user */
            $user = User::fromArray($row);
            return $user;
        }

        // Fallback: best-effort mapping
        $user = new User();
        foreach ($row as $k => $v) {
            if (property_exists($user, $k)) {
                $user->$k = $v;
            }
        }
        return $user;
    }

    /** @return array<string,mixed> */
    private function userToDbArray(User $user): array
    {
        // If User extends BaseEntity, this exists.
        if (method_exists($user, 'toArray')) {
            return $user->toArray();
        }

        // Fallback
        return get_object_vars($user);
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
}
