@echo off
title Tradim Queue Launcher

cd /d "C:\personal project\tradim"

echo Starting Tradim workers...

start "Tradim Video Worker" cmd /k "php artisan queue:work redis_video --queue=processing --sleep=3 --tries=3 --timeout=3600 -vvv"

start "Tradim General Worker" cmd /k "php artisan queue:work redis --queue=notifications,default --sleep=3 --tries=3 --timeout=120 -vvv"

echo Both workers started.
exit