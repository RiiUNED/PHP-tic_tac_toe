@echo off
powershell -NoProfile -ExecutionPolicy Bypass -Command "Invoke-RestMethod -Uri 'http://localhost/tres_en_raya/index.php/create' -Method POST"
pause
