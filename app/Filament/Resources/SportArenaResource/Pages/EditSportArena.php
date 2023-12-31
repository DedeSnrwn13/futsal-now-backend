<?php

namespace App\Filament\Resources\SportArenaResource\Pages;

use App\Filament\Resources\SportArenaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSportArena extends EditRecord
{
    protected static string $resource = SportArenaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
