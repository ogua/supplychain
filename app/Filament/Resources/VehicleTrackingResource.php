<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VehicleTrackingResource\Pages;
use App\Filament\Resources\VehicleTrackingResource\RelationManagers;
use App\Models\VehicleTracking;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use ArberMustafa\FilamentLocationPickrField\Forms\Components\LocationPickr;
use Tapp\FilamentGoogleAutocomplete\Forms\Components\GoogleAutocomplete;

class VehicleTrackingResource extends Resource
{
    protected static ?string $model = VehicleTracking::class;
    
    protected static ?string $navigationIcon = 'heroicon-o-shield-exclamation';
    protected static ?string $modelLabel = 'Vehicle Tracking';
    protected static ?string $pluralModelLabel = 'Trackings';
    protected static ?int $navigationSort = 4;
    
    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Section::make('')
            ->description('')
            ->schema([
                Forms\Components\Select::make('vehicle_id')
                ->relationship('vehicle', 'name')
                ->preload()
                ->searchable()
                ->required(),
                
                Forms\Components\Select::make('shipment_id')
                ->relationship('shipment', 'id')
                ->preload()
                ->searchable()
                ->required(),

               // LocationPickr::make('location'),
                GoogleAutocomplete::make('google_search')
                ->columnSpanFull(),
                
                Forms\Components\TextInput::make('latitude')
                ->numeric()
                ->default(null),
                
                Forms\Components\TextInput::make('longitude')
                ->numeric()
                ->default(null),
                
                Forms\Components\TextInput::make('speed')
                ->numeric()
                ->default(null),
                
                Forms\Components\TextInput::make('status')
                ->required(),
                Forms\Components\DateTimePicker::make('recorded_at')
                ->required(),
                ])
                ->columns(2),
            ]);
        }
        
        public static function table(Table $table): Table
        {
            return $table
            ->columns([
                Tables\Columns\TextColumn::make('vehicle.name')
                ->numeric()
                ->sortable(),
                Tables\Columns\TextColumn::make('shipment.id')
                ->numeric()
                ->sortable(),
                Tables\Columns\TextColumn::make('latitude')
                ->numeric()
                ->sortable(),
                Tables\Columns\TextColumn::make('longitude')
                ->numeric()
                ->sortable(),
                Tables\Columns\TextColumn::make('speed')
                ->numeric()
                ->sortable(),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('recorded_at')
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
                            'index' => Pages\ListVehicleTrackings::route('/'),
                            'create' => Pages\CreateVehicleTracking::route('/create'),
                            'edit' => Pages\EditVehicleTracking::route('/{record}/edit'),
                        ];
                    }
                }
                