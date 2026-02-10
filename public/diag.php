<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>System Diagnostics</h1>";

// 1. Check Autoload
echo "<h2>1. Autoloading</h2>";
if (file_exists(__DIR__ . '/../app/autoload.php')) {
    require_once __DIR__ . '/../app/autoload.php';
    echo "<span style='color:green'>[PASS] Autoload file found and loaded.</span><br>";
} else {
    die("<span style='color:red'>[FAIL] Autoload file NOT found.</span>");
}

// 2. Check Database Connection
echo "<h2>2. Database Connection</h2>";
use App\Core\Database;
try {
    $pdo = Database::getConnection();
    echo "<span style='color:green'>[PASS] Database connected.</span><br>";
    $stmt = $pdo->query("SELECT VERSION()");
    echo "DB Version: " . $stmt->fetchColumn() . "<br>";
} catch (Throwable $e) {
    echo "<span style='color:red'>[FAIL] Database connection failed: " . $e->getMessage() . "</span><br>";
    $config = require __DIR__ . '/../app/Config/database.php';
    echo "Config Dump: <pre>" . print_r($config, true) . "</pre>";
    die("Stopping diagnostics due to DB failure.");
}

// 3. Test StatsModel (User's custom code)
echo "<h2>3. StatsModel Logic</h2>";
use App\Models\StatsModel;
try {
    $model = new StatsModel();
    echo "<span style='color:green'>[PASS] StatsModel instantiated.</span><br>";
    
    echo "Testing getTotalCount('reservations')... ";
    $count = $model->getTotalCount('reservations');
    echo "Result: $count <span style='color:green'>[OK]</span><br>";

    echo "Testing getTotalCount('tables')... ";
    $count = $model->getTotalCount('tables');
    echo "Result: $count <span style='color:green'>[OK]</span><br>";
    
    echo "Testing getTodaysReservationsCount()... ";
    $count = $model->getTodaysReservationsCount();
    echo "Result: $count <span style='color:green'>[OK]</span><br>";

    // This is critical because users table might not have deleted_at
    echo "Testing getTotalCount('users', true) [Should handle missing deleted_at gracefully]... ";
    $count = $model->getTotalCount('users', true);
    echo "Result: $count <span style='color:green'>[OK]</span><br>";

} catch (Throwable $e) {
    echo "<span style='color:red'>[FAIL] StatsModel Error: " . $e->getMessage() . "</span><br>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

// 4. Test StatsService
echo "<h2>4. StatsService</h2>";
use App\Services\StatsService;
try {
    $service = new StatsService();
    echo "<span style='color:green'>[PASS] StatsService instantiated.</span><br>";
    $stats = $service->getDashboardStats();
    echo "Stats Dump: <pre>" . print_r($stats, true) . "</pre>";
} catch (Throwable $e) {
    echo "<span style='color:red'>[FAIL] StatsService Error: " . $e->getMessage() . "</span><br>";
}

// 5. Test DashboardController (Instantiation only)
echo "<h2>5. DashboardController</h2>";
use App\Controllers\DashboardController;
try {
    // Mock Session
    if (session_status() == PHP_SESSION_NONE) session_start();
    $_SESSION['user_id'] = 1; 
    $_SESSION['user_role'] = 'admin';

    $controller = new DashboardController();
    echo "<span style='color:green'>[PASS] DashboardController instantiated.</span><br>";
} catch (Throwable $e) {
    echo "<span style='color:red'>[FAIL] DashboardController Error: " . $e->getMessage() . "</span><br>";
}

echo "<h2>Diagnostics Complete</h2>";
