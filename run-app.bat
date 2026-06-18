@echo off
cd /d "%~dp0"
echo === Docker up === > run-log.txt
docker compose up -d --build >> run-log.txt 2>&1
echo === Docker ps === >> run-log.txt
docker compose ps >> run-log.txt 2>&1
echo === Wait for MySQL === >> run-log.txt
timeout /t 20 /nobreak >> run-log.txt 2>&1
echo === Migrations === >> run-log.txt
docker compose exec -T php php migrate.php up >> run-log.txt 2>&1
echo === Done === >> run-log.txt
