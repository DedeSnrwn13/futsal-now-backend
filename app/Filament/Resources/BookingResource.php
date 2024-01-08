<?php

namespace App\Filament\Resources;

use DateTime;
use Filament\Forms;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\View\Components\Modal;
use Filament\Tables;
use App\Models\Booking;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Faker\Provider\ar_EG\Text;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\Resources\BookingResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\BookingResource\RelationManagers;
use Filament\Resources\Pages\ViewRecord;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationLabel = 'Bookings';

    protected static ?string $navigationGroup = 'Master Booking';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                ->schema([
                    TextInput::make('ground_id')
                        ->afterStateHydrated(function ($record) {
                            return $record['ground']['name'];
                        })
                        ->label('Ground Name')
                        ->required(),
                    TextInput::make('user_id')
                        ->formatStateUsing(function ($record) {
                            return $record['user']['name'];
                        })
                        ->label('Bookers')
                        ->required(),
                    DateTimePicker::make('started_at')
                        ->required(),
                    DateTimePicker::make('ended_at')
                        ->required(),
                    TextInput::make('total_price')
                        ->numeric()
                        ->required(),
                    TextInput::make('order_number')
                        ->unique(
                            Booking::class,
                            'order_number',
                            fn ($record) => $record,
                        )
                        ->disabled()
                        ->required(),
                    Select::make('order_status')
                        ->options([
                            'processing' => 'Processing',
                            'finished' => 'Finished',
                            'canceled' => 'Canceled',
                        ])
                        ->required(),
                    TextInput::make('payment_method')
                        ->required(),
                    Select::make('payment_status')
                        ->options([
                            'pending' => 'Pending',
                            'success' => 'Success',
                            'failed' => 'Failed',
                        ])
                        ->required(),
                    DateTimePicker::make('paid_at')
                        ->required(),
                    TextInput::make('promo_id')
                        ->nullable()
                        ->default('-'),
                    TextInput::make('ref_id')
                            ->default('-')
                            ->nullable()
                ])
                ->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('ground.sportArena.name')
                    ->label('Sport Arena')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('ground.name')
                    ->label('Ground')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label('Bookers')
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
                TextColumn::make('started_at')
                    ->dateTime()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('total_price')
                    ->formatStateUsing(fn(string $state): string => number_format($state))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('order_status')
                    ->formatStateUsing(fn(string $state): string => strtoupper($state))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('payment_method')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('payment_status')
                    ->formatStateUsing(fn(string $state): string => strtoupper($state))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('paid_at')
                    ->dateTime()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('promo.name')
                    ->default('-')
                    ->sortable()
                    ->searchable()
            ])
            ->filters([
                SelectFilter::make('sport_arena')
                    ->relationship('ground.sportArena', 'name')
                    ->searchable(),
                SelectFilter::make('ground')
                    ->relationship('ground', 'name')
                    ->searchable(),
                SelectFilter::make('order_status')
                    ->options([
                        'processing' => 'Processing',
                        'finished' => 'Finished',
                        'canceled' => 'Canceled',
                    ])
                    ->searchable(),
                SelectFilter::make('payment_status')
                    ->options([
                        'pending' => 'Pending',
                        'success' => 'Success',
                        'failed' => 'Failed',
                    ])
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make()
                    ->requiresConfirmation()
                    ->button()
                    ->icon('heroicon-o-check-circle')
                    ->name('approve')
                    ->label('Approve')
                    ->color('success')
                    ->action(function (Booking $record): void {
                        $record->update([
                            'payment_status' => 'success',
                            'order_status' => 'finished'
                        ]);
                    })
                    ->hidden(fn ($record) => ($record->payment_status == 'success' && $record->order_status == 'finished'))
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
            'index' => Pages\ListBookings::route('/'),
        ];
    }
}
