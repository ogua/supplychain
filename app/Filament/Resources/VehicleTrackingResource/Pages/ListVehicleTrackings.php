<?php

namespace App\Filament\Resources\VehicleTrackingResource\Pages;

use App\Filament\Resources\VehicleTrackingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVehicleTrackings extends ListRecords
{
    protected static string $resource = VehicleTrackingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
