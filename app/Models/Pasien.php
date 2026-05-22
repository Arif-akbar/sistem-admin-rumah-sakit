<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Pasien Model
 * 
 * @property string $no_rm Nomor Rekam Medis (Primary Key)
 * @property string $nik Nomor Identitas Kependudukan
 * @property string $nama_pasien Nama lengkap pasien
 * @property date $tgl_lahir Tanggal lahir
 * @property string $tempat_lahir Tempat lahir
 * @property string $jenkel Jenis kelamin (Laki-Laki/Perempuan)
 * @property int $id_agama ID agama
 * @property string $golongan_darah Golongan darah (A/B/AB/O)
 * @property string $alamat Alamat lengkap
 * @property string $no_bpjs Nomor BPJS
 */
class Pasien extends Model
{
    protected $table = 'pasien';
    protected $primaryKey = 'no_rm';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'no_rm',
        'nik',
        'nama_pasien',
        'tgl_lahir',
        'tempat_lahir',
        'jenkel',
        'id_agama',
        'golongan_darah',
        'alamat',
        'rt',
        'rw',
        'kelurahan',
        'kecamatan',
        'kota',
        'kode_pos',
        'telepon',
        'no_bpjs',
    ];

    protected $casts = [
        'tgl_lahir' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi dengan Agama
     */
    public function agama(): BelongsTo
    {
        return $this->belongsTo(Agama::class, 'id_agama');
    }

    /**
     * Relasi dengan RekamMedis
     */
    public function rekamMedis(): HasMany
    {
        return $this->hasMany(RekamMedis::class, 'no_rm', 'no_rm');
    }

    /**
     * Hitung umur pasien
     */
    public function getUmur(): int
    {
        return $this->tgl_lahir->age;
    }

    /**
     * Format nomor RM untuk display
     */
    public function getFormattedNoRM(): string
    {
        return strtoupper($this->no_rm);
    }
}
