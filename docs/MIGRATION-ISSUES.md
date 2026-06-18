# Migration system — known problems (team ticket)

Discovered while building the order/booking refactor. The migration tooling and the
dev database are inconsistent across branches. **`migrate up` now works** (fixes below),
but `migrate reset` is still broken and the schema has duplicate/renamed tables that
need a team decision.

## 1. Two migration tools mixed in one folder
Some files are plain SQL for the custom **`migrate.php`** runner (need `USE haarlem_festival;`).
Others use **dbmate** syntax (`-- migrate:up` / `-- migrate:down`). Neither tool can run the
whole set as-is.

**Decision needed:** pick ONE canonical tool. Recommend standardizing on `migrate.php`
(it's what the project ships) and converting the dbmate-style files.

## 2. `migrate.php` executes the `-- migrate:down` SQL too
`migrate.php` runs the *entire* file, so any SQL after `-- migrate:down` also executes.
For ~11 files this means the down step undoes the up step. Examples:
- `01_schema.sql` down = `DROP TABLE restaurant/program_item/payment` → a **reset would drop those tables**.
- `02`, `11_stories_booking` down = revert the `section_type` ENUM.
- `03`, `04` down = delete seeded sections.

So `migrate reset` cannot be trusted until the `-- migrate:down` blocks are removed from
every file (or `migrate.php` is taught to split up/down).

## 3. Missing `USE haarlem_festival;`
`10_payments_table.sql` (fixed) and `11_history_tour_schedule_guides.sql` (still missing)
have no `USE`, so they fail with `1046 No database selected` under `migrate.php`.

## 4. ENUM `MODIFY` migrations truncate in-use values
Several `ALTER TABLE page_section MODIFY section_type ENUM(...)` migrations omit values that
existing rows use, which truncates those rows and aborts. Fixed in `05–09`, `15`, and
`11_stories_booking`; any future ENUM change must include the full current value set.

## 5. Duplicate / renamed / stray tables (need reconciliation)
- **`payment` vs `payments`** — both exist. `payment` is now the **canonical** order flow (`OrderRepository`); `payments` (plural) is only written by the **legacy** `PaymentController` → `StripeService` → `PaymentRepository` flow behind `/payments/checkout/{order_id}`. Consolidate onto `payment` and retire that legacy flow.
- **`order_ticket` vs `order_item`** — dev code referenced `order_ticket`; the refactor unified everything on `order_line`. The live DB has `order_item` (0 rows), now **dead** — it is only reachable via the legacy payment flow above.
- **`test_ok`** — genuine stray (not in `01_schema.sql`, `schema.sql`, or seeds). Safe to drop now → `22_cleanup_dead_artifacts.sql`.
- **`program_item`** — NOT a stray: it is canonical schema (`01_schema.sql`, `schema.sql`, `seeds/01_sample_data.sql`) for an older "My Program" persistence idea. Current code keeps My Program in the session (`$_SESSION['program_items']`), so the table is unused but **must not be dropped** without a team decision.
- **legacy `restaurant`** — was a misnamed reservation-shaped table (no name/slug); replaced by a real `restaurant` venue table in `18_order_booking_entities.sql`.

## 6. Order/booking refactor — final schema (this branch)
Entities the refactor added/normalised (idempotent migrations `18`–`21`):
- `restaurant` — real venue (name, slug UNIQUE, capacity, location_id). Seeded `ratatouille`, `bistro-toujours`.
- `reservation` — restaurant_id/user_id/order_id, date, session, adult/child counts, status. Capacity enforced at checkout (`ReservationService`).
- `order_line` — unified line for every item type, with `vat_rate` (9% food/drink, 21% else), `reservation_id`, and a JSON `item_data` catch-all (still in use — do not drop).
- `ticket.order_line_id` — tickets now attach to an order line (one QR per seat / per booking).
- `order.invoice_number` + `invoice_issued_at` + `total_price` — sequential invoice number `HF-YYYY-NNNNNN`; PDF invoice emailed + downloadable.

## What was fixed (so `migrate up` runs clean)
- `10_payments_table.sql` — added `USE`, removed destructive `-- migrate:down`.
- `11_stories_booking_section_type.sql` — up ENUM made a complete superset; removed `-- migrate:down`.
- `17_order_ticket_optional_type.sql` — guarded so it's a safe no-op when `order_ticket` is absent.

## Still open (for the team)
- Strip `-- migrate:down` from all files (or split up/down properly) so `reset` works.
- Add `USE` to `11_history_tour_schedule_guides.sql`.
- Standardize on one migration tool.

### Cleanup plan (ordered by risk)
1. **Safe now** — `test_ok` is dropped in `22_cleanup_dead_artifacts.sql` (stray, 0 rows, not in canonical schema/seeds).
2. **Needs the legacy payment flow retired first** — once `/payments/checkout/{order_id}`
   (`PaymentController` → `StripeService` → `PaymentRepository`) is folded into the canonical
   `payment`/`OrderRepository` flow, drop `payments` (plural) and then `order_item` and the
   legacy `order.amount` / `order.vat` columns and `ticket.order_item_id` (all 0 rows, only
   reachable through that flow).
3. **Team decision** — `program_item` is canonical schema (`01_schema.sql`, `schema.sql`,
   `seeds`) but unused (My Program lives in the session). Keep or remove deliberately.
