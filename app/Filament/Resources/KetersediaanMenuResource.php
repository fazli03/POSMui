<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KetersediaanMenuResource\Pages;
use App\Models\Menu;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\ToggleButtons;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Actions\Action;

class KetersediaanMenuResource extends Resource
{
    protected static ?string $model = Menu::class;

    protected static ?string $navigationIcon = 'heroicon-s-clipboard-document-check';

    protected static ?string $navigationGroup = 'Manajemen Menu';

    protected static ?string $navigationLabel = 'Ketersediaan Menu';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('gambar')->circular(),
                TextColumn::make('nama')->searchable()->label('Nama Menu'),
                TextColumn::make('is_tersedia')
                    ->label('Status')
                    ->badge()
                    ->state(fn($record) => $record->is_tersedia ? 'Tersedia' : 'Tidak Tersedia')
                    ->color(fn($state) => $state === 'Tersedia' ? 'success' : 'danger'),
            ])
            ->actions([
                Action::make('ubah_status')
                    ->label('Ubah Status')
                    ->icon('heroicon-m-adjustments-horizontal')
                    ->form(fn($record) => [
                        ToggleButtons::make('is_tersedia')
                            ->label('Status Ketersediaan')
                            ->options([
                                true => 'Tersedia',
                                false => 'Tidak Tersedia',
                            ])
                            ->colors([
                                true => 'success',
                                false => 'danger',
                            ])
                            ->default(fn($record) => $record->is_tersedia)
                            ->inline()
                            ->required()
                    ])
                    ->action(function ($record, array $data) {
                        $record->update([
                            'is_tersedia' => $data['is_tersedia'],
                        ]);
                    })
                    ->modalHeading('Konfirmasi Perubahan Status')
                    ->modalSubmitActionLabel('Simpan')
                    ->modalWidth('sm'), // Modal kecil, biar nggak ngganggu
            ]);
    }
    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKetersediaanMenus::route('/'),
            // 'edit' => Pages\EditKetersediaanMenu::route('/{record}/edit'),
        ];
    }

    // Mencegah akses create/delete
    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::check() && Auth::user()?->role === 'dapur';
    }

    public static function canViewAny(): bool
    {
        return Auth::check() && Auth::user()?->role === 'dapur';
    }

    public static function getNavigationGroup(): string
    {
        return 'Manajemen Menu';
    }
}
