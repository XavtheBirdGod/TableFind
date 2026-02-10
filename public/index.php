<?php
declare(strict_types=1);

/**
 * Hoofdbestand voor de routing en initialisatie.
 * Foutrapportage staat aan voor ontwikkeling.
 */
ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/../app/autoload.php';

// Beveiligingsheaders toevoegen
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: SAMEORIGIN");
header("X-XSS-Protection: 1; mode=block");
// CSP kan strikt zijn, dus we beginnen mild om niets te breken (pas aan indien nodig)
header("Content-Security-Policy: default-src 'self' https: 'unsafe-inline'; img-src 'self' data: https:; font-src 'self' https: data:;");

session_start();

// Catch Fatal Errors die een 500-fout zonder output kunnen veroorzaken
register_shutdown_function(function() {
    $error = error_get_last();
    if ($error && ($error['type'] === E_ERROR || $error['type'] === E_PARSE || $error['type'] === E_CORE_ERROR || $error['type'] === E_COMPILE_ERROR)) {
        http_response_code(500);
        echo "<h1>FATAL ERROR</h1>";
        echo "<pre>";
        print_r($error);
        echo "</pre>";
    }
});

use App\Core\Router;

// 1. Initialiseer het router object
$router = new Router();

/**
 * --- AUTHENTICATIE ROUTES ---
 */
$router->add('GET',  '/login',      'AuthController@showLogin');
$router->add('POST', '/login/auth', 'AuthController@authenticate');
$router->add('GET',  '/logout',     'AuthController@logout');

/**
 * --- PUBLIEKE ROUTES ---
 */
$router->add('GET',  '/',                          'ReservationsController@home');
$router->add('GET',  '/book',                      'ReservationsController@book');
$router->add('POST', '/reservations/public-store', 'ReservationsController@publicStore');
$router->add('GET',  '/success',                   'ReservationsController@success');

/**
 * --- ADMIN ROUTES ---
 */
$router->add('GET',  '/admin/dashboard',            'DashboardController@index');

// Gebruikersprofiel en Instellingen
$router->add('GET',  '/admin/profile',              'UsersController@profile');
$router->add('POST', '/admin/profile',              'UsersController@updateProfile');

// Reserveringsbeheer
$router->add('GET',  '/admin/reservations',            'ReservationsController@index');
$router->add('GET',  '/admin/reservations/edit/{id}',  'ReservationsController@edit');
$router->add('POST', '/admin/reservations/update/{id}', 'ReservationsController@update');
$router->add('POST', '/admin/reservations/delete/{id}', 'ReservationsController@destroy');

// Tafelbeheer
$router->add('GET',  '/admin/tables',              'TablesController@index');
$router->add('GET',  '/admin/tables/create',       'TablesController@create');
$router->add('POST', '/admin/tables/store',        'TablesController@store');
$router->add('GET',  '/admin/tables/edit/{id}',    'TablesController@edit');
$router->add('POST', '/admin/tables/update/{id}',  'TablesController@update');
$router->add('POST', '/admin/tables/delete/{id}',  'TablesController@destroy');

// Gebruikersbeheer (Optioneel)
$router->add('GET',  '/admin/users',               'UsersController@index');

/**
 * --- EXECUTE ---
 */
// Verwerk de inkomende aanvraag
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);