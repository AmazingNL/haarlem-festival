<?php
$values = is_array($sectionData ?? null) ? $sectionData : [];

$toFieldString = static function (mixed $data): string {
    if (is_array($data)) {
        $first = $data[0] ?? '';
        return is_string($first) ? $first : '';
    }

    return is_string($data) ? $data : '';
};

$title = isset($values['title']) ? $toFieldString($values['title']) : '';
$info = isset($values['info']) ? $toFieldString($values['info']) : '';
$customClass = isset($values['custom_class']) ? $toFieldString($values['custom_class']) : '';

$titleLabel = (string) ($sectionField['title']['label'] ?? 'Title');
$infoLabel = (string) ($sectionField['info']['label'] ?? 'Info');
$customClassLabel = (string) ($sectionField['custom_class']['label'] ?? 'Custom CSS class (optional)');
?>

<div class="content">
    <label for="field_title"><?= htmlspecialchars($titleLabel, ENT_QUOTES, 'UTF-8') ?></label>
    <input
        id="field_title"
        name="title"
        type="text"
        class="input"
        required
        value="<?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?>">
</div>

<div class="content field-full">
    <label for="field_info"><?= htmlspecialchars($infoLabel, ENT_QUOTES, 'UTF-8') ?></label>
    <textarea
        id="field_info"
        name="info"
        class="input js-wysiwyg"><?= htmlspecialchars($info, ENT_QUOTES, 'UTF-8') ?></textarea>
</div>

<div class="content">
    <label for="field_custom_class"><?= htmlspecialchars($customClassLabel, ENT_QUOTES, 'UTF-8') ?></label>
    <input
        id="field_custom_class"
        name="custom_class"
        type="text"
        class="input"
        placeholder="Enter custom CSS class"
        value="<?= htmlspecialchars($customClass, ENT_QUOTES, 'UTF-8') ?>">
</div>