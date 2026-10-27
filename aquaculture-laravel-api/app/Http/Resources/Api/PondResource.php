<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PondResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'pond_type' => $this->pond_type,
            'dimension' => [
                'length_m' => (float) $this->length_m,
                'width_m' => (float) $this->width_m,
                'depth_m' => (float) $this->depth_m,
                'area_sqm' => (float) $this->area_sqm,
                'volume_m3' => (float) $this->volume_m3,
            ],
            'status' => $this->status,
            'location' => $this->location,
            'notes' => $this->notes,
            'active_cycle' => $this->whenLoaded('activeCycle', function () {
                return $this->activeCycle ? new CycleResource($this->activeCycle) : null;
            }),
            'latest_water_quality' => $this->whenLoaded('latestWaterQuality', function () {
                return $this->latestWaterQuality ? new WaterQualityLogResource($this->latestWaterQuality) : null;
            }),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
