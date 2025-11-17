<?php

class Database {
    private static ?Database $instance = null;  // singleton
    private PDO $pdo;

    //  constructor prevents direct creation of object
    private function __construct() {
        $host = 'localhost';      
        $db   = 'travel_booking';  
        $user = 'root';     
        $pass = '';  
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, 
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // fetch as associative array
            PDO::ATTR_EMULATE_PREPARES   => false,                  // use real prepared statements
        ];

        try {
            $this->pdo = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            // Stop script and show error
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }
    public static function getInstance(): Database {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection(): PDO {
        return $this->pdo;
    }
}
