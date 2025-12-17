<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$dest = $_GET['destination'] ?? '';
$checkIn = $_GET['check_in_date'] ?? '';
$checkOut = $_GET['check_out_date'] ?? '';
$guests = $_GET['guests'] ?? 2;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hôtels à <?php echo htmlspecialchars($dest); ?> - TravelBooking</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f5f5f5; }
    </style>
</head>

<body class="text-gray-700">


<header class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between">
        <a href="index.php" class="text-2xl font-bold text-gray-800">TravelBooking</a>
        <div class="flex items-center space-x-4">
            <?php if (isset($_SESSION['user'])): ?>
                <a href="index.php?action=aide">Aide</a>
                <a href="index.php?action=profile" class="text-blue-600 font-bold">Profil</a>
                <a href="index.php?action=logout" class="text-red-500">Déconnexion</a>
            <?php else: ?>
                <a href="index.php?action=login" class="text-blue-600">Connexion</a>
            <?php endif; ?>
        </div>
    </div>
</header>


<div class="bg-gray-800 border-b-4 border-blue-500 py-4">
    <div class="max-w-7xl mx-auto px-4">
        <form action="index.php" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-2">
            <input type="hidden" name="action" value="search-hotels">

            <input class="md:col-span-4 p-3 rounded"
                   type="text" name="destination"
                   value="<?php echo htmlspecialchars($dest); ?>"
                   placeholder="Destination">

            <input class="md:col-span-2 p-3 rounded"
                   type="date" name="check_in_date"
                   value="<?php echo htmlspecialchars($checkIn); ?>">

            <input class="md:col-span-2 p-3 rounded"
                   type="date" name="check_out_date"
                   value="<?php echo htmlspecialchars($checkOut); ?>">

            <select name="guests" class="md:col-span-2 p-3 rounded">
                <option value="1" <?php if($guests==1) echo 'selected'; ?>>1 adulte</option>
                <option value="2" <?php if($guests==2) echo 'selected'; ?>>2 adultes</option>
                <option value="3" <?php if($guests==3) echo 'selected'; ?>>3 adultes</option>
            </select>

            <button class="md:col-span-2 bg-blue-600 text-white rounded font-bold">
                Rechercher
            </button>
        </form>
    </div>
</div>


<div class="max-w-7xl mx-auto px-4 py-6">

    <h2 class="text-xl font-bold mb-6">
        <?php echo isset($hotelsFound) ? count($hotelsFound) : 0; ?> hôtels trouvés
        <?php if ($dest) echo " à " . htmlspecialchars($dest); ?>
    </h2>

    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        <?php if (isset($hotelsFound) && count($hotelsFound) > 0): ?>
            <?php foreach ($hotelsFound as $index => $hotel): ?>

                <div class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden">

                    
                    <div class="relative h-48">
                        <?php if ($index === 0): ?>
                            <span class="absolute top-2 left-2 bg-green-600 text-white text-xs px-2 py-1 rounded z-10">
                                Meilleur résultat
                            </span>
                        <?php endif; ?>

                        <i class="far fa-heart absolute top-2 right-2 text-white text-xl z-10 drop-shadow cursor-pointer"></i>

                        <?php 
                            $imgUrl = !empty($hotel['main_image_url']) ? $hotel['main_image_url'] : 'image9.png'; 
                        ?>
                        <img src="<?php echo htmlspecialchars($imgUrl); ?>"
                             class="w-full h-full object-cover"
                             alt="<?php echo htmlspecialchars($hotel['name']); ?>">
                    </div>

                    
                    <div class="p-4">
                        <h3 class="text-lg font-bold text-blue-800">
                            <?php echo htmlspecialchars($hotel['name']); ?>
                        </h3>

                        <p class="text-sm text-gray-500 mt-1">
                            <i class="fas fa-map-marker-alt mr-1"></i>
                            <?php echo htmlspecialchars($hotel['city']); ?>
                        </p>

                        <div class="mt-4 flex items-center justify-between">
                            <div>
                                <p class="text-2xl font-bold text-gray-800">
                                    <?php echo htmlspecialchars($hotel['price_per_night']); ?> €
                                </p>
                                <p class="text-xs text-gray-400">Taxes incluses</p>
                            </div>
                        </div>

                        <a href="index.php?action=book-hotel&hotel_id=<?php echo $hotel['id']; ?>&check_in=<?php echo urlencode($checkIn); ?>&check_out=<?php echo urlencode($checkOut); ?>&guests=<?php echo $guests; ?>"
                    class="mt-4 block text-center bg-blue-600 text-white font-bold py-2 rounded
                          hover:bg-red-500 hover:text-blue-600 transition-colors ">
                   Voir l’offre
                     </a>

                    </div>

                </div>

            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-span-full text-center bg-white p-10 rounded shadow">
                <i class="fas fa-hotel text-4xl text-gray-300 mb-4"></i>
                <p class="text-gray-600">Aucun hôtel trouvé</p>
            </div>
        <?php endif; ?>

    </div>
</div>

</body>
</html>
