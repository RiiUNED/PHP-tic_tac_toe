@echo off
echo Movimiento 1
powershell -NoProfile -ExecutionPolicy Bypass -Command "Invoke-RestMethod -Uri 'http://localhost:8000/index.php/move' -Method POST -ContentType 'application/json' -Body (Get-Content ..\JSON\7partida\move1.json -Raw)"
timeout /t 1 >nul

echo Movimiento 2
powershell -NoProfile -ExecutionPolicy Bypass -Command "Invoke-RestMethod -Uri 'http://localhost:8000/index.php/move' -Method POST -ContentType 'application/json' -Body (Get-Content ..\JSON\7partida\move2.json -Raw)"
timeout /t 1 >nul

echo Movimiento 3
powershell -NoProfile -ExecutionPolicy Bypass -Command "Invoke-RestMethod -Uri 'http://localhost:8000/index.php/move' -Method POST -ContentType 'application/json' -Body (Get-Content ..\JSON\7partida\move3.json -Raw)"
timeout /t 1 >nul

pause
