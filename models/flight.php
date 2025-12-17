<?php

require_once 'database/database.php'; 

class Flight {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function searchFlights(array $criteria): array {
        $sql = "
            SELECT 
                f.id, f.flight_number, f.airline, f.departure_time, 
                f.duration,
                f.price_economy, f.price_business, f.price_first,
                dep.city AS departure_city, dep.iata_code AS departure_iata,
                arr.city AS arrival_city, arr.iata_code AS arrival_iata
            FROM flights f
            INNER JOIN airports dep ON f.departure_airport_id = dep.id
            INNER JOIN airports arr ON f.arrival_airport_id = arr.id
            WHERE 1=1
        ";

        $params = [];

        if (!empty($criteria['departure'])) {
            if (preg_match('/\(([A-Z]{3})\)$/', $criteria['departure'], $matches)) {
                 $sql .= " AND (dep.iata_code = :dep_iata)";
                 $params['dep_iata'] = $matches[1];
            } else {
                 $sql .= " AND (dep.city LIKE :dep_city OR dep.name LIKE :dep_name)";
                 $params['dep_city'] = '%' . trim($criteria['departure']) . '%';
                 $params['dep_name'] = '%' . trim($criteria['departure']) . '%';
            }
        }

        if (!empty($criteria['arrival'])) {
            if (preg_match('/\(([A-Z]{3})\)$/', $criteria['arrival'], $matches)) {
                 $sql .= " AND (arr.iata_code = :arr_iata)";
                 $params['arr_iata'] = $matches[1];
            } else {
                 $sql .= " AND (arr.city LIKE :arr_city OR arr.name LIKE :arr_name)";
                 $params['arr_city'] = '%' . trim($criteria['arrival']) . '%';
                 $params['arr_name'] = '%' . trim($criteria['arrival']) . '%';
            }
        }

        if (!empty($criteria['departure_date'])) {
            $sql .= " AND DATE(f.departure_time) = :dep_date";
            $params['dep_date'] = $criteria['departure_date'];
        }

        if (!empty($criteria['travel_class'])) {
            $classColumn = match ($criteria['travel_class']) {
                'business' => 'price_business',
                'first' => 'price_first',
                default => 'price_economy',
            };
            $sql .= " AND f.$classColumn IS NOT NULL";
        }

        $sql .= " ORDER BY f.departure_time ASC";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $results = $stmt->fetchAll();

            if (empty($results) && !empty($criteria['departure']) && !empty($criteria['arrival'])) {
                 $sqlFlexibleDate = "
                    SELECT 
                        f.id, f.flight_number, f.airline, f.departure_time, 
                        f.duration,
                        f.price_economy, f.price_business, f.price_first,
                        dep.city AS departure_city, dep.iata_code AS departure_iata,
                        arr.city AS arrival_city, arr.iata_code AS arrival_iata
                    FROM flights f
                    INNER JOIN airports dep ON f.departure_airport_id = dep.id
                    INNER JOIN airports arr ON f.arrival_airport_id = arr.id
                    WHERE (dep.city LIKE :dep_city OR dep.name LIKE :dep_name OR dep.iata_code LIKE :dep_iata)
                      AND (arr.city LIKE :arr_city OR arr.name LIKE :arr_name OR arr.iata_code LIKE :arr_iata)
                      AND f.departure_time >= NOW()
                    ORDER BY f.departure_time ASC
                    LIMIT 5
                ";
                
                $depTerm = '%' . trim($criteria['departure']) . '%';
                $arrTerm = '%' . trim($criteria['arrival']) . '%';
                $depIata = preg_match('/\(([A-Z]{3})\)$/', $criteria['departure'], $m) ? $m[1] : $criteria['departure'];
                $arrIata = preg_match('/\(([A-Z]{3})\)$/', $criteria['arrival'], $m) ? $m[1] : $criteria['arrival'];

                $stmtFlex = $this->db->prepare($sqlFlexibleDate);
                $stmtFlex->execute([
                    'dep_city' => $depTerm, 'dep_name' => $depTerm, 'dep_iata' => $depIata,
                    'arr_city' => $arrTerm, 'arr_name' => $arrTerm, 'arr_iata' => $arrIata
                ]);
                $results = $stmtFlex->fetchAll();
            }

            return $results;

        } catch (PDOException $e) {
            error_log("Erreur SQL lors de la recherche de vols: " . $e->getMessage());
            return [];
        }
    }
    
    public function searchAirport(string $term): array {
        $likeTerm = '%' . $term . '%';
        
        $sql = "
            SELECT 
                iata_code, name, city, country 
            FROM airports 
            WHERE city LIKE :term_city 
               OR iata_code LIKE :term_iata
               OR name LIKE :term_name
            LIMIT 10
        ";
        
        $params = [
            'term_city' => $likeTerm,
            'term_iata' => $likeTerm,
            'term_name' => $likeTerm
        ];
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Erreur SQL lors de la recherche d'aéroports (autocomplétion): " . $e->getMessage());
            return [];
        }
    }

    public function getFlightById(int $id): array|false {
        $sql = "
            SELECT 
                f.*, 
                TIME_FORMAT(f.duration, '%Hh %imin') AS duration_formatted,
                dep.city AS departure_city, dep.iata_code AS departure_iata, dep.name AS departure_airport_name,
                arr.city AS arrival_city, arr.iata_code AS arrival_iata, arr.name AS arrival_airport_name
            FROM flights f
            INNER JOIN airports dep ON f.departure_airport_id = dep.id
            INNER JOIN airports arr ON f.arrival_airport_id = arr.id
            WHERE f.id = :id
        ";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur de récupération du vol : " . $e->getMessage());
            return false;
        }
    }
}