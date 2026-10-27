<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WaterQualityLogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'pond_id' => $this->pond_id,
            'pond_name' => $this->pond?->name,
            'cycle_id' => $this->cycle_id,
            'cycle_code' => $this->cycle?->cycle_code,
            'recorded_at' => $this->recorded_at?->toIso8601String(),
            'parameters' => [
                'temperature_celsius' => (float) $this->temperature,
                'ph' => (float) $this->ph,
                'dissolved_oxygen_mgl' => (float) $this->dissolved_oxygen,
                'salinity_ppt' => (float) $this->salinity,
                'turbidity_cm' => $this->turbidity ? (float) $this->turbidity : null,
                'ammonia_ppm' => $this->ammonia ? (float) $this->ammonia : null,
                'alkalinity_ppm' => $this->alkalinity ? (float) $this->alkalinity : null,
            ],
            'condition_status' => $this->condition_status,
            'recorded_by' => $this->recorder ? [
                'id' => $this->recorder->id,
                'name' => $this->recorder->name,
            ] : null,
            'notes' => $this->notes,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
