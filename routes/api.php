<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\EnterprisesController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('registry/file/{id}/delete', [RegistController::class, 'file_destroy'])->name('registry.file.destroy');

    Route::post('settings/set-lvl', [SettingsController::class, 'setLvl'])->name('set-lvl')->middleware('permission:cp.stats-load');

    Route::get('notifications/get/popup', [UsersController::class, 'notification_popup'])->name('notification.get.popup');
    Route::get('notifications/set/{id}/read', [UsersController::class, 'notification_read'])->name('notification.set.read');
    Route::get('notifications/remove/{id}', [UsersController::class, 'notification_remove'])->name('notification.remove');

    Route::post('enterprise/get/all', [EnterprisesController::class, 'apiAll'])->name('enterpises.get.all');
    Route::get('enterprise/analytics/{id?}', [EnterprisesController::class, 'analytics'])->name('enterpises.analytics');
    Route::post('enterprise/dublicate/{inn}', [EnterprisesController::class, 'dublicate'])->name('enterpises.dublicate');
});
