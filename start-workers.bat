@echo off
setlocal

title Tradim Queue Launcher

REM Go to the Tradim project directory
cd /d "C:\personal project\tradim"

REM Check whether Artisan exists
if not exist "artisan" (
    echo ERROR: Tradim project not found.
    pause
    exit /b 1
)

echo ========================================
echo          TRADIM QUEUE LAUNCHER
echo ========================================
echo.

echo Starting video processing worker...

REM Handle both current and legacy video queues
start "Tradim Video Worker" cmd /k "php artisan queue:work redis_video --queue=processing,videos --sleep=3 --tries=3 --timeout=3600 -vvv"

echo Starting notification and general worker...

start "Tradim General Worker" cmd /k "php artisan queue:work redis --queue=notifications,default --sleep=3 --tries=3 --timeout=120 -vvv"

echo.
echo ========================================
echo Both Tradim workers have been launched.
echo Keep the worker windows open.
echo ========================================

exit /b 0