<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Support\Facades\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Redirect;

class RoleController extends Controller
{
    public function index()
    {
        return Inertia::render('Settings/Access/Roles/Index', [
            'filters' => Request::all('search'),
            'roles' => Role::orderBy('name')
            ->paginate()
        ]);
    }

    public function create()
    {
        return Inertia::render('Settings/Access/Roles/Create', [
            'permissions' => Permission::get()
        ]);
    }

    public function store()
    {
        Request::validate([
            'name' => ['required', 'max:50'],
        ]);

        $role = Role::create([
            'name' => Request::get('name')
        ]);

        $role->syncPermissions(Request::get('permission'));


        return Redirect::route('roles')->with('success', 'Роль создана.');
    }

    public function edit(Role $role)
    {
        return Inertia::render('Settings/Access/Roles/Edit', [
            'role' => [
                'id' => $role->id,
                'name' => $role->name,
                'permission' => $role->permissions->flatten()->pluck('id')->all()
            ],
            'permissions' => Permission::all()
        ]);
    }


    public function update(Role $role)
    {
        Request::validate([
            'name' => ['required', 'max:50'],
        ]);

        $role->update(Request::only('name'));
        $role->syncPermissions(Request::get('permission'));


        return Redirect::back()->with('success', 'Роль обновлена');
    }

    public function destroy(Role $role)
    {
        $role->delete();

        return Redirect::route('roles.index')->with('success', 'Роль удалена.');
    }
}
