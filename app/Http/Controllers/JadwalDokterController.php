<?php

namespace App\Http\Controllers;

use App\Models\JadwalDokter;
use App\Models\Dokter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * JadwalDokterController - Mengelola jadwal praktik dokter
 * 
 * Fitur:
 * - Daftar jadwal dokter dengan filter
 * - Tambah jadwal praktik baru
 * - Edit jadwal yang sudah ada
 * - Hapus jadwal
 * - Cari jadwal dokter berdasarkan hari/jam
 * - Validasi konflik jadwal (satu dokter tidak bisa 2 jadwal 1 waktu)
 */
class JadwalDokterController extends Controller
{
    /**
     * Tampilkan daftar semua jadwal dokter
     */
    public function index(Request $request)
    {
        try {
            $query = JadwalDokter::with('dokter.spesialis', 'dokter.poli');

            // Filter by hari
            if ($request->filled('hari')) {
                $query->where('hari', $request->hari);
            }

            // Filter by dokter
            if ($request->filled('id_dokter')) {
                $query->where('id_dokter', $request->id_dokter);
            }

            $jadwals = $query->orderBy('hari')
                ->orderBy('jam_mulai')
                ->paginate(15);

            $dokters = Dokter::with('spesialis', 'poli')
                ->orderBy('nama_dokter')
                ->get();

            $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

            return view('jadwal.index', compact('jadwals', 'dokters', 'days'));
        } catch (\Exception $e) {
            return redirect()->route('jadwal.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Form tambah jadwal baru
     */
    public function create()
    {
        try {
            $dokters = Dokter::with('spesialis', 'poli')
                ->orderBy('nama_dokter')
                ->get();
            $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

            return view('jadwal.create', compact('dokters', 'days'));
        } catch (\Exception $e) {
            return redirect()->route('jadwal.index')
                ->with('error', 'Gagal membuka form: ' . $e->getMessage());
        }
    }

    /**
     * Simpan jadwal baru
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'id_dokter' => 'required|exists:dokter,id_dokter',
                'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
                'jam_mulai' => 'required|date_format:H:i',
                'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
                'kuota_pasien' => 'required|integer|min:1|max:100',
            ], [
                'jam_selesai.after' => 'Jam selesai harus lebih besar dari jam mulai',
                'kuota_pasien.min' => 'Kuota minimal 1 pasien',
            ]);

            // Validasi tidak ada konflik jadwal (satu dokter tidak bisa 2 jadwal 1 waktu)
            $this->validateJadwalKonflik($validated);

            DB::transaction(function () use ($validated) {
                JadwalDokter::create($validated);
            });

            return redirect()->route('jadwal.index')
                ->with('success', 'Jadwal dokter berhasil ditambahkan!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menambahkan jadwal: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Form edit jadwal
     */
    public function edit($id)
    {
        try {
            $jadwal = JadwalDokter::findOrFail($id);
            $dokters = Dokter::with('spesialis', 'poli')
                ->orderBy('nama_dokter')
                ->get();
            $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

            return view('jadwal.edit', compact('jadwal', 'dokters', 'days'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('jadwal.index')
                ->with('error', 'Jadwal tidak ditemukan');
        } catch (\Exception $e) {
            return redirect()->route('jadwal.index')
                ->with('error', 'Gagal membuka form: ' . $e->getMessage());
        }
    }

    /**
     * Update jadwal
     */
    public function update(Request $request, $id)
    {
        try {
            $jadwal = JadwalDokter::findOrFail($id);

            $validated = $request->validate([
                'id_dokter' => 'required|exists:dokter,id_dokter',
                'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
                'jam_mulai' => 'required|date_format:H:i',
                'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
                'kuota_pasien' => 'required|integer|min:1|max:100',
            ]);

            // Validasi konflik, exclude jadwal yang sedang diedit
            $this->validateJadwalKonflik($validated, $id);

            DB::transaction(function () use ($jadwal, $validated) {
                $jadwal->update($validated);
            });

            return redirect()->route('jadwal.index')
                ->with('success', 'Jadwal dokter berhasil diperbarui!');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('jadwal.index')
                ->with('error', 'Jadwal tidak ditemukan');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui jadwal: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Hapus jadwal
     */
    public function destroy($id)
    {
        try {
            $jadwal = JadwalDokter::findOrFail($id);
            $dokterName = $jadwal->dokter->nama_dokter ?? 'Dokter';

            DB::transaction(function () use ($jadwal) {
                $jadwal->delete();
            });

            return redirect()->route('jadwal.index')
                ->with('success', 'Jadwal dokter berhasil dihapus!');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('jadwal.index')
                ->with('error', 'Jadwal tidak ditemukan');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus jadwal: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan detail jadwal
     */
    public function show($id)
    {
        try {
            $jadwal = JadwalDokter::with('dokter.spesialis', 'dokter.poli')
                ->findOrFail($id);

            return view('jadwal.show', compact('jadwal'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('jadwal.index')
                ->with('error', 'Jadwal tidak ditemukan');
        } catch (\Exception $e) {
            return redirect()->route('jadwal.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Validasi konflik jadwal (satu dokter tidak bisa 2 jadwal 1 waktu)
     */
    private function validateJadwalKonflik($data, $excludeId = null)
    {
        $query = JadwalDokter::where('id_dokter', $data['id_dokter'])
            ->where('hari', $data['hari']);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        $konflik = $query->where(function ($q) use ($data) {
            $q->whereBetween('jam_mulai', [$data['jam_mulai'], $data['jam_selesai']])
                ->orWhereBetween('jam_selesai', [$data['jam_mulai'], $data['jam_selesai']]);
        })->exists();

        if ($konflik) {
            throw new \Exception('Dokter sudah mempunyai jadwal pada hari dan jam tersebut');
        }
    }

    /**
     * Get jadwal dokter berdasarkan id_dokter (untuk AJAX)
     */
    public function getByDokter($id_dokter)
    {
        try {
            $jadwals = JadwalDokter::where('id_dokter', $id_dokter)
                ->with('dokter.spesialis')
                ->orderBy('hari')
                ->orderBy('jam_mulai')
                ->get();

            return response()->json($jadwals);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get jadwal berdasarkan poli (untuk pendaftaran)
     */
    public function getByPoli($id_poli)
    {
        try {
            $jadwals = JadwalDokter::whereHas('dokter', function ($q) use ($id_poli) {
                $q->where('id_poli', $id_poli);
            })
                ->with('dokter.spesialis')
                ->orderBy('hari')
                ->get();

            return response()->json($jadwals);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
