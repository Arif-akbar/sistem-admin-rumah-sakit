<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use App\Models\Spesialis;
use App\Models\Poli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * DokterController - Mengelola data dokter
 * 
 * Fitur:
 * - Daftar dokter dengan filter spesialis/poli
 * - Tambah dokter baru
 * - Edit data dokter
 * - Hapus dokter
 * - Detail dokter + jadwal praktik
 */
class DokterController extends Controller
{
    /**
     * Tampilkan daftar semua dokter
     */
    public function index(Request $request)
    {
        try {
            $query = Dokter::with('spesialis', 'poli');

            // Filter by spesialis
            if ($request->filled('id_spesialis')) {
                $query->where('id_spesialis', $request->id_spesialis);
            }

            // Filter by poli
            if ($request->filled('id_poli')) {
                $query->where('id_poli', $request->id_poli);
            }

            // Search by nama
            if ($request->filled('search')) {
                $query->where('nama_dokter', 'like', '%' . $request->search . '%')
                    ->orWhere('nomor_sip', 'like', '%' . $request->search . '%');
            }

            $dokters = $query->orderBy('nama_dokter')->paginate(10);
            $spesialis = Spesialis::orderBy('nama_spesialis')->get();
            $polis = Poli::orderBy('nama_poli')->get();

            return view('dokter.index', compact('dokters', 'spesialis', 'polis'));
        } catch (\Exception $e) {
            return redirect()->route('dokter.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Form tambah dokter baru
     */
    public function create()
    {
        try {
            $spesialis = Spesialis::orderBy('nama_spesialis')->get();
            $polis = Poli::orderBy('nama_poli')->get();

            return view('dokter.create', compact('spesialis', 'polis'));
        } catch (\Exception $e) {
            return redirect()->route('dokter.index')
                ->with('error', 'Gagal membuka form: ' . $e->getMessage());
        }
    }

    /**
     * Simpan dokter baru
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama_dokter' => 'required|string|max:100',
                'nomor_sip' => 'required|string|max:50|unique:dokter,nomor_sip',
                'id_spesialis' => 'required|exists:spesialis,id',
                'id_poli' => 'required|exists:poli,id_poli',
                'alamat' => 'nullable|string|max:255',
                'telepon' => 'nullable|string|max:20',
                'email' => 'nullable|email|max:100',
                'status' => 'required|in:Aktif,Non-Aktif',
            ]);

            DB::transaction(function () use ($validated) {
                Dokter::create($validated);
            });

            return redirect()->route('dokter.index')
                ->with('success', 'Dokter ' . $validated['nama_dokter'] . ' berhasil ditambahkan!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menambahkan dokter: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Form edit dokter
     */
    public function edit($id)
    {
        try {
            $dokter = Dokter::findOrFail($id);
            $spesialis = Spesialis::orderBy('nama_spesialis')->get();
            $polis = Poli::orderBy('nama_poli')->get();

            return view('dokter.edit', compact('dokter', 'spesialis', 'polis'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('dokter.index')
                ->with('error', 'Dokter tidak ditemukan');
        } catch (\Exception $e) {
            return redirect()->route('dokter.index')
                ->with('error', 'Gagal membuka form: ' . $e->getMessage());
        }
    }

    /**
     * Update dokter
     */
    public function update(Request $request, $id)
    {
        try {
            $dokter = Dokter::findOrFail($id);

            $validated = $request->validate([
                'nama_dokter' => 'required|string|max:100',
                'nomor_sip' => 'required|string|max:50|unique:dokter,nomor_sip,' . $id . ',id_dokter',
                'id_spesialis' => 'required|exists:spesialis,id',
                'id_poli' => 'required|exists:poli,id_poli',
                'alamat' => 'nullable|string|max:255',
                'telepon' => 'nullable|string|max:20',
                'email' => 'nullable|email|max:100',
                'status' => 'required|in:Aktif,Non-Aktif',
            ]);

            DB::transaction(function () use ($dokter, $validated) {
                $dokter->update($validated);
            });

            return redirect()->route('dokter.index')
                ->with('success', 'Data dokter berhasil diperbarui!');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('dokter.index')
                ->with('error', 'Dokter tidak ditemukan');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui dokter: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Hapus dokter
     */
    public function destroy($id)
    {
        try {
            $dokter = Dokter::findOrFail($id);
            $nama = $dokter->nama_dokter;

            DB::transaction(function () use ($dokter) {
                // Hapus jadwal dokter terlebih dahulu (jika ada foreign key)
                $dokter->jadwal()->delete();
                $dokter->delete();
            });

            return redirect()->route('dokter.index')
                ->with('success', 'Dokter ' . $nama . ' berhasil dihapus!');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('dokter.index')
                ->with('error', 'Dokter tidak ditemukan');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus dokter: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan detail dokter
     */
    public function show($id)
    {
        try {
            $dokter = Dokter::with('spesialis', 'poli', 'jadwal')
                ->findOrFail($id);

            return view('dokter.show', compact('dokter'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('dokter.index')
                ->with('error', 'Dokter tidak ditemukan');
        } catch (\Exception $e) {
            return redirect()->route('dokter.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Get dokter berdasarkan spesialis (untuk AJAX)
     */
    public function getBySpesialis($id_spesialis)
    {
        try {
            $dokters = Dokter::where('id_spesialis', $id_spesialis)
                ->where('status', 'Aktif')
                ->with('spesialis')
                ->orderBy('nama_dokter')
                ->get();

            return response()->json($dokters);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get dokter berdasarkan poli (untuk AJAX)
     */
    public function getByPoli($id_poli)
    {
        try {
            $dokters = Dokter::where('id_poli', $id_poli)
                ->where('status', 'Aktif')
                ->with('spesialis', 'poli')
                ->orderBy('nama_dokter')
                ->get();

            return response()->json($dokters);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
