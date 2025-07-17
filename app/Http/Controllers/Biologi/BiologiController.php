<?php

namespace App\Http\Controllers\Biologi;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Kunjungan;
use App\Models\Kerusakan;
use Barryvdh\DomPDF\Facade\Pdf;

class BiologiController extends Controller
{
    public function __construct()
    {
        // Batasi akses hanya untuk petugas lab biologi
        $this->middleware(['auth', 'role:biologi']);
    }


    public function murid()
    {
        $kunjunganBiologi = Kunjungan::where('role_tujuan', 'biologi')
            ->whereHas('user', function ($query) {
                $query->where('role', 'murid');
            })
            ->latest()
            ->paginate(5) 
            ->withQueryString(); 

        return view('biologi.murid', compact('kunjunganBiologi'));
    }

    public function guru()
    {
        $kunjunganGuru = Kunjungan::where('role_tujuan', 'biologi')
            ->whereHas('user', function ($query) {
                $query->where('role', 'guru');
            })
            ->latest()
           ->paginate(5) 
            ->withQueryString(); 

        return view('biologi.guru', compact('kunjunganGuru'));
    }

    public function filter(Request $request, $asal)
    {
        $jenisFilter = $request->input('filter_jenis', 'harian');

        $query = Kunjungan::with('user')
            ->where('role_tujuan', 'biologi');

        if ($asal === 'murid') {
            $query->whereHas('user', fn($q) => $q->where('role', 'murid'));
        } elseif ($asal === 'guru') {
            $query->whereHas('user', fn($q) => $q->where('role', 'guru'));
        }


        $this->applyDateFilter($query, $request);

        $data = $query->latest()->get();

        if ($asal === 'murid') {
            return view('biologi.murid', ['kunjunganBiologi' => $data]);
        } elseif ($asal === 'guru') {
            return view('biologi.guru', ['kunjunganGuru' => $data]);
        } else {
            $murid = $data->where('user.role', 'murid');
            $guru = $data->where('user.role', 'guru');

            return view('biologi.dashboard', [
                'kunjunganMurid' => $murid,
                'kunjunganGuru' => $guru,
            ]);
        }
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
    $kategori = $data->lokasi ?? 'biologi';

    if ($request->status === 'kerusakan') {
        Kerusakan::create([
            'nama' => $nama,
            'kelas' => $kelas,
            'kategori' => $kategori,
            'deskripsi' => 'Kerusakan saat kunjungan lab biologi.',
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
    $query = Kunjungan::where('role_tujuan', 'biologi')
        ->whereHas('user', fn($q) => $q->where('role', 'murid'));

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

    $murid = $query->get();

    return PDF::loadView('pdf.kunjungan_biologi_murid', compact('murid'))->stream('kunjungan_murid.pdf');
}

public function cetakGuru(Request $request)
{
    $query = Kunjungan::where('role_tujuan', 'biologi')
        ->whereHas('user', fn($q) => $q->where('role', 'guru'));

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

    $guru = $query->get();

    return PDF::loadView('pdf.kunjungan_biologi_guru', compact('guru'))->stream('kunjungan_guru.pdf');
}

}
