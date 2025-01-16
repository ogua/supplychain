<?php

namespace App\Filament\Resources\VehicleTrackingResource\Pages;

use App\Filament\Resources\VehicleTrackingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVehicleTracking extends EditRecord
{
    protected static string $resource = VehicleTrackingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
