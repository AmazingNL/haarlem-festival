# Haarlem Festival

## Quick Start

1. Start services:

```bash
docker compose up --build
```

2. Run schema migrations:

```bash
docker compose exec php php /app/migrate.php up
```

3. Load sample data (optional, recommended for local development):

```bash
docker compose exec php php /app/migrate.php seed
```

## Database Commands

- `up`: applies schema migrations from `db/migrations`.
- `seed`: loads sample data from `db/seeds`.
- `reset --force`: drops all tables, recreates schema, then reseeds.

```bash
docker compose exec php php /app/migrate.php reset --force
```

## Backup And Restore

Your local database data is stored in Docker volume `mysqldata` and is not committed to Git.
To share real data between machines, export/import a SQL dump.

### Create Backup

```bash
docker compose exec mysql sh -c 'mariadb-dump -uroot -psecret123 haarlem_festival' > backup.sql
```

### Restore Backup

```bash
cat backup.sql | docker compose exec -T mysql sh -c 'mariadb -uroot -psecret123 haarlem_festival'
```

## Team Workflow

- New clone: run `up` and `seed` to get schema + demo data.
- Real project data: use backup/restore commands above.
- Avoid running `reset --force` unless you intentionally want to wipe local DB data.
