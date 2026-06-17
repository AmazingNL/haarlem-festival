<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;

interface IUserRepository
{
    public function findUserByLogin(string $login): ?User;
    public function findUserByEmail(string $email): ?User;
    public function findUserById(int $id): ?User;
    public function createUser(User $user): void;
    public function updateUser(User $user): void;
    public function deleteUser(int $id): void;
    public function findAllUsers(): array;
    public function existsByEmailOrUsername(string $email, string $username): bool;
    public function findFiltered(string $role, string $search, string $sort): array;
}
