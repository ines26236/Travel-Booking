<?php 
//la session est démarrée au début de index.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservez Votre Hôtel - TravelBooking</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f2f5;
        }

       
        .header-background {
            
            background-image: url('image9.png'); 
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            position: relative;
            z-index: 0;
            padding-top: 64px;
        }

        .header-background::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.4); 
            backdrop-filter: blur(2px);
            z-index: 1;
        }

        .header-content {
            position: relative;
            z-index: 2;
        }

        /* Styles de la navigation */
        .nav-link {
            padding: 0.75rem 1rem;
            border-bottom: 2px solid transparent;
            transition: all 0.2s ease-in-out;
        }

        .nav-link.active {
            border-color: #3b82f6; 
            color: #1f2937; 
            font-weight: 600;
        }
        
        /* Styles spécifiques aux inputs */
        .search-input-group {
            position: relative;
        }
        .search-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            border: 1px solid #d1d5db;
            background-color: #fff;
            font-size: 0.95rem;
            color: #374151;
            transition: all 0.2s ease-in-out;
            padding-left: 2.5rem; /* Espace pour l'icône */
        }
        .search-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 1px #3b82f6;
        }
        .search-input-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
        }
    </style>
</head>
<body>

    <header class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex-shrink-0">
                    <a href="index.php?action=home" class="text-2xl font-bold text-gray-800">TravelBooking</a>
                </div>
                <div class="flex items-center space-x-6 h-full">
                    <a href="index.php?action=vols" class="nav-link">
                        <span class="text-sm">Vols</span>
                    </a>
                    <a href="index.php?action=hotels" class="nav-link active">
                        <span class="text-sm">Hôtels</span>
                    </a>
                    <a href="index.php?action=login" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:border-blue-300 hover:text-blue-700 transition duration-150">
                        <span class="text-sm">Connexion</span>
                    </a>
                    <a href="index.php?action=signup" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:border-blue-300 hover:text-blue-700 transition duration-150">
                        <span class="text-sm">Créer un compte</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main class="header-background min-h-screen flex items-start justify-center pt-24 pb-12">
        <div class="header-content w-full max-w-4xl px-4">
            
            <h1 class="text-4xl font-extrabold text-white text-center mb-12">
                Réservez l'Hôtel Parfait
            </h1>

            <div class="bg-white p-8 rounded-xl shadow-lg">
                
                <form action="index.php?action=search-hotels" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    
                    <div class="search-input-group col-span-full md:col-span-2 lg:col-span-1">
                        <label for="destination" class="sr-only">Destination</label>
                        <i class="fas fa-city search-input-icon"></i>
                        <input type="text" id="destination" name="destination" placeholder="Ville, région ou hôtel" class="search-input" required>
                    </div>

                    <div class="search-input-group col-span-1">
                        <label for="check_in_date" class="sr-only">Arrivée</label>
                        <i class="fas fa-calendar-check search-input-icon"></i>
                        <input type="date" id="check_in_date" name="check_in_date" class="search-input" required>
                    </div>

                    <div class="search-input-group col-span-1">
                        <label for="check_out_date" class="sr-only">Départ</label>
                        <i class="fas fa-calendar-times search-input-icon"></i>
                        <input type="date" id="check_out_date" name="check_out_date" class="search-input" required>
                    </div>
                    
                    <div class="search-input-group col-span-1">
                        <label for="guests" class="sr-only">Invités et Chambres</label>
                        <i class="fas fa-user-friends search-input-icon"></i>
                        <input type="number" id="guests" name="guests" value="2" min="1" placeholder="Nombre d'invités" class="search-input" required>
                        </div>

                    <div class="col-span-full flex justify-end">
                        <button type="submit" class="w-full md:w-auto bg-green-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-green-700 transition duration-200 shadow-md">
                            <i class="fas fa-search mr-2"></i> Rechercher Des Hôtels
                        </button>
                    </div>
                </form>
            </div>

            <div class="info-box text-center bg-green-100 border-green-300 text-green-800 mt-8 rounded-lg p-4 flex items-center gap-4 shadow-md">
                <i class="fas fa-bed text-3xl"></i>
                <p class="text-lg">Entrez votre destination et vos dates pour trouver les meilleures offres d'hôtels.</p>
            </div>
            
        </div>
    </main>

    <script>
        //  validation simple pour les dates
        document.addEventListener('DOMContentLoaded', function() {
            const checkInInput = document.getElementById('check_in_date');
            const checkOutInput = document.getElementById('check_out_date');
            
            // Empêche la sélection d'une date passée
            const today = new Date().toISOString().split('T')[0];
            checkInInput.setAttribute('min', today);
            checkOutInput.setAttribute('min', today);

            // La date de départ doit être après la date d'arrivée
            checkInInput.addEventListener('change', function() {
                if (checkInInput.value) {
                    checkOutInput.setAttribute('min', checkInInput.value);
                }
            });
        });
    </script>
</body>
</html>