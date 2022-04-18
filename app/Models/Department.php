<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;

class Department extends Model
{
    protected $guarded = array('id');

    public function owner()
    {
        return $this->hasOne(User::class, 'id', 'owner');
    }

    public function workers()
    {
        return $this->belongsToMany(User::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }
}
