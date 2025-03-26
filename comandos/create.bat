@echo off
powershell -NoProfile -ExecutionPolicy Bypass -Command "Invoke-RestMethod -Uri 'http://localhost:8000/index.php/create' -Method POST"
pause
