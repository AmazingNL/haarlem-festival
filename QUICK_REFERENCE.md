# Quick Reference - Database Sync

##  One-Line Summary

Your local database is out of sync when you pull code. Fix it with one command.

---

## Quick Commands

### Linux/macOS
```bash
./team-sync.sh              # Full sync (recommended)
./team-sync.sh up           # Start containers only
./team-sync.sh reset        # Destructive reset
```

### Windows (PowerShell)
```powershell
.\team-sync.ps1             # Full sync (recommended)
.\team-sync.ps1 -Command up # Start containers only
.\team-sync.ps1 -Command reset  # Destructive reset
```

### All Platforms (Manual)
```bash
docker compose up -d        # Start containers
php migrate.php up          # Apply migrations
php scripts/db-status.php   # Verify setup
```

---

##  Workflow

1. **Pull code**
   ```bash
   git pull origin main
   ```

2. **Sync database** (use any method above)
   ```bash
   ./team-sync.sh      # Linux/macOS
   # OR
   .\team-sync.ps1     # Windows
   # OR
   php migrate.php up  # All platforms
   ```

3. **You're ready**
   - Schema is updated
   - Your data is preserved
   - Start coding

---

## How to Know You're Synced

Run this and check for all :

```bash
php scripts/db-status.php
```

Expected output:
```
Database: haarlem_festival
Connection: OK

MIGRATION STATUS:
     Applied - 01_schema.sql
     Applied - 03_stories_seed_fix.sql
     Applied - 04_page_section_restaurant_card_alias.sql
     Applied - 05_page_section_restaurant_card_only.sql
     Applied - 06_image_caption.sql
     Applied - 07_page_section_welcome_banner_card.sql
     Applied - 08_drop_page_section_image_id.sql

Tables in database: 12
Your database is ready for development.
```

---

##  For Database Changes You Make

1. Create migration file: `db/migrations/09_your_change.sql`
2. Test locally: `php migrate.php up`
3. Verify: `php scripts/db-status.php`
4. Commit: `git add db/migrations/09_your_change.sql`
5. Push: `git push origin main`
6. Team syncs automatically

---

## Troubleshooting

| Problem | Solution |
|---------|----------|
| Containers not running | `docker compose up -d` |
| Database not synced | `php migrate.php up` |
| Not sure about status | `php scripts/db-status.php` |
| Want fresh start | `./team-sync.sh reset` or `.\team-sync.ps1 -Command reset` |
| Data lost | Check `backup-*.sql` file |

---

## Full Documentation

- **Setup Guide:** [docs/DATABASE_SETUP.md](docs/DATABASE_SETUP.md)
- **Team Sync Guide:** [TEAM_SYNC.md](TEAM_SYNC.md)
- **Migration Files:** `db/migrations/`

---

## Pro Tips

- Run sync after every `git pull` to stay safe
- Safe to run multiple times (skips already-applied migrations)
- Backups are created automatically on reset
- Check `php scripts/db-status.php` to debug
- Always commit migration files to git immediately
