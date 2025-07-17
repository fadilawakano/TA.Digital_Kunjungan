<?php

namespace App\Http\Controllers\Murid;

use App\Http\Controllers\Controller;
use App\Models\Kunjungan;
use App\Models\Kerusakan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardMuridController extends Controller
{
    public function index()
    {
        $jadwal = [
            'biologi' => $this->jadwalTersedia('biologi'),
            'fisika' => $this->jadwalTersedia('fisika'),
            'kimia' => $this->jadwalTersedia('kimia'),
            'perpustakaan' => true, // Tidak perlu jadwal
        ];

        return view('murid.dashboard', compact('jadwal'));
    }

    public function biologi()
    {
        return $this->renderFormKunjungan('biologi', ['Biologi', 'Ekologi', 'Botani']);
    }

    public function fisika()
    {
        return $this->renderFormKunjungan('fisika', ['Fisika Dasar', 'Listrik', 'Mekanika']);
    }

    public function kimia()
    {
        return $this->renderFormKunjungan('kimia', ['Kimia Organik', 'Analitik', 'Reaksi']);
    }

    public function perpustakaan()
    {
        $user = Auth::user();

        $blokir = Kunjungan::where('user_id', $user->id)
            ->where('role_tujuan', 'perpustakaan')
            ->where('status_verifikasi', 'kerusakan')
            ->exists();

        if ($blokir) {
            return redirect()->route('murid.dashboard')->with('error', 'Anda tidak dapat mengunjungi perpustakaan karena ada status verifikasi kerusakan.');
        }

        return $this->renderFormKunjungan('perpustakaan');
    }

    private function renderFormKunjungan(string $lokasi, array $judulMateri = [])
{
    $user = Auth::user();

    $kerusakanAktif = Kerusakan::whereRaw('LOWER(nama) = ?', [strtolower($user->name)])
        ->whereRaw('TRIM(kelas) = ?', [trim($user->kelas)])
        ->whereRaw('LOWER(kategori) = ?', [strtolower($lokasi)])
        ->where('status', 'belum dikonfirmasi')
        ->exists();

    if ($kerusakanAktif) {
        return redirect()->route('murid.dashboard')
            ->with('error', 'Anda tidak dapat melakukan kunjungan ke ' . ucfirst($lokasi) . ' karena masih ada laporan kerusakan yang belum dikonfirmasi.');
    }

    if (in_array($lokasi, ['biologi', 'fisika', 'kimia']) && !$this->jadwalTersedia($lokasi)) {
        return redirect()->route('murid.dashboard')
            ->with('error', 'Tidak ada jadwal kunjungan tersedia untuk ' . strtoupper($lokasi));
    }

    return view('murid.kunjungan', [
        'lokasi' => $lokasi,
        'judul_materi' => $judulMateri,
        'kerusakan' => false, 
        'nama' => $user->name,
        'kelas' => $user->kelas,
    ]);
}



    public function statusKunjungan()
    {
        $kunjungan = Kunjungan::where('user_id', Auth::id())->latest()->get();
        return view('murid.status-kunjungan', compact('kunjungan'));
    }

    private function jadwalTersedia(string $lokasi): bool
    {
        return Kunjungan::where([
            ['tipe', 'jadwal'],
            ['lokasi', $lokasi],
            ['kelas', Auth::user()->kelas],
            ['tanggal', now()->toDateString()],
        ])->exists();
    }
}
