<?php

declare(strict_types=1);

namespace App\Services\Implementations;

use App\Services\Interfaces\IUserService;

use App\Models\User;
use App\Models\Enum\UserRole;
use App\Repositories\IUserRepository;

final class UserService implements IUserService
{
    private IUserRepository $userRepo;

    public function __construct(IUserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function registerUser(User $user, string $plainPassword): void
    {
        $user->password_hash = password_hash($plainPassword, PASSWORD_DEFAULT);
        $this->userRepo->createUser($user);
    }

    public function authenticate(string $emailOrUsername, string $plainPassword): ?User
    {
        $user = $this->userRepo->findUserByLogin($emailOrUsername);
        if ($user === null || !password_verify($plainPassword, $user->password_hash)) {
            return null;
        }

        if (is_string($user->role)) {
            $user->role = UserRole::tryFrom(strtolower($user->role)) ?? UserRole::customer;
        }

        return $user;
    }

    public function getUserById(int $id): ?User
    {
        return $this->userRepo->findUserById($id);
    }

    public function updateUser(User $user): void
    {
        $this->userRepo->updateUser($user);
    }

    public function getAllUsers(): array
    {
        return $this->userRepo->findAllUsers();
    }

    public function getUserByEmail(string $email): ?User
    {
        return $this->userRepo->findUserByEmail($email);
    }

    public function deleteUser(int $id): void
    {
        $this->userRepo->deleteUser($id);
    }

    public function userExists(string $email, string $username): bool
    {
        return $this->userRepo->existsByEmailOrUsername($email, $username);
    }

    public function updateUserAdmin(User $user, string $plainPassword): User
    {
        if ($plainPassword !== '') {
            $user->password_hash = password_hash($plainPassword, PASSWORD_DEFAULT);
        }

        $this->userRepo->updateUser($user);

        return $user;
    }

    public function filterUsers(string $role, string $search, string $sort): array
    {
        return $this->userRepo->findFiltered($role, $search, $sort);
    }
}
