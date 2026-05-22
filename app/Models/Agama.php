<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agama extends Model
{
    protected $table = 'agama';
    public $timestamps = false;
    protected $guarded = [];

    public function getNamaAgamaAttribute(): ?string
    {
        return $this->attributes['nama'] ?? null;
    }
}
