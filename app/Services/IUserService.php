<?php

namespace App\Services;
use App\Models\User;
interface IUserService
{
    public function registerUser(User $user, string $plainPassword): User;
    public function authenticate(string $emailOrUsername, string $plainPassword): ?User;
    public function getUserById(int $id): ?User;
    public function getUserByEmail(string $email): ?User;
    public function getAllUsers(): array;
    public function updateUser(User $user): User;
    public function deleteUser(int $id): bool;
    public function listUsers(): array;
    public function userExists(string $email, string $username): bool;

    /**
     * @param string $role   Empty = all roles.
     * @param string $search Matched against name/email. Empty = no filter.
     * @param string $sort   date_desc|date_asc|name_asc|name_desc.
     * @return User[]
     */
    public function filterUsers(string $role, string $search, string $sort): array;
}