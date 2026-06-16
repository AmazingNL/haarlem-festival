<?php

namespace App\Schemas;

abstract class BaseSection
{

    public string $type;
    public string $customClass = '';
    public int $sortOrder = 0;

    public function __construct(
        string $type,
        string $customClass = '',
        int $sortOrder = 0
    ) {

        $this->type = $type;
        $this->customClass = $customClass;
        $this->sortOrder = $sortOrder;
    }

    abstract public function getAdminFormFields():array;
}