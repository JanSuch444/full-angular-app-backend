<?php 
        // 1. Nastavení hlaviček pro prohlížeč a JavaScript (CORS)
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *"); 
        header("Content-Type: application/json; charset=utf-8");

        $servername = "localhost";
        $username= "root";
        $password = "";
        $dbname = "system-status";

        try {

                $dsn = "mysql:host=$servername;dbname=$dbname;charset=utf8mb4";

                $options = [
                                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ];

                // vytvorri connection k dba
                $pdo = new PDO($dsn,  $username, $password, $options);
   
                // Vytáhnevšechny servery z tabulky 'services'
                $sqlServices = "SELECT id, name, overall_status, overall_status_label FROM services";
                $sqlResultServices = $pdo->query($sqlServices);  // pošle zadostt na sql a ziska si je

                $vystupServery = [];

                // Procháazí jeden server po    druhém
                while ($service = $sqlResultServices->fetch()) {
                        $serviceID = $service["id"];
 
                        // vytahnou 90 stavuu pro aktualni server
                        $sqlHistory = "SELECT status_value, created_at FROM services_history WHERE service_id = ? ORDER BY id DESC LIMIT 90";
                        $resultHistory = $pdo->prepare($sqlHistory);
                        $resultHistory->execute(["$serviceID"]); // Tady bezpečně předá ID do dotazu pro sql

                        $historieObjekty = [];
                        while ($historyRow = $resultHistory->fetch()) {
                                // Převede texxtové číslo z DB na opravdové PHP číslo (i  nt)
                                $historieObjekty[] = [
                                        "status" => (int)$historyRow["status_value"],
                                        "cas" => $historyRow["created_at"]
                                ];
                        }

                        $historieObjekty = array_reverse($historieObjekty);

                        $vystupServery[] = [
                                "jmeno" => $service["name"],
                                "overallStatus" => $service["overall_status"],
                                "overallStatusLabel" => $service["overall_status_label"],
                                "historie" => $historieObjekty
                        ];
                }

                // vytvareni hlaviho objektu
                $aktualniCas = date("D:H:i:s");
                $hlavniObjekt = [
                        "overallStatus" => "operational",
                        "overallStatusLabel" => "All services are online",
                        "lastUpdated" => $aktualniCas,
                        "services" => $vystupServery 
                ];

                // odelani ve json fomratu do angularu
                echo json_encode($hlavniObjekt);
        } catch(PDOException $e) {
                echo json_encode(["error" => "Chyba databáze (PDO): " . $e->getMessage()]);
        }
        
        catch(Exception $e) {
                echo json_encode(["error" => "Chyba při čtení z databáze: " . $e->getMessage()]);
        }

        $pdo = null;
        // 2. Pole s názvy serverů
       /* $servery = ["Webová platforma", "API", "APP API"];
        $vystup = [];*/



        /*foreach ($servery as $jmenoServeru) {
                $historie = [];

                for ( $i = 0; $i < 180 ; $i++ ) {  
                        $nahodna = rand() % 100;

                        if ( $nahodna > 92 ) {
                                $stav = 2;  // vapdaek severu
                        }
                        else if ( $nahodna > 85) {
                                $stav = 1;  // udrzba serveru
                        }
                        else {
                                $stav = 0;   // bezici server
                        }

                        $historie[] = $stav;
                }

                $vystup[] = [
                        "jmeno" => $jmenoServeru,
                        "historie" => $historie
                ];
        }*/

       /* // 3. Vytvoření hlavního objektu, který obsahuje všechna data pro Angular
        $aktualniCas = date("H:i:s");
        $hlavniObjekt = [
                "overallStatus" => "operational",          // Celkový stav (operational / outage / maintenance)
                "overallStatusLabel" => "All services are online", // Hlavní text v panelu
                "lastUpdated" => $aktualniCas,             // TADY JE TO DATUM/ČAS!
                "services" => $vystup                      // Tvoje pole se servery
        ];

        echo json_encode($hlavniObjekt);*/
?>