<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::create('kunjungan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('nama');
            $table->string('kelas')->nullable();
            $table->string('lokasi');
            $table->string('kategori')->nullable();
            $table->string('judul_buku')->nullable();
            $table->integer('jumlah_buku')->nullable();
            $table->string('judul_materi')->nullable();
            $table->string('mata_pelajaran')->nullable();
            $table->string('alat')->nullable();
            $table->integer('jumlah_alat')->nullable();
            $table->date('tanggal');
            $table->date('tanggal_pengembalian')->nullable(); 
            $table->dateTime('waktu_mulai')->nullable();
            $table->dateTime('waktu_selesai')->nullable();
            $table->string('tipe')->default('kunjungan');
            $table->string('role_tujuan');
            $table->boolean('status_verifikasi')->default(false);
            $table->boolean('ada_kerusakan')->default(false);
            $table->enum('jenis_kerusakan', ['alat', 'buku'])->nullable();
            $table->text('deskripsi_kerusakan')->nullable();
            $table->boolean('verifikasi_petugas')->default(false);
            $table->text('catatan_petugas')->nullable();
            $table->timestamps();

            // Foreign key ke tabel users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('kunjungan');
    }
};
