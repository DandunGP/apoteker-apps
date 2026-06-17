<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

use App\Http\Controllers\AuthController;

use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Unified Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');
Route::get('/dashboard/export-fifo', [DashboardController::class, 'exportFifo'])->middleware('auth')->name('dashboard.export-fifo');

use App\Http\Controllers\MedicineController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\StockAdjustmentController;

// Future role-specific modules
// Shared monitoring & reports accessible by both Admin Gudang and Apoteker
Route::middleware(['auth', 'role:admin_gudang,apoteker'])->prefix('admin')->group(function () {
    Route::get('monitoring/stock', [MedicineController::class, 'monitoringStock'])->name('admin.monitoring.stock');
    Route::get('monitoring/stock/export', [MedicineController::class, 'monitoringStockExport'])->name('admin.monitoring.stock.export');
    Route::get('monitoring/stock/labels', [MedicineController::class, 'monitoringStockLabels'])->name('admin.monitoring.stock.labels');
    Route::get('monitoring/expiry', [MedicineController::class, 'monitoringExpiry'])->name('admin.monitoring.expiry');
    Route::get('monitoring/expiry/export', [MedicineController::class, 'monitoringExpiryExport'])->name('admin.monitoring.expiry.export');
    Route::get('monitoring/expiry/labels', [MedicineController::class, 'monitoringExpiryLabels'])->name('admin.monitoring.expiry.labels');
    Route::get('reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('admin.reports.index');
    Route::get('reports/stock/export', [\App\Http\Controllers\ReportController::class, 'exportStock'])->name('admin.reports.stock.export');
    Route::get('reports/incoming/export', [\App\Http\Controllers\ReportController::class, 'exportIncoming'])->name('admin.reports.incoming.export');
    Route::get('reports/sales/export', [\App\Http\Controllers\ReportController::class, 'exportSales'])->name('admin.reports.sales.export');
    Route::get('reports/expiry/export', [\App\Http\Controllers\ReportController::class, 'exportExpiry'])->name('admin.reports.expiry.export');
    Route::post('reports/process-expiry-action', [\App\Http\Controllers\ReportController::class, 'processExpiryAction'])->name('admin.reports.process-expiry-action');
    
    // Stock Opname History
    Route::get('adjustments/history', [StockAdjustmentController::class, 'history'])->name('adjustments.history');
});

// Admin Gudang exclusive actions
Route::middleware(['auth', 'role:admin_gudang'])->prefix('admin')->group(function () {
    Route::get('retur-pemusnahan', [\App\Http\Controllers\ReportController::class, 'returPemusnahanIndex'])->name('admin.retur-pemusnahan.index');
    Route::get('retur-pemusnahan/{id}/print', [\App\Http\Controllers\ReportController::class, 'printReturPemusnahanDocument'])->name('admin.retur-pemusnahan.print');
    Route::get('medicines/export', [MedicineController::class, 'export'])->name('medicines.export');
    Route::resource('medicines', MedicineController::class);
    Route::resource('suppliers', \App\Http\Controllers\SupplierController::class);
    Route::get('batches', [BatchController::class, 'index'])->name('batches.index');
    Route::get('batches/create', [BatchController::class, 'create'])->name('batches.create');
    Route::post('batches', [BatchController::class, 'store'])->name('batches.store');
    Route::get('batches/{batch}/print', [BatchController::class, 'print'])->name('batches.print');

    // Stock Opname exclusive actions
    Route::get('adjustments', [StockAdjustmentController::class, 'index'])->name('adjustments.index');
    Route::get('adjustments/create/{id}', [StockAdjustmentController::class, 'create'])->name('adjustments.create');
    Route::post('adjustments', [StockAdjustmentController::class, 'store'])->name('adjustments.store');
    
    // Services (Pelayanan Jasa)
    Route::resource('services', \App\Http\Controllers\ServiceController::class);
    // User Management
    Route::resource('users', \App\Http\Controllers\UserController::class);
});

use App\Http\Controllers\PharmacistController;
use App\Http\Controllers\ValidationController;

Route::middleware(['auth', 'role:apoteker'])->prefix('apoteker')->group(function () {
    Route::get('stock', [PharmacistController::class, 'verifyStock'])->name('apoteker.stock');
    Route::get('expiry', [PharmacistController::class, 'expiryAlerts'])->name('apoteker.expiry');
    Route::get('report', [PharmacistController::class, 'salesReport'])->name('apoteker.report');
    // Validasi Obat Masuk
    Route::get('validasi', [ValidationController::class, 'index'])->name('apoteker.validasi.index');
    Route::get('validasi/panel', [ValidationController::class, 'panel'])->name('apoteker.validasi.panel');
    Route::post('validasi/confirm', [ValidationController::class, 'confirm'])->name('apoteker.validasi.confirm');
    Route::post('validasi/defer', [ValidationController::class, 'defer'])->name('apoteker.validasi.defer');
});

use App\Http\Controllers\POSController;

Route::middleware(['auth', 'role:kasir'])->prefix('kasir')->group(function () {
    Route::get('pos', [POSController::class, 'index'])->name('pos.index');
    Route::post('pos/checkout', [POSController::class, 'checkout'])->name('pos.checkout');
    Route::get('sales', [POSController::class, 'history'])->name('sales.history');
    Route::get('sales/{sale}/print', [POSController::class, 'printReceipt'])->name('sales.print');
    Route::post('sales/{sale}/refund', [POSController::class, 'refund'])->name('sales.refund');
    Route::get('search', [POSController::class, 'searchMedicines'])->name('kasir.search');
    Route::get('cashflow', [\App\Http\Controllers\CashFlowController::class, 'index'])->name('kasir.cashflow');
    Route::post('cashflow', [\App\Http\Controllers\CashFlowController::class, 'store'])->name('kasir.cashflow.store');
    Route::get('cashflow/export', [\App\Http\Controllers\CashFlowController::class, 'export'])->name('kasir.cashflow.export');
});
