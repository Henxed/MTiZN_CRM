<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class AreasLog extends Model
{

    protected $fillable = ['user_id', 'area_id', 'log', 'type'];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    static function setLog($user_id, $area_id, $data, $type){
        $check = false;

        // Собираем из Area & AreaExtra те же поля, что и в форме
        if($type === 'USER_LOG') {
            $area = Areas::leftJoin('areas_extra', function($join) {
                $join->on('areas.id', '=', 'areas_extra.area_id');
            })->select(['areas.id', 'region', 'city', 'leader', 'population', 'area', 'amw', 'subject', 'contract', 'lvl', 'unemployed', 'unemployed_percent', 'vacancy', 'tension', 'employed', 'cripple', 'cripple_worked', 'commissions_c', 'commissions_t', 'commissions', 'in_employment', 'school', 'vvuz', 'ssuz', 'detdom', 'nou', 'ur',  'jobs'])
            ->where('areas.id', $data['id'])->get();

            $diff_bd = array_diff($area[0]->toArray(), $data);
            $diff_form = array_diff($data, $area[0]->toArray());

            $build = [
                'old' => $diff_bd,
                'new' => $diff_form //Данные с формы, они в массиве
            ];

            $check = $build['old'] !== $build['new'];
        }
        if($type === 'STATS_MONTH') {
            $build = $data;
            $check = true;
        }

        if($check){

            self::create([
                'user_id' => $user_id,
                'area_id' => $area_id,
                'log' => json_encode($build, JSON_UNESCAPED_UNICODE),
                'type' => $type
            ]);
        }
    }
}
