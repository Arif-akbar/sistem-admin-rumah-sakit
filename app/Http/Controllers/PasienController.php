<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use Illuminate\Http\Request;

class PasienController extends Controller
{
    public function index()
    {
        $pasiens = Pasien::orderBy('created_at', 'desc')->paginate(10);
        return view('pasien.index', compact('pasiens'));
    }
}
