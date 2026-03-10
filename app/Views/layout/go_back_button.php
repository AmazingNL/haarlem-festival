<style>
    .card-back a {
        text-decoration: none;
        border-radius: var(--primary-border-radius);
        align-items: center;
        background-color: var(--button-color-primary);
        color: var(--color-text-light);
        padding: 10px 15px 10px 10px;
        margin: 0 60px;
        position: relative;
        top: 10px;
    }

    .card-back {}

    .icon-arrow-back {
        transform: rotate(180deg);
    }

    .card-back a:hover {
        border: solid;
        border-color: var(--color-accent-pink);
        background-color: transparent;
    }
</style>


<section class="card-back">
    <a href="{{ url('/') }}">
        <svg id="backLink" class="icon-arrow-back icon-fixed" viewBox="0 0 24 24">
            <path d="M8 4l8 8-8 8" stroke="#fcf7f7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        <label id="label">Back</label>
    </a>
</section>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const link = document.querySelector('.card-back a');
        const label = document.getElementById('label');

        link.addEventListener('mouseenter', () => label.textContent = 'Go Back');
        link.addEventListener('mouseleave', () => label.textContent = 'Back');

        link.addEventListener('click', (e) => {
            if (window.history.length > 1) {
                e.preventDefault();
                window.history.back();
            }
        });
    });
</script>