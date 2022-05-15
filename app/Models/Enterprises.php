<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Enterprises extends Model
{
    protected $guarded = array('id');

    public function enterprises()
    {
        return $this->hasMany(Enterprises::class);
    }
    public function children()
    {
        return $this->hasMany(Enterprises::class)->with('enterprises');
    }
    public function status()
    {
        return $this->hasOne(Status::class, 'code', 'status_id');
    }
}
