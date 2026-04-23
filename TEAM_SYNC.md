# Team Database Synchronization Guide

## Problem We're Solving

Different team members have different local database setups. When the schema changes, we need everyone to sync without losing their local data or breaking their workflow.

---

## The Solution: One-Command Sync

### For All Team Members

After pulling any code changes:

```bash
cd Haarlem_Festival
docker compose up -d   # Ensure containers are running
php migrate.php up     # Apply any pending migrations
php scripts/db-status.php  # Verify your setup
```

**That's it.** Your local schema is now in sync with the team.

---

## How It Works

1. **Pull from Git**
   ```bash
   git pull origin main
   ```

2. **Run Migrations**
   ```bash
   php migrate.php up
   ```
   - Detects which migrations you've already applied
   - Applies only the new ones
   - Skips duplicates (safe to run multiple times)
   - Preserves all your local data

3. **Verify Status**
   ```bash
   php scripts/db-status.php
   ```
   - Shows which migrations are applied
   - Lists all tables in your database
   - Confirms everything is working

---

## Real-World Example

### Scenario: A New Migration is Pushed

**Developer A** creates migration `09_add_status_to_user.sql` and pushes to git.

**Your workflow:**

```bash
# You pull the latest code
git pull origin main

# Your local db still has the old schema
# But now you have the new migration file

# Run migrations to apply the new change
php migrate.php up

# Output:
# Running migration 09_add_status_to_user.sql...
# Schema migrations updated!

# Verify
php scripts/db-status.php

# Output shows:
# Applied - 09_add_status_to_user.sql
# ... and all your local data is still there
```

---

## Adding a New Migration

When **you** need to change the schema:

### 1. Create the Migration File

```bash
# In db/migrations/ with incrementing number
# Example: db/migrations/09_add_new_feature.sql
```

**Follow the pattern from existing migrations:**
- Use safe SQL (IF NOT EXISTS, IF COLUMN EXISTS, etc.)
- Never destructive unless intentional
- Include comments explaining the change

**Minimal example:**

```sql
-- ============================================================
-- 09_add_new_feature.sql
-- Add feature_enabled column to page table
-- ============================================================
USE haarlem_festival;

SET @has_column := (
  SELECT COUNT(*)
  FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'page'
    AND COLUMN_NAME = 'feature_enabled'
);

SET @sql := IF(@has_column = 0,
  'ALTER TABLE page ADD COLUMN feature_enabled TINYINT(1) DEFAULT 0',
  'SELECT 1'
);

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
```

### 2. Test It Locally

```bash
# Your new migration is in db/migrations/
php migrate.php up

# Verify in phpMyAdmin or CLI
php scripts/db-status.php
```

### 3. Commit and Push

```bash
git add db/migrations/09_add_new_feature.sql
git commit -m "chore(db): add feature_enabled column to page table"
git push origin main
```

### 4. Teammates Auto-Sync

When they pull and run `php migrate.php up`, your migration is applied automatically.

---

## Troubleshooting

### "My database doesn't have the new table"

```bash
# Containers not running?
docker compose ps
docker compose up -d

# Run migrations again
php migrate.php up
```

### "I get a SQL error about duplicate column"

This means the migration tried to apply twice. This is actually okay—the script handles it.

```bash
# Verify status
php scripts/db-status.php
```

### "I messed up my local data and want to start fresh"

```bash
# WARNING: This is destructive. Backs up first!
mysqldump -h localhost -u developer -psecret123 haarlem_festival > backup.sql

# Reset everything (requires --force as confirmation)
php migrate.php reset --force

# Restores schema + seeds sample data
```

### "I'm not sure if I'm in sync with the team"

```bash
# Check your migration status
php scripts/db-status.php

# Compare with teammates (they should run the same command)
```

---

## Best Practices

1. **Always run** `php migrate.php up` after pulling
2. **Test migrations locally** before pushing
3. **Use safe SQL** (IF EXISTS, IF NOT EXISTS, etc.)
4. **Commit migration files to git** immediately
5. **Run** `php scripts/db-status.php` to verify
6. **Never edit** old migration files—create new ones instead
7. **Document** your migration with comments at the top

---

## Workflow Checklist

### Daily Startup

- [ ] `git pull origin main`
- [ ] `docker compose up -d`
- [ ] `php migrate.php up`
- [ ] `php scripts/db-status.php`
- [ ] Start coding

### When Pushing Schema Changes

- [ ] Create new migration file in `db/migrations/`
- [ ] Test with `php migrate.php up`
- [ ] Verify with `php scripts/db-status.php`
- [ ] Commit migration file: `git add db/migrations/XX_*.sql`
- [ ] Push to git
- [ ] Team members pull and auto-sync

### If Something Goes Wrong

- [ ] Check Docker: `docker compose ps`
- [ ] Check status: `php scripts/db-status.php`
- [ ] View logs: `docker compose logs mysql`
- [ ] Ask for help in Slack/Discord

---

## Need More Info?

See [DATABASE_SETUP.md](docs/DATABASE_SETUP.md) for:
- Complete setup guide
- Migration file format details
- phpMyAdmin connection info
- Database connection examples for code
