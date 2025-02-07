<?php

namespace App\Filament\Widgets;

use App\Models\Products;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class ProductWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalProducts = Products::count();
        $totalqty = Products::sum('quantity');
        $AvengePx = Products::avg('price');

        return [
            Stat::make('Total Products', $totalProducts),
            Stat::make('Total Quantity', $totalqty),
            Stat::make('Average Price', number_format(round($AvengePx,2), 2)),
        ];
    }
}
