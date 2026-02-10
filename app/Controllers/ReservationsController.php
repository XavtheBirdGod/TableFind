<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Flash;
use App\Core\View;
use App\Repositories\ReservationsRepository;
use App\Repositories\TablesRepository;

/**
 * ReservationsController
 * Regelt de flow van reserveringsgegevens tussen de gebruiker en de database.
 */
final class ReservationsController
{
    private ReservationsRepository $reservations;

    public function __construct()
    {
        $this->reservations = ReservationsRepository::make();
    }

    /**
     * Geeft de hoofdpagina met alle reserveringen weer.
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
     * Toont het formulier voor een nieuwe reservering.
     */
    public function create(): void
    {
        $tables = TablesRepository::make()->getAll();
        View::render('Admin/reservation-create.php', [
            'title' => 'Nieuwe Boeking',
            'tables' => $tables
        ]);
    }

    /**
     * Verwerkt het opslaan van een nieuwe reservering met validatie.
     */
    public function store(): void
    {
        // Server-side validatie (Verplicht voor opdracht 2)
        $name = trim($_POST['customer_name'] ?? '');
        $date = $_POST['reservation_date'] ?? '';

        if (empty($name) || empty($date)) {
            Flash::set('error', 'Naam en datum zijn verplicht in te vullen.');
            header('Location: /reservations/create');
            exit;
        }

        $this->reservations->create([
            'customer_name'    => $name,
            'customer_email'   => $_POST['customer_email'],
            'customer_phone'   => $_POST['customer_phone'],
            'guest_count'      => (int)$_POST['guest_count'],
            'reservation_date' => $date,
            'reservation_time' => $_POST['reservation_time'],
            'table_id'         => !empty($_POST['table_id']) ? (int)$_POST['table_id'] : null,
            'status'           => 'confirmed',
            'notes'            => $_POST['notes'] ?? ''
        ]);

        Flash::set('success', 'De reservering is succesvol opgeslagen.');
        header('Location: /reservations');
        exit;
    }

    /**
     * Verwijdert een reservering uit de lijst.
     */
    public function delete(int $id): void
    {
        $this->reservations->delete($id);
        Flash::set('success', 'Reservering is succesvol verwijderd.');
        header('Location: /reservations');
        exit;
    }
}