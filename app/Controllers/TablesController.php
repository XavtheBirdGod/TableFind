<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Core\Auth;
use App\Core\Flash;
use App\Core\Security;
use App\Repositories\TablesRepository;

/**
 * TablesController
 * Beheert de configuratie en status van de restauranttafels.
 */
class TablesController
{
    private TablesRepository $tablesRepository;

    public function __construct()
    {
        // Beveiliging: Alleen geautoriseerde admins kunnen tafels beheren
        Auth::requireLogin();
        if (!Auth::isAdmin()) {
            Flash::set('Onvoldoende rechten. Alleen admins hebben toegang.', 'danger');
            header('Location: /admin/dashboard');
            exit;
        }
        $this->tablesRepository = TablesRepository::make();
    }

    /**
     * Toont het overzicht van alle beschikbare tafels.
     */
    public function index(): void
    {
        $tables = $this->tablesRepository->getAll();
        View::render('Admin/tables/index', [
            'title'  => 'Tafelbeheer',
            'tables' => $tables
        ]);
    }

    /**
     * Toont het formulier om een nieuwe tafel aan te maken.
     */
    public function create(): void
    {
        View::render('Admin/tables/create', [
            'title' => 'Nieuwe Tafel Toevoegen'
        ]);
    }

    /**
     * Verwerkt het opslaan van een nieuwe tafel.
     */
    public function store(): void
    {
        $input = Security::sanitize($_POST);

        if (!Security::validateCsrfToken($input['csrf_token'] ?? '')) {
            Flash::set('Ongeldige sessie (CSRF).', 'danger');
            header('Location: /admin/tables/create');
            exit;
        }

        $tableNumber = trim($input['table_number'] ?? '');
        $capacity = (int)($input['capacity'] ?? 0);
        $status = $input['status'] ?? 'available';

        $errors = [];
        if (empty($tableNumber)) {
            $errors[] = 'Tafelnummer is verplicht.';
        }
        if ($capacity < 1) {
            $errors[] = 'Capaciteit moet minimaal 1 zijn.';
        }
        if (!in_array($status, ['available', 'occupied', 'out_of_order'])) {
             $errors[] = 'Ongeldige status.';
        }

        if (!empty($errors)) {
            Flash::set(implode('<br>', $errors), 'danger');
            header('Location: /admin/tables/create');
            exit;
        }

        $data = [
            'table_number' => $tableNumber,
            'capacity'     => $capacity,
            'status'       => $status
        ];

        if ($this->tablesRepository->create($data)) {
            Flash::set('Tafel succesvol toegevoegd.', 'success');
        } else {
            Flash::set('Fout bij het toevoegen van de tafel.', 'danger');
        }

        header('Location: /admin/tables');
        exit;
    }

    /**
     * Toont het bewerkingsformulier voor een specifieke tafel.
     */
    public function edit(int $id): void
    {
        $table = $this->tablesRepository->find($id);
        if (!$table) {
            Flash::set('Tafel niet gevonden.', 'warning');
            header('Location: /admin/tables');
            exit;
        }

        View::render('Admin/tables/edit', [
            'title' => 'Tafel Bewerken',
            'table' => $table
        ]);
    }

    /**
     * Werkt de gegevens van een bestaande tafel bij.
     */
    public function update(int $id): void
    {
        $input = Security::sanitize($_POST);

        if (!Security::validateCsrfToken($input['csrf_token'] ?? '')) {
            Flash::set('Ongeldige sessie (CSRF).', 'danger');
            header('Location: /admin/tables/edit/' . $id);
            exit;
        }

        $tableNumber = trim($input['table_number'] ?? '');
        $capacity = (int)($input['capacity'] ?? 0);
        $status = $input['status'] ?? 'available';

        $errors = [];
        if (empty($tableNumber)) {
            $errors[] = 'Tafelnummer is verplicht.';
        }
        if ($capacity < 1) {
            $errors[] = 'Capaciteit moet minimaal 1 zijn.';
        }
        if (!in_array($status, ['available', 'occupied', 'out_of_order'])) {
             $errors[] = 'Ongeldige status.';
        }

        if (!empty($errors)) {
            Flash::set(implode('<br>', $errors), 'danger');
            header('Location: /admin/tables/edit/' . $id);
            exit;
        }

        $data = [
            'table_number' => $tableNumber,
            'capacity'     => $capacity,
            'status'       => $status
        ];

        if ($this->tablesRepository->update($id, $data)) {
            Flash::set('Tafel succesvol bijgewerkt.', 'success');
        } else {
            Flash::set('Er is een fout opgetreden bij de update.', 'danger');
        }

        header('Location: /admin/tables');
        exit;
    }

    /**
     * Verwijdert een tafel uit het systeem.
     */
    public function destroy(int $id): void
    {
        if (!Security::validateCsrfToken($_POST['csrf_token'] ?? '')) {
            Flash::set('Ongeldige sessie (CSRF).', 'danger');
            header('Location: /admin/tables');
            exit;
        }

        if ($this->tablesRepository->delete($id)) {
            Flash::set('Tafel succesvol verwijderd.', 'success');
        } else {
            Flash::set('Fout bij het verwijderen van de tafel.', 'danger');
        }

        header('Location: /admin/tables');
        exit;
    }
}