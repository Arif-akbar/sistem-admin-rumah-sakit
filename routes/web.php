<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\JadwalDokterController;
use App\Http\Controllers\RekamMedisController;
use App\Http\Controllers\PoliController;

// Dashboard
Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

// Additional routes must be registered before resources so they are not
// captured by resource show routes such as pasien/{pasien}.
Route::prefix('pasien')->group(function () {
    Route::get('{no_rm}/riwayat', [PasienController::class, 'riwayat'])->name('pasien.riwayat');
    Route::get('search', [PasienController::class, 'search'])->name('pasien.search');
});

Route::prefix('jadwal')->group(function () {
    Route::get('dokter/{id_dokter}', [JadwalDokterController::class, 'getByDokter'])->name('jadwal.byDokter');
    Route::get('poli/{id_poli}', [JadwalDokterController::class, 'getByPoli'])->name('jadwal.byPoli');
});

Route::prefix('dokter')->group(function () {
    Route::get('spesialis/{id_spesialis}', [DokterController::class, 'getBySpesialis'])->name('dokter.bySpesialis');
    Route::get('poli/{id_poli}', [DokterController::class, 'getByPoli'])->name('dokter.byPoli');
});

Route::prefix('rekam-medis')->group(function () {
    Route::get('pasien/{no_rm}/riwayat', [RekamMedisController::class, 'riwayatPasien'])->name('rekam_medis.riwayat');
    Route::get('{id}/cetak', [RekamMedisController::class, 'cetak'])->name('rekam_medis.cetak');
    Route::get('statistik', [RekamMedisController::class, 'getStatistik'])->name('rekam_medis.statistik');
});

Route::prefix('poli')->group(function () {
    Route::get('aktif', [PoliController::class, 'getAktif'])->name('poli.aktif');
});

// Resources (Eloquent Resource Routes)
Route::resource('pasien', PasienController::class);
Route::resource('dokter', DokterController::class);
Route::resource('jadwal', JadwalDokterController::class);
Route::resource('rekam-medis', RekamMedisController::class)->names('rekam_medis');
Route::resource('poli', PoliController::class);
