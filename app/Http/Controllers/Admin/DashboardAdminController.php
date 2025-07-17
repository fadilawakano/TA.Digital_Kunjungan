<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kunjungan;
use Illuminate\Support\Carbon;

class DashboardAdminController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $weekStart = Carbon::now()->startOfWeek();
        $monthStart = Carbon::now()->startOfMonth();

        $hariIni = [
            'murid' => Kunjungan::whereDate('tanggal', $today)
                ->whereHas('user', fn($q) => $q->where('role', 'murid'))
                ->count(),
            'guru' => Kunjungan::whereDate('tanggal', $today)
                ->whereHas('user', fn($q) => $q->where('role', 'guru'))
                ->count(),
        ];

        $mingguan = [
            'murid' => Kunjungan::whereBetween('tanggal', [$weekStart, now()])
                ->whereHas('user', fn($q) => $q->where('role', 'murid'))
                ->count(),
            'guru' => Kunjungan::whereBetween('tanggal', [$weekStart, now()])
                ->whereHas('user', fn($q) => $q->where('role', 'guru'))
                ->count(),
        ];

        $bulanan = [
            'murid' => Kunjungan::whereBetween('tanggal', [$monthStart, now()])
                ->whereHas('user', fn($q) => $q->where('role', 'murid'))
                ->count(),
            'guru' => Kunjungan::whereBetween('tanggal', [$monthStart, now()])
                ->whereHas('user', fn($q) => $q->where('role', 'guru'))
                ->count(),
        ];

        $lokasiMurid = Kunjungan::whereBetween('tanggal', [$monthStart, now()])
                ->whereHas('user', fn($q) => $q->where('role', 'murid'))
                ->selectRaw('lokasi, COUNT(*) as total')
                ->groupBy('lokasi')
                ->pluck('total', 'lokasi')
                ->toArray();

            $lokasiGuru = Kunjungan::whereBetween('tanggal', [$monthStart, now()])
                ->whereHas('user', fn($q) => $q->where('role', 'guru'))
                ->selectRaw('lokasi, COUNT(*) as total')
                ->groupBy('lokasi')
                ->pluck('total', 'lokasi')
                ->toArray();

            $lokasiStatusGabungan = ['Lab Biologi', 'Lab Fisika', 'Lab Kimia', 'Perpustakaan'];

            $lokasiMap = [
                'biologi' => 'Lab Biologi',
                'fisika' => 'Lab Fisika',
                'kimia' => 'Lab Kimia',
                'perpustakaan' => 'Perpustakaan',
            ];

            $statusGabungan = [];

        foreach ($lokasiMap as $roleTujuan => $namaLokasi) {
            $statusGabungan[] = [
                'lokasi' => $namaLokasi,
                'murid_berhasil' => Kunjungan::whereHas('user', fn ($q) => $q->where('role', 'murid'))
                    ->where('role_tujuan', $roleTujuan)
                    ->whereNotNull('verifikasi_petugas')
                    ->where('status_verifikasi', 'berhasil')
                    ->whereBetween('tanggal', [$monthStart, now()])
                    ->count(),
                'murid_rusak' => Kunjungan::whereHas('user', fn ($q) => $q->where('role', 'murid'))
                    ->where('role_tujuan', $roleTujuan)
                    ->whereNotNull('verifikasi_petugas')
                    ->where('status_verifikasi', 'kerusakan')
                    ->whereBetween('tanggal', [$monthStart, now()])
                    ->count(),
                'guru_berhasil' => Kunjungan::whereHas('user', fn ($q) => $q->where('role', 'guru'))
                    ->where('role_tujuan', $roleTujuan)
                    ->whereNotNull('verifikasi_petugas')
                    ->where('status_verifikasi', 'berhasil')
                    ->whereBetween('tanggal', [$monthStart, now()])
                    ->count(),
                'guru_rusak' => Kunjungan::whereHas('user', fn ($q) => $q->where('role', 'guru'))
                    ->where('role_tujuan', $roleTujuan)
                    ->whereNotNull('verifikasi_petugas')
                    ->where('status_verifikasi', 'kerusakan')
                    ->whereBetween('tanggal', [$monthStart, now()])
                    ->count(),
            ];

            }

        return view('admin.dashboard', compact(
            'hariIni',
            'mingguan',
            'bulanan',
            'lokasiMurid',
            'lokasiGuru',
            'statusGabungan'
        ));
    }
}
