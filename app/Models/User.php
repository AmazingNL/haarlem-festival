<?php
// src/Domain/User.php

declare(strict_types=1);

namespace App\Models;
use App\Models\Enum\UserRole;

final class User
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

public function __construct(
    string $username = '',
    string $email = '',
    string $password_hash = '',
    string $first_name = '',
    string $last_name = '',
    UserRole $role = UserRole::customer,
    ?string $phone = null,
    ?int $profile_image_id = null
) {
    $this->username = $username;
    $this->email = $email;
    $this->password_hash = $password_hash;
    $this->first_name = $first_name;
    $this->last_name = $last_name;
    $this->role = $role;
    $this->phone = $phone;
    $this->profile_image_id = $profile_image_id;
}
public function getUsername(): string
{
    return $this->username;
}

public function getEmail(): string
{
    return $this->email;
}

public function getPasswordHash(): string
{
    return $this->password_hash;
}

public function getFirstName(): string
{
    return $this->first_name;
}

public function getLastName(): string
{
    return $this->last_name;
}

public function getRole(): UserRole
{
    return $this->role;
}

public function getPhone(): ?string
{
    return $this->phone;
}

public function getProfileImageId(): ?int
{
    return $this->profile_image_id;
}

public function getUserId(): ?int
{
    return $this->user_id;
}

public function getCreatedAt(): ?string
{
    return $this->created_at;
}

public function getUpdatedAt(): ?string
{
    return $this->updated_at;
}
}
