<?php

namespace App\Http\Controllers;

use App\Models\Boost;
use App\Models\UUID;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;

class MapController extends Controller
{

    public function order($id) // Вся инфа о заказе
    {
        $order = Boost::where('orderID', $id)->with('status')->firstOrFail();

        if($order->user_id != null) {
            if(Auth::check()) {
                //\Illuminate\Support\Facades\Artisan::call('websockets:serve');
                if($order->user_id !== Auth::user()->id) {
                    return abort(403);
                }
            } else {
                return abort(401);
            }
        }

        $data = json_decode($order['data'], true);

        switch ($order->type_boost) {
            case 'Rank Boost':
                $ranks = Boost::ranks();
                break;
            case 'Arenas Rank Boost':
                $ranks = Boost::ranks_arena();
                break;

            default:
                $ranks = false;
                break;
        }

        return Inertia::render('GameProfile/Index', [
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
            'ranks' => $ranks,
            //'tran' => Transaction::findOrFail()
        ]);
    }

    public function setAccount(Request $request, $id) { // Сохранение аккаунта в заказе

        $boost = Boost::findOrFail($id);


        if(Auth::check()) {
            if($boost->user_id !== Auth::user()->id) {
                return abort(403);
            }elseif($boost->status_id < 5) {
                return abort(403);
            }
        } else {
            return abort(401);
        }

        $data = json_decode($boost->data, TRUE);

        if ($data['account'] ?? false) {
            unset($data['account']);
        }

        $data['account'] = [
            'login' => $request->login,
            'password' => $request->password,
        ];
        $boost->data = json_encode($data);
        $boost->save();


        return response()->json([$boost, $data]);
    }

    public function orders() // Все заказы
    {

        return Inertia::render('GameProfile/Orders', [
            'orders' => Boost::where('user_id', Auth::user()->id)
                        ->with('status')
                        ->paginate()
        ]);
    }

    public function boost_store(Request $request) // Добавление буста в базу
    {
        $data = [
            'from' => (int)$request->from,
            'to' => (int)$request->to,
            'current' => (int)$request->from,
        ];

        $id = UUID::v4();
        Boost::create([
            'user_id' => Auth::check() ? Auth::user()->id : null,
            'orderID' => $id ,
            'platform' => $request->platform,
            "price" => $request->price,
            "game" => $request->game,
            "type_boost" => $request->type_boost,
            "options" => json_encode($request->options),
            "data" => json_encode($data)
        ]);

        return Redirect::route('order', ['orderID' => $id ])->with(['success' => 'Заказ оформлен']);
    }


    public function boost() // калькулятор буста
    {
        return Inertia::render('Games/Apex/boost', [
            'ranks' => Boost::ranks(),
            'exts' => Boost::exts()
        ]);
    }

    public function arena()
    {
        return Inertia::render('Games/Apex/arena', [
            'ranks' =>  Boost::ranks_arena(),
            'exts' => Boost::exts()
        ]);
    }
    public function badges()
    {
        return Inertia::render('Games/Apex/badges', [
            'badges' =>  Boost::badges(),
            'exts' => Boost::exts()
        ]);
    }
}
