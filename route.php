<?php
require 'controllers/PageController.php';

$routes = [
'home' => 'accueil',
'login' => 'connexion',
'signup' => 'inscription',
'profile' => 'profile',
'vols' => 'booking', 
'logout' => 'logout',
'hotels' => 'search_hotels',

'aide' => 'HelpPage',
'flight' => 'search_flight',
    
    // NOUVELLE ROUTE AJAX À AJOUTER ICI
    'search-airport' => 'searchAirportAction', 
    'search-flights' => 'searchFlightsAction', // Action renommée pour clarté
    'search-hotels' => 'searchHotelsAction',
    'hotels-list' => 'showHotelResults',
    
    // Routes de Réservation de Vol
    'book-flight' => 'bookFlightAction',       // Affichage récapitulatif
    'confirm-booking' => 'confirmBookingAction', // Traitement final

    // Routes de Réservation d'Hôtel
    'book-hotel' => 'bookHotelAction',
    'confirm-hotel-booking' => 'confirmHotelBookingAction',

    // Routes de Location de Voiture
    'cars' => 'carsListAction',
    'book-car' => 'bookCarAction',
    'confirm-car-booking' => 'confirmCarBookingAction',

];


$action = $_GET['action'] ?? 'home';


$controller = new PageController();

if (array_key_exists($action, $routes)) {
$method = $routes[$action];
if (method_exists($controller, $method)) {
$controller->$method(); 
return;
}
}


$controller->error(404, "Action non trouvée");