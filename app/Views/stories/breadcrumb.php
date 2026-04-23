<nav class="stories-breadcrumb" aria-label="Breadcrumb">
    <div class="stories-breadcrumb__inner">
        <a href="/" class="crumb crumb--home">
            <svg class="icon-chevron" width="10" height="10" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M8 4l8 8-8 8" stroke="#c33" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <span>Home</span>
        </a>
        <span class="crumb-sep">›</span>
        <span class="crumb crumb--current">Stories</span>
    </div>
</nav>

<style>
    .stories-breadcrumb {
        width: 100%;
        background: #9f9999;
        box-sizing: border-box;
    }

    .stories-breadcrumb__inner {
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

    .crumb--home span { color: #fff; }
    .icon-chevron { display: inline-block; margin-right: 6px; }

    .crumb-sep {
        color: rgba(0, 0, 0, 0.25);
        font-size: 18px;
    }

    .crumb--current {
        color: #3b0b0b;
        font-weight: 600;
        font-size: 18px;
    }

    @media (max-width: 700px) {
        .stories-breadcrumb__inner { padding: 12px 16px; }
        .crumb, .crumb--current { font-size: 16px; }
    }

    @media (max-width: 420px) {
        .stories-breadcrumb__inner {
            gap: 10px;
            padding: 10px 12px;
        }

        .crumb,
        .crumb--current,
        .crumb-sep {
            font-size: 14px;
        }

        .crumb {
            gap: 6px;
        }
    }
</style>
