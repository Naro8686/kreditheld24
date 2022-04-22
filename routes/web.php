<?php
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

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProposalController;


Route::middleware(['auth'])->group(function () {
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/', 'index')->name('dashboard');
        Route::get('/statistics', 'statistics')->name('statistics');
        Route::get('/read-file', 'readFile')->name('readFile');
    });
    Route::controller(ProposalController::class)->prefix('proposals')->name('proposal.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/draft', 'draft')->name('draft');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}', 'edit')->name('edit');
        Route::put('/{id}', 'update')->name('update');
    });
    Route::controller(ProfileController::class)->prefix('profile')->name('profile.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::put('/update', 'update')->name('update');
    });
    Route::get('contacts', [\App\Http\Controllers\ContactController::class, 'index'])->name('contacts');
    Route::get('formulas', [\App\Http\Controllers\FormulaController::class, 'index'])->name('formulas');
});

require __DIR__ . '/auth.php';
require __DIR__ . '/admin.php';
