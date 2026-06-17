@echo off
cd /d "%~dp0"
echo Installing Composer dependencies into vendor\ ...
docker compose run --rm composer install --no-interaction --no-progress --ignore-platform-reqs
if errorlevel 1 (
    echo.
    echo FAILED. Is Docker Desktop running?
    pause
    exit /b 1
)
if not exist "vendor\autoload.php" (
    echo.
    echo ERROR: vendor\autoload.php was not created.
    pause
    exit /b 1
)
echo.
echo SUCCESS: vendor\autoload.php exists.
echo Restarting PHP container...
docker compose up -d php nginx
echo Refresh http://localhost in your browser.
pause
