<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryProductController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DepoController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\StockFlowController;
use App\Http\Controllers\CashFlowController;
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

    // Product -> Kategory
    Route::get('category-product/data/{status}', [CategoryProductController::class, 'listData'])->name('category-product.data');
    Route::resource('category-product', CategoryProductController::class);
  
    // Product -> Data Product
    Route::get('data-product/data/{categoryId}/{supplierId}/{status}', [ProductController::class, 'listData'])->name('data-product.data');
    Route::resource('data-product', ProductController::class);

    // Depo
    Route::get('depo/data', [DepoController::class, 'listData'])->name('depo.data');
    Route::resource('depo', DepoController::class);

    // Employyee
    Route::get('employee/data', [EmployeeController::class, 'listData'])->name('employee.data');
    Route::post('employee/add', [EmployeeController::class, 'add'])->name('employee.add');
    Route::resource('employee', EmployeeController::class);

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

    // Stock Flow
    Route::get('stock', [StockFlowController::class, 'index'])->name('stock.index');
    Route::get('stock/data', [StockFlowController::class, 'getAllData'])->name('stock.data');
    Route::get('stock/create', [StockFlowController::class, 'create'])->name('stock.create');
    Route::get('stock/{id}', [StockFlowController::class, 'getById'])->name('stock.byid');
    Route::post('stock/create', [StockFlowController::class, 'store'])->name('stock.store');
    Route::get('stock/edit/{id}', [StockFlowController::class, 'edit'])->name('stock.edit');
    Route::post('stock/update/{id}', [StockFlowController::class, 'update'])->name('stock.update');
    Route::get('stock/print-invoice/{order_id}', [StockFlowController::class, 'printReceiptHandler'])->name('stock.print');
    
    // Cash Flow
    Route::get('cashflow', [CashFlowController::class, 'index'])->name('cashflow.index');
    Route::get('cashflow/data', [CashFlowController::class, 'getAllData'])->name('cashflow.data');
    Route::get('cashflow/create', [CashFlowController::class, 'create'])->name('cashflow.create');
    Route::post('cashflow/create', [CashFlowController::class, 'store'])->name('cashflow.store');
    Route::get('cashflow/edit/{id}', [CashFlowController::class, 'edit'])->name('cashflow.edit');
    
    // Depo
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