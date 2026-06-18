<?php
$danceFilters = is_array($danceFilters ?? null) ? $danceFilters : [];
$danceFilterOptions = is_array($danceFilterOptions ?? null) ? $danceFilterOptions : [];

$selectedDanceFilter = static function (string $name) use ($danceFilters): string {
    return (string) ($danceFilters[$name] ?? '');
};

$danceFilterSelected = static function (string $name, string $value) use ($selectedDanceFilter): string {
    return $selectedDanceFilter($name) === $value ? ' selected' : '';
};

$escapeDanceFilter = static fn(mixed $value): string => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
$filtersKicker = trim((string) ($filtersKicker ?? 'Find Your Session'));
$filtersTitle = trim((string) ($filtersTitle ?? 'Filter Dance Sessions'));
$filtersIntro = trim((string) ($filtersIntro ?? 'Use the filters to narrow the official Dance programme by date, venue, artist, or session type.'));
?>

<section class="dance-filters" id="dance-filters" aria-labelledby="dance-filters-title">
    <div class="dance-filters__header">
        <p class="dance-kicker"><?= $escapeDanceFilter($filtersKicker !== '' ? $filtersKicker : 'Find Your Session') ?></p>
        <h2 id="dance-filters-title"><?= $escapeDanceFilter($filtersTitle !== '' ? $filtersTitle : 'Filter Dance Sessions') ?></h2>
        <?php if ($filtersIntro !== ''): ?>
            <p><?= $escapeDanceFilter($filtersIntro) ?></p>
        <?php endif; ?>
    </div>

    <form class="dance-filters__form" method="get" action="/dance#dance-tickets">
        <label class="dance-filter-field">
            <span>Date</span>
            <select name="date">
                <option value="">All dates</option>
                <?php foreach (($danceFilterOptions['dates'] ?? []) as $option): ?>
                    <?php
                    $value = (string) ($option['value'] ?? '');
                    $label = (string) ($option['label'] ?? $value);
                    ?>
                    <option value="<?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?>"<?= $danceFilterSelected('date', $value) ?>>
                        <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>

        <label class="dance-filter-field">
            <span>Venue</span>
            <select name="venue">
                <option value="">All venues</option>
                <?php foreach (($danceFilterOptions['venues'] ?? []) as $option): ?>
                    <?php
                    $value = (string) ($option['value'] ?? '');
                    $label = (string) ($option['label'] ?? $value);
                    ?>
                    <option value="<?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?>"<?= $danceFilterSelected('venue', $value) ?>>
                        <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>

        <label class="dance-filter-field">
            <span>Artist</span>
            <select name="artist">
                <option value="">All artists</option>
                <?php foreach (($danceFilterOptions['artists'] ?? []) as $option): ?>
                    <?php
                    $value = (string) ($option['value'] ?? '');
                    $label = (string) ($option['label'] ?? $value);
                    ?>
                    <option value="<?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?>"<?= $danceFilterSelected('artist', $value) ?>>
                        <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>

        <label class="dance-filter-field">
            <span>Session</span>
            <select name="session">
                <option value="">All sessions</option>
                <?php foreach (($danceFilterOptions['sessions'] ?? []) as $option): ?>
                    <?php
                    $value = (string) ($option['value'] ?? '');
                    $label = (string) ($option['label'] ?? $value);
                    ?>
                    <option value="<?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?>"<?= $danceFilterSelected('session', $value) ?>>
                        <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>

        <div class="dance-filters__actions">
            <button type="submit">Filter</button>
            <a href="/dance#dance-tickets">Clear filters</a>
        </div>
    </form>
</section>
