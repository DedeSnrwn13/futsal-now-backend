<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\GroundReview;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\GroundReviewResource\Pages;
use App\Filament\Resources\GroundReviewResource\RelationManagers;

class GroundReviewResource extends Resource
{
    protected static ?string $model = GroundReview::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';

    protected static ?string $navigationLabel = 'Ground Reviews';

    protected static ?string $navigationGroup = 'Master Sport Arena';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                ->schema([
                    TextInput::make('ground_id')
                        ->label('Ground')
                        ->numeric()
                        ->required(),
                    TextInput::make('user_id')
                        ->label('User')
                        ->numeric()
                        ->required(),
                    TextInput::make('rate')
                        ->numeric()
                        ->required(),
                    Textarea::make('comment')
                        ->autosize()
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
                    ->label('Sport Arena Name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('ground.name')
                    ->label('Ground Name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label('Reviewer')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('rate')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('sport_arena')->relationship('ground.sportArena', 'name'),
                SelectFilter::make('ground')->relationship('ground', 'name'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListGroundReviews::route('/')
        ];
    }
}
