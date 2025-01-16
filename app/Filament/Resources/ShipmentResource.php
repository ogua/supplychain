<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShipmentResource\Pages;
use App\Filament\Resources\ShipmentResource\RelationManagers;
use App\Models\Order;
use App\Models\Shipment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ShipmentResource extends Resource
{
    protected static ?string $model = Shipment::class;
    
    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?int $navigationSort = 3;
    
    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Section::make('')
            ->description('')
            ->schema([
                Forms\Components\Select::make('order_id')
                ->label('Order Reff')
                ->relationship('order')
                ->options(Order::latest()->pluck('order_reff','id'))
                ->preload()
                ->searchable()
                ->required(),

                Forms\Components\TextInput::make('tracking_number')
                ->required()
                ->maxLength(255),

                Forms\Components\Select::make('status')
                ->options(['pending' => 'Pending', 'in_transit' => 'In Transit', 'delivered' => 'Delivered', 'delayed' => 'Delayed', 'canceled' => 'Cancelled'])
                ->searchable()
                ->required(),

                Forms\Components\Textarea::make('shipping_address')
                ->required()
                ->columnSpanFull(),

                Forms\Components\DateTimePicker::make('shipped_at'),
                Forms\Components\DateTimePicker::make('delivered_at'),
                Forms\Components\DateTimePicker::make('expected_delivery'),
                ])
                ->columns(2),
            ]);
        }
        
        public static function table(Table $table): Table
        {
            return $table
            ->columns([
                Tables\Columns\TextColumn::make('order.order_reff')
                ->numeric()
                ->sortable(),
                Tables\Columns\TextColumn::make('tracking_number')
                ->searchable(),
                Tables\Columns\TextColumn::make('status')
                ->color(function($record){
                    return match($record->status){
                        'pending' => 'warning',
                        'in_transit' => 'info',
                        'delivered' => 'success',
                        'delayed' => 'warning',
                        'canceled' => 'danger',
                    };
                }),
                Tables\Columns\TextColumn::make('shipped_at')
                ->dateTime()
                ->sortable(),
                Tables\Columns\TextColumn::make('delivered_at')
                ->dateTime()
                ->sortable(),
                Tables\Columns\TextColumn::make('expected_delivery')
                ->dateTime()
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
                            'index' => Pages\ListShipments::route('/'),
                            'create' => Pages\CreateShipment::route('/create'),
                            'edit' => Pages\EditShipment::route('/{record}/edit'),
                        ];
                    }
                }
                