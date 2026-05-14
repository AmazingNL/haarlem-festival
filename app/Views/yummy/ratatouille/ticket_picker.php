<?php
$formattedPickerPrice = $formatMoney((float) ($pickerPrice ?? 0));
$pickerLabel = (string) ($pickerLabel ?? 'How many');
$pickerInputName = (string) ($pickerInputName ?? 'count');
$pickerPriceName = (string) ($pickerPriceName ?? 'price');
?>

<section class="ratatouille-ticket-picker" data-ticket-picker data-price="<?= htmlspecialchars($formattedPickerPrice, ENT_QUOTES, 'UTF-8') ?>">
    <button type="button" class="ratatouille-picker-toggle" aria-expanded="false">
        <span data-picker-label>Choose here</span>
        <svg viewBox="0 0 24 24" aria-hidden="true">
            <path d="M6 15l6-6 6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
    </button>

    <input type="hidden" name="<?= htmlspecialchars($pickerInputName, ENT_QUOTES, 'UTF-8') ?>" value="0" data-picker-input>
    <input type="hidden" name="<?= htmlspecialchars($pickerPriceName, ENT_QUOTES, 'UTF-8') ?>" value="<?= htmlspecialchars($formattedPickerPrice, ENT_QUOTES, 'UTF-8') ?>">

    <section class="ratatouille-picker-panel" hidden>
        <div class="ratatouille-picker-panel-row">
            <span>Price</span>
            <strong>€ <?= htmlspecialchars($formattedPickerPrice, ENT_QUOTES, 'UTF-8') ?></strong>
        </div>

        <div class="ratatouille-picker-panel-row ratatouille-picker-panel-row--counter">
            <span><?= htmlspecialchars($pickerLabel, ENT_QUOTES, 'UTF-8') ?></span>
            <svg class="ratatouille-picker-people" viewBox="0 0 48 32" aria-hidden="true">
                <path d="M15 10a5 5 0 1 0 0-10 5 5 0 0 0 0 10Zm-8 6a8 8 0 0 1 16 0v6h-5v10h-6V22H7v-6Z" fill="none" stroke="currentColor" stroke-width="2" />
                <path d="M33 10a5 5 0 1 0 0-10 5 5 0 0 0 0 10Zm-8 6a8 8 0 0 1 16 0v6h-5v10h-6V22h-5v-6Z" fill="none" stroke="currentColor" stroke-width="2" />
            </svg>

            <div class="ratatouille-picker-stepper" aria-label="<?= htmlspecialchars($pickerLabel, ENT_QUOTES, 'UTF-8') ?> count">
                <button type="button" data-picker-decrease aria-label="Decrease <?= htmlspecialchars($pickerLabel, ENT_QUOTES, 'UTF-8') ?> count">-</button>
                <span data-picker-count>0</span>
                <button type="button" data-picker-increase aria-label="Increase <?= htmlspecialchars($pickerLabel, ENT_QUOTES, 'UTF-8') ?> count">+</button>
            </div>
        </div>

        <div class="ratatouille-picker-panel-row">
            <span>Total Price</span>
            <strong>€ <span data-picker-total>0</span></strong>
        </div>

        <button type="button" class="ratatouille-picker-enter" data-picker-close>Enter</button>
    </section>
</section>