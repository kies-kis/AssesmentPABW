<?php

namespace Modules\TitikDrainase\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Laporan\Models\Laporan;

class TitikDrainase extends Model
{
    use HasFactory;

    protected $table = 'titik_drainases';

    protected $fillable = [
        'nama_titik',
        'kecamatan',
        'deskripsi',
        'kedalaman_cm',
    ];

    public function laporan(): HasMany
    {
        return $this->hasMany(Laporan::class, 'titik_drainase_id');
    }
}
