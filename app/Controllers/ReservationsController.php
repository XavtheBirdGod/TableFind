<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Flash;
use App\Core\View;
use App\Core\Auth;
use App\Core\Security;
use App\Repositories\ReservationsRepository;
use App\Repositories\TablesRepository;

/**
 * ReservationsController
 * Beheert de reserveringsflow voor zowel klanten als beheerders.
 */
final class ReservationsController
{
    private ReservationsRepository $reservations;
    private TablesRepository $tables;

    /**
     * Constructor initialiseert de repositories en controleert de toegang.
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
     * Toont de hoofdpagina van de website (Landing Page).
     * FIX: Gebruikt nu de juiste View class en laadt niet langer automatisch de booking page.
     */
    public function home(): void
    {
        View::render('Public/home', [
            'title' => 'Welkom bij TableFind'
        ]);
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
     * Toont het bewerkingsformulier voor een specifieke reservering.
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
            'tables' => $this->tables->getAll()
        ]);
    }

    /**
     * Verwerkt de update van een bestaande reservering.
     */
    public function update(int $id): void
    {
        if (!Security::validateCsrfToken($_POST['csrf_token'] ?? '')) {
            Flash::set('Ongeldige sessie (CSRF).', 'danger');
            header('Location: /admin/reservations/edit/' . $id);
            exit;
        }

        // Validatie
        $errors = [];
        if (empty($_POST['customer_name'])) $errors[] = 'Klantnaam is verplicht.';
        if (empty($_POST['customer_phone'])) $errors[] = 'Telefoonnummer is verplicht.';
        if (!empty($_POST['customer_email']) && !filter_var($_POST['customer_email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Ongeldig e-mailadres.';
        }
        if ((int)($_POST['guest_count'] ?? 0) < 1) $errors[] = 'Aantal gasten moet minimaal 1 zijn.';
        if (empty($_POST['reservation_date'])) $errors[] = 'Datum is verplicht.';
        if (empty($_POST['reservation_time'])) $errors[] = 'Tijd is verplicht.';
        
        $status = $_POST['status'] ?? 'pending';
        if (!in_array($status, ['pending', 'confirmed', 'cancelled', 'completed'])) {
            $errors[] = 'Ongeldige status.';
        }

        if (!empty($errors)) {
            Flash::set(implode('<br>', $errors), 'danger');
            header('Location: /admin/reservations/edit/' . $id);
            exit;
        }

        $data = [
            'customer_name'    => $_POST['customer_name'],
            'customer_email'   => $_POST['customer_email'] ?? '',
            'customer_phone'   => $_POST['customer_phone'],
            'guest_count'      => (int)$_POST['guest_count'],
            'reservation_date' => $_POST['reservation_date'],
            'reservation_time' => $_POST['reservation_time'],
            'table_id'         => !empty($_POST['table_id']) ? (int)$_POST['table_id'] : null,
            'status'           => $status,
            'notes'            => $_POST['notes'] ?? ''
        ];

        if ($this->reservations->update($id, $data)) {
            Flash::set('Reservering succesvol bijgewerkt.', 'success');
        } else {
            Flash::set('Er is een fout opgetreden bij het bijwerken.', 'danger');
        }

        header('Location: /admin/reservations');
        exit;
    }

    /**
     * Verwijdert een reservering.
     */
    public function destroy(int $id): void
    {
        if (!Security::validateCsrfToken($_POST['csrf_token'] ?? '')) {
            Flash::set('Ongeldige sessie (CSRF).', 'danger');
            header('Location: /admin/reservations');
            exit;
        }

        if ($this->reservations->delete($id)) {
            Flash::set('Reservering succesvol verwijderd.', 'success');
        } else {
            Flash::set('Fout bij het verwijderen van de reservering.', 'danger');
        }

        header('Location: /admin/reservations');
        exit;
    }

    /**
     * Toont het reserveringsformulier voor gasten.
     */
    public function book(): void
    {
        View::render('Public/reservations/book', [
            'title' => 'Maak een Reservering'
        ]);
    }

    /**
     * Verwerkt een nieuwe publieke reservering.
     */
    public function publicStore(): void
    {
        if (!Security::validateCsrfToken($_POST['csrf_token'] ?? '')) {
            Flash::set('Ongeldige sessie (CSRF).', 'danger');
            header('Location: /book');
            exit;
        }

        // Validatie
        $errors = [];
        if (empty($_POST['customer_name'])) $errors[] = 'Naam is verplicht.';
        if (empty($_POST['customer_phone'])) $errors[] = 'Telefoonnummer is verplicht.';
        if ((int)($_POST['guest_count'] ?? 0) < 1) $errors[] = 'Aantal personen moet minimaal 1 zijn.';
        if (empty($_POST['reservation_date'])) $errors[] = 'Datum is verplicht.';
        if (empty($_POST['reservation_time'])) $errors[] = 'Tijd is verplicht.';

        if (!empty($errors)) {
            Flash::set(implode('<br>', $errors), 'danger');
            header('Location: /book');
            exit;
        }

        $result = $this->reservations->create([
            'customer_name'    => $_POST['customer_name'],
            'customer_phone'   => $_POST['customer_phone'],
            'guest_count'      => (int)$_POST['guest_count'],
            'reservation_date' => $_POST['reservation_date'],
            'reservation_time' => $_POST['reservation_time'],
            'status'           => 'pending'
        ]);

        if ($result) {
            Flash::set('Uw reservering is succesvol geplaatst!', 'success');
        }

        header('Location: /success');
        exit;
    }

    /**
     * Toont de succes-pagina.
     */
    public function success(): void
    {
        View::render('Public/reservations/reservation-success', [
            'title' => 'Reservering Bevestigd'
        ]);
    }
}