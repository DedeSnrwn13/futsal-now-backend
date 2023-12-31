<?php

namespace App\Filament\Resources\SportArenaResource\RelationManagers;

use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class GroundsRelationManager extends RelationManager
{
    protected static string $relationship = 'grounds';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                ->schema([
                    TextInput::make('sport_arena_id')
                        ->default(function ($livewire) {
                            return $livewire->ownerRecord['id'];
                        })
                        ->hidden()
                        ->required(),
                    TextInput::make('name')
                        ->placeholder('Field name')
                        ->helperText('eg: 1')
                        ->minLength(1)
                        ->maxLength(255)
                        ->required(),
                    TextInput::make('rental_price')
                        ->placeholder('eg: 70000')
                        ->prefix('Rp. ')
                        ->helperText('Per Hour')
                        ->numeric()
                        ->required(),
                    TextInput::make('capacity')
                        ->helperText('People can watch on this field')
                        ->numeric()
                        ->required(),
                    Toggle::make('is_available')
                        ->inline(false)
                        ->default(true)
                        ->required(),
                    Textarea::make('description')
                        ->autosize()
                        ->required(),
                    FileUpload::make('image')
                        ->required()
                        ->image()
                        ->multiple()
                        ->reorderable()
                        ->maxSize(2048)
                        ->minFiles(1)
                        ->previewable()
                        ->getUploadedFileNameForStorageUsing(
                            fn (TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                                ->prepend('grund-'. Str::random(24) .'-'),
                        )
                        // ->loadingIndicatorPosition('left')
                        // ->panelAspectRatio('2:1')
                        // ->panelLayout('integrated')
                        // ->removeUploadedFileButtonPosition('right')
                        // ->uploadButtonPosition('left')
                        // ->uploadProgressIndicatorPosition('left'),
                ])
                ->columns(2)
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('rental_price')
                    ->formatStateUsing(fn(string $state): string => number_format($state))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('capacity')
                    ->formatStateUsing(fn(string $state): string => number_format($state))
                    ->sortable()
                    ->searchable(),
                ToggleColumn::make('is_available')
                    ->disabled(true)
                    ->sortable()
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->searchable()
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
