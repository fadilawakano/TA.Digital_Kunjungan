<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class KunjunganSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('kunjungan')->insert([
            'user_id' => 1,
            'nama' => 'Dina Aulia',
            'kelas' => 'XII IPA 1',
            'lokasi' => 'Lab Biologi',
            'kategori' => 'Praktikum',
            'judul_buku' => null,
            'jumlah_buku' => null,
            'judul_materi' => 'Struktur Sel',
            'mata_pelajaran' => 'Biologi',
            'alat' => 'Mikroskop',
            'jumlah_alat' => 2,
            'tanggal' => Carbon::now()->toDateString(),
            'tanggal_pengembalian' => null,
            'tipe' => 'kunjungan',
            'role_tujuan' => 'murid',
            'status_verifikasi' => false,
            'ada_kerusakan' => false,
            'jenis_kerusakan' => null,
            'deskripsi_kerusakan' => null,
            'verifikasi_petugas' => false,
            'catatan_petugas' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
