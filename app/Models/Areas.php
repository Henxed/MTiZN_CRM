<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Areas extends Model
{
    protected $fillable = ['leader','population','region','city','area','amw','subject','contract','lvl','unemployed','vacancy', 'tension','employed','cripple','cripple_worked','commissions','commissions_c','commissions_t','in_employment'];

    public function selsoviet()
    {
        return $this->hasMany(Selsoviet::class, 'area_id');
    }

    public function enterprises()
    {
        return $this->hasMany(Enterprises::class, 'area_id');
    }

    public function extra()
    {
        return $this->hasOne(AreasExtra::class, 'area_id');
    }

    public function distance()
    {
        return $this->hasMany(AreasDistance::class, 'area_id');
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

    public function scopeExclude($query, $value = [])
    {
        return $query->select(array_diff($this->columns, (array) $value));
    }
}
