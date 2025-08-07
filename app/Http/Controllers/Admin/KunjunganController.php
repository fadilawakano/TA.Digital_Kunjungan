<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kunjungan;
use Barryvdh\DomPDF\Facade\Pdf;

class KunjunganController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'murid'); // default tab = murid

        $query = Kunjungan::query();

        // Filter berdasarkan role
        if ($tab === 'murid') {
            $query->whereHas('user', fn($q) => $q->where('role', 'murid'));
        } elseif ($tab === 'guru') {
            $query->whereHas('user', fn($q) => $q->where('role', 'guru'));
        }

        // Filter tanggal berdasarkan periode
if ($request->filled('tanggal')) {
    $tanggalInput = $request->tanggal;
    $periode = $request->periode ?? 'harian';

    try {
        if ($periode === 'mingguan') {
            // Format "2025-W23"
            [$year, $week] = explode('-W', $tanggalInput);
            $start = \Carbon\Carbon::now()->setISODate($year, $week)->startOfWeek();
            $end = \Carbon\Carbon::now()->setISODate($year, $week)->endOfWeek();
            $query->whereBetween('tanggal', [$start, $end]);

        } elseif ($periode === 'bulanan') {
            // Format "2025-06"
            $date = \Carbon\Carbon::parse($tanggalInput . '-01');
            $query->whereYear('tanggal', $date->year)
                  ->whereMonth('tanggal', $date->month);

        } else {
            // Harian default
            $query->whereDate('tanggal', $tanggalInput);
        }
    } catch (\Exception $e) {
        // Jika parsing gagal, abaikan filter
    }
}


        // Filter lokasi
        if ($request->filled('lokasi')) {
            $query->where('lokasi', $request->lokasi);
        }

        // Filter tipe (khusus perpustakaan)
        if ($request->filled('tipe') && $request->lokasi === 'perpustakaan') {
            $query->where('tipe', $request->tipe);
        }

        // Filter status verifikasi
        if ($request->filled('status')) {
            if ($request->status === 'Terverifikasi') {
                $query->whereNotNull('verifikasi_petugas');
            } elseif ($request->status === 'Menunggu') {
                $query->whereNull('verifikasi_petugas');
            }
        }

        $kunjungan = $query->latest()->paginate(8);

        return view('admin.kunjungan.index', [
            'kunjungan' => $kunjungan,
            'tab' => $tab,
        ]);
    }

    public function cetak(Request $request)
{
    $tab = $request->get('tab', 'murid');
    $lokasi = $request->get('lokasi');
    $tipe   = $request->get('tipe'); // baca atau pinjam

    $query = Kunjungan::query();

    if ($tab === 'murid') {
        $query->whereHas('user', fn($q) => $q->where('role', 'murid'));
    } elseif ($tab === 'guru') {
        $query->whereHas('user', fn($q) => $q->where('role', 'guru'));
    }

    // Filter tanggal
    if ($request->filled('tanggal')) {
        $tanggalInput = $request->tanggal;
        $periode = $request->periode ?? 'harian';

        try {
            if ($periode === 'mingguan') {
                [$year, $week] = explode('-W', $tanggalInput);
                $start = \Carbon\Carbon::now()->setISODate($year, $week)->startOfWeek();
                $end   = \Carbon\Carbon::now()->setISODate($year, $week)->endOfWeek();
                $query->whereBetween('tanggal', [$start, $end]);
            } elseif ($periode === 'bulanan') {
                $date = \Carbon\Carbon::parse($tanggalInput . '-01');
                $query->whereYear('tanggal', $date->year)
                      ->whereMonth('tanggal', $date->month);
            } else {
                $query->whereDate('tanggal', $tanggalInput);
            }
        } catch (\Exception $e) {
            // Abaikan jika parsing gagal
        }
    }

    // Filter lokasi
    if ($request->filled('lokasi')) {
        $query->where('lokasi', $request->lokasi);
    }

    // Filter tipe (khusus perpustakaan)
    if ($request->lokasi === 'perpustakaan' && $request->filled('tipe')) {
        $query->where('tipe', $request->tipe);
    }

    // Filter status
    if ($request->filled('status')) {
        if ($request->status === 'Terverifikasi') {
            $query->whereNotNull('verifikasi_petugas');
        } elseif ($request->status === 'Menunggu') {
            $query->whereNull('verifikasi_petugas');
        }
    }

    $kunjungan = $query->latest()->get();

    $pdf = Pdf::loadView('admin.kunjungan.cetak', [
        'kunjungan' => $kunjungan,
        'tab'       => $tab,
        'lokasi'    => $lokasi,
        'tipe'      => $tipe // dikirim ke blade
    ])->setPaper('A4', 'portrait');

    return $pdf->stream('laporan_kunjungan_' . $tab . '.pdf');
}

}
