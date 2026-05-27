<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Menu;
use Filament\Tables;
use App\Models\Kategori;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use App\Filament\Resources\MenuResource\Pages;



class MenuResource extends Resource
{
    protected static ?string $model = Menu::class;

    protected static ?string $navigationIcon = 'heroicon-s-folder';
      public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(3)
                    ->schema([
                        Forms\Components\Section::make('Informasi Menu')
                        
                            ->schema([
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('nama')
                                            ->label('Nama Menu')
                                            ->required()
                                            ->maxLength(225),
                                        Forms\Components\TextInput::make('harga')
                                            ->label('Harga')
                                            ->required()
                                            ->numeric()
                                            ->prefix('IDR'),
                                    ]),
                                Forms\Components\RichEditor::make('deskripsi')
                                    ->label('Deskripsi')
                                    ->required()
                                    ->validationMessages([
                                        'required' => ' Deskripsi tidak boleh kosong.',
                                    ])
                                    ->columnSpanFull(),
                                Forms\Components\FileUpload::make('gambar')
                                    ->image()
                                    ->getUploadedFileNameForStorageUsing(function ($file) {
                                        return 'menus/' . $file->hashName();
                                    })
                                    ->label('Gambar')
                                    ->preserveFilenames()
                                    ->required()
                                    ->validationMessages([
                                        'required' => ' Gambar tidak boleh kosong.',
                                    ]),
                            ])
                            ->columnSpan(2),
                        Forms\Components\Section::make('Status & Kategori')
                            ->schema([
                                Forms\Components\ToggleButtons::make('is_tersedia')
                                    ->label('Ketersediaan')
                                    ->options([
                                        true => 'Tersedia',
                                        false => 'Tidak Tersedia',
                                    ])
                                    ->inline()
                                    ->required()
                                    ->validationMessages([
                                        'required' => ' Status tidak boleh kosong.',
                                    ]),
                                Forms\Components\Select::make('kategoris_id')
                                    ->label('Kategori')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->validationMessages([
                                        'required' => ' Kategori tidak boleh kosong.',
                                    ])
                                    ->options(
                                        Kategori::pluck('nama', 'id')
                                    ),
                            ])
                            ->columnSpan(1),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('gambar')
                    ->circular()
                    ->url(fn($record) => asset('storage/' . $record->gambar)),
                Tables\Columns\TextColumn::make('nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kategori.nama')
                    ->label('Kategori'),
                Tables\Columns\TextColumn::make('is_tersedia')
                    ->label('Status')
                    ->badge()
                    ->getStateUsing(fn($record) => $record->is_tersedia ? 'Tersedia' : 'Tidak Tersedia')
                    ->colors([
                        'success' => 'Tersedia',
                        'danger' => 'Tidak Tersedia',
                    ]),
            ])
            ->actions([])
            ->bulkActions([]);
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
            'index' => Pages\ListMenus::route('/'),
            'create' => Pages\CreateMenu::route('/create'),
            'edit' => Pages\EditMenu::route('/{record}/edit'),
        ];
    }

    public static function getSlug(): string
    {
        return 'owner/menus';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Manajemen Menu';
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::check() && Auth::user()?->role === 'owner';
    }

    public static function canViewAny(): bool
    {
        return Auth::check() && Auth::user()?->role === 'owner';
    }
}
