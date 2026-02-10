<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Core\Auth;
use App\Core\Flash;
use App\Core\Security;
use App\Repositories\UsersRepository;

/**
 * UsersController
 * Beheert de profielinstellingen en gebruikersinformatie.
 */
class UsersController
{
    private UsersRepository $usersRepository;

    public function __construct()
    {
        Auth::requireLogin();
        $this->usersRepository = UsersRepository::make();
    }

    /**
     * Toont het profiel van de ingelogde gebruiker.
     * Bevat speciale logica voor de "Dev Backdoor" gebruiker (ID 999).
     */
    public function profile(): void
    {
        $userId = (int)($_SESSION['user_id'] ?? 0);

        // Dev Backdoor Afhandeling: Gebruiker bestaat niet in DB
        if ($userId === 999) {
            $user = [
                'id'    => 999,
                'name'  => $_SESSION['user_name'] ?? 'Super Admin (Dev)',
                'email' => 'admin@admin.com'
            ];
        } else {
            $user = $this->usersRepository->findById($userId);
        }

        if (!$user) {
            Flash::set('Gebruiker niet gevonden in het systeem.', 'danger');
            header('Location: /admin/dashboard');
            exit;
        }

        View::render('Admin/profile', ['title' => 'Mijn Profiel', 'user' => $user]);
    }

    /**
     * Verwerkt het formulier voor profielaanpassingen.
     * Blokkeert wijzigingen voor de Dev Backdoor gebruiker.
     */
    public function updateProfile(): void
    {
        $userId = (int)($_SESSION['user_id'] ?? 0);

        if (!Security::validateCsrfToken($_POST['csrf_token'] ?? '')) {
            Flash::set('Ongeldige sessie (CSRF).', 'danger');
            header('Location: /admin/profile');
            exit;
        }

        // Beveiliging: Dev account mag niet worden gewijzigd
        if ($userId === 999) {
            Flash::set('Het ontwikkelaccount kan niet worden gewijzigd.', 'warning');
            header('Location: /admin/profile');
            exit;
        }

        $name  = trim(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
        $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) ?? '');
        $pass  = $_POST['password'] ?? '';

        if (empty($name) || empty($email)) {
            Flash::set('Naam en e-mailadres zijn verplicht.', 'danger');
            header('Location: /admin/profile');
            exit;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Flash::set('Ongeldig e-mailadres.', 'danger');
            header('Location: /admin/profile');
            exit;
        }

        // Controleer of het e-mailadres al in gebruik is door een ander
        $existing = $this->usersRepository->findByEmail($email);
        if ($existing && (int)$existing['id'] !== $userId) {
            Flash::set('Dit e-mailadres is al in gebruik.', 'danger');
            header('Location: /admin/profile');
            exit;
        }

        // Wachtwoord alleen hashen als het is ingevuld
        $passwordHash = !empty($pass) ? password_hash($pass, PASSWORD_DEFAULT) : null;

        if ($this->usersRepository->updateProfile($userId, $email, $name, $passwordHash)) {
            // Update de sessie naam zodat deze direct zichtbaar is
            $_SESSION['user_name'] = $name;
            Flash::set('Profiel succesvol bijgewerkt.', 'success');
        } else {
            Flash::set('Er is een fout opgetreden bij het opslaan.', 'danger');
        }

        header('Location: /admin/profile');
        exit;
    }
}