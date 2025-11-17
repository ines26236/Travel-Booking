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
    <title>Trouvez Votre Prochain Vol - TravelBooking</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f2f5; /* Un fond gris clair par défaut */
        }

        /* Styles pour le Header Background */
        .header-background {
            background-image: url('image6.png'); /* Assurez-vous que le chemin est correct */
            background-size: cover;
            background-position: center;
            background-attachment: fixed; /* Pour un effet parallax léger */
            position: relative;
            z-index: 0;
            padding-top: 64px; /* Pour éviter que le contenu ne soit derrière le header fixe */
        }

        .header-background::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.4); /* Overlay sombre */
            backdrop-filter: blur(2px); /* Léger flou */
            z-index: 1;
        }

        .header-content {
            position: relative;
            z-index: 2; /* S'assurer que le contenu est au-dessus de l'overlay */
        }

        .nav-link {
            padding: 0.75rem 1rem;
            border-bottom: 2px solid transparent;
            transition: all 0.2s ease-in-out;
        }

        .nav-link.active {
            border-color: #3b82f6; /* Couleur de la bordure active (bleu) */
            color: #1f2937; /* Texte plus foncé */
            font-weight: 600;
        }

        /* Styles spécifiques aux inputs dans le formulaire de recherche */
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

        /* Style pour les listes d'autocomplétion */
        .autocomplete-results {
            position: absolute;
            z-index: 100; /* Assurez-vous qu'il est au-dessus de tout */
            width: 100%;
            background-color: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-height: 200px;
            overflow-y: auto;
            margin-top: 4px; /* Petit espace entre l'input et la liste */
        }
        .autocomplete-results li {
            padding: 0.75rem 1rem;
            cursor: pointer;
            transition: background-color 0.2s ease;
            font-size: 0.9rem;
            color: #4b5563;
        }
        .autocomplete-results li:hover {
            background-color: #f3f4f6;
        }

        /* Style pour le message d'info */
        .info-box {
            background-color: #e0f2fe; /* bg-blue-50 */
            border: 1px solid #90cdf4; /* border-blue-200 */
            color: #2b6cb0; /* text-blue-800 */
            padding: 1.5rem;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
            margin-top: 3rem;
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
                    <a href="index.php?action=vols" class="nav-link active">
                        <span class="text-sm">Vols</span>
                    </a>
                    <a href="index.php?action=hotels" class="nav-link">
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
                Trouvez Votre Prochain Vol
            </h1>

            <div class="bg-white p-8 rounded-xl shadow-lg">
                <div class="flex space-x-6 mb-6">
                    <label class="inline-flex items-center text-gray-700">
                        <input type="radio" name="trip_type" value="round_trip" class="form-radio text-blue-600" checked>
                        <span class="ml-2">Aller-retour</span>
                    </label>
                    <label class="inline-flex items-center text-gray-700">
                        <input type="radio" name="trip_type" value="one_way" class="form-radio text-blue-600">
                        <span class="ml-2">Aller Simple</span>
                    </label>
                </div>

                <form action="index.php?action=search-flights" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="search-input-group col-span-1 md:col-span-2 lg:col-span-1">
                        <label for="departure_airport" class="sr-only">Départ</label>
                        <i class="fas fa-plane-departure search-input-icon"></i>
                        <input type="text" id="departure_airport" name="departure" placeholder="Départ (Ville ou Aéroport)" class="search-input">
                        <ul id="departure_airport-results" class="autocomplete-results hidden"></ul>
                    </div>

                    <div class="search-input-group col-span-1 md:col-span-2 lg:col-span-1">
                        <label for="arrival_airport" class="sr-only">Arrivée</label>
                        <i class="fas fa-plane-arrival search-input-icon"></i>
                        <input type="text" id="arrival_airport" name="arrival" placeholder="Arrivée (Ville ou Aéroport)" class="search-input">
                        <ul id="arrival_airport-results" class="autocomplete-results hidden"></ul>
                    </div>
                    
                    <div class="search-input-group col-span-1">
                        <label for="departure_date" class="sr-only">Date Aller</label>
                        <i class="fas fa-calendar-alt search-input-icon"></i>
                        <input type="date" id="departure_date" name="departure_date" placeholder="jj/mm/aaaa" class="search-input">
                    </div>

                    <div class="search-input-group col-span-1" id="return_date_group">
                        <label for="return_date" class="sr-only">Date Retour</label>
                        <i class="fas fa-calendar-alt search-input-icon"></i>
                        <input type="date" id="return_date" name="return_date" placeholder="jj/mm/aaaa" class="search-input">
                    </div>

                    <div class="search-input-group col-span-1">
                        <label for="passengers" class="sr-only">Passagers</label>
                        <i class="fas fa-users search-input-icon"></i>
                        <input type="number" id="passengers" name="passengers" value="1" min="1" class="search-input">
                    </div>

                    <div class="search-input-group col-span-1">
                        <label for="travel_class" class="sr-only">Classe</label>
                        <i class="fas fa-chair search-input-icon"></i>
                        <select id="travel_class" name="travel_class" class="search-input appearance-none bg-white pr-8">
                            <option value="economy">Économique</option>
                            <option value="business">Affaires</option>
                            <option value="first">Première</option>
                        </select>
                        <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-600 pointer-events-none"></i>
                    </div>

                    <div class="col-span-full flex justify-end">
                        <button type="submit" class="w-full md:w-auto bg-blue-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-blue-700 transition duration-200 shadow-md">
                            <i class="fas fa-search mr-2"></i> Rechercher Des Vols
                        </button>
                    </div>
                </form>
            </div>

            <div class="info-box text-center">
                <i class="fas fa-info-circle text-3xl"></i>
                <p class="text-lg">Veuillez remplir le formulaire ci-dessus pour lancer votre recherche de vols.</p>
            </div>
            
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- Logique Aller-retour / Aller Simple ---
            const tripTypeRadios = document.querySelectorAll('input[name="trip_type"]');
            const returnDateGroup = document.getElementById('return_date_group');
            const returnDateInput = document.getElementById('return_date');

            function toggleReturnDate() {
                if (document.querySelector('input[name="trip_type"]:checked').value === 'one_way') {
                    returnDateGroup.style.display = 'none';
                    returnDateInput.removeAttribute('required'); // Rendre non obligatoire
                    returnDateInput.value = ''; // Vider le champ
                } else {
                    returnDateGroup.style.display = 'block';
                    returnDateInput.setAttribute('required', 'required'); // Rendre obligatoire
                }
            }

            tripTypeRadios.forEach(radio => {
                radio.addEventListener('change', toggleReturnDate);
            });

            // Initialiser l'état au chargement de la page
            toggleReturnDate();


            // --- Logique d'Autocomplétion (pour Départ et Arrivée) ---
            const departureInput = document.getElementById('departure_airport');
            const arrivalInput = document.getElementById('arrival_airport');

            function handleAutocomplete(event) {
                const inputElement = event.target;
                const searchTerm = inputElement.value;
                const resultsContainerId = inputElement.id + '-results';
                let resultsContainer = document.getElementById(resultsContainerId);

                if (!resultsContainer) {
                    resultsContainer = document.createElement('ul');
                    resultsContainer.id = resultsContainerId;
                    resultsContainer.className = 'autocomplete-results'; // Utilise la classe CSS définie
                    inputElement.parentNode.appendChild(resultsContainer);
                }
                resultsContainer.innerHTML = ''; 

                if (searchTerm.length < 2) {
                    resultsContainer.style.display = 'none';
                    return;
                }

                // Appel AJAX vers votre route `search-airport`
                fetch(`index.php?action=search-airport&q=${encodeURIComponent(searchTerm)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            console.error("Erreur du serveur :", data.error);
                            return;
                        }
                        
                        if (data.length > 0) {
                            data.forEach(airport => {
                                const listItem = document.createElement('li');
                                listItem.innerHTML = `
                                    <div data-iata="${airport.iata_code}" data-name="${airport.name}" data-city="${airport.city}" class="flex items-center">
                                        <i class="fas fa-plane mr-2 text-gray-500"></i>
                                        <span><strong>${airport.iata_code}</strong> - ${airport.name}, ${airport.city} (${airport.country})</span>
                                    </div>
                                `;
                                
                                listItem.querySelector('div').addEventListener('click', function() {
                                    inputElement.value = this.dataset.name + " (" + this.dataset.iata + ")"; // Affiche Nom (IATA)
                                    // Ou inputElement.value = this.dataset.city + " (" + this.dataset.iata + ")";
                                    resultsContainer.style.display = 'none';
                                });

                                resultsContainer.appendChild(listItem);
                            });
                            resultsContainer.style.display = 'block';
                        } else {
                            resultsContainer.innerHTML = '<li class="p-2 text-gray-500 text-sm">Aucun aéroport trouvé.</li>';
                            resultsContainer.style.display = 'block';
                        }
                    })
                    .catch(error => {
                        console.error('Erreur de réseau ou de parsing:', error);
                    });
            }

            // Attacher l'événement d'entrée avec debounce
            function debounce(func, delay) {
                let timeout;
                return function(...args) {
                    const context = this;
                    clearTimeout(timeout);
                    timeout = setTimeout(() => func.apply(context, args), delay);
                };
            }

            if (departureInput) {
                departureInput.addEventListener('input', debounce(handleAutocomplete, 300));
            }
            if (arrivalInput) {
                arrivalInput.addEventListener('input', debounce(handleAutocomplete, 300));
            }

            // Cacher la liste si l'utilisateur clique ailleurs
            document.addEventListener('click', function(e) {
                if (departureInput && !departureInput.contains(e.target) && !e.target.closest('#departure_airport-results')) {
                    const departureResults = document.getElementById('departure_airport-results');
                    if (departureResults) departureResults.style.display = 'none';
                }
                if (arrivalInput && !arrivalInput.contains(e.target) && !e.target.closest('#arrival_airport-results')) {
                    const arrivalResults = document.getElementById('arrival_airport-results');
                    if (arrivalResults) arrivalResults.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>