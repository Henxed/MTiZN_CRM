<?php

namespace App\Models;

use App\Events\Notify;
use Exception;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use HasRoles;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    protected function defaultProfilePhotoUrl()
    {
        return 'https://ui-avatars.com/api/?name='.urlencode($this->name).'&color=f3f6fd&background=94a3b8';
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('name', 'like', '%'.$search.'%')
                ->orWhere('email', 'like', '%'.$search.'%');
        });
    }

    public function areas()
    {
        return $this->belongsToMany(Areas::class);
    }


    public function All_Permissions()
    {
        $perm_team = Arr::pluck(DB::select('SELECT p.name FROM permissions p LEFT JOIN department_permission dp ON dp.permission_id = p.id LEFT JOIN department_user du ON du.department_id = dp.department_id WHERE du.user_id = ?', [Auth::user()->id]), 'name');
        $perm_user = Auth::user()->getAllPermissions()->pluck('name');
        $all_perm = $perm_user->merge($perm_team)->unique()->values();

        return $all_perm;
    }

    public function hasPermission($permission) {
        $perm_team = Arr::pluck(DB::select('SELECT p.name FROM permissions p LEFT JOIN department_permission dp ON dp.permission_id = p.id LEFT JOIN department_user du ON du.department_id = dp.department_id WHERE du.user_id = ?', [Auth::user()->id]), 'name');
        $perm_user = Auth::user()->getAllPermissions()->pluck('name');
        $all_perm = $perm_user->merge($perm_team)->unique();
        foreach($all_perm as $item){
            if($permission === $item) {
                return true;
            }
        }
        return false;
    }

    public function checkArea($area_id = null)
    {
        if(Auth::user()->hasRole('super-admin') ? false : true){
            if(Auth::user()->can('region.edit') ? false : true){
                if(!in_array((int)$area_id, AreasUser::where('user_id', Auth::user()->id)->pluck('areas_id')->all(), true) ){
                    return true;
                }
            }
        }

        return false;
    }

    static function sendNotify($title, $message, $user_id = null, $url = null, $type = 'USER'){
        $data = Notification::create([
            'id' => (string) Str::uuid(), //генерирует ид: e00dd748-d2b7-4289-b7d0-36366198545c
            'type' => $type, //может быть null
            'user_id' => $user_id, //ид кому, может быть null
            'data' => json_encode([
                'title' => $title,
                'message' => $message,
                'url' => $url
            ], JSON_UNESCAPED_UNICODE), //можно добавить всё что угодно
            'read_at' => null
        ]); //Формируем данные уведомления и сохраняем в БД

        broadcast(new Notify($data)); //Отправляем пуш на вебсокет
    }
}
