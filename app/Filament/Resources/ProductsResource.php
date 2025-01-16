<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductsResource\Pages;
use App\Filament\Resources\ProductsResource\RelationManagers;
use App\Models\Products;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductsResource extends Resource
{
    protected static ?string $model = Products::class;
    
    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $modelLabel = 'Product';
    protected static ?string $pluralModelLabel = 'Products';
    protected static ?int $navigationSort = 1;
    
    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Section::make('')
            ->description('')
            ->schema([
                
                Forms\Components\FileUpload::make('photo')
                ->label('Image')
                ->image()
                ->columnSpanFull()
                ->required(),

                Forms\Components\Select::make('company_id')
                ->label('Company')
                ->relationship('company', 'name')
                ->searchable()
                ->preload()
                ->required(),

                Forms\Components\TextInput::make('name')
                ->label('Product name')
                ->required()
                ->maxLength(255),

                Forms\Components\TextInput::make('sku')
                ->label('SKU')
                ->required()
                ->maxLength(255),
                Forms\Components\Textarea::make('description')
                ->columnSpanFull(),
                Forms\Components\TextInput::make('price')
                ->label('Cost')
                ->required()
                ->numeric(),
                Forms\Components\TextInput::make('quantity')
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
                Tables\Columns\ImageColumn::make('photo')
                ->label('Image'),
                Tables\Columns\TextColumn::make('company.name')
                ->label('Company')
                ->searchable(),
                Tables\Columns\TextColumn::make('name')
                ->searchable(),
                Tables\Columns\TextColumn::make('sku')
                ->label('SKU')
                ->searchable(),
                Tables\Columns\TextColumn::make('quantity')
                ->numeric()
                ->sortable()
                ->badge(),
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
                            'index' => Pages\ListProducts::route('/'),
                            'create' => Pages\CreateProducts::route('/create'),
                            'edit' => Pages\EditProducts::route('/{record}/edit'),
                        ];
                    }
                }
                