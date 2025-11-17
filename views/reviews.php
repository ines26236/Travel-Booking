<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Avis - TravelBooking</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<?php include "views/header.php"; ?>

<div class="max-w-3xl mx-auto mt-10">

    <h2 class="text-2xl font-bold mb-6 text-gray-700">Avis</h2>

    <!-- Formulaire pour ajouter un avis -->
    <div class="bg-white p-6 rounded-xl shadow mb-6">
        <form action="index.php?page=reviewPost" method="POST" class="space-y-4">
            <input type="hidden" name="type" value="<?= htmlspecialchars($_GET['type']) ?>">
            <input type="hidden" name="item_id" value="<?= htmlspecialchars($_GET['id']) ?>">

            <div>
                <label class="block mb-1 text-gray-700">Note (1 à 5)</label>
                <input type="number" name="rating" min="1" max="5" required
                       class="w-full border-gray-300 rounded-lg p-2 shadow-sm focus:ring focus:ring-blue-300">
            </div>

            <div>
                <label class="block mb-1 text-gray-700">Commentaire</label>
                <textarea name="comment" rows="3" required
                          class="w-full border-gray-300 rounded-lg p-2 shadow-sm focus:ring focus:ring-blue-300"></textarea>
            </div>

            <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition">
                Publier l’avis
            </button>
        </form>
    </div>

    <!-- Liste des avis existants -->
    <?php if (!empty($reviews)) : ?>
        <?php foreach ($reviews as $review) : ?>
            <div class="bg-white p-4 rounded-xl shadow mb-4">
                <div class="flex justify-between">
                    <span class="font-semibold"><?= htmlspecialchars($review['name']) ?></span>
                    <span class="text-yellow-500"><?= str_repeat('★', $review['rating']) ?></span>
                </div>
                <p class="text-gray-700 mt-2"><?= htmlspecialchars($review['comment']) ?></p>
            </div>
        <?php endforeach; ?>
    <?php else : ?>
        <p class="text-gray-600">Aucun avis pour cet item.</p>
    <?php endif; ?>

</div>

</body>
</html>
