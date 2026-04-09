<?php
// Return section data info for editing - good dynamic view for creating sections and editing section

$values = is_array($sectionData ?? null) ? $sectionData : [];

$toFieldString = static function (mixed $data): string {
    if (is_array($data)) {
        $first = $data[0] ?? '';
        return is_string($first) ? $first : '';
    }
    return is_string($data) ? $data : '';
};
?>

<?php foreach ($sectionField as $name => $config): ?>

    <?php
    $type = $config['type'] ?? 'text';
    $label = $config['label'] ?? ucfirst($name);
    $required = !empty($config['required']) ? 'required' : '';
    $value = isset($values[$name]) ? $toFieldString($values[$name]) : '';
    ?>

    <div class="content <?= $type === 'textarea' ? 'field-full' : '' ?>">

        <label for="field_<?= htmlspecialchars($name) ?>">
            <?= htmlspecialchars($label) ?>
        </label>

        <?php if ($type === 'textarea'): ?>

            <textarea id="field_<?= htmlspecialchars($name) ?>" name="<?= htmlspecialchars($name) ?>"
                class="input <?= htmlspecialchars($config['class'] ?? '') ?>" <?= $required ?>><?= htmlspecialchars($value) ?></textarea>

        <?php elseif ($type === 'image'): ?>
            <input id="field_<?= htmlspecialchars($name) ?>" name="<?= htmlspecialchars($name) ?>" type="file" class="input"
                accept="image/*" <?= $required ?>>

            <?php if ($value !== ''): ?>
                <p class="field-note">Current image: <a href="<?= htmlspecialchars($value) ?>" target="_blank" rel="noopener">view</a></p>
            <?php endif; ?>

        <?php elseif ($type === 'custom_class'): ?>

            <!-- Special custom field -->
            <input id="field_<?= htmlspecialchars($name) ?>" name="<?= htmlspecialchars($name) ?>" type="text"
                class="<?= htmlspecialchars($config['class'] ?? 'input') ?>" placeholder="Enter custom CSS class"
                value="<?= htmlspecialchars($value) ?>">

        <?php else: ?>

            <input id="field_<?= htmlspecialchars($name) ?>" name="<?= htmlspecialchars($name) ?>"
                type="<?= htmlspecialchars($type) ?>" class="input" <?= $required ?> value="<?= htmlspecialchars($value) ?>">

        <?php endif; ?>
    </div>

<?php endforeach; ?>