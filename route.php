<?php
require 'controllers/PageController.php';

$routes = [
 'home' => 'accueil',
 'login' => 'connexion',
 'signup' => 'inscription',
 'profile' => 'profil',
 'vols' => 'booking', 
 'logout' => 'deconnexion',
 'hotels' => 'search_hotels',
 'activities' => 'search_activities',
 'aide' => 'HelpPage',
 'flight' => 'search_flight',

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

