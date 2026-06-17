@echo off
setlocal
cd /d "%~dp0"

echo ============================================
echo  Haarlem Festival - Docker Startup
echo ============================================
echo.

docker info >nul 2>&1
if errorlevel 1 (
    echo [ERROR] Docker is not running.
    echo.
    echo 1. Open "Docker Desktop" from the Start menu
    echo 2. Wait until it says "Docker Desktop is running"
    echo 3. Run this script again
    echo.
    pause
    exit /b 1
)

echo [OK] Docker is running.
echo.
echo Starting containers (this may take a few minutes the first time)...
docker compose up -d --build
if errorlevel 1 (
    echo.
    echo [WARN] Build failed, trying without rebuild...
    docker compose up -d
    if errorlevel 1 (
        echo [ERROR] Could not start containers. See errors above.
        pause
        exit /b 1
    )
)

echo.
echo Waiting 15 seconds for MySQL...
timeout /t 15 /nobreak >nul

echo Installing PHP dependencies (Composer)...
docker compose exec -T php composer install --no-interaction --no-progress
if errorlevel 1 (
    echo [ERROR] composer install failed. Check docker compose logs php
    pause
    exit /b 1
)

echo Running database migrations...
docker compose exec -T php php /app/migrate.php reset --force

echo.
echo ============================================
docker compose ps
echo ============================================
echo.

docker compose ps --format "{{.Name}} {{.Status}}" | findstr /i "nginx" >nul
if errorlevel 1 (
    echo [ERROR] nginx container is not running. Try: docker compose logs nginx
    pause
    exit /b 1
)

echo Open in your browser:
echo   http://localhost
echo   http://localhost:8081   (if port 80 is blocked)
echo.
echo Other services:
echo   Mailpit:    http://127.0.0.1:8025
echo   phpMyAdmin: http://127.0.0.1:8080
echo.
echo If localhost still fails, port 80 may be in use. Run:
echo   netstat -ano | findstr :80
echo.
pause
