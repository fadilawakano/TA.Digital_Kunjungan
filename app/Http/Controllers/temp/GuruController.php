<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Kerusakan;
use App\Models\Kunjungan;
use App\Models\BidangStudiUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuruController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ambil daftar bidang studi guru dari tabel bidang_studi_users
        $bidangStudi = BidangStudiUser::with('bidangStudi')
        ->where('user_id', $user->id)
        ->get()
        ->pluck('bidangStudi.nama') // akses nama dari relasi
        ->toArray();

        $kerusakanList = Kerusakan::whereRaw('LOWER(nama) = ?', [strtolower($user->name)])
    ->where('status', 'belum dikonfirmasi')
    ->pluck('kategori') // contoh: lab kimia, lab fisika, perpustakaan
    ->map(function ($item) {
        return strtolower(trim(str_replace('lab ', '', $item))); // hasil: kimia, fisika, dll
    })
    ->toArray();


        return view('guru.dashboard', [
            'bidangStudi' => $bidangStudi,
            'kerusakanList' => $kerusakanList,
        ]);
    }

    public function biologi()
    {
        return $this->renderFormKunjungan('lab biologi', ['Biologi', 'Ekologi', 'Botani']);
    }

    public function fisika()
    {
        return $this->renderFormKunjungan('lab fisika', ['Fisika Dasar', 'Listrik', 'Mekanika']);
    }

    public function kimia()
    {
        return $this->renderFormKunjungan('lab kimia', ['Kimia Organik', 'Analitik', 'Reaksi']);
    }

    public function perpustakaan()
    {
        return $this->renderFormKunjungan('perpustakaan');
    }

   private function renderFormKunjungan(string $lokasi, array $judulMateri = [])
{
    $user = Auth::user();

    // Normalisasi kategori untuk mencocokkan
    $kategori = strtolower(str_replace('lab ', '', $lokasi));
    if ($kategori === 'perpustakaan') {
        $kategori = 'perpustakaan';
    }

    $kerusakanAktif = Kerusakan::whereRaw('LOWER(nama) = ?', [strtolower($user->name)])
        ->whereRaw('TRIM(kelas) = ?', [trim($user->kelas)])
        ->whereRaw('LOWER(kategori) = ?', [$kategori])
        ->where('status', 'belum dikonfirmasi')
        ->exists();

    if ($kerusakanAktif) {
        return redirect()->route('guru.dashboard')
            ->with('error', 'Anda tidak dapat melakukan kunjungan ke ' . strtoupper($kategori) . ' karena masih ada laporan kerusakan yang belum dikonfirmasi.');
    }

    $kelasList = ['X Fase E1', 'X Fase E2', 'X Fase E3', 'XI Fase F1', 'XI Fase F2', 'XI Fase F3', 'XII Fase F1', 'XII Fase F2', 'XII Fase F3'];
    $mapelList = ['Biologi', 'Fisika', 'Kimia', 'Matematika', 'Bahasa Indonesia'];

    return view('guru.kunjungan', [
        'lokasi' => $lokasi,
        'judul_materi' => $judulMateri,
        'kerusakan' => false,
        'nama' => $user->name,
        'kelas' => $user->kelas,
        'kelasList' => $kelasList,
        'mapelList' => $mapelList,
    ]);
}


    public function store(Request $request)
    {
        $request->validate([
            'kelas' => 'required|string',
            'mata_pelajaran' => 'required|string',
            'judul_materi' => 'required|string',
            'alat' => 'required|string',
            'jumlah_alat' => 'required|integer',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
            'lokasi' => 'required|string',
        ]);

        $user = Auth::user();

        $lokasiInput = strtolower(trim($request->lokasi));
        $role_tujuan = match ($lokasiInput) {
            'lab biologi' => 'biologi',
            'lab fisika' => 'fisika',
            'lab kimia' => 'kimia',
            default => $lokasiInput,
        };

        Kunjungan::create([
            'user_id' => $user->id,
            'nama' => $user->name,
            'kelas' => $request->kelas,
            'mata_pelajaran' => $request->mapel,
            'judul_materi' => $request->judul_materi,
            'alat' => $request->alat,
            'jumlah_alat' => $request->jumlah_alat,
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
            'lokasi' => $request->lokasi,
            'role_tujuan' => $role_tujuan,
            'status_verifikasi' => 'menunggu',
        ]);

        return redirect()->route('dashboard.guru')->with('success', 'Pendaftaran kunjungan berhasil dikirim.');
    }
}
