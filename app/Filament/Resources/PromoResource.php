<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Promo;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\Resources\PromoResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PromoResource\RelationManagers;
use Filament\Tables\Columns\ToggleColumn;

class PromoResource extends Resource
{
    protected static ?string $model = Promo::class;

    protected static ?string $navigationIcon = 'heroicon-o-scissors';

    protected static ?string $navigationLabel = 'Promo';

    protected static ?string $navigationGroup = 'Master Promo';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                ->schema([
                    TextInput::make('name')
                        ->required(),
                    TextInput::make('unique_code')
                        ->unique(ignoreRecord: true)
                        ->required(),
                    TextInput::make('type')
                        ->placeholder('eg: fixed or percentage')
                        ->required(),
                    TextInput::make('amount')
                        ->numeric()
                        ->required(),
                    DateTimePicker::make('started_at')
                        ->required(),
                    DateTimePicker::make('ended_at')
                        ->required(),
                    Toggle::make('status')
                        ->default(false)
                        ->inline(false)
                        ->required(),
                    Textarea::make('description')
                        ->autosize()
                        ->required()
                ])
                ->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('unique_code')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('type')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('amount')
                    ->formatStateUsing(fn(string $state): string => number_format($state))
                    ->sortable()
                    ->searchable(),
                ToggleColumn::make('status')
                    ->disabled()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('started_at')
                    ->dateTime()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('ended_at')
                    ->dateTime()
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        0 => 'Inactive',
                        1 => 'Active',
                    ])
                    ->searchable(),
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
            'index' => Pages\ListPromos::route('/'),
            'create' => Pages\CreatePromo::route('/create'),
            'edit' => Pages\EditPromo::route('/{record}/edit'),
        ];
    }
}
