<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Notification extends Model
{
    protected $fillable = ['id', 'type', 'user_id', 'data', 'read_at'];
    public $incrementing = false; //Если нет автоинкримента, то мы генерируем тектовый ид (char)

    /**
     * Отметить уведомление пользователя, как "прочитано" по ид
     *
     * @param  string $id
     * @return bool
     */
    static function read(string &$id): bool{
        $n = Notification::where('id', $id)->where('user_id', Auth::user()->id)->update([
                'read_at' => now()
            ]);

        return $n;
    }


   /**
    * Удаление уведомления пользователя по ид
    * @param string $id
    * @return bool
    */
    static function remove(string &$id): bool{
        $n = Notification::where('id', $id)->where('user_id', Auth::user()->id)->delete();

        return $n;
    }
}
