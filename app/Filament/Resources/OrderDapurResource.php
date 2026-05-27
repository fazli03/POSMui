<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderDapurResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Auth;

class OrderDapurResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-s-clipboard-document-list';


    protected static ?string $pluralModelLabel = 'Daftar Pesanan';

    // Filter hanya pesanan yang sudah dikonfirmasi kasir (status diproses)
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('status', 'diproses')
            ->with(['orderDetails.menu']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Detail Pesanan')
                    ->schema([
                        Forms\Components\Placeholder::make('nama')
                            ->label('Nama Pelanggan')
                            ->content(fn($get) => $get('nama')),

                        Forms\Components\Placeholder::make('tipe_order')
                            ->label('Tipe Order')
                            ->content(fn($get) => match ($get('tipe_order')) {
                                'dine_in' => 'Dine In',
                                'takeaway' => 'Takeaway',
                                default => '-',
                            }),
                        Forms\Components\Placeholder::make('no_meja')
                            ->label('No Meja')
                            ->content(function ($record) {
                                if ($record->tipe_order === 'takeaway') {
                                    return 'TAKEAWAY';
                                }

                                return $record->no_meja ? "Meja {$record->no_meja}" : 'Belum Ditentukan';
                            })

                    ]),


                Forms\Components\Section::make('Menu yang Dipesan')
                    ->schema([
                        Forms\Components\Repeater::make('orderDetails')
                            ->label('Menu yang Dipesan')
                            ->relationship('orderDetails')
                            ->schema([
                                Forms\Components\Placeholder::make('menu_id')
                                    ->label('Nama Menu')
                                    ->content(fn($record) => $record?->menu?->nama ?? 'Menu tidak ditemukan'),

                                Forms\Components\Placeholder::make('quantity')
                                    ->label('Jumlah')
                                    ->content(fn($state) => $state . ' porsi'),

                                Forms\Components\Placeholder::make('catatan')
                                    ->label('Catatan')
                                    ->content(fn($state) => $state ?: 'Tidak ada catatan'),
                            ])
                            ->columns(3)
                            ->addable(false)
                            ->deletable(false)
                            ->reorderable(false)
                            ->disabled(),
                    ])
                    ->columns(1)
                    ->collapsible(),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_pesanan')
                    ->label('Kode Pesanan')
                    ->weight(FontWeight::Bold)
                    ->searchable()
                    ->sortable(),

                TextColumn::make('tipe_order')
                    ->label('Tipe Order')
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'dine_in' => 'Dine In',
                        'takeaway' => 'Takeaway',
                    })
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'dine_in' => 'primary',
                        'takeaway' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('no_meja')
                    ->label('No Meja')
                    ->formatStateUsing(function ($state, $record) {
                        if ($record->tipe_order === 'takeaway') {
                            return 'TAKEAWAY';
                        }
                        return $state ? "Meja {$state}" : 'Belum Ditentukan';
                    })
                    ->badge()
                    ->color(function ($state, $record) {
                        if ($record->tipe_order === 'takeaway') {
                            return 'warning';
                        }
                        return $state ? 'success' : 'gray';
                    })
                    // Tambahkan ini untuk memastikan badge selalu tampil
                    ->default(function ($record) {
                        return $record->tipe_order === 'takeaway' ? 'TAKEAWAY' : null;
                    }),

                TextColumn::make('created_at')
                    ->label('Waktu Pesanan')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'diproses' => 'Pesanan Baru',
                    })
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'diproses' => 'warning',
                    }),

                TextColumn::make('orderDetails_count')
                    ->label('Jumlah Item')
                    ->state(fn($record) => $record->orderDetails->count()) // pastikan scalar
                    ->badge()
                    ->color('info')
                    ->suffix(' item'),


            ])
            ->filters([
                //
            ])
            ->actions([

                // Action::make('selesai')
                //     ->label('Selesai')
                //     ->icon('heroicon-o-check-circle')
                //     ->color('success')
                //     ->requiresConfirmation()
                //     ->modalHeading('Konfirmasi Pesanan Selesai')
                //     ->modalDescription('Apakah pesanan ini sudah selesai?')
                //     ->action(function (Order $record) {
                //         $record->update(['status' => 'selesai']);

                //         Notification::make()
                //             ->title('Pesanan Selesai')
                //             ->body("Pesanan {$record->kode_pesanan} telah diselesaikan.")
                //             ->success()
                //             ->send();
                //     })
                //     ->visible(fn(Order $record) => $record->status === 'diproses'),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Action::make('selesai_bulk')
                //         ->label('Tandai Selesai')
                //         ->icon('heroicon-o-check-circle')
                //         ->color('success')
                //         ->requiresConfirmation()
                //         ->modalHeading('Konfirmasi Pesanan Selesai')
                //         ->modalDescription('Apakah Anda yakin semua pesanan yang dipilih sudah selesai?')
                //         ->action(function ($records) {
                //             $count = 0;
                //             foreach ($records as $record) {
                //                 if ($record->status === 'diproses') {
                //                     $record->update(['status' => 'selesai']);
                //                     $count++;
                //                 }
                //             }

                //             Notification::make()
                //                 ->title('Pesanan Selesai')
                //                 ->body("{$count} pesanan telah diselesaikan.")
                //                 ->success()
                //                 ->send();
                //         }),
                // ]),
            ])
            ->defaultSort('created_at', 'asc')
            ->poll('4s'); // Auto refresh setiap 4 detik
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
            'index' => Pages\ListOrderDapurs::route('/'),
            'edit' => Pages\EditOrderDapur::route('/{record}/edit'),
        ];
    }

    // Method untuk mendapatkan statistik pesanan
    // public static function getStats(): array
    // {
    //     $totalPesananBaru = Order::where('status', 'diproses')->count();
    //     $totalPesananSelesai = Order::where('status', 'selesai')
    //         ->whereDate('created_at', today())
    //         ->count();

    //     return [
    //         'pesanan_baru' => $totalPesananBaru,
    //         'pesanan_selesai' => $totalPesananSelesai,
    //     
    // }

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
        return 'Order';
    }
}
