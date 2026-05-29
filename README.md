- Status Page – Backend (PHP)

Backendová část projektu zajišťuje API pro frontend a automatické generování simulačních dat do MySQL databáze.

-- Struktura souborů
- `status.php` – Veřejné API, které vrací historii stavů pro Angular ve formátu JSON (ošetřeno CORS hlavičkami).
- `generujData.php` – Skript, který náhodně generuje stavy (0 = OK, 1 = Údržba, 2 = Výpadek) pro servery a ukládá je do DB.
- `spustitGenerator.bat` – Automatizační Windows skript pro spouštění generátoru každých 30 sekund.
- `system-status.sql` – Export struktury MySQL databáze.

-- Jak spustit lokálně
1. Složku umístěte do XAMPP (`htdocs/eos-backend`).
2. V phpMyAdmin vytvořte databázi `system-status` a importujte soubor `system-status.sql`.
3. Pro simulaci dat spustit soubor `spustitGenerator.bat`.

-- Technologie
- PHP (PDO, MySQL)
- Windows Batch Script
