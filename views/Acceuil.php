<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil - TravelBooking</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
   

    <style>
        body { font-family: 'Poppins', sans-serif; }

        .slideshow {
            position: relative;
            width: 100%;
            height: 500px; 
            overflow: hidden;
            z-index: 1; 
        }

        .slideshow img {
            position: absolute;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0;
            transition: opacity 1.5s ease-in-out;
        }

        .slideshow img.active {
            opacity: 1;
        }

        .overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.6), rgba(0,0,0,0.1), rgba(0,0,0,0.6));
            opacity: 1; 
            z-index: 2; 
        }
        
        /* Style d'entrée pour la recherche en ligne */
        .search-input-field {
            border: none;
            height: 100%;
            padding: 1rem 1rem;
            font-size: 1rem;
            outline: none;
            width: 100%;
        }

        /* Conteneur principal des champs de recherche */
        .search-container {
            display: grid;
            grid-template-columns: 2fr 1fr 1.5fr 1.5fr 1fr; 
            border: 1px solid #e5e7eb; 
            border-radius: 0.5rem;
            overflow: hidden;
            height: 4rem; 
            align-items: center;
        }

        .search-separator {
            width: 1px;
            height: 70%;
            background-color: #e5e7eb; 
        }
        
       
        .ribbon::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 0;
            border-left: 8px solid transparent;
            border-right: 8px solid transparent;
            border-top: 8px solid var(--ribbon-color, #1f2937); 
        }
       

        .hotel-card {
            background-color: #007bff; 
            color: white;
            border-radius: 0.5rem;
            overflow: hidden;
            position: relative;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s;
            background-image: linear-gradient(to bottom, #007bff, #0056b3);
            height: 350px; 
        }
        .hotel-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        }
        .hotel-card img {
            height: 60%; 
            object-fit: cover; /* Ajouté pour s'assurer que l'image couvre l'espace */
        }
        .discount-badge {
            position: absolute;
            bottom: 40%; 
            right: 0.5rem;
            background-color: #d9534f; 
            color: white;
            font-weight: 600;
            padding: 0.25rem 0.75rem;
            border-radius: 4px; 
            font-size: 1.25rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
        }
        .price-info {
            height: 40%;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 1rem;
            line-height: 1.2;
            background: rgba(0, 0, 0, 0.2);
        }
        .hotel-title {
            position: absolute;
            top: 55%; 
            left: 1rem;
            transform: translateY(-50%);
            font-size: 1.5rem;
            font-weight: 700;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.7);
        }
    </style>
</head>
<body class="bg-gray-100">

<header class="bg-white shadow-md sticky top-0 z-50">
        
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex justify-between items-center h-16">
            
            <div class="flex-shrink-0">
                <a href="/" class="text-2xl font-bold text-gray-800">TravelBooking</a>
            </div>

            <nav class="hidden sm:ml-6 sm:flex sm:space-x-8 h-full">
                <a href="index.php?action=vols" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700 transition duration-150">Vols</a>
                <a href="index.php?action=hotels" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700 transition duration-150">Hôtels</a>
                <a href="#" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700 transition duration-150">Vol + Hôtel</a>
                <a href="#" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700 transition duration-150">Location de voiture</a>
                <a href="index.php?action=activities" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700 transition duration-150">Activités</a>
            </nav>

            <div class="hidden sm:ml-6 sm:flex sm:items-center space-x-4 h-full">
                <button class="flex items-center space-x-1 text-sm font-bold bg-yellow-500 text-white px-3 py-1 rounded-full hover:bg-yellow-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" /></svg>
                    <span>Prime</span>
                </button>

                <a href="index.php?action=login" class="text-gray-500 hover:text-gray-700 transition duration-150 flex items-center space-x-1 text-sm font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    <span>Connexion</span>
                    
                </a>

                <a href="#" class="text-gray-500 hover:text-gray-700 transition duration-150 flex items-center space-x-1 text-sm font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                    <span>Gérer ma réservation</span>
                </a>
                
                <a href="index.php?action=aide" class="text-gray-500 hover:text-gray-700 transition duration-150 flex items-center space-x-1 text-sm font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9.247a3.75 3.75 0 100 5.506 3.75 3.75 0 000-5.506z" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 10.75a1.25 1.25 0 11-2.5 0 1.25 1.25 0 012.5 0z" /></svg>
                    <span>Aide</span>
                </a>
            </div>
        </div>
    </div>
