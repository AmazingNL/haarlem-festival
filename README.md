# Haarlem Festival

A web application for browsing and booking tickets for the Haarlem Festival — events, restaurant reservations, and history tours.

## Requirements

- [Docker Desktop](https://www.docker.com/products/docker-desktop/) (includes Docker Compose)
- [Git](https://git-scm.com/)

---

## Getting Started

### 1. Clone the repository

```bash
git clone <repo-url>
cd Haarlem_Festival
```

### 2. Start the containers

```bash
docker compose up --build
```

Wait until all services are running (first build takes a minute).

### 3. Run the database migrations

```bash
docker compose run --rm dbmate up
```

### 4. Open in browser

| Service | URL |
|---|---|
| Application | http://localhost |
| phpMyAdmin | http://localhost:8080 |
| Mailpit (email) | http://localhost:8025 |

---

## Daily Workflow

After pulling new changes from the team:

```bash
docker compose up          # start containers if not running
docker compose run --rm dbmate up   # apply any new migrations
```

Or use the sync script which handles both:

```bash
./team-sync.sh             # macOS/Linux
.\team-sync.ps1            # Windows PowerShell
```

---

## Database

### Credentials

| Field | Value |
|---|---|
| Host | `localhost` |
| Port | `3306` |
| Database | `haarlem_festival` |
| Username | `developer` |
| Password | `secret123` |

### Backup & Restore

```bash
# Export current data to backup.sql
docker compose exec mysql sh -c 'mariadb-dump -uroot -psecret123 haarlem_festival' > backup.sql

# Restore from backup.sql
cat backup.sql | docker compose exec -T mysql sh -c 'mariadb -uroot -psecret123 haarlem_festival'
```

---

## Email

All outgoing emails are captured by Mailpit — nothing is sent to a real inbox.

View captured emails at **http://localhost:8025**

---

## Stopping the project

```bash
docker compose down
```

To also delete the database volume (full reset):

```bash
docker compose down -v
```
