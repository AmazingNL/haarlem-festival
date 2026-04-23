<?php
// Read the selectable options from the CMS section data.
$dayOptions = array_values(array_filter([
    $s['day_one'] ?? '',
    $s['day_two'] ?? '',
    $s['day_three'] ?? '',
    $s['day_four'] ?? '',
], static fn(?string $value): bool => trim((string) $value) !== ''));

$timeOptions = array_values(array_filter([
    $s['time_one'] ?? '',
    $s['time_two'] ?? '',
    $s['time_three'] ?? '',
], static fn(?string $value): bool => trim((string) $value) !== ''));

$languageOptions = array_values(array_filter([
    $s['language_one'] ?? '',
    $s['language_two'] ?? '',
    $s['language_three'] ?? '',
], static fn(?string $value): bool => trim((string) $value) !== ''));

$parseMoney = static function (?string $value): float {
    $raw = str_replace(',', '.', (string) $value);
    if (!preg_match('/\d+(?:\.\d{1,2})?/', $raw, $match)) {
        return 0.0;
    }

    return (float) $match[0];
};

$parseCount = static function (?string $value, int $default = 4): int {
    if (!preg_match_all('/\d+/', (string) $value, $matches) || empty($matches[0])) {
        return $default;
    }

    return (int) end($matches[0]);
};

$selectedDay = '';
$selectedTime = '';
$selectedLanguage = '';

$individualPriceValue = $parseMoney($s['individual_price'] ?? '');
$familyPriceValue = $parseMoney($s['family_price'] ?? '');
$familyGroupSize = $parseCount($s['family_price'] ?? '', 4);

$individualTitle = trim((string) ($s['individual_title'] ?? 'Individual'));
$familyTitle = trim((string) ($s['family_title'] ?? 'Family'));

$defaultTicketKey = '';
$defaultQuantity = max(1, (int) ($s['quantity_value'] ?? 1));
$defaultUnitPrice = 0.0;
$defaultTotalPrice = 0.0;
$defaultSelectionText = '';
$defaultTicketSummary = '';
$selectionPlaceholder = 'Choose day, time, and language';
$ticketPlaceholder = 'Choose a ticket type';

$defaultSavingNote = '';

$submitUrl = trim((string) ($s['button_link'] ?? '')) !== '' && trim((string) ($s['button_link'] ?? '')) !== '#'
    ? trim((string) $s['button_link'])
    : '/history/book-tour/add-to-program';
?>

