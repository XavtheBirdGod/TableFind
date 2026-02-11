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
 * Deze controller zorgt voor de validatie en interactie tussen de views en repositories.
 */
final class ReservationsController
{
    private ReservationsRepository $reservations;
    private TablesRepository $tables;

    /**
     * Constructor initialiseert de benodigde repositories en controleert de toegangsrechten.
     */
    public function __construct()
    {
        // Beveiliging: Controleer of de gebruiker admin-rechten heeft voor admin-routes
        if (str_contains($_SERVER['REQUEST_URI'], '/admin')) {
            if (!Auth::isAdmin()) {
                Flash::set('Toegang geweigerd. U moet ingelogd zijn als admin.', 'danger');
                header('Location: /login');
                exit;
            }
        }

        /**
         * Initialisatie van repositories via de static factory methode.
         */
        $this->reservations = ReservationsRepository::make();
        $this->tables = TablesRepository::make();
    }

    /**
     * Toont de hoofdpagina van de website.
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
        // Haal alle reserveringen op via de repository
        $data = $this->reservations->getAll();
        View::render('Admin/reservations', [
            'title' => 'Reserveringen Beheren',
            'reservations' => $data
        ]);
    }

    /**
     * Toont het formulier om een bestaande reservering te bewerken.
     * FIX: Gebruikt nu de juiste methode van TablesRepository.
     */
    public function edit(int $id): void
    {
        $reservation = $this->reservations->find($id);
        if (!$reservation) {
            Flash::set('Reservering niet gevonden.', 'warning');
            header('Location: /admin/reservations');
            exit;
        }

        /**
         * We halen alle tafels op om ze weer te geven in de dropdown voor toewijzing.
         * De methode 'getAll' moet aanwezig zijn in TablesRepository.
         */
        View::render('Admin/reservation-edit', [
            'title'   => 'Reservering Bewerken',
            'res'     => $reservation,
            'tables'  => $this->tables->getAll() 
        ]);
    }

    /**
     * Verwerkt de update van een bestaande reservering na validatie.
     */
    public function update(int $id): void
    {
        // CSRF-beveiliging validatie
        if (!Security::validateCsrfToken($_POST['csrf_token'] ?? '')) {
            Flash::set('Ongeldige sessie (CSRF).', 'danger');
            header('Location: /admin/reservations/edit/' . $id);
            exit;
        }

        // Validatie van de invoervelden
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

        // Als er fouten zijn, stuur de gebruiker terug met een foutmelding
        if (!empty($errors)) {
            Flash::set(implode('<br>', $errors), 'danger');
            header('Location: /admin/reservations/edit/' . $id);
            exit;
        }

        // Voorbereiden van data voor de repository
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

        // Voer de update uit en geef feedback aan de gebruiker
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
     * Toont het publieke reserveringsformulier voor gasten.
     */
    public function book(): void
    {
        View::render('Public/reservations/book', [
            'title' => 'Maak een Reservering'
        ]);
    }

    /**
     * Verwerkt een nieuwe reservering geplaatst via de publieke website.
     */
    public function publicStore(): void
    {
        if (!Security::validateCsrfToken($_POST['csrf_token'] ?? '')) {
            Flash::set('Ongeldige sessie (CSRF).', 'danger');
            header('Location: /book');
            exit;
        }

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
            header('Location: /success');
        } else {
            Flash::set('Er is iets misgegaan. Probeer het later opnieuw.', 'danger');
            header('Location: /book');
        }
        exit;
    }

    /**
     * Toont de succes-bevestigingspagina na een reservering.
     */
    public function success(): void
    {
        View::render('Public/reservations/reservation-success', [
            'title' => 'Reservering Bevestigd'
        ]);
    }
}