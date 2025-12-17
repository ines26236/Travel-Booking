<?php

require_once 'models/modelUtilisateur.php';
require_once 'models/flight.php';
require_once 'models/hotels.php';
require_once 'models/booking.php';

class PageController {
    private User $userModel;
    private Flight $flightModel;
    private Hotel $hotelModel;
    private Booking $bookingModel;

    public function __construct() {
        $this->userModel = new User();
        $this->flightModel = new Flight();
        $this->hotelModel = new Hotel();
        $this->bookingModel = new Booking();
    }

    private function isAuthenticated(): bool {
        if (session_status() === PHP_SESSION_NONE) session_start();
        return isset($_SESSION['user']) && !empty($_SESSION['user']);
    }

    public function error(int $code, string $message): void {
        http_response_code($code);
        echo "<div style='text-align: center; padding: 50px; font-family: sans-serif;'>";
        echo '<h1 style="color: #e53e3e;">Erreur ' . $code . '</h1>';
        echo '<p style="font-size: 1.2rem; color: #4a5568;">' . htmlspecialchars($message) . '</p>';
        echo '<a href="index.php" style="color: #3182ce; text-decoration: underline;">Retour à l\'accueil</a>';
        echo "</div>";
    }

    public function accueil(): void {
        require 'views/Acceuil.php';
    }

