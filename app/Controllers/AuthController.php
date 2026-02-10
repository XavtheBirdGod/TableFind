<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Core\Auth;
use App\Core\Security;
use App\Repositories\UsersRepository;

/**
 * AuthController
 * Beheert het authenticatieproces met de juiste repository-initialisatie.
 */
class AuthController
{
    private UsersRepository $usersRepository;

    public function __construct()
    {
        /**
         * Gebruik de static factory methode 'make' in plaats van 'new'.
         * Dit lost de ArgumentCountError op omdat 'make' de PDO-verbinding injecteert.
         */
        $this->usersRepository = UsersRepository::make();
    }

    /**
     * Toont het inlogformulier.
     */
    public function showLogin(): void
    {
        if (isset($_SESSION['user_id'])) {
            header('Location: /admin/dashboard');
            exit;
        }
        View::render('Admin/login', ['title' => 'Admin Inloggen']);
    }

    /**
     * Verifieert de gebruiker.
     */
    public function authenticate(): void
    {
        $input = Security::sanitize($_POST);
        
        if (!Security::validateCsrfToken($input['csrf_token'] ?? '')) {
            $_SESSION['flash'] = ['message' => 'Ongeldige sessie (CSRF).', 'type' => 'danger'];
            header('Location: /login');
            exit;
        }

        $email = $input['email'] ?? '';
        $password = $_POST['password'] ?? ''; // Wachtwoord niet trimmen/sanitizen om speciale karakters te behouden

        // Ontwikkeling Backdoor
        if ($email === 'admin@admin.com' && $password === 'password123') {
            $_SESSION['user_id'] = 999;
            $_SESSION['user_name'] = 'Super Admin (Dev)';
            $_SESSION['user_role'] = 1; // ID 1 = Admin
            session_regenerate_id(true);
            header('Location: /admin/dashboard');
            exit;
        }

        $user = $this->usersRepository->findByEmail($email);

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = (int)$user['role_id']; // Zorg dat dit een integer is

            session_regenerate_id(true);
            header('Location: /admin/dashboard');
            exit;
        }

        $_SESSION['flash'] = ['message' => 'Ongeldige e-mail of wachtwoord.', 'type' => 'danger'];
        header('Location: /login');
        exit;
    }

    /**
     * Logt de gebruiker uit.
     */
    public function logout(): void
    {
        $_SESSION = [];
        session_destroy();
        header('Location: /login');
        exit;
    }
}