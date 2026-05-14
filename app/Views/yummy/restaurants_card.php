<section class="page-container">
    <h2 class="restaurants-heading">All Restaurants</h2>

    <section class="restaurants-grid">
        <?php
        // Keep only valid card arrays from the incoming data.
        $restaurantCards = array_values(array_filter(
            $resCard ?? [],
            static fn ($card): bool => is_array($card)
        ));

        // Normalize scalar-or-array values into a trimmed string.
        $normalizeText = static function (mixed $value, string $default = ''): string {
            if (is_array($value)) {
                $value = $value[0] ?? $default;
            }

            $text = trim((string) $value);
            return $text;
        };

        // Accept cuisine as array, comma-separated text, or newline-separated text.
        $normalizeCuisines = static function (mixed $value): array {
            if (is_array($value)) {
                return array_values(array_filter(array_map(
                    static fn (mixed $item): string => trim((string) $item),
                    $value
                )));
            }

            $text = trim((string) $value);
            if ($text === '') {
                return [];
            }

            return array_values(array_filter(array_map(
                static fn (string $item): string => trim($item),
                preg_split('/[\r\n,]+/', $text) ?: []
            )));
        };

        // Resolve card image with fallback when data is missing.
        $getImage = static function (array $card): string {
            $image = $card['section_image'] ?? $card['image'] ?? '';
            if (is_array($image)) {
                $image = $image[0] ?? '';
            }

            $image = trim((string) $image);
            return $image !== '' ? $image : '/assets/images/yummy/yummy.jpg';
        };
        ?>

        <?php if ($restaurantCards === []): ?>
            <article class="restaurant-card">
                <section class="card-body">
                    <h3 class="card-title">No restaurants yet</h3>
                </section>
            </article>
        <?php endif; ?>

        <?php foreach ($restaurantCards as $card): ?>
            <?php
            // Shape each card field into safe, display-ready values.
            $title = $normalizeText($card['title'] ?? '', 'Restaurant');
            $introduction = preg_replace('/\s+/', ' ', trim(strip_tags($normalizeText($card['introduction'] ?? '', ''))));
            $rating = number_format((float) $normalizeText($card['rating'] ?? '0.0', '0.0'), 1, '.', '');
            $buttonText = $normalizeText($card['button_text'] ?? 'View', 'View');
            $buttonLink = $normalizeText($card['button_link'] ?? '#', '#');
            $capacity = $normalizeText($card['capacity'] ?? '', '0');
            $cuisines = $normalizeCuisines($card['cuisine'] ?? []);
            $image = $getImage($card);
            ?>

            <article class="restaurant-card">
                <section class="card-media">
                    <img src="<?= htmlspecialchars($image, ENT_QUOTES, 'UTF-8') ?>"
                        alt="<?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?>">
                    <section class="rating-tag">
                        <section class="rating">
                            <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M17.1595 4.30522L19.3592 8.7411C19.6592 9.35858 20.4591 9.95087 21.1341 10.0643L25.1211 10.7322C27.6709 11.1607 28.2709 13.0257 26.4335 14.8656L23.3339 17.9909C22.8089 18.5201 22.5215 19.5409 22.6839 20.2719L23.5714 24.1406C24.2712 27.2029 22.6589 28.3875 19.9717 26.787L16.2346 24.5565C15.5597 24.1532 14.4474 24.1532 13.7599 24.5565L10.0228 26.787C7.34812 28.3875 5.72331 27.1902 6.42324 24.1406L7.31064 20.2719C7.47311 19.5409 7.18565 18.5201 6.6607 17.9909L3.56105 14.8656C1.73625 13.0257 2.32369 11.1607 4.8734 10.7322L8.86046 10.0643C9.52289 9.95087 10.3228 9.35858 10.6228 8.7411L12.8225 4.30522C14.0224 1.89826 15.9721 1.89826 17.1595 4.30522Z"
                                    fill="#F7D117" stroke="#AA0000" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                            <p><?= htmlspecialchars($rating, ENT_QUOTES, 'UTF-8') ?></p>
                        </section>

                        <a class="fav" aria-label="Save to favorites">
                            <svg class="icon-fixed icon-favorite" width="30" height="30" viewBox="0 0 30 30"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M11.7634 26.2096C8.23677 23.5725 1.25 17.5435 1.25 12.118C1.25 8.53204 3.88157 5.625 7.5 5.625C9.375 5.625 11.25 6.25 13.75 8.75C16.25 6.25 18.125 5.625 20 5.625C23.6184 5.625 26.25 8.53204 26.25 12.118C26.25 17.5435 19.2633 23.5725 15.7366 26.2096C14.5499 27.097 12.9501 27.097 11.7634 26.2096Z"
                                    stroke="#F7D117" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path
                                    d="M13.0134 24.9596C9.48677 22.3225 2.5 16.2935 2.5 10.868C2.5 7.28204 5.13157 4.375 8.75 4.375C10.625 4.375 12.5 5 15 7.5C17.5 5 19.375 4.375 21.25 4.375C24.8684 4.375 27.5 7.28204 27.5 10.868C27.5 16.2935 20.5133 22.3225 16.9866 24.9596C15.7999 25.847 14.2001 25.847 13.0134 24.9596Z"
                                    stroke="#AA0000" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>
                    </section>
                </section>

                <section class="card-body">
                    <h3 class="card-title"><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h3>
                    <?php if ($introduction !== ''): ?>
                        <p class="card-excerpt"><?= htmlspecialchars($introduction, ENT_QUOTES, 'UTF-8') ?></p>
                    <?php endif; ?>

                    <?php if ($cuisines !== []): ?>
                        <section class="card-tags">
                            <?php foreach ($cuisines as $cuisine): ?>
                                <span><?= htmlspecialchars($cuisine, ENT_QUOTES, 'UTF-8') ?></span>
                            <?php endforeach; ?>
                        </section>
                    <?php endif; ?>

                    <section class="card-footer">
                        <p>Available Seats</p>
                        <section class="strong">
                            <strong><?= htmlspecialchars($capacity, ENT_QUOTES, 'UTF-8') ?></strong>
                            <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M3.75 15V21.25M3.75 21.25H6.25C8.01776 21.25 8.90165 21.25 9.45082 21.7991C10 22.3484 10 23.2323 10 25V26.25M3.75 21.25V26.25"
                                    stroke="#AA0000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path
                                    d="M5 8.75L11.6459 5.10431C13.2917 4.20144 14.1148 3.75 15 3.75C15.8852 3.75 16.7083 4.20144 18.3541 5.10431L25 8.75"
                                    stroke="#AA0000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M22.5 7.5V12.5M7.5 7.5V12.5" stroke="#AA0000" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                                <path
                                    d="M26.25 15V21.25M26.25 21.25H23.75C21.9823 21.25 21.0984 21.25 20.5491 21.7991C20 22.3484 20 23.2323 20 25V26.25M26.25 21.25V26.25"
                                    stroke="#AA0000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M8.75 17.5H15M15 17.5H21.25M15 17.5V26.25M15 26.25H13.75M15 26.25H16.25"
                                    stroke="#AA0000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </section>
                    </section>

                    <section class="primary-button footer-button">
                        <a href="<?= htmlspecialchars($buttonLink, ENT_QUOTES, 'UTF-8') ?>">
                            <?= htmlspecialchars($buttonText, ENT_QUOTES, 'UTF-8') ?>
                        </a>
                    </section>
                </section>
            </article>
        <?php endforeach; ?>
    </section>
</section>
