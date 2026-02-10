<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Flash;
use App\Core\View;
use App\Core\Auth;
use App\Repositories\ReservationsRepository;
use App\Repositories\TablesRepository;

/**
 * ReservationsController
 * Beheert de reserveringsflow voor zowel klanten als beheerders.
 * Dit omvat het bekijken, bewerken, bijwerken en verwijderen van reserveringen.
 */
final class ReservationsController
{
    private ReservationsRepository $reservations;
    private TablesRepository $tables;

    /**
     * Constructor initialiseert de repositories en controleert de toegang.
     * Hier wordt gecontroleerd of de gebruiker geautoriseerd is voor admin-routes.
     */
    public function __construct()
    {
        // Beveiliging: Alleen toegankelijk voor admins in het admin-gedeelte
        if (str_contains($_SERVER['REQUEST_URI'], '/admin')) {
            if (!Auth::isAdmin()) {
                Flash::set('Toegang geweigerd. U moet ingelogd zijn als admin.', 'danger');
                header('Location: /login');
                exit;
            }
        }

        $this->reservations = ReservationsRepository::make();
        $this->tables = TablesRepository::make();
    }

    /**
     * Toont alle reserveringen in het administratiepaneel.
     */
    public function index(): void
    {
        $data = $this->reservations->getAll();
        View::render('Admin/reservations', [
            'title' => 'Reserveringen Beheren',
            'reservations' => $data
        ]);
    }

    /**
     * Toont het bewerkingsformulier voor een specifieke reservering op basis van ID.
     */
    public function edit(int $id): void
    {
        $reservation = $this->reservations->find($id);
        if (!$reservation) {
            Flash::set('Reservering niet gevonden.', 'warning');
            header('Location: /admin/reservations');
            exit;
        }

        View::render('Admin/reservation-edit', [
            'title' => 'Reservering Bewerken',
            'res' => $reservation,
            'tables' => $this->tables->getAll() // Nodig voor de tafel selectiebox
        ]);
    }

    /**
     * Verwerkt de update van een bestaande reservering.
     */
    public function update(int $id): void
    {
        $data = [
            'customer_name'    => $_POST['customer_name'] ?? '',
            'customer_email'   => $_POST['customer_email'] ?? '',
            'customer_phone'   => $_POST['customer_phone'] ?? '',
            'guest_count'      => (int)($_POST['guest_count'] ?? 1),
            'reservation_date' => $_POST['reservation_date'] ?? '',
            'reservation_time' => $_POST['reservation_time'] ?? '',
            'table_id'         => !empty($_POST['table_id']) ? (int)$_POST['table_id'] : null,
            'status'           => $_POST['status'] ?? 'pending',
            'notes'            => $_POST['notes'] ?? ''
        ];

        // Voer de update uit via de repository
        if ($this->reservations->update($id, $data)) {
            Flash::set('Reservering succesvol bijgewerkt.', 'success');
        } else {
            Flash::set('Er is een fout opgetreden bij het bijwerken.', 'danger');
        }

        header('Location: /admin/reservations');
        exit;
    }

    /**
     * Verwijdert een reservering uit het systeem.
     */
    public function destroy(int $id): void
    {
        if ($this->reservations->delete($id)) {
            Flash::set('Reservering succesvol verwijderd.', 'success');
        } else {
            Flash::set('Fout bij het verwijderen van de reservering.', 'danger');
        }
        
        header('Location: /admin/reservations');
        exit;
    }

    /**
     * Toont het reserveringsformulier aan de publieke zijde voor gasten.
     */
    public function book(): void
    {
        View::render('Public/reservations/book', [
            'title' => 'Maak een Reservering'
        ]);
    }

    /**
     * Verwerkt een nieuwe reservering geplaatst door een externe klant.
     */
    public function publicStore(): void
    {
        $result = $this->reservations->create([
            'customer_name'    => $_POST['customer_name'] ?? '',
            'customer_phone'   => $_POST['customer_phone'] ?? '',
            'guest_count'      => (int)($_POST['guest_count'] ?? 1),
            'reservation_date' => $_POST['reservation_date'] ?? date('Y-m-d'),
            'reservation_time' => $_POST['reservation_time'] ?? '18:00',
            'status'           => 'pending'
        ]);

        if ($result) {
            Flash::set('Uw reservering is succesvol geplaatst!', 'success');
        }

        header('Location: /success');
        exit;
    }
    /**
     * Toont de succes-pagina na een geslaagde reservering.
     */
    public function success(): void
    {
        View::render('Public/reservations/reservation-success', [
            'title' => 'Reservering Bevestigd'
        ]);
    }
}