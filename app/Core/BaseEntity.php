<?php
// src/Domain/BaseEntity.php

declare(strict_types=1);
namespace App\Core;
abstract class BaseEntity
{
    public static function fromArray(array $row): static
    {
        $obj = new static();
        foreach ($row as $key => $value) {
            if (property_exists($obj, $key)) {
                $obj->$key = $value;
            }
        }
        return $obj;
    }

    /** @return array<string,mixed> */
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
