<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Model\Areas;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Model\AreasUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

        $users = User::with('roles')->paginate(25);

        return view('cp.users.index')->with(compact('users'));
    }

    public function user_new()
    {
        $roles = Role::get();
        $regions = Areas::select('id', 'region')->where('areas_id', NULL)->get();

        return view('cp.users.userAdd')->with(compact('roles', 'regions'));
    }

    public function user_add(Request $request)
    {
        $roles = Role::get();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'api_token' => Str::random()
        ]);

        if($request->role){
            $RoleU = new RoleUser();
            $RoleU->role_id = $request->role;
            $RoleU->user_id = $user->id;
            $RoleU->save();
        }
        if($request->region){
            $RoleU = new AreasUser();
            $RoleU->areas_id = $request->region;
            $RoleU->user_id = $user->id;
            $RoleU->save();
        }

        return redirect()->route("users");
    }

    public function user_edit($id)
    {
        $user = User::with('roles')->where('id', $id)->first();
        $roles = Role::get();
        $regions = Areas::select('id', 'region')->where('areas_id', NULL)->get();

        return view('cp.users.userEdit')->with(compact('user', 'roles', 'regions'));
    }

    public function user_update(Request $request)
    {
        $user = User::where('id', $request->id)->first();

        $user->name = $request->name;
        $user->email = $request->email;
        if(!empty($request->input('password'))) {
          $user->password = Hash::make($request->password);
        }
        $user->api_token = Str::random();
        $user->save();

        $user->roles()->sync($request->roles);
        $user->areas()->sync($request->region);



        //$users = User::with('roles')->get();

        return redirect()->route("users");
    }


    // Роли и права
    public function roles() //все Роли в index
    {
        if (!auth()->user()->can('cp.roles')) {
            return abort(403);
        }

        $roles = Role::all();

        return view('cp.users.roles.index', compact('roles'));
    }

    public function role_create() //Страница создание роли
    {
        if (!auth()->user()->can('cp.roles')) {
            return abort(403);
        }

        $permissions = Permission::get();

        return view('cp.users.roles.form', compact('permissions'));
    }

    public function role_edit($id) //Страница редактирование роли
    {
        if (!auth()->user()->can('cp.roles')) {
            return abort(403);
        }
        $role = Role::with(['permissions' => function ($query) {
            $query->orderBy('name');
        }])
            ->where('id', $id)
            ->first();

        $permissions = Permission::get();

        return view('cp.users.roles.form')->with(compact('role', 'permissions'));
    }

    public function role_store(Request $request) //сраница снесения данных
    {
        if (!auth()->user()->can('cp.roles')) {
            return abort(403);
        }

        if (isset($request->id)) {
            $role = Role::findOrFail($request->id);
            $role->update($request->except('permission'));
            $permissions = $request->permission ? $request->permission : [];
            $role->syncPermissions($permissions);

            return redirect()->route('roles');
        } else {
            $role = Role::create($request->except('permission'));
            $permissions = $request->permission ? $request->permission : [];
            $role->givePermissionTo($permissions);

            return view('cp.users.roles.form')->with(compact('permissions'));
        }
    }

    public function role_destroy($id)
    {
        if (!auth()->user()->can('cp.roles')) {
            return abort(403);
        }
        $role = Role::findOrFail($id);
        $role->delete();

        return redirect()->route('cp.users.roles.index');
    }

    //----------------------------------------------
    // Разрешения (права доступа)
    public function permissions()
    {
        if (!auth()->user()->can('cp.permission')) {
            return abort(403);
        }
        $permissions = Permission::orderBy('name')->get();

        return view('cp.users.permissions.index')->with(compact('permissions'));
    }

    public function permission_create()
    {
        if (!auth()->user()->can('cp.permission.add')) {
            return abort(403);
        }

        $roles = Role::get();

        return view('cp.users.permissions.form')->with(compact('roles'));
    }

    public function permission_edit($id)
    {
        if (!auth()->user()->can('cp.permission')) {
            return abort(403);
        }
        $permission = Permission::findOrFail($id);
        $roles = Role::get();

        return view('cp.users.permissions.form')->with(compact('permission', 'roles'));
    }

    public function permission_update(Request $request)
    {
        if (!auth()->user()->can('cp.permission.add')) {
            return abort(403);
        }

        if (isset($request->id)) {
            $permission = Permission::findOrFail($request->id);
            $permission->update($request->except('roles'));
        } else {
            $permission = Permission::create($request->except('roles'));
        }

        $roles = $request->roles ? $request->roles : [];
        $permission->syncRoles($roles);

        return redirect()->route('permissions');
    }

    public function permission_destroy($id)
    {
        if (!auth()->user()->can('cp.permission.destroy')) {
            return abort(403);
        }

        $permission = Permission::findOrFail($id);
        $permission->delete();

        return redirect()->route('permissions');
    }

    public function destroy(User $user, $id)
    {
        if (!auth()->user()->can('cp.users')) {
            return abort(403);
        }
        User::destroy((int)$id); //удаление

        return redirect()->route("users");
    }

}
