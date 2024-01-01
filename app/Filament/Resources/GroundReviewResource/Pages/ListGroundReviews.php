<?php

namespace App\Filament\Resources\GroundReviewResource\Pages;

use App\Filament\Resources\GroundReviewResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGroundReviews extends ListRecords
{
    protected static string $resource = GroundReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
