<?php
// src/Domain/BaseEntity.php

declare(strict_types=1);
namespace App\Core;
abstract class BaseEntity
{
    public static function fromArray(array $row): static
    {
        $obj = new static();

        $ref = new \ReflectionObject($obj);

        foreach ($row as $key => $value) {
            if (! $ref->hasProperty($key)) {
                continue;
            }

            $prop = $ref->getProperty($key);
            $type = $prop->getType();

            if ($type !== null) {
                $typeName = $type->getName();

                // Handle NULLs respecting nullable declarations
                if ($value === null) {
                    if ($type->allowsNull()) {
                        $obj->$key = null;
                    }
                    // skip assigning null to non-nullable typed property
                    continue;
                }

                switch ($typeName) {
                    case 'bool':
                        $obj->$key = (bool) $value;
                        break;
                    case 'int':
                        $obj->$key = (int) $value;
                        break;
                    case 'float':
                        $obj->$key = (float) $value;
                        break;
                    case 'string':
                        $obj->$key = (string) $value;
                        break;
                    default:
                        // for enums or objects leave as-is; models can override fromArray
                        $obj->$key = $value;
                }
            } else {
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
