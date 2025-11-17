<?php
// models/modelUtilisateur.php

require_once 'database/Database.php';

class User {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    // 1️⃣ — Register a new user
    public function register(string $name, string $email, string $password): bool {
        try {
            $hashed = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $this->pdo->prepare(
                "INSERT INTO users (name, email, password) VALUES (?, ?, ?)"
            );
            return $stmt->execute([$name, $email, $hashed]);
        } catch (PDOException $e) {
            // Optional: log the error
            return false;
        }
    }

    // 2️⃣ — Login a user
    public function login(string $email, string $password): array|false {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    // 3️⃣ — Get user by ID
    public function getUserById(int $id): array|false {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 4️⃣ — Update user profile
    public function updateProfile(int $id, string $name, string $email): bool {
        $stmt = $this->pdo->prepare(
            "UPDATE users SET name = ?, email = ? WHERE id = ?"
        );
        return $stmt->execute([$name, $email, $id]);
    }

    // 5️⃣ — Get bookings for a user
    public function getBooking(int $user_id): array {
        $stmt = $this->pdo->prepare("SELECT * FROM booking WHERE user_id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
