<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CycleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'pond_id' => $this->pond_id,
            'pond' => $this->whenLoaded('pond', function () {
                return [
                    'id' => $this->pond->id,
                    'name' => $this->pond->name,
                    'code' => $this->pond->code,
                ];
            }),
            'cycle_code' => $this->cycle_code,
            'species' => $this->species,
            'start_date' => $this->start_date?->format('Y-m-d'),
            'target_harvest_date' => $this->target_harvest_date?->format('Y-m-d'),
            'actual_harvest_date' => $this->actual_harvest_date?->format('Y-m-d'),
            'current_doc' => $this->current_doc,
            'initial_population' => (int) $this->initial_population,
            'target_fcr' => (float) $this->target_fcr,
            'target_size' => (int) $this->target_size,
            'status' => $this->status,
            'total_feed_consumed_kg' => (float) $this->total_feed_consumed_kg,
            'notes' => $this->notes,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
