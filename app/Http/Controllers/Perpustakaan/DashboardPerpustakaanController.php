<?php

namespace App\Http\Controllers\Perpustakaan;

use App\Http\Controllers\Controller;
use App\Models\Kunjungan;
use Illuminate\Support\Carbon;

class DashboardPerpustakaanController extends Controller
{
    public function index()
    {
        $startOfMonth = Carbon::now()->startOfMonth()->toDateString();
        $endOfMonth = Carbon::now()->endOfMonth()->toDateString();

        return view('perpustakaan.dashboard', [
            'muridHariIni' => Kunjungan::where('role_tujuan', 'perpustakaan')
                ->whereIn('tipe', ['kunjungan', 'baca'])
                ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
                ->whereHas('user', fn ($q) => $q->where('role', 'murid'))
                ->count(),

            'guruHariIni' => Kunjungan::where('role_tujuan', 'perpustakaan')
                ->where('tipe', 'kunjungan')
                ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
                ->whereHas('user', fn ($q) => $q->where('role', 'guru'))
                ->count(),

            'peminjamanHariIni' => Kunjungan::where('role_tujuan', 'perpustakaan')
                ->where('tipe', 'pinjam')
                ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
                ->count(),

            'statusVerifikasi' => [
                'murid_berhasil' => Kunjungan::where('role_tujuan', 'perpustakaan')
                    ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
                    ->whereHas('user', fn ($q) => $q->where('role', 'murid'))
                    ->whereNotNull('verifikasi_petugas')
                    ->where('status_verifikasi', 'berhasil')
                    ->count(),

                'murid_rusak' => Kunjungan::where('role_tujuan', 'perpustakaan')
                    ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
                    ->whereHas('user', fn ($q) => $q->where('role', 'murid'))
                    ->whereNotNull('verifikasi_petugas')
                    ->where('status_verifikasi', 'kerusakan')
                    ->count(),

                'guru_berhasil' => Kunjungan::where('role_tujuan', 'perpustakaan')
                    ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
                    ->whereHas('user', fn ($q) => $q->where('role', 'guru'))
                    ->whereNotNull('verifikasi_petugas')
                    ->where('status_verifikasi', 'berhasil')
                    ->count(),

                'guru_rusak' => Kunjungan::where('role_tujuan', 'perpustakaan')
                    ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
                    ->whereHas('user', fn ($q) => $q->where('role', 'guru'))
                    ->whereNotNull('verifikasi_petugas')
                    ->where('status_verifikasi', 'kerusakan')
                    ->count(),
            ],
        ]);
    }
}
