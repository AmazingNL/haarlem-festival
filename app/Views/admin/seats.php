<style>
    .seats-overview .card a.seats-card__button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: fit-content;
        margin-top: 0.75rem;
        padding: 0.65rem 1.1rem;
        border-radius: 8px;
        background: #ffffff;
        color: #2d0a0a;
        border: 1px solid rgba(45, 10, 10, 0.25);
        font-weight: 700;
        text-decoration: none;
    }

    .seats-overview .card a.seats-card__button:hover,
    .seats-overview .card a.seats-card__button:focus-visible {
        background: #2d0a0a;
        color: #ffffff;
        border-color: #2d0a0a;
    }
</style>

<div class="admin-header mb-4">
    <div>
        <h1 class="admin-title">Seats Management</h1>
        <p class="muted mb-0">Manage available seats for festival parts.</p>
    </div>
    <div class="admin-actions">
        <a href="/admin/dashboard" class="btn-secondary">Back to Dashboard</a>
    </div>
</div>

<div class="cards seats-overview">
    <div class="card">
        <div class="card-header">
            <h2>Dance Ticket Availability</h2>
        </div>
        <p class="muted">Change available tickets for Dance event ticket types.</p>
        <a href="/admin/dance/seats" class="seats-card__button">Manage Dance Tickets</a>
    </div>
</div>
