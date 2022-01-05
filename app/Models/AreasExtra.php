<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AreasExtra extends Model
{
    protected $table = 'areas_extra';
    protected $fillable = ['school', 'vvuz', 'ssuz', 'detdom'];

}
