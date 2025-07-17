<?php

namespace App\Http\Controllers\Murid;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kunjungan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class KunjunganMuridController extends Controller
{
    public function store(Request $request)
{
    $murid = Auth::user();

    $lokasiInput = Str::lower(trim($request->input('lokasi')));
    $lokasiStandar = match (true) {
        Str::contains($lokasiInput, 'biologi') => 'biologi',
        Str::contains($lokasiInput, 'fisika') => 'fisika',
        Str::contains($lokasiInput, 'kimia') => 'kimia',
        Str::contains($lokasiInput, 'perpus') || Str::contains($lokasiInput, 'pustaka') => 'perpustakaan',
        default => 'lainnya',
    };

    // Validasi awal
    $rules = [
        'lokasi' => 'required|string',
    ];

    if ($lokasiStandar === 'perpustakaan') {
        $rules += [
            'tipe' => 'required|in:baca,pinjam',
            'judul_buku' => 'required|string|max:255',
            'jumlah_buku' => 'required|integer|min:1',
        ];

        if ($request->input('tipe') === 'pinjam') {
            $rules['tanggal_pengembalian'] = 'required|date|after_or_equal:today';
        }
    } else {
        $rules += [
            'mata_pelajaran' => 'required|string|max:255',
            'judul_materi' => 'required|string|max:255',
            'alat' => 'nullable|string|max:255',
        ];
    }

    $validated = $request->validate($rules);

    // Untuk laboratorium, wajib ada jadwal
    if ($lokasiStandar !== 'perpustakaan') {
        $jadwal = Kunjungan::where('tipe', 'jadwal')
            ->where('kelas', $murid->kelas)
            ->where('lokasi', $lokasiStandar)
            ->where('tanggal', today())
            ->first();

        if (!$jadwal) {
            return redirect()->back()->with('error', 'Tidak ada jadwal kunjungan ke ' . $lokasiStandar . ' hari ini untuk kelas Anda.');
        }
        if (now()->gt(today()->setTimeFromTimeString($jadwal->waktu_selesai))) {
            return redirect()->back()->with('error', 'Pendaftaran kunjungan sudah ditutup karena melewati batas waktu.');
        }
    }

    $sudahDaftar = Kunjungan::where('tipe', 'kunjungan')
        ->where('user_id', $murid->id)
        ->where('lokasi', $lokasiStandar)
        ->where('tanggal', today())
        ->exists();

    if ($sudahDaftar) {
        return redirect()->back()->with('error', 'Anda sudah mendaftar kunjungan hari ini.');
    }

    // Simpan kunjungan
    $kunjungan = new Kunjungan([
        'user_id' => $murid->id,
        'nama' => $murid->name,
        'kelas' => $murid->kelas,
        'tanggal' => today(),
        'lokasi' => $lokasiStandar,
        'role_tujuan' => $lokasiStandar,
        'status_verifikasi' => 'menunggu',
        'tipe' => $lokasiStandar === 'perpustakaan' ? $validated['tipe'] : 'kunjungan',
    ]);

    if ($lokasiStandar === 'perpustakaan') {
        $kunjungan->judul_buku = $validated['judul_buku'];
        $kunjungan->jumlah_buku = $validated['jumlah_buku'];
        if (($validated['tipe'] ?? '') === 'pinjam') {
            $kunjungan->tanggal_pengembalian = $validated['tanggal_pengembalian'];
        }
    } else {
        $kunjungan->mata_pelajaran = $validated['mata_pelajaran'];
        $kunjungan->judul_materi = $validated['judul_materi'];
        $kunjungan->alat = $validated['alat'] ?? null;
    }

    $kunjungan->save();

    return redirect()->route('murid.dashboard')->with('success', 'Pendaftaran kunjungan berhasil.');
}

}
