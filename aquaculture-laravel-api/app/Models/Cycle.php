<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cycle extends Model
{
    use HasFactory;

    protected $fillable = [
        'pond_id',
        'cycle_code',
        'species',
        'start_date',
        'target_harvest_date',
        'actual_harvest_date',
        'initial_population',
        'target_fcr',
        'target_size',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'target_harvest_date' => 'date',
            'actual_harvest_date' => 'date',
            'initial_population' => 'integer',
            'target_fcr' => 'decimal:2',
            'target_size' => 'integer',
        ];
    }

    public function pond(): BelongsTo
    {
        return $this->belongsTo(Pond::class);
    }

    public function feedingLogs(): HasMany
    {
        return $this->hasMany(FeedingLog::class);
    }

    public function waterQualityLogs(): HasMany
    {
        return $this->hasMany(WaterQualityLog::class);
    }

    public function currentDoc(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->start_date) {
                    return 0;
                }
                $endDate = $this->actual_harvest_date ? Carbon::parse($this->actual_harvest_date) : Carbon::today();
                $startDate = Carbon::parse($this->start_date);
                if ($startDate->isFuture()) {
                    return 0;
                }
                return (int) $startDate->diffInDays($endDate) + 1;
            }
        );
    }

    public function totalFeedConsumedKg(): Attribute
    {
        return Attribute::make(
            get: fn () => (float) $this->feedingLogs()->sum('amount_kg')
        );
    }
}
