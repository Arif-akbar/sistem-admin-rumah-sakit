<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\JadwalDokterController;
use App\Http\Controllers\RekamMedisController;

Route::get('/', function () {
    return view('dashboard');
});

Route::resource('pasien', PasienController::class);
Route::get('/jadwal', [JadwalDokterController::class, 'index']);
Route::get('/rekam-medis', [RekamMedisController::class, 'index']);


