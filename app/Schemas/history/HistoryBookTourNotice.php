<?php

namespace App\Schemas\history;

use App\Schemas\BaseSection;

final class HistoryBookTourNotice extends BaseSection
{
    public function __construct(string $customClass = '', int $sortOrder = 0)
    {
        parent::__construct('history_book_tour_notice', $customClass, $sortOrder);
    }

    public function getAdminFormFields(): array
    {
        return [
            'title' => ['type' => 'text', 'label' => 'Title', 'required' => true],
            'body' => ['type' => 'textarea', 'label' => 'Body'],
        ];
    }
}
