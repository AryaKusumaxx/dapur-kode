<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\WarrantyController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandingPageController;
use Illuminate\Support\Facades\Route;

// Halaman publik
Route::get('/', [LandingPageController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// Routes untuk pengguna yang sudah login
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Routes untuk order
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    
    // Routes untuk pembayaran
    Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
    Route::get('/payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');
    
    // Routes untuk invoice
    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('/invoices/{invoice}/download', [InvoiceController::class, 'download'])->name('invoices.download');
    
    // Routes untuk garansi
    Route::get('/warranties', [WarrantyController::class, 'index'])->name('warranties.index');
    Route::get('/warranties/{warranty}', [WarrantyController::class, 'show'])->name('warranties.show');
    Route::get('/warranties/{warranty}/download', [WarrantyController::class, 'downloadCertificate'])->name('warranties.download');
    Route::post('/warranties/{warranty}/extend', [WarrantyController::class, 'extend'])->name('warranties.extend');
    
    // Validasi kode diskon
    Route::post('/discounts/validate', [DiscountController::class, 'validateCode'])->name('discounts.validate');
});

// Routes untuk admin dan manager
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');
    
    // Admin product management
    Route::resource('products', ProductController::class)->except(['index', 'show']);
    Route::resource('discounts', DiscountController::class);
    
    // Admin order management
    Route::get('/orders', [OrderController::class, 'adminIndex'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'adminShow'])->name('orders.show');
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    
    // Admin payment management
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::patch('/payments/{payment}/verify', [PaymentController::class, 'verify'])->name('payments.verify');
    Route::patch('/payments/{payment}/reject', [PaymentController::class, 'reject'])->name('payments.reject');
    
    // Admin warranty management
    Route::get('/warranties', [WarrantyController::class, 'adminIndex'])->name('warranties.index');
    Route::get('/warranties/{warranty}', [WarrantyController::class, 'adminShow'])->name('warranties.show');
    
    // Admin settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::patch('/settings', [SettingController::class, 'update'])->name('settings.update');
});

// Routes khusus untuk manager
Route::middleware(['auth', 'role:manager'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/reports/sales', [OrderController::class, 'salesReport'])->name('reports.sales');
    Route::get('/reports/warranties', [WarrantyController::class, 'warrantiesReport'])->name('reports.warranties');
});

require __DIR__.'/auth.php';
