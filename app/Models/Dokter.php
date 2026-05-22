<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Dokter Model
 * 
 * @property int $id_dokter ID Dokter (Primary Key)
 * @property string $nama_dokter Nama lengkap dokter
 * @property string $nomor_sip Nomor SIP (Surat Izin Praktik)
 * @property int $id_spesialis ID spesialisasi
 * @property int $id_poli ID poli/departemen
 * @property string $alamat Alamat
 * @property string $telepon Nomor telepon
 * @property string $email Email address
 * @property string $status Status (Aktif/Non-Aktif)
 */
class Dokter extends Model
{
    protected $table = 'dokter';
    protected $primaryKey = 'id_dokter';

    protected $fillable = [
        'kode_dokter',
        'nama_dokter',
        'nomor_sip',
        'sip_number',
        'id_spesialis',
        'id_poli',
        'telepon',
        'email',
        'status',
        'is_active',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi dengan Spesialis
     */
    public function spesialis(): BelongsTo
    {
        return $this->belongsTo(Spesialis::class, 'id_spesialis');
    }

    /**
     * Relasi dengan Poli
     */
    public function poli(): BelongsTo
    {
        return $this->belongsTo(Poli::class, 'id_poli');
    }

    /**
     * Relasi dengan JadwalDokter
     */
    public function jadwal(): HasMany
    {
        return $this->hasMany(JadwalDokter::class, 'id_dokter');
    }

    /**
     * Relasi dengan RekamMedis
     */
    public function rekamMedis(): HasMany
    {
        return $this->hasMany(RekamMedis::class, 'id_dokter');
    }

    /**
     * Scope untuk dokter aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('is_active', 1);
    }

    /**
     * Scope untuk spesialis tertentu
     */
    public function scopeBySpesialis($query, $id_spesialis)
    {
        return $query->where('id_spesialis', $id_spesialis);
    }

    /**
     * Scope untuk poli tertentu
     */
    public function scopeByPoli($query, $id_poli)
    {
        return $query->where('id_poli', $id_poli);
    }

    public function getNomorSipAttribute(): ?string
    {
        return $this->attributes['sip_number'] ?? null;
    }

    public function setNomorSipAttribute($value): void
    {
        $this->attributes['sip_number'] = $value;
    }

    public function getStatusAttribute(): string
    {
        return ($this->attributes['is_active'] ?? 1) ? 'Aktif' : 'Non-Aktif';
    }

    public function setStatusAttribute($value): void
    {
        $this->attributes['is_active'] = $value === 'Aktif' ? 1 : 0;
    }
}
