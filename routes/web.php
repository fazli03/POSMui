<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StrukController;

Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/note', [CartController::class, 'updateNote'])->name('cart.note');


Route::get('/pembayaran', [OrderController::class, 'showPembayaranForm'])->name('cart.formPembayaran');
Route::post('/pembayaran', [OrderController::class, 'prosesPembayaran'])->name('cart.prosesPembayaran');

Route::get('/', function () {
    return view('home');
});

Route::get('/menu', [ProdukController::class, 'index'])->name('dine.index');
Route::get('/info/{id}', [ProdukController::class, 'show']);


Route::get('/take', function () {
    return view('take-away');
});

// Route::get('/pesanan', [OrderController::class, 'pesananSaya']);

// Route::get('/menu', function () {
//     return view('menu');
// });

Route::get('/order-finish', [OrderController::class, 'pesananSaya'])->name('order.finish');
Route::get('/order-finish/{id}', [OrderController::class, 'pesananTertentu'])->name('order.finish.specific');


Route::get('/xx', function () {
    return view('welcome');
})->name('home');



Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
Route::get('/kasir/struk/{order}', [StrukController::class, 'showHtml'])
    ->name('kasir.print-struk.html');



require __DIR__ . '/auth.php';
