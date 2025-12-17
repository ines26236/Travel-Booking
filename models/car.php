<?php

require_once 'database/database.php'; 

class Car {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAllCars(): array {
        try {
            $stmt = $this->db->query("SELECT * FROM cars WHERE available = 1 ORDER BY price_per_day ASC");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Erreur SQL get all cars: " . $e->getMessage());
            return [];
        }
    }

    public function getCarById(int $id): ?array {
        try {
            $stmt = $this->db->prepare("SELECT * FROM cars WHERE id = :id");
            $stmt->execute(['id' => $id]);
            $result = $stmt->fetch();
            return $result ?: null;
        } catch (PDOException $e) {
            error_log("Erreur SQL get car by ID: " . $e->getMessage());
            return null;
        }
    }

    public function createCarBooking(int $userId, int $carId, string $pickupDate, string $returnDate, string $pickupLocation, ?string $returnLocation, float $totalPrice): ?int {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO car_bookings (user_id, car_id, pickup_date, return_date, pickup_location, return_location, total_price, status)
                VALUES (:user_id, :car_id, :pickup_date, :return_date, :pickup_location, :return_location, :total_price, 'confirmed')
            ");
            
            $stmt->execute([
                'user_id' => $userId,
                'car_id' => $carId,
                'pickup_date' => $pickupDate,
                'return_date' => $returnDate,
                'pickup_location' => $pickupLocation,
                'return_location' => $returnLocation ?? $pickupLocation,
                'total_price' => $totalPrice
            ]);

            return (int)$this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Erreur SQL create car booking: " . $e->getMessage());
            return null;
        }
    }

    public function getUserCarBookings(int $userId): array {
        try {
            $stmt = $this->db->prepare("
                SELECT cb.*, c.brand, c.model, c.image_url, c.seats, c.fuel_type
                FROM car_bookings cb
                JOIN cars c ON cb.car_id = c.id
                WHERE cb.user_id = :user_id
                ORDER BY cb.created_at DESC
            ");
            $stmt->execute(['user_id' => $userId]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Erreur SQL get user car bookings: " . $e->getMessage());
            return [];
        }
    }
}
