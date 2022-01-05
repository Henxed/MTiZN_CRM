<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Model\Role;
use App\Model\Permission;

class Tpl extends Model
{
    protected $table = 'tpl';
    protected $fillable = ['name', 'content', 'permission'];

    public function permissions()
    {
        return $this->hasManyThrough(Permission::class, RoleUser::class,'role_id');
    }
}
