<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistCategory extends Model
{
    protected $fillable = ['name', 'slug'];
    protected $table = 'regists_categories';
}
