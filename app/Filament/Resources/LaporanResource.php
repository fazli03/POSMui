<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LaporanResource\Pages;
use App\Models\Order;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\Filter;

class LaporanResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-s-currency-dollar';
    protected static ?string $navigationLabel = 'Laporan Penjualan';
    protected static ?string $navigationGroup = 'Laporan';

    public static function form(Form $form): Form
    {
        return $form;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(Builder $query) =>
            $query->whereIn('status', ['selesai', 'dibatalkan']))
            ->filters([
                Filter::make('metode_bayar')
                    ->form([
                        Select::make('metode_bayar')
                            ->label('Metode Bayar')
                            ->options([
                                'tunai' => 'Tunai',
                                'non_tunai' => 'Non Tunai',
                            ])
                            ->placeholder('Semua'),
                    ])
                    ->query(fn($query, $data) =>
                    filled($data['metode_bayar']) ? $query->where('metode_bayar', $data['metode_bayar']) : $query),

                Filter::make('status')
                    ->form([
                        Select::make('status')
                            ->label('Status Pesanan')
                            ->options([
                                'selesai' => 'Selesai',
                                'dibatalkan' => 'Dibatalkan',
                            ])
                            ->placeholder('Semua'),
                    ])
                    ->query(fn($query, $data) =>
                    filled($data['status']) ? $query->where('status', $data['status']) : $query),

                Filter::make('tanggal_awal')
                    ->form([
                        DatePicker::make('tanggal_awal')->label('Tanggal Awal'),
                    ])
                    ->query(fn($query, $data) =>
                    filled($data['tanggal_awal']) ? $query->whereDate('created_at', '>=', $data['tanggal_awal']) : $query),

                Filter::make('tanggal_akhir')
                    ->form([
                        DatePicker::make('tanggal_akhir')->label('Tanggal Akhir'),
                    ])
                    ->query(fn($query, $data) =>
                    filled($data['tanggal_akhir']) ? $query->whereDate('created_at', '<=', $data['tanggal_akhir']) : $query),
            ], layout: FiltersLayout::AboveContent)
            ->columns([
                Tables\Columns\TextColumn::make('kode_pesanan')->label('Kode Pesanan')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('nama')->label('Nama Customer')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('metode_bayar')
                    ->label('Metode Bayar')
                    ->sortable()
                    ->badge()
                    ->color(fn(string $state) => match ($state) {
                        'tunai' => 'success',
                        'non_tunai' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state) => match ($state) {
                        'tunai' => 'Tunai',
                        'non_tunai' => 'Non Tunai',
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'success' => 'selesai',
                        'danger' => 'dibatalkan',
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('tipe_order')
                    ->label('Tipe Order')
                    ->badge()
                    ->color(fn(string $state) => match ($state) {
                        'dine_in' => 'success',
                        'takeaway' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state) => match ($state) {
                        'dine_in' => 'DINE IN',
                        'takeaway' => 'TAKEAWAY',
                    }),
                Tables\Columns\TextColumn::make('total')
                    ->label('Total Bayar')
                    ->formatStateUsing(fn($state) => 'Rp ' . number_format((int) $state, 0, ',', '.'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('jumlah_uang_diberikan')
                    ->label('Uang Diberikan')
                    ->formatStateUsing(function ($state, $record) {
                        $metodeBayar = $record->metode_bayar;
                        $total = $record->total;

                        return 'Rp ' . number_format(
                            $metodeBayar === 'non_tunai' ? (int) $total : (int) preg_replace('/\D/', '', $state),
                            0,
                            ',',
                            '.'
                        );
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('kembalian')
                    ->label('Kembalian')
                    ->formatStateUsing(fn($state) => 'Rp ' . number_format((int) preg_replace('/\D/', '', $state), 0, ',', '.'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->contentFooter(function ($livewire) {
                $filteredQuery = $livewire->getFilteredTableQuery();
                $total = (clone $filteredQuery)
                    ->where('status', 'selesai')
                    ->sum('total');

                return view('filament.tables.footer-total', [
                    'total' => $total,
                ]);
            })
            ->actions([])
            ->bulkActions([])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLaporans::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::check() && Auth::user()?->role === 'owner';
    }

    public static function canViewAny(): bool
    {
        return Auth::check() && Auth::user()?->role === 'owner';
    }

    public static function getNavigationGroup(): string
    {
        return 'Laporan';
    }
}
