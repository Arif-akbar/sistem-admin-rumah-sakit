<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalDokter extends Model
{
    protected $table = 'jadwal_dokter';
    public $timestamps = false;

    protected $guarded = [];

    public function dokter()
    {
        return $this->belongsTo(Dokter::class, 'id_dokter');
    }
}
