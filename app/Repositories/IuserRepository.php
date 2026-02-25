<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;

interface IUserRepository
{
    public function findUserByEmail(string $identifier): ?User;
    public function findUserById(int $id): ?User;
    public function createUser(User $user): User;
    public function updateUser(User $user): User;
    public function deleteUser(int $id): bool;
    public function findAllUsers(): array;
    public function findByRole(string $role): array;
    public function findByName(string $name): array;
    public function existsByEmailOrUsername(string $email, string $username): bool;
}
