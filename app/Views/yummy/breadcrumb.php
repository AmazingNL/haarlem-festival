<nav class="yummy-breadcrumb" aria-label="Breadcrumb">
    <div class="yummy-breadcrumb__inner">
        <a href="/" class="crumb crumb--home">
            <svg class="icon-chevron" width="10" height="10" viewBox="0 0 24 24" fill="none"
                xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M8 4l8 8-8 8" stroke="#c33" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <span>Home</span>
        </a>

        <span class="crumb-sep">›</span>

        <div class="crumb crumb--current">
            <span>Restaurant</span>
            <svg class="icon-caret" width="12" height="8" viewBox="0 0 12 8" fill="none"
                xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M1 1.5l5 4 5-4" stroke="#3b0b0b" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </div>
    </div>
</nav>

<style>
    .yummy-breadcrumb {
        width: 100%;
        background: #9f9999;
        box-sizing: border-box;
    }

    .yummy-breadcrumb__inner {
        max-width: 1200px;
        margin: 0 auto;
        padding: 18px 24px;
        display: flex;
        align-items: center;
        gap: 18px;
        font-family: Poppins, system-ui, sans-serif;
    }

    .crumb {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
        color: #ffffff;
        font-size: 18px;
    }

    .crumb--home span {
        color: #fff;
    }

    .icon-chevron {
        display: inline-block;
        margin-right: 6px;
    }

    .crumb-sep {
        color: rgba(0, 0, 0, 0.25);
        font-size: 18px;
        margin-left: 6px;
        margin-right: 6px;
    }

    .crumb--current {
        color: #3b0b0b;
        /* deep burgundy for current */
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 18px;
    }

    .icon-caret {
        opacity: 0.9;
    }

    /* Responsive adjustments */
    @media (max-width: 700px) {
        .yummy-breadcrumb__inner {
            padding: 12px 16px;
        }

        .crumb,
        .crumb--current {
            font-size: 16px;
        }
    }
</style>