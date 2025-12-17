<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$flight = $flight ?? null;
$passengers = $passengers ?? 1;
$travelClass = $travelClass ?? 'economy';

if (!$flight) {
    header('Location: index.php?action=vols');
    exit;
}

$priceKeyMap = [
    'economy' => 'price_economy', 
    'business' => 'price_business',
    'first' => 'price_first'
];

$priceKey = $priceKeyMap[$travelClass] ?? 'price_economy';
$pricePerPerson = $flight[$priceKey] ?? $flight['price_economy'] ?? 0;
$totalPrice = $pricePerPerson * $passengers;

$departureDisplay = $flight['departure_city'] . ' (' . $flight['departure_iata'] . ')';
$arrivalDisplay = $flight['arrival_city'] . ' (' . $flight['arrival_iata'] . ')';

$classLabels = [
    'economy' => 'Économique',
    'business' => 'Affaires',
    'first' => 'Première Classe'
];
$classLabel = $classLabels[$travelClass] ?? 'Économique';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation de Vol - TravelBooking</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            /* Background image style */
            background-image: url('assets/images/image6.png');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            position: relative;
        }

        /* Overlay */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5); /* Slightly darker for better contrast */
            backdrop-filter: blur(3px);
            z-index: -1;
        }

        .card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            position: relative; /* To sit above overlay */
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

        .input-field.error {
            border-color: #ef4444;
        }

        .error-message {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: none;
        }

        .radio-custom {
            appearance: none;
            width: 20px;
            height: 20px;
            border: 2px solid #d1d5db;
            border-radius: 50%;
            position: relative;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .radio-custom:checked {
            border-color: #3b82f6;
            background-color: #3b82f6;
        }

        .radio-custom:checked::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 8px;
            height: 8px;
            background-color: white;
            border-radius: 50%;
        }

        .passenger-section {
            border: 2px solid #e5e7eb;
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            background: #f9fafb;
        }

        .card-input {
            font-family: 'Courier New', monospace;
            letter-spacing: 0.05em;
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

        .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .flight-summary {
            background: white;
            color: #1f2937; /* gray-800 */
            padding: 2rem;
            border-radius: 1rem;
            margin-bottom: 2rem;
            position: relative;
            z-index: 10;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            border-top: 4px solid #2563eb; /* blue-600 accent */
        }
        
        .flight-summary .text-white {
            color: #1f2937 !important;
        }
        
        .flight-summary .opacity-90, .flight-summary .opacity-75 {
            color: #4b5563; /* gray-600 */
            opacity: 1;
        }

        .price-badge {
            background: #eff6ff; /* blue-50 */
            padding: 1rem 1.5rem;
            border-radius: 0.75rem;
            border: 1px solid #bfdbfe; /* blue-200 */
            color: #2563eb; /* blue-600 */
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
            </div>
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-4 py-8">
        <div class="flight-summary">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h1 class="text-3xl font-bold mb-2">
                        <i class="fas fa-plane-departure mr-2"></i>
                        Réservation de Vol
                    </h1>
                    <p class="text-lg opacity-90">
                        <?php echo htmlspecialchars($flight['airline']); ?> - Vol <?php echo htmlspecialchars($flight['flight_number']); ?>
                    </p>
                </div>
                <div class="price-badge text-right">
                    <p class="text-sm opacity-90">Prix total</p>
                    <p class="text-3xl font-bold"><?php echo number_format($totalPrice, 0, ',', ' '); ?> €</p>
                    <p class="text-sm opacity-75"><?php echo $passengers; ?> passager<?php echo $passengers > 1 ? 's' : ''; ?> - <?php echo $classLabel; ?></p>
                </div>
            </div>
            
            <div class="flex items-center justify-between bg-gray-50 border border-gray-100 rounded-lg p-4">
                <div class="text-center">
                    <p class="text-2xl font-bold text-gray-800"><?php echo date('d/m/Y H:i', strtotime($flight['departure_time'])); ?></p>
                    <p class="text-gray-600 font-medium"><?php echo htmlspecialchars($departureDisplay); ?></p>
                </div>
                
                <div class="flex-1 mx-6 text-center">
                    <i class="fas fa-long-arrow-alt-right text-2xl text-blue-500"></i>
                    <p class="text-sm mt-1 text-gray-500 font-medium"><?php echo htmlspecialchars($flight['duration']); ?></p>
                </div>
                
                <div class="text-center">
                    <p class="text-2xl font-bold text-gray-800">--:--</p>
                    <p class="text-gray-600 font-medium"><?php echo htmlspecialchars($arrivalDisplay); ?></p>
                </div>
            </div>
        </div>

        <div class="card p-8">
            <form id="bookingForm" method="POST" action="index.php?action=confirm-booking">
                <input type="hidden" name="flight_id" value="<?php echo $flight['id']; ?>">
                <input type="hidden" name="number_of_passengers" value="<?php echo $passengers; ?>">
                <input type="hidden" name="travel_class" value="<?php echo $travelClass; ?>">
                <input type="hidden" name="total_price" value="<?php echo $totalPrice; ?>">

                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">
                        <i class="fas fa-users text-blue-600 mr-2"></i>
                        Informations des Passagers
                    </h2>

                    <?php for ($i = 1; $i <= $passengers; $i++): ?>
                    <div class="passenger-section">
                        <h3 class="text-lg font-semibold text-gray-700 mb-4">
                            Passager <?php echo $i; ?>
                        </h3>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Civilité *</label>
                            <div class="flex gap-6">
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="passenger_<?php echo $i; ?>_title" value="M" class="radio-custom mr-2" required>
                                    <span class="text-gray-700">M.</span>
                                </label>
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="passenger_<?php echo $i; ?>_title" value="Mme" class="radio-custom mr-2" required>
                                    <span class="text-gray-700">Mme</span>
                                </label>
                            </div>
                            <span class="error-message" id="error_passenger_<?php echo $i; ?>_title">Veuillez sélectionner une civilité</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Prénom *</label>
                                <input type="text" name="passenger_<?php echo $i; ?>_first_name" class="input-field" placeholder="Jean" required>
                                <span class="error-message" id="error_passenger_<?php echo $i; ?>_first_name">Le prénom est requis</span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nom *</label>
                                <input type="text" name="passenger_<?php echo $i; ?>_last_name" class="input-field" placeholder="Dupont" required>
                                <span class="error-message" id="error_passenger_<?php echo $i; ?>_last_name">Le nom est requis</span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date de Naissance *</label>
                            <input type="date" name="passenger_<?php echo $i; ?>_date_of_birth" class="input-field" max="<?php echo date('Y-m-d'); ?>" required>
                            <span class="error-message" id="error_passenger_<?php echo $i; ?>_date_of_birth">La date de naissance est requise</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Téléphone *</label>
                                <input type="tel" name="passenger_<?php echo $i; ?>_phone_number" class="input-field" placeholder="+33 6 12 34 56 78" pattern="[0-9+\s\-()]+" required>
                                <span class="error-message" id="error_passenger_<?php echo $i; ?>_phone_number">Le numéro de téléphone est requis</span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                                <input type="email" name="passenger_<?php echo $i; ?>_email" class="input-field" placeholder="jean.dupont@example.com" required>
                                <span class="error-message" id="error_passenger_<?php echo $i; ?>_email">L'email est requis et doit être valide</span>
                            </div>
                        </div>
                    </div>
                    <?php endfor; ?>
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
                        <span class="error-message" id="error_card_holder">Le nom du titulaire est requis</span>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Numéro de Carte *</label>
                        <input type="text" name="card_number" id="cardNumber" class="input-field card-input" placeholder="1234 5678 9012 3456" maxlength="19" required>
                        <span class="error-message" id="error_card_number">Le numéro de carte est requis (16 chiffres)</span>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date d'Expiration *</label>
                            <input type="text" name="card_expiry" id="cardExpiry" class="input-field card-input" placeholder="MM/AA" maxlength="5" required>
                            <span class="error-message" id="error_card_expiry">Format: MM/AA</span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">CVV *</label>
                            <input type="text" name="card_cvv" id="cardCVV" class="input-field card-input" placeholder="123" maxlength="4" required>
                            <span class="error-message" id="error_card_cvv">CVV requis (3-4 chiffres)</span>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-primary">
                    <i class="fas fa-check-circle mr-2"></i>
                    Confirmer la Réservation - <?php echo number_format($totalPrice, 0, ',', ' '); ?> €
                </button>
            </form>
        </div>
    </main>

    <script>
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

        document.getElementById('bookingForm').addEventListener('submit', function(e) {
            let isValid = true;
            
            document.querySelectorAll('.error-message').forEach(el => el.style.display = 'none');
            document.querySelectorAll('.input-field').forEach(el => el.classList.remove('error'));

            const cardNumber = document.getElementById('cardNumber').value.replace(/\s/g, '');
            if (cardNumber.length !== 16 || !/^\d+$/.test(cardNumber)) {
                document.getElementById('error_card_number').style.display = 'block';
                document.getElementById('cardNumber').classList.add('error');
                isValid = false;
            }

            const expiry = document.getElementById('cardExpiry').value;
            if (!/^\d{2}\/\d{2}$/.test(expiry)) {
                document.getElementById('error_card_expiry').style.display = 'block';
                document.getElementById('cardExpiry').classList.add('error');
                isValid = false;
            } else {
                const [month, year] = expiry.split('/').map(Number);
                const currentYear = new Date().getFullYear() % 100;
                const currentMonth = new Date().getMonth() + 1;
                
                if (month < 1 || month > 12 || year < currentYear || (year === currentYear && month < currentMonth)) {
                    document.getElementById('error_card_expiry').style.display = 'block';
                    document.getElementById('cardExpiry').classList.add('error');
                    isValid = false;
                }
            }

            const cvv = document.getElementById('cardCVV').value;
            if (cvv.length < 3 || cvv.length > 4 || !/^\d+$/.test(cvv)) {
                document.getElementById('error_card_cvv').style.display = 'block';
                document.getElementById('cardCVV').classList.add('error');
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });
    </script>
</body>
</html>
