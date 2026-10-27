<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WaterQualityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'pond_id',
        'cycle_id',
        'recorded_at',
        'temperature',
        'ph',
        'dissolved_oxygen',
        'salinity',
        'turbidity',
        'ammonia',
        'alkalinity',
        'condition_status',
        'recorded_by',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'recorded_at' => 'datetime',
            'temperature' => 'decimal:2',
            'ph' => 'decimal:2',
            'dissolved_oxygen' => 'decimal:2',
            'salinity' => 'decimal:2',
            'turbidity' => 'decimal:2',
            'ammonia' => 'decimal:3',
            'alkalinity' => 'decimal:2',
        ];
    }

    public function pond(): BelongsTo
    {
        return $this->belongsTo(Pond::class);
    }

    public function cycle(): BelongsTo
    {
        return $this->belongsTo(Cycle::class);
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
