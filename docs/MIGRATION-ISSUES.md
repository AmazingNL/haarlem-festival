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
- **`payment` vs `payments`** — both exist; `OrderRepository` uses `payment`, `PaymentRepository` uses `payments`. Pick one.
- **`order_ticket` vs `order_item`** — dev code references `order_ticket`, but the live DB has `order_item`. (The order/booking refactor removes `order_ticket` in favour of a unified `order_line`.)
- **`test_ok`** — stray table, drop it.
- **legacy `restaurant`** — was a misnamed reservation-shaped table (no name/slug); replaced by a real `restaurant` venue table in `18_order_booking_entities.sql`.

## What was fixed (so `migrate up` runs clean)
- `10_payments_table.sql` — added `USE`, removed destructive `-- migrate:down`.
- `11_stories_booking_section_type.sql` — up ENUM made a complete superset; removed `-- migrate:down`.
- `17_order_ticket_optional_type.sql` — guarded so it's a safe no-op when `order_ticket` is absent.

## Still open (for the team)
- Strip `-- migrate:down` from all files (or split up/down properly) so `reset` works.
- Add `USE` to `11_history_tour_schedule_guides.sql`.
- Reconcile `payment`/`payments`, remove `order_item`/`test_ok`, standardize the tool.
