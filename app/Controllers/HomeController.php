<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\User;

class HomeController extends Controller
{
    public function index(): void
    {
        $user = null;

        if (Auth::check()) {
            $user = User::findHeaderUserById((int) Auth::userId());
            if (!$user) {
                Auth::logout();
                $this->redirect('/login');
            }

            $_SESSION['is_admin'] = (bool) ($user['admin'] ?? false);
        }

        $this->render('home/index', [
            'user' => $user,
        ]);
    }
}
