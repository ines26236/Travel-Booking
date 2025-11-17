<?php


require_once 'models/modelUtilisateur.php';

class PageController {
    private User $userModel;

    public function __construct() {
        $this->userModel = new User(); 
    }

    // 1 Homepage
    public function accueil(): void {
        require 'views/Acceuil.php';
    }

    // 2 Login page / process
  public function connexion(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        // Simulation d’un utilisateur connecté
        $user = ['id' => 1, 'name' => 'John Doe', 'email' => $email];

        if ($user) {
            $_SESSION['user'] = $user;

            $redirectUrl = $_SESSION['redirect_to'] ?? 'index.php?action=vols';
            unset($_SESSION['redirect_to']);

            header('Location: ' . $redirectUrl);
            exit;
        }
    }

    require 'views/Connexion.php';
}


    // 3 Registration page / process
    public function inscription(): void {
        $errorMessage = null;
        $successMessage = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            // Validation simple
            if (empty($name) || empty($email) || empty($password)) {
                 $errorMessage = "Veuillez remplir tous les champs.";
            } else {
                $success = $this->userModel->register($name, $email, $password);

                if ($success) {
                    // Rediriger vers la page de connexion avec un message de succès
                    $successMessage = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
                    $_SESSION['flash_message'] = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
                    header('Location: index.php?action=login');
                    exit;

                } else {
                    $errorMessage = "Erreur lors de l'inscription. L'email peut déjà exister.";
                }
            }
        }
        
        // $errorMessage sera disponible dans la vue Inscription.php
        require 'views/Inscription.php';
    }

    // 4  Profile page
    public function profil(): void {
    
        $message = null; // Pour les messages de succès/erreur de mise à jour

        if (!isset($_SESSION['user'])) {
            header('Location: index.php?action=login'); // Redirection vers la connexion
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $userData = $this->userModel->getUserById($userId); // Récupérer les données fraîches

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
        
            if (!empty($name) && !empty($email)) {
                $success = $this->userModel->updateProfile($userId, $name, $email);

                if ($success) {
                    $message = "Profil mis à jour avec succès !";
                    // Mettre à jour la session avec les nouvelles données
                    $_SESSION['user']['name'] = $name;
                    $_SESSION['user']['email'] = $email;
                    
                    // Mettre à jour $userData pour l'affichage immédiat
                    $userData['name'] = $name;
                    $userData['email'] = $email;
                } else {
                    $message = "Erreur lors de la mise à jour du profil. L'email peut déjà exister ou aucune donnée n'a changé.";
                }
            } else {
                 $message = "Veuillez remplir tous les champs obligatoires.";
            }
        }
        
        require 'views/Profil.php';
    }

    // 5 booking
  
public function booking(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
        $_SESSION['redirect_to'] = 'index.php?action=vols';
        header('Location: index.php?action=login');
        exit;
    }

    require 'views/booking.php';
}


    // 6— Generic error handler
    public function error(int $code, string $message): void {
        http_response_code($code);
        echo "<h1>Erreur $code</h1><p>$message</p>";
    }
    public function search_hotels(): void {

    if (!isset($_SESSION['user'])) {
        
        $_SESSION['redirect_to'] = 'index.php?action=hotels';
        header('Location: index.php?action=login'); 
        exit;
    }

    $hotelsList = [
        ['name' => 'Hôtel Central', 'city' => 'Paris', 'price' => 120],
        ['name' => 'Résidence Soleil', 'city' => 'Nice', 'price' => 95],
    ];

    // 6. Chargement de la vue (doit pointer vers votre nouveau fichier HTML/PHP)
    require 'views/search_hotels.php';
}
public function helpPage(): void {
    
    // Si la page d'aide a besoin du nom de l'utilisateur s'il est connecté :
    $userName = isset($_SESSION['user']['name']) ? $_SESSION['user']['name'] : 'Visiteur';
    require 'views/HelpPage.php';
}

// Dans controllers/PageController.php

public function activities(): void {
   
    if (!$this->isAuthenticated()) {
        $_SESSION['redirect_to'] = 'activities';
        header('Location: index.php?action=login'); 
        exit;
    }
   
    
    // Simplement afficher la vue
    require 'views/search_activities.php';
}

}
