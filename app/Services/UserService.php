<?php

namespace App\Services;

use App\Models\User;
use App\Models\Enum\UserRole;
use App\Repositories\IUserRepository;

final class UserService implements IUserService
{
    private IUserRepository $userRepo;

    // Inject the user repository that talks to the database.
    public function __construct(IUserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    // Hash the plain password before the new user is saved.
    public function registerUser(User $user, string $plainPassword): void
    {
        $user->password_hash = password_hash($plainPassword, PASSWORD_DEFAULT);
        $this->userRepo->createUser($user);
    }

    // Load the user by email or username and verify the entered password against the stored hash.
    public function authenticate(string $emailOrUsername, string $plainPassword): ?User
    {
        $user = $this->userRepo->findUserByLogin($emailOrUsername);
        if ($user && password_verify($plainPassword, $user->password_hash)) {
            if (is_string($user->role)) {
                $user->role = UserRole::tryFrom(strtolower($user->role)) ?? UserRole::customer;
            }
            return $user;
        }
        return null;
    }

    // Return one user by id.
    public function getUserById(int $id): ?User
    {
        return $this->userRepo->findUserById($id);
    }

    // Save normal profile changes for a user.
    public function updateUser(User $user): void
    {
        $this->userRepo->updateUser($user);
    }

    // Return all users.
    public function getAllUsers(): array
    {
        return $this->userRepo->findAllUsers();
    }

    // Return one user by email address.
    public function getUserByEmail(string $email): ?User
    {
        return $this->userRepo->findUserByEmail($email);
    }

    // Delete one user by id.
    public function deleteUser(int $id): void
    {
        $this->userRepo->deleteUser($id);
    }

    // Alias for listing all users in the admin area.
    public function listUsers(): array
    {
        return $this->userRepo->findAllUsers();
    }

    // Check if the email or username is already taken.
    public function userExists(string $email, string $username): bool
    {
        return $this->userRepo->existsByEmailOrUsername($email, $username);
    }

    /**
     * Update a user; if $plainPassword is non-empty, replace their password.
     *
     * @param User   $user          User with updated fields.
     * @param string $plainPassword New plain-text password or empty string.
     * @return User The updated user.
     */
    // Update an admin-managed user and re-hash the password if a new one was entered.
    public function updateUserAdmin(User $user, string $plainPassword): User
    {
        $user->password_hash = $plainPassword !== ''
            ? password_hash($plainPassword, PASSWORD_DEFAULT)
            : '';
        return $this->userRepo->updateUser($user);
    }

    /**
     * Return all users filtered by role/search and sorted.
     *
     * @param string $role   Empty = all roles.
     * @param string $search Matched against name/email. Empty = no filter.
     * @param string $sort   date_desc|date_asc|name_asc|name_desc.
     * @return User[]
     */
    // Filter and sort the user list for the admin overview.
    public function filterUsers(string $role, string $search, string $sort): array
    {
        $users = $this->userRepo->findAllUsers();

        if ($role !== '') {
            $users = array_filter(
                $users,
                fn(User $u) =>
                ($u->role instanceof UserRole ? $u->role->value : (string) $u->role) === $role
            );
        }

        if ($search !== '') {
            $q = strtolower($search);
            $users = array_filter(
                $users,
                fn(User $u) =>
                str_contains(strtolower($u->first_name . ' ' . $u->last_name), $q)
                || str_contains(strtolower($u->email), $q)
            );
        }

        $users = array_values($users);

        usort($users, match ($sort) {
            'date_asc' => fn($a, $b) => strcmp($a->created_at ?? '', $b->created_at ?? ''),
            'name_asc' => fn($a, $b) => strcmp(($a->first_name ?? '') . ($a->last_name ?? ''), ($b->first_name ?? '') . ($b->last_name ?? '')),
            'name_desc' => fn($a, $b) => strcmp(($b->first_name ?? '') . ($b->last_name ?? ''), ($a->first_name ?? '') . ($a->last_name ?? '')),
            default => fn($a, $b) => strcmp($b->created_at ?? '', $a->created_at ?? ''),
        });

        return $users;
    }
}
