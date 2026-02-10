<?php
declare(strict_types=1);

/**
 * Front Controller - TableFind entry point.
 */

// 1. Laad de autoloader
require_once __DIR__ . '/../app/autoload.php';

session_start();

use App\Core\Router;
use App\Controllers\ReservationsController;

$router = new Router();

// --- PUBLIEKE ROUTES ---
$router->get('/', function() {
    require __DIR__ . '/../app/Views/Public/home.php';
});

// De route voor de reserveringsknop
$router->get('/book', [ReservationsController::class, 'book']);

// Verwerking van het formulier
$router->post('/reservations/public-store', [ReservationsController::class, 'publicStore']);

// --- ADMIN ROUTES ---
$router->get('/admin/reservations', [ReservationsController::class, 'index']);

// 3. Start de applicatie
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);