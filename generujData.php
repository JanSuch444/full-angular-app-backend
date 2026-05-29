<?php 
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "system-status";

        // seznam ID serveru
        $mojeServery = [1, 2, 3];

        try {
                $dsn = "mysql:host=$servername;dbname=$dbname;charset=utf8mb4";
                $options = [
                        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ];
                $pdo = new PDO($dsn, $username, $password, $options);

                foreach($mojeServery as $serviceID) {
                        $nahoda = rand(1, 100);

                        if ($nahoda > 93) {
                                $novyStav = 2;  // hodi chybu jako stav
                        }
                        elseif ($nahoda > 88) {
                                $novyStav = 1;
                        }
                        else {
                                $novyStav = 0;
                        }

                        $sql = "INSERT INTO services_history (service_id, status_value, created_at) VALUES (?, ?, NOW())";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([$serviceID, $novyStav]);
                }
        } catch (Exception $e) {
                echo "Chyba generatoru: " . $e->getMessage() . "\n";
        }

        $pdo = null;
?>