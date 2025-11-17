<?php 
// Si vous utilisez la session pour les messages d'erreur ou la redirection après inscription, 
// assurez-vous que session_start() est bien exécuté avant d'inclure cette vue.
// session_start(); 
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer un compte - TravelBooking</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <style>
        /* Typographie cohérente */
        body { font-family: 'Poppins', sans-serif; }

        /* Style pour l'état actif du lien 'Créer un compte' */
        .nav-link-active {
            border-bottom: 2px solid #3b82f6; 
            color: #1f2937; 
            font-weight: 600;
        }

        /* Styles des champs de formulaire (fond bleu pâle cohérent) */
        .input-field {
            background-color: #eff6ff;
            border: 1px solid #dbeafe;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            width: 100%;
            transition: all 0.2s;
        }
        .input-field:focus {
            outline: none;
            border-color: #60a5fa; 
            box-shadow: 0 0 0 1px #60a5fa;
        }
        
        /* --- STYLES POUR L'IMAGE DE FOND (THÈME VILLE) --- */
        .background-container {
            /* REMPLACER AVEC L'URL DE VOTRE IMAGE DE VILLE/VOYAGE HOSTÉE */
            background-image: url('image6.png'); 
            background-size: cover;
            background-position: center;
            background-attachment: fixed; 
            position: relative;
            z-index: 10;
        }
        .background-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            /* Overlay sombre pour garantir la lisibilité du formulaire blanc */
            background-color: rgba(0, 0, 0, 0.4); 
            backdrop-filter: blur(2px); 
            z-index: 20;
        }
        /* Assure que le formulaire est au-dessus de l'overlay */
        .form-content {
            position: relative;
            z-index: 30; 
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
                
                <a href="index.php?action=login" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:border-blue-300 hover:text-blue-700 transition duration-150">
                    <span class="text-sm">Connexion</span>
                </a>
                
                <a href="index.php?action=signup" class="inline-flex items-center px-1 pt-1 nav-link-active transition duration-150">
                    <span class="text-sm">Créer un compte</span>
                </a>
            </div>
        </div>
    </div>
</header>


<div class="min-h-screen flex items-start justify-center pt-20 pb-12 background-container">
    <div class="w-full max-w-md bg-white p-8 rounded-xl shadow-2xl transition duration-300 transform form-content">
        
        <h2 class="text-3xl font-bold text-gray-800 text-center mb-6">Créer un compte</h2>

      <p class="text-center text-sm text-gray-450 mb-6 font-bold">Débloquez les avantages exclusifs et une expérience personnalisée.</p>
        <p class="text-center text-sm text-gray-600 mb-6">Vous voulez voir vos voyages ? Créez un compte avec votre e-mail de réservation ou votre compte social.</p>
        <div class="space-y-3 mb-6">
            
            <button class="w-full flex items-center justify-center border border-gray-300 bg-white text-gray-700 font-medium py-2 rounded-lg hover:bg-gray-50 transition duration-150 shadow-sm">
                <span class="text-lg mr-3 font-bold -mt-0.5">
                    <span style="color: #4285F4;">G</span><span style="color: #EA4335;">o</span><span style="color: #FBBC05;">o</span><span style="color: #4285F4;">g</span><span style="color: #34A853;">l</span><span style="color: #EA4335;">e</span></span>
                Continuer avec Google
            </button>

            <button class="w-full flex items-center justify-center border border-gray-300 bg-white text-gray-700 font-medium py-2 rounded-lg hover:bg-gray-50 transition duration-150 shadow-sm">
                <i class="fab fa-facebook-f text-lg mr-3 text-blue-600"></i>
                Continuer avec Facebook
            </button>

            <button class="w-full flex items-center justify-center border border-gray-300 bg-white text-gray-700 font-medium py-2 rounded-lg hover:bg-gray-50 transition duration-150 shadow-sm">
                <i class="fab fa-apple text-lg mr-3 text-gray-800"></i>
                Continuer avec Apple
            </button>
        </div>

        <div class="flex items-center my-6">
            <div class="flex-grow border-t border-gray-300"></div>
            <span class="flex-shrink mx-4 text-gray-500 text-sm">OU</span>
            <div class="flex-grow border-t border-gray-300"></div>
        </div>

        <form action="index.php?action=register" method="POST">
            
            <div class="mb-5">
                <label for="full_name" class="block text-sm font-medium text-gray-700 mb-2">Nom complet</label>
                <input 
                    type="text" 
                    id="full_name" 
                    name="full_name" 
                    class="input-field" 
                    placeholder="Votre nom et prénom" 
                    required
                >
            </div>
            
            <div class="mb-5">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    class="input-field" 
                    placeholder="votre.email@exemple.com" 
                    required
                >
            </div>
            
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Mot de passe</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    class="input-field" 
                    placeholder="8 caractères minimum" 
                    required
                >
            </div>
            
            <button 
                type="submit" 
                class="w-full bg-blue-600 text-white font-bold py-3 rounded-lg hover:bg-blue-700 transition duration-200 shadow-md transform hover:scale-[1.01]"
            >
                S'inscrire
            </button>
        </form>
        
        <div class="mt-8 text-center text-sm text-gray-600">
            Déjà un compte ? 
            <a href="index.php?action=login" class="font-semibold text-blue-600 hover:text-blue-800 transition duration-150">Se connecter</a>
        </div>
        
    </div>
</div>

</body>
</html>