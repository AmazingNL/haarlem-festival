# Haarlem Festival

## Quick Start for Team Members

### First Time Setup

```bash
# Clone and navigate
git clone <repo-url>
cd Haarlem_Festival

# One-command sync (handles everything)
./team-sync.sh              # macOS/Linux
# OR
.\team-sync.ps1             # Windows PowerShell
```

That's it! Your database is now fully set up.

### Daily Workflow

After pulling code:

```bash
./team-sync.sh              # macOS/Linux
# OR  
.\team-sync.ps1             # Windows
```

---

## Documentation

- **[QUICK_REFERENCE.md](QUICK_REFERENCE.md)** — Fast answers for common tasks
- **[TEAM_SYNC.md](TEAM_SYNC.md)** — How to keep your database in sync with the team
- **[docs/DATABASE_SETUP.md](docs/DATABASE_SETUP.md)** — Complete database guide for new developers

---

## Local Email

When running the project with Docker, outgoing emails are captured by Mailpit instead of being sent to a real inbox.

- Mailpit inbox: http://127.0.0.1:8025
- SMTP host inside Docker: `mailpit`
- SMTP port: `1025`

---

## Manual Setup (if sync scripts don't work)

```bash
# 1. Start services
docker compose up --build

# 2. Create database schema (migrations)
docker compose exec php php /app/migrate.php up


# 3. Backup & Restore (Real Project Data)

- `backup.sql` = Real project data (actual restaurants, events, etc.)
- You need `backup.sql` to share real data with teammates


```bash
# Export the current Docker database data to backup.sql
docker compose exec mysql sh -c 'mariadb-dump -uroot -psecret123 haarlem_festival' > backup.sql

# Restore backup.sql into the Docker database
cat backup.sql | docker compose exec -T mysql sh -c 'mariadb -uroot -psecret123 haarlem_festival'
```


