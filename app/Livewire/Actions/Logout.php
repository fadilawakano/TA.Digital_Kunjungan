<?php

namespace App\Livewire\Actions;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Logout
{
    /**
     * Log the current user out of the application.
     */
    public function __invoke()
    {
        Auth::guard('web')->logout();

        Session::invalidate();
        Session::regenerateToken();

        // Hapus URL tujuan terakhir (biar nggak diarahkan ke halaman user sebelumnya)
        Session::forget('url.intended');

        return redirect('/login'); // langsung ke login biar jelas
    }
}