<article
    class="history-tour-card history-tour-card--booking"
    data-booking-widget
    data-submit-url="<?= htmlspecialchars($submitUrl, ENT_QUOTES, 'UTF-8') ?>"
    data-default-day="<?= htmlspecialchars($selectedDay, ENT_QUOTES, 'UTF-8') ?>"
    data-default-time="<?= htmlspecialchars($selectedTime, ENT_QUOTES, 'UTF-8') ?>"
    data-default-language="<?= htmlspecialchars($selectedLanguage, ENT_QUOTES, 'UTF-8') ?>"
    data-default-ticket="<?= htmlspecialchars($defaultTicketKey, ENT_QUOTES, 'UTF-8') ?>"
    data-default-quantity="<?= htmlspecialchars((string) $defaultQuantity, ENT_QUOTES, 'UTF-8') ?>"
    data-individual-price="<?= htmlspecialchars(number_format($individualPriceValue, 2, '.', ''), ENT_QUOTES, 'UTF-8') ?>"
    data-family-price="<?= htmlspecialchars(number_format($familyPriceValue, 2, '.', ''), ENT_QUOTES, 'UTF-8') ?>"
    data-family-size="<?= htmlspecialchars((string) $familyGroupSize, ENT_QUOTES, 'UTF-8') ?>"
    data-saving-note="<?= htmlspecialchars((string) ($s['saving_note'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
    data-booking-title="<?= htmlspecialchars((string) ($s['heading'] ?? 'Book Your Adventure'), ENT_QUOTES, 'UTF-8') ?>">

    <div class="history-tour-card__head">
        <h2><?= htmlspecialchars((string) ($s['heading'] ?? ''), ENT_QUOTES, 'UTF-8') ?></h2>
        <?php if (!empty($s['intro'])): ?>
            <p><?= htmlspecialchars((string) $s['intro'], ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>
    </div>

    <form class="history-tour-booking-form" method="post" action="<?= htmlspecialchars($submitUrl, ENT_QUOTES, 'UTF-8') ?>">
        <?php // Hidden inputs are updated by JavaScript and posted to the controller on submit. ?>
        <input type="hidden" name="_csrf" value="<?= htmlspecialchars((string) ($csrf ?? ''), ENT_QUOTES, 'UTF-8') ?>">
        <input type="hidden" name="booking_title" value="<?= htmlspecialchars((string) ($s['heading'] ?? 'Book Your Adventure'), ENT_QUOTES, 'UTF-8') ?>">
        <input type="hidden" name="selected_day" value="<?= htmlspecialchars($selectedDay, ENT_QUOTES, 'UTF-8') ?>" data-booking-input="day">
        <input type="hidden" name="selected_time" value="<?= htmlspecialchars($selectedTime, ENT_QUOTES, 'UTF-8') ?>" data-booking-input="time">
        <input type="hidden" name="selected_language" value="<?= htmlspecialchars($selectedLanguage, ENT_QUOTES, 'UTF-8') ?>" data-booking-input="language">
        <input type="hidden" name="ticket_key" value="<?= htmlspecialchars($defaultTicketKey, ENT_QUOTES, 'UTF-8') ?>" data-booking-input="ticket_key">
        <input type="hidden" name="ticket_title" value="" data-booking-input="ticket_title">
        <input type="hidden" name="quantity" value="<?= htmlspecialchars((string) $defaultQuantity, ENT_QUOTES, 'UTF-8') ?>" data-booking-input="quantity">
        <input type="hidden" name="unit_price" value="<?= htmlspecialchars(number_format($defaultUnitPrice, 2, '.', ''), ENT_QUOTES, 'UTF-8') ?>" data-booking-input="unit_price">
        <input type="hidden" name="total_price" value="<?= htmlspecialchars(number_format($defaultTotalPrice, 2, '.', ''), ENT_QUOTES, 'UTF-8') ?>" data-booking-input="total_price">
        <input type="hidden" name="selection_text" value="<?= htmlspecialchars($defaultSelectionText, ENT_QUOTES, 'UTF-8') ?>" data-booking-input="selection_text">
        <input type="hidden" name="ticket_summary_text" value="<?= htmlspecialchars($defaultTicketSummary, ENT_QUOTES, 'UTF-8') ?>" data-booking-input="ticket_summary_text">

        <div class="history-tour-picker">
            <h3><?= htmlspecialchars((string) ($s['day_label'] ?? ''), ENT_QUOTES, 'UTF-8') ?></h3>
            <div class="history-tour-pill-row history-tour-pill-row--stretch">
                <?php foreach ($dayOptions as $option): ?>
                    <button
                        type="button"
                        class="history-tour-pill"
                        data-booking-choice="day"
                        data-value="<?= htmlspecialchars($option, ENT_QUOTES, 'UTF-8') ?>">
                        <?= htmlspecialchars($option, ENT_QUOTES, 'UTF-8') ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="history-tour-picker">
            <h3><?= htmlspecialchars((string) ($s['time_label'] ?? ''), ENT_QUOTES, 'UTF-8') ?></h3>
            <div class="history-tour-pill-row history-tour-pill-row--stretch">
                <?php foreach ($timeOptions as $option): ?>
                    <button
                        type="button"
                        class="history-tour-pill"
                        data-booking-choice="time"
                        data-value="<?= htmlspecialchars($option, ENT_QUOTES, 'UTF-8') ?>">
                        <?= htmlspecialchars($option, ENT_QUOTES, 'UTF-8') ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="history-tour-picker">
            <h3><?= htmlspecialchars((string) ($s['language_label'] ?? ''), ENT_QUOTES, 'UTF-8') ?></h3>
            <div class="history-tour-pill-row history-tour-pill-row--stretch">
                <?php foreach ($languageOptions as $option): ?>
                    <button
                        type="button"
                        class="history-tour-pill"
                        data-booking-choice="language"
                        data-value="<?= htmlspecialchars($option, ENT_QUOTES, 'UTF-8') ?>">
                        <?= htmlspecialchars($option, ENT_QUOTES, 'UTF-8') ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="history-tour-picker">
            <h3><?= htmlspecialchars((string) ($s['ticket_label'] ?? ''), ENT_QUOTES, 'UTF-8') ?></h3>
            <div class="history-tour-ticket-grid">
                <button
                    type="button"
                    class="history-tour-ticket"
                    data-booking-ticket="individual"
                    data-label="<?= htmlspecialchars($individualTitle, ENT_QUOTES, 'UTF-8') ?>"
                    data-price="<?= htmlspecialchars(number_format($individualPriceValue, 2, '.', ''), ENT_QUOTES, 'UTF-8') ?>">
                    <span class="history-tour-ticket__check" aria-hidden="true"></span>
                    <strong><?= htmlspecialchars($individualTitle, ENT_QUOTES, 'UTF-8') ?></strong>
                    <span><?= htmlspecialchars((string) ($s['individual_price'] ?? ''), ENT_QUOTES, 'UTF-8') ?></span>
                </button>

                <button
                    type="button"
                    class="history-tour-ticket"
                    data-booking-ticket="family"
                    data-label="<?= htmlspecialchars($familyTitle, ENT_QUOTES, 'UTF-8') ?>"
                    data-price="<?= htmlspecialchars(number_format($familyPriceValue, 2, '.', ''), ENT_QUOTES, 'UTF-8') ?>"
                    data-family-size="<?= htmlspecialchars((string) $familyGroupSize, ENT_QUOTES, 'UTF-8') ?>">
                    <?php if (!empty($s['family_badge'])): ?>
                        <span class="history-tour-ticket__badge"><?= htmlspecialchars((string) $s['family_badge'], ENT_QUOTES, 'UTF-8') ?></span>
                    <?php endif; ?>
                    <span class="history-tour-ticket__check" aria-hidden="true"></span>
                    <strong><?= htmlspecialchars($familyTitle, ENT_QUOTES, 'UTF-8') ?></strong>
                    <span><?= htmlspecialchars((string) ($s['family_price'] ?? ''), ENT_QUOTES, 'UTF-8') ?></span>
                </button>
            </div>
        </div>

        <div class="history-tour-quantity" data-booking-quantity>
            <button type="button" class="history-tour-quantity__button" data-booking-quantity-action="decrease" aria-label="Decrease quantity">-</button>
            <strong class="history-tour-quantity__value" data-booking-summary="quantity"><?= htmlspecialchars((string) $defaultQuantity, ENT_QUOTES, 'UTF-8') ?></strong>
            <button type="button" class="history-tour-quantity__button" data-booking-quantity-action="increase" aria-label="Increase quantity">+</button>
        </div>

        <div class="history-tour-summary">
            <div class="history-tour-summary__row">
                <span><?= htmlspecialchars((string) ($s['selection_label'] ?? 'Selection'), ENT_QUOTES, 'UTF-8') ?></span>
                <strong data-booking-summary="selection"><?= htmlspecialchars($selectionPlaceholder, ENT_QUOTES, 'UTF-8') ?></strong>
            </div>
            <div class="history-tour-summary__row">
                <span><?= htmlspecialchars((string) ($s['ticket_summary_label'] ?? 'Ticket'), ENT_QUOTES, 'UTF-8') ?></span>
                <strong data-booking-summary="ticket"><?= htmlspecialchars($ticketPlaceholder, ENT_QUOTES, 'UTF-8') ?></strong>
            </div>
            <div class="history-tour-summary__row history-tour-summary__row--total">
                <span><?= htmlspecialchars((string) ($s['total_label'] ?? 'Total'), ENT_QUOTES, 'UTF-8') ?></span>
                <strong data-booking-summary="total">€<?= htmlspecialchars(number_format($defaultTotalPrice, 2, '.', ''), ENT_QUOTES, 'UTF-8') ?></strong>
            </div>
            <p class="history-tour-summary__note" data-booking-summary="note"><?= htmlspecialchars($defaultSavingNote, ENT_QUOTES, 'UTF-8') ?></p>
        </div>

        <button type="submit" class="history-btn history-btn--dark history-tour-card__button history-tour-card__submit" data-booking-submit disabled>
            <span class="history-tour-card__submit-icon" aria-hidden="true">+</span>
            <span><?= htmlspecialchars((string) ($s['button_text'] ?? 'Add to My Program'), ENT_QUOTES, 'UTF-8') ?></span>
        </button>
    </form>
</article>
