<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    header('Location: index.php?action=login');
    exit;
}

$car = $car ?? null;
$pickupDate = $_GET['pickup_date'] ?? date('Y-m-d');
$returnDate = $_GET['return_date'] ?? date('Y-m-d', strtotime('+1 day'));

if (!$car) {
    header('Location: index.php?action=cars');
    exit;
}

// Calculate days from pickup and return dates
$days = 1;
if ($pickupDate && $returnDate) {
    $pickupDateTime = new DateTime($pickupDate);
    $returnDateTime = new DateTime($returnDate);
    $days = $pickupDateTime->diff($returnDateTime)->days;
    if ($days < 1) $days = 1;
}

$totalPrice = $car['price_per_day'] * $days;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation de Voiture - TravelBooking</title>
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

        .card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            position: relative;
            z-index: 10;
        }

        .input-field {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 0.5rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .input-field:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .btn-primary {
            background-color: #2563eb;
            color: white;
            padding: 1rem 2rem;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            width: 100%;
        }

        .btn-primary:hover {
            background-color: #1d4ed8;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.4);
        }

        .car-summary {
            background: white;
            color: #1f2937;
            padding: 2rem;
            border-radius: 1rem;
            margin-bottom: 2rem;
            position: relative;
            z-index: 10;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            border-top: 4px solid #2563eb;
        }

        .price-badge {
            background: #eff6ff;
            padding: 1rem 1.5rem;
            border-radius: 0.75rem;
            border: 1px solid #bfdbfe;
            color: #2563eb;
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
                    <a href="index.php?action=cars" class="text-gray-600 hover:text-blue-600 transition">
                        <i class="fas fa-arrow-left mr-2"></i>Retour aux voitures
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-4 py-8">
        <div class="car-summary">
            <div class="flex justify-between items-start mb-4">
                <div class="flex items-center gap-4">
                    <div class="w-32 h-24 rounded-lg overflow-hidden">
                        <img src="<?= htmlspecialchars($car['image_url']) ?>" 
                             alt="<?= htmlspecialchars($car['brand'] . ' ' . $car['model']) ?>"
                             class="w-full h-full object-cover">
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold mb-2 text-gray-800">
                            <i class="fas fa-car mr-2 text-blue-600"></i>
                            Réservation de Voiture
                        </h1>
                        <p class="text-lg text-gray-600">
                            <?= htmlspecialchars($car['brand'] . ' ' . $car['model']) ?>
                        </p>
                        <p class="text-sm text-gray-500 mt-1">
                            <i class="fas fa-users mr-1"></i> <?= $car['seats'] ?> places
                            <span class="mx-2">•</span>
                            <i class="fas fa-gas-pump mr-1"></i> <?= htmlspecialchars($car['fuel_type']) ?>
                        </p>
                    </div>
                </div>
                <div class="price-badge text-right">
                    <p class="text-sm text-gray-600">Prix total</p>
                    <p class="text-3xl font-bold" id="totalPriceHeader"><?= number_format($totalPrice, 0, ',', ' ') ?> €</p>
                    <p class="text-sm text-gray-500"><span id="daysCountHeader"><?= $days ?></span> jour<?= $days > 1 ? 's' : '' ?></p>
                </div>
            </div>
            
            <div class="flex items-center justify-between bg-gray-50 border border-gray-100 rounded-lg p-4">
                <div class="text-center">
                    <p class="text-sm text-gray-600 mb-1">Prise en charge</p>
                    <p class="text-xl font-bold text-gray-800" id="pickupDateDisplay"><?= $pickupDate ? date('d/m/Y', strtotime($pickupDate)) : '--/--/----' ?></p>
                </div>
                
                <div class="flex-1 mx-6 text-center">
                    <i class="fas fa-long-arrow-alt-right text-2xl text-blue-500"></i>
                    <p class="text-sm mt-1 text-gray-500 font-medium"><span id="daysCountMiddle"><?= $days ?></span> jour<?= $days > 1 ? 's' : '' ?></p>
                </div>
                
                <div class="text-center">
                    <p class="text-sm text-gray-600 mb-1">Retour</p>
                    <p class="text-xl font-bold text-gray-800" id="returnDateDisplay"><?= $returnDate ? date('d/m/Y', strtotime($returnDate)) : '--/--/----' ?></p>
                </div>
            </div>
        </div>

        <div class="card p-8">
            <form id="carBookingForm" method="POST" action="index.php?action=confirm-car-booking">
                <input type="hidden" name="car_id" value="<?= $car['id'] ?>">
                <input type="hidden" name="pickup_date" id="hiddenPickupDate" value="<?= htmlspecialchars($pickupDate) ?>">
                <input type="hidden" name="return_date" id="hiddenReturnDate" value="<?= htmlspecialchars($returnDate) ?>">
                <input type="hidden" name="total_price" id="hiddenTotalPrice" value="<?= $totalPrice ?>">

                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">
                        <i class="fas fa-calendar text-blue-600 mr-2"></i>
                        Détails de la Location
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date de prise en charge *</label>
                            <input type="date" id="pickupDateInput" name="pickup_date_display" class="input-field" value="<?= htmlspecialchars($pickupDate) ?>" min="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nombre de jours *</label>
                            <select id="daysInput" name="days_display" class="input-field" required>
                                <?php for($i = 1; $i <= 30; $i++): ?>
                                    <option value="<?= $i ?>" <?= $i == $days ? 'selected' : '' ?>><?= $i ?> jour<?= $i > 1 ? 's' : '' ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date de retour</label>
                            <input type="date" id="returnDateInput" class="input-field bg-gray-100" value="<?= htmlspecialchars($returnDate) ?>" readonly>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Lieu de prise en charge *</label>
                            <input type="text" name="pickup_location" class="input-field" placeholder="Ex: Aéroport Paris CDG" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Lieu de retour</label>
                            <input type="text" name="return_location" class="input-field" placeholder="Même lieu par défaut">
                        </div>
                    </div>

                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                        <div class="flex items-center">
                            <i class="fas fa-info-circle text-blue-400 mr-3"></i>
                            <div>
                                <p class="text-sm text-blue-700">
                                    <strong>Prix par jour:</strong> <?= number_format($car['price_per_day'], 0, ',', ' ') ?> €
                                </p>
                                <p class="text-sm text-blue-700 mt-1">
                                    <strong>Prix total:</strong> <span id="totalPriceDisplay"><?= number_format($totalPrice, 0, ',', ' ') ?></span> €
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">
                        <i class="fas fa-user text-blue-600 mr-2"></i>
                        Informations du Conducteur
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Prénom *</label>
                            <input type="text" name="first_name" class="input-field" placeholder="Jean" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nom *</label>
                            <input type="text" name="last_name" class="input-field" placeholder="Dupont" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Téléphone *</label>
                            <input type="tel" name="phone_number" class="input-field" placeholder="+33 6 12 34 56 78" pattern="[0-9+\s\-()]+" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                            <input type="email" name="email" class="input-field" placeholder="jean.dupont@example.com" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Numéro de permis *</label>
                            <input type="text" name="license_number" class="input-field" placeholder="Ex: 123456789012" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date de naissance *</label>
                            <input type="date" name="date_of_birth" class="input-field" max="<?= date('Y-m-d', strtotime('-18 years')) ?>" required>
                        </div>
                    </div>
                </div>

                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">
                        <i class="fas fa-credit-card text-blue-600 mr-2"></i>
                        Informations de Paiement
                    </h2>

                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                        <div class="flex">
                            <i class="fas fa-info-circle text-yellow-400 mt-1 mr-3"></i>
                            <p class="text-sm text-yellow-700">
                                <strong>Note:</strong> Ceci est une démo. Les informations de carte ne seront pas réellement traitées.
                            </p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Titulaire de la Carte *</label>
                        <input type="text" name="card_holder" class="input-field" placeholder="Jean Dupont" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Numéro de Carte *</label>
                        <input type="text" name="card_number" id="cardNumber" class="input-field" placeholder="1234 5678 9012 3456" maxlength="19" required>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date d'Expiration *</label>
                            <input type="text" name="card_expiry" id="cardExpiry" class="input-field" placeholder="MM/AA" maxlength="5" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">CVV *</label>
                            <input type="text" name="card_cvv" id="cardCVV" class="input-field" placeholder="123" maxlength="4" required>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-primary">
                    <i class="fas fa-check-circle mr-2"></i>
                    Confirmer la Réservation - <span id="totalPriceButton"><?= number_format($totalPrice, 0, ',', ' ') ?></span> €
                </button>
            </form>
        </div>
    </main>

    <script>
        const pricePerDay = <?= $car['price_per_day'] ?>;
        const pickupDateInput = document.getElementById('pickupDateInput');
        const daysInput = document.getElementById('daysInput');
        const returnDateInput = document.getElementById('returnDateInput');
        const totalPriceDisplay = document.getElementById('totalPriceDisplay');
        const totalPriceHeader = document.getElementById('totalPriceHeader');
        const totalPriceButton = document.getElementById('totalPriceButton');
        const daysCountHeader = document.getElementById('daysCountHeader');
        const daysCountMiddle = document.getElementById('daysCountMiddle');
        const pickupDateDisplay = document.getElementById('pickupDateDisplay');
        const returnDateDisplay = document.getElementById('returnDateDisplay');
        const hiddenPickupDate = document.getElementById('hiddenPickupDate');
        const hiddenReturnDate = document.getElementById('hiddenReturnDate');
        const hiddenTotalPrice = document.getElementById('hiddenTotalPrice');

        function formatDate(dateStr) {
            const date = new Date(dateStr);
            return date.toLocaleDateString('fr-FR');
        }

        function calculateDates() {
            const pickupDate = pickupDateInput.value;
            const days = parseInt(daysInput.value);

            if (pickupDate && days) {
                const pickupDateObj = new Date(pickupDate);
                const returnDateObj = new Date(pickupDateObj);
                returnDateObj.setDate(returnDateObj.getDate() + days);

                const returnDateStr = returnDateObj.toISOString().split('T')[0];
                returnDateInput.value = returnDateStr;

                // Update hidden fields
                hiddenPickupDate.value = pickupDate;
                hiddenReturnDate.value = returnDateStr;

                // Calculate and update total price
                const totalPrice = pricePerDay * days;
                const formattedPrice = totalPrice.toLocaleString('fr-FR');
                totalPriceDisplay.textContent = formattedPrice;
                totalPriceHeader.textContent = formattedPrice + ' €';
                totalPriceButton.textContent = formattedPrice;
                hiddenTotalPrice.value = totalPrice;

                // Update days count
                daysCountHeader.textContent = days;
                daysCountMiddle.textContent = days;

                // Update date displays
                pickupDateDisplay.textContent = formatDate(pickupDate);
                returnDateDisplay.textContent = formatDate(returnDateStr);
            }
        }

        pickupDateInput.addEventListener('change', calculateDates);
        daysInput.addEventListener('change', calculateDates);

        // Initialize on page load
        calculateDates();

        document.getElementById('cardNumber').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s/g, '');
            let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
            e.target.value = formattedValue;
        });

        document.getElementById('cardExpiry').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.slice(0, 2) + '/' + value.slice(2, 4);
            }
            e.target.value = value;
        });

        document.getElementById('cardCVV').addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/\D/g, '');
        });
    </script>
</body>
</html>
