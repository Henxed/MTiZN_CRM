<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use App\Models\User;
use App\Models\Areas;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\AreasUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:users']);
    }

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

    public function create()
    {
        return Inertia::render('Settings/Users/Create', [
            'roles' => Role::get(),
            //'permissions' => Permission::get(),
            'regions' => Areas::select('id', 'region')->where('areas_id', NULL)->get()
        ]);
    }


    public function store(Request $request)
    {
        Request::validate([
            'name' => ['required', 'max:50'],
            'email' => ['required', 'max:50', 'email', Rule::unique('users')],
            'password' => ['nullable'],
            'role' => ['nullable'],
        ]);

        $user = User::create([
            'name' => Request::get('name'),
            'email' => Request::get('email'),
            'password' => Hash::make(Request::get('password'))
        ]);

        if($request->region){
            $RoleU = new AreasUser();
            $RoleU->areas_id = $request->region;
            $RoleU->user_id = $user->id;
            $RoleU->save();
        }

        if (Request::get('role')) {
            $user->roles()->sync(Request::get('role'));
        }

        return Redirect::route('users.index')->with('success', 'Пользователь создан.');

    }

    public function edit(User $user)
    {
        return Inertia::render('Settings/Users/Edit', [
            'users' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->roles->pluck('id'),
                //'permissions' => $user->permissions->flatten()->pluck('id'),
            ],
            'regions' => Areas::select('id', 'region')->where('areas_id', NULL)->get(),
            'roles' => Role::get(),
            'permissions' => Permission::get(),
        ]);
    }

    public function update(Request $request, User $user)
    {

        Request::validate([
            'name' => ['required', 'max:50'],
            'email' => ['required', 'max:50', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable'],
            'roles' => ['nullable'],
        ]);

        $user->update(Request::only('name', 'email'));
        $user->roles()->sync(Request::get('roles'));
        //$user->permissions()->sync(Request::get('permissions'));
        $user->areas()->sync($request->region);

        if (Request::get('password')) {
            $user->update(['password' => Hash::make(Request::get('password'))]);
        }

        return Redirect::back()->with('success', 'Пользователь обновлен');

        //$users = User::with('roles')->get();

        return redirect()->route("users.index");
    }

}
