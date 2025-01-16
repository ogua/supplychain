<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?int $navigationSort = 2;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
        ->orderBy('id','desc');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('order_reff')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('company_id')
                    ->relationship('company', 'name')
                    ->required(),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\TextInput::make('status')
                    ->required(),
                Forms\Components\TextInput::make('order_tax_rate')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('order_tax_value')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('order_tax')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('order_discount_type')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('order_discount_value')
                    ->numeric()
                    ->default(null),
                Forms\Components\TextInput::make('total_discount')
                    ->numeric()
                    ->default(null),
                Forms\Components\TextInput::make('shipping_cost')
                    ->numeric()
                    ->default(null),
                Forms\Components\TextInput::make('total')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('grand_total')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('paid_amount')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_reff')
                    ->searchable(),
                Tables\Columns\TextColumn::make('client.customer_name')
                ->label('Client')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                ->color(function($record){
                    return match($record->status){
                        'pending' => 'warning',
                        'processing' => 'info',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                    };
                }),

                Tables\Columns\TextColumn::make('grand_total')
                    ->numeric()
                    ->state(fn($record) => "GHC: ".($record->grand_total))
                    ->sortable(),
                Tables\Columns\TextColumn::make('paid_amount')
                    ->numeric()
                    ->state(fn($record) => "GHC: ".($record->paid_amount))
                    ->sortable(),
                Tables\Columns\TextColumn::make('balance')
                    ->numeric()
                    ->state(fn($record) => "GHC: ".($record->grand_total - $record->paid_amount))
                    ->color('info')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([

                Tables\Actions\Action::make('printreceipt')
                ->label('Print Receipt')
                ->icon('heroicon-m-inbox-stack')
                ->color('success')
                ->url( fn ($record) => route('order-invoice', $record->id), shouldOpenInNewTab: true),

                Tables\Actions\Action::make('packingslip')
                ->label('Packing Slip')
                ->icon('heroicon-m-inbox-stack')
                ->color('info')
                ->url( fn ($record) => route('packing-slip', $record->id), shouldOpenInNewTab: true),

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
