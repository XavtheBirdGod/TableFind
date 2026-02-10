<?php
declare(strict_types=1);

// Foutrapportage aan voor debugging
ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/../app/autoload.php';

session_start();

use App\Core\Router;
use App\Controllers\ReservationsController;

$router = new Router();

// --- PUBLIEKE ROUTES ---

// Homepagina via een anonieme functie die de View class gebruikt
$router->get('/', function() {
    App\Core\View::render('Public/home', ['title' => 'TableFind | Welkom']);
});

// Reserveringspagina
$router->get('/book', [ReservationsController::class, 'book']);

// Verwerken van reservering
$router->post('/reservations/public-store', [ReservationsController::class, 'publicStore']);

// --- ADMIN ROUTES ---
$router->get('/admin/reservations', [ReservationsController::class, 'index']);

// Dispatch de URI
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);