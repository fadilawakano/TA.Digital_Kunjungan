<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends Controller
{
    // Tampilkan form lupa password
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    // Proses pengecekan username
    public function checkUsername(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user) {
            return back()->withErrors(['username' => 'Username tidak ditemukan.']);
        }

        // Redirect ke halaman reset password
        return redirect()->route('password.reset', ['username' => $user->username]);
    }

    // Tampilkan form untuk reset password
   public function showResetForm($username)
{
    $user = User::where('username', $username)->firstOrFail();
    return view('auth.reset-password', ['username' => $user->username]);

}

    // Proses simpan password baru
    public function updatePassword(Request $request, $username)
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::where('username', $username)->firstOrFail();
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('login')->with('success', 'Password berhasil diubah.');
    }
}
