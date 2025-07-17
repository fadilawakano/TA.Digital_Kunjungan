<?php

namespace App\Http\Controllers\Biologi;

use App\Http\Controllers\Controller;
use App\Models\Kunjungan;
use Illuminate\Support\Carbon;

class DashboardBiologiController extends Controller
{
    public function index()
{
    $startOfMonth = Carbon::now()->startOfMonth()->toDateString();
    $endOfMonth = Carbon::now()->endOfMonth()->toDateString();

    return view('biologi.dashboard', [
        'muridHariIni' => Kunjungan::where('role_tujuan', 'biologi')
            ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
            ->whereHas('user', fn ($q) => $q->where('role', 'murid'))
            ->count(),

        'guruHariIni' => Kunjungan::where('role_tujuan', 'biologi')
            ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
            ->whereHas('user', fn ($q) => $q->where('role', 'guru'))
            ->count(),

        'statusVerifikasi' => [
            'murid_berhasil' => Kunjungan::where('role_tujuan', 'biologi')
                ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
                ->whereHas('user', fn ($q) => $q->where('role', 'murid'))
                ->whereNotNull('verifikasi_petugas')
                ->where('status_verifikasi', 'berhasil')
                ->count(),

            'murid_rusak' => Kunjungan::where('role_tujuan', 'biologi')
                ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
                ->whereHas('user', fn ($q) => $q->where('role', 'murid'))
                ->whereNotNull('verifikasi_petugas')
                ->where('status_verifikasi', 'kerusakan')
                ->count(),

            'guru_berhasil' => Kunjungan::where('role_tujuan', 'biologi')
                ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
                ->whereHas('user', fn ($q) => $q->where('role', 'guru'))
                ->whereNotNull('verifikasi_petugas')
                ->where('status_verifikasi', 'berhasil')
                ->count(),

            'guru_rusak' => Kunjungan::where('role_tujuan', 'biologi')
                ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
                ->whereHas('user', fn ($q) => $q->where('role', 'guru'))
                ->whereNotNull('verifikasi_petugas')
                ->where('status_verifikasi', 'kerusakan')
                ->count(),
        ],
    ]);
}
}