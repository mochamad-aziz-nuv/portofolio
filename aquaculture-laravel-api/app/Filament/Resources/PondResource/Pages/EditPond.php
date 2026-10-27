<?php

namespace App\Filament\Resources\PondResource\Pages;

use App\Filament\Resources\PondResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPond extends EditRecord
{
    protected static string $resource = PondResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
