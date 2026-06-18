@echo off
cd /d "%~dp0"

echo Checking out dev branch...
git checkout dev

echo Starting Docker...
docker compose up -d --build

echo Waiting for MySQL (20s)...
timeout /t 20 /nobreak >nul

echo Running migrations...
docker compose exec -T php php migrate.php up

echo.
echo Ready:
echo   App:      http://localhost
echo   Mailpit:  http://localhost:8026
echo   phpMyAdmin: http://localhost:8080
echo.
docker compose ps
