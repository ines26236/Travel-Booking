#  TravelBooking

**TravelBooking** est une plateforme complète de réservation de voyages développée en **PHP Natif** suivant l'architecture **MVC** (Modèle-Vue-Contrôleur). Elle permet aux utilisateurs de réserver des vols, des hôtels et des voitures de location via une interface moderne et responsive stylisée avec **Tailwind CSS**.


##  Fonctionnalités Principales

### 1. Gestion des Vols
*   **Recherche avancée** : Recherche par ville/aéroport de départ et d'arrivée, dates, nombre de passagers et classe de voyage.
*   **Autocomplétion** : Suggestion d'aéroports en temps réel via AJAX.
*   **Réservation** : Sélection de vols (Aller simple ou Aller-retour) et enregistrement des passagers.
*   **Calcul de prix** : Gestion dynamique des prix selon la classe (Économique, Affaires, Première).

### 2. Gestion des Hôtels 
*   **Catalogue** : Liste d'hôtels avec photos, descriptions, notes et prix.
*   **Recherche** : Filtrage par destination, dates de séjour et nombre d'invités.
*   **Réservation** : Formulaire de réservation complet avec calcul automatique du coût total en fonction de la durée du séjour.

### 3. Location de Voitures
*   **Flotte variée** : Affichage des véhicules disponibles
*   **Réservation** : Sélection des dates de prise en charge et de retour avec calcul du prix journalier.

### 4. Espace Utilisateur
*   **Authentification** : Système complet d'inscription et de connexion sécurisé.
*   **Profil Dashboard** : Tableau de bord personnel affichant l'historique de toutes les réservations (Vols, Hôtels, Voitures) avec leur statut.

##  Stack Technique

*   **Langage Backend** : PHP 8.x (Natif, sans framework backend).
*   **Base de Données** : MySQL.
*   **Architecture** : MVC (Model - View - Controller).
*   **Frontend** : HTML5, JavaScript.
*   **CSS Framework** : Tailwind CSS (via CDN).
*   **Serveur Web** : Apache (via WAMP).

## Structure du Projet

L'application respecte une structure claire et modulaire :


/Reservation
├── assets/             # Ressources statiques (Images)
├── controllers/        # Logique de l'application (PageController, UserController...)
├── database/           # Connexion BDD et scripts SQL (init_db.sql)
├── models/             # Interaction avec la base de données (Flight, Hotel, Car...)
├── views/              # Fichiers d'affichage HTML/PHP
├── index.php           # Point d'entrée unique (Routeur frontal)
├── route.php           # Configuration des routes URL
└── README.md           # Documentation du projet

##  Installation

1.  **Prérequis** : Avoir un environnement serveur local comme **WAMP**.
2.  **Placement** : Placez le dossier du projet dans votre répertoire web .
3.  **Base de Données** :
    *   Ouvrez phpMyAdmin.
    *   Créez une base de données nommée `travel_booking`et insérer les tables nécessaires.
    
4.  **Configuration** :
    *   Vérifiez les identifiants dans `database/database.php` (Par défaut : user=`root`, password=``).
5.  **Lancement** :
    *   Accédez à `http://localhost/Reservation/` dans votre navigateur.


##  Auteurs
<<<<<<< HEAD
Projet développé dans le cadre d'un module de développement Web PHP Avancé.
=======
Projet développé dans le cadre d'un module de développement Web PHP Avancé.
>>>>>>> 41f05ee (ajout de la base de donnée)
