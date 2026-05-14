(function () {
    // Handle the interactive booking widget on the History book tour page.
    const widgets = document.querySelectorAll('[data-booking-widget]');
    const formatter = new Intl.NumberFormat('nl-NL', {
        style: 'currency',
        currency: 'EUR',
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });

    widgets.forEach((widget) => {
        const dayButtons = Array.from(widget.querySelectorAll('[data-booking-choice="day"]'));
        const timeButtons = Array.from(widget.querySelectorAll('[data-booking-choice="time"]'));
        const languageButtons = Array.from(widget.querySelectorAll('[data-booking-choice="language"]'));
        const ticketButtons = Array.from(widget.querySelectorAll('[data-booking-ticket]'));
        const quantityButtons = Array.from(widget.querySelectorAll('[data-booking-quantity-action]'));
        const bookingForm = widget.querySelector('.history-tour-booking-form');

        const inputs = {
            day: widget.querySelector('[data-booking-input="day"]'),
            time: widget.querySelector('[data-booking-input="time"]'),
            language: widget.querySelector('[data-booking-input="language"]'),
            ticketKey: widget.querySelector('[data-booking-input="ticket_key"]'),
            ticketTitle: widget.querySelector('[data-booking-input="ticket_title"]'),
            quantity: widget.querySelector('[data-booking-input="quantity"]'),
            unitPrice: widget.querySelector('[data-booking-input="unit_price"]'),
            totalPrice: widget.querySelector('[data-booking-input="total_price"]'),
            selectionText: widget.querySelector('[data-booking-input="selection_text"]'),
            ticketSummaryText: widget.querySelector('[data-booking-input="ticket_summary_text"]')
        };

        const summaries = {
            quantity: widget.querySelector('[data-booking-summary="quantity"]'),
            selection: widget.querySelector('[data-booking-summary="selection"]'),
            ticket: widget.querySelector('[data-booking-summary="ticket"]'),
            total: widget.querySelector('[data-booking-summary="total"]'),
            note: widget.querySelector('[data-booking-summary="note"]')
        };

        const submitButton = widget.querySelector('[data-booking-submit]');

        const labels = {
            individual: ticketButtons.find((button) => button.dataset.bookingTicket === 'individual')?.dataset.label || 'Individual',
            family: ticketButtons.find((button) => button.dataset.bookingTicket === 'family')?.dataset.label || 'Family'
        };

        const state = {
            day: widget.dataset.defaultDay || '',
            time: widget.dataset.defaultTime || '',
            language: widget.dataset.defaultLanguage || '',
            ticket: widget.dataset.defaultTicket === 'family' || widget.dataset.defaultTicket === 'individual'
                ? widget.dataset.defaultTicket
                : '',
            quantity: Math.max(1, parseInt(widget.dataset.defaultQuantity || '1', 10) || 1)
        };

        const prices = {
            individual: parseFloat(widget.dataset.individualPrice || '0') || 0,
            family: parseFloat(widget.dataset.familyPrice || '0') || 0
        };

        const familySize = Math.max(1, parseInt(widget.dataset.familySize || '4', 10) || 4);

        function setSelected(buttons, selectedValue, valueReader) {
            buttons.forEach((button) => {
                const isSelected = valueReader(button) === selectedValue;
                button.classList.toggle('is-selected', isSelected);
                button.setAttribute('aria-pressed', isSelected ? 'true' : 'false');
            });
        }

        function currentUnitPrice() {
            if (!state.ticket) {
                return 0;
            }

            return state.ticket === 'family' ? prices.family : prices.individual;
        }

        function currentTicketTitle() {
            if (!state.ticket) {
                return '';
            }

            return state.ticket === 'family' ? labels.family : labels.individual;
        }

        function currentSelectionText() {
            const parts = [state.day, state.time].filter(Boolean);
            let text = parts.join(', ');

            if (state.language) {
                text += (text ? ' | ' : '') + state.language;
            }

            return text;
        }

        function currentTicketSummary() {
            if (!state.ticket) {
                return '';
            }

            if (state.ticket === 'family') {
                if (state.quantity === 1) {
                    return labels.family + ' (up to ' + familySize + ')';
                }

                return state.quantity + ' ' + labels.family.toLowerCase() + ' tickets (up to ' + (state.quantity * familySize) + ' people)';
            }

            return state.quantity === 1 ? '1 person' : state.quantity + ' people';
        }

        function currentTotalPrice() {
            return currentUnitPrice() * state.quantity;
        }

        function isComplete() {
            return state.day !== '' && state.time !== '' && state.language !== '' && state.ticket !== '';
        }

        function currentSavings() {
            if (state.ticket !== 'family') {
                return 0;
            }

            return (prices.individual * familySize * state.quantity) - currentTotalPrice();
        }

        // Refresh the button states, summaries, and hidden form inputs.
        function updateView() {
            setSelected(dayButtons, state.day, (button) => button.dataset.value || '');
            setSelected(timeButtons, state.time, (button) => button.dataset.value || '');
            setSelected(languageButtons, state.language, (button) => button.dataset.value || '');
            setSelected(ticketButtons, state.ticket, (button) => button.dataset.bookingTicket || '');

            const selectionText = currentSelectionText();
            const ticketSummary = currentTicketSummary();
            const unitPrice = currentUnitPrice();
            const totalPrice = currentTotalPrice();
            const savings = currentSavings();
            const hasCompleteSelection = isComplete();

            if (summaries.quantity) summaries.quantity.textContent = String(state.quantity);
            if (summaries.selection) summaries.selection.textContent = selectionText || 'Choose day, time, and language';
            if (summaries.ticket) summaries.ticket.textContent = ticketSummary || 'Choose a ticket type';
            if (summaries.total) summaries.total.textContent = formatter.format(totalPrice);
            if (summaries.note) summaries.note.textContent = savings > 0 ? 'Save ' + formatter.format(savings) + ' vs individual tickets!' : '';

            if (inputs.day) inputs.day.value = state.day;
            if (inputs.time) inputs.time.value = state.time;
            if (inputs.language) inputs.language.value = state.language;
            if (inputs.ticketKey) inputs.ticketKey.value = state.ticket;
            if (inputs.ticketTitle) inputs.ticketTitle.value = currentTicketTitle();
            if (inputs.quantity) inputs.quantity.value = String(state.quantity);
            if (inputs.unitPrice) inputs.unitPrice.value = unitPrice.toFixed(2);
            if (inputs.totalPrice) inputs.totalPrice.value = hasCompleteSelection ? totalPrice.toFixed(2) : '0.00';
            if (inputs.selectionText) inputs.selectionText.value = hasCompleteSelection ? selectionText : '';
            if (inputs.ticketSummaryText) inputs.ticketSummaryText.value = state.ticket ? ticketSummary : '';
            if (submitButton) submitButton.disabled = !hasCompleteSelection;
        }

        dayButtons.forEach((button) => {
            button.addEventListener('click', () => {
                state.day = button.dataset.value || '';
                updateView();
            });
        });

        timeButtons.forEach((button) => {
            button.addEventListener('click', () => {
                state.time = button.dataset.value || '';
                updateView();
            });
        });

        languageButtons.forEach((button) => {
            button.addEventListener('click', () => {
                state.language = button.dataset.value || '';
                updateView();
            });
        });

        ticketButtons.forEach((button) => {
            button.addEventListener('click', () => {
                state.ticket = button.dataset.bookingTicket === 'family' ? 'family' : 'individual';
                updateView();
            });
        });

        quantityButtons.forEach((button) => {
            button.addEventListener('click', () => {
                if (button.dataset.bookingQuantityAction === 'decrease') {
                    state.quantity = Math.max(1, state.quantity - 1);
                } else {
                    state.quantity = Math.min(10, state.quantity + 1);
                }

                updateView();
            });
        });

        if (bookingForm && submitButton) {
            // Prevent double-submit after the booking is complete.
            bookingForm.addEventListener('submit', () => {
                if (!isComplete() || submitButton.disabled) {
                    return;
                }

                submitButton.disabled = true;
            });
        }

        updateView();
    });

    const scheduleWidgets = document.querySelectorAll('[data-schedule-widget]');
    scheduleWidgets.forEach((widget) => {
        const scheduleButtons = Array.from(widget.querySelectorAll('[data-schedule-choice]'));
        const schedulePanels = Array.from(widget.querySelectorAll('[data-schedule-panel]'));

        let activeDay = widget.dataset.defaultDay || scheduleButtons[0]?.dataset.value || '';

        function updateSchedule() {
            scheduleButtons.forEach((button) => {
                const isSelected = (button.dataset.value || '') === activeDay;
                button.classList.toggle('is-selected', isSelected);
                button.setAttribute('aria-pressed', isSelected ? 'true' : 'false');
            });

            schedulePanels.forEach((panel) => {
                const isActive = (panel.dataset.schedulePanel || '') === activeDay;
                panel.classList.toggle('is-active', isActive);
                panel.hidden = !isActive;
            });
        }

        scheduleButtons.forEach((button) => {
            button.addEventListener('click', () => {
                activeDay = button.dataset.value || '';
                updateSchedule();
            });
        });

        updateSchedule();
    });
})();
