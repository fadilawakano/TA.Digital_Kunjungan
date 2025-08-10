<?php

namespace App\Http\Controllers\Fisika;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Kunjungan;
use App\Models\Kerusakan;
use Barryvdh\DomPDF\Facade\Pdf;

class FisikaController extends Controller
{
    public function __construct()
    {
        // Batasi akses hanya untuk petugas lab fisika
        $this->middleware(['auth', 'role:fisika']);
    }

    public function murid()
    {
        $data = Kunjungan::where('role_tujuan', 'fisika')
            ->whereHas('user', fn($q) => $q->where('role', 'murid'))
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('fisika.murid', compact('data'));
    }

    public function guru()
    {
        $data = Kunjungan::where('role_tujuan', 'fisika')
            ->whereHas('user', fn($q) => $q->where('role', 'guru'))
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('fisika.guru', compact('data'));
    }

    public function filter(Request $request, $asal)
    {
        $query = Kunjungan::with('user')
            ->where('role_tujuan', 'fisika');

        if ($asal === 'murid') {
            $query->whereHas('user', fn($q) => $q->where('role', 'murid'));
        } elseif ($asal === 'guru') {
            $query->whereHas('user', fn($q) => $q->where('role', 'guru'));
        }

        $this->applyDateFilter($query, $request);

        $data = $query->latest()->paginate(5)->withQueryString();

        if (in_array($asal, ['murid', 'guru'])) {
            return view("fisika.$asal", compact('data'));
        }

        $murid = $data->where('user.role', 'murid');
        $guru = $data->where('user.role', 'guru');

        return view('fisika.dashboard', compact('murid', 'guru'));
    }

    private function applyDateFilter($query, $request)
    {
        $filterJenis = $request->filter_jenis;

        if ($filterJenis === 'harian' && $request->tanggal) {
            $query->whereDate('tanggal', $request->tanggal);
        } elseif ($filterJenis === 'mingguan' && $request->minggu) {
            [$year, $week] = explode('-W', $request->minggu);
            $query->whereBetween('tanggal', [
                Carbon::now()->setISODate($year, $week)->startOfWeek(),
                Carbon::now()->setISODate($year, $week)->endOfWeek(),
            ]);
        } elseif ($filterJenis === 'bulanan' && $request->bulan) {
            $query->whereYear('tanggal', substr($request->bulan, 0, 4))
                ->whereMonth('tanggal', substr($request->bulan, 5, 2));
        }
    }

    public function verifikasi(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:berhasil,kerusakan',
        ]);

        $data = Kunjungan::findOrFail($id);
        $petugas = Auth::user();

        $data->status_verifikasi = $request->status;
        $data->verifikasi_petugas = $petugas->name;
        $data->save();

        $nama = trim($data->nama);
        $kelas = trim($data->kelas ?? '-');
        $kategori = $data->lokasi ?? 'fisika';

        if ($request->status === 'kerusakan') {
            Kerusakan::create([
                'nama' => $nama,
                'kelas' => $kelas,
                'kategori' => $kategori,
                'deskripsi' => 'Kerusakan saat kunjungan lab fisika.',
                'status' => 'belum dikonfirmasi',
            ]);
        } elseif ($request->status === 'berhasil') {
            Kerusakan::whereRaw('LOWER(nama) = ?', [strtolower($nama)])
                ->whereRaw('TRIM(kelas) = ?', [$kelas])
                ->whereRaw('LOWER(kategori) = ?', [strtolower($kategori)])
                ->where('status', 'belum dikonfirmasi')
                ->update(['status' => 'selesai']);
        }

        return back()->with('success', 'Data berhasil diverifikasi.');
    }

    public function destroy($id)
    {
        $data = Kunjungan::findOrFail($id);
        $data->delete();

        return back()->with('success', 'Data kunjungan berhasil dihapus.');
    }

    public function cetakMurid(Request $request)
    {
        $query = Kunjungan::where('role_tujuan', 'fisika')
            ->whereHas('user', fn($q) => $q->where('role', 'murid'));

        $this->applyDateFilter($query, $request);

        $murid = $query->get();

        return PDF::loadView('pdf.kunjungan_fisika_murid', compact('murid'))
            ->stream('kunjungan_murid.pdf');
    }

    public function cetakGuru(Request $request)
    {
        $query = Kunjungan::where('role_tujuan', 'fisika')
            ->whereHas('user', fn($q) => $q->where('role', 'guru'));

        $this->applyDateFilter($query, $request);

        $guru = $query->get();

        return PDF::loadView('pdf.kunjungan_fisika_guru', compact('guru'))
            ->stream('kunjungan_guru.pdf');
    }
}
