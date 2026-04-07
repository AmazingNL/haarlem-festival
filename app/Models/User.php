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

    public function __construct(string $username, string $email, string $password_hash,
                                string $first_name, string $last_name, ?string $phone, UserRole $role)
    {
        $this->username = $username;
        $this->email = $email;
        $this->password_hash = $password_hash;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->phone = $phone;
        $this->role = $role;
    }
}
