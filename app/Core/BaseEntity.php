<?php
// src/Domain/BaseEntity.php

declare(strict_types=1);
namespace App\Core;
abstract class BaseEntity
{
    /** @param array<string,mixed> $row */
    public static function fromArray(array $row): static
    {
        $obj = new static();
        foreach ($row as $k => $v) {
            if (property_exists($obj, $k)) {
                $obj->$k = $v;
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
