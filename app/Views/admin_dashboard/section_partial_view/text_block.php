<?php 
$values = is_array($sectionData ?? null) ? $sectionData : [] ?>

<?php foreach ($sectionField as $name => $config): ?>

    <?php
    $type = $config['type'] ?? 'text';
    $label = $config['label'] ?? ucfirst($name);
    $required = !empty($config['required']) ? 'required' : '';
    $value = isset($values[$name]) ? (string) $values[$name] : '';
    ?>

    <div class="content <?= $type === 'textarea' ? 'field-full' : '' ?>">
        <section>
            <label for="field_<?= htmlspecialchars($name) ?>">
                <?= htmlspecialchars($label) ?>
            </label>

            <?php if ($type === 'textarea'): ?>
                <textarea id="field_<?= htmlspecialchars($name) ?>" name="<?= htmlspecialchars($name) ?>"
                    class="input <?= htmlspecialchars($config['class'] ?? '') ?>" <?= $required ?>>
                    <?= htmlspecialchars($value) ?>
                </textarea>
        </section>

        <section>

            <?php elseif ($type === 'custom_class'): ?>

                <input id="field_<?= htmlspecialchars($name) ?>" name="<?= htmlspecialchars($name) ?>" type="text"
                    class="<?= htmlspecialchars($config['class'] ?? 'input') ?>" 
                    value="<?= htmlspecialchars($value)?>" placeholder="Enter custom CSS class">

            <?php else: ?>

                <input id="field_<?= htmlspecialchars($name) ?>" name="<?= htmlspecialchars($name) ?>"
                    type="<?= htmlspecialchars($type) ?>" class="input" 
                    value="<?= htmlspecialchars($value)?>"
                    <?= $required ?>>

            <?php endif; ?>
        </section>
    </div>

<?php endforeach; ?>
