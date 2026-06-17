document.addEventListener('DOMContentLoaded', function () {
    var tabs = document.querySelectorAll('.sched-tab');
    var days = document.querySelectorAll('.sched-day');

    if (tabs.length && days.length) {
        days[0].classList.add('active');

        tabs.forEach(function (tab) {
            tab.addEventListener('click', function () {
                tabs.forEach(function (t) { t.classList.remove('active'); });
                days.forEach(function (d) { d.classList.remove('active'); });

                tab.classList.add('active');
                var target = document.querySelector('.sched-day[data-day="' + tab.dataset.target + '"]');
                if (target) target.classList.add('active');
            });
        });
    }

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
