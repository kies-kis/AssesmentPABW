<?php

namespace Modules\Laporan\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\TitikDrainase\Models\TitikDrainase;

class Laporan extends Model
{
    use HasFactory;

    public const STATUS_KONDISI = [
        'Normal' => 'Normal',
        'Tersumbat' => 'Tersumbat',
        'Meluap' => 'Meluap',
    ];

    protected $table = 'laporans';

    protected $fillable = [
        'user_id',
        'titik_drainase_id',
        'tinggi_air_cm',
        'status_kondisi',
        'deskripsi',
        'foto_bukti',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function titikDrainase(): BelongsTo
    {
        return $this->belongsTo(TitikDrainase::class, 'titik_drainase_id');
    }

    public function badgeStatus(): string
    {
        return match ($this->status_kondisi) {
            'Normal' => 'success',
            'Tersumbat' => 'warning',
            default => 'danger',
        };
    }
}
