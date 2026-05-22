<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * JadwalDokter Model
 * 
 * @property int $id ID Jadwal (Primary Key)
 * @property int $id_dokter ID Dokter (FK)
 * @property string $hari Hari praktik (Senin-Minggu)
 * @property string $jam_mulai Jam mulai praktik (HH:MM)
 * @property string $jam_selesai Jam selesai praktik (HH:MM)
 * @property int $kuota_pasien Jumlah kuota pasien per sesi
 */
class JadwalDokter extends Model
{
    protected $table = 'jadwal_dokter';
    public $timestamps = false;

    protected $fillable = [
        'id_dokter',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'kuota',
        'kuota_pasien',
        'is_active',
    ];

    /**
     * Relasi dengan Dokter
     */
    public function dokter(): BelongsTo
    {
        return $this->belongsTo(Dokter::class, 'id_dokter');
    }

    /**
     * Scope untuk jadwal pada hari tertentu
     */
    public function scopeHari($query, $hari)
    {
        return $query->where('hari', $hari);
    }

    /**
     * Scope untuk jadwal dokter tertentu
     */
    public function scopeDokter($query, $id_dokter)
    {
        return $query->where('id_dokter', $id_dokter);
    }

    /**
     * Format jam untuk display (HH:MM)
     */
    public function getFormattedJamMulai(): string
    {
        return date('H:i', strtotime($this->jam_mulai));
    }

    /**
     * Format jam untuk display (HH:MM)
     */
    public function getFormattedJamSelesai(): string
    {
        return date('H:i', strtotime($this->jam_selesai));
    }

    /**
     * Durasi jadwal dalam menit
     */
    public function getDurasi(): int
    {
        $mulai = strtotime($this->jam_mulai);
        $selesai = strtotime($this->jam_selesai);
        return round(($selesai - $mulai) / 60);
    }

    public function getKuotaPasienAttribute(): ?int
    {
        return $this->attributes['kuota'] ?? null;
    }

    public function setKuotaPasienAttribute($value): void
    {
        $this->attributes['kuota'] = $value;
    }
}
