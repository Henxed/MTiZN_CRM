<?php

namespace App\Http\Controllers;

use App\Models\Boost;
use App\Models\Transaction;
use App\Models\UUID;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;

class BoostController extends Controller
{
    public function order($id) // Вся инфа о заказе
    {
        $order = Boost::where('orderID', $id)->with('status')->firstOrFail();


        if($order->booster_id  !== Auth::user()->id) {
            return abort(403);
        }

        $data = json_decode($order['data'], true);

        return Inertia::render('GameProfile/Booster', [
            'data' => $order,
            'rank' => [
                'current' => (int)$data['current'],
                'from' => (int)$data['from'],
                'to' => (int)$data['to']
            ],
            'account' => [
                'login' => $data['account']['login'] ?? '',
                'password' => $data['account']['password'] ?? ''
            ],
            'ranks' => Boost::ranks(),
            //'tran' => Transaction::findOrFail()
        ]);
    }
}
