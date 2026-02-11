<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\User;

interface IUserService
{
    public function registerUser(User $user, string $plainPassword): User;
    public function authenticate(string $emailOrUsername, string $plainPassword): ?User;
    public function getUserById(int $id): ?User;
    public function updateUser(User $user): User;
    public function deleteUser(int $id): bool;
    public function listUsers(): array;
}
