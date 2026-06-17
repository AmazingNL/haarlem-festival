# team-sync.ps1 - One-command database and schema sync for the team (PowerShell)
#
# Usage:
#   .\team-sync.ps1                 # Full sync (migrate only, keeps your data)
#   .\team-sync.ps1 -Command sync   # Same as above
#   .\team-sync.ps1 -Command up     # Start containers only
#   .\team-sync.ps1 -Command reset  # Full reset (drops all data, re-seeds)

param(
    [string]$Command = "sync"
)

$ErrorActionPreference = "Stop"

Write-Host "`n Haarlem Festival Team Database Sync" -ForegroundColor Cyan
Write-Host "========================================`n" -ForegroundColor Cyan

function Run-Step {
    param([string]$Description, [scriptblock]$Script)
    Write-Host $Description -ForegroundColor Yellow
    & $Script
    Write-Host ""
}

switch ($Command) {

    "up" {
        Run-Step " Starting Docker containers..." {
            docker compose up -d
            docker compose ps
            Write-Host " Containers started" -ForegroundColor Green
        }
    }

    "sync" {
        Write-Host " Starting full sync..." -ForegroundColor Cyan
        Write-Host ""

        Run-Step "1) Starting containers..." {
            docker compose up -d
        }

        Run-Step "2) Waiting for MySQL to be ready..." {
            Start-Sleep -Seconds 5
            Write-Host "   MySQL should be ready" -ForegroundColor Green
        }

        Run-Step "3) Creating pre-migration backup..." {
            $backupDir = "backups"
            if (-not (Test-Path $backupDir)) {
                New-Item -ItemType Directory -Path $backupDir | Out-Null
            }
            $timestamp = Get-Date -Format "yyyyMMdd-HHmmss"
            $backupFile = "$backupDir/pre-migrate-$timestamp.sql"
            docker compose exec -T mysql sh -c "mariadb-dump -uroot -psecret123 haarlem_festival" | Out-File -FilePath $backupFile -Encoding UTF8
            Write-Host "   Backup saved to $backupFile" -ForegroundColor Green
        }

        Run-Step "4) Running new database migrations..." {
            docker compose exec -T php php /app/migrate.php up
        }

        Run-Step "5) Verifying container status..." {
            docker compose ps
        }

        Write-Host " Sync complete! You are in sync with the team." -ForegroundColor Green
        Write-Host "   Site:        http://localhost" -ForegroundColor White
        Write-Host "   phpMyAdmin:  http://localhost:8080" -ForegroundColor White
    }

    "reset" {
        Write-Host " WARNING: This will DELETE all local data and reset to seed data." -ForegroundColor Red
        Write-Host "   A backup will be created first.`n" -ForegroundColor Red

        $confirmation = Read-Host "Type 'yes' to confirm"

        if ($confirmation -eq "yes") {
            Run-Step "1) Backing up current database..." {
                $backupDir = "backups"
                if (-not (Test-Path $backupDir)) {
                    New-Item -ItemType Directory -Path $backupDir | Out-Null
                }
                $timestamp = Get-Date -Format "yyyyMMdd-HHmmss"
                $backupFile = "$backupDir/pre-reset-$timestamp.sql"
                docker compose exec -T mysql sh -c "mariadb-dump -uroot -psecret123 haarlem_festival" | Out-File -FilePath $backupFile -Encoding UTF8
                Write-Host "   Backup saved to $backupFile" -ForegroundColor Green
            }

            Run-Step "2) Resetting database (all migrations + seeds)..." {
                docker compose exec -T php php /app/migrate.php reset --force
            }

            Write-Host " Reset complete. Sample data restored." -ForegroundColor Green
        } else {
            Write-Host " Reset cancelled." -ForegroundColor Yellow
        }
    }

    default {
        Write-Host "Usage: .\team-sync.ps1 [-Command <command>]`n" -ForegroundColor Yellow
        Write-Host "Commands:" -ForegroundColor Yellow
        Write-Host "  sync    - Apply new migrations, keep your data [DEFAULT]" -ForegroundColor White
        Write-Host "  up      - Start Docker containers only" -ForegroundColor White
        Write-Host "  reset   - Drop everything and re-seed from scratch (backs up first)" -ForegroundColor White
        Write-Host ""
        Write-Host "Examples:" -ForegroundColor Yellow
        Write-Host "  .\team-sync.ps1                    # Full sync" -ForegroundColor White
        Write-Host "  .\team-sync.ps1 -Command reset     # Reset to sample data" -ForegroundColor White
        exit 1
    }
}
