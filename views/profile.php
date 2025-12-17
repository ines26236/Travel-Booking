<?php
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['user'])) {
    header('Location: index.php?action=login');
    exit;
}

$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil - TravelBooking</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
            min-height: 100vh;
            background-image: url('image6.png');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            position: relative;
        }
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(3px);
            z-index: -1;
        }
        .welcome-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            border-top: 4px solid #2563eb;
            position: relative;
            z-index: 10;
        }
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        main {
            position: relative;
            z-index: 10;
        }
    </style>
</head>
<body class="bg-gray-50">

    <header class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex-shrink-0">
                    <a href="index.php?action=home" class="text-2xl font-bold text-gray-800">TravelBooking</a>
                </div>
                <nav class="hidden sm:ml-6 sm:flex sm:space-x-8 h-full">
                    <a href="index.php?action=vols" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700 transition duration-150">Vols</a>
                    <a href="index.php?action=hotels" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700 transition duration-150">Hôtels</a>
                    <a href="index.php?action=profile" class="inline-flex items-center px-1 pt-1 border-b-2 border-blue-500 text-sm font-medium text-gray-900 transition duration-150">Mon Profil</a>
                </nav>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-700 font-medium"><?= htmlspecialchars($user['name']) ?></span>
                    <a href="index.php?action=logout" class="bg-red-500 text-white px-4 py-2 rounded-lg font-medium hover:bg-red-600 transition duration-200">
                        <i class="fas fa-sign-out-alt mr-2"></i>Déconnexion
                    </a>
                </div>
            </div>
        </div>
    </header>

    <?php if (isset($_SESSION['flash_message'])): ?>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <p class="text-green-600 text-center font-medium">
                    <i class="fas fa-check-circle mr-2"></i>
                    <?= htmlspecialchars($_SESSION['flash_message']); unset($_SESSION['flash_message']); ?>
                </p>
            </div>
        </div>
    <?php endif; ?>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <div class="welcome-card p-8 mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-6">
                    <div class="bg-blue-50 rounded-full p-6 text-blue-600">
                        <i class="fas fa-user text-5xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold mb-2 text-gray-800">Bienvenue, <?= htmlspecialchars($user['name']) ?> !</h1>
                        <p class="text-gray-600 flex items-center">
                            <i class="fas fa-envelope mr-2 text-blue-500"></i>
                            <?= htmlspecialchars($user['email']) ?>
                        </p>
                        <p class="text-gray-500 text-sm mt-1">
                            <i class="fas fa-calendar mr-2 text-blue-500"></i>
                            Membre depuis le <?= date('d/m/Y', strtotime($user['created_at'])) ?>
                        </p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="bg-blue-50 rounded-lg p-4 border border-blue-100">
                        <p class="text-sm text-gray-600 mb-1">Total des réservations</p>
                        <p class="text-4xl font-bold text-blue-600"><?= $totalBookings ?? 0 ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-plane-departure text-blue-600 mr-2"></i>
                    Mes Réservations
                </h2>
                <a href="index.php?action=vols" class="bg-blue-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-700 transition duration-200">
                    <i class="fas fa-plus mr-2"></i>Nouvelle réservation
                </a>
            </div>

            <?php if (empty($bookings)): ?>
                <div class="text-center py-12">
                    <div class="inline-block p-6 bg-gray-100 rounded-full mb-4">
                        <i class="fas fa-plane text-5xl text-gray-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Aucune réservation</h3>
                    <p class="text-gray-600 mb-6">Vous n'avez pas encore effectué de réservation.</p>
                    <a href="index.php?action=vols" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700 transition duration-200">
                        Rechercher un vol
                    </a>
                </div>
            <?php else: ?>
                <div class="space-y-6">
                    <?php foreach ($bookings as $booking): ?>
                        <div class="border border-gray-200 rounded-xl p-6 card-hover transition duration-200">
                            <div class="flex items-center justify-between mb-4 pb-4 border-b border-gray-200">
                                <div>
                                    <h3 class="text-lg font-bold text-gray-800">
                                        <?= htmlspecialchars($booking['departure_city']) ?> 
                                        <i class="fas fa-arrow-right text-blue-600 mx-2"></i> 
                                        <?= htmlspecialchars($booking['arrival_city']) ?>
                                    </h3>
                                    <p class="text-sm text-gray-600 mt-1">
                                        <i class="fas fa-plane mr-2"></i>
                                        <?= htmlspecialchars($booking['airline']) ?> - Vol <?= htmlspecialchars($booking['flight_number']) ?>
                                    </p>
                                </div>
                                <div class="text-right">
                                    <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        <?= ucfirst($booking['status']) ?>
                                    </span>
                                    <p class="text-sm text-gray-600 mt-2">
                                        Réservé le <?= date('d/m/Y', strtotime($booking['created_at'])) ?>
                                    </p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <p class="text-xs text-gray-600 mb-1">Départ</p>
                                    <p class="text-lg font-bold text-gray-800">
                                        <?= date('H:i', strtotime($booking['departure_time'])) ?>
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        <?= htmlspecialchars($booking['departure_iata']) ?> - <?= htmlspecialchars($booking['departure_city']) ?>
                                    </p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4 text-center">
                                    <p class="text-xs text-gray-600 mb-1">Durée</p>
                                    <p class="text-lg font-bold text-gray-800">
                                        <?= htmlspecialchars($booking['duration']) ?>
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        Classe <?= ucfirst($booking['travel_class']) ?>
                                    </p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4 text-right">
                                    <p class="text-xs text-gray-600 mb-1">Arrivée</p>
                                    <p class="text-lg font-bold text-gray-800">
                                        <?= date('H:i', strtotime($booking['arrival_time'])) ?>
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        <?= htmlspecialchars($booking['arrival_iata']) ?> - <?= htmlspecialchars($booking['arrival_city']) ?>
                                    </p>
                                </div>
                            </div>

                            <div class="bg-blue-50 rounded-lg p-4 mb-4">
                                <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                                    <i class="fas fa-users text-blue-600 mr-2"></i>
                                    Passagers (<?= count($booking['passengers']) ?>)
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <?php foreach ($booking['passengers'] as $passenger): ?>
                                        <div class="bg-white rounded-lg p-3">
                                            <p class="font-medium text-gray-800">
                                                <?= htmlspecialchars($passenger['title']) ?>. 
                                                <?= htmlspecialchars($passenger['first_name']) ?> 
                                                <?= htmlspecialchars($passenger['last_name']) ?>
                                            </p>
                                            <p class="text-sm text-gray-600">
                                                <i class="fas fa-birthday-cake mr-1"></i>
                                                <?= date('d/m/Y', strtotime($passenger['date_of_birth'])) ?>
                                            </p>
                                            <p class="text-sm text-gray-600">
                                                <i class="fas fa-envelope mr-1"></i>
                                                <?= htmlspecialchars($passenger['email']) ?>
                                            </p>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-600">
                                    <i class="fas fa-users mr-1"></i>
                                    <?= $booking['number_of_passengers'] ?> passager(s)
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-600">Prix total</p>
                                    <p class="text-2xl font-bold text-green-600">
                                        <?= number_format($booking['total_price'], 2, ',', ' ') ?> €
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Hotel Bookings Section -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mt-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-hotel text-blue-600 mr-2"></i>
                    Mes Réservations d'Hôtels
                </h2>
            </div>

            <?php if (empty($hotelBookings)): ?>
                <div class="text-center py-12">
                    <div class="inline-block p-6 bg-gray-100 rounded-full mb-4">
                        <i class="fas fa-hotel text-5xl text-gray-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Aucune réservation d'hôtel</h3>
                    <p class="text-gray-600 mb-6">Vous n'avez pas encore réservé d'hôtel.</p>
                    <a href="index.php?action=hotels" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700 transition duration-200">
                        Rechercher un hôtel
                    </a>
                </div>
            <?php else: ?>
                <div class="space-y-6">
                    <?php foreach ($hotelBookings as $hotelBooking): ?>
                        <div class="border border-gray-200 rounded-xl p-6 card-hover transition duration-200">
                            <div class="flex items-center justify-between mb-4 pb-4 border-b border-gray-200">
                                <div>
                                    <h3 class="text-lg font-bold text-gray-800">
                                        <?= htmlspecialchars($hotelBooking['hotel_name']) ?>
                                    </h3>
                                    <p class="text-sm text-gray-600 mt-1">
                                        <i class="fas fa-map-marker-alt mr-2"></i>
                                        <?= htmlspecialchars($hotelBooking['city']) ?>
                                    </p>
                                </div>
                                <div class="text-right">
                                    <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        <?= ucfirst($hotelBooking['status']) ?>
                                    </span>
                                    <p class="text-sm text-gray-600 mt-2">
                                        Réservé le <?= date('d/m/Y', strtotime($hotelBooking['created_at'])) ?>
                                    </p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <p class="text-xs text-gray-600 mb-1">Arrivée</p>
                                    <p class="text-lg font-bold text-gray-800">
                                        <?= date('d/m/Y', strtotime($hotelBooking['check_in'])) ?>
                                    </p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4 text-center">
                                    <p class="text-xs text-gray-600 mb-1">Durée</p>
                                    <p class="text-lg font-bold text-gray-800">
                                        <?php
                                            $date1 = new DateTime($hotelBooking['check_in']);
                                            $date2 = new DateTime($hotelBooking['check_out']);
                                            $nights = $date1->diff($date2)->days;
                                            echo $nights . ' nuit' . ($nights > 1 ? 's' : '');
                                        ?>
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        <?= $hotelBooking['guests'] ?> personne<?= $hotelBooking['guests'] > 1 ? 's' : '' ?>
                                    </p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4 text-right">
                                    <p class="text-xs text-gray-600 mb-1">Départ</p>
                                    <p class="text-lg font-bold text-gray-800">
                                        <?= date('d/m/Y', strtotime($hotelBooking['check_out'])) ?>
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-600">
                                    <i class="fas fa-users mr-1"></i>
                                    <?= $hotelBooking['guests'] ?> personne(s)
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-600">Prix total</p>
                                    <p class="text-2xl font-bold text-green-600">
                                        <?= number_format($hotelBooking['total_price'], 2, ',', ' ') ?> €
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Car Bookings Section -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mt-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-car text-blue-600 mr-2"></i>
                    Mes Réservations de Voitures
                </h2>
            </div>

            <?php if (empty($carBookings)): ?>
                <div class="text-center py-12">
                    <div class="inline-block p-6 bg-gray-100 rounded-full mb-4">
                        <i class="fas fa-car text-5xl text-gray-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Aucune réservation de voiture</h3>
                    <p class="text-gray-600 mb-6">Vous n'avez pas encore loué de voiture.</p>
                    <a href="index.php?action=cars" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700 transition duration-200">
                        Louer une voiture
                    </a>
                </div>
            <?php else: ?>
                <div class="space-y-6">
                    <?php foreach ($carBookings as $carBooking): ?>
                        <div class="border border-gray-200 rounded-xl p-6 card-hover transition duration-200">
                            <div class="flex items-center justify-between mb-4 pb-4 border-b border-gray-200">
                                <div class="flex items-center gap-4">
                                    <div class="w-20 h-14 rounded-lg overflow-hidden">
                                        <img src="<?= htmlspecialchars($carBooking['image_url'] ?? '') ?>" 
                                             alt="<?= htmlspecialchars($carBooking['brand'] . ' ' . $carBooking['model']) ?>"
                                             class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-800">
                                            <?= htmlspecialchars($carBooking['brand'] . ' ' . $carBooking['model']) ?>
                                        </h3>
                                        <p class="text-sm text-gray-600 mt-1">
                                            <i class="fas fa-map-marker-alt mr-2"></i>
                                            <?= htmlspecialchars($carBooking['pickup_location']) ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        <?= ucfirst($carBooking['status']) ?>
                                    </span>
                                    <p class="text-sm text-gray-600 mt-2">
                                        Réservé le <?= date('d/m/Y', strtotime($carBooking['created_at'])) ?>
                                    </p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <p class="text-xs text-gray-600 mb-1">Prise en charge</p>
                                    <p class="text-lg font-bold text-gray-800">
                                        <?= date('d/m/Y', strtotime($carBooking['pickup_date'])) ?>
                                    </p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4 text-center">
                                    <p class="text-xs text-gray-600 mb-1">Durée</p>
                                    <p class="text-lg font-bold text-gray-800">
                                        <?php
                                            $date1 = new DateTime($carBooking['pickup_date']);
                                            $date2 = new DateTime($carBooking['return_date']);
                                            $days = $date1->diff($date2)->days;
                                            echo $days . ' jour' . ($days > 1 ? 's' : '');
                                        ?>
                                    </p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4 text-right">
                                    <p class="text-xs text-gray-600 mb-1">Retour</p>
                                    <p class="text-lg font-bold text-gray-800">
                                        <?= date('d/m/Y', strtotime($carBooking['return_date'])) ?>
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-600">
                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                    Retour: <?= htmlspecialchars($carBooking['return_location'] ?: $carBooking['pickup_location']) ?>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-600">Prix total</p>
                                    <p class="text-2xl font-bold text-green-600">
                                        <?= number_format($carBooking['total_price'], 2, ',', ' ') ?> €
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

    </main>

    <footer class="bg-gray-900 text-gray-400 py-8 text-center text-sm mt-12">
        <p>© 2025 TravelBooking. Tous droits réservés.</p>
    </footer>

</body>
</html>
