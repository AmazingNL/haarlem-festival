<?php

declare(strict_types=1);

namespace App\Services\Interfaces;

use App\Models\User;

interface IUserService
{
    public function registerUser(User $user, string $plainPassword): void;
    public function authenticate(string $emailOrUsername, string $plainPassword): ?User;
    public function getUserById(int $id): ?User;
    public function getUserByEmail(string $email): ?User;
    public function getAllUsers(): array;
    public function updateUser(User $user): void;
    public function deleteUser(int $id): void;
    public function userExists(string $email, string $username): bool;
    public function updateUserAdmin(User $user, string $plainPassword): User;
    public function filterUsers(string $role, string $search, string $sort): array;
}
