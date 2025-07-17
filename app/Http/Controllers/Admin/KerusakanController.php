<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kunjungan;
use Illuminate\Http\Request;

class KerusakanController extends Controller
{
    public function index()
    {
        $kerusakan = Kunjungan::where('jenis_kerusakan', true)
            ->where('jenis_kerusakan', 'alat')
            ->latest()
            ->get();

        return view('admin.kerusakan.index', compact('kerusakan'));
    }
}
