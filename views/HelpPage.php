<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Centre d'aide - TravelBooking</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800">

  <!-- En-tête -->
  <header class="bg-white shadow p-4 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-gray-600">Centre d'aide</h1>
    <a href="index.php?action=login" class="text-gray-600 font-semibold hover:underline">Connexion</a>
  </header>

  <!-- Message d'accueil -->
  <section class="text-center py-8">
    <h2 class="text-xl font-semibold">Gagnez du temps. Nous avons les réponses à toutes vos questions.</h2>
    <p class="mt-2 text-gray-600">Recherchez une rubrique ou parcourez les catégories ci-dessous.</p>
    <div class="mt-4 max-w-md mx-auto">
      <input type="text" placeholder="Rechercher des articles d'aide" class="w-full p-3 border rounded shadow-sm">
    </div>
  </section>

  <!-- Rubriques d'aide -->
  <section class="max-w-5xl mx-auto grid grid-cols-2 md:grid-cols-3 gap-6 p-6">
    <div class="bg-white p-4 rounded shadow hover:bg-green-50 cursor-pointer">
      <h3 class="font-bold text-lg">Annulation</h3>
      <p class="text-sm text-gray-600">Annuler ou demander un remboursement</p>
    </div>
    <div class="bg-white p-4 rounded shadow hover:bg-green-50 cursor-pointer">
      <h3 class="font-bold text-lg">Modification</h3>
      <p class="text-sm text-gray-600">Modifier les dates ou les passagers</p>
    </div>
    <div class="bg-white p-4 rounded shadow hover:bg-green-50 cursor-pointer">
      <h3 class="font-bold text-lg">Bagages</h3>
      <p class="text-sm text-gray-600">Règles et frais de bagages</p>
    </div>
    <div class="bg-white p-4 rounded shadow hover:bg-green-50 cursor-pointer">
      <h3 class="font-bold text-lg">Enregistrement</h3>
      <p class="text-sm text-gray-600">Comment s’enregistrer en ligne</p>
    </div>
    <div class="bg-white p-4 rounded shadow hover:bg-green-50 cursor-pointer">
      <h3 class="font-bold text-lg">Hôtels</h3>
      <p class="text-sm text-gray-600">Réservations et annulations d’hôtel</p>
    </div>
    <div class="bg-white p-4 rounded shadow hover:bg-green-50 cursor-pointer">
      <h3 class="font-bold text-lg">Trains</h3>
      <p class="text-sm text-gray-600">Billets et horaires de train</p>
    </div>
  </section>

  <!-- Bannière Prime -->
  <section class="bg-gray-600 text-white text-center p-6 mt-8">
    <h3 class="text-xl font-bold">GOPrime ASSISTANCE PRIORITAIRE</h3>
    <p class="mt-2">Nous prenons presque tous les appels dans les 120 secondes.</p>
    <button class="mt-4 bg-white text-gray-600 font-semibold px-6 py-2 rounded shadow hover:bg-gray-100">Essayez Prime</button>
  </section>

</body>
</html>
