<?php

require_once __DIR__ . '/../database/Database.php'; 
require_once __DIR__ . '/../models/modelUtilisateur.php'; 

class UserController {
    private User $userModel;

    public function __construct() {
        // Le modèle User se connecte automatiquement à la base de données via le Singleton
        $this->userModel = new User();
    }

    /**
     * Affiche la page d'inscription (view)
     */
    public function showRegisterPage(): void {
        // Chargez ici le fichier de vue de l'inscription
        require_once __DIR__ . '/../views/register.php';
    }

    /**
     * Traite la soumission du formulaire d'inscription.
     */
    public function registerUser(): void {
        // Vérification de la méthode de la requête et des données POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            
            // 1. Validation de base des champs
            if (empty($name) || empty($email) || empty($password)) {
                //  stocker l'erreur dans la session pour l'afficher
                $_SESSION['error'] = "Veuillez remplir tous les champs.";
                header('Location: index.php?action=register');
                exit;
            }
            
            // 2. Tenter d'enregistrer l'utilisateur
            if ($this->userModel->register($name, $email, $password)) {
                $_SESSION['success'] = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
                header('Location: index.php?action=login');
            } else {
                $_SESSION['error'] = "Erreur lors de l'inscription. L'email est peut-être déjà utilisé.";
                header('Location: index.php?action=register');
            }
        } else {
            header('Location: index.php?action=register'); 
        }
        exit;
    }

    /**
     * Affiche la page de connexion (view)
     */
    public function showLoginPage(): void {
        // Chargez ici le fichier de vue de la connexion 
        require_once __DIR__ . '/../views/login.php';
    }

    /**
     * Traite la soumission du formulaire de connexion.
     */
    public function loginUser(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            // 1. Tenter la connexion via le modèle
            $user = $this->userModel->login($email, $password);

            if ($user) {
                // 2. Connexion réussie : Stocker les données utilisateur dans la session
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email']
                ];
                $_SESSION['success'] = "Connexion réussie ! Bienvenue, {$user['name']}.";
                
                header('Location: index.php?action=home'); 
            } else {
                // Échec de la connexion
                $_SESSION['error'] = "Email ou mot de passe incorrect.";
                header('Location: index.php?action=login');
            }
        } else {
            header('Location: index.php?action=login');
        }
        exit;
    }

    /**
     * Déconnecte l'utilisateur.
     */
    public function logoutUser(): void {
        // Si la session existe, la détruire
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_unset();    
            session_destroy(); 
        }
        header('Location: index.php?action=home');
        exit;
    }
}