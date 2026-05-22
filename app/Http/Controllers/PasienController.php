<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Models\Agama;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * PasienController - Mengelola data pasien rumah sakit
 * 
 * Fitur:
 * - Daftar pasien dengan pagination & pencarian
 * - Tambah pasien baru dengan validasi NIK & No.RM otomatis
 * - Ubah data pasien
 * - Hapus data pasien
 * - Detail pasien + riwayat kunjungan
 */
class PasienController extends Controller
{
    /**
     * Tampilkan daftar semua pasien
     */
    public function index(Request $request)
    {
        try {
            $query = Pasien::query();

            // Search by nama, NIK, atau No.RM
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where('nama_pasien', 'like', "%$search%")
                    ->orWhere('nik', 'like', "%$search%")
                    ->orWhere('no_rm', 'like', "%$search%");
            }

            // Filter by gender
            if ($request->filled('jenkel')) {
                $query->where('jenkel', $request->jenkel);
            }

            $pasiens = $query->with('agama')
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return view('pasien.index', compact('pasiens'));
        } catch (\Exception $e) {
            return redirect()->route('pasien.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Form tambah pasien baru
     */
    public function create()
    {
        try {
            $agamas = Agama::orderBy('nama_agama')->get();
            return view('pasien.create', compact('agamas'));
        } catch (\Exception $e) {
            return redirect()->route('pasien.index')
                ->with('error', 'Gagal membuka form: ' . $e->getMessage());
        }
    }

    /**
     * Simpan pasien baru ke database
     */
    public function store(Request $request)
    {
        try {
            // Validasi input
            $validated = $request->validate([
                'nik' => 'required|size:16|numeric|unique:pasien,nik',
                'nama_pasien' => 'required|string|max:100',
                'tgl_lahir' => 'required|date|before:today',
                'tempat_lahir' => 'nullable|string|max:50',
                'jenkel' => 'required|in:Laki-Laki,Perempuan',
                'id_agama' => 'required|exists:agama,id',
                'golongan_darah' => 'nullable|in:A,B,AB,O,Tidak Diketahui',
                'alamat' => 'required|string|max:255',
                'rt' => 'nullable|string|max:5',
                'rw' => 'nullable|string|max:5',
                'kelurahan' => 'nullable|string|max:50',
                'kecamatan' => 'nullable|string|max:50',
                'kota' => 'nullable|string|max:50',
                'kode_pos' => 'nullable|string|max:10',
                'telepon' => 'nullable|string|max:20',
                'no_bpjs' => 'nullable|string|max:20|unique:pasien,no_bpjs',
            ], [
                'nik.unique' => 'NIK sudah terdaftar dalam sistem',
                'nik.size' => 'NIK harus 16 digit',
                'no_bpjs.unique' => 'No. BPJS sudah terdaftar',
            ]);

            // Generate No.RM otomatis
            $nextRM = $this->generateNoRM();
            $validated['no_rm'] = $nextRM;
            $validated['golongan_darah'] = $validated['golongan_darah'] ?? 'Tidak Diketahui';

            // Simpan ke database dalam transaction
            DB::transaction(function () use ($validated) {
                Pasien::create($validated);
            });

            return redirect()->route('pasien.index')
                ->with('success', 'Pasien ' . $validated['nama_pasien'] . ' berhasil ditambahkan!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menambahkan pasien: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Tampilkan form edit pasien
     */
    public function edit($no_rm)
    {
        try {
            $pasien = Pasien::findOrFail($no_rm);
            $agamas = Agama::orderBy('nama_agama')->get();
            return view('pasien.edit', compact('pasien', 'agamas'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('pasien.index')
                ->with('error', 'Pasien tidak ditemukan');
        } catch (\Exception $e) {
            return redirect()->route('pasien.index')
                ->with('error', 'Gagal membuka form: ' . $e->getMessage());
        }
    }

    /**
     * Update data pasien
     */
    public function update(Request $request, $no_rm)
    {
        try {
            $pasien = Pasien::findOrFail($no_rm);

            $validated = $request->validate([
                'nama_pasien' => 'required|string|max:100',
                'tgl_lahir' => 'required|date|before:today',
                'tempat_lahir' => 'nullable|string|max:50',
                'jenkel' => 'required|in:Laki-Laki,Perempuan',
                'id_agama' => 'required|exists:agama,id',
                'golongan_darah' => 'nullable|in:A,B,AB,O,Tidak Diketahui',
                'alamat' => 'required|string|max:255',
                'rt' => 'nullable|string|max:5',
                'rw' => 'nullable|string|max:5',
                'kelurahan' => 'nullable|string|max:50',
                'kecamatan' => 'nullable|string|max:50',
                'kota' => 'nullable|string|max:50',
                'kode_pos' => 'nullable|string|max:10',
                'telepon' => 'nullable|string|max:20',
                'no_bpjs' => 'nullable|string|max:20|unique:pasien,no_bpjs,' . $no_rm . ',no_rm',
            ]);

            $validated['golongan_darah'] = $validated['golongan_darah'] ?? 'Tidak Diketahui';

            DB::transaction(function () use ($pasien, $validated) {
                $pasien->update($validated);
            });

            return redirect()->route('pasien.index')
                ->with('success', 'Data pasien berhasil diperbarui!');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('pasien.index')
                ->with('error', 'Pasien tidak ditemukan');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui data: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Tampilkan detail pasien
     */
    public function show($no_rm)
    {
        try {
            $pasien = Pasien::with('agama')->findOrFail($no_rm);
            // Load riwayat kunjungan jika ada relasi
            if ($pasien->rekamMedis) {
                $rekamMedis = $pasien->rekamMedis()
                    ->with('dokter.spesialis')
                    ->orderBy('tgl_periksa', 'desc')
                    ->get();
            } else {
                $rekamMedis = collect();
            }

            return view('pasien.show', compact('pasien', 'rekamMedis'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('pasien.index')
                ->with('error', 'Pasien tidak ditemukan');
        } catch (\Exception $e) {
            return redirect()->route('pasien.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Hapus data pasien
     */
    public function destroy($no_rm)
    {
        try {
            $pasien = Pasien::findOrFail($no_rm);
            $nama = $pasien->nama_pasien;

            DB::transaction(function () use ($pasien) {
                // Hapus rekam medis terlebih dahulu (jika ada foreign key)
                $pasien->rekamMedis()->delete();
                $pasien->delete();
            });

            return redirect()->route('pasien.index')
                ->with('success', 'Pasien ' . $nama . ' berhasil dihapus!');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('pasien.index')
                ->with('error', 'Pasien tidak ditemukan');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus pasien: ' . $e->getMessage());
        }
    }

    /**
     * Generate No.RM otomatis dengan format RM-XXXXXX
     */
    private function generateNoRM()
    {
        $lastRM = Pasien::orderBy('created_at', 'desc')->first();

        if ($lastRM) {
            $lastNumber = (int) substr($lastRM->no_rm, 3);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return 'RM-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Search pasien via AJAX
     */
    public function search(Request $request)
    {
        try {
            $query = $request->input('q', '');

            if (strlen($query) < 2) {
                return response()->json(['error' => 'Minimal 2 karakter'], 400);
            }

            $results = Pasien::where('nama_pasien', 'like', "%$query%")
                ->orWhere('nik', 'like', "%$query%")
                ->orWhere('no_rm', 'like', "%$query%")
                ->limit(10)
                ->get(['no_rm', 'nik', 'nama_pasien']);

            return response()->json($results);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
