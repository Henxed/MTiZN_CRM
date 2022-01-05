<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Areas extends Model
{
    protected $fillable = ['leader','population','region','city','area','amw','subject','contract','lvl','unemployed','vacancy', 'tension','employed','cripple','cripple_worked','commissions','commissions_c','commissions_t','in_employment'];

    public function selsoviet()
    {
        return $this->hasMany('App\Model\Selsoviet', 'area_id');
    }

    public function enterprises()
    {
        return $this->hasMany('App\Model\Enterprises', 'area_id');
    }

    public function extra()
    {
        return $this->hasOne('App\Model\AreasExtra', 'area_id');
    }

    public function users()
    {
        $this->belongsToMany(User::class);
    }
    public function areas()
    {
        return $this->hasMany(Areas::class);
    }
    public function areas_children()
    {
        return $this->hasMany(Areas::class)->with('areas');
    }
}
