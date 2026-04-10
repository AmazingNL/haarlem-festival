# Haarlem Festival

## Quick Start

When you first clone the project:

```bash
# 1. Start services
docker compose up --build

# 2. Create database schema (migrations)
docker compose exec php php /app/migrate.php up


# 3. Backup & Restore (Real Project Data)

- `backup.sql` = Real project data (actual restaurants, events, etc.)
- You need `backup.sql` to share real data with teammates


```bash
# exports
docker compose exec mysql sh -c 'mariadb-dump -uroot -psecret123 haarlem_festival' > backup.sql

# restore:
cat backup.sql | docker compose exec -T mysql sh -c 'mariadb -uroot -psecret123 haarlem_festival'
```