</header>
<div class="slideshow">
    <img src="image1.png" class="active blur-xs">
    <img src="image2.png" class="blur-xs">
    <img src="image3.jpg" class="blur-sm">

    <div class="overlay"></div>

    <div class="absolute inset-0 z-30 flex items-center justify-center">
        <div class="w-full max-w-6xl mx-auto" style="padding: 0 5rem;">

            <div class=" rounded-t-lg relative">
                <div class="ribbon ribbon-gray absolute -top-4 left-1/2 transform -translate-x-1/2 text-sm font-semibold bg-gray-800 text-white px-3 py-1 rounded-full shadow-lg" style="--ribbon-color: #353836ff;">
                    Réservez vos vols, hôtels et activités au meilleur prix !
                </div>
                
                <div class="flex pt-2">
                    <div class="flex items-center space-x-2 bg-white text-wh-800 p-3 rounded-t-lg font-semibold shadow-md ">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                        <span>Vols</span>
                    </div>
                    <div class="flex items-center space-x-2 p-3 font-semibold rounded-t-lg bg-transparent text-white opacity-80 cursor-pointer hover:bg-gray-700 transition duration-150">
  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-1 4h1m-1 4h1M7 21h10" />
  </svg>
  <span>Hôtels</span>
</div>

                    <div class="flex items-center space-x-2 text-white p-3 font-semibold rounded-t-lg opacity-80 cursor-pointer hover:bg-gray-700 transition duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span>Vol + Hôtel</span>
                    </div>
                    <div class="flex items-center space-x-2 text-white p-3 font-semibold rounded-t-lg opacity-80 cursor-pointer hover:bg-gray-700 transition duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>Location de voiture</span>
                    </div>
                     
                </div>
                
            </div>

            <div class="bg-gray-800 p-6 rounded-b-lg shadow-xl border-t border-gray-200"> 
                <div class="flex items-center space-x-4 mb-4 text-white"> <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="radio" name="trip-type" value="round" class="form-radio h-4 w-4 text-gray-500 border-white focus:ring-gray-500" checked onchange="updateTripForm()">
                        <span class="font-semibold">Aller-retour</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="radio" name="trip-type" value="oneway" class="form-radio h-4 w-4 text-green-500 border-white focus:ring-green-500" onchange="updateTripForm()">
                        <span class="opacity-70">Aller simple</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="radio" name="trip-type" value="multi" class="form-radio h-4 w-4 text-green-500 border-white focus:ring-green-500" onchange="updateTripForm()">
                        <span class="opacity-70">Multi-destinations</span>
                    </label>

                    <div class="ml-auto flex items-center space-x-4">
                        <select class="px-4 py-1 border border-white rounded-md appearance-none bg-gray-700 text-white cursor-pointer text-sm font-semibold">
                            <option value="1" class="bg-white text-gray-900">Économique</option>
                            <option value="2" class="bg-white text-gray-900">Éco Premium</option>
                            <option value="3" class="bg-white text-gray-900">Affaires</option>
                            <option value="4" class="bg-white text-gray-900">Première</option>
                        </select>
                        
                        <label class="flex items-center space-x-2 text-white opacity-90 cursor-pointer">
                            <input type="checkbox" class="form-checkbox h-4 w-4 text-gray-500 border-white rounded focus:ring-gray-500">
                            <span>Vols directs</span>
                        </label>
                    </div>
                </div>

                <div id="tripForm" class="space-y-4">
                    </div>

                <div class="flex justify-end mt-6 space-x-4">
                    <button type="submit" class="border border-gray-500 bg-white text-gray-800 font-bold px-6 py-3 rounded-md hover:bg-gray-100 transition shadow-lg">
                        Rechercher Vol + Hôtel
                    </button>
                    <button type="submit" class="bg-gray-600 text-white font-bold px-6 py-3 rounded-md hover:bg-gray-700 transition shadow-lg">
                        Rechercher des vols
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<hr class="my-16 border-gray-300">

