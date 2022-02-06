<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\RegistController;

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

    //Route::get('registry', [RegistController::class, 'index'])->name('regists');
    Route::resource('registry', RegistController::class);
    Route::get('registry/{slug}/{parametr}', [RegistController::class, 'list'])->name('registries');



    // Route::get('/messages/{id}', [ChatController::class, 'getMessagesFor'])->name('messages');
    // Route::post('/message/send', [ChatController::class, 'send'])->name('message.send');

});
