<?php

require_once 'database/database.php'; 

class Hotel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function searchHotels(array $criteria): array {
        $sql = "SELECT * FROM hotels WHERE 1=1";
        $params = [];

        if (!empty($criteria['destination'])) {
            $sql .= " AND (city LIKE :dest1 OR name LIKE :dest2)";
            $searchTerm = '%' . trim($criteria['destination']) . '%';
            $params['dest1'] = $searchTerm;
            $params['dest2'] = $searchTerm;
        }

        $sql .= " ORDER BY price_per_night ASC";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Erreur SQL recherche hôtels: " . $e->getMessage());
            return [];
        }
    }

    public function getHotelById(int $id): ?array {
        try {
            $stmt = $this->db->prepare("SELECT * FROM hotels WHERE id = :id");
            $stmt->execute(['id' => $id]);
            $result = $stmt->fetch();
            return $result ?: null;
        } catch (PDOException $e) {
            error_log("Erreur SQL get hotel by ID: " . $e->getMessage());
            return null;
        }
    }

    public function createHotelBooking(int $userId, int $hotelId, string $checkIn, string $checkOut, int $guests, float $totalPrice): ?int {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO hotel_bookings (user_id, hotel_id, check_in, check_out, guests, total_price, status)
                VALUES (:user_id, :hotel_id, :check_in, :check_out, :guests, :total_price, 'confirmed')
            ");
            
            $stmt->execute([
                'user_id' => $userId,
                'hotel_id' => $hotelId,
                'check_in' => $checkIn,
                'check_out' => $checkOut,
                'guests' => $guests,
                'total_price' => $totalPrice
            ]);

            return (int)$this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Erreur SQL create hotel booking: " . $e->getMessage());
            return null;
        }
    }

    public function getUserHotelBookings(int $userId): array {
        try {
            $stmt = $this->db->prepare("
                SELECT hb.*, h.name as hotel_name, h.city, h.main_image_url
                FROM hotel_bookings hb
                JOIN hotels h ON hb.hotel_id = h.id
                WHERE hb.user_id = :user_id
                ORDER BY hb.created_at DESC
            ");
            $stmt->execute(['user_id' => $userId]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Erreur SQL get user hotel bookings: " . $e->getMessage());
            return [];
        }
    }
}
