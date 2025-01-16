<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RetailerResource\Pages;
use App\Filament\Resources\RetailerResource\RelationManagers;
use App\Models\Retailer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RetailerResource extends Resource
{
    protected static ?string $model = Retailer::class;
    
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?int $navigationSort = 6;
    
    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Section::make('')
            ->description('')
            ->schema([
                Forms\Components\FileUpload::make('passport_size_photo')
                ->image()
                ->columnSpanFull(),
                Forms\Components\TextInput::make('customer_name')
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
                Tables\Columns\TextColumn::make('passport_size_photo')
                ->label('Photo'),
                Tables\Columns\TextColumn::make('customer_name')
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
                            'index' => Pages\ListRetailers::route('/'),
                            'create' => Pages\CreateRetailer::route('/create'),
                            'edit' => Pages\EditRetailer::route('/{record}/edit'),
                        ];
                    }
                }
                