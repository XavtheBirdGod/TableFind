<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Core\Auth;
use App\Core\Flash;
use App\Repositories\UsersRepository;

/**
 * UsersController
 * Beheert de systeemgebruikers en hun rollen binnen het platform.
 */
class UsersController
{
    private UsersRepository $usersRepository;

    public function __construct()
    {
        Auth::requireLogin();
        // Alleen admins mogen gebruikers beheren
        if (!Auth::isAdmin()) {
            Flash::set('Toegang geweigerd.', 'danger');
            header('Location: /admin/dashboard');
            exit;
        }
        $this->usersRepository = UsersRepository::make();
    }

    /**
     * Toont de lijst met alle actieve gebruikers.
     */
    public function index(): void
    {
        $users = $this->usersRepository->getAll(); // Zorg dat deze methode bestaat in je Repo
        View::render('Admin/users', [
            'title' => 'Gebruikersbeheer',
            'users' => $users
        ]);
    }
}