<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\StatsModel;

/**
 * StatsService
 * Business logic laag voor het aggregeren van dashboard statistieken.
 */
class StatsService
{
    private StatsModel $statsModel;

    public function __construct()
    {
        $this->statsModel = new StatsModel();
    }

    /**
     * Retourneert een geformatteerd overzicht van alle belangrijke statistieken.
     */
    public function getDashboardStats(): array
    {
        return [
            'total_reservations' => $this->statsModel->getTotalCount('reservations'),
            'today_reservations' => $this->statsModel->getTodaysReservationsCount(),
            // Pass false for 'users' because it doesn't have deleted_at column
            'total_users'        => $this->statsModel->getTotalCount('users', false),
            'available_tables'   => $this->statsModel->getAvailableTablesCount()
        ];
    }
}