<section class="mt-8 mb-24 max-w-7xl mx-auto px-4">
    <h2 class="text-4xl font-extrabold text-gray-700 mb-2">Hôtels les plus prisés</h2>
    <p class="text-gray-600 mb-10 text-xl">Prix les plus bas garantis dans les top destinations !</p>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
        
        <div class="hotel-card">
            <div class="relative w-full h-full">
                <img src="alger.jpg" alt="Alger" class="w-full object-cover">
                <h3 class="hotel-title">Hôtels à Alger</h3>
                <div class="price-info">
                    <div class="flex justify-end items-end w-full">
                        <div class="text-right mr-4">
                            <p class="text-sm font-medium line-through opacity-80">76 €</p>
                            <p class="text-xs font-light">Prix Prime par nuit</p>
                        </div>
                        <p class="text-5xl font-extrabold text-white">45 €</p>
                    </div>
                </div>
                <div class="discount-badge" style="background-color: #d9534f;">-40%</div>
            </div>
        </div>

        <div class="hotel-card">
            <div class="relative w-full h-full">
                <img src="Lisbonne.png" alt="Lisbonne" class="w-full object-cover">
                <h3 class="hotel-title">Hôtels à Lisbonne</h3>
                <div class="price-info">
                    <div class="flex justify-end items-end w-full">
                        <div class="text-right mr-4">
                            <p class="text-sm font-medium line-through opacity-80">113 €</p>
                            <p class="text-xs font-light">Prix Prime par nuit</p>
                        </div>
                        <p class="text-5xl font-extrabold text-white">75 €</p>
                    </div>
                </div>
                <div class="discount-badge" style="background-color: #f59e0b;">-33%</div>
            </div>
        </div>

        <div class="hotel-card">
            <div class="relative w-full h-full">
                <img src="Marrakech.png" alt="Marrakech" class="w-full object-cover">
                <h3 class="hotel-title">Hôtels à Marrakech</h3>
                <div class="price-info">
                    <div class="flex justify-end items-end w-full">
                        <div class="text-right mr-4">
                            <p class="text-sm font-medium line-through opacity-80">122 €</p>
                            <p class="text-xs font-light">Prix Prime par nuit</p>
                        </div>
                        <p class="text-5xl font-extrabold text-white">83 €</p>
                    </div>
                </div>
                <div class="discount-badge" style="background-color: #d9534f;">-31%</div>
            </div>
        </div>

        <div class="hotel-card">
            <div class="relative w-full h-full">
                <img src="Tunis.png" alt="Tunis" class="w-full object-cover">
                <h3 class="hotel-title">Hôtels à Tunis</h3>
                <div class="price-info">
                    <div class="flex justify-end items-end w-full">
                        <div class="text-right mr-4">
                            <p class="text-sm font-medium line-through opacity-80">54 €</p>
                            <p class="text-xs font-light">Prix Prime par nuit</p>
                        </div>
                        <p class="text-5xl font-extrabold text-white">27 €</p>
                    </div>
                </div>
                <div class="discount-badge" style="background-color: #d9534f;">-50%</div>
            </div>
        </div>
        
        <div class="hotel-card">
            <div class="relative w-full h-full">
                <img src="Barcelone.png" alt="Barcelone" class="w-full object-cover">
                <h3 class="hotel-title">Hôtels à Barcelone</h3>
                <div class="price-info">
                    <div class="flex justify-end items-end w-full">
                        <div class="text-right mr-4">
                            <p class="text-sm font-medium line-through opacity-80">300 €</p>
                            <p class="text-xs font-light">Prix Prime par nuit</p>
                        </div>
                        <p class="text-5xl font-extrabold text-white">141 €</p>
                    </div>
                </div>
                <div class="discount-badge" style="background-color: #3b82f6;">-29%</div>
            </div>
        </div>
    </div>

    <div class="text-center mt-12">
        <button class="border-2 border-gray-600 text-gray-600 font-bold px-8 py-3 rounded-full hover:bg-green-50 transition text-lg">
            Rechercher des offres d'hôtels
        </button>
    </div>
</section>
<hr class="my-16 border-gray-300">

