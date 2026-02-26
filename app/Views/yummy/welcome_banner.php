<section class="welcome-banner">
    <div class="welcome-card">
        <a class="welcome-card__back"
            href="<?= htmlspecialchars($s['back_link'] ?? '/yummy', ENT_QUOTES, 'UTF-8') ?>">&larr; Back</a>

        <div class="welcome-card__inner">
            <?php if (!empty($s['title'])):
                $title = (string) $s['title'];
                $spacePos = strpos($title, ' ');
                if ($spacePos !== false) {
                    $first = substr($title, 0, $spacePos);
                    $rest = trim(substr($title, $spacePos));
                } else {
                    $first = $title;
                    $rest = '';
                }
                ?>
                <h1 class="welcome-card__title">
                    <span class="welcome-card__title--accent"><?= htmlspecialchars($first, ENT_QUOTES, 'UTF-8') ?></span>
                    <?php if ($rest !== ''): ?>
                        <span class="welcome-card__title--main"><?= htmlspecialchars($rest, ENT_QUOTES, 'UTF-8') ?></span>
                    <?php endif; ?>
                </h1>
            <?php endif; ?>

            <?php if (!empty($s['content'])): ?>
                <div class="welcome-card__content">
                    <?= $s['content'] ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($s['button_text']) && !empty($s['button_link'])): ?>
                <a class="welcome-card__button"
                    href="<?= htmlspecialchars((string) $s['button_link'], ENT_QUOTES, 'UTF-8') ?>">
                    <?= htmlspecialchars((string) $s['button_text'], ENT_QUOTES, 'UTF-8') ?>
                </a>
            <?php endif; ?>
        </div>
    </div>
</section>

<style>
    /* HERO BACKGROUND IS NOW THE SECTION */
    .welcome-banner {
        height: 400px;
        background-image: url('/assets/images/admin/9b6d4a6e36ebd72fc86d1d10f2c39660.jpg');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }

    /* CARD BECOMES A TRANSPARENT “CONTENT WRAPPER” */
    .welcome-card {
        width: min(1400px, calc(100% - 80px));
        min-height: 420px;
        border-radius: 8px;
        position: relative;
        overflow: hidden;
        background: transparent;
    }

    .welcome-card__back {
        position: absolute;
        top: 14px;
        left: 14px;
        z-index: 3;
        color: #fff;
        text-decoration: none;
        background: rgba(0, 0, 0, 0.55);
        border: 1px solid rgba(255, 255, 255, 0.10);
        padding: 8px 14px;
        border-radius: 999px;
        font-weight: 700;
        font-size: 0.95rem;
    }

    .welcome-card__inner {
        position: relative;
        z-index: 2;
        padding: 56px 56px;
        max-width: 640px;
        color: #fff;
    }

    .welcome-card__title {
        margin: 0 0 16px 0;
        text-transform: uppercase;
        line-height: 0.92;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .welcome-card__title--accent {
        font-size: 4.1rem;
        font-weight: 900;
        background: linear-gradient(90deg, #f2b53a 0%, #e24a2b 45%, #c21f1f 100%);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        white-space: nowrap;
    }

    .welcome-card__title--main {
        font-size: 3.5rem;
        font-weight: 900;
        color: #fff;
        white-space: nowrap;
    }

    .welcome-card__content {
        max-width: 520px;
        font-size: 1.02rem;
        line-height: 1.75;
        color: rgba(255, 255, 255, 0.92);
        margin-bottom: 22px;
    }

    .welcome-card__button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        min-width: 340px;
        padding: 14px 34px;
        border-radius: 999px;
        background: #7f1414;
        color: #fff;
        font-weight: 800;
        text-decoration: none;
        border: 1px solid rgba(255, 255, 255, 0.08);
    }

    .welcome-card__button::after {
        content: "›";
        font-size: 1.25rem;
    }

    @media (max-width: 1100px) {
        .welcome-card__inner {
            padding: 56px 44px;
        }

        .welcome-card__title--accent {
            font-size: 3.1rem;
        }

        .welcome-card__title--main {
            font-size: 2.7rem;
        }
    }

    @media (max-width: 600px) {
        .welcome-card {
            width: calc(100% - 28px);
        }

        .welcome-card__inner {
            padding: 64px 22px 28px;
            max-width: 100%;
        }

        .welcome-card__button {
            width: 100%;
            min-width: 0;
        }

        .welcome-banner::after {
            background: linear-gradient(180deg,
                    rgba(8, 9, 10, 0.88) 0%,
                    rgba(8, 9, 10, 0.55) 60%,
                    rgba(8, 9, 10, 0.45) 100%);
        }
    }
</style>