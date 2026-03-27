<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin(): void
    {
        if (Auth::check()) {
            $this->redirect('/');
        }

        $this->render('auth/login', [
            'isInvalid' => false,
            'email' => '',
        ]);
    }

    public function login(): void
    {
        $email = isset($_POST['email']) ? trim((string) $_POST['email']) : '';
        $password = isset($_POST['password']) ? (string) $_POST['password'] : '';
        $user = User::findByEmail($email);

        if ($user && password_verify($password, $user['passwort'])) {
            Auth::login((int) $user['ID'], (bool) $user['admin']);
            $this->redirect('/');
        }

        $this->render('auth/login', [
            'isInvalid' => true,
            'email' => $email,
        ]);
    }

    public function logout(): void
    {
        Auth::logout();
        $this->redirect('/?message=' . urlencode('Successfully logged out'));
    }
}
