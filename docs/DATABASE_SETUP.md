# Database Setup Guide

## Overview

This project uses **MariaDB** running in Docker, with migrations managed via `migrate.php` and optional `dbmate` support.

**Current Schema Version:** Migration `08_drop_page_section_image_id.sql` (latest)

---

## Quick Start for New Team Members

### 1. Clone and Setup Docker

```bash
# Clone the repository
git clone <repo-url>
cd Haarlem_Festival

# Build and start containers
docker compose up -d

# Wait for MySQL to be ready (check health at docker compose ps)
docker compose ps
```

### 2. Initialize Your Local Database

Run the migration suite to create all tables and apply pending schema changes:

```bash
# From project root
php migrate.php up

# Expected output:
# Running migration 01_schema.sql...
# Running migration 03_stories_seed_fix.sql...
# ... (skips already applied migrations)
# Schema migrations completed!
```

### 3. Seed Sample Data (Optional)

```bash
php migrate.php seed
```

### 4. Verify Your Setup

Open **phpMyAdmin** at `http://localhost:8080` and log in:
- **Server:** mysql
- **Username:** developer
- **Password:** secret123
- **Database:** haarlem_festival

Expected tables: `page`, `page_section`, `image`, `user`, `order`, `payment`, `ticket`, etc.

---

## Migration Workflow

### What are Migrations?

Migrations are versioned SQL files in `db/migrations/` that describe schema changes. They allow the team to:
- Track all changes to the database structure
- Apply changes consistently across environments
- Collaborate safely without overwriting each other's data

### Current Migrations

| File | Purpose | Status |
|------|---------|--------|
| `01_schema.sql` | Initial schema (all core tables) | Applied once per environment |
| `03_stories_seed_fix.sql` | Fix stories table data | Applied |
| `04_page_section_restaurant_card_alias.sql` | Add restaurants_card enum alias | Applied |
| `05_page_section_restaurant_card_only.sql` | Remove legacy alias, normalize to restaurant_card | Applied |
| `06_image_caption.sql` | Add caption column to image table | Applied |
| `07_page_section_welcome_banner_card.sql` | Add welcome_banner_card section type | Applied |
| `08_drop_page_section_image_id.sql` | Remove image_id FK, use JSON instead | Applied |

### Apply Migrations

Every time you pull from git:

```bash
# Always run this after pulling
php migrate.php up
```

The script is **smart**: it skips migrations you've already applied, so it's safe to run multiple times.

---

## Creating New Migrations

When you need to modify the schema:

### 1. Create a Migration File

```bash
# In db/migrations/ directory, use next number
# Example: 09_add_my_new_column.sql
```

**File naming convention:** `{number}_{description}.sql`
- Number should be 2 digits, incremented from the last migration
- Use lowercase with underscores
- Be descriptive

### 2. Write Safe SQL

Always include:
- `USE haarlem_festival;` at the top
- Comments explaining the change
- Reversible logic (CHECK IF EXISTS before ADD, etc.)

**Example:**

```sql
-- ============================================================
-- 09_add_my_new_column.sql
-- Add status column to user table with default 'active'
-- ============================================================
USE haarlem_festival;

-- Safe: only add if column doesn't exist
SET @has_column := (
  SELECT COUNT(*)
  FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'user'
    AND COLUMN_NAME = 'status'
);

SET @add_column_sql := IF(@has_column = 0,
  'ALTER TABLE user ADD COLUMN status ENUM("active", "inactive") NOT NULL DEFAULT "active"',
  'SELECT 1'
);

PREPARE stmt FROM @add_column_sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
```

### 3. Test Locally

```bash
# Run your new migration
php migrate.php up

# Verify in phpMyAdmin
```

### 4. Commit and Push

```bash
git add db/migrations/09_add_my_new_column.sql
git commit -m "chore(db): add status column to user table"
git push
```

---

## Team Synchronization Checklist

### For Each Team Member After Pulling

- [ ] Pull latest code: `git pull origin main`
- [ ] Verify Docker containers running: `docker compose ps`
- [ ] Run migrations: `php migrate.php up`
- [ ] Check phpMyAdmin for new tables/columns
- [ ] Test your feature locally
- [ ] Push your changes

### If Someone Adds a New Migration

1. **Developer A** creates `09_new_feature.sql`, tests it, commits and pushes
2. **Developer B** pulls the code
3. **Developer B** runs: `php migrate.php up`
4. **Developer B's** local schema is now updated automatically
5. **Developer B's** existing data is preserved (because migrations use safe ALTER statements)

---

## Troubleshooting

### "Error: SQLSTATE[HY000]"
The database isn't running.
```bash
docker compose ps
# If mysql isn't healthy, restart:
docker compose restart mysql
```

### "Migration already applied" or Duplicate column errors
You may have run `migrate.php up` twice. This is fine; the second run skips already-applied migrations.

### Lost Data After Migration
If a migration failed, check the error and:

1. **Backup your data** (export from phpMyAdmin)
2. **Reset cleanly** (destructive):
   ```bash
   php migrate.php reset --force
   ```
3. **Re-run migrations**:
   ```bash
   php migrate.php up
   php migrate.php seed  # restore sample data
   ```

### "Port 3306 already in use"
Another MySQL is running, or Docker is stale:
```bash
docker compose down
docker compose up -d
```

---

## Connecting from Code

### PHP (PDO)

```php
$pdo = new PDO(
    'mysql:host=mysql;charset=utf8mb4;dbname=haarlem_festival',
    'developer',
    'secret123',
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);
```

### From Host Machine

Use `localhost` instead of `mysql`:

```bash
mysql -h localhost -u developer -psecret123 haarlem_festival
```

---

## Exporting Current Schema

To share your current schema with the team:

```bash
# Export to backup.sql (from host)
docker compose exec mysql mysqldump \
  -u developer -psecret123 haarlem_festival > backup.sql

# Or use phpMyAdmin: Export > Structure Only
```

---

## Important Notes

- **Never modify** `db/migrations/01_schema.sql` after initial setup
- **Always run** `php migrate.php up` after pulling
- **Test migrations locally** before pushing
- **Keep migrations reversible** (use IF EXISTS, safe ALTERs)
- **Commit migration files to git**; they're the source of truth for schema

---

## Support

For questions, check:
- `db/migrations/*.sql` — see all applied changes
- `migrate.php` — logic for running migrations
- `docker-compose.yml` — container configuration
