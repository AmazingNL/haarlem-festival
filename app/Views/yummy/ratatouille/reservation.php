<?php
$normalizeValue = static function (mixed $value): array {
    if (!is_array($value)) {
        $data = trim((string) $value);
        $value = ($data === '') ? [] : preg_split('/[,\n\r]+/', $data);
    }

    return array_values(array_filter(array_map(
        static fn($data) => trim(strip_tags((string) $data)),
        $value ?? []
    )));
};

$splitInformation = static function (mixed $value) use ($normalizeValue): array {
    if (is_array($value)) {
        return $normalizeValue($value);
    }

    $text = trim((string) $value);
    if ($text === '') {
        return [];
    }

    $text = str_ireplace(
        ['</p>', '</li>', '<br>', '<br/>', '<br />'],
        "\n",
        $text
    );

    return array_values(array_filter(array_map(
        static fn($item): string => trim(strip_tags($item)),
        explode("\n", $text)
    )));
};

$dates = $normalizeValue($s['date'] ?? []);
$sessions = $normalizeValue($s['session'] ?? []);
$informationItems = $splitInformation($s['information'] ?? '');

$title = trim((string) ($s['title'] ?? ''));
$buttonText = trim((string) ($s['button_text'] ?? ''));
$buttonLink = trim((string) ($s['button_link'] ?? ''));
$pageSlug = trim((string) ($page->slug ?? 'ratatouille'));
$fallbackAction = '/yummy/' . $pageSlug . '/book-reservation';
$adultPrice = (float) ($s['adultPrice'] ?? 0);
$childPrice = (float) ($s['kidsPrice'] ?? 0);

$formatMoney = static fn(float $amount): string => number_format($amount, $amount === floor($amount) ? 0 : 2, '.', '');
?>

<form method="post" action="<?= htmlspecialchars($buttonLink !== '' && $buttonLink !== '#' ? $buttonLink : $fallbackAction, ENT_QUOTES, 'UTF-8') ?>" class="ratatouille-booking-card">
    <input type="hidden" name="_csrf" value="<?= htmlspecialchars((string) ($csrf ?? ''), ENT_QUOTES, 'UTF-8') ?>">
    <button type="button" aria-label="Close reservation form" class="ratatouille-booking-close">x</button>

    <?php if ($title !== ''): ?>
        <h2><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h2>
    <?php endif; ?>

    <?php if ($informationItems !== []): ?>
        <section class="ratatouille-booking-info" aria-label="Reservation information">
            <?php foreach ($informationItems as $item): ?>
                <p><?= htmlspecialchars($item, ENT_QUOTES, 'UTF-8') ?></p>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>

    <?php if ($dates !== []): ?>
        <fieldset class="ratatouille-booking-fieldset">
            <legend>Date</legend>
            <div class="ratatouille-date-options">
                <?php foreach ($dates as $index => $date): ?>
                    <label>
                        <input type="radio" name="date" value="<?= htmlspecialchars($date, ENT_QUOTES, 'UTF-8') ?>" <?= $index === 0 ? 'checked' : '' ?>>
                        <span><?= htmlspecialchars($date, ENT_QUOTES, 'UTF-8') ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
        </fieldset>
    <?php endif; ?>

    <?php if ($sessions !== []): ?>
        <fieldset class="ratatouille-booking-fieldset">
            <legend>Choose Your Session</legend>
            <div class="ratatouille-session-options">
                <?php foreach ($sessions as $index => $session): ?>
                    <label>
                        <input type="radio" name="session" value="<?= htmlspecialchars($session, ENT_QUOTES, 'UTF-8') ?>" <?= $index === 0 ? 'checked' : '' ?>>
                        <span><?= htmlspecialchars($session, ENT_QUOTES, 'UTF-8') ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
        </fieldset>
    <?php endif; ?>

    <div class="ratatouille-booking-row-label" data-ticket-summary>
        <span>How Many Adult</span>
        <strong data-ticket-summary-count>0</strong>
        <span>Total Price</span>
        <strong data-ticket-summary-total>€ 0</strong>
    </div>
    <?php
    $pickerLabel = 'How many adults';
    $pickerInputName = 'adultCount';
    $pickerPrice = $adultPrice;
    $pickerPriceName = 'adultPrice';
    require __DIR__ . '/ticket_picker.php';
    ?>

    <div class="ratatouille-booking-row-label" data-ticket-summary>
        <span>How Many Children</span>
        <strong data-ticket-summary-count>0</strong>
        <span>Total Price</span>
        <strong data-ticket-summary-total>€ 0</strong>
    </div>
    <?php
    $pickerLabel = 'How many children';
    $pickerInputName = 'childCount';
    $pickerPrice = $childPrice;
    $pickerPriceName = 'childPrice';
    require __DIR__ . '/ticket_picker.php';
    ?>

    <label class="ratatouille-notes-label" for="reservation-notes">Special Requests</label>
    <textarea id="reservation-notes" name="special_requests"></textarea>

    <div class="ratatouille-total-row">
        <span>Total Price</span>
        <strong>€ 0</strong>
    </div>
    <input type="hidden" name="totalPrice" value="0" data-reservation-total-input>

    <button type="submit" class="ratatouille-submit-button">
        <?= htmlspecialchars($buttonText !== '' ? $buttonText : 'Book Reservation', ENT_QUOTES, 'UTF-8') ?>
    </button>
</form>
<script src="/assets/js/ratatouille-reservation.js" defer></script>
