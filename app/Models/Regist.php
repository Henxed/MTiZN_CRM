<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Regist extends Model
{
    protected $fillable = ['title','npa','depart', 'status_id', 'category_id', 'file', 'term'];

    public function status()
    {
        return $this->hasMany(Status::class, 'code', 'status_id');
    }

    public function category()
    {
        return $this->hasMany(RegistCategory::class, 'id', 'category_id');
    }

    public function files()
    {
        return $this->hasMany(File::class, 'regist_id');
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('title', 'like', '%'.$search.'%')
                ->orWhere('npa', 'like', '%'.$search.'%');
        });
    }
}
