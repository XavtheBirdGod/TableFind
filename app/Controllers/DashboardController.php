<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Core\Auth;
use App\Services\StatsService;

/**
 * DashboardController
 * Centraliseert de belangrijkste bedrijfsstatistieken voor de admin-omgeving.
 */
class DashboardController
{
    private StatsService $statsService;

    /**
     * Constructor: Controleert autorisatie en initialiseert benodigde services.
     */
    public function __construct()
    {
        // Controleer of de gebruiker is ingelogd voordat de pagina wordt geladen
        Auth::requireLogin();
        $this->statsService = new StatsService();
    }

    /**
     * Rendert de dashboard view met actuele statistieken.
     *
     * @return void
     */
    public function index(): void
    {
        // Haal statistieken op via de StatsService
        $stats = $this->statsService->getDashboardStats();

        // Stuur de data naar de view engine
        View::render('Admin/dashboard', [
            'title' => 'Beheerders Dashboard',
            'stats' => $stats
        ]);
    }
}