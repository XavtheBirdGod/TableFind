<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Core\Flash;
use App\Core\Security;
use App\Repositories\TablesRepository;

/**
 * TablesController
 * Beheert alle acties voor tafels in het administratiepaneel.
 * Omvat het maken, bekijken, bewerken en verwijderen van tafels.
 */
class TablesController
{
    private TablesRepository $tablesRepository;

    public function __construct()
    {
        /**
         * Initialiseer de repository via de static factory methode.
         */
        $this->tablesRepository = TablesRepository::make();
    }

    /**
     * Toont de lijst met alle tafels.
     */
    public function index(): void
    {
        $tables = $this->tablesRepository->all();
        View::render('Admin/Tables/index', [
            'title' => 'Tafelbeheer',
            'tables' => $tables
        ]);
    }

    /**
     * Toont het formulier om een nieuwe tafel aan te maken.
     * Gekoppeld aan GET /admin/tables/create
     */
    public function create(): void
    {
        View::render('Admin/Tables/create', [
            'title' => 'Nieuwe Tafel Toevoegen'
        ]);
    }

    /**
     * Verwerkt het opslaan van een nieuwe tafel.
     * Gekoppeld aan POST /admin/tables/store
     */
    public function store(): void
    {
        if (!Security::validateCsrfToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['flash'] = ['message' => 'Ongeldige CSRF-token', 'type' => 'danger'];
            header('Location: /admin/tables/create');
            exit;
        }

        $input = Security::sanitize($_POST);
        $tableNumber = $input['table_number'] ?? '';
        $capacity = (int)($input['capacity'] ?? 2);
        $status = $input['status'] ?? 'available';

        if (empty($tableNumber)) {
            $_SESSION['flash'] = ['message' => 'Tafelnummer is verplicht.', 'type' => 'warning'];
            header('Location: /admin/tables/create');
            exit;
        }

        // Voeg hier de logica toe om de tafel op te slaan via de repository
        $this->tablesRepository->create([
            'table_number' => $tableNumber,
            'capacity' => $capacity,
            'status' => $status
        ]);

        $_SESSION['flash'] = ['message' => 'Tafel succesvol toegevoegd.', 'type' => 'success'];
        header('Location: /admin/tables');
        exit;
    }

    /**
     * Toont het formulier om een bestaande tafel te bewerken.
     * Gekoppeld aan GET /admin/tables/edit/{id}
     */
    public function edit(int $id): void
    {
        $table = $this->tablesRepository->find($id);
        
        if (!$table) {
            $_SESSION['flash'] = ['message' => 'Tafel niet gevonden.', 'type' => 'danger'];
            header('Location: /admin/tables');
            exit;
        }

        View::render('Admin/Tables/edit', [
            'title' => 'Tafel Bewerken',
            'table' => $table
        ]);
    }

    /**
     * Verwerkt de update van een bestaande tafel.
     * Gekoppeld aan POST /admin/tables/update/{id}
     */
    public function update(int $id): void
    {
        if (!Security::validateCsrfToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['flash'] = ['message' => 'Ongeldige CSRF-token', 'type' => 'danger'];
            header('Location: /admin/tables/edit/' . $id);
            exit;
        }

        $input = Security::sanitize($_POST);
        $tableNumber = $input['table_number'] ?? '';
        $capacity = (int)($input['capacity'] ?? 2);
        $status = $input['status'] ?? 'available';
        
        // Logica voor het bijwerken van de tafel...
        $this->tablesRepository->update($id, [
            'table_number' => $tableNumber,
            'capacity' => $capacity,
            'status' => $status
        ]);

        $_SESSION['flash'] = ['message' => 'Tafel succesvol bijgewerkt.', 'type' => 'success'];
        header('Location: /admin/tables');
        exit;
    }

    /**
     * Verwijdert een tafel uit het systeem.
     * Gekoppeld aan POST /admin/tables/delete/{id}
     */
    public function destroy(int $id): void
    {
        if (!Security::validateCsrfToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['flash'] = ['message' => 'Ongeldige CSRF-token', 'type' => 'danger'];
            header('Location: /admin/tables');
            exit;
        }

        // Logica voor het verwijderen van de tafel...
        $this->tablesRepository->delete($id);

        $_SESSION['flash'] = ['message' => 'Tafel succesvol verwijderd.', 'type' => 'success'];
        header('Location: /admin/tables');
        exit;
    }
}