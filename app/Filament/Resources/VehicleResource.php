<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VehicleResource\Pages;
use App\Filament\Resources\VehicleResource\RelationManagers;
use App\Models\Vehicle;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VehicleResource extends Resource
{
    protected static ?string $model = Vehicle::class;
    
    protected static ?string $navigationIcon = 'heroicon-o-truck';
    //protected static ?string $navigationGroup = 'Settings';
    protected static ?int $navigationSort = 7;
    
    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Section::make('')
            ->description('')
            ->schema([
                Forms\Components\FileUpload::make('photo')
                ->image()
                ->columnSpanFull(),
                
                Forms\Components\Select::make('company_id')
                ->relationship('company', 'name')
                ->preload()
                ->searchable()
                ->required(),
                
                Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),
                Forms\Components\TextInput::make('model')
                ->required()
                ->maxLength(255),
                Forms\Components\TextInput::make('license_plate')
                ->required()
                ->maxLength(255),
                Forms\Components\TextInput::make('capacity')
                ->required()
                ->numeric(),
                ])
                ->columns(2),
            ]);
        }
        
        public static function table(Table $table): Table
        {
            return $table
            ->columns([
                Tables\Columns\TextColumn::make('company.name')
                ->searchable(),
                Tables\Columns\TextColumn::make('photo')
                ->searchable(),
                Tables\Columns\TextColumn::make('name')
                ->searchable(),
                Tables\Columns\TextColumn::make('model')
                ->searchable(),
                Tables\Columns\TextColumn::make('license_plate')
                ->searchable(),
                Tables\Columns\TextColumn::make('capacity')
                ->numeric()
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
                            'index' => Pages\ListVehicles::route('/'),
                            'create' => Pages\CreateVehicle::route('/create'),
                            'edit' => Pages\EditVehicle::route('/{record}/edit'),
                        ];
                    }
                }
                