document.addEventListener('DOMContentLoaded', function () {
    const tabs = document.querySelectorAll('.sched-tab');
    const days = document.querySelectorAll('.sched-day');

    if (!tabs.length) return;

    // Show first day by default
    if (days.length) days[0].classList.add('active');

    tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            tabs.forEach(function (t) { t.classList.remove('active'); });
            days.forEach(function (d) { d.classList.remove('active'); });

            tab.classList.add('active');
            const target = document.querySelector('.sched-day[data-day="' + tab.dataset.target + '"]');
            if (target) target.classList.add('active');
        });
    });
});
