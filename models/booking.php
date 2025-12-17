<?php

require_once 'database/database.php';

class Booking {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function createBooking(int $userId, int $flightId, string $travelClass, int $passengers, float $totalPrice): bool {
        $sql = "INSERT INTO bookings (user_id, flight_id, travel_class, number_of_passengers, total_price, status) VALUES (:user_id, :flight_id, :travel_class, :passengers, :total_price, 'confirmed')";
        
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'user_id' => $userId,
                'flight_id' => $flightId,
                'travel_class' => $travelClass,
                'passengers' => $passengers,
                'total_price' => $totalPrice
            ]);
        } catch (PDOException $e) {
            error_log("Erreur lors de la création de la réservation : " . $e->getMessage());
            return false;
        }
    }

    public function createBookingWithPassengers(int $userId, int $flightId, string $travelClass, int $numberOfPassengers, float $totalPrice, array $passengersData) {
        try {
            $this->db->beginTransaction();
            
            $sql = "INSERT INTO bookings (user_id, flight_id, travel_class, number_of_passengers, total_price, status) 
                    VALUES (:user_id, :flight_id, :travel_class, :passengers, :total_price, 'confirmed')";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'user_id' => $userId,
                'flight_id' => $flightId,
                'travel_class' => $travelClass,
                'passengers' => $numberOfPassengers,
                'total_price' => $totalPrice
            ]);
            
            $bookingId = $this->db->lastInsertId();
            
            foreach ($passengersData as $passenger) {
                $this->addPassenger($bookingId, $passenger);
            }
            
            $this->db->commit();
            
            return $bookingId;
        } catch (PDOException $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            error_log("Erreur lors de la création de la réservation avec passagers : " . $e->getMessage());
            throw new \Exception("Erreur base de données: " . $e->getMessage());
        }
    }

    public function addPassenger(int $bookingId, array $passengerData): bool {
        $sql = "INSERT INTO passengers (booking_id, title, first_name, last_name, date_of_birth, phone_number, email) 
                VALUES (:booking_id, :title, :first_name, :last_name, :date_of_birth, :phone_number, :email)";
        
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'booking_id' => $bookingId,
                'title' => $passengerData['title'],
                'first_name' => $passengerData['first_name'],
                'last_name' => $passengerData['last_name'],
                'date_of_birth' => $passengerData['date_of_birth'],
                'phone_number' => $passengerData['phone_number'],
                'email' => $passengerData['email']
            ]);
        } catch (PDOException $e) {
            error_log("Erreur lors de l'ajout du passager : " . $e->getMessage());
            return false;
        }
    }

    public function getBookingPassengers(int $bookingId): array {
        $sql = "SELECT * FROM passengers WHERE booking_id = :booking_id ORDER BY id ASC";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['booking_id' => $bookingId]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des passagers : " . $e->getMessage());
            return [];
        }
    }

    public function getUserBookingsWithDetails(int $userId): array {
        $sql = "SELECT 
                    b.*,
                    f.flight_number,
                    f.airline,
                    f.departure_time,
                    f.arrival_time,
                    f.duration,
                    dep.name as departure_airport,
                    dep.city as departure_city,
                    dep.iata_code as departure_iata,
                    arr.name as arrival_airport,
                    arr.city as arrival_city,
                    arr.iata_code as arrival_iata
                FROM bookings b
                JOIN flights f ON b.flight_id = f.id
                JOIN airports dep ON f.departure_airport_id = dep.id
                JOIN airports arr ON f.arrival_airport_id = arr.id
                WHERE b.user_id = :user_id
                ORDER BY b.created_at DESC";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['user_id' => $userId]);
            $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($bookings as &$booking) {
                $booking['passengers'] = $this->getBookingPassengers($booking['id']);
            }
            
            return $bookings;
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des réservations : " . $e->getMessage());
            return [];
        }
    }

    public function countUserBookings(int $userId): int {
        $sql = "SELECT COUNT(*) as total FROM bookings WHERE user_id = :user_id";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['user_id' => $userId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)$result['total'];
        } catch (PDOException $e) {
            error_log("Erreur lors du comptage des réservations : " . $e->getMessage());
            return 0;
        }
    }
}
