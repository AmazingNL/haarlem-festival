<?php

declare(strict_types=1);

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
     * Update a user's profile and optionally their password.
     *
     * @param User   $user          The user with updated fields.
     * @param string $plainPassword New password, or empty string to keep existing.
     * @return User The updated user.
     */
    public function updateUserAdmin(User $user, string $plainPassword): User;

    /**
     * Return all users filtered by role/search and sorted.
     *
     * @param string $role   Empty = all roles.
     * @param string $search Matched against name/email. Empty = no filter.
     * @param string $sort   date_desc|date_asc|name_asc|name_desc.
     * @return User[]
     */
    public function filterUsers(string $role, string $search, string $sort): array;
}
