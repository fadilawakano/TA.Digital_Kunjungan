<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Murid\DashboardMuridController;
use App\Http\Controllers\Murid\KunjunganMuridController;
use App\Http\Controllers\Murid\StatusVerifikasiController as MuridStatusVerifikasiController;
use App\Http\Controllers\Guru\GuruController;
use App\Http\Controllers\Guru\KunjunganGuruController;
use App\Http\Controllers\Guru\StatusVerifikasiController as GuruStatusVerifikasiController;
use App\Http\Controllers\Biologi\BiologiController; 
use App\Http\Controllers\Biologi\DashboardBiologiController;
use App\Http\Controllers\Fisika\FisikaController;
use App\Http\Controllers\Fisika\DashboardFisikaController;
use App\Http\Controllers\Kimia\KimiaController;
use App\Http\Controllers\Kimia\DashboardKimiaController;
use App\Http\Controllers\Perpustakaan\PerpustakaanController;
use App\Http\Controllers\Perpustakaan\DashboardPerpustakaanController;
use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\NewPasswordController;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

Route::get('/', function () {
    return view('welcome');
});

require __DIR__ . '/auth.php';

Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])
        ->name('password.request');

    Route::post('/forgot-password', [ForgotPasswordController::class, 'checkUsername'])
        ->name('password.check');

    Route::get('/reset-password/{username}', [ForgotPasswordController::class, 'showResetForm'])
        ->name('password.reset');

    Route::post('/reset-password/{username}', [ForgotPasswordController::class, 'updatePassword'])
        ->name('password.update');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ===================== MIDDLEWARE PROTECTED ROUTES =====================
