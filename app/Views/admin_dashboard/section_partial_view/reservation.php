<?php
$values = is_array($sectionData ?? null) ? $sectionData : [];

$toFieldString = static function (mixed $data): string {
    if (is_array($data)) {
        $first = $data[0] ?? '';
        return is_string($first) ? $first : '';
    }

    return is_string($data) ? $data : '';
};

$sessionValue = '';
if (isset($values['session'])) {
    $rawSession = $values['session'];
    if (is_array($rawSession)) {
        $sessionValue = implode(', ', array_filter(array_map(static fn($item): string => is_string($item) ? trim($item) : '', $rawSession)));
    } else {
        $sessionValue = (string) $rawSession;
    }
}

$dateValue = '';
if (isset($values['date'])) {
    $rawDate = $values['date'];
    if (is_array($rawDate)) {
        $dateValue = implode(', ', array_filter(array_map(static fn($item): string => is_string($item) ? trim($item) : '', $rawDate)));
    } else {
        $dateValue = (string) $rawDate;
    }
}
?>

<?php foreach ($sectionField as $name => $config): ?>

    <?php
    $type = $config['type'] ?? 'text';
    $label = $config['label'] ?? ucfirst($name);
    $required = !empty($config['required']) ? 'required' : '';
    $value = isset($values[$name]) ? $toFieldString($values[$name]) : '';

    if ($name === 'session') {
        $value = $sessionValue;
    }

    if ($name === 'date') {
        $value = $dateValue;
    }
    ?>

    <div class="content <?= $type === 'textarea' ? 'field-full' : '' ?>">
        <label for="field_<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>">
            <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>
        </label>


        <?php if ($name === 'information'): ?>
            <textarea id="field_<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>"
                name="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>" class="input" rows="3"
                placeholder="Example: any information for your customers"<?= $required ?>><?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?></textarea>
            <p class="field-note">write information for your customer.</p>

        <?php elseif ($name === 'session'): ?>
            <textarea id="field_<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>"
                name="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>" class="input" rows="3"
                placeholder="Example: 1st Session 18:00 - 19:30" <?= $required ?>><?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?></textarea>
            <p class="field-note">Use one session or many. Separate values with commas or new lines.</p>

        <?php elseif ($name === 'date'): ?>
            <textarea id="field_<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>"
                name="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>" class="input" rows="3"
                placeholder="Example: Thursday July - 23rd" <?= $required ?>><?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?></textarea>
            <p class="field-note">Use one date or many. Separate values with commas or new lines.</p>

        <?php elseif ($name === 'adultPrice'): ?>
            <input id="field_<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>"
                name="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>" type="number" min="0" class="input" <?= $required ?>
                value="<?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?>">
            <p class="field-note">Set the price of Adult.</p>

        <?php elseif ($name === 'kidsPrice'): ?>
            <input id="field_<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>"
                name="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>" type="number" min="0" class="input" <?= $required ?>
                value="<?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?>">
            <p class="field-note">Set the price of Kids.</p>

        <?php elseif ($type === 'textarea'): ?>
            <textarea id="field_<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>"
                name="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>"
                class="input <?= htmlspecialchars($config['class'] ?? '', ENT_QUOTES, 'UTF-8') ?>" <?= $required ?>><?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?>
            </textarea>

            <?php if ($value !== ''): ?>
                <p class="field-note">Current image: <a href="<?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?>" target="_blank"
                        rel="noopener">view</a></p>
            <?php endif; ?>

        <?php elseif ($type === 'custom_class'): ?>
            <input id="field_<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>"
                name="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>" type="text"
                class="<?= htmlspecialchars($config['class'] ?? 'input', ENT_QUOTES, 'UTF-8') ?>"
                placeholder="Enter custom CSS class" value="<?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?>">

        <?php else: ?>
            <input id="field_<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>"
                name="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>"
                type="<?= htmlspecialchars($type, ENT_QUOTES, 'UTF-8') ?>" class="input" <?= $required ?>
                value="<?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?>">
        <?php endif; ?>
    </div>

<?php endforeach; ?>