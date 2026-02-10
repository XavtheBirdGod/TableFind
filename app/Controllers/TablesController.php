<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Core\Auth;
use App\Core\Flash;
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
        $data = [
            'table_number' => $_POST['table_number'] ?? '',
            'capacity'     => (int)($_POST['capacity'] ?? 2),
            'status'       => $_POST['status'] ?? 'available'
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
        $data = [
            'table_number' => $_POST['table_number'] ?? '',
            'capacity'     => (int)($_POST['capacity'] ?? 2),
            'status'       => $_POST['status'] ?? 'available'
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
        if ($this->tablesRepository->delete($id)) {
            Flash::set('Tafel succesvol verwijderd.', 'success');
        } else {
            Flash::set('Fout bij het verwijderen van de tafel.', 'danger');
        }

        header('Location: /admin/tables');
        exit;
    }
}