<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$departure_city = $criteria['departure'] ?? 'Départ';
$arrival_city = $criteria['arrival'] ?? 'Arrivée';
$date_depart = $criteria['departure_date'] ?? date('Y-m-d');
$passengers = $criteria['passengers'] ?? 1;

function formatDuration($sqlDuration) {
    if (strpos($sqlDuration, 'h') !== false) {
        return $sqlDuration; 
    }
    return str_replace([':', '00'], ['h ', ''], substr($sqlDuration, 0, 5)) . 'min'; 
}

function calculateArrival($dep, $dur) {
    $timestamp = strtotime($dep);
    $hours = 0;
    $minutes = 0;
    
    if (strpos($dur, 'h') !== false) {
        sscanf($dur, "%dh %dmin", $hours, $minutes);
    } else {
        $parts = explode(':', $dur);
        $hours = $parts[0] ?? 0;
        $minutes = $parts[1] ?? 0;
    }
    
    return date('H:i', $timestamp + ($hours * 3600) + ($minutes * 60));
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats de recherche - TravelBooking</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .header-background {
            background-image: url('assets/images/image6.png'); 
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
        }
        .header-background::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.05); 
            z-index: -1;
            pointer-events: none;
        }
    </style>
</head>
<body class="header-background">

    <header class="bg-white/95 backdrop-blur-sm shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex-shrink-0">
                    <a href="index.php?action=home" class="text-2xl font-bold text-gray-800">TravelBooking</a>
                </div>

                <nav class="hidden sm:ml-6 sm:flex sm:space-x-8 h-full">
                    <a href="index.php?action=vols" class="inline-flex items-center px-1 pt-1 border-b-2 border-blue-500 text-sm font-medium text-gray-900 transition duration-150">Vols</a>
                    <a href="index.php?action=hotels" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700 transition duration-150">Hôtels</a>
                    <a href="#" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700 transition duration-150">Vol + Hôtel</a>
                    <a href="#" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700 transition duration-150">Location</a>
                    <a href="index.php?action=activities" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700 transition duration-150">Activités</a>
                </nav>

                <div class="hidden sm:ml-6 sm:flex sm:items-center space-x-4 h-full">
                    <button class="flex items-center space-x-1 text-sm font-bold bg-yellow-500 text-white px-3 py-1 rounded-full hover:bg-yellow-600 transition">
                        <i class="fas fa-crown"></i>
                        <span>Prime</span>
                    </button>

                    <?php if(isset($_SESSION['user'])): ?>
                         <a href="index.php?action=profile" class="text-gray-500 hover:text-gray-700 font-medium"><?= htmlspecialchars($_SESSION['user']['name']) ?></a>
                    <?php else: ?>
                        <a href="index.php?action=login" class="text-gray-500 hover:text-gray-700 transition duration-150 flex items-center space-x-1 text-sm font-medium">
                            <i class="far fa-user"></i>
                            <span>Connexion</span>
                        </a>
                    <?php endif; ?>
                    
                    <a href="index.php?action=aide" class="text-gray-500 hover:text-gray-700 transition duration-150 flex items-center space-x-1 text-sm font-medium">
                        <i class="far fa-question-circle"></i>
                        <span>Aide</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <div class="bg-white border-b border-gray-200 py-4 shadow-sm">
         <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center space-x-3 bg-gray-50 border border-gray-300 rounded-full px-5 py-2">
                <span class="font-bold text-gray-800"><?= htmlspecialchars($departure_city) ?></span>
                <i class="fas fa-arrow-right text-gray-400 text-sm"></i>
                <span class="font-bold text-gray-800"><?= htmlspecialchars($arrival_city) ?></span>
            </div>
            
            <div class="flex items-center space-x-6 text-sm font-medium text-gray-600">
                <div class="flex items-center space-x-2">
                    <i class="far fa-calendar text-blue-500"></i>
                    <span><?= date('D. d/m/Y', strtotime($date_depart)) ?></span>
                </div>
                <div class="flex items-center space-x-2">
                    <i class="far fa-user text-blue-500"></i>
                    <span><?= $passengers ?> Voyageur(s)</span>
                </div>
            </div>

            <a href="index.php?action=vols" class="bg-blue-600 text-white px-6 py-2 rounded-md font-semibold shadow hover:bg-blue-700 transition flex items-center gap-2">
                <i class="fas fa-search"></i> Modifier
            </a>
         </div>
    </div>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex flex-col md:flex-row gap-8">
        
        <aside class="w-full md:w-1/4 space-y-6">
            


            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <div class="flex justify-between items-center mb-4 border-b pb-2">
                    <h3 class="font-bold text-gray-800">Escales</h3>
                </div>
                <div class="space-y-3">
                    <label class="flex items-center space-x-3 cursor-pointer group">
                        <input type="checkbox" checked class="form-checkbox text-blue-600 rounded focus:ring-blue-500">
                        <span class="text-gray-700 group-hover:text-blue-600 transition">Peu m'importe</span>
                    </label>
                    <label class="flex items-center space-x-3 cursor-pointer group">
                         <input type="checkbox" class="form-checkbox text-blue-600 rounded focus:ring-blue-500">
                         <span class="text-gray-700 group-hover:text-blue-600 transition">Direct uniquement</span>
                    </label>
                </div>
            </div>
            
        </aside>

        <section class="w-full md:w-3/4 space-y-6">
            
            <?php if (!empty($vols_trouves)): ?>
                <div class="flex justify-between items-center">
                    <div class="text-sm text-gray-600">
                        <strong><?= count($vols_trouves) ?> résultats</strong> trouvés
                    </div>
                     <div class="text-sm text-gray-500">
                        Trier par: <strong>Prix croissant</strong>
                    </div>
                </div>
            <?php endif; ?>



            <?php if (empty($vols_trouves)): ?>
                <div class="bg-white p-12 text-center rounded-lg shadow-sm border border-dashed border-gray-300">
                    <div class="inline-block p-4 rounded-full bg-blue-50 mb-4">
                        <i class="fas fa-plane-slash text-4xl text-blue-400"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800">Aucun vol trouvé</h3>
                    <p class="text-gray-500 mt-2">Nous n'avons trouvé aucun résultat pour votre recherche.</p>
                    <a href="index.php?action=vols" class="inline-block mt-6 text-blue-600 font-semibold hover:underline">Modifier ma recherche</a>
                </div>
            <?php else: ?>
                <?php foreach ($vols_trouves as $vol): ?>
                    <?php 
                        $basePrice = $vol['price_economy'] ?? 100;
                        $primePrice = floor($basePrice * 0.8); 
                        $duration = $vol['duration']; 
                        $depTime = date('H:i', strtotime($vol['departure_time']));
                        $arrTime = calculateArrival($vol['departure_time'], $duration);
                        $airline = $vol['airline'];
                        $isDirect = true; 
                        
                        $iconClass = 'fa-plane'; 
                        if (stripos($airline, 'Air France') !== false) $logoColor = 'text-blue-700';
                        elseif (stripos($airline, 'Algerie') !== false) $logoColor = 'text-green-600';
                        else $logoColor = 'text-gray-700';
                    ?>
                    
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition duration-200 group">
                        <div class="flex flex-col md:flex-row">
                            
                            <div class="flex-1 p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center gap-2">
                                         <span class="px-2 py-1 bg-gray-100 text-xs font-bold text-gray-600 rounded uppercase"><?= htmlspecialchars($airline) ?></span>
                                    </div>
                                    <span class="text-xs text-green-600 font-semibold flex items-center gap-1">
                                        <i class="fas fa- suitcase"></i> Bagage inclus
                                    </span>
                                </div>

                                <div class="flex items-center">
                                    <div class="w-16 text-center">
                                        <div class="text-xl font-bold text-gray-900"><?= $depTime ?></div>
                                        <div class="text-xs text-gray-500"><?= $vol['departure_iata'] ?></div>
                                    </div>
                                    
                                    <div class="flex-1 px-6 flex flex-col items-center">
                                        <div class="text-xs text-gray-500 mb-1"><?= formatDuration($duration) ?></div>
                                        <div class="w-full h-px bg-gray-300 relative flex items-center justify-center">
                                            <div class="bg-white px-2">
                                                <i class="fas fa-plane text-gray-400 text-xs transform rotate-90"></i>
                                            </div>
                                        </div>
                                        <div class="text-xs text-blue-600 font-medium mt-1">Direct</div>
                                    </div>

                                    <div class="w-16 text-center">
                                        <div class="text-xl font-bold text-gray-900"><?= $arrTime ?></div>
                                        <div class="text-xs text-gray-500"><?= $vol['arrival_iata'] ?></div>
                                    </div>
                                </div>
                            </div>

                            <div class="w-full md:w-64 border-t md:border-t-0 md:border-l border-gray-200 bg-gray-50 p-4 flex flex-col justify-center space-y-3">
                                
                                <div class="flex justify-between items-center group-hover:opacity-75 transition">
                                    <span class="text-sm text-gray-600">Standard</span>
                                    <span class="text-lg font-bold text-gray-900"><?= number_format($basePrice, 2) ?> €</span>
                                </div>

                                <div class="bg-white border border-blue-200 rounded p-3 shadow-sm relative overflow-hidden">
                                    <div class="absolute top-0 right-0 bg-yellow-400 text-white text-[10px] font-bold px-2 py-0.5 rounded-bl">PRIME</div>
                                    <div class="text-xs text-gray-500 line-through"><?= number_format($basePrice, 2) ?> €</div>
                                    <div class="flex justify-between items-end">
                                        <div>
                                            <span class="text-2xl font-bold text-blue-600"><?= number_format($primePrice, 2) ?> €</span>
                                        </div>
                                    </div>
                                    <div class="text-[10px] text-gray-500 mt-1">Économisez <?= number_format($basePrice - $primePrice, 0) ?> €</div>
                                </div>

                                <a href="index.php?action=book-flight&flight_id=<?= $vol['id'] ?>" class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 rounded transition shadow-sm">
                                    Voir l'offre <i class="fas fa-chevron-right ml-1 text-xs"></i>
                                </a>
                            </div>

                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

        </section>
    </main>

    <footer class="bg-gray-900 text-gray-400 py-8 text-center text-sm">
        <p>© 2025 TravelBooking. Tous droits réservés.</p>
    </footer>

</body>
</html>
