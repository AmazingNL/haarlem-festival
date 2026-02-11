<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;
use PDO;

final class UserRepository implements IUserRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function findUserByEmail(string $identifier): ?User
    {
        // NOTE: backticks around `user` because "user" can be reserved in MySQL
        $stmt = $this->db->prepare(
            "SELECT * FROM `user` WHERE email = :id OR username = :id LIMIT 1"
        );

        $stmt->execute(['id' => $identifier]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return User::fromArray($row);
    }

    public function findUserById(int $id): ?User
    {
        // TODO: implement properly
        return null;
    }

    public function createUser(User $user): User
    {
        // TODO: implement properly
        return $user;
    }

    public function updateUser(User $user): User
    {
        // TODO: implement properly
        return $user;
    }

    public function deleteUser(int $id): bool
    {
        // TODO: implement properly
        return true;
    }

    public function findAllUsers(): array
    {
        // TODO: implement properly
        return [];
    }

    public function findByRole(string $role): array
    {
        // TODO: implement properly
        return [];
    }

    public function findByName(string $name): array
    {
        // TODO: implement properly
        return [];
    }
}
