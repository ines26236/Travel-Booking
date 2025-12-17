<?php

require_once __DIR__ . '/../database/Database.php'; 
require_once __DIR__ . '/../models/modelUtilisateur.php'; 

class UserController {
    private User $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function showRegisterPage(): void {
        require_once __DIR__ . '/../views/register.php';
    }

    public function registerUser(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            
            if (empty($name) || empty($email) || empty($password)) {
                $_SESSION['error'] = "Veuillez remplir tous les champs.";
                header('Location: index.php?action=register');
                exit;
            }
            
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

    public function showLoginPage(): void {
        require_once __DIR__ . '/../views/login.php';
    }

    public function loginUser(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            $user = $this->userModel->login($email, $password);

            if ($user) {
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email']
                ];
                $_SESSION['success'] = "Connexion réussie ! Bienvenue, {$user['name']}.";
                
                header('Location: index.php?action=home'); 
            } else {
                $_SESSION['error'] = "Email ou mot de passe incorrect.";
                header('Location: index.php?action=login');
            }
        } else {
            header('Location: index.php?action=login');
        }
        exit;
    }

    public function logoutUser(): void {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_unset();    
            session_destroy(); 
        }
        header('Location: index.php?action=home');
        exit;
    }
}