<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Flash;
use App\Core\View;
use App\Repositories\ReservationsRepository;

/**
 * ReservationsController
 * Beheert de reserveringsflow voor de vintage website.
 */
final class ReservationsController
{
    private ReservationsRepository $reservations;

    public function __construct()
    {
        $this->reservations = ReservationsRepository::make();
    }

    /**
     * Toont het reserveringsformulier binnen de layout.
     */
    public function book(): void
    {
        // GEBRUIK VIEW::RENDER OM DE LAYOUT TE BEHOUDEN
        View::render('Public/reservations/book', [
            'title' => 'Maak een Reservering'
        ]);
    }

    public function publicStore(): void
    {
        $name = trim($_POST['customer_name'] ?? '');
        $phone = trim($_POST['customer_phone'] ?? '');

        if (empty($name) || empty($phone)) {
            header('Location: /book');
            exit;
        }

        $this->reservations->create([
            'customer_name'    => $name,
            'customer_phone'   => $phone,
            'guest_count'      => (int)($_POST['guest_count'] ?? 1),
            'reservation_date' => $_POST['reservation_date'] ?? date('Y-m-d'),
            'reservation_time' => $_POST['reservation_time'] ?? '18:00',
            'status'           => 'pending'
        ]);

        header('Location: /');
        exit;
    }

    public function index(): void
    {
        $data = $this->reservations->getAll();
        View::render('Admin/reservations', [
            'title' => 'Beheer Overzicht',
            'reservations' => $data
        ]);
    }
}