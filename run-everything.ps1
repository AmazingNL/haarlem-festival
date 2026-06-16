# Haarlem Festival - full local setup (Docker + Composer + migrations)
$ErrorActionPreference = "Continue"
Set-Location $PSScriptRoot

$log = Join-Path $PSScriptRoot "setup-run.log"
function Log($msg) {
    $line = "[$(Get-Date -Format 'HH:mm:ss')] $msg"
    Write-Host $line
    Add-Content -Path $log -Value $line -Encoding utf8
}

"" | Set-Content -Path $log -Encoding utf8
Log "=== Haarlem Festival setup ==="

# 1. Docker
Log "Checking Docker..."
docker info 2>&1 | Out-Null
if ($LASTEXITCODE -ne 0) {
    Log "ERROR: Docker is not running. Open Docker Desktop, wait until it is ready, then run this script again."
    Read-Host "Press Enter to close"
    exit 1
}
Log "Docker OK"

# 2. Start containers
Log "Starting containers (first run may take several minutes)..."
docker compose up -d --build 2>&1 | Tee-Object -FilePath $log -Append
if ($LASTEXITCODE -ne 0) {
    Log "Build failed, trying without rebuild..."
    docker compose up -d 2>&1 | Tee-Object -FilePath $log -Append
}
if ($LASTEXITCODE -ne 0) {
    Log "ERROR: Could not start containers. See $log"
    Read-Host "Press Enter to close"
    exit 1
}

# 3. Wait for MySQL
Log "Waiting 20s for MySQL..."
Start-Sleep -Seconds 20

# 4. Composer (fixes vendor/autoload.php error)
Log "Installing PHP dependencies (Composer)..."
docker compose run --rm composer install --no-interaction --no-progress --ignore-platform-reqs 2>&1 | Tee-Object -FilePath $log -Append
if ($LASTEXITCODE -ne 0) {
    Log "ERROR: composer install failed. See $log"
    Read-Host "Press Enter to close"
    exit 1
}
if (-not (Test-Path "vendor\autoload.php")) {
    Log "ERROR: vendor\autoload.php was not created"
    Read-Host "Press Enter to close"
    exit 1
}
Log "Composer OK - vendor\autoload.php exists"

# 5. Rebuild/start app containers (composer service is one-shot)
Log "Starting application containers..."
docker compose up -d --build php nginx 2>&1 | Tee-Object -FilePath $log -Append
# 6. Database reset (schema + migrations + seeds)
Log "Resetting database (migrate reset --force)..."
docker compose exec -T php php /app/migrate.php reset --force 2>&1 | Tee-Object -FilePath $log -Append

# 7. Status
Log "Container status:"
docker compose ps 2>&1 | Tee-Object -FilePath $log -Append

# 8. Test HTTP
$urls = @("http://127.0.0.1/", "http://127.0.0.1:8081/", "http://localhost/")
$opened = $false
foreach ($url in $urls) {
    try {
        $r = Invoke-WebRequest -Uri $url -UseBasicParsing -TimeoutSec 5 -ErrorAction Stop
        Log "Site responded: $url (HTTP $($r.StatusCode))"
        if (-not $opened) {
            Start-Process $url
            $opened = $true
        }
    } catch {
        Log "No response from $url"
    }
}

Log ""
Log "Done."
Log "  Website:    http://localhost  or  http://localhost:8081"
Log "  Mailpit:    http://127.0.0.1:8025"
Log "  phpMyAdmin: http://127.0.0.1:8080"
Log "  Full log:   $log"
Write-Host ""
Write-Host "Website: http://localhost (or http://localhost:8081)" -ForegroundColor Green
Read-Host "Press Enter to close"
