-- Cleanup: drop the stray `test_ok` table. It is not part of the canonical schema
-- (`01_schema.sql`, `schema.sql`) or seeds, holds 0 rows, and nothing references it.
-- Idempotent; safe no-op if already gone. Other legacy objects (payments/order_item/
-- order.amount/order.vat/program_item) need team coordination first — see
-- docs/MIGRATION-ISSUES.md "Cleanup plan". No `-- migrate:down` block on purpose:
-- migrate.php executes the whole file, so a down block here would run on every up.
USE haarlem_festival;

DROP TABLE IF EXISTS test_ok;
