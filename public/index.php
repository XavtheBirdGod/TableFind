<?php
declare(strict_types=1);

// 1. Laad de autoloader als eerste!
require_once __DIR__ . '/../app/autoload.php';
include __DIR__ . '/views/home.php';


// 2. Gebruik de juiste namespaces
use App\Core\Router;
use App\Controllers\ReservationsController;

$router = new Router();

// --- Routes ---
$router->get('/', function() {
    require __DIR__ . '/../app/Views/Public/home.php';
});

// De route voor de reserveringspagina (gekoppeld aan de knop)
$router->get('/book', [new ReservationsController(), 'book']);

// De route voor het opslaan van de reservering
$router->post('/reservations/public-store', [new ReservationsController(), 'publicStore']);

// 3. Dispatch
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);