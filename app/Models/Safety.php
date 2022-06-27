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
}
