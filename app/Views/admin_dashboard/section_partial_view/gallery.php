<?php
$values = is_array($sectionData ?? null) ? $sectionData : [];

$toFieldString = static function (mixed $data): string {
    if (is_array($data)) {
        $isAssoc = array_keys($data) !== range(0, count($data) - 1);

        if ($isAssoc && isset($data['src'])) {
            $src = trim((string) ($data['src'] ?? ''));
            if ($src === '') {
                return '';
            }

            $alt = trim((string) ($data['alt'] ?? ''));
            $caption = trim((string) ($data['caption'] ?? ''));

            $html = '<figure><img src="' . htmlspecialchars($src, ENT_QUOTES, 'UTF-8') . '"';
            if ($alt !== '') {
                $html .= ' alt="' . htmlspecialchars($alt, ENT_QUOTES, 'UTF-8') . '"';
            }
            $html .= '>';

            if ($caption !== '') {
                $html .= '<figcaption>' . htmlspecialchars($caption, ENT_QUOTES, 'UTF-8') . '</figcaption>';
            }

            return $html . '</figure>';
        }

        $parts = [];
        foreach ($data as $item) {
            if (is_array($item) && isset($item['src'])) {
                $src = trim((string) ($item['src'] ?? ''));
                if ($src === '') {
                    continue;
                }

                $alt = trim((string) ($item['alt'] ?? ''));
                $caption = trim((string) ($item['caption'] ?? ''));

                $html = '<figure><img src="' . htmlspecialchars($src, ENT_QUOTES, 'UTF-8') . '"';
                if ($alt !== '') {
                    $html .= ' alt="' . htmlspecialchars($alt, ENT_QUOTES, 'UTF-8') . '"';
                }
                $html .= '>';

                if ($caption !== '') {
                    $html .= '<figcaption>' . htmlspecialchars($caption, ENT_QUOTES, 'UTF-8') . '</figcaption>';
                }

                $parts[] = $html . '</figure>';
                continue;
            }

            if (is_string($item) && trim($item) !== '') {
                $src = trim($item);
                $parts[] = '<figure><img src="' . htmlspecialchars($src, ENT_QUOTES, 'UTF-8') . '"></figure>';
            }
        }

        return implode("\n", $parts);
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
                class="input <?= htmlspecialchars($config['class'] ?? '') ?>" <?= $required ?>><?= htmlspecialchars($value) ?>
            </textarea>

        <?php elseif ($type === 'custom_class'): ?>
            <input id="field_<?= htmlspecialchars($name) ?>" name="<?= htmlspecialchars($name) ?>" type="text"
                class="<?= htmlspecialchars($config['class'] ?? 'input') ?>" placeholder="Enter custom CSS class"
                value="<?= htmlspecialchars($value) ?>">

        <?php else: ?>

            <input id="field_<?= htmlspecialchars($name) ?>" name="<?= htmlspecialchars($name) ?>"
                type="<?= htmlspecialchars($type) ?>" class="input" <?= $required ?> value="<?= htmlspecialchars($value) ?>">

        <?php endif; ?>
    </div>

<?php endforeach; ?>