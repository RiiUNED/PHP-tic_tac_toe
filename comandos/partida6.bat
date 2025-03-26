@echo off
echo Movimiento 1
powershell -NoProfile -ExecutionPolicy Bypass -Command "Invoke-RestMethod -Uri 'http://localhost:8000/index.php/move' -Method POST -ContentType 'application/json' -Body (Get-Content ..\JSON\6partida\move1.json -Raw)"
timeout /t 1 >nul

echo Movimiento 2
powershell -NoProfile -ExecutionPolicy Bypass -Command "Invoke-RestMethod -Uri 'http://localhost:8000/index.php/move' -Method POST -ContentType 'application/json' -Body (Get-Content ..\JSON\6partida\move2.json -Raw)"
timeout /t 1 >nul

echo Movimiento 3
powershell -NoProfile -ExecutionPolicy Bypass -Command "Invoke-RestMethod -Uri 'http://localhost:8000/index.php/move' -Method POST -ContentType 'application/json' -Body (Get-Content ..\JSON\6partida\move3.json -Raw)"
timeout /t 1 >nul

echo Movimiento 4
powershell -NoProfile -ExecutionPolicy Bypass -Command "Invoke-RestMethod -Uri 'http://localhost:8000/index.php/move' -Method POST -ContentType 'application/json' -Body (Get-Content ..\JSON\6partida\move4.json -Raw)"
timeout /t 1 >nul

echo Movimiento 5
powershell -NoProfile -ExecutionPolicy Bypass -Command "Invoke-RestMethod -Uri 'http://localhost:8000/index.php/move' -Method POST -ContentType 'application/json' -Body (Get-Content ..\JSON\6partida\move5.json -Raw)"
timeout /t 1 >nul

echo Movimiento 6
powershell -NoProfile -ExecutionPolicy Bypass -Command "Invoke-RestMethod -Uri 'http://localhost:8000/index.php/move' -Method POST -ContentType 'application/json' -Body (Get-Content ..\JSON\6partida\move6.json -Raw)"
timeout /t 1 >nul

echo Movimiento 7
powershell -NoProfile -ExecutionPolicy Bypass -Command "Invoke-RestMethod -Uri 'http://localhost:8000/index.php/move' -Method POST -ContentType 'application/json' -Body (Get-Content ..\JSON\6partida\move7.json -Raw)"
timeout /t 1 >nul

echo Movimiento 8
powershell -NoProfile -ExecutionPolicy Bypass -Command "Invoke-RestMethod -Uri 'http://localhost:8000/index.php/move' -Method POST -ContentType 'application/json' -Body (Get-Content ..\JSON\6partida\move8.json -Raw)"

timeout /t 1 >nul
pause
