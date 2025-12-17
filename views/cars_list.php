<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'database/database.php';

$db = Database::getInstance()->getConnection();

// Get filter parameters
$category = $_GET['category'] ?? '';
$transmission = $_GET['transmission'] ?? '';

// Build query
$sql = "SELECT * FROM cars WHERE available = 1";
$params = [];

if ($category) {
    $sql .= " AND category = :category";
    $params['category'] = $category;
}

if ($transmission) {
    $sql .= " AND transmission = :transmission";
    $params['transmission'] = $transmission;
}

$sql .= " ORDER BY price_per_day ASC";

$stmt = $db->prepare($sql);
$stmt->execute($params);
$cars = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Location de Voiture - TravelBooking</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
            min-height: 100vh;
            background-image: url('assets/images/image6.png');
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
        
        .car-card {
            transition: all 0.3s ease;
            background: white;
        }
        
        .car-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        .main-content {
            position: relative;
            z-index: 10;
        }

        .page-title {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 1rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
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
                <div class="flex items-center space-x-4">
                    <?php if (isset($_SESSION['user'])): ?>
                        <a href="index.php?action=profile" class="text-blue-600 font-medium">Profil</a>
                        <a href="index.php?action=logout" class="text-red-500 font-medium">Déconnexion</a>
                    <?php else: ?>
                        <a href="index.php?action=login" class="text-blue-600 font-medium">Connexion</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <main class="main-content max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Title -->
        <div class="page-title p-8 mb-8">
            <div class="flex items-center gap-4">
                <div class="bg-blue-100 rounded-full p-4">
                    <i class="fas fa-car text-3xl text-blue-600"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Location de Voiture</h1>
                    <p class="text-gray-600">Trouvez la voiture parfaite pour votre voyage</p>
                </div>
            </div>
        </div>

        <!-- Results count -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-white">
                <?= count($cars) ?> voiture<?= count($cars) > 1 ? 's' : '' ?> disponible<?= count($cars) > 1 ? 's' : '' ?>
            </h2>
        </div>

        <!-- Cars Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($cars as $car): ?>
                <div class="car-card bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="relative h-48">
                        <img src="<?= htmlspecialchars($car['image_url']) ?>" 
                             alt="<?= htmlspecialchars($car['brand'] . ' ' . $car['model']) ?>" 
                             class="w-full h-full object-cover">
                    </div>

                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">
                            <?= htmlspecialchars($car['brand'] . ' ' . $car['model']) ?>
                        </h3>

                        <div class="flex items-center gap-4 mb-4 text-sm text-gray-600">
                            <div class="flex items-center">
                                <i class="fas fa-users text-blue-500 mr-2"></i>
                                <?= $car['seats'] ?> places
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-gas-pump text-blue-500 mr-2"></i>
                                <?= htmlspecialchars($car['fuel_type']) ?>
                            </div>
                        </div>

                        <?php if ($car['features']): ?>
                            <div class="mb-4">
                                <p class="text-xs text-gray-500">
                                    <i class="fas fa-check-circle text-green-500 mr-1"></i>
                                    <?= htmlspecialchars($car['features']) ?>
                                </p>
                            </div>
                        <?php endif; ?>

                        <div class="border-t pt-4 flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">À partir de</p>
                                <p class="text-2xl font-bold text-blue-600">
                                    <?= number_format($car['price_per_day'], 0) ?> €
                                    <span class="text-sm text-gray-500 font-normal">/jour</span>
                                </p>
                            </div>
                            <a href="index.php?action=book-car&car_id=<?= $car['id'] ?>" 
                               class="bg-blue-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-blue-700 transition">
                                Réserver
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if (empty($cars)): ?>
            <div class="text-center py-12 bg-white rounded-lg">
                <i class="fas fa-car text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Aucune voiture trouvée</h3>
                <p class="text-gray-600">Essayez de modifier vos critères de recherche.</p>
            </div>
        <?php endif; ?>
    </main>

    <footer class="bg-gray-900 text-gray-400 py-8 text-center text-sm mt-12">
        <p>© 2025 TravelBooking. Tous droits réservés.</p>
    </footer>
</body>
</html>
