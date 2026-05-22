<?php

namespace App\Http\Controllers;

use App\Models\JadwalDokter;
use Illuminate\Http\Request;

class JadwalDokterController extends Controller
{
    public function index()
    {
        // Load relationships: dokter.spesialis, dokter.poli
        $jadwals = JadwalDokter::with(['dokter.spesialis', 'dokter.poli'])
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->get();
            
        return view('jadwal.index', compact('jadwals'));
    }
}
