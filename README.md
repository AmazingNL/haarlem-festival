# Haarlem Festival

## Project Setup

### 1. Start Containers

```bash
docker compose up --build
```

### 2. Apply Database Schema

```bash
docker compose exec php php /app/migrate.php up
```


## Database Setup & Management

### First Time Setup (Fresh Clone)

When you first clone the project:

```bash
docker compose exec php php /app/migrate.php reset --force
```

Warning: `reset --force` deletes your local DB data.

## Real Data: Backup And Restore

real local database data lives in Docker volume `mysqldata`.
To share real data between teammates, use `backup.sql`.

### Create Backup

```bash
docker compose exec mysql sh -c 'mariadb-dump -uroot -psecret123 haarlem_festival' > backup.sql
```

### Restore Backup

```bash
cat backup.sql | docker compose exec -T mysql sh -c 'mariadb -uroot -psecret123 haarlem_festival'
```

Note: restoring `backup.sql` replaces current data in `haarlem_festival`.

## Team Workflow (Share DB Data)

### A. You Share Your Data

```bash
# Export current DB to backup.sql
docker compose exec mysql sh -c 'mariadb-dump -uroot -psecret123 haarlem_festival' > backup.sql
```

Then send `backup.sql` to your teammate using your team file-sharing method.

### B. Teammate Pulls Your Data

```bash
# Restore shared snapshot
cat backup.sql | docker compose exec -T mysql sh -c 'mariadb -uroot -psecret123 haarlem_festival'
```

### C. You Pull Teammate Data

Exactly the same flow in reverse:

```bash
cat backup.sql | docker compose exec -T mysql sh -c 'mariadb -uroot -psecret123 haarlem_festival'
```

## Keep Your Own Local Data Before Restore

If you want a safety copy before replacing your DB:

```bash
docker compose exec mysql sh -c 'mariadb-dump -uroot -psecret123 haarlem_festival' > my_local_backup_YYYYMMDD.sql
```

Then restore team `backup.sql`.

## Practical Rules For Team

1. Export `backup.sql` only when you intentionally want to share updated real data.
2. Name shared files clearly if you keep versions (for example: `backup_2026-04-07.sql`).
3. Always make a personal backup before restoring someone else's snapshot.
4. Keep schema changes in migrations; keep test/demo data in seeds.
5. Treat `backup.sql` as a shared snapshot, not a migration history.
