<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class AreasLog extends Model
{

    protected $fillable = ['user_id', 'log', 'type'];

    static function setLog($user_id = null, $data, $type = null){
        $json = json_encode($data);

        $s = self::where('log', $json)->where('id', $user_id)->whereDate('created_at', Carbon::today()->toDateString())->count();

        if(!$s){
            self::create([
                'user_id' => $user_id,
                'log' => $json,
                'type' => $type
            ]);
        }
    }
}
