<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FeedingLogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'cycle_id' => $this->cycle_id,
            'cycle_code' => $this->cycle?->cycle_code,
            'pond_name' => $this->cycle?->pond?->name,
            'feed_brand' => $this->feed_brand,
            'feeding_date' => $this->feeding_date?->format('Y-m-d'),
            'feeding_time' => $this->feeding_time,
            'doc' => (int) $this->doc,
            'amount_kg' => (float) $this->amount_kg,
            'appetite_rate' => $this->appetite_rate,
            'recorded_by' => $this->recorder ? [
                'id' => $this->recorder->id,
                'name' => $this->recorder->name,
            ] : null,
            'notes' => $this->notes,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
