<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryBarangController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SupplyController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return redirect('/');
})->name('dashboard');



Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('home');
    Route::get('/carts/{start}/{end}', [DashboardController::class, 'cartsIncome'])->name('home.carts.data-by-date');
    Route::get('/home/{start}/{end}', [DashboardController::class, 'data'])->name('home.data');
    Route::get('/incomeCount/{start}/{end}', [DashboardController::class, 'incomeCount'])->name('home.incomeCount');
    Route::get('/incomeSum/{start}/{end}', [DashboardController::class, 'incomeSum'])->name('home.incomeSum');
    Route::get('/incomeAverage/{start}/{end}', [DashboardController::class, 'incomeAverage'])->name('home.incomeAverage');

    // User
    Route::resource('user', UserController::class);
    Route::get('user/delete/{id}', [UserController::class, 'delete'])->name('user.delete');

    // Profile
    Route::get('profile', [UserController::class, 'profile'])->name('profile');
    Route::patch('profile', [UserController::class, 'updateProfile'])->name('profile-update');

    // Barang -> Kategory
    Route::get('category-barang/data/{status}', [CategoryBarangController::class, 'listData'])->name('category-barang.data');
    Route::resource('category-barang', CategoryBarangController::class);
  
    // Barang -> Data Barang
    Route::get('data-barang/data/{categoryId}/{supplierId}/{status}', [BarangController::class, 'listData'])->name('data-barang.data');
    Route::resource('data-barang', BarangController::class);

    // Supplier
    Route::get('supplier/data', [SupplierController::class, 'listData'])->name('supplier.data');
    Route::resource('supplier', SupplierController::class);

    // Customer
    Route::get('customer/data', [CustomerController::class, 'listData'])->name('customer.data');
    Route::post('customer/add', [CustomerController::class, 'add'])->name('customer.add');
    Route::resource('customer', CustomerController::class);

    // Report -> Expenses
    Route::get('expense/data', [ExpenseController::class, 'listData'])->name('expense.data');
    Route::get('expense/pdf', [ExpenseController::class, 'listDataPdf'])->name('expense.pdf');
    Route::get('expense/{start}/{end}', [ExpenseController::class, 'listData'])->name('expense.data-by-date');
    Route::resource('expense', ExpenseController::class);

    // Report -> incomes
    Route::get('income/export/{start}/{end}', [IncomeController::class, 'exportPdf'])->name('income.export');
    Route::get('income/download/{start}/{end}', [IncomeController::class, 'downloadPdf'])->name('income.download');
    Route::get('income/data', [IncomeController::class, 'listData'])->name('income.data');
    Route::get('income/print', [IncomeController::class, 'printReceipt'])->name('income.print');
    Route::get('income/{start}/{end}', [IncomeController::class, 'listData'])->name('income.data-by-date');
    Route::resource('income', IncomeController::class);

    // Transactions -> Sales
    Route::get('sales', [OrderController::class, 'index'])->name('sales.index');
    Route::get('sales/data', [OrderController::class, 'getAllData'])->name('sales.data');
    Route::get('sales/create', [OrderController::class, 'create'])->name('sales.create');
    Route::get('sales/{id}', [OrderController::class, 'getById'])->name('sales.byid');
    Route::post('sales/create', [OrderController::class, 'store'])->name('sales.store');
    Route::get('sales/edit/{id}', [OrderController::class, 'edit'])->name('sales.edit');
    Route::post('sales/update/{id}', [OrderController::class, 'update'])->name('sales.update');
    Route::get('sales/print-invoice/{order_id}', [OrderController::class, 'printReceiptHandler'])->name('sales.print');
    
    // Transactions -> Sales
    Route::get('supply', [SupplyController::class, 'index'])->name('supply.index');
    Route::get('supply/data', [SupplyController::class, 'getAllData'])->name('supply.data');
    Route::get('supply/create', [SupplyController::class, 'create'])->name('supply.create');
    Route::get('supply/{id}', [SupplyController::class, 'getById'])->name('supply.byid');
    Route::post('supply', [SupplyController::class, 'store'])->name('supply.store');
    Route::get('supply/edit/{id}', [SupplyController::class, 'edit'])->name('supply.edit');
    Route::post('supply/update/{id}', [SupplyController::class, 'update'])->name('supply.update');
    
    // Settings
    Route::get('settings/data', [SettingsController::class, 'getAllData'])->name('settings.data');
    Route::resource('settings', SettingsController::class);

});

require_once __DIR__ . "/jetstream.php";