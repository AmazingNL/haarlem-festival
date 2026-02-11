<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Repositories\IUserRepository;

final class UserService implements IUserService
{
    private IUserRepository $userRepo;

    public function __construct(IUserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function registerUser(User $user, string $plainPassword): User
    {
        $user->password_hash = password_hash($plainPassword, PASSWORD_DEFAULT);
        return $this->userRepo->createUser($user);
    }

    public function authenticate(string $emailOrUsername, string $plainPassword): ?User
    {
        $user = $this->userRepo->findUserByEmail($emailOrUsername);

        if ($user !== null && password_verify($plainPassword, $user->password_hash)) {
            return $user;
        }

        return null;
    }

    public function getUserById(int $id): ?User
    {
        return $this->userRepo->findUserById($id);
    }

    public function updateUser(User $user): User
    {
        return $this->userRepo->updateUser($user);
    }

    public function deleteUser(int $id): bool
    {
        return $this->userRepo->deleteUser($id);
    }

    public function listUsers(): array
    {
        return $this->userRepo->findAllUsers();
    }
}
