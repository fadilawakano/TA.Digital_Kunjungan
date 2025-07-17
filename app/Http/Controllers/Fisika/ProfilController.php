<?php

namespace App\Http\Controllers\Fisika;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ProfilController extends Controller
{
    public function edit()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        return view('fisika.profil', compact('user'));
    }

    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
           // 'username' => 'required|string|unique:users,username,' . $user->id,
            'password' => 'nullable|min:6|confirmed',
        ]);

        try {
            //$user->username = $request->username;

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->save();

            return back()->with('success', 'Profil berhasil diperbarui.');

        } catch (\Exception $e) {
            // Untuk produksi sebaiknya pakai log, bukan dd()
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
