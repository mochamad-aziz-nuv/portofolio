<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeedingLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'cycle_id',
        'feed_brand',
        'feeding_date',
        'feeding_time',
        'doc',
        'amount_kg',
        'appetite_rate',
        'recorded_by',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'feeding_date' => 'date',
            'doc' => 'integer',
            'amount_kg' => 'decimal:2',
        ];
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
