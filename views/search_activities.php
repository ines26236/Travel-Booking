<?php 
// Assurez-vous que la session est démarrée au début de index.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trouver des Activités - TravelBooking</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f2f5;
        }

        /* Styles pour le Header Background */
        .header-background {
            /* Remplacez 'activities_background.jpg' par le chemin de votre image */
            background-image: url('activities_background.jpg'); 
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
            background-color: rgba(0, 0, 0, 0.4); /* Overlay sombre */
            backdrop-filter: blur(2px);
            z-index: 1;
        }

        .header-content {
            position: relative;
            z-index: 2;
        }

        /* Styles de la navigation pour la cohérence */
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
                    <a href="index.php?action=hotels" class="nav-link">
                        <span class="text-sm">Hôtels</span>
                    </a>
                    <a href="index.php?action=activities" class="nav-link active">
                        <span class="text-sm">Activités</span>
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
                Découvrez des Activités et Attractions
            </h1>

            <div class="bg-white p-8 rounded-xl shadow-lg">
                
                <form action="index.php?action=search-activities-results" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    
                    <div class="search-input-group col-span-full md:col-span-2 lg:col-span-2">
                        <label for="activity_destination" class="sr-only">Destination</label>
                        <i class="fas fa-map-marker-alt search-input-icon"></i>
                        <input type="text" id="activity_destination" name="destination" placeholder="Ville ou point d'intérêt" class="search-input" required>
                    </div>

                    <div class="search-input-group col-span-1">
                        <label for="activity_date" class="sr-only">Date</label>
                        <i class="fas fa-calendar-alt search-input-icon"></i>
                        <input type="date" id="activity_date" name="date" class="search-input" required>
                    </div>

                    <div class="search-input-group col-span-1">
                        <label for="participants" class="sr-only">Participants</label>
                        <i class="fas fa-users search-input-icon"></i>
                        <input type="number" id="participants" name="participants" value="2" min="1" placeholder="Nombre de participants" class="search-input" required>
                    </div>

                    <div class="search-input-group col-span-full md:col-span-2 lg:col-span-2">
                        <label for="category" class="sr-only">Catégorie</label>
                        <i class="fas fa-tags search-input-icon"></i>
                        <select id="category" name="category" class="search-input appearance-none bg-white pr-8">
                            <option value="">Toutes les catégories</option>
                            <option value="museums">Musées & Culture</option>
                            <option value="tours">Visites guidées</option>
                            <option value="food">Gastronomie</option>
                            <option value="outdoors">Plein Air & Nature</option>
                        </select>
                        <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-600 pointer-events-none"></i>
                    </div>
                    
                    <div class="col-span-full flex justify-end">
                        <button type="submit" class="w-full md:w-auto bg-purple-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-purple-700 transition duration-200 shadow-md">
                            <i class="fas fa-search mr-2"></i> Rechercher Des Activités
                        </button>
                    </div>
                </form>
            </div>

            <div class="info-box text-center bg-purple-100 border-purple-300 text-purple-800 mt-8 rounded-lg p-4 flex items-center gap-4 shadow-md">
                <i class="fas fa-magic text-3xl"></i>
                <p class="text-lg">Trouvez les meilleures visites, excursions et attractions locales pour agrémenter votre voyage.</p>
            </div>
            
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Empêcher la sélection d'une date passée pour l'activité
            const activityDateInput = document.getElementById('activity_date');
            const today = new Date().toISOString().split('T')[0];
            if (activityDateInput) {
                activityDateInput.setAttribute('min', today);
            }
        });
    </script>
</body>
</html>