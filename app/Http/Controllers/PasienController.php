<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Models\Agama;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PasienController extends Controller
{
    public function index()
    {
        $pasiens = Pasien::orderBy('created_at', 'desc')->paginate(10);
        return view('pasien.index', compact('pasiens'));
    }

    public function create()
    {
        $agamas = Agama::all();
        return view('pasien.create', compact('agamas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|size:16|unique:pasien,nik',
            'nama_pasien' => 'required|string|max:100',
            'tgl_lahir' => 'required|date',
            'tempat_lahir' => 'nullable|string|max:50',
            'jenkel' => 'required|in:Laki-Laki,Perempuan',
            'id_agama' => 'required|exists:agama,id',
            'golongan_darah' => 'nullable|in:A,B,AB,O,Tidak Diketahui',
            'alamat' => 'required|string',
            'telepon' => 'nullable|string|max:20',
        ]);

        // Generate No RM (RM-000000 format)
        $lastRM = Pasien::orderBy('no_rm', 'desc')->first();
        if ($lastRM) {
            $number = (int) substr($lastRM->no_rm, 3);
            $nextRM = 'RM-' . str_pad($number + 1, 6, '0', STR_PAD_LEFT);
        } else {
            $nextRM = 'RM-000001';
        }

        Pasien::create([
            'no_rm' => $nextRM,
            'nik' => $request->nik,
            'nama_pasien' => $request->nama_pasien,
            'tgl_lahir' => $request->tgl_lahir,
            'tempat_lahir' => $request->tempat_lahir,
            'jenkel' => $request->jenkel,
            'id_agama' => $request->id_agama,
            'golongan_darah' => $request->golongan_darah ?? 'Tidak Diketahui',
            'alamat' => $request->alamat,
            'rt' => $request->rt,
            'rw' => $request->rw,
            'kelurahan' => $request->kelurahan,
            'kecamatan' => $request->kecamatan,
            'kota' => $request->kota,
            'kode_pos' => $request->kode_pos,
            'telepon' => $request->telepon,
            'no_bpjs' => $request->no_bpjs,
        ]);

        return redirect()->route('pasien.index')->with('success', 'Pasien berhasil ditambahkan!');
    }
}
