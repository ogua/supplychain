<?php

namespace App\Filament\Resources\LogisticsProviderResource\Pages;

use App\Filament\Resources\LogisticsProviderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLogisticsProvider extends EditRecord
{
    protected static string $resource = LogisticsProviderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
