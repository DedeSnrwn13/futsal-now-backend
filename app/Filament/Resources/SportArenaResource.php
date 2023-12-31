<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\SportArena;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TimePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SportArenaResource\Pages;
use App\Filament\Resources\SportArenaResource\RelationManagers;
use App\Filament\Resources\SportArenaResource\RelationManagers\GroundsRelationManager;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class SportArenaResource extends Resource
{
    protected static ?string $model = SportArena::class;

    protected static ?string $navigationIcon = 'heroicon-o-home-modern';


    protected static ?string $navigationLabel = 'Sport Arenas';

    protected static ?string $navigationGroup = 'Master Sport Arena';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                ->schema([
                    Select::make('user_id')
                        ->options(User::find(auth()->id())->pluck('name', 'id'))
                        ->default(auth()->id())
                        ->disabled()
                        ->required(),
                    TextInput::make('name')
                        ->placeholder('Input sport arena name')
                        ->minLength(3)
                        ->required(),
                    TextInput::make('city')
                        ->placeholder('eg: South Jakarta')
                        ->minLength(2)
                        ->required(),
                    TextInput::make('district')
                        ->placeholder('eg: Pancoran')
                        ->minLength(2)
                        ->required(),
                    TextInput::make('email')
                        ->placeholder('Sport arena email')
                        ->unique(
                            SportArena::class,
                            'email',
                            fn ($record) => $record,
                        )
                        ->nullable()
                        ->email(),
                    TextInput::make('wa_number')
                        ->placeholder('eg: 628xxxxxxx')
                        ->tel()
                        ->required(),
                    TimePicker::make('open_time')
                        ->required(),
                    TimePicker::make('close_time')
                        ->required(),
                    Textarea::make('map_link')
                        ->placeholder('Google map link')
                        ->autosize()
                        ->nullable(),
                    Textarea::make('description')
                        ->placeholder('Input sport arena description include facility, etc')
                        ->minLength(15)
                        ->autosize()
                        ->required(),
                    FileUpload::make('logo')
                        ->image()
                        ->getUploadedFileNameForStorageUsing(
                            fn (TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                                ->prepend('sport-arena-'. Str::random(24) .'-'),
                        )
                        ->imagePreviewHeight('250')
                        ->loadingIndicatorPosition('left')
                        ->panelAspectRatio('2:1')
                        ->panelLayout('integrated')
                        ->removeUploadedFileButtonPosition('right')
                        ->uploadButtonPosition('left')
                        ->uploadProgressIndicatorPosition('left'),

                ])
                ->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo')
                    ->default('-'),
                TextColumn::make('user.name')
                    ->label('Owner'),
                TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('city')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('district')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('wa_number')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('email')
                    ->default('-')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('open_time')
                    ->time()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('close_time')
                    ->time()
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
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

    public static function getRelations(): array
    {
        return [
            GroundsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSportArenas::route('/'),
            'create' => Pages\CreateSportArena::route('/create'),
            'edit' => Pages\EditSportArena::route('/{record}/edit'),
        ];
    }
}
