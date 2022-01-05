<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{

    protected $fillable = ['key', 'value'];
    public $timestamps = false;
    public $primaryKey = null;

    protected function get($key)
	{
        $e = Setting::where('key', $key)->first();
        return $e->value;
	}

	/**
	 * @TODO Настройки - установка
	 */
	protected function set($key, $value = null)
	{
        return Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
	}
}
