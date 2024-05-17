<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ExpenseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group([], function () {
    Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('categories/store', [CategoryController::class, 'store'])->name('categories.store');
    Route::post('categories/import', [CategoryController::class, 'import'])->name('categories.import');
    Route::get('categories/export', [CategoryController::class, 'export'])->name('categories.export');
    Route::get('category/{id}', [CategoryController::class, 'show'])->name('categories.show');
    Route::post('category/update/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('categories/delete/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
});

Route::group([], function () {
    Route::get('expenses', [ExpenseController::class, 'index'])->name('expenses.index');
    Route::post('expenses/store', [ExpenseController::class, 'store'])->name('expenses.store');
    Route::post('expenses/import', [ExpenseController::class, 'import'])->name('expenses.import');
    Route::get('expenses/export', [ExpenseController::class, 'export'])->name('expenses.export');
    Route::get('expense/{id}', [ExpenseController::class, 'show'])->name('expenses.show');
    Route::post('expenses/update/{id}', [ExpenseController::class, 'update'])->name('expenses.update');
    Route::delete('expenses/delete/{id}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');
});
