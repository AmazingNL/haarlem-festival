// Jazz agenda: show one day's performances at a time when a day tab is clicked.
(function () {
    document.addEventListener('DOMContentLoaded', function () {
        var tabs = Array.prototype.slice.call(document.querySelectorAll('.jazz-agenda__tab'));
        var panels = Array.prototype.slice.call(document.querySelectorAll('.jazz-agenda__panel'));
        if (tabs.length === 0) {
            return;
        }

        function showDay(day) {
            tabs.forEach(function (tab) {
                tab.classList.toggle('is-active', tab.getAttribute('data-jazz-tab') === day);
            });
            panels.forEach(function (panel) {
                panel.classList.toggle('is-active', panel.getAttribute('data-jazz-panel') === day);
            });
        }

        tabs.forEach(function (tab) {
            tab.addEventListener('click', function () {
                showDay(tab.getAttribute('data-jazz-tab'));
            });
        });
    });
})();
