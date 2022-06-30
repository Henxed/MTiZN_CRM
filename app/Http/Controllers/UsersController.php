<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use App\Models\User;
use App\Models\Areas;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\AreasUser;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class UsersController extends Controller
{

    public function index()
    {
        return Inertia::render('Settings/Users/Index', [
            'filters' => Request::all('search'),
            'users' => User::orderBy('name')
            ->with('roles')
            ->filter(Request::only('search'))
            ->paginate()
        ]);
    }

    public function show($username)
    {
        return Inertia::render('Profile/User', [
            'user_info' => User::select('departments.name as d_name', 'users.*')
            ->leftJoin('department_user', function($join) {
                $join->on('department_user.user_id', 'users.id');
            })
            ->leftJoin('departments', function($join) {
                $join->select('departments.name')->on('departments.id', 'department_id');
            })
            ->where('username', $username)
            ->firstOrFail()
        ]);
    }

    public function create()
    {
        return Inertia::render('Settings/Users/Create', [
            'roles' => Role::get(),
            'permissions' => Permission::get(),
            'regions' => Areas::select('id', 'region')->get()
        ]);
    }


    public function store(Request $request)
    {
        Request::validate([
            'name' => ['required', 'max:50'],
            'username' => ['required', 'max:50', Rule::unique('users')],
            'email' => ['required', 'max:50', 'email', Rule::unique('users')],
            'password' => ['required', 'min:8'],
        ]);

        $user = User::create([
            'name' => Request::get('name'),
            'username' => Request::get('username'),
            'email' => Request::get('email'),
            'password' => Hash::make(Request::get('password'))
        ]);

        $user->roles()->sync(Request::get('roles'));
        $user->permissions()->sync(Request::get('permissions'));
        $user->areas()->sync(Request::get('regions'));

        return Redirect::route('users.index')->with('success', 'Пользователь создан.');

    }

    public function edit(User $user)
    {
        return Inertia::render('Settings/Users/Edit', [
            'users' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'roles' => $user->roles->pluck('id'),
                'permissions' => $user->permissions->flatten()->pluck('id'),
                'areas' => $user->areas->flatten()->pluck('id')
            ],
            'regions' => Areas::select('id', 'region')->get(),
            'roles' => Role::get(),
            'permissions' => Permission::get(),
        ]);
    }

    public function update(User $user)
    {

        Request::validate([
            'name' => ['required', 'max:50'],
            'username' => ['required', 'max:50', Rule::unique('users')],
            'email' => ['required', 'max:50', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable'],
        ]);

        $user->update(Request::only('name', 'username', 'email'));
        $user->roles()->sync(Request::get('roles'));
        $user->permissions()->sync(Request::get('permissions'));
        $user->areas()->sync(Request::get('regions'));

        if (Request::get('password')) {
            $user->update(['password' => Hash::make(Request::get('password'))]);
        }

        return Redirect::route('users.index')->with('success', 'Пользователь обновлен');

    }

    public function destroy(User $user)
    {
        if(strtolower($user->username) === 'henxed' || $user->id === 1){
            return Redirect::back()->with('error', "Нельзя удалить разработчика!");
        }

        $user->destroy($user->id); //удаление

        return Redirect::route('users.index')->with('success', "Пользователь удален!");
    }

    public function notification_popup(){
        $n = Notification::where('user_id', Auth::user()->id)->orderBy('created_at', 'DESC')->paginate();

        return response()->json($n);
    }
    public function notification_read($id){
        $n = Notification::read($id);

        return response()->json($n);
    }
    public function notification_remove($id){
        $n = Notification::remove($id);

        return response()->json($n);
    }
}
