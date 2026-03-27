<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\User;

class ProfileController extends Controller
{
    public function show(): void
    {
        Auth::requireLogin();

        $user = User::findProfileById((int) Auth::userId());
        if (!$user) {
            Auth::logout();
            $this->redirect('/login');
        }

        $this->render('profile/show', [
            'user' => $user,
        ]);
    }
}
