(function () {
    const formatMoney = function (amount) {
        return Number.isInteger(amount) ? String(amount) : amount.toFixed(2);
    };

    const closePicker = function (picker) {
        const panel = picker.querySelector('.ratatouille-picker-panel');
        const toggle = picker.querySelector('.ratatouille-picker-toggle');

        if (!panel || !toggle) return;

        panel.hidden = true;
        picker.classList.remove('is-open');
        toggle.setAttribute('aria-expanded', 'false');
    };

    const initReservationCard = function (card) {
        if (card.dataset.reservationPickerReady === 'true') return;
        card.dataset.reservationPickerReady = 'true';

        const totalRow = card.querySelector('.ratatouille-total-row strong');
        const totalInput = card.querySelector('[data-reservation-total-input]');

        const updateGrandTotal = function () {
            const total = Array.from(card.querySelectorAll('[data-ticket-picker]')).reduce(function (sum, picker) {
                const price = Number(picker.dataset.price || 0);
                const count = Number(picker.querySelector('[data-picker-input]')?.value || 0);
                return sum + (price * count);
            }, 0);

            if (totalRow) totalRow.textContent = '€ ' + formatMoney(total);
            if (totalInput) totalInput.value = formatMoney(total);
        };

        card.querySelectorAll('[data-ticket-picker]').forEach(function (picker) {
            const toggle = picker.querySelector('.ratatouille-picker-toggle');
            const panel = picker.querySelector('.ratatouille-picker-panel');
            const input = picker.querySelector('[data-picker-input]');
            const label = picker.querySelector('[data-picker-label]');
            const countText = picker.querySelector('[data-picker-count]');
            const totalText = picker.querySelector('[data-picker-total]');
            const summary = picker.previousElementSibling?.matches('[data-ticket-summary]')
                ? picker.previousElementSibling
                : null;
            const summaryCount = summary?.querySelector('[data-ticket-summary-count]');
            const summaryTotal = summary?.querySelector('[data-ticket-summary-total]');
            const decrease = picker.querySelector('[data-picker-decrease]');
            const increase = picker.querySelector('[data-picker-increase]');
            const close = picker.querySelector('[data-picker-close]');
            const price = Number(picker.dataset.price || 0);
            let count = Number(input?.value || 0);

            if (!toggle || !panel || !input || !label || !countText || !totalText || !decrease || !increase || !close) {
                return;
            }

            const setOpen = function (open) {
                panel.hidden = !open;
                picker.classList.toggle('is-open', open);
                toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            };

            const render = function () {
                input.value = String(count);
                countText.textContent = String(count);
                totalText.textContent = formatMoney(price * count);
                if (summaryCount) summaryCount.textContent = String(count);
                if (summaryTotal) summaryTotal.textContent = '€ ' + formatMoney(price * count);
                label.textContent = count > 0 ? count + ' selected' : 'Choose here';
                updateGrandTotal();
            };

            toggle.addEventListener('click', function () {
                const nextOpen = panel.hidden;
                card.querySelectorAll('[data-ticket-picker]').forEach(function (otherPicker) {
                    if (otherPicker !== picker) closePicker(otherPicker);
                });
                setOpen(nextOpen);
            });

            decrease.addEventListener('click', function () {
                count = Math.max(0, count - 1);
                render();
            });

            increase.addEventListener('click', function () {
                count = Math.min(10, count + 1);
                render();
            });

            close.addEventListener('click', function () {
                setOpen(false);
            });

            render();
        });

        document.addEventListener('click', function (event) {
            if (card.contains(event.target)) return;
            card.querySelectorAll('[data-ticket-picker]').forEach(closePicker);
        });
    };

    document.querySelectorAll('.ratatouille-booking-card').forEach(initReservationCard);
})();