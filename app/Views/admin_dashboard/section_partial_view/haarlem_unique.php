<?php
$values = is_array($sectionData ?? null) ? $sectionData : [];

$toFieldString = static function (string $fieldName, mixed $data): string {
	if ($fieldName !== 'section_image') {
		return is_string($data) ? $data : '';
	}

	if (is_string($data)) {
		return $data;
	}

	if (!is_array($data)) {
		return '';
	}

	$parts = [];
	foreach ($data as $item) {
		if (is_array($item)) {
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
};
?>

<?php foreach ($sectionField as $name => $config): ?>

	<?php
	$type = $config['type'] ?? 'text';
	$label = $config['label'] ?? ucfirst($name);
	$required = !empty($config['required']) ? 'required' : '';
	$value = isset($values[$name]) ? $toFieldString($name, $values[$name]) : '';
	?>

	<div class="content <?= $type === 'textarea' ? 'field-full' : '' ?>">

		<label for="field_<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>">
			<?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>
		</label>

		<?php if ($type === 'textarea'): ?>

			<textarea id="field_<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>"
				name="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>"
				class="input <?= htmlspecialchars($config['class'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
				<?= $required ?>><?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?></textarea>

		<?php elseif ($type === 'custom_class'): ?>

			<input id="field_<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>"
				name="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>"
				type="text"
				class="<?= htmlspecialchars($config['class'] ?? 'input', ENT_QUOTES, 'UTF-8') ?>"
				value="<?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?>"
				placeholder="Enter custom CSS class">

		<?php else: ?>

			<input id="field_<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>"
				name="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>"
				type="<?= htmlspecialchars($type, ENT_QUOTES, 'UTF-8') ?>"
				class="input"
				value="<?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?>"
				<?= $required ?>>

		<?php endif; ?>
	</div>

<?php endforeach; ?>
