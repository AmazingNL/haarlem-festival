#!/bin/bash
# team-sync.sh - One-command database and schema sync for the team
# 
# Usage:
#   ./team-sync.sh        # Full sync (start containers, migrate, seed, verify)
#   ./team-sync.sh sync   # Sync only (skip containers restart)
#   ./team-sync.sh up     # Start containers only
#   ./team-sync.sh reset  # Full reset (destructive - requires confirmation)

set -e

COMMAND=${1:-sync}
PROJECT_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

echo "🔄 Haarlem Festival Team Database Sync"
echo "========================================"
echo ""

case $COMMAND in
  up)
    echo "📦 Starting Docker containers..."
    docker compose up -d
    docker compose ps
    echo "✅ Containers started"
    ;;

  sync)
    echo "🔄 Full synchronization..."
    echo ""
    
    # Step 1: Start containers
    echo "1️⃣  Starting containers..."
    docker compose up -d
    docker compose ps
    echo ""
    
    # Step 2: Wait for MySQL
    echo "2️⃣  Waiting for MySQL to be ready..."
    sleep 3

    # Step 3: Create safety backup before migrations
    echo "3️⃣  Creating pre-migration backup..."
    mkdir -p backups
    BACKUP_FILE="backups/pre-migrate-$(date +%Y%m%d-%H%M%S).sql"
    docker compose exec -T mysql sh -c 'mariadb-dump -udeveloper -psecret123 haarlem_festival' > "$BACKUP_FILE"
    echo "   ✅ Backup saved to $BACKUP_FILE"
    
    # Step 4: Run migrations
    echo "4️⃣  Running database migrations..."
    php migrate.php up
    echo ""
    
    # Step 5: Verify status
    echo "5️⃣  Verifying database status..."
    php scripts/db-status.php
    echo ""
    
    echo "✅ Sync complete! You're in sync with the team."
    ;;

  reset)
    echo "⚠️  WARNING: This will DELETE all local data and reset to sample data."
    echo "   Your data will be backed up first."
    echo ""
    read -p "Are you sure? Type 'yes' to confirm: " confirmation
    
    if [ "$confirmation" = "yes" ]; then
      echo "💾 Backing up current database..."
      docker compose exec -T mysql mysqldump -u developer -psecret123 haarlem_festival > "backup-$(date +%Y%m%d-%H%M%S).sql"
      echo "   ✅ Backup saved to backup-*.sql"
      echo ""
      
      echo "🔄 Resetting database..."
      php migrate.php reset --force
      echo ""
      
      echo "✅ Reset complete. Sample data restored."
    else
      echo "❌ Reset cancelled."
    fi
    ;;

  *)
    echo "Usage: ./team-sync.sh [command]"
    echo ""
    echo "Commands:"
    echo "  sync    - Full sync (start containers, migrate, verify) [DEFAULT]"
    echo "  up      - Start Docker containers only"
    echo "  reset   - Full destructive reset (backs up first)"
    echo ""
    echo "Examples:"
    echo "  ./team-sync.sh         # Full sync"
    echo "  ./team-sync.sh sync    # Same as above"
    echo "  ./team-sync.sh up      # Just start containers"
    echo ""
    exit 1
    ;;
esac
