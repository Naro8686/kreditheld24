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
use App\Http\Controllers\SendEmailController;
use App\Http\Controllers\EmailTemplateController;


Route::middleware(['auth'])->group(function () {
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/', 'index')->name('dashboard');
        Route::get('/statistics', 'statistics')->name('statistics');
        Route::get('/read-file', 'readFile')->name('readFile');
    });
    Route::resource('email-templates', EmailTemplateController::class);
    Route::controller(ProposalController::class)->prefix('proposals')->name('proposal.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/draft', 'draft')->name('draft');
        Route::get('/duplicate/{id}', 'duplicate')->name('duplicate');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}', 'edit')->name('edit');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'delete')->name('delete');
    });
    Route::controller(ProfileController::class)->prefix('profile')->name('profile.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::put('/update', 'update')->name('update');
    });
    Route::controller(SendEmailController::class)->name('email.')->prefix('email')->group(function () {
        Route::post('/send', 'send')->name('send');
    });
    Route::get('contacts', [\App\Http\Controllers\ContactController::class, 'index'])->name('contacts');
    Route::get('formulas', [\App\Http\Controllers\FormulaController::class, 'index'])->name('formulas');
    Route::match(['GET', 'POST'], '/export-to-pdf', [\App\Http\Controllers\ProposalController::class, 'exportToPdf'])->name('exportToPdf');
});

require __DIR__ . '/auth.php';
require __DIR__ . '/admin.php';
