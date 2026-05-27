<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms;
use App\Models\Menu;
use Filament\Tables;
use App\Models\Order;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use App\Filament\Resources\OrderResource\Pages;



class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-s-clipboard-document-list';
    protected static ?string $pluralModelLabel = 'Daftar Pesanan';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Wizard::make([
                    self::getProductAndPriceStep(),
                    self::getOrderInformationStep(),
                    self::getPaymentInformationStep()
                ])
                    ->columnSpan('full')
                    ->columns(1)
                    ->skippable()
            ]);
    }

    private static function getProductAndPriceStep(): Forms\Components\Wizard\Step
    {
        return Forms\Components\Wizard\Step::make('Menu Yang Di Pesan')
            ->completedIcon('heroicon-m-hand-thumb-up')
            ->schema([
                Grid::make(2)->schema([
                    self::getOrderDetailsRepeater()
                ]),
                self::getTotalDisplayGrid()
            ]);
    }

    private static function getOrderDetailsRepeater(): Forms\Components\Repeater
    {
        return Forms\Components\Repeater::make('OrderDetails')
            ->relationship('orderDetails')
            ->schema([
                self::getMenuSelect(),
                self::getPriceInput(),
                self::getQuantityInput(),
                self::getNotesTextarea()
            ])
            ->minItems(1)
            ->columnSpanFull()
            ->label('Pilih Menu')
            ->reactive()
            ->live()
            ->afterStateUpdated(function (Set $set, Get $get) {
                self::updateTotals($set, $get);
            })
            ->disabled(self::readonlyCondition());
    }

    private static function getMenuSelect(): Forms\Components\Select
    {
        return Forms\Components\Select::make('menu_id')
            ->label('Pilih Menu')
            ->options(function () {
                return Menu::all()->mapWithKeys(function ($menu) {
                    $label = $menu->nama;
                    if (!$menu->is_tersedia) {
                        $label .= ' (Tidak Tersedia)';
                    }
                    return [$menu->id => $label];
                });
            })
            ->searchable()
            ->preload()
            ->disableOptionWhen(function ($value) {
                $menu = Menu::find($value);
                return $menu && !$menu->is_tersedia;
            })
            ->required()
            ->reactive()
            ->afterStateUpdated(function (Set $set, $state) {
                self::setMenuPrice($set, $state);
            });
    }

    private static function getPriceInput(): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make('harga')
            ->numeric()
            ->readOnly()
            ->label('Harga')
            ->hint('Harga Per Porsi');
    }

    private static function getQuantityInput(): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make('quantity')
            ->label('Quantity')
            ->numeric()
            ->minValue(1)
            ->required()
            ->reactive()
            ->hint('Input Jumlah Pesanan');
    }

    private static function getNotesTextarea(): Forms\Components\TextArea
    {
        return Forms\Components\TextArea::make('catatan')
            ->label("Catatan")
            ->nullable()
            ->hint('Opsional');
    }

    private static function getTotalDisplayGrid(): Grid
    {
        return Grid::make(2)->schema([
            Forms\Components\TextInput::make('quantity')
                ->label('Total Quantity')
                ->readOnly()
                ->default(0)
                ->reactive(),

            Forms\Components\TextInput::make('total')
                ->label('Total Bayar')
                ->readOnly()
                ->default(0)
                ->reactive()
                ->live()
        ]);
    }

    private static function getOrderInformationStep(): Forms\Components\Wizard\Step
    {
        return Forms\Components\Wizard\Step::make('Informasi Pemesanan')
            ->completedIcon('heroicon-m-hand-thumb-up')
            ->schema([
                Grid::make(2)->schema([
                    self::getCustomerNameInput(),
                    self::getOrderTypeRadio(),
                    self::getTableNumberInput(),
                    self::getStatusHidden(),
                    self::getActionTakenHidden()
                ])
            ]);
    }

    private static function getCustomerNameInput(): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make('nama')
            ->label('Nama Pelanggan')
            ->required()
            ->maxLength(225)
            ->disabled(self::readonlyCondition());
    }

    private static function getOrderTypeRadio(): Forms\Components\Radio
    {
        return Forms\Components\Radio::make('tipe_order')
            ->label('Tipe Order')
            ->required()
            ->options([
                'dine_in' => 'Dine In',
                'takeaway' => 'TakeAway',
            ])
            ->live()
            ->columns(2)
            ->disabled(self::readonlyCondition());
    }

    private static function getTableNumberInput(): ViewField
    {
        return ViewField::make('no_meja')
            ->label('Pilih Nomor Meja')
            ->visible(fn($get) => $get('tipe_order') === 'dine_in')
            ->required(fn($get) => $get('tipe_order') === 'dine_in')
            ->view('components.forms.nomor-meja');
    }

    private static function getStatusHidden(): Forms\Components\Hidden
    {
        return Forms\Components\Hidden::make('status')
            ->default('pending');
    }

    private static function getActionTakenHidden(): Forms\Components\Hidden
    {
        return Forms\Components\Hidden::make('action_taken')
            ->default(false);
    }

    private static function getPaymentInformationStep(): Forms\Components\Wizard\Step
    {
        return Forms\Components\Wizard\Step::make('Informasi Pembayaran')
            ->completedIcon('heroicon-m-hand-thumb-up')
            ->schema([
                self::getPaymentBasicInfoGrid(),
                self::getPaymentDetailsSection(),
                self::getPaymentActionsSection()
            ]);
    }

    private static function getPaymentBasicInfoGrid(): Grid
    {
        return Grid::make(2)->schema([
            Forms\Components\TextInput::make('kode_pesanan')
                ->label('Kode Pesanan')
                ->readOnly()
                ->default(fn() => Order::generateUniqueKodePesanan())
                ->maxLength(225),

            Forms\Components\Radio::make('metode_bayar')
                ->required()
                ->options([
                    'tunai' => 'Tunai',
                    'non_tunai' => 'Non Tunai',
                ])
                ->columns(1)
                ->reactive()
                ->disabled(self::readonlyCondition())
        ]);
    }

    private static function getPaymentDetailsSection(): Forms\Components\Section
    {
        return Forms\Components\Section::make('Detail Pembayaran')
            ->schema([
                Grid::make(3)->schema([
                    Forms\Components\TextInput::make('total_display')
                        ->label('Total Yang Harus Dibayar')
                        ->readOnly()
                        ->prefix('Rp')
                        ->afterStateHydrated(function (Forms\Components\TextInput $component, ?Order $record) {
                            $component->state(number_format($record?->total ?? 0, 0, ',', '.'));
                        }),


                    TextInput::make('jumlah_uang_diberikan')
                        ->label('Jumlah Uang Diberikan')
                        ->prefix('Rp')
                        ->type('text') // Jangan pakai ->numeric()
                        ->required()
                        ->reactive()
                        ->afterStateHydrated(function ($component, $state) {
                            $component->state(number_format((int) $state, 0, ',', '.'));
                        })
                        ->afterStateUpdated(function (Set $set, Get $get, $state) {
                            $cleanValue = (int) str_replace('.', '', $state);

                            // Format ulang input
                            $set('jumlah_uang_diberikan', number_format($cleanValue, 0, ',', '.'));

                            // Hitung ulang kembalian
                            $total = (int) $get('total');
                            $kembalian = max($cleanValue - $total, 0);
                            $set('kembalian', number_format($kembalian, 0, ',', '.'));
                        }),

                    TextInput::make('kembalian')
                        ->label('Kembalian')
                        ->prefix('Rp')
                        ->type('text')
                        ->readOnly()
                        ->afterStateHydrated(function ($component, $state) {
                            $component->state(number_format((int) $state, 0, ',', '.'));
                        }),

                ])
            ])
            ->visible(fn(Get $get) => $get('metode_bayar') === 'tunai');
    }

    private static function getPaymentActionsSection(): Forms\Components\Section
    {
        return Forms\Components\Section::make('Aksi Pembayaran')
            ->schema([
                Forms\Components\Actions::make([
                    self::getConfirmAction(),
                    // self::getPrintReceiptAction(),
                    self::getCancelAction(),
                ])
            ]);
    }

    private static function getConfirmAction(): Forms\Components\Actions\Action
    {
        return Forms\Components\Actions\Action::make('konfirmasi')
            ->label('Konfirmasi Pesanan')
            ->color('success')
            ->icon('heroicon-o-check-circle')

            // Tombol hanya terlihat saat status pending
            ->visible(fn(Get $get) => $get('status') === 'pending')

            // Tombol nonaktif jika:
            // - Sudah ada aksi diambil
            // - Status bukan pending
            // - Atau uang yang diberikan kurang dari total (untuk metode tunai)
            ->disabled(function (Get $get) {
                if ($get('action_taken')) return true;
                if ($get('status') !== 'pending') return true;



                return false; // metode selain tunai, tombol tetap aktif
            })

            // Jalankan aksi jika tombol ditekan
            ->action(function (array $data, Set $set, Get $get, $livewire) {
                return self::handleConfirmAction($data, $set, $get, $livewire);
            });
    }

    private static function getCancelAction(): Forms\Components\Actions\Action
    {
        return Forms\Components\Actions\Action::make('Batalkan')
            ->label('Batalkan Pesanan')
            ->color('danger')
            ->icon('heroicon-o-x-circle')
            ->visible(fn(Get $get) => in_array($get('status'), ['pending']))
            ->disabled(function (Get $get) {
                return $get('action_taken') || $get('status') === 'dibatalkan';
            })
            ->requiresConfirmation()
            ->modalHeading('Batalkan Pesanan')
            ->modalDescription('Yakin ingin membatalkan pesanan ini?')
            ->action(function (Set $set, Get $get, $record) {
                return self::handleCancelAction($set, $get, $record);
            });
    }

    private static function handleConfirmAction(array $data, Set $set, Get $get, $livewire)
    {
        // Ambil semua inputan dari form
        $input = [
            'nama' => $get('nama'),
            'tipe_order' => $get('tipe_order'),
            'no_meja' => $get('no_meja'),
            'metode_bayar' => $get('metode_bayar'),
            'jumlah_uang_diberikan' => $get('jumlah_uang_diberikan'),
            'total' => $get('total'),
            'OrderDetails' => $get('OrderDetails'),
        ];

        // ✅ Validasi manual
        $validator = Validator::make(
            $input,
            [
                'nama' => 'required|string',
                'tipe_order' => 'required|string',
                'no_meja' => 'required_if:tipe_order,dine_in|string|nullable',
                'metode_bayar' => 'required|string',
                'OrderDetails' => 'required|array|min:1',
                'OrderDetails.*.menu_id' => 'required|integer|exists:menus,id',
                'OrderDetails.*.quantity' => 'required|integer|min:1',
                'OrderDetails.*.catatan' => 'nullable|string',
            ],
            [
                'nama.required' => 'Nama Pelanggan harus diisi!.',
                'tipe_order.required' => 'Tipe order harus dipilih!.',
                'no_meja.required_if' => 'Nomor meja harus diisi!.',
                'metode_bayar.required' => 'Metode pembayaran wajib dipilih!.',
                'OrderDetails.required' => 'Pesanan tidak boleh kosong!.',
                'OrderDetails.*.menu_id.required' => 'Menu harus dipilih!.',
                'OrderDetails.*.quantity.required' => 'Jumlah pesanan harus diisi!.',
                'OrderDetails.*.quantity.min' => 'Jumlah pesanan minimal 1.',
            ]
        );

        if ($validator->fails()) {
            Notification::make()
                ->title('Konfirmasi Gagal!')
                ->body(collect($validator->errors()->all())->join("\n"))
                ->danger()
                ->send();

            return;
        }

        if ($get('metode_bayar') === 'tunai') {
            $jumlahBayar = (float) preg_replace('/\D/', '', $get('jumlah_uang_diberikan'));
            $total = (float) preg_replace('/\D/', '', $get('total'));

            if ($jumlahBayar < $total) {
                Notification::make()
                    ->title('Jumlah Pembayaran Kurang')
                    ->body('Jumlah uang yang dibayar kurang dari total yang harus dibayar')
                    ->danger()
                    ->send();
                return;
            }
        }

        // Set action taken untuk disable tombol
        $set('action_taken', true);

        // Ambil semua data dari form
        $allData = [
            'nama' => $get('nama'),
            'tipe_order' => $get('tipe_order'),
            'no_meja' => $get('no_meja'),
            'status' => 'diproses',
            'kode_pesanan' => $get('kode_pesanan'),
            'metode_bayar' => $get('metode_bayar'),
            'quantity' => $get('quantity'),
            'jumlah_uang_diberikan' => self::parseRupiah($get('jumlah_uang_diberikan') ?? $get('total')),
            'kembalian' => self::parseRupiah($get('kembalian') ?? '0'),
            'total' => self::parseRupiah($get('total') ?? '0'),
            'OrderDetails' => $get('OrderDetails')
        ];

        // Cek apakah ini mode edit atau create
        $record = $livewire->getRecord();

        if ($record) {
            // Mode edit - update existing record
            $order = $record;
            $order->update([
                'nama' => $allData['nama'],
                'tipe_order' => $allData['tipe_order'],
                'no_meja' => $allData['no_meja'],
                'status' => $allData['status'],
                'kode_pesanan' => $allData['kode_pesanan'],
                'metode_bayar' => $allData['metode_bayar'],
                'quantity' => $allData['quantity'],
                'total' => $allData['total'],
                'jumlah_uang_diberikan' => $allData['jumlah_uang_diberikan'],
                'kembalian' => $allData['kembalian'],
            ]);

            // Update order details - hapus yang lama, buat yang baru
            $order->orderDetails()->delete();
            foreach ($allData['OrderDetails'] as $item) {
                $order->orderDetails()->create($item);
            }
        } else {
            // Mode create - buat record baru
            $order = Order::create([
                'nama' => $allData['nama'],
                'tipe_order' => $allData['tipe_order'],
                'no_meja' => $allData['no_meja'],
                'status' => $allData['status'],
                'kode_pesanan' => $allData['kode_pesanan'],
                'metode_bayar' => $allData['metode_bayar'],
                'quantity' => $allData['quantity'],
                'total' => $allData['total'],
                'jumlah_uang_diberikan' => $allData['jumlah_uang_diberikan'],
                'kembalian' => $allData['kembalian'],
            ]);

            // Buat order details
            foreach ($allData['OrderDetails'] as $item) {
                $order->orderDetails()->create($item);
            }
        }

        // Update status di form
        $set('status', 'diproses');

        // Notifikasi sukses
        $action = $record ? 'diupdate' : 'dikonfirmasi';
        Notification::make()
            ->title("Pesanan berhasil dikonfirmasi")
            ->body("Pesanan {$order->kode_pesanan} berhasil konfiramasi")
            ->success()
            ->send();

        // Redirect ke halaman cetak struk HTML
        return redirect()->route('kasir.print-struk.html', ['order' => $order->id]);
    }

    private static function handleCancelAction(Set $set, Get $get, $record)
    {
        // Set action taken untuk disable tombol
        $set('action_taken', true);
        $set('status', 'dibatalkan');

        // Jika record sudah ada (edit mode), update status
        if ($record) {
            $record->update(['status' => 'dibatalkan']);
        }

        Notification::make()
            ->title('Pesanan Dibatalkan')
            ->body('Pesanan telah dibatalkan')
            ->warning()
            ->send();

        // Redirect ke halaman index
        return redirect(OrderResource::getUrl('index'));
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('1s')
            ->columns([
                Tables\Columns\TextColumn::make('kode_pesanan')
                    ->label('Kode Pesanan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Pelanggan')
                    ->searchable()
                    ->sortable(),

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

                Tables\Columns\TextColumn::make('metode_bayar')
                    ->label('Metode Bayar')
                    ->badge()
                    ->icon(fn(string $state): string => match ($state) {
                        'tunai' => 'heroicon-o-banknotes',
                        'non_tunai' => 'heroicon-o-credit-card',
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'tunai' => 'info',
                        'non_tunai' => 'primary',
                    })
                    ->formatStateUsing(fn(string $state) => match ($state) {
                        'tunai' => 'Tunai',
                        'non_tunai' => 'Non Tunai',
                    })
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->icon(fn(string $state): string => match ($state) {
                        'pending' => 'heroicon-o-clock',
                        'diproses' => 'heroicon-o-fire',
                        'selesai' => 'heroicon-o-check-circle',
                        'dibatalkan' => 'heroicon-o-x-circle',
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'diproses' => 'info',
                        'selesai' => 'success',
                        'dibatalkan' => 'danger',
                    }),



                Tables\Columns\TextColumn::make('tipe_order')
                    ->label('Tipe Order')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'dine_in' => 'success',
                        'takeaway' => 'warning',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'dine_in' => 'DINE IN',
                        'takeaway' => 'TAKEAWAY',
                    }),

                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->formatStateUsing(fn($state) => 'Rp ' . number_format((int) $state, 0, ',', '.'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'diproses' => 'Diproses',
                        'selesai' => 'Selesai',
                        'dibatalkan' => 'Dibatalkan',
                    ]),
            ])
            ->actions([])
            ->bulkActions([])
            ->defaultSort('created_at', 'desc');
    }

    // Method untuk set harga menu
    private static function setMenuPrice(Set $set, $menuId)
    {
        if ($menuId) {
            $menu = Menu::find($menuId);
            $set('harga', $menu ? $menu->harga : 0);
        }
    }

    // Method untuk update total
    private static function updateTotals(Set $set, Get $get)
    {
        $items = $get('OrderDetails') ?? [];

        $totalQty = collect($items)->sum(function ($item) {
            return is_numeric($item['quantity'] ?? null) ? (int) $item['quantity'] : 0;
        });

        $totalBayar = collect($items)->sum(function ($item) {
            $qty = isset($item['quantity']) && is_numeric($item['quantity']) ? (int) $item['quantity'] : 0;
            $harga = isset($item['harga']) && is_numeric($item['harga']) ? (int) $item['harga'] : 0;
            return $qty * $harga;
        });


        $set('quantity', $totalQty);
        $set('total', $totalBayar);
        $set('total_display', number_format($totalBayar, 0, ',', '.'));
    }
    public static function parseRupiah($value): int
    {
        return (int) preg_replace('/\D/', '', $value);
    }

    private static function readonlyCondition(array $status = ['diproses', 'selesai']): \Closure
    {
        return fn(Get $get) => in_array($get('status'), $status);
    }



    // Method untuk cetak struk
    // private static function printReceipt($order)
    // {
    //     // Load order dengan relasi
    //     $order->load(['orderDetails.menu']);

    //     $data = [
    //         'order' => $order,
    //         'company' => [
    //             'name' => 'Kedai Marbaka Ulama Indonesia',
    //             'address' => 'Jl. Marco No.01  Kec.bebek standing dan terbang Kel.Karapan Sapi',
    //             'phone' => '0123-456-789',
    //         ]
    //     ];

    //     $pdf = Pdf::loadView('struk', $data)
    //         ->setPaper([0, 0, 226.77, 566.93], 'portrait'); // 80mm thermal paper

    //     return $pdf->stream("struk-{$order->kode_pesanan}.pdf");
    // }

    public static function getEloquentQuery(): Builder
    {
        return static::getModel()::query()
            ->whereDate('created_at', Carbon::today('Asia/Jakarta'));
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function getSlug(): string
    {
        return 'kasir/orders';
    }


    public static function shouldRegisterNavigation(): bool
    {
        return Auth::check() && Auth::user()?->role === 'kasir';
    }

    public static function canViewAny(): bool
    {
        return Auth::check() && Auth::user()?->role === 'kasir';
    }
}
