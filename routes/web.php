<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProposalController;
use Illuminate\Support\Facades\Route;

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

Route::controller(ProposalController::class)
    ->middleware(['auth'])
    ->name('proposal.')
    ->group(function () {
        Route::get('/', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/proposals', 'index')->name('index');
        Route::get('/proposals/{id}', 'edit')->name('edit');
        Route::put('/proposals/{id}', 'update')->name('update');
    });

Route::controller(ProfileController::class)
    ->middleware(['auth'])
    ->name('profile.')
    ->group(function () {
        Route::get('/profile', 'index')->name('index');
        Route::put('/profile/update', 'update')->name('update');
    });

require __DIR__ . '/auth.php';
require __DIR__ . '/admin.php';
