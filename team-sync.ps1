# team-sync.ps1 - One-command database and schema sync for the team (PowerShell)
#
# Usage:
#   .\team-sync.ps1                 # Full sync
#   .\team-sync.ps1 -Command sync   # Sync only
#   .\team-sync.ps1 -Command up     # Start containers
#   .\team-sync.ps1 -Command reset  # Full reset

param(
    [string]$Command = "sync"
)

$ProjectRoot = Split-Path -Parent $MyInvocation.MyCommand.Path
$ErrorActionPreference = "Stop"

Write-Host "`n Haarlem Festival Team Database Sync" -ForegroundColor Cyan
Write-Host "========================================`n" -ForegroundColor Cyan

function Run-Command {
    param([string]$Description, [scriptblock]$Script)
    
    Write-Host $Description -ForegroundColor Green
    & $Script
    Write-Host ""
}

switch ($Command) {
    "up" {
        Run-Command " Starting Docker containers..." {
            docker compose up -d
            docker compose ps
            Write-Host " Containers started" -ForegroundColor Green
        }
    }

    "sync" {
        Run-Command " Full synchronization..." {
            Write-Host ""
            
            # Step 1: Star  Starting containers..." -ForegroundColor Yellow
            Write-Host "1) Starting containers..." -ForegroundColor Yellow
            docker compose up -d
            docker compose ps
            Write-Host ""
            
            # Step 2: Wait for MySQL
            Write-Host "2️  Waiting for MySQL to be ready..." -ForegroundColor Yellow
            Start-Sleep -Seconds 3
            Write-Host ""

            # Step 3: Create safety backup before migrations
            Write-Host "3️  Creating pre-migration backup..." -ForegroundColor Yellow
            $backupDir = "backups"
            if (-not (Test-Path $backupDir)) {
                New-Item -ItemType Directory -Path $backupDir | Out-Null
            }
            $timestamp = Get-Date -Format "yyyyMMdd-HHmmss"
            $backupFile = "$backupDir/pre-migrate-$timestamp.sql"
            docker compose exec -T mysql sh -c "mariadb-dump -udeveloper -psecret123 haarlem_festival" | Out-File -FilePath $backupFile -Encoding UTF8
            Write-Host "    Backup saved to $backupFile" -ForegroundColor Green
            Write-Host ""
            
            # Step 4: Run migrations
            Write-Host "4️  Running database migrations..." -ForegroundColor Yellow
            php migrate.php up
            Write-Host ""
            
            # Step 5: Verify status
            Write-Host "5️ Verifying database status..." -ForegroundColor Yellow
            php scripts/db-status.php
            Write-Host ""
            
            Write-Host "Sync complete! You're in sync with the team." -ForegroundColor Green
        }
    }

    "reset" {
        Write-Host " WARNING: This will DELETE all local data and reset to sample data." -ForegroundColor Red
        Write-Host "   Your data will be backed up first.`n" -ForegroundColor Red
        
        $confirmation = Read-Host "Are you sure? Type 'yes' to confirm"
        
        if ($confirmation -eq "yes") {
            Run-Command " Backing up current database..." {
                $timestamp = Get-Date -Format "yyyyMMdd-HHmmss"
                $backupFile = "backup-$timestamp.sql"
                
                docker compose exec -T mysql mysqldump -u developer -psecret123 haarlem_festival | Out-File -FilePath $backupFile -Encoding UTF8
                Write-Host "   Backup saved to $backupFile" -ForegroundColor Green
                Write-Host ""
            }
            
            Run-Command " Resetting database..." {
                php migrate.php reset --force
                Write-Host ""
                Write-Host " Reset complete. Sample data restored." -ForegroundColor Green
            }
        } else {
            Write-Host " Reset cancelled." -ForegroundColor Red
        }
    }

    default {
        Write-Host "Usage: .\team-sync.ps1 [-Command <command>]`n" -ForegroundColor Yellow
        Write-Host "Commands:" -ForegroundColor Yellow
        Write-Host "  sync    - Full sync (start containers, migrate, verify) [DEFAULT]" -ForegroundColor White
        Write-Host "  up      - Start Docker containers only" -ForegroundColor White
        Write-Host "  reset   - Full destructive reset (backs up first)" -ForegroundColor White
        Write-Host ""
        Write-Host "Examples:" -ForegroundColor Yellow
        Write-Host "  .\team-sync.ps1                    # Full sync" -ForegroundColor White
        Write-Host "  .\team-sync.ps1 -Command sync      # Same as above" -ForegroundColor White
        Write-Host "  .\team-sync.ps1 -Command up        # Just start containers" -ForegroundColor White
        Write-Host "  .\team-sync.ps1 -Command reset     # Reset to sample data" -ForegroundColor White
        Write-Host ""
        exit 1
    }
}
