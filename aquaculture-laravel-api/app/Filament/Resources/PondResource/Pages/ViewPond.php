<?php

namespace App\Filament\Resources\PondResource\Pages;

use App\Filament\Resources\PondResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPond extends ViewRecord
{
    protected static string $resource = PondResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
