<?php

namespace App\Filament\Widgets;

use App\Models\Shipment;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class ShipmentsWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        $totalShipments = Shipment::count();
        $pending = Shipment::where('status', 'pending')->count();
        $intransit = Shipment::where('status', 'in_transit')->count();
        $delivered = Shipment::where('status', 'delivered')->count();
        $delayed = Shipment::where('status', 'delayed')->count();
        $canceled = Shipment::where('status', 'canceled')->count();


        return [
            Stat::make('Total Shipments', $totalShipments),
            Stat::make('Pending', $pending),
            Stat::make('In Transit', $intransit),
            Stat::make('Delivered', $delivered),
            Stat::make('Delayed', $delayed),
            Stat::make('Canceled', $canceled),
        ];
    }
}