<section class="max-w-7xl mx-auto px-4 mb-16 text-center">
    <div class="flex justify-between items-center py-8">
        <div class="text-left">
            <h3 class="text-2xl font-bold text-gray-800 mb-4">Les meilleures offres sur votre smartphone</h3>
            <div class="flex space-x-4">
                <a href="#"><img src="image4.png" alt="Télécharger sur l'App Store" class="h-10"></a>
                <a href="#"><img src="image5.png" alt="Disponible sur Google Play" class="h-10"></a>
            </div>
        </div>

        <div class="text-right">
            <h3 class="text-2xl font-bold text-gray-800 mb-4">Retrouvez-nous sur les réseaux sociaux</h3>
            <p class="text-sm font-medium text-gray-700 mb-4">Faites le plein d'inspiration et découvrez tous les bons plans voyage !</p>
           <div class="flex justify-end space-x-4">
  <!-- Facebook -->
  <a href="#" class="w-10 h-10 flex items-center justify-center rounded-lg bg-[#1877F2] hover:bg-[#145DBF] transition" aria-label="Lien vers Facebook">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6 text-white">
      <path d="M22 12a10 10 0 10-11.5 9.9v-7h-2v-3h2v-2.3c0-2 1.2-3.1 3-3.1.9 0 1.8.1 1.8.1v2h-1c-1 0-1.3.6-1.3 1.2V12h2.2l-.4 3h-1.8v7A10 10 0 0022 12z" fill="currentColor"/>
    </svg>
  </a>

  <!-- Instagram -->
  <a href="#" class="w-10 h-10 flex items-center justify-center rounded-lg bg-gradient-to-tr from-yellow-400 via-pink-500 to-purple-600 hover:opacity-90 transition" aria-label="Lien vers Instagram">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6 text-white">
      <path d="M7 2C4.8 2 3 3.8 3 6v12c0 2.2 1.8 4 4 4h10c2.2 0 4-1.8 4-4V6c0-2.2-1.8-4-4-4H7zm10 2c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H7c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2h10zm-5 3a5 5 0 100 10 5 5 0 000-10zm0 2a3 3 0 110 6 3 3 0 010-6zm4.5-2a1.5 1.5 0 100 3 1.5 1.5 0 000-3z" fill="currentColor"/>
    </svg>
  </a>

  <!-- TikTok -->
  <a href="#" class="w-10 h-10 flex items-center justify-center rounded-lg bg-black hover:bg-gray-800 transition" aria-label="Lien vers TikTok">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6 text-white">
      <path d="M12 2h2.5c.2 1.7 1.5 3.1 3.2 3.3v2.5c-1.2 0-2.3-.4-3.2-1v7.9c0 3.2-2.6 5.8-5.8 5.8S3 17.9 3 14.7c0-3.2 2.6-5.8 5.8-5.8.5 0 1 .1 1.5.2v2.6c-.5-.2-1-.3-1.5-.3-1.8 0-3.2 1.4-3.2 3.2s1.4 3.2 3.2 3.2 3.2-1.4 3.2-3.2V2z" fill="currentColor"/>
    </svg>
  </a>
</div>

        </div>
    </div>
</section>

