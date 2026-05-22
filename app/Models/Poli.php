<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Poli Model
 * 
 * @property int $id_poli ID Poli (Primary Key)
 * @property string $nama_poli Nama poli/departemen
 * @property string $deskripsi Deskripsi poli
 * @property string $lokasi Lokasi ruangan
 * @property string $telepon Nomor telepon poli
 * @property string $jam_buka Jam buka operasional (HH:MM)
 * @property string $jam_tutup Jam tutup operasional (HH:MM)
 * @property string $status Status (Aktif/Non-Aktif)
 */
class Poli extends Model
{
    protected $table = 'poli';
    protected $primaryKey = 'id_poli';

    protected $fillable = [
        'kode_poli',
        'nama_poli',
        'keterangan',
        'deskripsi',
        'status',
        'is_active',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi dengan Dokter
     */
    public function dokter(): HasMany
    {
        return $this->hasMany(Dokter::class, 'id_poli');
    }

    /**
     * Scope untuk poli aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('is_active', 1);
    }

    /**
     * Get jumlah dokter di poli ini
     */
    public function getJumlahDokter(): int
    {
        return $this->dokter()->count();
    }

    /**
     * Get dokter aktif di poli ini
     */
    public function getDokterAktif(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->dokter()->where('is_active', 1)->get();
    }

    public function getDeskripsiAttribute(): ?string
    {
        return $this->attributes['keterangan'] ?? null;
    }

    public function setDeskripsiAttribute($value): void
    {
        $this->attributes['keterangan'] = $value;
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
