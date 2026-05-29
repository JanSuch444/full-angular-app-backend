@echo off
:loop
echo [%time%] Generuji nova data do databaze...

:: Spustíme PHP z tvé složky XAMMP a předáme mu tvůj skript
"C:\honza\XAMMP\php\php.exe" "C:\honza\XAMMP\htdocs\eos-backend\generujData.php"

timeout /t 30 /nobreak
goto loop