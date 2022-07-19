<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Builder;

class Safety extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function scopeRegion(Builder $query, $value): Builder
    {
        return $query->whereIn('enterprises.area_id', explode(',', $value));
    }

    static function dynFields($list_arr){
        $count_num = 0;
        $mostRecent = [];
        $arr = array_filter($list_arr, function($value) { return !is_null($value['count']) && $value['count'] !== ''; });

        foreach ($list_arr as $value) {
            $count_num += intval($value['count']);
            $mostRecent[] = $value['date'];
        }

        $max = max(array_map('strtotime', $mostRecent));

        return ['list' => $arr, 'date' => $max ? date('Y-m-d', $max) : null, 'count' => $count_num ? $count_num : null];
    }
}
