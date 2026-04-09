<section class="history-tour-message history-tour-message--notice">
    <div class="history-container">
        <div class="history-tour-message__box">
            <h2><?= htmlspecialchars((string) ($s['title'] ?? ''), ENT_QUOTES, 'UTF-8') ?></h2>
            <p><?= $historyText($s['body'] ?? '') ?></p>
        </div>
    </div>
</section>
