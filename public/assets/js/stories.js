document.addEventListener('DOMContentLoaded', function () {
    // Filter pills — toggle active state (visual only; filtering wired server-side)
    document.querySelectorAll('.sched-filter-group').forEach(function (group) {
        group.querySelectorAll('.sched-filter-pill').forEach(function (pill) {
            pill.addEventListener('click', function () {
                group.querySelectorAll('.sched-filter-pill').forEach(function (p) {
                    p.classList.remove('active');
                });
                pill.classList.add('active');
            });
        });
    });

    // Booking stepper
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
