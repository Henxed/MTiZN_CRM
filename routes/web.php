<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\RegistController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
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
Route::group(['middleware' => 'auth'], function () {
    Route::get('/', function () {
        return Inertia::render('Home');
    })->name('home');


    Route::get('/regions', [RegionController::class, 'index'])->name('regions');
    Route::get('/region/{id}', [RegionController::class, 'show'])->name('region');

    Route::resource('registry', RegistController::class);
    Route::get('registry/{slug}/{parametr}', [RegistController::class, 'list'])->name('registries');

    Route::group(['prefix' => 'settings'], function () {
        Route::resource('users', UsersController::class);
        // Роли
        Route::resource('roles', RoleController::class)->except(['show'])->middleware('role:super-admin');

        // Права доступа
        Route::resource('permissions', PermissionController::class)->except(['show'])->middleware('role:super-admin');

        // Настройки
        Route::get('/', function () {
            return Inertia::render('Settings/Index');
        })->middleware('role:super-admin')->name('settings');
    });

    // Route::get('/messages/{id}', [ChatController::class, 'getMessagesFor'])->name('messages');
    // Route::post('/message/send', [ChatController::class, 'send'])->name('message.send');

});
