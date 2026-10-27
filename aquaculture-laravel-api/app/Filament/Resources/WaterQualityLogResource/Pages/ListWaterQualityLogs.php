<?php

namespace App\Filament\Resources\WaterQualityLogResource\Pages;

use App\Filament\Resources\WaterQualityLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWaterQualityLogs extends ListRecords
{
    protected static string $resource = WaterQualityLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
