<?php

declare(strict_types=1);

namespace App\Services\Implementations;

use App\Services\Interfaces\IUserService;

use App\Models\Enum\UserRole;
use App\Models\User;
use App\Repositories\IUserRepository;

final class UserService implements IUserService
{
    private IUserRepository $userRepo;

    public function __construct(IUserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function registerCustomer(
        string $firstName,
        string $lastName,
        string $username,
        string $email,
        string $plainPassword,
        ?string $phone = null
    ): User {
        $firstName = trim($firstName);
        $lastName = trim($lastName);
        $username = trim($username);
        $email = trim($email);
        $phone = $phone !== null && trim($phone) !== '' ? trim($phone) : null;

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid email address.');
        }

        if (mb_strlen($plainPassword) < 8) {
            throw new \InvalidArgumentException('Password must be at least 8 characters.');
        }

        if ($this->userRepo->existsByEmailOrUsername($email, $username)) {
            throw new \InvalidArgumentException('Email or username already exists.');
        }

        $user = new User($username, $email, '', $firstName, $lastName, $phone, UserRole::customer);
        $this->saveNewUser($user, $plainPassword);

        return $user;
    }

    public function registerUser(User $user, string $plainPassword): void
    {
        $this->saveNewUser($user, $plainPassword);
    }

    public function authenticate(string $emailOrUsername, string $plainPassword): ?User
    {
        $user = $this->userRepo->findUserByLogin($emailOrUsername);
        if ($user === null || !password_verify($plainPassword, $user->password_hash)) {
            return null;
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

    private function saveNewUser(User $user, string $plainPassword): void
    {
        $user->password_hash = password_hash($plainPassword, PASSWORD_DEFAULT);
        $this->userRepo->createUser($user);
    }
}