<footer class="bg-gray-900 text-gray-300 pt-10 pb-4">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="grid grid-cols-2 md:grid-cols-6 gap-6 text-sm mb-10">
            <div>
                <h4 class="font-bold text-blue-500 mb-3">TravelBooking</h4>
                <ul class="space-y-2">
                    <li><a href="#" class="hover:text-white transition">Centre d'Aide</a></li>
                    <li><a href="#" class="hover:text-white transition">TravelBooking Recrute</a></li>
                    <li><a href="#" class="hover:text-white transition">Inscrivez votre établissement</a></li>
                    <li><a href="#" class="hover:text-white transition">Affiliation</a></li>
                    <li><a href="#" class="hover:text-white transition">Blog de voyages</a></li>
                    <li><a href="#" class="hover:text-white transition">Notre appli</a></li>
                    <li><a href="#" class="hover:text-white transition">Garantie du meilleur prix</a></li>
                    <li><a href="#" class="hover:text-white transition">Liste noire compagnies</a></li>
                    <li><a href="#" class="hover:text-white transition">Plan du site</a></li>
                    <li><a href="#" class="hover:text-white transition">Ministère des affaires étrangères</a></li>
                </ul>
            </div>
        </div>

        <hr class="border-gray-700 my-8">

        <div class="text-sm mb-8">
            <h4 class="font-bold text-white mb-3">Liens sur les voyages</h4>
            <div class="flex flex-wrap gap-x-4 gap-y-2">
                <a href="#" class="hover:text-white transition">Billet d'avion</a>
                <a href="#" class="hover:text-white transition">Vols multi destination</a>
                <a href="#" class="hover:text-white transition">Bon de réduction</a>
                <a href="#" class="hover:text-white transition">Vols pas cher</a>
                <a href="#" class="hover:text-white transition">Vols dernière minute</a>
                <a href="#" class="hover:text-white transition">Franchise de bagage</a>
                <a href="#" class="hover:text-white transition">Vols low cost</a>
                <a href="#" class="hover:text-white transition">Comparateur de vol</a>
                <a href="#" class="hover:text-white transition">Agence de voyage</a>
                <a href="#" class="hover:text-white transition">Week-ends</a>
                <a href="#" class="hover:text-white transition">Compagnies aériennes</a>
                <a href="#" class="hover:text-white transition">Vol Ryanair</a>
                <a href="#" class="hover:text-white transition">Vol easyJet</a>
                <a href="#" class="hover:text-white transition">Vol Transavia</a>
                <a href="#" class="hover:text-white transition">Vol Air France</a>
                <a href="#" class="hover:text-white transition">Vol Paris</a>
                <a href="#" class="hover:text-white transition">Vol New York</a>
                <a href="#" class="hover:text-white transition">Vol Marrakech</a>
                <a href="#" class="hover:text-white transition">Vol Paris New York</a>
                <a href="#" class="hover:text-white transition">Vol Paris Lisbonne</a>
                <a href="#" class="hover:text-white transition">Vol Nantes Italie</a>
                <a href="#" class="hover:text-white transition">Vol Paris Algérie</a>
                <a href="#" class="hover:text-white transition">Hôtels map</a>
                <a href="#" class="hover:text-white transition">TravelBooking Partenaires</a>
                <a href="#" class="hover:text-white transition">TravelBooking Saga</a>
                <a href="#" class="hover:text-white transition">TravelBooking Bons plans</a>
                <a href="#" class="hover:text-white transition">Autres Destinations</a>
                <a href="#" class="hover:text-white transition">Informations voyages</a>
                <a href="#" class="hover:text-white transition">Garantie du meilleur prix</a>
                <a href="#" class="hover:text-white transition">Prime</a>
                <a href="#" class="hover:text-white transition">Prime Day</a>
                <a href="#" class="hover:text-white transition">Black Friday billet d'avion</a>
                <a href="#" class="hover:text-white transition">Compagnies aériennes</a>
                <a href="#" class="hover:text-white transition">Routes des Compagnies aériennes</a>
                <a href="#" class="hover:text-white transition">Destinations Pays</a>
                <a href="#" class="hover:text-white transition">Destinations Villes</a>
                <a href="#" class="hover:text-white transition">Villes vers pays</a>
                <a href="#" class="hover:text-white transition">Hôtels</a>
                <a href="#" class="hover:text-white transition">Itinéraires</a>
                <a href="#" class="hover:text-white transition">Packs Vol + Hôtel</a>
            </div>
        </div>

        <hr class="border-gray-700 my-8">

        <div class="flex flex-col items-center space-y-4">
            <div class="text-3xl font-extrabold text-blue-500">TravelBooking</div>

            <div class="flex flex-wrap justify-center gap-x-6 gap-y-2 text-xs font-semibold">
                <a href="#" class="hover:text-white transition">Conditions Générales de vente</a>
                <a href="#" class="hover:text-white transition">Politique de cookies</a>
                <a href="#" class="hover:text-white transition">Politique de confidentialité</a>
                <a href="#" class="hover:text-white transition">Press office</a>
                <a href="#" class="hover:text-white transition">Accessibilité</a>
            </div>
            
            <p class="text-xs text-gray-500 text-center">* Tarifs « à partir de » et sous réserve de disponibilité :</p>
            
            <div class="text-[0.65rem] text-gray-500 text-center leading-relaxed">
                <p>
                    - Vol : Prix TTC, « à partir de » et par personne, incluant les taxes d’aéroport, hors assurances optionnelles, frais de réservation, de livraison et frais de services, pour un vol aller-retour en classe économique, sur une sélection de dates et de destinations. Offre soumise à conditions et valable sous réserve de disponibilité et de confirmation de la compagnie aérienne.
                </p>
                <p>
                    - Vol + Hôtel : Prix TTC, « à partir de » et par personne, incluant les taxes d'aéroport, hors assurances optionnelles, frais de réservation, de livraison, frais de services et taxes locales (Resort Fees) pour un vol aller-retour en classe économique, sur la base d'une chambre double, sur une sélection de dates et de destinations. Sous réserve de disponibilité et de confirmation.
                </p>
                <p class="mt-4">
                    © 1999-2025 TravelBooking. Tous droits réservés - Vacaciones eDreams, S.L. société soumise au droit espagnol, inscrite au registre du commerce de Madrid, Tomo 36897, Folio 121, Hoja M-661117 dont le siège social est Calle de Manzanares, nº 4, Planta 1ª, Oficina 108, 28005, Madrid, E
                </p>
            </div>
        </div>
    </div>
