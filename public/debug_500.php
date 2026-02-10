<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../app/autoload.php';

use App\Controllers\DashboardController;
use App\Core\Auth;
use App\Services\StatsService;

echo "Autoload loaded.<br>";

try {
    session_start();
    echo "Session started.<br>";
    
    // Test Auth (simulating logged in)
    $_SESSION['user_id'] = 1;
    $_SESSION['user_role'] = 'admin';
    echo "Simulated session.<br>";

    // Test StatsService
    $service = new StatsService();
    echo "StatsService instantiated.<br>";
    $stats = $service->getDashboardStats();
    echo "Stats retrieved: <pre>" . print_r($stats, true) . "</pre><br>";
    
    // Test DashboardController
    // Note: DashboardController constructor calls Auth::requireLogin()
    $controller = new DashboardController();
    echo "DashboardController instantiated.<br>";
    
    echo "Test Complete. Check if you see this.";

} catch (Throwable $e) {
    echo "Error: " . $e->getMessage() . "<br>";
    echo "Trace: " . $e->getTraceAsString();
}
