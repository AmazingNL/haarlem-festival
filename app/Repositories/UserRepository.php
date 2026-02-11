<?php

namespace App\Repositories;
use App\Models\User;

class UserRepository implements IUserRepository
{
    protected \PDO $db;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }
    // David
   public function findUserByEmail(string $identifier): ?User
    {
        $stmt = $this->db->prepare("SELECT * FROM user WHERE email = :id OR username = :id LIMIT 1");
        $stmt->execute(['id' => $identifier]);

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return User::fromArray($row);
    }

//

    public function findUserById(int $id): ?User
    {
        // Implement database logic to find a user by ID
        return null; // Placeholder return
    }

    public function createUser(User $user): User
    {
        // Implement database logic to create a new user
        return $user; // Placeholder return
    }

    public function updateUser(User $user): User
    {
        // Implement database logic to update an existing user
        return $user; // Placeholder return
    }

    public function deleteUser(int $id): bool
    {
        // Implement database logic to delete a user by ID
        return true; // Placeholder return
    }

    public function findAllUsers(): array
    {
        // Implement database logic to retrieve all users
        return []; // Placeholder return
    }

    public function findByRole(string $role): array
    {
        // Implement database logic to find users by role
        return []; // Placeholder return
    }

    public function findByName(string $name): array
    {
        // Implement database logic to find users by name
        return []; // Placeholder return
    }
}