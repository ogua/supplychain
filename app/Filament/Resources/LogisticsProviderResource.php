<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LogisticsProviderResource\Pages;
use App\Filament\Resources\LogisticsProviderResource\RelationManagers;
use App\Models\LogisticsProvider;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LogisticsProviderResource extends Resource
{
    protected static ?string $model = LogisticsProvider::class;
    
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 9;
    
    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Section::make('')
            ->description('')
            ->schema([
                Forms\Components\TextInput::make('full_name')
                ->required()
                ->maxLength(255),
                Forms\Components\TextInput::make('company_name')
                ->maxLength(255)
                ->default(null),
                Forms\Components\TextInput::make('email')
                ->email()
                ->required()
                ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                ->tel()
                ->required()
                ->maxLength(255),
                Forms\Components\Textarea::make('address')
                ->columnSpanFull(),
                Forms\Components\TextInput::make('location')
                ->maxLength(255)
                ->default(null),
                ])
                ->columns(2),
            ]);
        }
        
        public static function table(Table $table): Table
        {
            return $table
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                ->searchable(),
                Tables\Columns\TextColumn::make('company_name')
                ->searchable(),
                Tables\Columns\TextColumn::make('email')
                ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                ->searchable(),
                Tables\Columns\TextColumn::make('location')
                ->searchable(),
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
                            'index' => Pages\ListLogisticsProviders::route('/'),
                            'create' => Pages\CreateLogisticsProvider::route('/create'),
                            'edit' => Pages\EditLogisticsProvider::route('/{record}/edit'),
                        ];
                    }
                }
                