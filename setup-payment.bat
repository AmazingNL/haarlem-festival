@echo off
setlocal
cd /d "%~dp0"

echo ============================================
echo  Payment + database setup
echo ============================================
echo.

if not exist ".env" (
    echo Creating .env from .env.example...
    copy /Y ".env.example" ".env"
    echo.
    echo IMPORTANT: Edit .env and paste your Stripe TEST secret key:
    echo   STRIPE_SECRET_KEY=sk_test_...
    echo.
    notepad .env
) else (
    echo .env already exists.
)

echo.
echo Applying migrations...
docker compose exec -T php php /app/migrate.php up
echo.
echo Loading seed content (events, pages, sample users)...
docker compose exec -T php php /app/migrate.php seed
echo.
echo Running payment check...
docker compose exec -T php php /app/scripts/payment-check.php
echo.
echo Restarting PHP (loads latest .env)...
docker compose up -d php nginx
echo.
echo Done. Open http://localhost/program and http://localhost/events
pause
