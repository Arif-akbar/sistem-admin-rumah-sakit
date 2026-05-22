<?php

namespace App\Http\Controllers;

use App\Models\RekamMedis;
use App\Models\Pasien;
use App\Models\Dokter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * RekamMedisController - Mengelola rekam medis digital pasien
 * 
 * Fitur:
 * - Daftar rekam medis dengan pagination
 * - Tambah rekam medis baru (dari pendaftaran)
 * - Edit rekam medis
 * - Hapus rekam medis
 * - Detail rekam medis (dengan timeline)
 * - Cetak rekam medis
 * - Filter berdasarkan pasien/dokter/tanggal
 */
class RekamMedisController extends Controller
{
    /**
     * Tampilkan daftar rekam medis
     */
    public function index(Request $request)
    {
        try {
            $query = RekamMedis::with('pasien', 'dokter.spesialis');

            // Filter by pasien
            if ($request->filled('no_rm')) {
                $query->where('no_rm', $request->no_rm);
            }

            // Filter by dokter
            if ($request->filled('id_dokter')) {
                $query->where('id_dokter', $request->id_dokter);
            }

            // Filter by tanggal
            if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
                $query->whereBetween('tgl_periksa', [$request->tanggal_awal, $request->tanggal_akhir]);
            }

            $rekams = $query->orderBy('tgl_periksa', 'desc')
                ->paginate(10);

            $dokters = Dokter::orderBy('nama_dokter')->get();

            return view('rekam_medis.index', compact('rekams', 'dokters'));
        } catch (\Exception $e) {
            return redirect()->route('rekam_medis.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Form tambah rekam medis
     */
    public function create()
    {
        try {
            $pasiens = Pasien::orderBy('nama_pasien')->get();
            $dokters = Dokter::with('spesialis')
                ->orderBy('nama_dokter')
                ->get();

            return view('rekam_medis.create', compact('pasiens', 'dokters'));
        } catch (\Exception $e) {
            return redirect()->route('rekam_medis.index')
                ->with('error', 'Gagal membuka form: ' . $e->getMessage());
        }
    }

    /**
     * Simpan rekam medis baru
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'no_rm' => 'required|exists:pasien,no_rm',
                'id_dokter' => 'required|exists:dokter,id_dokter',
                'tgl_periksa' => 'required|date|before_or_equal:today',
                'jam_periksa' => 'required|date_format:H:i',
                'keluhan_utama' => 'required|string|max:500',
                'diagnosis' => 'nullable|string|max:500',
                'kode_icd10' => 'nullable|string|max:10',
                'resep' => 'nullable|string',
                'tindakan' => 'nullable|string',
                'catatan' => 'nullable|string',
                // Vital signs
                'tekanan_darah' => 'nullable|string|max:20',
                'denyut_nadi' => 'nullable|integer|min:0|max:200',
                'respirasi' => 'nullable|integer|min:0|max:100',
                'suhu_tubuh' => 'nullable|numeric|min:35|max:42',
                'berat_badan' => 'nullable|numeric|min:0|max:500',
                'tinggi_badan' => 'nullable|numeric|min:0|max:300',
            ]);

            $validated['tgl_periksa'] = $request->tgl_periksa . ' ' . $request->jam_periksa;

            DB::transaction(function () use ($validated) {
                RekamMedis::create($validated);
            });

            return redirect()->route('rekam_medis.index')
                ->with('success', 'Rekam medis berhasil ditambahkan!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menambahkan rekam medis: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Form edit rekam medis
     */
    public function edit($id)
    {
        try {
            $rekamMedis = RekamMedis::findOrFail($id);
            $pasiens = Pasien::orderBy('nama_pasien')->get();
            $dokters = Dokter::with('spesialis')
                ->orderBy('nama_dokter')
                ->get();

            return view('rekam_medis.edit', compact('rekamMedis', 'pasiens', 'dokters'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('rekam_medis.index')
                ->with('error', 'Rekam medis tidak ditemukan');
        } catch (\Exception $e) {
            return redirect()->route('rekam_medis.index')
                ->with('error', 'Gagal membuka form: ' . $e->getMessage());
        }
    }

    /**
     * Update rekam medis
     */
    public function update(Request $request, $id)
    {
        try {
            $rekamMedis = RekamMedis::findOrFail($id);

            $validated = $request->validate([
                'no_rm' => 'required|exists:pasien,no_rm',
                'id_dokter' => 'required|exists:dokter,id_dokter',
                'tgl_periksa' => 'required|date|before_or_equal:today',
                'jam_periksa' => 'required|date_format:H:i',
                'keluhan_utama' => 'required|string|max:500',
                'diagnosis' => 'nullable|string|max:500',
                'kode_icd10' => 'nullable|string|max:10',
                'resep' => 'nullable|string',
                'tindakan' => 'nullable|string',
                'catatan' => 'nullable|string',
                'tekanan_darah' => 'nullable|string|max:20',
                'denyut_nadi' => 'nullable|integer|min:0|max:200',
                'respirasi' => 'nullable|integer|min:0|max:100',
                'suhu_tubuh' => 'nullable|numeric|min:35|max:42',
                'berat_badan' => 'nullable|numeric|min:0|max:500',
                'tinggi_badan' => 'nullable|numeric|min:0|max:300',
            ]);

            $validated['tgl_periksa'] = $request->tgl_periksa . ' ' . $request->jam_periksa;

            DB::transaction(function () use ($rekamMedis, $validated) {
                $rekamMedis->update($validated);
            });

            return redirect()->route('rekam_medis.index')
                ->with('success', 'Rekam medis berhasil diperbarui!');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('rekam_medis.index')
                ->with('error', 'Rekam medis tidak ditemukan');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui rekam medis: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Hapus rekam medis
     */
    public function destroy($id)
    {
        try {
            $rekamMedis = RekamMedis::findOrFail($id);

            DB::transaction(function () use ($rekamMedis) {
                $rekamMedis->delete();
            });

            return redirect()->route('rekam_medis.index')
                ->with('success', 'Rekam medis berhasil dihapus!');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('rekam_medis.index')
                ->with('error', 'Rekam medis tidak ditemukan');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus rekam medis: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan detail rekam medis
     */
    public function show($id)
    {
        try {
            $rekamMedis = RekamMedis::with('pasien', 'dokter.spesialis')
                ->findOrFail($id);

            return view('rekam_medis.show', compact('rekamMedis'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('rekam_medis.index')
                ->with('error', 'Rekam medis tidak ditemukan');
        } catch (\Exception $e) {
            return redirect()->route('rekam_medis.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Riwayat rekam medis pasien (timeline)
     */
    public function riwayatPasien($no_rm)
    {
        try {
            $pasien = Pasien::findOrFail($no_rm);
            $rekams = RekamMedis::where('no_rm', $no_rm)
                ->with('dokter.spesialis')
                ->orderBy('tgl_periksa', 'desc')
                ->get();

            return view('rekam_medis.riwayat', compact('pasien', 'rekams'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('pasien.index')
                ->with('error', 'Pasien tidak ditemukan');
        } catch (\Exception $e) {
            return redirect()->route('pasien.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Cetak rekam medis
     */
    public function cetak($id)
    {
        try {
            $rekamMedis = RekamMedis::with('pasien', 'dokter.spesialis')
                ->findOrFail($id);

            return view('rekam_medis.cetak', compact('rekamMedis'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('rekam_medis.index')
                ->with('error', 'Rekam medis tidak ditemukan');
        } catch (\Exception $e) {
            return redirect()->route('rekam_medis.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Statistik rekam medis (untuk dashboard)
     */
    public function getStatistik(Request $request)
    {
        try {
            $bulan = $request->input('bulan', date('Y-m'));

            $total = RekamMedis::whereYear('tgl_periksa', substr($bulan, 0, 4))
                ->whereMonth('tgl_periksa', substr($bulan, 5, 2))
                ->count();

            $byDokter = RekamMedis::selectRaw('id_dokter, COUNT(*) as total')
                ->whereYear('tgl_periksa', substr($bulan, 0, 4))
                ->whereMonth('tgl_periksa', substr($bulan, 5, 2))
                ->with('dokter')
                ->groupBy('id_dokter')
                ->get();

            return response()->json([
                'total' => $total,
                'byDokter' => $byDokter
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
