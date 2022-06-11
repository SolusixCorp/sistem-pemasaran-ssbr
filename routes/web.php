<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryProductController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductDepoController;
use App\Http\Controllers\DepoController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ReportController;
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

    // Product -> Kategori
    Route::get('category-product/data/{status}', [CategoryProductController::class, 'listData'])->name('category-product.data');
    Route::resource('category-product', CategoryProductController::class);
  
    // Product -> Data Product
    Route::get('data-product/data', [ProductController::class, 'listData'])->name('data-product.data');
    Route::resource('data-product', ProductController::class);

    // Product -> Data Product Depo
    Route::get('data-product-depo/data/{categoryId}/{depoId}/{status}', [ProductDepoController::class, 'listData'])->name('data-product-depo.data');
    Route::resource('data-product-depo', ProductDepoController::class);

    // Depo
    Route::get('depo/data', [DepoController::class, 'listData'])->name('depo.data');
    Route::resource('depo', DepoController::class);

    // Employyee
    Route::get('employee', [EmployeeController::class, 'index'])->name('employee.index');
    Route::get('employee/data', [EmployeeController::class, 'listData'])->name('employee.data');
    Route::get('employee/edit/{id}', [EmployeeController::class, 'edit'])->name('employee.edit');
    Route::post('employee/update/{id}', [EmployeeController::class, 'update'])->name('employee.update');
    Route::post('employee/create', [EmployeeController::class, 'store'])->name('employee.store');
    Route::get('employee/create', [EmployeeController::class, 'create'])->name('employee.create');
    // Route::resource('employee', EmployeeController::class);
    
    // Report
    Route::get('report', [ReportController::class, 'index'])->name('report.index');
    Route::get('report/create', [ReportController::class, 'create'])->name('report.create');
    Route::get('report/data', [ReportController::class, 'getAllData'])->name('report.data');
    Route::get('report/edit/{id}', [ReportController::class, 'edit'])->name('report.edit');
    Route::get('report/{start}/{end}', [ReportController::class, 'getAllData'])->name('report.data-by-date');
    Route::post('report/create', [ReportController::class, 'store'])->name('report.store');
    Route::post('report/update/{id}', [ReportController::class, 'update'])->name('report.update');
    
    // Stock Flow
    Route::get('stock', [StockFlowController::class, 'index'])->name('stock.index');
    Route::get('stock/data', [StockFlowController::class, 'getAllData'])->name('stock.data');
    Route::get('stock/product/{id}', [StockFlowController::class, 'getProductById'])->name('stock.product.id');
    Route::get('stock/create', [StockFlowController::class, 'create'])->name('stock.create');
    Route::get('stock/{id}', [StockFlowController::class, 'getById'])->name('stock.byid');
    Route::get('stock/date/{date}', [StockFlowController::class, 'getByDate'])->name('stock.bydate');
    Route::post('stock/create', [StockFlowController::class, 'store'])->name('stock.store');
    Route::get('stock/edit/{id}', [StockFlowController::class, 'edit'])->name('stock.edit');
    Route::post('stock/update/{id}', [StockFlowController::class, 'update'])->name('stock.update');
    
    // Cash Flow
    Route::get('cashflow', [CashFlowController::class, 'index'])->name('cashflow.index');
    Route::get('cashflow/data', [CashFlowController::class, 'getAllData'])->name('cashflow.data');
    Route::get('cashflow/create', [CashFlowController::class, 'create'])->name('cashflow.create');
    Route::get('cashflow/{id}', [CashFlowController::class, 'getById'])->name('cashflow.byid');
    Route::post('cashflow/create', [CashFlowController::class, 'store'])->name('cashflow.store');
    Route::post('cashflow/update/{id}', [CashFlowController::class, 'update'])->name('cashflow.update');
    Route::get('cashflow/edit/{id}', [CashFlowController::class, 'edit'])->name('cashflow.edit');
    Route::get('cashflow/receipt/{order_id}', [CashFlowController::class, 'printReceipt'])->name('stock.print');
    
    // Depo
    Route::get('supply', [SupplyController::class, 'index'])->name('supply.index');
    Route::get('supply/data', [SupplyController::class, 'getAllData'])->name('supply.data');
    Route::get('supply/create', [SupplyController::class, 'create'])->name('supply.create');
    Route::get('supply/{id}', [SupplyController::class, 'getById'])->name('supply.byid');
    Route::post('supply', [SupplyController::class, 'store'])->name('supply.store');
    Route::get('supply/edit/{id}', [SupplyController::class, 'edit'])->name('supply.edit');
    Route::post('supply/update/{id}', [SupplyController::class, 'update'])->name('supply.update');

});

require_once __DIR__ . "/jetstream.php";