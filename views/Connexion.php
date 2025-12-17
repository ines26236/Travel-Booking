<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion - TravelBooking</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
        }

        .nav-link-active {
            border-bottom: 2px solid #3b82f6; 
            color: #1f2937; 
            font-weight: 600;
        }

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

        .background-container {
            background-image: url('assets/images/image6.png'); 
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
            background-color: rgba(0, 0, 0, 0.4); 
            backdrop-filter: blur(2px); 
            z-index: 20;
        }
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
                <a href="index.php?action=login" class="inline-flex items-center px-1 pt-1 nav-link-active transition duration-150">
                    <span class="text-sm">Connexion</span>
                </a>
                <a href="index.php?action=signup" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:border-blue-300 hover:text-blue-700 transition duration-150">
                    <span class="text-sm">Créer un compte</span>
                </a>
            </div>
        </div>
    </div>
</header>


<div class="min-h-screen flex items-start justify-center pt-20 pb-12 background-container">
    <div class="w-full max-w-md bg-white p-8 rounded-xl shadow-2xl transition duration-300 transform hover:shadow-3xl form-content">
        
        
        <h2 class="text-3xl font-bold text-gray-800 text-center mb-8">Se connecter</h2>
        
        <?php if (isset($errorMessage) && $errorMessage): ?>
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <p class="text-sm text-red-600 text-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <?php echo htmlspecialchars($errorMessage); ?>
                </p>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-sm text-green-600 text-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <?php echo htmlspecialchars($_SESSION['flash_message']); unset($_SESSION['flash_message']); ?>
                </p>
            </div>
        <?php endif; ?>
        
      <form action="index.php?action=login" method="POST">

            
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
                    placeholder="••••••••" 
                    required
                >
            </div>
            
            <div class="text-right mb-6">
                <a href="index.php?action=forgot_password" class="text-sm font-medium text-blue-600 hover:text-blue-800 transition duration-150">
                    Mot de passe oublié ?
                </a>
            </div>
            
            <button 
                type="submit" 
                class="w-full bg-blue-600 text-white font-bold py-3 rounded-lg hover:bg-blue-700 transition duration-200 shadow-md transform hover:scale-[1.01]"
            >
                Se connecter
            </button>
        </form>
        
        <div class="mt-8 text-center text-sm text-gray-600">
            Pas encore de compte ? 
            <a href="index.php?action=signup" class="font-semibold text-blue-600 hover:text-blue-800 transition duration-150">Créer un compte</a>
        </div>
        
    </div>
</div>

</body>
</html>