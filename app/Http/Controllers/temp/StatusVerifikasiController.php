<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kunjungan;
use Illuminate\Support\Facades\Auth;

class StatusVerifikasiController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $biologi = Kunjungan::where('user_id', $userId)->where('role_tujuan', 'biologi')->get();
        $fisika = Kunjungan::where('user_id', $userId)->where('role_tujuan', 'fisika')->get();
        $kimia = Kunjungan::where('user_id', $userId)->where('role_tujuan', 'kimia')->get();
        $perpustakaan = Kunjungan::where('user_id', $userId)->where('role_tujuan', 'perpustakaan')->get();

        return view('guru.status-verifikasi', compact('biologi', 'fisika', 'kimia', 'perpustakaan'));
    }
}
