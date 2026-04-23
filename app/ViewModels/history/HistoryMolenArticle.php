<?php

namespace App\ViewModels\history;

use App\ViewModels\BaseSection;

final class HistoryMolenArticle extends BaseSection
{
    public function __construct(int $pageId = 0, int $sectionId = 0, string $customClass = '', int $sortOrder = 0)
    {
        parent::__construct($pageId, $sectionId, 'history_molen_article', $customClass, $sortOrder);
    }

    public function getAdminFormFields(): array
    {
        return [
            'heading' => ['type' => 'text', 'label' => 'Heading', 'required' => true],
            'intro_one' => ['type' => 'textarea', 'label' => 'Intro Paragraph One'],
            'intro_two' => ['type' => 'textarea', 'label' => 'Intro Paragraph Two'],
            'gallery_heading' => ['type' => 'text', 'label' => 'Gallery Heading'],
            'gallery_one_image' => ['type' => 'image', 'label' => 'First Gallery Image', 'folder' => 'history', 'prefix' => 'history-molen-machinery'],
            'gallery_one_caption' => ['type' => 'text', 'label' => 'First Gallery Caption'],
            'gallery_two_image' => ['type' => 'image', 'label' => 'Second Gallery Image', 'folder' => 'history', 'prefix' => 'history-molen-historic'],
            'gallery_two_caption' => ['type' => 'text', 'label' => 'Second Gallery Caption'],
            'gallery_three_image' => ['type' => 'image', 'label' => 'Third Gallery Image', 'folder' => 'history', 'prefix' => 'history-molen-spaarne'],
            'gallery_three_caption' => ['type' => 'text', 'label' => 'Third Gallery Caption'],
            'significance_heading' => ['type' => 'text', 'label' => 'Historical Significance Heading'],
            'significance_one' => ['type' => 'textarea', 'label' => 'Historical Significance Paragraph One'],
            'significance_two' => ['type' => 'textarea', 'label' => 'Historical Significance Paragraph Two'],
            'significance_three' => ['type' => 'textarea', 'label' => 'Historical Significance Paragraph Three'],
            'importance_heading' => ['type' => 'text', 'label' => 'Importance Heading'],
            'importance_one' => ['type' => 'textarea', 'label' => 'Importance Paragraph One'],
            'importance_two' => ['type' => 'textarea', 'label' => 'Importance Paragraph Two'],
            'importance_three' => ['type' => 'textarea', 'label' => 'Importance Paragraph Three'],
        ];
    }
}