Route::middleware(['auth', 'prevent-back-history'])->group(function () {

    // ---------- PROFIL ----------
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ---------- GURU ----------
    Route::middleware(['role:guru'])->prefix('guru')->name('guru.')->group(function () {
        Route::get('/dashboard', [GuruController::class, 'index'])->name('dashboard');

        // Formulir kunjungan
        Route::get('/kunjungan/biologi', [GuruController::class, 'biologi'])->name('kunjungan.biologi');
        Route::get('/kunjungan/fisika', [GuruController::class, 'fisika'])->name('kunjungan.fisika');
        Route::get('/kunjungan/kimia', [GuruController::class, 'kimia'])->name('kunjungan.kimia');
        Route::get('/kunjungan/perpustakaan', [GuruController::class, 'perpustakaan'])->name('kunjungan.perpustakaan');
        Route::get('/status-kunjungan', [GuruController::class, 'statusKunjungan'])->name('status.kunjungan');
        Route::get('/status-verifikasi', [GuruStatusVerifikasiController::class, 'index'])->name('status-verifikasi');
        Route::post('/kunjungan/store', [KunjunganGuruController::class, 'store'])->name('kunjungan.store');
        Route::get('/profil', [\App\Http\Controllers\Guru\ProfilController::class, 'edit'])->name('profil.edit');
        Route::post('/profil', [\App\Http\Controllers\Guru\ProfilController::class, 'update'])->name('profil.update');
    });

    // ---------- PETUGAS LAB KIMIA ----------
    Route::middleware(['role:kimia'])->prefix('kimia')->name('kimia.')->group(function () {
        Route::get('/dashboard', [DashboardKimiaController::class, 'index'])->name('dashboard');
        Route::get('/murid', [KimiaController::class, 'murid'])->name('murid');
        Route::get('/guru', [KimiaController::class, 'guru'])->name('guru');
        Route::post('/verifikasi/{id}', [KimiaController::class, 'verifikasi'])->name('verifikasi');
        Route::get('/filter/{asal}', [KimiaController::class, 'filter'])->name('filter');
        Route::get('/cetak/kimia/murid', [kimiaController::class, 'cetakMurid'])->name('cetak.kimia.murid');
        Route::get('/cetak/kimia/guru', [kimiaController::class, 'cetakGuru'])->name('cetak.kimia.guru');
        Route::delete('/kunjungan/{id}', [KimiaController::class, 'destroy'])->name('kunjungan.destroy');
        Route::get('/profil', [\App\Http\Controllers\Kimia\ProfilController::class, 'edit'])->name('profil.edit');
        Route::post('/profil', [\App\Http\Controllers\Kimia\ProfilController::class, 'update'])->name('profil.update');
    });

    // ---------- PETUGAS LAB BIOLOGI ----------
    Route::middleware(['role:biologi'])->prefix('biologi')->name('biologi.')->group(function () {
        Route::get('/dashboard', [DashboardBiologiController::class, 'index'])->name('dashboard');
        Route::get('/murid', [BiologiController::class, 'murid'])->name('murid');
        Route::get('/guru', [BiologiController::class, 'guru'])->name('guru');
        Route::post('/verifikasi/{id}', [BiologiController::class, 'verifikasi'])->name('verifikasi');
        Route::get('/filter/{asal}', [BiologiController::class, 'filter'])->name('filter');
        Route::get('/cetak/biologi/murid', [BiologiController::class, 'cetakMurid'])->name('cetak.biologi.murid');
        Route::get('/cetak/biologi/guru', [BiologiController::class, 'cetakGuru'])->name('cetak.biologi.guru');
        Route::delete('/kunjungan/{id}', [BiologiController::class, 'destroy'])->name('kunjungan.destroy');
        Route::get('/profil', [\App\Http\Controllers\Biologi\ProfilController::class, 'edit'])->name('profil.edit');
        Route::post('/profil', [\App\Http\Controllers\Biologi\ProfilController::class, 'update'])->name('profil.update');
    });
    });

     // ---------- PETUGAS LAB FISIKA ----------
    Route::middleware(['role:fisika'])->prefix('fisika')->name('fisika.')->group(function () {
        Route::get('/dashboard', [DashboardFisikaController::class, 'index'])->name('dashboard');
        Route::get('/murid', [FisikaController::class, 'murid'])->name('murid');
        Route::get('/guru', [FisikaController::class, 'guru'])->name('guru');
        Route::post('/verifikasi/{id}', [FisikaController::class, 'verifikasi'])->name('verifikasi');
        Route::get('/filter/{asal}', [FisikaController::class, 'filter'])->name('filter');
        Route::get('/cetak/fisika/murid', [FisikaController::class, 'cetakMurid'])->name('cetak.fisika.murid');
        Route::get('/cetak/fisika/guru', [FisikaController::class, 'cetakGuru'])->name('cetak.fisika.guru');
        Route::delete('/kunjungan/{id}', [FisikaController::class, 'destroy'])->name('kunjungan.destroy');
        Route::get('/profil', [\App\Http\Controllers\Fisika\ProfilController::class, 'edit'])->name('profil.edit');
        Route::post('/profil', [\App\Http\Controllers\Fisika\ProfilController::class, 'update'])->name('profil.update');
    });

    // ---------- PETUGAS PERPUSTAKAAN ----------
    Route::middleware(['role:perpustakaan'])->prefix('perpustakaan')->name('perpustakaan.')->group(function () {
        Route::get('/dashboard', [DashboardPerpustakaanController::class, 'index'])->name('dashboard');
        Route::get('/murid', [PerpustakaanController::class, 'murid'])->name('murid');
        Route::get('/guru', [PerpustakaanController::class, 'guru'])->name('guru');
        Route::post('/verifikasi/{id}', [PerpustakaanController::class, 'verifikasi'])->name('verifikasi');
       // Route::get('/filter', [PerpustakaanController::class, 'filter'])->name('filter');
        Route::get('/filter/{asal}', [PerpustakaanController::class, 'filter'])->name('filter');
        Route::get('/cetak/perpustakaan/murid', [PerpustakaanController::class, 'cetakMurid'])->name('cetak.perpus.murid');
        Route::get('/cetak/perpustakaan/guru', [PerpustakaanController::class, 'cetakGuru'])->name('cetak.perpus.guru');
        Route::delete('/kunjungan/{id}', [PerpustakaanController::class, 'destroy'])->name('kunjungan.destroy');
        Route::get('/profil', [\App\Http\Controllers\Perpustakaan\ProfilController::class, 'edit'])->name('profil.edit');
        Route::post('/profil', [\App\Http\Controllers\Perpustakaan\ProfilController::class, 'update'])->name('profil.update');
    
    });

        // ---------- MURID ----------
    Route::middleware(['role:murid'])->prefix('murid')->name('murid.')->group(function () {
        Route::get('/dashboard', [DashboardMuridController::class, 'index'])->name('dashboard');
        Route::get('/kunjungan/biologi', [DashboardMuridController::class, 'biologi'])->name('kunjungan.biologi');
        Route::get('/kunjungan/fisika', [DashboardMuridController::class, 'fisika'])->name('kunjungan.fisika');
        Route::get('/kunjungan/kimia', [DashboardMuridController::class, 'kimia'])->name('kunjungan.kimia');
        Route::get('/kunjungan/perpustakaan', [DashboardMuridController::class, 'perpustakaan'])->name('kunjungan.perpustakaan');
        Route::post('/kunjungan/store', [KunjunganMuridController::class, 'store'])->name('kunjungan.store');
        Route::get('/status-kunjungan', [DashboardMuridController::class, 'statusKunjungan'])->name('status.kunjungan');
        Route::get('/status-verifikasi', [MuridStatusVerifikasiController::class, 'index'])->name('status-verifikasi');
        Route::get('/status-verifikasi/filter', [MuridStatusVerifikasiController::class, 'filter'])->name('status-verifikasi.filter');
        Route::get('/profil', [\App\Http\Controllers\Murid\ProfilController::class, 'edit'])->name('profil.edit');
        Route::post('/profil', [\App\Http\Controllers\Murid\ProfilController::class, 'update'])->name('profil.update');

    });

    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardAdminController::class, 'index'])->name('dashboard');
        Route::resource('users', UserController::class);
        Route::get('kunjungan', [\App\Http\Controllers\Admin\KunjunganController::class, 'index'])->name('kunjungan.index');
        Route::get('/admin/kunjungan/cetak', [App\Http\Controllers\Admin\KunjunganController::class, 'cetak'])->name('kunjungan.cetak');

}); 





