<?php

use App\Models\Role;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ManagerController;
use App\Http\Controllers\Admin\ProposalController;
use App\Http\Controllers\Admin\SendEmailController;


Route::middleware(['auth', 'role:' . Role::ADMIN])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/statistics', [DashboardController::class, 'statistics'])->name('statistics');
        Route::get('/read-file', [DashboardController::class, 'readFile'])->name('readFile');
        Route::get('/download-zip/{proposal_id}', [DashboardController::class, 'downloadZip'])->name('downloadZip');
        Route::controller(ProposalController::class)
            ->name('proposals.')
            ->group(function () {
                Route::get('/proposals', 'index')->name('index');
                Route::get('/proposals/{id}', 'edit')->name('edit');
                Route::put('/proposals/{id}', 'update')->name('update');
                Route::delete('/proposals/{id}', 'delete')->name('delete');
            });
        Route::controller(ManagerController::class)
            ->name('managers.')
            ->group(function () {
                Route::get('/managers', 'index')->name('index');
                Route::get('/managers/create', 'create')->name('create');
                Route::post('/managers/create', 'store')->name('store');
                Route::delete('/managers/{id}', 'delete')->name('delete');
            });
        Route::controller(SendEmailController::class)
            ->name('email.')
            ->prefix('email')
            ->group(function () {
                Route::get('/{type}', 'index')
                    ->where('type', implode('|', SendEmailController::$types))
                    ->name('index');
                Route::post('/{type}', 'send')
                    ->where('type', implode('|', SendEmailController::$types))
                    ->name('send');
            });
    });
