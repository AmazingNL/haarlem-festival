<?php
$values = is_array($sectionData ?? null) ? $sectionData : [];

$toFieldString = static function (mixed $data): string {
    if (is_array($data)) {
        $first = $data[0] ?? '';
        return is_string($first) ? $first : '';
    }

    return is_string($data) ? $data : '';
};

$cuisineValue = '';
if (isset($values['cuisine'])) {
    $rawCuisine = $values['cuisine'];
    if (is_array($rawCuisine)) {
        $cuisineValue = implode(', ', array_filter(array_map(static fn ($item): string => is_string($item) ? trim($item) : '', $rawCuisine)));
    } else {
        $cuisineValue = (string) $rawCuisine;
    }
}
?>

<?php foreach ($sectionField as $name => $config): ?>

    <?php
    $type = $config['type'] ?? 'text';
    $label = $config['label'] ?? ucfirst($name);
    $required = !empty($config['required']) ? 'required' : '';
    $value = isset($values[$name]) ? $toFieldString($values[$name]) : '';

    if ($name === 'cuisine') {
        $value = $cuisineValue;
    }
    ?>

    <div class="content <?= $type === 'textarea' ? 'field-full' : '' ?>">
        <label for="field_<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>">
            <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>
        </label>

        <?php if ($name === 'cuisine'): ?>
            <textarea id="field_<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>"
                name="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>"
                class="input"
                rows="3"
                placeholder="Example: Sea Food, French, European"
                <?= $required ?>><?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?></textarea>
            <p class="field-note">Use one cuisine or many. Separate values with commas or new lines.</p>

        <?php elseif ($name === 'capacity'): ?>
            <input id="field_<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>"
                name="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>"
                type="number"
                min="0"
                class="input"
                <?= $required ?>
                value="<?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?>">
            <p class="field-note">Set the seats shown on this card. Use the same value as the linked event capacity.</p>

        <?php elseif ($name === 'rating'): ?>
            <input id="field_<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>"
                name="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>"
                type="number"
                min="0"
                max="5"
                step="0.1"
                class="input"
                <?= $required ?>
                value="<?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?>">
            <p class="field-note">Enter a score from 0 to 5. Decimals are allowed.</p>

        <?php elseif ($type === 'textarea'): ?>
            <textarea id="field_<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>"
                name="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>"
                class="input <?= htmlspecialchars($config['class'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                <?= $required ?>><?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?></textarea>

        <?php elseif ($type === 'image'): ?>
            <input id="field_<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>"
                name="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>"
                type="file"
                class="input"
                accept="image/*"
                <?= $required ?>>

            <?php
            $altName = $name . '_alt_text';
            $captionName = $name . '_caption';
            $altValue = isset($values[$altName]) ? $toFieldString($values[$altName]) : '';
            $captionValue = isset($values[$captionName]) ? $toFieldString($values[$captionName]) : '';
            ?>

            <label for="field_<?= htmlspecialchars($altName, ENT_QUOTES, 'UTF-8') ?>">Image Alt Text</label>
            <input
                id="field_<?= htmlspecialchars($altName, ENT_QUOTES, 'UTF-8') ?>"
                name="<?= htmlspecialchars($altName, ENT_QUOTES, 'UTF-8') ?>"
                type="text"
                class="input"
                value="<?= htmlspecialchars($altValue, ENT_QUOTES, 'UTF-8') ?>"
                data-image-alt>

            <label for="field_<?= htmlspecialchars($captionName, ENT_QUOTES, 'UTF-8') ?>">Image Caption</label>
            <input
                id="field_<?= htmlspecialchars($captionName, ENT_QUOTES, 'UTF-8') ?>"
                name="<?= htmlspecialchars($captionName, ENT_QUOTES, 'UTF-8') ?>"
                type="text"
                class="input"
                value="<?= htmlspecialchars($captionValue, ENT_QUOTES, 'UTF-8') ?>"
                data-image-caption>

            <?php if ($value !== ''): ?>
                <p class="field-note">Current image: <a href="<?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener">view</a></p>
            <?php endif; ?>

        <?php elseif ($type === 'custom_class'): ?>
            <input id="field_<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>"
                name="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>"
                type="text"
                class="<?= htmlspecialchars($config['class'] ?? 'input', ENT_QUOTES, 'UTF-8') ?>"
                placeholder="Enter custom CSS class"
                value="<?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?>">

        <?php else: ?>
            <input id="field_<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>"
                name="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>"
                type="<?= htmlspecialchars($type, ENT_QUOTES, 'UTF-8') ?>"
                class="input"
                <?= $required ?>
                value="<?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?>">
        <?php endif; ?>
    </div>

<?php endforeach; ?>
