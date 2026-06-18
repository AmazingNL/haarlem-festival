document.addEventListener('DOMContentLoaded', function () {

    // ── Schedule filter ──────────────────────────────────────────────
    var activeLang = 'all';
    var activeDay  = 'thursday';

    function applyFilters() {
        var cards   = document.querySelectorAll('#sched-events .sched-card');
        var noRes   = document.getElementById('sched-no-results');
        var visible = 0;

        cards.forEach(function (card) {
            var dayMatch  = card.dataset.day  === activeDay;
            var langMatch = activeLang === 'all' || card.dataset.lang === activeLang;
            var show      = dayMatch && langMatch;
            card.hidden   = !show;
            if (show) visible++;
        });

        if (noRes) noRes.hidden = visible > 0;
    }

    // Language pills
    var langGroup = document.querySelector('[data-filter="lang"]');
    if (langGroup) {
        langGroup.querySelectorAll('.sched-filter-pill').forEach(function (pill) {
            pill.addEventListener('click', function () {
                langGroup.querySelectorAll('.sched-filter-pill').forEach(function (p) {
                    p.classList.remove('active');
                    p.setAttribute('aria-selected', 'false');
                });
                pill.classList.add('active');
                pill.setAttribute('aria-selected', 'true');
                activeLang = pill.dataset.value;
                applyFilters();
            });
        });
    }

    // Day tabs
    document.querySelectorAll('.sched-day-tab').forEach(function (tab) {
        tab.addEventListener('click', function () {
            document.querySelectorAll('.sched-day-tab').forEach(function (t) {
                t.classList.remove('active');
                t.setAttribute('aria-selected', 'false');
            });
            tab.classList.add('active');
            tab.setAttribute('aria-selected', 'true');
            activeDay = tab.dataset.value;
            applyFilters();
        });
    });

    // Run once on load so Thursday cards show, rest are hidden
    applyFilters();

    // ── Booking stepper ───────────────────────────────────────────────
    document.querySelectorAll('.sbc-stepper').forEach(function (stepper) {
        var minus = stepper.querySelector('.sbc-stepper-btn:first-child');
        var plus  = stepper.querySelector('.sbc-stepper-btn:last-child');
        var qty   = stepper.querySelector('.sbc-stepper-count');
        if (!minus || !plus || !qty) return;
        minus.addEventListener('click', function () {
            var v = parseInt(qty.value, 10);
            if (v > 1) qty.value = v - 1;
        });
        plus.addEventListener('click', function () {
            var v = parseInt(qty.value, 10);
            if (v < 20) qty.value = v + 1;
        });
    });
});
