<?php

namespace App\Filament\Resources\SportArenaResource\Pages;

use App\Filament\Resources\SportArenaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSportArenas extends ListRecords
{
    protected static string $resource = SportArenaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
