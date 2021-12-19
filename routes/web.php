<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\GameProfile\ChatController;
use App\Http\Controllers\GamesController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\BoostController;

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

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');


Route::prefix('apex')->group(function () {
    Route::get('/', function () { return redirect(route('boost')); })->name('apex');
    Route::post('boost', [GamesController::class, 'boost_store'])->name('boost.store');

    Route::get('boost', [GamesController::class, 'boost'])->name('boost');
    Route::get('arena', [GamesController::class, 'arena'])->name('arena');
    Route::get('badges', [GamesController::class, 'badges'])->name('badges');
});

Route::get('/order/{orderID}', [GamesController::class, 'order'])->name('order');



    Route::get('/booster/order/{orderID}', [BoostController::class, 'order'])->name('boost.order');
    Route::get('/orders', [GamesController::class, 'orders'])->name('orders');

    Route::get('/messages/{id}', [ChatController::class, 'getMessagesFor'])->name('messages');
    Route::post('/message/send', [ChatController::class, 'send'])->name('message.send');

});
