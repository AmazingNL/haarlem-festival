<?php
$placeName = trim((string) ($s['place_name'] ?? ''));
$addressOne = trim((string) ($s['address_line_one'] ?? ''));
$addressTwo = trim((string) ($s['address_line_two'] ?? ''));
$email = trim((string) ($s['email'] ?? ''));
$phoneOffice = trim((string) ($s['phone_office'] ?? ''));
$officeHours = trim((string) ($s['office_hours'] ?? ''));
$phoneCashDesk = trim((string) ($s['phone_cash_desk'] ?? ''));
?>
<section class="jazz-section jazz-contact">
    <div class="jazz-container">
        <h2 class="jazz-section__title"><?= htmlspecialchars((string) ($s['heading'] ?? 'Location and Contact'), ENT_QUOTES, 'UTF-8') ?></h2>
        <?php if (!empty($s['intro'])): ?>
            <p class="jazz-section__intro"><?= $jazzText($s['intro']) ?></p>
        <?php endif; ?>

        <div class="jazz-contact__grid">
            <div class="jazz-contact__address">
                <?php if ($placeName !== ''): ?>
                    <p class="jazz-contact__place"><?= htmlspecialchars($placeName, ENT_QUOTES, 'UTF-8') ?></p>
                <?php endif; ?>
                <?php if ($addressOne !== ''): ?>
                    <p><?= htmlspecialchars($addressOne, ENT_QUOTES, 'UTF-8') ?></p>
                <?php endif; ?>
                <?php if ($addressTwo !== ''): ?>
                    <p><?= htmlspecialchars($addressTwo, ENT_QUOTES, 'UTF-8') ?></p>
                <?php endif; ?>
            </div>

            <div class="jazz-contact__details">
                <?php if ($email !== ''): ?>
                    <p class="jazz-contact__email">
                        <a href="mailto:<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?></a>
                    </p>
                <?php endif; ?>
                <?php if ($phoneOffice !== ''): ?>
                    <p class="jazz-contact__phone">
                        <?= htmlspecialchars($phoneOffice, ENT_QUOTES, 'UTF-8') ?>
                        <?php if ($officeHours !== ''): ?>
                            <span class="jazz-contact__hours"><?= htmlspecialchars($officeHours, ENT_QUOTES, 'UTF-8') ?></span>
                        <?php endif; ?>
                    </p>
                <?php endif; ?>
                <?php if ($phoneCashDesk !== ''): ?>
                    <p class="jazz-contact__phone"><?= htmlspecialchars($phoneCashDesk, ENT_QUOTES, 'UTF-8') ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
