<?php

namespace App\Http\Controllers\Murid;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kunjungan;
use Illuminate\Support\Facades\Auth;

class StatusVerifikasiController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $biologi = Kunjungan::where('user_id', $userId)->where('role_tujuan', 'biologi')->get();
        $fisika = Kunjungan::where('user_id', $userId)->where('role_tujuan', 'fisika')->get();
        $kimia = Kunjungan::where('user_id', $userId)->where('role_tujuan', 'kimia')->get();
        $perpustakaan = Kunjungan::where('user_id', $userId)->where('role_tujuan', 'perpustakaan')->get();

        return view('murid.status-verifikasi', compact('biologi', 'fisika', 'kimia', 'perpustakaan'));
    }

    // Tambahan: filter perpustakaan berdasarkan tipe dan waktu
    public function filter(Request $request)
    {
        $userId = Auth::id();

        $biologi = Kunjungan::where('user_id', $userId)->where('role_tujuan', 'biologi')->get();
        $fisika = Kunjungan::where('user_id', $userId)->where('role_tujuan', 'fisika')->get();
        $kimia = Kunjungan::where('user_id', $userId)->where('role_tujuan', 'kimia')->get();

        $perpustakaan = Kunjungan::where('user_id', $userId)->where('role_tujuan', 'perpustakaan');

        if ($request->filled('tipe')) {
            $perpustakaan->where('tipe', $request->tipe);
        }

        if ($request->filled('waktu')) {
            if ($request->waktu === 'harian') {
                $perpustakaan->whereDate('tanggal', now()->toDateString());
            } elseif ($request->waktu === 'mingguan') {
                $perpustakaan->whereBetween('tanggal', [now()->startOfWeek(), now()->endOfWeek()]);
            } elseif ($request->waktu === 'bulanan') {
                $perpustakaan->whereMonth('tanggal', now()->month);
            }
        }

        $perpustakaan = $perpustakaan->latest()->get();

        return view('murid.status-verifikasi', compact('biologi', 'fisika', 'kimia', 'perpustakaan'));
    }
}
