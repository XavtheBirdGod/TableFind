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
         * Dit injecteert de PDO-verbinding correct in de repository.
         */
        $this->usersRepository = UsersRepository::make();
    }

    /**
     * Toont het inlogformulier aan de gebruiker.
     */
    public function showLogin(): void
    {
        // Controleer of de gebruiker al is ingelogd
        if (isset($_SESSION['user_id'])) {
            header('Location: /admin/dashboard');
            exit;
        }
        View::render('Admin/login', ['title' => 'Admin Inloggen']);
    }

    /**
     * Verifieert de gebruikersgegevens tegen de database.
     */
    public function authenticate(): void
    {
        // Sanitize invoergegevens om XSS te voorkomen
        $input = Security::sanitize($_POST);
        
        // Valideer CSRF-token voor beveiliging tegen cross-site request forgery
        if (!Security::validateCsrfToken($input['csrf_token'] ?? '')) {
            $_SESSION['flash'] = ['message' => 'Ongeldige sessie (CSRF).', 'type' => 'danger'];
            header('Location: /login');
            exit;
        }

        $email = $input['email'] ?? '';
        $password = $_POST['password'] ?? ''; // Wachtwoord niet trimmen om integriteit te behouden

        /**
         * Zoek de gebruiker in de database via de repository.
         * De hardcoded backdoor is verwijderd voor betere beveiliging.
         */
        $user = $this->usersRepository->findByEmail($email);

        // Verifieer wachtwoord met de PHP password_verify functie
        if ($user && password_verify($password, $user['password_hash'])) {
            // Sla essentiële gebruikersgegevens op in de sessie
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = (int)$user['role_id']; 

            // Regenereer sessie-ID om session fixation aanvallen te voorkomen
            session_regenerate_id(true);
            header('Location: /admin/dashboard');
            exit;
        }

        // Foutmelding bij mislukte inlogpoging
        $_SESSION['flash'] = ['message' => 'Ongeldige e-mail of wachtwoord.', 'type' => 'danger'];
        header('Location: /login');
        exit;
    }

    /**
     * Logt de gebruiker uit en vernietigt de sessie.
     */
    public function logout(): void
    {
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        header('Location: /login');
        exit;
    }
}