<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\BidangStudi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
{
    $query = User::query();

    if ($request->has('search') && $request->search != '') {
        $query->where(function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->search . '%')
              ->orWhere('username', 'like', '%' . $request->search . '%');
        });
    }

    $users = $query->latest()->paginate(8)->withQueryString(); // Menyimpan query search saat paginate
    return view('admin.users.index', compact('users'));
}

    public function create()
    {
        $bidangStudis = BidangStudi::all(); // Ambil semua bidang studi
        return view('admin.users.create', compact('bidangStudis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,murid,guru,perpustakaan,biologi,fisika,kimia',
            'kelas'    => 'nullable|string|max:100',
            'bidang_studi' => 'nullable|array',
            'bidang_studi.*' => 'exists:bidang_studi,id'
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'kelas' => $request->kelas,
        ]);

        // Jika guru, simpan bidang studi ke pivot
        if ($request->role === 'guru' && $request->has('bidang_studi')) {
            $user->bidangStudis()->sync($request->bidang_studi);
        }

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $bidangStudis = BidangStudi::all();
        $selectedBidangStudi = $user->bidangStudis->pluck('id')->toArray();

        return view('admin.users.edit', compact('user', 'bidangStudis', 'selectedBidangStudi'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'username' => 'required|string|unique:users,username,' . $user->id,
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:admin,murid,guru,perpustakaan,biologi,fisika,kimia',
            'kelas'    => 'nullable|string|max:100',
            'bidang_studi' => 'nullable|array',
            'bidang_studi.*' => 'exists:bidang_studi,id'
        ]);

        $user->username = $request->username;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->role = $request->role;
        $user->kelas    = $request->kelas;
        $user->save();

        // Sinkronisasi bidang studi jika role guru
        if ($request->role === 'guru') {
            $user->bidangStudis()->sync($request->bidang_studi ?? []);
        } else {
            $user->bidangStudis()->detach(); // Kosongkan jika bukan guru
        }

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->bidangStudis()->detach(); // Hapus relasi bidang studi dulu
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }
}
