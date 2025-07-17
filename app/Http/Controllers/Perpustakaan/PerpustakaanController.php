<?php

namespace App\Http\Controllers\Perpustakaan;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Kunjungan;
use App\Models\Kerusakan;
use Barryvdh\DomPDF\Facade\Pdf;

class PerpustakaanController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:perpustakaan']);
    }

    public function murid()
    {
        $kunjunganPerpustakaan = Kunjungan::with('user')
    ->where('role_tujuan', 'perpustakaan')
    ->whereHas('user', fn($q) => $q->where('role', 'murid'))
    ->latest()
    ->paginate(5) 
    ->withQueryString(); 


        return view('perpustakaan.murid', compact('kunjunganPerpustakaan'));
    }

    public function guru()
    {
        $kunjunganGuru = Kunjungan::with('user')
            ->where('role_tujuan', 'perpustakaan')
            ->whereHas('user', fn($q) => $q->where('role', 'guru'))
            ->latest()
            ->paginate(5) 
            ->withQueryString(); 

        return view('perpustakaan.guru', compact('kunjunganGuru'));
    }

    public function filter(Request $request, $asal)
    {
        $query = Kunjungan::with('user')
            ->where('role_tujuan', 'perpustakaan');

        if ($asal === 'murid') {
            $query->whereHas('user', fn($q) => $q->where('role', 'murid'));
        } elseif ($asal === 'guru') {
            $query->whereHas('user', fn($q) => $q->where('role', 'guru'));
        }

        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }

        $this->applyDateFilter($query, $request);

        $data = $query->latest()->paginate(5)->withQueryString();

        if ($asal === 'murid') {
            return view('perpustakaan.murid', ['kunjunganPerpustakaan' => $data]);
        } elseif ($asal === 'guru') {
            return view('perpustakaan.guru', ['kunjunganGuru' => $data]);
        } else {
            $murid = $data->where('user.role', 'murid');
            $guru = $data->where('user.role', 'guru');

            return view('perpustakaan.dashboard', [
                'kunjunganMurid' => $murid,
                'kunjunganGuru' => $guru,
            ]);
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

        if ($request->status === 'kerusakan') {
            Kerusakan::create([
                'nama' => $data->nama,
                'kelas' => $data->kelas ?? '-',
                'kategori' => $data->kategori ?? 'perpustakaan',
                'deskripsi' => 'Kerusakan saat kunjungan perpustakaan.',
                'status' => 'belum dikonfirmasi', 
            ]);
        } elseif ($request->status === 'berhasil') {
            $kategori = $data->lokasi ?? 'perpustakaan';

            Kerusakan::whereRaw('LOWER(nama) = ?', [strtolower(trim($data->nama))])
                ->whereRaw('LOWER(kategori) = ?', [strtolower($kategori)])
                ->whereRaw('TRIM(kelas) = ?', [trim($data->kelas ?? '-')])
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
        $query = Kunjungan::with('user')
            ->where('role_tujuan', 'perpustakaan')
            ->whereHas('user', fn($q) => $q->where('role', 'murid'));

        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }

        $this->applyDateFilter($query, $request);

        $murid = $query->get();

        return PDF::loadView('pdf.kunjungan_perpustakaan_murid', compact('murid'))
            ->stream('kunjungan_murid.pdf');
    }

    public function cetakGuru(Request $request)
    {
        $query = Kunjungan::with('user')
            ->where('role_tujuan', 'perpustakaan')
            ->whereHas('user', fn($q) => $q->where('role', 'guru'));

        $this->applyDateFilter($query, $request);

        $guru = $query->get();

        return PDF::loadView('pdf.kunjungan_perpustakaan_guru', compact('guru'))
            ->stream('kunjungan_guru.pdf');
    }

   private function applyDateFilter($query, $request)
{
    $filterJenis = $request->input('filter_jenis');

    switch ($filterJenis) {
        case 'bulanan':
            if ($request->filled('bulan')) {
                $query->whereYear('tanggal', substr($request->bulan, 0, 4))
                      ->whereMonth('tanggal', substr($request->bulan, 5, 2));
            }
            break;

        case 'mingguan':
            if ($request->filled('minggu')) {
                [$year, $week] = explode('-W', $request->minggu);
                $start = Carbon::now()->setISODate($year, $week)->startOfWeek();
                $end = Carbon::now()->setISODate($year, $week)->endOfWeek();
                $query->whereBetween('tanggal', [$start, $end]);
            }
            break;

        case 'harian':
        default:
            if ($request->filled('tanggal')) {
                $query->whereDate('tanggal', $request->tanggal);
            }
            break;
    }
}



    public function index()
    {
        return view('perpustakaan.dashboard', [
            'muridHariIni' => Kunjungan::whereDate('tanggal', today())
                ->whereHas('user', fn($q) => $q->where('role', 'murid'))
                ->count(),
            'guruHariIni' => Kunjungan::whereDate('tanggal', today())
                ->whereHas('user', fn($q) => $q->where('role', 'guru'))
                ->count(),
            'peminjamanHariIni' => Kunjungan::where('tipe', 'pinjam')->whereDate('tanggal', today())->count(),
            'jumlahBerhasil' => Kunjungan::where('keterangan', 'berhasil')->count(),
            'jumlahRusak' => Kunjungan::where('keterangan', 'rusak')->count(),
        ]);

        
    }
}
