<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;

interface IUserRepository
{
    public function findUserByEmail(string $email): ?User;
    public function findUserById(int $id): ?User;
    public function createUser(User $user): void;
    public function updateUser(User $user): User;
    public function deleteUser(int $id): bool;
    public function findAllUsers(): array;
    public function findByRole(string $role): array;
    public function findByName(string $name): array;
    public function existsByEmailOrUsername(string $email, string $username): bool;

    /**
     * @param string $role   Empty string = no role filter.
     * @param string $search Empty string = no name/email filter.
     * @param string $sort   One of date_desc|date_asc|name_asc|name_desc.
     * @return User[]
     */
    public function findFiltered(string $role, string $search, string $sort): array;
}
