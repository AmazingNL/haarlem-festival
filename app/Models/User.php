<?php
// src/Domain/User.php

declare(strict_types=1);

namespace App\Models;
use App\Core\BaseEntity;
use App\Models\Enum\UserRole;

final class User extends BaseEntity
{
    public ?int $user_id = null;
    public string $username = '';
    public string $email = '';
    public string $password_hash = '';
    public string $first_name = '';
    public string $last_name = '';
    public UserRole $role = UserRole::customer;

    public ?string $phone = null;
    public ?int $profile_image_id = null;

    public ?string $created_at = null;
    public ?string $updated_at = null;


    /** @param array<string,mixed> $row */
    public static function fromArray(array $row): static
    {
        $rawRole = $row['role'] ?? null;
        unset($row['role']); // prevent parent from assigning a raw string to the enum-typed property

        $user = parent::fromArray($row);

        if ($rawRole !== null) {
            //  tryFrom to avoid ValueError on invalid values 
            $role = UserRole::tryFrom((string) $rawRole);
            if ($role !== null)
                $user->role = $role;
        }

        return $user;
    }

}