    public function connexion(): void {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $user = $this->userModel->login($email, $password);

            if ($user) {
                $_SESSION['user'] = $user;
                $redirectUrl = $_SESSION['redirect_to'] ?? 'index.php?action=vols';
                unset($_SESSION['redirect_to']);
                header('Location: ' . $redirectUrl);
                exit;
            } else {
                 $errorMessage = "Email ou mot de passe incorrect.";
            }
        }
        require 'views/Connexion.php';
    }

    public function inscription(): void {
        $errorMessage = null;
        $successMessage = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['full_name'] ?? '';  
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            if (empty($name) || empty($email) || empty($password)) {
                 $errorMessage = "Veuillez remplir tous les champs.";
            } else {
                $success = $this->userModel->register($name, $email, $password);

                if ($success) {
                    $_SESSION['flash_message'] = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
                    header('Location: index.php?action=login');
                    exit;
                } else {
                    $errorMessage = "Erreur lors de l'inscription. L'email peut déjà exister.";
                }
            }
        }
        require 'views/Inscription.php';
    }

    public function profil(): void {
        if (!$this->isAuthenticated()) {
            header('Location: index.php?action=login');
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $userData = $this->userModel->getUserById($userId);
        $message = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
        
            if (!empty($name) && !empty($email)) {
                $success = $this->userModel->updateProfile($userId, $name, $email);

                if ($success) {
                    $message = "Profil mis à jour avec succès !";
                    $_SESSION['user']['name'] = $name;
                    $_SESSION['user']['email'] = $email;
                    $userData['name'] = $name;
                    $userData['email'] = $email;
                } else {
                    $message = "Erreur lors de la mise à jour (Email déjà pris ?).";
                }
            } else {
                 $message = "Tous les champs sont obligatoires.";
            }
        }
        require 'views/profile.php';
    }

    public function booking(): void {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        if (!$this->isAuthenticated()) {
            $_SESSION['redirect_to'] = 'index.php?action=vols';
            header('Location: index.php?action=login');
            exit;
        }

        require 'views/booking.php';
    }

    public function searchFlightsAction(): void {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        $method = $_SERVER['REQUEST_METHOD'];
        $data = ($method === 'POST') ? $_POST : $_GET;

        $criteria = [
            'departure' => $data['departure'] ?? '',
            'arrival' => $data['arrival'] ?? '',
            'departure_date' => $data['departure_date'] ?? '',
            'return_date' => $data['return_date'] ?? '',
            'passengers' => $data['passengers'] ?? 1,
            'travel_class' => $data['travel_class'] ?? 'economy',
            'trip_type' => $data['trip_type'] ?? 'round_trip'
        ];
        
        if (isset($data['trip_type_radio'])) {
             $criteria['trip_type'] = $data['trip_type_radio'];
        }

        $_SESSION['last_search'] = $criteria;
        
        try {
            $vols_trouves = $this->flightModel->searchFlights($criteria);
        } catch (Exception $e) {
            $vols_trouves = [];
        }

        require 'views/flight_results.php';
    }

    public function searchHotelsAction(): void {
        require 'views/search_hotels.php';
    }

    public function showHotelResults(): void {
    $criteria = [
        'destination' => $_GET['destination'] ?? '',
        'check_in_date' => $_GET['check_in_date'] ?? '',
        'check_out_date' => $_GET['check_out_date'] ?? '',
        'guests' => $_GET['guests'] ?? 1
    ];

    $hotelsFound = $this->hotelModel->searchHotels($criteria);

    usort($hotelsFound, function($a, $b) {
        return strcmp($a['name'], $b['name']);
    });

    require 'views/hotels_list.php';
}


    public function search_hotels(): void {
        $this->searchHotelsAction();
    }

    public function searchAirportAction(): void {
        header('Content-Type: application/json');
        $term = filter_input(INPUT_GET, 'term', FILTER_SANITIZE_STRING) ?? ''; 
        
        if ($term) {
            $results = $this->flightModel->searchAirport($term);
            echo json_encode($results);
        } else {
            echo json_encode([]);
        }
    }

    public function bookFlightAction(): void {
        if (!$this->isAuthenticated()) {
            $_SESSION['redirect_to'] = 'index.php?action=book-flight&flight_id=' . ($_GET['flight_id'] ?? '');
            header('Location: index.php?action=login');
            exit;
        }

        $flightId = filter_input(INPUT_GET, 'flight_id', FILTER_VALIDATE_INT);
        if (!$flightId) {
            $this->error(400, "ID de vol manquant ou invalide.");
            return;
        }

        $flight = $this->flightModel->getFlightById($flightId);
        if (!$flight) {
            $this->error(404, "Le vol demandé n'existe pas.");
            return;
        }

        $passengers = $_SESSION['last_search']['passengers'] ?? 1; 
        $travelClass = $_SESSION['last_search']['travel_class'] ?? 'economy'; 

        // Override flight date with the searched date
        if (!empty($_SESSION['last_search']['departure_date'])) {
            $searchDate = $_SESSION['last_search']['departure_date'];
            $dbTime = date('H:i:s', strtotime($flight['departure_time']));
            $flight['departure_time'] = $searchDate . ' ' . $dbTime;
        }

        require 'views/booking_form.php';
    }

    public function confirmBookingAction(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=vols');
            exit;
        }

        if (!$this->isAuthenticated()) {
            header('Location: index.php?action=login');
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $flightId = filter_input(INPUT_POST, 'flight_id', FILTER_VALIDATE_INT);
        $numberOfPassengers = filter_input(INPUT_POST, 'number_of_passengers', FILTER_VALIDATE_INT) ?? 1;
        $travelClass = $_POST['travel_class'] ?? 'economy';
        $totalPrice = floatval($_POST['total_price'] ?? 0);

        if (!$flightId || $totalPrice <= 0) {
            $this->error(400, "Données de réservation invalides.");
            return;
        }

        $passengersData = [];
        for ($i = 1; $i <= $numberOfPassengers; $i++) {
            $title = $_POST["passenger_{$i}_title"] ?? '';
            $firstName = $_POST["passenger_{$i}_first_name"] ?? '';
            $lastName = $_POST["passenger_{$i}_last_name"] ?? '';
            $dateOfBirth = $_POST["passenger_{$i}_date_of_birth"] ?? '';
            $phoneNumber = $_POST["passenger_{$i}_phone_number"] ?? '';
            $email = $_POST["passenger_{$i}_email"] ?? '';

            if (empty($title) || empty($firstName) || empty($lastName) || 
                empty($dateOfBirth) || empty($phoneNumber) || empty($email)) {
                $this->error(400, "Toutes les informations des passagers sont requises.");
                return;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->error(400, "L'adresse email du passager {$i} est invalide.");
                return;
            }

            $passengersData[] = [
                'title' => $title,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'date_of_birth' => $dateOfBirth,
                'phone_number' => $phoneNumber,
                'email' => $email
            ];
        }

        try {
            $bookingId = $this->bookingModel->createBookingWithPassengers(
                $userId, 
                $flightId, 
                $travelClass, 
                $numberOfPassengers, 
                $totalPrice, 
                $passengersData
            );
            
            if ($bookingId) {
                $_SESSION['flash_message'] = "Votre réservation a été confirmée avec succès ! Bon voyage !";
                header('Location: index.php?action=profile'); 
                exit;
            } else {
                $this->error(500, "Une erreur s'est produite lors de l'enregistrement de votre réservation.");
            }
        } catch (Exception $e) {
            $this->error(500, "Erreur: " . $e->getMessage());
        }
    }

    public function helpPage(): void {
        $userName = isset($_SESSION['user']['name']) ? $_SESSION['user']['name'] : 'Visiteur';
        require 'views/HelpPage.php';
    }



    public function profile(): void {
        if (!$this->isAuthenticated()) {
            header('Location: index.php?action=login');
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $bookings = $this->bookingModel->getUserBookingsWithDetails($userId);
        $totalBookings = $this->bookingModel->countUserBookings($userId);
        $hotelBookings = $this->hotelModel->getUserHotelBookings($userId);
        
        // Get car bookings
        require_once 'database/database.php';
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("
            SELECT cb.*, c.brand, c.model, c.image_url 
            FROM car_bookings cb 
            JOIN cars c ON cb.car_id = c.id 
            WHERE cb.user_id = ? 
            ORDER BY cb.created_at ASC
        ");
        $stmt->execute([$userId]);
        $carBookings = $stmt->fetchAll();
        
        require 'views/profile.php';
    }

    public function logout(): void {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $_SESSION = [];
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }
        session_destroy();
        header('Location: index.php?action=home');
        exit;
    }

    public function bookHotelAction(): void {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        if (!$this->isAuthenticated()) {
            $_SESSION['redirect_to'] = $_SERVER['REQUEST_URI'];
            header('Location: index.php?action=login');
            exit;
        }

        $hotelId = $_GET['hotel_id'] ?? null;
        
        if (!$hotelId) {
            header('Location: index.php?action=hotels');
            exit;
        }

        $hotel = $this->hotelModel->getHotelById($hotelId);
        
        if (!$hotel) {
            $this->error(404, "Hôtel non trouvé");
            return;
        }

        require 'views/hotel_booking_form.php';
    }

    public function confirmHotelBookingAction(): void {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        if (!$this->isAuthenticated()) {
            header('Location: index.php?action=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=hotels');
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $hotelId = $_POST['hotel_id'] ?? null;
        $checkIn = $_POST['check_in'] ?? null;
        $checkOut = $_POST['check_out'] ?? null;
        $guests = $_POST['guests'] ?? 1;
        $totalPrice = $_POST['total_price'] ?? 0;

        if (!$hotelId || !$checkIn || !$checkOut) {
            $_SESSION['flash_message'] = "Données de réservation invalides.";
            header('Location: index.php?action=hotels');
            exit;
        }

        try {
            $bookingId = $this->hotelModel->createHotelBooking(
                $userId,
                $hotelId,
                $checkIn,
                $checkOut,
                $guests,
                $totalPrice
            );

            if ($bookingId) {
                $_SESSION['flash_message'] = "Votre réservation d'hôtel a été confirmée avec succès !";
                header('Location: index.php?action=profile');
                exit;
            } else {
                $_SESSION['flash_message'] = "Erreur lors de la réservation. Veuillez réessayer.";
                header('Location: index.php?action=hotels');
                exit;
            }
        } catch (Exception $e) {
            $_SESSION['flash_message'] = "Erreur: " . $e->getMessage();
            header('Location: index.php?action=hotels');
            exit;
        }
    }

    public function carsListAction(): void {
        require 'views/cars_list.php';
    }

    public function bookCarAction(): void {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        if (!$this->isAuthenticated()) {
            $_SESSION['redirect_to'] = $_SERVER['REQUEST_URI'];
            header('Location: index.php?action=login');
            exit;
        }

        $carId = $_GET['car_id'] ?? null;
        
        if (!$carId) {
            header('Location: index.php?action=cars');
            exit;
        }

        require_once 'database/database.php';
        $db = Database::getInstance()->getConnection();
        
        $stmt = $db->prepare("SELECT * FROM cars WHERE id = ? AND available = 1");
        $stmt->execute([$carId]);
        $car = $stmt->fetch();
        
        if (!$car) {
            $this->error(404, "Voiture non trouvée ou non disponible");
            return;
        }

        require 'views/car_booking_form.php';
    }

    public function confirmCarBookingAction(): void {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        if (!$this->isAuthenticated()) {
            header('Location: index.php?action=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=cars');
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $carId = $_POST['car_id'] ?? null;
        $pickupDate = $_POST['pickup_date'] ?? null;
        $returnDate = $_POST['return_date'] ?? null;
        $pickupLocation = $_POST['pickup_location'] ?? '';
        $returnLocation = $_POST['return_location'] ?? $pickupLocation;
        $totalPrice = $_POST['total_price'] ?? 0;

        if (!$carId || !$pickupDate || !$returnDate || !$pickupLocation) {
            $_SESSION['flash_message'] = "Données de réservation invalides.";
            header('Location: index.php?action=cars');
            exit;
        }

        try {
            require_once 'database/database.php';
            $db = Database::getInstance()->getConnection();
            
            $stmt = $db->prepare("
                INSERT INTO car_bookings (user_id, car_id, pickup_date, return_date, pickup_location, return_location, total_price, status, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, 'confirmed', NOW())
            ");
            
            $result = $stmt->execute([
                $userId,
                $carId,
                $pickupDate,
                $returnDate,
                $pickupLocation,
                $returnLocation ?: $pickupLocation,
                $totalPrice
            ]);

            if ($result) {
                $_SESSION['flash_message'] = "Votre réservation de voiture a été confirmée avec succès !";
                header('Location: index.php?action=profile');
                exit;
            } else {
                $_SESSION['flash_message'] = "Erreur lors de la réservation. Veuillez réessayer.";
                header('Location: index.php?action=cars');
                exit;
            }
        } catch (Exception $e) {
            $_SESSION['flash_message'] = "Erreur: " . $e->getMessage();
            header('Location: index.php?action=cars');
            exit;
        }
    }
}
