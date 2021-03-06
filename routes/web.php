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
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SafetyController;
use Laravel\Jetstream\Http\Controllers\Inertia\UserProfileController;

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

    // Пользователи
    Route::group(['prefix' => 'user'], function () {
        Route::get('settings', [UserProfileController::class, 'show'])->name('profile.settings');
        Route::get('@{username}', [UsersController::class, 'show'])->name('profile.show');
    });

    // Охрана труда
    Route::resource('safety/partners', SafetyController::class, ['as' => 'safety'])->middleware('permission:safety');


    // Регионы
    Route::resource('regions', RegionController::class);
    // Карта всех регионов
    Route::get('map', [RegionController::class, 'map'])->name('map');

    // Предприятия регионов
    Route::resource('regions.enterprises', EnterprisesController::class);
    // Предприятя сводной
    Route::get('regions/enterprises/all', [EnterprisesController::class, 'all'])->name('regions.enterprises.all');
    // Выгрузка всех предприятий
    Route::get('regions/enterprises/all/export', [EnterprisesController::class, 'export']);

    // Реестры
    Route::resource('registry', RegistController::class);
    Route::get('registry/{slug}/{parametr}', [RegistController::class, 'list'])->name('registry.list');

    // Статистика
    Route::get('statistics', [StatController::class, 'index'])->name('stats.index');

    // Администрирование
    Route::group(['prefix' => 'settings'], function () {
        Route::resource('users', UsersController::class)->middleware('permission:cp.users');
        // Роли
        Route::resource('roles', RoleController::class)->except(['show'])->middleware('permission:cp.roles');

        // Отделы
        Route::resource('departments', DepartmentController::class)->middleware('permission:cp.departments');

        // Права доступа
        Route::resource('permissions', PermissionController::class)->except(['show'])->middleware('permission:cp.permission');

        Route::get('stats-load', [SettingsController::class, 'statsLoad'])->name('stats-load')->middleware('permission:cp.stats-load');
        Route::post('stats-load/upload', [RegionController::class, 'region_data_upload'])->name('cp.upload.stats')->middleware('permission:cp.stats-load');

        Route::get('entr-load', [SettingsController::class, 'entrLoad'])->name('entr-load')->middleware('permission:cp.entr-load');
        Route::post('entr-load/upload', [SettingsController::class, 'uploadEntrLoad'])->name('cp.upload.entr')->middleware('permission:cp.entr-load');


        // Настройки
        Route::get('/', function () {
            return Inertia::render('Settings/Index');
        })->middleware('permission:cp')->name('settings');
    });


    // Ниже тестовые или для пару раз

    Route::get('/csv', function () { // загрузка предприятий массово
        return Inertia::render('Maps/Enterprises/File');
    })->name('csv')->middleware('role:super-admin');
    Route::post('enterprises_u', [EnterprisesController::class, 'data_upload'])->name('csv_u')->middleware('role:super-admin');

    Route::get('test', function () {
        App\Models\User::sendNotify('ваипр', "ывамждбыджуа", 1);
        //event(new App\Events\Notify('notify'));
        return "Event has been sent!";
    });
    // Route::get('/messages/{id}', [ChatController::class, 'getMessagesFor'])->name('messages');
    // Route::post('/message/send', [ChatController::class, 'send'])->name('message.send');

});
