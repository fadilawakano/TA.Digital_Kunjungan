<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kunjungan;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class KunjunganGuruController extends Controller
{
    public function store(Request $request)
    {
        $lokasiInput = strtolower(trim($request->input('lokasi')));
        $lokasiStandar = match (true) {
            str_contains($lokasiInput, 'biologi') => 'biologi',
            str_contains($lokasiInput, 'fisika') => 'fisika',
            str_contains($lokasiInput, 'kimia') => 'kimia',
            str_contains($lokasiInput, 'perpus') || str_contains($lokasiInput, 'pustaka') => 'perpustakaan',
            default => 'lainnya',
        };

        $rules = [
            'nama' => 'required|string|max:255',
            'tanggal' => 'required|date|after_or_equal:today',
            'lokasi' => 'required|string',
        ];

        if ($lokasiStandar === 'perpustakaan') {
            $rules += [
                'judul_buku' => 'required|string|max:255',
                'jumlah_buku' => 'required|integer|min:1',
                'tanggal_pengembalian' => 'required|date|after_or_equal:tanggal',
            ];
        } else {
            $rules += [
                'kelas' => 'required|array|min:1',
                'kelas.*' => 'string|max:255',
                'mata_pelajaran' => 'required|string|max:255',
                'alat' => 'required|string|max:255',
                'jumlah_alat' => 'required|string|max:255',
                'judul_materi' => 'required|string|max:255',
                'waktu_mulai' => 'required|date_format:Y-m-d\TH:i',
                'waktu_selesai' => 'required|date_format:Y-m-d\TH:i|after:waktu_mulai',
            ];
        }

        $validated = $request->validate($rules);

        // ⛔ Cek waktu sudah lewat (khusus selain perpustakaan)
        if ($lokasiStandar !== 'perpustakaan') {
            $waktuMulai = Carbon::parse($validated['waktu_mulai']);
            $waktuSelesai = Carbon::parse($validated['waktu_selesai']);

            if ($waktuMulai->isPast() || $waktuSelesai->isPast()) {
                return redirect()->back()->with('error', 'Tanggal atau waktu kunjungan sudah lewat. Silakan pilih waktu yang masih berlaku.');
            }
        }

        if ($lokasiStandar === 'perpustakaan') {
            $kunjungan = new Kunjungan();
            $kunjungan->user_id = Auth::id();
            $kunjungan->nama = $validated['nama'];
            $kunjungan->tanggal = $validated['tanggal'];
            $kunjungan->lokasi = $lokasiStandar;
            $kunjungan->role_tujuan = $lokasiStandar;
            $kunjungan->status_verifikasi = 'menunggu';
            $kunjungan->tipe = 'kunjungan';
            $kunjungan->judul_buku = $validated['judul_buku'];
            $kunjungan->jumlah_buku = $validated['jumlah_buku'];
            $kunjungan->tanggal_pengembalian = $validated['tanggal_pengembalian'];
            $kunjungan->save();
        } else {
            foreach ($validated['kelas'] as $kelas) {
                $kunjungan = new Kunjungan();
                $kunjungan->user_id = Auth::id();
                $kunjungan->nama = $validated['nama'];
                $kunjungan->kelas = $kelas;
                $kunjungan->tanggal = $validated['tanggal'];
                $kunjungan->lokasi = $lokasiStandar;
                $kunjungan->role_tujuan = $lokasiStandar;
                $kunjungan->status_verifikasi = 'menunggu';
                $kunjungan->tipe = 'jadwal';
                $kunjungan->mata_pelajaran = $validated['mata_pelajaran'];
                $kunjungan->alat = $validated['alat'];
                $kunjungan->jumlah_alat = $validated['jumlah_alat'];
                $kunjungan->judul_materi = $validated['judul_materi'];
                $kunjungan->waktu_mulai = $validated['waktu_mulai'];
                $kunjungan->waktu_selesai = $validated['waktu_selesai'];
                $kunjungan->save();
            }
        }

        $message = $lokasiStandar === 'perpustakaan'
            ? 'Pendaftaran berhasil dicatat!'
            : 'Jadwal kunjungan berhasil dicatat untuk semua kelas yang dipilih!';

        return redirect()->back()->with('success', $message);
    }
}
