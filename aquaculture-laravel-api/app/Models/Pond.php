<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pond extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'pond_type',
        'length_m',
        'width_m',
        'depth_m',
        'area_sqm',
        'volume_m3',
        'status',
        'location',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'length_m' => 'decimal:2',
            'width_m' => 'decimal:2',
            'depth_m' => 'decimal:2',
            'area_sqm' => 'decimal:2',
            'volume_m3' => 'decimal:2',
        ];
    }

    public function cycles(): HasMany
    {
        return $this->hasMany(Cycle::class);
    }

    public function activeCycle(): HasOne
    {
        return $this->hasOne(Cycle::class)->where('status', 'active')->latestOfMany();
    }

    public function waterQualityLogs(): HasMany
    {
        return $this->hasMany(WaterQualityLog::class);
    }

    public function latestWaterQuality(): HasOne
    {
        return $this->hasOne(WaterQualityLog::class)->latestOfMany('recorded_at');
    }
}
