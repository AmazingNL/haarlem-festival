<?php
// src/Domain/User.php

declare(strict_types=1);

namespace App\Models;
use App\Core\BaseEntity;
use App\Models\Enum\UserRole;

final class User extends BaseEntity
{
    public ?int $user_id = null;
    public string $email = '';
    public string $password_hash = '';
    public string $first_name = '';
    public string $last_name = '';
    public UserRole $role = UserRole::customer;

    public ?string $phone = null;

    public ?string $billing_street = null;
    public ?string $billing_postal_code = null;
    public ?string $billing_city = null;
    public ?string $billing_country = null;

    public ?int $profile_image_id = null;

    public ?string $created_at = null;
    public ?string $updated_at = null;


    /** @param array<string,mixed> $row */
    public static function fromArray(array $row): static
    {
        $rawRole = $row['role'] ?? null;
        unset($row['role']); // prevent parent from assigning a raw string to the enum-typed property

        $u = parent::fromArray($row);

        if ($rawRole !== null) {
            // Prefer tryFrom to avoid ValueError on invalid values (PHP 8.1+)
            $role = UserRole::tryFrom((string) $rawRole);
            if ($role !== null)
                $u->role = $role;
        }

        return $u;
    }

}
