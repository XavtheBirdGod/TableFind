<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Flash;
use App\Core\View;
use App\Repositories\ReservationsRepository;
use App\Repositories\TablesRepository;

/**
 * ReservationsController
 * Beheert de reserveringslogica voor zowel de publieke website als het admin-paneel.
 */
final class ReservationsController
{
    private ReservationsRepository $reservations;

    public function __construct()
    {
        $this->reservations = ReservationsRepository::make();
    }

    /**
     * PUBLIC: Toont het reserveringsformulier aan de klant.
     */
    public function book(): void
    {
        // We laden de publieke view
        require __DIR__ . '/../Views/Public/reservations/book.php';
    }

    /**
     * PUBLIC: Verwerkt de reservering die door een klant is geplaatst.
     */
    public function publicStore(): void
    {
        $name = trim($_POST['customer_name'] ?? '');
        $phone = trim($_POST['customer_phone'] ?? '');
        $date = $_POST['reservation_date'] ?? '';

        if (empty($name) || empty($phone) || empty($date)) {
            Flash::set('error', 'Vul alstublieft alle verplichte velden in.');
            header('Location: /book');
            exit;
        }

        $this->reservations->create([
            'customer_name'    => $name,
            'customer_email'   => $_POST['customer_email'] ?? null,
            'customer_phone'   => $phone,
            'guest_count'      => (int)($_POST['guest_count'] ?? 1),
            'reservation_date' => $date,
            'reservation_time' => $_POST['reservation_time'] ?? '18:00',
            'status'           => 'pending', // Klanten reserveringen staan standaard op pending
            'notes'            => $_POST['notes'] ?? ''
        ]);

        header('Location: /reservations/success');
        exit;
    }

    /**
     * ADMIN: Geeft de lijst met alle reserveringen weer.
     */
    public function index(): void
    {
        $data = $this->reservations->getAll();
        View::render('Admin/reservations.php', [
            'title' => 'Beheer Reserveringen',
            'reservations' => $data
        ]);
    }

    /**
     * ADMIN: Verwijdert een reservering.
     */
    public function delete(int $id): void
    {
        $this->reservations->delete($id);
        Flash::set('success', 'Reservering is verwijderd.');
        header('Location: /admin/reservations');
        exit;
    }
}