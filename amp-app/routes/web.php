<?php

use App\Http\Controllers\BalanceController;
use App\Http\Controllers\Categories\CategoryBreadingsController;
use App\Http\Controllers\Categories\CategoryController;
use App\Http\Controllers\Categories\CategoryDepositsController;
use App\Http\Controllers\Categories\CategoryExpansesController;
use App\Http\Controllers\Categories\CategoryIncomesController;
use App\Http\Controllers\Categories\CategoryInventorysController;
use App\Http\Controllers\Categories\CategoryReportsController;
use App\Http\Controllers\Categories\CategoryReportsDownloadController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Deposits\DepositController;
use App\Http\Controllers\Expanses\ExpanseController;
use App\Http\Controllers\Incomes\IncomeController;
use App\Http\Controllers\InReview\InReviewController;
use App\Http\Controllers\Inventorys\InventoryController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\Productions\MilkProductionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Sells\MilkSellController;
use App\Http\Controllers\Types\TypeController;
use App\Http\Controllers\Units\UnitController;
use App\Http\Controllers\Users\UserController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('dashboard')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/categories/{category}/reports/date-wise', [CategoryReportsController::class, 'getFilterDateWiseReport'])->name('categories.reports.date-wise');
        Route::get('/inReview', [InReviewController::class, 'index'])->name('inReview');
        Route::resources([
            'categories' => CategoryController::class,
            'deposits' => DepositController::class,
            'expanses' => ExpanseController::class,
            'incomes' => IncomeController::class,
            'inventories' => InventoryController::class,
        ]);
        Route::resource('/types', TypeController::class)->except(['create', 'edit']);
        Route::resource('/productions', MilkProductionController::class)->except(['create', 'edit']);
        Route::resource('/sells', MilkSellController::class)->except(['create', 'edit']);
        Route::get('/milk-productions', [MilkProductionController::class, 'milkProductionReport'])->name('milkProductionReport');

        Route::resource('/units', UnitController::class)->except(['create', 'edit']);
        Route::resource('/users', UserController::class)->except(['create', 'edit']);

        Route::resource('categories.reports', CategoryReportsController::class)->only('index');
        Route::resource('categories.reports-download', CategoryReportsDownloadController::class)->only('index');
        Route::resource('categories.deposits', CategoryDepositsController::class);
        Route::resource('categories.expanses', CategoryExpansesController::class);
        Route::resource('categories.incomes', CategoryIncomesController::class);
        Route::resource('categories.inventories', CategoryInventorysController::class);
        Route::resource('categories.breadings', CategoryBreadingsController::class)->only('index');

        Route::get('language/{locale}', [LanguageController::class, 'setLocale'])->name('setLocale');
        Route::put('users/{user}/update-password', [UserController::class, 'updatePassword'])->name('users.update-password');
        Route::get('balance', [BalanceController::class, 'getBalance']);
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
