<?php

namespace App\Models;

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
}
