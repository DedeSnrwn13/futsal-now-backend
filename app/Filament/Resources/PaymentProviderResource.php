<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentProviderResource\Pages;
use App\Filament\Resources\PaymentProviderResource\RelationManagers;
use App\Models\PaymentProvider;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentProviderResource extends Resource
{
    protected static ?string $model = PaymentProvider::class;

    protected static ?string $navigationIcon = 'heroicon-o-key';

    protected static ?string $navigationLabel = 'Payment Provider';

    protected static ?string $navigationGroup = 'Manage Provider Key';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                ->schema([
                    TextInput::make('merchant_id')
                        ->unique(ignoreRecord: true)
                        ->required(),
                    TextInput::make('client_key')
                        ->unique(ignoreRecord: true)
                        ->required(),
                    TextInput::make('server_key')
                        ->unique(ignoreRecord: true)
                        ->required(),

                ])
                ->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('merchant_id')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('client_key')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('server_key')
                    ->sortable()
                    ->searchable(),
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
            'index' => Pages\ListPaymentProviders::route('/'),
            'create' => Pages\CreatePaymentProvider::route('/create'),
            'edit' => Pages\EditPaymentProvider::route('/{record}/edit'),
        ];
    }
}
