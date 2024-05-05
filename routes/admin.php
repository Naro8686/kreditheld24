<?php

use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\FileManagerController;
use App\Http\Controllers\Admin\FormulaController;
use App\Http\Controllers\Admin\ManagerController;
use App\Http\Controllers\Admin\ProposalController as AdminProposalController;
use App\Http\Controllers\Admin\SendEmailController;
use App\Http\Controllers\DashboardController;
use App\Models\Role;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth', 'role:' . Role::ADMIN])->prefix('admin')->name('admin.')->group(function () {
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/download-file', 'downloadFile')->name('downloadFile');
        Route::get('/download-zip/{proposal_id}', 'downloadZip')->name('downloadZip');
        Route::get('/download-files-zip', 'downloadFilesZip')->name('downloadFilesZip');
    });
    Route::controller(AdminProposalController::class)->name('proposals.')->group(function () {
        Route::get('/proposals', 'index')->name('index');
        Route::get('/proposals/archive', 'archive')->name('archive');
        Route::get('/proposals/duplicate/{id}', 'duplicate')->name('duplicate');
        Route::post('/proposals/archive/duplicate/all', 'archiveDuplicateAll')->name('archive.duplicate.all');
        Route::get('/proposals/{id}', 'edit')->name('edit');
        Route::put('/proposals/{id}', 'update')->name('update');
        Route::delete('/proposals/{id}', 'delete')->name('delete');
        Route::get('/export/{id}', 'export')->name('export');
    });
    Route::controller(ManagerController::class)->name('managers.')->group(function () {
        Route::get('/managers', 'index')->name('index');
        Route::get('/managers/create', 'create')->name('create');
        Route::post('/managers/store', 'store')->name('store');
        Route::get('/managers/show/{id}', 'show')->name('show');
        Route::get('/managers/edit/{id}', 'edit')->name('edit');
        Route::put('/managers/update/{id}', 'update')->name('update');
        Route::delete('/managers/{id}', 'delete')->name('delete');
    });
    Route::controller(SendEmailController::class)->name('email.')->prefix('email')->group(function () {
        Route::get('/{type}', 'index')
            ->where('type', implode('|', SendEmailController::$types))
            ->name('index');
        Route::post('/{type}', 'send')
            ->where('type', implode('|', SendEmailController::$types))
            ->name('send');
    });
    Route::resource('contacts', ContactController::class);
    Route::resource('formulas', FormulaController::class);
    Route::get('/file-manager', [FileManagerController::class, 'index'])->name('file-manager.index');
});
