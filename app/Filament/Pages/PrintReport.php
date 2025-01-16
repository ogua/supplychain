<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class PrintReport extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Report';
    protected static ?string $navigationLabel = 'Generate Report';
    protected static ?string $title = 'Generate Report';

    protected static string $view = 'filament.pages.print-report';
}
