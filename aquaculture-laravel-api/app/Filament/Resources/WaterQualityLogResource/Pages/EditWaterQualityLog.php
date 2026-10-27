<?php

namespace App\Filament\Resources\WaterQualityLogResource\Pages;

use App\Filament\Resources\WaterQualityLogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWaterQualityLog extends EditRecord
{
    protected static string $resource = WaterQualityLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
