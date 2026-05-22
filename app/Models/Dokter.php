<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dokter extends Model
{
    protected $table = 'dokter';
    protected $primaryKey = 'id_dokter';

    protected $guarded = [];

    public function spesialis()
    {
        return $this->belongsTo(Spesialis::class, 'id_spesialis');
    }

    public function poli()
    {
        return $this->belongsTo(Poli::class, 'id_poli');
    }

    public function jadwal()
    {
        return $this->hasMany(JadwalDokter::class, 'id_dokter');
    }
}
