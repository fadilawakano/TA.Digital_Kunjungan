<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class NewPasswordController extends Controller
{
    // Tampilkan form reset password
    public function create($username)
    {
        // Kirim variabel $username ke view
        return view('auth.reset-password', compact('username'));
    }

    // Proses update password
    public function update(Request $request, $username)
    {
        $request->validate([
            'password' => 'required|confirmed|min:6',
        ]);

        $user = User::where('username', $username)->first();

        if (!$user) {
            return redirect()->route('password.reset', $username)
                ->withErrors(['username' => 'User tidak ditemukan.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('login')->with('success', 'Password berhasil diubah.');
    }
}
