@echo off
setlocal
cd /d "%~dp0"

echo ============================================
echo  Load Haarlem website content (database)
echo ============================================
echo.

docker info >nul 2>&1
if errorlevel 1 (
    echo ERROR: Docker is not running. Start Docker Desktop first.
    pause
    exit /b 1
)

echo Starting containers...
docker compose up -d
echo Waiting 20s for MySQL...
timeout /t 20 /nobreak >nul

echo Resetting database (schema + migrations + seeds)...
docker compose exec -T php php /app/migrate.php reset --force
if errorlevel 1 (
    echo ERROR: Database setup failed. See output above.
    pause
    exit /b 1
)

echo.
echo Pages in database:
docker compose exec -T mysql mariadb -uroot -psecret123 -e "SELECT slug, status FROM haarlem_festival.page ORDER BY slug;"

echo.
echo Done. Open http://localhost/home and http://localhost/history
echo Login: customer1@haarlemfest.test / Test12345!
echo.
pause
