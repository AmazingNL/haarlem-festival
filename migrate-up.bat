@echo off
cd /d "%~dp0"
echo Running database migrations...
docker compose exec -T php php migrate.php up
echo.
echo Done. Refresh My Program and try checkout again.
pause
