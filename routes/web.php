<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\RegionController;

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

    // Route::get('/messages/{id}', [ChatController::class, 'getMessagesFor'])->name('messages');
    // Route::post('/message/send', [ChatController::class, 'send'])->name('message.send');

});
