<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\RegistController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\EnterprisesController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\StatController;
use Glhd\Gretel\Routing\ResourceBreadcrumbs;

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

    Route::get('/csv', function () {
        return Inertia::render('Maps/Enterprises/File');
    })->name('csv')->middleware('role:super-admin');
    Route::post('enterprises_u', [EnterprisesController::class, 'data_upload'])->name('csv_u')->middleware('role:super-admin');

    Route::resource('regions', RegionController::class);
    Route::get('map', [RegionController::class, 'map'])->name('map');

    Route::resource('regions.enterprises', EnterprisesController::class);

    Route::get('regions/enterprises/all', [EnterprisesController::class, 'all'])->name('regions.enterprises.all');
    Route::get('regions/enterprises/all/export', [EnterprisesController::class, 'export']);

    Route::resource('registry', RegistController::class);
    Route::get('registry/{slug}/{parametr}', [RegistController::class, 'list'])->name('registry.list');

    Route::get('statistics', [StatController::class, 'index'])->name('stats.index');

    Route::group(['prefix' => 'settings'], function () {
        Route::resource('users', UsersController::class)->middleware('permission:cp.users');
        // Роли
        Route::resource('roles', RoleController::class)->except(['show'])->middleware('permission:cp.roles');

        // Отделы
        Route::resource('departments', DepartmentController::class)->middleware('permission:cp.departments');

        // Права доступа
        Route::resource('permissions', PermissionController::class)->except(['show'])->middleware('permission:cp.permission');

        // Настройки
        Route::get('/', function () {
            return Inertia::render('Settings/Index');
        })->middleware('permission:cp')->name('settings');
    });

    Route::get('test', function () {
        App\Models\User::sendNotify('ваипр', "ывамждбыджуа", 1);
        //event(new App\Events\Notify('notify'));
        return "Event has been sent!";
    });
    // Route::get('/messages/{id}', [ChatController::class, 'getMessagesFor'])->name('messages');
    // Route::post('/message/send', [ChatController::class, 'send'])->name('message.send');

});
