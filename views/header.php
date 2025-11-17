<!-- views/header.php -->
<nav class="bg-white shadow p-4 flex justify-between items-center">
    <h1 class="text-xl font-bold text-gray-600">TravelBooking</h1>

    <div class="flex gap-6 items-center">
        <a href="index.php?page=search_hotels" class="text-gray-600 hover:text-gray-600">Hôtels</a>
        <a href="index.php?page=search_flights" class="text-gray-600 hover:text-gray-600">Vols</a>
        <a href="index.php?page=search_activities" class="text-gray-600 hover:text-gray-600">Activités</a>

        <div class="ml-4 flex gap-2">
            <a href="index.php?page=login" 
               class="bg-gray-600 text-white px-4 py-1 rounded hover:bg-gray-700">Login</a>
            <a href="index.php?page=signup" 
               class="bg-gray-200 text-gray-700 px-4 py-1 rounded hover:bg-gray-300">Sign Up</a>
        </div>
    </div>
</nav>
