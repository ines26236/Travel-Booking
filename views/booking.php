<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$vols_trouves = $vols_trouves ?? [];
$criteria = $criteria ?? [];

$trip_type_selected = $criteria['trip_type'] ?? 'round_trip';
$departure_value = htmlspecialchars($criteria['departure'] ?? '');
$arrival_value = htmlspecialchars($criteria['arrival'] ?? '');
$departure_date_value = htmlspecialchars($criteria['departure_date'] ?? '');
$return_date_value = htmlspecialchars($criteria['return_date'] ?? '');
$passengers_value = htmlspecialchars($criteria['passengers'] ?? 1);
$travel_class_selected = $criteria['travel_class'] ?? 'economy';
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
            background-color: #f0f2f5;
        }

        .header-background {
            background-image: url('image6.png'); 
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
            padding-left: 2.5rem; 
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

        .autocomplete-results {
            position: absolute;
            z-index: 100;
            width: 100%;
            background-color: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-height: 200px;
            overflow-y: auto;
            margin-top: 4px;
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

        .info-box {
            background-color: #e0f2fe; 
            border: 1px solid #90cdf4; 
            color: #2b6cb0; 
            padding: 1.5rem;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
            margin-top: 3rem;
        }
        .info-box.error {
            background-color: #fee2e2; 
            border-color: #fca5a5; 
            color: #991b1b; 
        }

        #loader-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.8);
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3b82f6;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin-bottom: 1rem;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .hidden { display: none !important; }
    </style>
</head>
<body>
    <div id="loader-overlay" class="hidden">
        <div class="spinner"></div>
        <p class="text-blue-600 font-semibold text-lg">Recherche des meilleurs vols...</p>
    </div>

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

            <?php include 'flight_search_form.php'; ?>
            
            <?php if (!empty($vols_trouves) || (isset($criteria) && count($criteria) > 0)): ?>
                
                <div class="mt-12 w-full">
                    <h2 class="text-3xl font-bold text-white mb-6">
                        <?php echo empty($vols_trouves) ? "Aucun vol trouvé." : "Vols trouvés pour " . $departure_value . " vers " . $arrival_value; ?>
                    </h2>
                    
                    <?php if (!empty($vols_trouves)): ?>
                        <div class="space-y-4">
                            <?php foreach ($vols_trouves as $vol): ?>
                                <?php 
                                    $priceKeyMap = [
                                        'economy' => 'price_economy', 
                                        'business' => 'price_business',
                                        'first' => 'price_first'
                                    ];
                                    
                                    $priceKey = $priceKeyMap[$travel_class_selected] ?? 'price_economy';
                                    
                                    $pricePerPerson = $vol[$priceKey] ?? $vol['price_economy'] ?? 0;
                                    $finalPrice = $pricePerPerson * $passengers_value;

                                    $departureDisplay = $vol['departure_city'] . ' (' . $vol['departure_iata'] . ')';
                                    $arrivalDisplay = $vol['arrival_city'] . ' (' . $vol['arrival_iata'] . ')';
                                ?>
                                <div class="bg-white p-6 rounded-xl shadow-lg flex justify-between items-center transition hover:shadow-xl">
                                    
                                    <div class="flex items-center space-x-6 w-1/4">
                                        <i class="fas fa-plane text-2xl text-blue-600"></i>
                                        <div>
                                            <p class="text-xl font-semibold text-gray-800"><?php echo htmlspecialchars($vol['airline']); ?></p>
                                            <p class="text-sm font-normal text-gray-500">Vol : <?php echo htmlspecialchars($vol['flight_number']); ?></p>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-center w-2/4">
                                        <div class="text-center">
                                            <p class="text-2xl font-bold text-gray-900"><?php echo htmlspecialchars($vol['departure_time']); ?></p>
                                            <p class="text-gray-600"><?php echo htmlspecialchars($departureDisplay); ?></p>
                                        </div>
                                        
                                        <div class="mx-6 text-center">
                                            <i class="fas fa-long-arrow-alt-right text-gray-400"></i>
                                            <p class="text-sm text-gray-500 mt-1"><?php echo htmlspecialchars($vol['duration']); ?></p>
                                        </div>
                                        
                                        <div class="text-center">
                                            <p class="text-2xl font-bold text-gray-900">
                                                <?php echo "--:--"; ?>
                                            </p>
                                            <p class="text-gray-600"><?php echo htmlspecialchars($arrivalDisplay); ?></p>
                                        </div>
                                    </div>

                                    <div class="text-right w-1/4">
                                        <p class="text-sm text-gray-500">
                                            Prix total (<?php echo $passengers_value; ?> pers.)
                                        </p>
                                        <p class="text-3xl font-extrabold text-green-600 mb-2">
                                            <?php echo number_format($finalPrice, 0, ',', ' '); ?> €
                                        </p>
                                        <a href="index.php?action=book-flight&flight_id=<?php echo $vol['id']; ?>" class="bg-blue-600 text-white py-2 px-4 rounded-lg font-medium hover:bg-blue-700 transition duration-200">
                                            Réserver
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                    <?php else: ?>
                        <div class="info-box error mt-6">
                            <i class="fas fa-exclamation-triangle text-3xl"></i>
                            <p class="text-lg">Désolé, aucun vol trouvé pour votre sélection. Veuillez essayer d'autres critères de recherche.</p>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <div class="info-box text-center" id="initial_info_box" style="<?php echo (isset($criteria) && count($criteria) > 0) ? 'display: none;' : 'display: flex;'; ?>">
                <i class="fas fa-info-circle text-3xl"></i>
                <p class="text-lg">Veuillez remplir le formulaire ci-dessus pour lancer votre recherche de vols.</p>
            </div>
            
        </div>
    </main>
</body>
</html>