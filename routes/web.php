<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DeliveryNoteController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PerusahaanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RekeningController;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // ==== PROFILE (bawaan Breeze) ====
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ==== MASTER ====
    Route::resource('perusahaan', PerusahaanController::class);
    Route::resource('customer', CustomerController::class);
    Route::resource('barang', BarangController::class);
    Route::resource('rekening', RekeningController::class);

    // AJAX helper untuk form Delivery Note / Invoice
    Route::get('/rekening-by-perusahaan/{perusahaan}', [RekeningController::class, 'byPerusahaan'])
        ->name('rekening.byPerusahaan');
    Route::get('/barang-detail/{barang}', [BarangController::class, 'detail'])
        ->name('barang.detail');

    // ==== TRANSAKSI: DELIVERY NOTE ====
    Route::get('/delivery-note/{delivery_note}/cetak', [DeliveryNoteController::class, 'cetak'])
        ->name('delivery-note.cetak');
    Route::resource('delivery-note', DeliveryNoteController::class);

    // ==== TRANSAKSI: INVOICE ====
    Route::get('/invoice/{invoice}/cetak', [InvoiceController::class, 'cetak'])
        ->name('invoice.cetak');
    Route::get('/delivery-note-by-perusahaan/{perusahaan}', [InvoiceController::class, 'deliveryNotesByPerusahaan'])
        ->name('invoice.deliveryNotesByPerusahaan');
    Route::get('/delivery-note-detail/{delivery_note}', [InvoiceController::class, 'deliveryNoteDetail'])
        ->name('invoice.deliveryNoteDetail');
    Route::resource('invoice', InvoiceController::class);

    // ==== LAPORAN ====
    Route::get('/laporan/delivery-note', [DeliveryNoteController::class, 'index'])
        ->name('laporan.delivery-note');
    Route::get('/laporan/invoice', [InvoiceController::class, 'index'])
        ->name('laporan.invoice');
});