<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * RekamMedis Model
 * 
 * @property int $id ID Rekam Medis (Primary Key)
 * @property string $no_rm Nomor Rekam Medis (FK ke Pasien)
 * @property int $id_dokter ID Dokter (FK)
 * @property datetime $tgl_periksa Tanggal dan waktu pemeriksaan
 * @property string $keluhan_utama Keluhan utama pasien
 * @property string $diagnosis Diagnosis dari dokter
 * @property string $kode_icd10 Kode ICD-10 diagnosis
 * @property string $resep Resep obat
 * @property string $tindakan Tindakan medis yang dilakukan
 * @property string $catatan Catatan tambahan
 * @property string $tekanan_darah Tekanan darah (sistol/diastol)
 * @property int $denyut_nadi Denyut nadi (bpm)
 * @property int $respirasi Frekuensi pernapasan
 * @property float $suhu_tubuh Suhu tubuh dalam Celsius
 * @property float $berat_badan Berat badan pasien (kg)
 * @property float $tinggi_badan Tinggi badan pasien (cm)
 */
class RekamMedis extends Model
{
    protected $table = 'rekam_medis';

    protected $fillable = [
        'no_rm',
        'id_dokter',
        'tgl_periksa',
        'jam_periksa',
        'keluhan_utama',
        'diagnosis',
        'kode_icd10',
        'resep',
        'tindakan',
        'catatan',
        'tekanan_darah',
        'denyut_nadi',
        'respirasi',
        'suhu_tubuh',
        'berat_badan',
        'tinggi_badan',
    ];

    protected $casts = [
        'tgl_periksa' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'denyut_nadi' => 'integer',
        'respirasi' => 'integer',
        'suhu_tubuh' => 'float',
        'berat_badan' => 'float',
        'tinggi_badan' => 'float',
    ];

    /**
     * Relasi dengan Pasien
     */
    public function pasien(): BelongsTo
    {
        return $this->belongsTo(Pasien::class, 'no_rm', 'no_rm');
    }

    /**
     * Relasi dengan Dokter
     */
    public function dokter(): BelongsTo
    {
        return $this->belongsTo(Dokter::class, 'id_dokter');
    }

    /**
     * Scope untuk rekam medis pasien tertentu
     */
    public function scopePasien($query, $no_rm)
    {
        return $query->where('no_rm', $no_rm);
    }

    /**
     * Scope untuk rekam medis dokter tertentu
     */
    public function scopeDokter($query, $id_dokter)
    {
        return $query->where('id_dokter', $id_dokter);
    }

    /**
     * Scope untuk tanggal tertentu
     */
    public function scopeTanggal($query, $tanggal)
    {
        return $query->whereDate('tgl_periksa', $tanggal);
    }

    /**
     * Hitung IMB dari berat dan tinggi badan
     */
    public function getIMB(): ?float
    {
        if ($this->berat_badan && $this->tinggi_badan) {
            $tinggi = $this->tinggi_badan / 100; // Convert cm to m
            return $this->berat_badan / ($tinggi * $tinggi);
        }
        return null;
    }

    /**
     * Kategori IMB
     */
    public function getKategoriIMB(): ?string
    {
        $imb = $this->getIMB();

        if (!$imb) return null;
        if ($imb < 18.5) return 'Kurus';
        if ($imb <= 24.9) return 'Normal';
        if ($imb <= 29.9) return 'Overweight';
        return 'Obese';
    }
}
