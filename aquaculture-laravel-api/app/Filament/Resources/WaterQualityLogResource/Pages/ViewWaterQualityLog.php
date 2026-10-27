<?php

namespace App\Filament\Resources\WaterQualityLogResource\Pages;

use App\Filament\Resources\WaterQualityLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewWaterQualityLog extends ViewRecord
{
    protected static string $resource = WaterQualityLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