</footer>

<script>
    const slides = document.querySelectorAll('.slideshow img');
    const tripForm = document.getElementById('tripForm');
    let current = 0;

    // Mise à jour de la couleur du ruban pour le triangle
    document.addEventListener('DOMContentLoaded', () => {
        const ribbon = document.querySelector('.ribbon');
        // Utiliser la couleur verte par défaut pour le style
        const ribbonColor = ribbon.style.getPropertyValue('--ribbon-color') || '#4712aaff'; 
        const styleSheet = document.createElement('style');

        document.head.appendChild(styleSheet);
        
        // Initialiser le formulaire
        updateTripForm();
    });

    // Fonction modifiée pour la mise en page en grille/ligne
    function createSegment(includeRetour = true) {
        const travelerSelect = `
            <select class="search-input-field appearance-none bg-white text-gray-700 cursor-pointer text-center">
                <option value="1">1 voyageur</option>
                <option value="2">2 voyageurs</option>
                <option value="3">3 voyageurs</option>
            </select>
        `;

        // Utilisation d'une structure en grille (grid) pour une disposition en ligne similaire à la photo
        const formHTML = `
            <div class="search-container bg-white">
                
                <div class="relative flex items-center">
                    <input type="text" placeholder="D'où partez-vous ?" class="search-input-field text-gray-700 placeholder-gray-500">
                </div>

                <div class="relative flex items-center justify-center">
                    <button type="button" class="text-green-600 hover:text-green-800 transition duration-150 p-2 rounded-full border border-green-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                    </button>
                </div>

                <div class="relative flex items-center">
                    <input type="text" placeholder="Où allez-vous ?" class="search-input-field text-gray-700 placeholder-gray-500">
                </div>

                <div class="relative flex items-center border-l border-gray-300">
                    <input type="text" onfocus="(this.type='date')" onblur="(this.type='text')" placeholder="Départ le" class="search-input-field text-gray-700 placeholder-gray-500 text-center">
                </div>

                ${includeRetour ? `
                    <div class="relative flex items-center border-l border-gray-300">
                        <input type="text" onfocus="(this.type='date')" onblur="(this.type='text')" placeholder="Retour le" class="search-input-field text-gray-700 placeholder-gray-500 text-center">
                    </div>
                    <div class="relative flex items-center border-l border-gray-300">
                        ${travelerSelect}
                    </div>
                ` : `
                    <div class="relative flex items-center border-l border-gray-300">
                        ${travelerSelect}
                    </div>
                `}

            </div>
            ${selected === 'multi' ? `
                <button type="button" onclick="addSegment()" class="text-white text-sm font-semibold hover:text-green-200 transition">
                    + Ajouter un vol
                </button>
            ` : ''}
        `;
        return formHTML;
    }

    function updateTripForm() {
        const selected = document.querySelector('input[name="trip-type"]:checked').value;
        tripForm.innerHTML = '';

        // J'ai simplifié la structure des champs pour mimer la photo : 
        // 1. D'où ? 2. Échange 3. Où ? 4. Départ 5. Retour 6. Voyageurs
        // La structure en grille que j'ai créée dans le CSS plus haut simule cette ligne.

        if (selected === 'round') {
            // Pour l'aller-retour, on affiche 6 colonnes (avec le champ "Retour le")
            tripForm.innerHTML = `
                <div class="search-container bg-white" style="grid-template-columns: 2fr 0.5fr 2fr 1.5fr 1.5fr 1.2fr;">
                    <div class="relative flex items-center">
                        <input type="text" placeholder="D'où partez-vous ?" class="search-input-field text-gray-700 placeholder-gray-500">
                    </div>
                    <div class="relative flex items-center justify-center">
                        <button type="button" class="text-gray-600 hover:text-gray-800 transition duration-150 p-2 rounded-full border border-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                            </svg>
                        </button>
                    </div>
                    <div class="relative flex items-center border-l border-gray-300">
                        <input type="text" placeholder="Où allez-vous ?" class="search-input-field text-gray-700 placeholder-gray-500">
                    </div>
                    <div class="relative flex items-center border-l border-gray-300">
                        <input type="text" onfocus="(this.type='date')" onblur="(this.type='text')" placeholder="Départ le" class="search-input-field text-gray-700 placeholder-gray-500 text-center">
                    </div>
                    <div class="relative flex items-center border-l border-gray-300">
                        <input type="text" onfocus="(this.type='date')" onblur="(this.type='text')" placeholder="Retour le" class="search-input-field text-gray-700 placeholder-gray-500 text-center">
                    </div>
                    <div class="relative flex items-center border-l border-gray-300">
                        <select class="search-input-field appearance-none bg-white text-gray-700 cursor-pointer text-center">
                            <option value="1">1 voyageur</option>
                            <option value="2">2 voyageurs</option>
                            <option value="3">3 voyageurs</option>
                        </select>
                    </div>
                </div>
            `;
        } else if (selected === 'oneway') {
            // Pour l'aller simple, on supprime la colonne "Retour le" (5 colonnes)
            tripForm.innerHTML = `
                <div class="search-container bg-white" style="grid-template-columns: 2fr 0.5fr 2fr 1.5fr 1.2fr;">
                    <div class="relative flex items-center">
                        <input type="text" placeholder="D'où partez-vous ?" class="search-input-field text-gray-700 placeholder-gray-500">
                    </div>
                    <div class="relative flex items-center justify-center">
                        <button type="button" class="text-gray-600 hover:text-gray-800 transition duration-150 p-2 rounded-full border border-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                            </svg>
                        </button>
                    </div>
                    <div class="relative flex items-center border-l border-gray-300">
                        <input type="text" placeholder="Où allez-vous ?" class="search-input-field text-gray-700 placeholder-gray-500">
                    </div>
                    <div class="relative flex items-center border-l border-gray-300">
                        <input type="text" onfocus="(this.type='date')" onblur="(this.type='text')" placeholder="Départ le" class="search-input-field text-gray-700 placeholder-gray-500 text-center">
                    </div>
                    <div class="relative flex items-center border-l border-gray-300">
                        <select class="search-input-field appearance-none bg-white text-gray-700 cursor-pointer text-center">
                            <option value="1">1 voyageur</option>
                            <option value="2">2 voyageurs</option>
                            <option value="3">3 voyageurs</option>
                        </select>
                    </div>
                </div>
            `;
        } else if (selected === 'multi') {
            // Pour le multi-destination, on n'a pas le champ "Retour le" et on peut ajouter des segments (4 colonnes de champs + bouton)
            tripForm.innerHTML = `
                <div class="multi-segments space-y-3">
                    <div class="search-container bg-white" style="grid-template-columns: 2fr 0.5fr 2fr 1.5fr 1.2fr;">
                        <div class="relative flex items-center">
                            <input type="text" placeholder="D'où partez-vous ?" class="search-input-field text-gray-700 placeholder-gray-500">
                        </div>
                        <div class="relative flex items-center justify-center">
                            <button type="button" class="text-gray-600 hover:text-gray-800 transition duration-150 p-2 rounded-full border border-gray-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                </svg>
                            </button>
                        </div>
                        <div class="relative flex items-center border-l border-gray-300">
                            <input type="text" placeholder="Où allez-vous ?" class="search-input-field text-gray-700 placeholder-gray-500">
                        </div>
                        <div class="relative flex items-center border-l border-gray-300">
                            <input type="text" onfocus="(this.type='date')" onblur="(this.type='text')" placeholder="Départ le" class="search-input-field text-gray-700 placeholder-gray-500 text-center">
                        </div>
                        <div class="relative flex items-center border-l border-gray-300">
                            <select class="search-input-field appearance-none bg-white text-gray-700 cursor-pointer text-center">
                                <option value="1">1 voyageur</option>
                                <option value="2">2 voyageurs</option>
                                <option value="3">3 voyageurs</option>
                            </select>
                        </div>
                    </div>
                   <button type="button" onclick="addSegment()" class="add-segment-button text-white text-sm font-semibold hover:text-gray-200 transition">
    + Ajouter un vol
</button>
                </div>
            `;
        }
    }

    function addSegment() {
    const multiSegments = document.querySelector('.multi-segments');
    
    if (multiSegments) {
        // Crée l'élément div pour le nouveau segment
        const newSegment = document.createElement('div');
        newSegment.className = 'search-container bg-white'; // On retire 'space-y-3' ici car c'est pour les blocs verticaux
        // On ajuste le grid-template-columns pour inclure une petite colonne pour le bouton "X"
        newSegment.style.gridTemplateColumns = '2fr 0.5fr 2fr 1.5fr 1.2fr 0.3fr'; // Ajout d'une colonne pour le X
        
        // Structure HTML du nouveau segment (avec le bouton de suppression "X")
        newSegment.innerHTML = `
            <div class="relative flex items-center p-2">
                <input type="text" placeholder="D'où partez-vous ?" class="search-input-field text-gray-700 placeholder-gray-500 w-full p-1 border-none focus:ring-0">
            </div>
            <div class="relative flex items-center justify-center">
                <button type="button" class="text-gray-600 hover:text-gray-800 transition duration-150 p-2 rounded-full border border-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    </svg>
                </button>
            </div>
            <div class="relative flex items-center border-l border-gray-300 p-2">
                <input type="text" placeholder="Où allez-vous ?" class="search-input-field text-gray-700 placeholder-gray-500 w-full p-1 border-none focus:ring-0">
            </div>
            <div class="relative flex items-center border-l border-gray-300 p-2">
                <input type="text" onfocus="(this.type='date')" onblur="(this.type='text')" placeholder="Départ le" class="search-input-field text-gray-700 placeholder-gray-500 text-center w-full p-1 border-none focus:ring-0">
            </div>
            <div class="relative flex items-center border-l border-gray-300 p-2">
                 <select class="search-input-field appearance-none bg-white text-gray-700 cursor-pointer text-center w-full p-1 border-none focus:ring-0">
                    <option value="1">1 voyageur</option>
                    <option value="2">2 voyageurs</option>
                    <option value="3">3 voyageurs</option>
                </select>
            </div>
            <div class="relative flex items-center justify-center border-l border-gray-300">
                 <button type="button" onclick="removeSegment(this)" class="text-gray-500 hover:text-gray-700 transition duration-150 p-2">
                     <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        `;
        
        const addButton = multiSegments.querySelector('button[onclick="addSegment()"]');

        if (addButton) {
            multiSegments.insertBefore(newSegment, addButton);
        } else {
            multiSegments.appendChild(newSegment);
        }
    }
}

// La fonction removeSegment reste la même
function removeSegment(buttonElement) {
    const segmentToRemove = buttonElement.closest('.search-container');
    if (segmentToRemove) {
        segmentToRemove.remove();
    }
}

    function nextSlide() {
        slides[current].classList.remove('active');
        current = (current + 1) % slides.length;
        slides[current].classList.add('active');
    }

    // setInterval(nextSlide, 5000); // Décommenter pour activer le diaporama automatique
</script>
<script>
    function startSlideshow() {
        const images = document.querySelectorAll('.slideshow img');
        let currentIndex = 0;

        function nextImage() {
            // 1. Retirer la classe 'active' de l'image courante
            images[currentIndex].classList.remove('active');

            // 2. Calculer l'index de la prochaine image
            currentIndex = (currentIndex + 1) % images.length;

            // 3. Ajouter la classe 'active' à la nouvelle image
            images[currentIndex].classList.add('active');
        }

        // 4. Définir un intervalle pour changer d'image toutes les 4000 millisecondes (4 secondes)
        setInterval(nextImage, 4000); 
    }

    // Démarrer le diaporama une fois que le document est prêt
    document.addEventListener('DOMContentLoaded', startSlideshow);
</script>

</body>
</html>