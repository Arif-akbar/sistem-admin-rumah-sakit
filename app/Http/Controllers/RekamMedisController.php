<?php

namespace App\Http\Controllers;

use App\Models\RekamMedis;
use Illuminate\Http\Request;

class RekamMedisController extends Controller
{
    public function index()
    {
        $rekams = RekamMedis::with(['pasien', 'dokter.spesialis'])
            ->orderBy('tgl_periksa', 'desc')
            ->paginate(10);
            
        return view('rekam_medis.index', compact('rekams'));
    }
}
