<?php

namespace App\Http\Controllers;

use App\Models\Poli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * PoliController - Mengelola unit pelayanan/departemen poli
 * 
 * Fitur:
 * - Daftar poli/departemen
 * - Tambah poli baru
 * - Edit data poli
 * - Hapus poli
 * - Detail poli + dokter yang bertugas
 */
class PoliController extends Controller
{
    /**
     * Tampilkan daftar semua poli
     */
    public function index(Request $request)
    {
        try {
            $query = Poli::query();

            // Search by nama poli
            if ($request->filled('search')) {
                $query->where('nama_poli', 'like', '%' . $request->search . '%');
            }

            // Filter by status
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            $polis = $query->orderBy('nama_poli')->paginate(10);

            return view('poli.index', compact('polis'));
        } catch (\Exception $e) {
            return redirect()->route('poli.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Form tambah poli baru
     */
    public function create()
    {
        try {
            return view('poli.create');
        } catch (\Exception $e) {
            return redirect()->route('poli.index')
                ->with('error', 'Gagal membuka form: ' . $e->getMessage());
        }
    }

    /**
     * Simpan poli baru
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama_poli' => 'required|string|max:100|unique:poli,nama_poli',
                'deskripsi' => 'nullable|string|max:500',
                'lokasi' => 'nullable|string|max:100',
                'telepon' => 'nullable|string|max:20',
                'jam_buka' => 'nullable|date_format:H:i',
                'jam_tutup' => 'nullable|date_format:H:i|after:jam_buka',
                'status' => 'required|in:Aktif,Non-Aktif',
            ], [
                'jam_tutup.after' => 'Jam tutup harus lebih besar dari jam buka',
            ]);

            DB::transaction(function () use ($validated) {
                Poli::create($validated);
            });

            return redirect()->route('poli.index')
                ->with('success', 'Poli ' . $validated['nama_poli'] . ' berhasil ditambahkan!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menambahkan poli: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Form edit poli
     */
    public function edit($id)
    {
        try {
            $poli = Poli::findOrFail($id);
            return view('poli.edit', compact('poli'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('poli.index')
                ->with('error', 'Poli tidak ditemukan');
        } catch (\Exception $e) {
            return redirect()->route('poli.index')
                ->with('error', 'Gagal membuka form: ' . $e->getMessage());
        }
    }

    /**
     * Update poli
     */
    public function update(Request $request, $id)
    {
        try {
            $poli = Poli::findOrFail($id);

            $validated = $request->validate([
                'nama_poli' => 'required|string|max:100|unique:poli,nama_poli,' . $id . ',id_poli',
                'deskripsi' => 'nullable|string|max:500',
                'lokasi' => 'nullable|string|max:100',
                'telepon' => 'nullable|string|max:20',
                'jam_buka' => 'nullable|date_format:H:i',
                'jam_tutup' => 'nullable|date_format:H:i|after:jam_buka',
                'status' => 'required|in:Aktif,Non-Aktif',
            ]);

            DB::transaction(function () use ($poli, $validated) {
                $poli->update($validated);
            });

            return redirect()->route('poli.index')
                ->with('success', 'Data poli berhasil diperbarui!');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('poli.index')
                ->with('error', 'Poli tidak ditemukan');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui poli: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Hapus poli
     */
    public function destroy($id)
    {
        try {
            $poli = Poli::findOrFail($id);
            $nama = $poli->nama_poli;

            // Cek apakah ada dokter yang terdaftar di poli ini
            if ($poli->dokter()->count() > 0) {
                return redirect()->route('poli.index')
                    ->with('error', 'Tidak bisa menghapus poli yang masih memiliki dokter');
            }

            DB::transaction(function () use ($poli) {
                $poli->delete();
            });

            return redirect()->route('poli.index')
                ->with('success', 'Poli ' . $nama . ' berhasil dihapus!');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('poli.index')
                ->with('error', 'Poli tidak ditemukan');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus poli: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan detail poli
     */
    public function show($id)
    {
        try {
            $poli = Poli::with('dokter.spesialis')
                ->findOrFail($id);

            $dokters = $poli->dokter()
                ->with('spesialis')
                ->orderBy('nama_dokter')
                ->get();

            return view('poli.show', compact('poli', 'dokters'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('poli.index')
                ->with('error', 'Poli tidak ditemukan');
        } catch (\Exception $e) {
            return redirect()->route('poli.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Get semua poli aktif (untuk dropdown/select)
     */
    public function getAktif()
    {
        try {
            $polis = Poli::where('status', 'Aktif')
                ->orderBy('nama_poli')
                ->get();

            return response()->json($polis);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
